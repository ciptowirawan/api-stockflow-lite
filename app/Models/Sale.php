<?php

namespace App\Models;

use App\Auditable;
use App\Models\Customer;
use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes, Auditable;

    protected $guarded = ['id'];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function details() {
        return $this->hasMany(SaleDetail::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
