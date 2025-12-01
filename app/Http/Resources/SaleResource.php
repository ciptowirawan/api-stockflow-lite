<?php

namespace App\Http\Resources;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\SaleDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_date' => $this->order_date,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'grand_total' => $this->grand_total,
            'paid_amount' => $this->paid_amount,
            'created_by' => $this->created_by,
            'details' => SaleDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at,
        ];
    }
}
