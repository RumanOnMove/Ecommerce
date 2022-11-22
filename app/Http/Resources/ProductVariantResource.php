<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'sku' => new SkuResource($this->whenLoaded('sku')),
            'attribute' => new AttributeResource($this->whenLoaded('attribute')),
            'value' => new ValueResource($this->whenLoaded('value'))
        ];
    }
}
