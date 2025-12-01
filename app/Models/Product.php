<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Category;
use App\Models\StockDetail;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes, Auditable;

    protected $guarded = ['id'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function stock() {
        return $this->hasOne(Stock::class, 'stock_id', 'id');
    }

    public function stock_details() {
        return $this->hasMany(StockDetail::class, 'product_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
