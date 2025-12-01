<?php

namespace App\Models;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use SoftDeletes, Auditable;

    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function details() {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
