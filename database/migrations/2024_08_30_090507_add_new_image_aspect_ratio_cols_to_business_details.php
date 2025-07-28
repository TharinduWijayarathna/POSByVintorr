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
            $table->decimal('business_logo_ratio')->nullable()->after('image_id');
            $table->decimal('bill_logo_ratio')->nullable()->after('bill_image_id');
            $table->decimal('invoice_logo_ratio')->nullable()->after('invoice_image_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_details', function (Blueprint $table) {
            $table->dropColumn('business_logo_ratio');
            $table->dropColumn('bill_logo_ratio');
            $table->dropColumn('invoice_logo_ratio');
        });
    }
};
