<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\PurchaseDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'purchase_date' => $this->purchase_date,
            'supplier'  => new SupplierResource($this->whenLoaded('supplier')),
            'grand_total'  => $this->grand_total,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'details' => PurchaseDetailResource::collection($this->whenLoaded('details')),
            'created_at'    => $this->created_at,
        ];
    }
}
