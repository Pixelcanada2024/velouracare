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
    Schema::create('translated_images', function (Blueprint $table) {
      $table->id();
      $table->morphs('model'); // model_type, model_id
      $table->string('key')->index(); // e.g., banner, thumbnail
      $table->string('locale', 5); // en, ar
      $table->unsignedBigInteger('upload_id');
      $table->timestamps();

      $table->unique(['model_type', 'model_id', 'key', 'locale']); // prevent duplicates
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('translated_images');
  }
};
