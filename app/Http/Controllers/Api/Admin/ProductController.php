<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            // Find product if old
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

            if (count($request->input('attributes')) > 0){
                //Creating attributes
                foreach ($request->input('attributes') as $attribute){
                    if ($attribute['id'] === null){
                        $attribute = Attribute::create([
                            'name' => $attribute['name'],
                            'slug' => Str::slug($attribute['name']),
                        ]);

                        if (empty($attribute)){
                            throw new Exception('Could not create attribute');
                        }
                        foreach ($attribute['values'] as $value){
                            if ($value['id'] === null){
                                $value = $attribute->values()->create([
                                    'name' => $value['name'],
                                    'slug' => Str::slug($value['name']),
                                ]);
                                if (empty($value)){
                                    throw new Exception('Could not create value');
                                }
                            }
                        }
                    }
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

            dd($request->input('attributes'));





            DB::commit();


            return json_response('Success', ResponseAlias::HTTP_OK, '', 'Product created successfully', true);
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
}
