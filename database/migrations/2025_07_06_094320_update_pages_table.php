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
    \App\Models\Page::find(2)->update([
      'slug' => 'age-policy',
      'title' => 'Age Policy Pages',
      'type' => 'age_policy_page',
    ]);
    \App\Models\Page::find(4)->update([
      'slug' => 'shipping-policy',
      'title' => 'Shipping Policy Pages',
      'type' => 'shipping_policy_page',
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    \App\Models\Page::find(2)->update([
      'slug' => 'seller-policy',
      'title' => 'Seller Policy Pages',
      'type' => 'seller_policy_page',
    ]);
    \App\Models\Page::find(4)->update([
      'slug' => 'support-policy',
      'title' => 'Support Policy Pages',
      'type' => 'support_policy_page',
    ]);
  }
};
