<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = $request->user()->orders()->with('order_masters');
        $orders = build_collection_response($request, $orders);
        $orders = OrderResource::collection($orders);

        return collection_response($orders, 'Success', ResponseAlias::HTTP_OK, 'Order list get successful');
    }

    public function show(Request $request, $id){
        // Todo return auth user specific order
    }

    /**
     * Order Store
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'item_sub_total' => 'required|numeric',
            'discount' => 'required|numeric',
            'item_total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.id' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
            'items.*.total_price' => 'required|numeric'
        ]);

        if ($validator->fails()){
            return validation_response($validator->errors()->getMessages());
        }

        try {
            DB::beginTransaction();
            $order = $request->user()->orders()->create([
                'item_quantity' => count($request->input('items')),
                'item_sub_total' => $request->input('item_sub_total'),
                'discount' => $request->input('discount'),
                'item_total' => $request->input('item_total'),
                'status' => !empty($request->input('status')) ? $request->input('status') : Order::Status['Active']
            ]);

            if (empty($order)){
                throw new Exception('Could not create order');
            }

            foreach ($request->input('items') as $item){
                $orderMaster = $order->order_masters()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price']
                ]);

                $product = Product::where('id', $item['id'])->first();
                if (empty($product)){
                    throw new Exception('Could not find product');
                }

                $productU = $product->update([
                    'quantity' => $product->quantity - $item['quantity']
                ]);
                if (empty($productU)){
                    throw new Exception('Could not update product');
                }

                if (empty($orderMaster)){
                    throw new Exception('Could not create order master');
                }
            }

            DB::commit();

            $order = $order->load('order_masters');
            $order = new OrderResource($order);
            return json_response('Success', ResponseAlias::HTTP_OK, $order, 'Order created successfully', true);
        } catch (Exception $exception){
            DB::rollBack();
            return json_response('Failed', ResponseAlias::HTTP_PAYMENT_REQUIRED, '', $exception->getMessage(), false);
        }
    }

    public function update(Request $request, $id){
        // Todo update order
    }

    public function destroy(Request $request, $id){
        // Todo delete order
    }

}
