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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->integer('type')->nullable();
            $table->date('date')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('payment_code')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->string('client')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('sign')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
