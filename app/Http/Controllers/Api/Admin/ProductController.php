<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Value;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    /**
     * Store Product
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer',
            'name' => 'nullable|unique:products',
            'attributes' => 'nullable|array',
            'sku' => 'required:max:25',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()){
            return validation_response($validator->errors()->getMessages());
        }

        try {
            DB::beginTransaction();

            // Product
            // If old product
            if (!empty($request->input('id'))){
                $product = Product::where('id', $request->input('id'))->first();
                if (empty($product)){
                    throw new Exception('Could not find product');
                }
            }
            // Creating product if new
            else {
                $product = Product::create([
                    'name' => $request->input('name'),
                    'slug' => Str::slug($request->input('name')),
                    'status' => Product::Status['Active']
                ]);
                if (empty($product)){
                    throw new Exception('Could not create product');
                }
            }

            // Creating product sku
            $sku = $product->skus()->create([
                'name' => $request->input('sku'),
                'price' => $request->input('price')
            ]);
            if (empty($sku)){
                throw new Exception('Could not create sku');
            }

            if (count($request->input('attributes')) > 0){
                foreach ($request->input('attributes') as $attr){
                    // Creating attribute if not exist
                    if (empty($attr['id'])){
                        $attribute = Attribute::create([
                            'name' => $attr['name'],
                            'slug' => Str::slug($attr['name'])
                        ]);
                        if (empty($attribute)){
                            throw new Exception('Could not create attribute');
                        }

                        if (count($attr['values']) > 0){
                            if (!$this->_store_variants($attr, $attribute, $product, $sku)){
                                throw new Exception('Variation is not created');
                            }
                        }

                    } else{
                        $attribute = Attribute::where('id', $attr['id'])->first();
                        if (empty($attribute)){
                            throw new Exception('Could not find attribute');
                        }
                        if (!$this->_store_variants($attr, $attribute, $product, $sku)){
                            throw new Exception('Variation is not created');
                        }
                    }
                }
            }
            DB::commit();
            $product = $product->load(['skus' => function($q){
                return $q->with(['product_variants' => function($qq){
                    return $qq->with('attribute', 'value');
                }]);
            }]);

            $product = new ProductResource($product);
            return json_response('Success', ResponseAlias::HTTP_OK, $product, 'Product created successfully', true);
        } catch (Exception $exception) {
            DB::rollBack();
            return json_response('Failed', ResponseAlias::HTTP_PAYMENT_REQUIRED, '', $exception->getMessage(), false);
        }

    }

    /**
     * Update Product
     * @param Request $request
     * @param $slug
     * @return JsonResponse
     */
    public function update(Request $request, $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)->first();
        if (empty($product)){
            return json_response('Failed', ResponseAlias::HTTP_NOT_FOUND, '', 'Product not found', false);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,'.$product->id,
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'low_stock' => 'required|integer'
        ]);

        if ($validator->fails()){
            return validation_response($validator->errors()->getMessages());
        }

        try {
            $productUpdate = $product->update([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'low_stock' => $request->input('low_stock'),
                'status' => $request->input('status') ? $request->input('status') : Product::Status['Active']
            ]);

            if (empty($productUpdate)){
                throw new Exception('Could not update product');
            }

            $product = new ProductResource($product->fresh());
            return json_response('Success', ResponseAlias::HTTP_OK, $product, 'Product updated successfully', true);
        } catch (Exception $exception) {
            return json_response('Failed', ResponseAlias::HTTP_NOT_FOUND, '', $exception->getMessage(), false);
        }
    }

    /**
     * Delete Product
     * @param Request $request
     * @param $slug
     * @return JsonResponse
     */
    public function destroy(Request $request, $slug): JsonResponse
    {
        try {
            $product = Product::where('slug', $slug)->first();
            if (empty($product)){
                throw new Exception('Could not find product');
            }

            $product->delete();
            return json_response('Success', ResponseAlias::HTTP_OK, '', 'Product deleted successfully', true);
        } catch (Exception $exception){
            return json_response('Failed', ResponseAlias::HTTP_NOT_FOUND, '', $exception->getMessage(), false);
        }
    }


    private function _store_variants($attr, $attribute, $product, $sku){
        try{
            foreach ($attr['values'] as $val){
                // Creating value if not exist
                if (empty($val['id'])){
                    $value = $attribute->values()->create([
                        'name' => $val['name'],
                        'slug' => Str::slug($val['name'])
                    ]);

                    if (empty($value)){
                        throw new Exception('Could not create value');
                    }
                    $product_variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku_id' => $sku->id,
                        'attribute_id' => $attribute->id,
                        'value_id' => $value->id
                    ]);
                    if (empty($product_variant)){
                        throw new Exception('Could not create product variant');
                    }
                } else {
                    $value = Value::where('id', $val['id'])->first();
                    if (empty($value)){
                        throw new Exception('Could not find value');
                    }
                    $product_variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku_id' => $sku->id,
                        'attribute_id' => $attribute->id,
                        'value_id' => $value->id
                    ]);
                    if (empty($product_variant)){
                        throw new Exception('Could not create product variant');
                    }
                }
            }
            return true;
        } catch (Exception $exception){
            return false;
        }

    }
}
