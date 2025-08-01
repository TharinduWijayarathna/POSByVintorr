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
        Schema::table('pos_order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');
            $table->text('description')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_order_items', function (Blueprint $table) {
            //
        });
    }
};
