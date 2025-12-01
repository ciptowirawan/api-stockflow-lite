<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'product'     => new ProductResource($this->whenLoaded('product')),
            'qty'         => $this->qty,
            'price'       => $this->price,
            'total_price' => $this->total_price,
        ];
    }
}
