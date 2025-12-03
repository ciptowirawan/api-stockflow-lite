<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{

    protected $guarded = ['id'];

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
