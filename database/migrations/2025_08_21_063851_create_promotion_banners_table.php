<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('promotion_banners', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable();
      $table->string('description')->nullable();
      $table->unsignedBigInteger('brand_id')->nullable();
      $table->decimal('discount_percent', 5, 2)->nullable();
      $table->timestamp('start_at')->nullable();
      $table->timestamp('end_at')->nullable();
      $table->unsignedBigInteger('tablet_banner')->nullable();
      $table->unsignedBigInteger('mobile_banner')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('promotion_banners');
  }
};
