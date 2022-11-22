<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    /**
     * List Product
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $products = Product::where('status', Product::Status['Active'])->with(['product_variants' => function($q){
            return $q->with('sku', 'attribute', 'value');
        }]);
        $products = build_collection_response($request, $products);
        $products = ProductResource::collection($products);
        return collection_response($products, 'Success', ResponseAlias::HTTP_OK, 'Products get successfully');
    }

    /**
     * Show Product
     * @param Request $request
     * @param $slug
     * @return JsonResponse
     */
    public function show(Request $request, $slug): JsonResponse
    {
        try {
            $product = Product::where('slug', $slug)->first();
            if (empty($product)){
                throw new Exception('Could not find product');
            }

            $product = new ProductResource($product);
            return json_response('Success', ResponseAlias::HTTP_OK, $product, 'Product get successfully', true);
        } catch (Exception $exception){
            return json_response('Failed', ResponseAlias::HTTP_NOT_FOUND, '', $exception->getMessage(), false);
        }
    }
}
