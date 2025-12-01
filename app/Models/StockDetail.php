<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
    use SoftDelete, Auditable;

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
