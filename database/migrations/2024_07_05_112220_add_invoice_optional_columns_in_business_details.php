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
        Schema::table('business_details', function (Blueprint $table) {
            $table->integer('is_product_code')->default(0)->after('color_code');
            $table->integer('is_product_description')->default(0)->after('is_product_code');
            $table->integer('is_line_number')->default(0)->after('is_product_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_details', function (Blueprint $table) {
            //
        });
    }
};
