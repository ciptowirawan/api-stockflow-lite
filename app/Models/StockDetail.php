<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockDetail extends Model
{
    use Auditable;

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
