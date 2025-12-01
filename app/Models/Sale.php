<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function details() {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }
}
