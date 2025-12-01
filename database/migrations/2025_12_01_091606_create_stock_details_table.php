<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');

            $table->integer('quantity');            
            $table->integer('stock_after');            
            $table->enum('type', ['P_RET', 'S_RET', 'ADJ'])->comment('P_RET: Retur Pembelian, S_RET: Retur Penjualan, ADJ: Stock Opname/Koreksi');
            $table->foreignId('created_by')->constrained('users');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_details');
    }
};
