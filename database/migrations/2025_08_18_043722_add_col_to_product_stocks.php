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
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sku');
            $table->unsignedInteger('box_qty')->nullable()->after('qty');
            $table->string('lead_time')->nullable()->after('box_qty');
            $table->unsignedInteger('made_in_country_id')->nullable()->after('lead_time');
            $table->unsignedTinyInteger('available_document')->nullable()->after('made_in_country_id');
            // 0 Commercial Invoice
            // 1 MSDS
            // 2 MSDS, Commercial Invoice 
            $table->unsignedDecimal('msrp', 10, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
};
