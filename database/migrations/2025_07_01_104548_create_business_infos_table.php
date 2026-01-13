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
        Schema::create('business_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('position')->nullable();
            $table->string('owner_state_issued_id')->nullable();

            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('business_address')->nullable();
            $table->unsignedBigInteger('company_state_id')->nullable();
            $table->unsignedBigInteger('company_country_id')->nullable();
            $table->string('company_postal_code')->nullable();
            $table->string('smoke_shop_picture')->nullable();

            $table->string('shop_address')->nullable();
            $table->unsignedBigInteger('shop_country_id')->nullable();
            $table->unsignedBigInteger('shop_state_id')->nullable();
            $table->string('shop_postal_code')->nullable();

            $table->string('fed_tax_id')->nullable();
            $table->string('fed_tax_file')->nullable();
            $table->string('resale_certificate')->nullable();
            $table->string('resale_certificate_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_infos');
    }
};
