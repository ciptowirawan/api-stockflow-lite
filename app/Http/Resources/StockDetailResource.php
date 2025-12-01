<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StockDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'product_id'  => $this->product_id,
            'quantity'    => $this->quantity,
            'stock_after' => $this->stock_after,
            'type'        => $this->type,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];
    }
}
