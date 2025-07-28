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
        Schema::create('quotation_item_footer_parameters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quotation_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('parameter_id')->nullable();
            $table->integer('order')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_item_footer_parameters');
    }
};
