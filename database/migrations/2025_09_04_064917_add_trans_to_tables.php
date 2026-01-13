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
    Schema::table('faqs', function (Blueprint $table) {
      $table->json('question_trans')->nullable()->after('question');
      $table->json('answer_trans')->nullable()->after('answer');
      $table->string('question')->nullable()->change();
      $table->text('answer')->nullable()->change();
    });
    Schema::table('faq_categories', function (Blueprint $table) {
      $table->json('name_trans')->nullable()->after('name');
      $table->string('name')->nullable()->change();
    });
    Schema::table('blogs', function (Blueprint $table) {
      $table->json('title_trans')->nullable()->after('title');
      $table->json('short_description_trans')->nullable()->after('short_description');
      $table->json('description_trans')->nullable()->after('description');
      $table->string('title')->nullable()->change();
      $table->text('short_description')->nullable()->change();
      $table->longText('description')->nullable()->change();
      $table->unsignedBigInteger('banner')->nullable()->change();
    });
    Schema::table('blog_categories', function (Blueprint $table) {
      $table->json('category_name_trans')->nullable()->after('category_name');
      $table->string('category_name')->nullable()->change();
    });

    Schema::table('brands', function (Blueprint $table) {
      $table->string('name')->nullable()->change();
      $table->unsignedBigInteger('web_banner')->nullable()->change();
      $table->unsignedBigInteger('mobile_banner')->nullable()->change();

      $table->json('name_trans')->nullable()->after('name');
    });

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('faqs', function (Blueprint $table) {
      $table->dropColumn('question_trans');
      $table->dropColumn('answer_trans');
      $table->string('question')->change();
      $table->text('answer')->change();
    });
    Schema::table('faq_categories', function (Blueprint $table) {
      $table->dropColumn('name_trans');
      $table->string('name')->change();
    });
    Schema::table('blogs', function (Blueprint $table) {
      $table->dropColumn('title_trans');
      $table->dropColumn('short_description_trans');
      $table->dropColumn('description_trans');
      $table->string('title')->change();
      $table->text('short_description')->change();
      $table->longText('description')->change();
      $table->unsignedInteger('banner')->change();
    });
    Schema::table('blog_categories', function (Blueprint $table) {
      $table->dropColumn('category_name_trans');
      $table->string('category_name')->change();
    });

    Schema::table('brands', function (Blueprint $table) {
      $table->dropColumn('name_trans');
    });
  }
};
