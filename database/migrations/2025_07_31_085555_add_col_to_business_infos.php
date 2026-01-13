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
    Schema::table('business_infos', function (Blueprint $table) {
      // Drop old columns
      $table->dropColumn([
        'position',
        'owner_state_issued_id',
        'company_name',
        'company_phone',
        'business_address',
        'company_state_id',
        'company_country_id',
        'company_postal_code',
        'smoke_shop_picture',
        'shop_address',
        'shop_country_id',
        'shop_state_id',
        'shop_postal_code',
        'fed_tax_id',
        'fed_tax_file',
        'resale_certificate',
        'resale_certificate_file',
      ]);

      $table->string('address_line_one')->nullable();
      $table->string('address_line_two')->nullable();
      $table->unsignedBigInteger('country_id')->nullable();
      $table->string('state')->nullable();
      $table->string('city')->nullable();
      $table->string('postal_code')->nullable();

      $table->string('company_name')->nullable();
      $table->string('store_link')->nullable();
      $table->string('business_id')->nullable();

      $table->json('business_proof_assets')->nullable();

      $table->unsignedTinyInteger('business_type')->nullable(); // 0 'offline',  1 'online',  2 'both
      $table->unsignedTinyInteger('find_us')->nullable(); // 0=Google/Yahoo/Bing, 1=Social media ads, 2=Online webinar, 3=Offline event, 4=Others

    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('business_infos', function (Blueprint $table) {
      //
    });
  }
};
