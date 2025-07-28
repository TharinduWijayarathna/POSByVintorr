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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->bigInteger('expense_category_id')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->bigInteger('image_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
