<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'itemQuantity' => $this->item_quantity,
            'itemSubTotal' => $this->item_sub_total,
            'discount' => $this->discount,
            'itemTotal' => $this->item_total,
            'status' => $this->status,
            'statusLabel' => $this->status_label,
            'orderMasters' => OrderMasterResource::collection($this->whenLoaded('order_masters'))
        ];
    }
}
