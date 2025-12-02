<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes, Auditable;

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
