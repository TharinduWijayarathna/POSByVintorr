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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('status')->default(0);
            $table->integer('po_status')->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->bigInteger('currency_id')->nullable();
            $table->text('note')->nullable();
            $table->string('ref_no')->nullable();
            $table->date('date')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_mobile')->nullable();
            $table->string('po_link')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
