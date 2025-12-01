<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function purchase() {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
