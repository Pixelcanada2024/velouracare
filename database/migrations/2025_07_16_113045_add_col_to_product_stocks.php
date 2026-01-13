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
            $table->unsignedTinyInteger('has_special_discount')->default(0); //0->false - 1 ->true
            $table->string('discount_type', 20)->default('percent'); // 'percent' or 'amount'
            $table->decimal('discount', 10, 2)->default(0.00);
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
