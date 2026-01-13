<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class FrontendProductServiceHelpers
{
  public function getCategoryIds(Request $request)
  {
    $names = $request['categories'] ?? [];

    if (empty($names)) {
      return [];
    }

    $lowercaseNames = array_map('strtolower', $names);

    $categoryIds = Category::where(function ($query) use ($lowercaseNames) {
        $query->orWhereHas('category_translations', function ($q) use ($lowercaseNames) {
          $q->whereRaw('LOWER(name) LIKE ?', ["%{$lowercaseNames[0]}%"]);
        })
          ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowercaseNames[0]}%"]);
      })
      ->pluck('categories.id')
      ->toArray();

    return $categoryIds;
  }

  public function getBrandIds(Request $request)
  {
    $names = $request['brands'] ?? [];

    if (empty($names)) {
      return [];
    }

    $lowercaseNames = array_map('strtolower', $names);
    $brandIds = Brand::whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name_trans, "$.en"))) LIKE ?', ["%{$lowercaseNames[0]}%"])
        ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name_trans, "$.ar"))) LIKE ?', ["%{$lowercaseNames[0]}%"])
        ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowercaseNames[0]}%"])
        ?->pluck('id')?->toArray() ?? [];

    return $brandIds;
  }

  public function getFilterData($productQuery)
  {

    $productIds = $productQuery->pluck('id')->toArray();

    $brandNameFilters = $this->getBrandNameFilters($productQuery);

    $categoryNameFilters = $this->getCategoryNameFilters($productIds);

    // Clone the base query so we don’t affect it
    $baseQuery = clone $productQuery;
    $productsWithStock = $baseQuery->withSum('stocks as total_qty', 'qty');
    $inStockCount = (clone $productsWithStock)->get()->where('total_qty', '>', 0)->count();
    $outStockCount = (clone $productsWithStock)->get()->where('total_qty', '<=', 0)->count();

    $availability = [];
    if ($inStockCount > 0) {
      $availability[] = ['value' => 'in_stock', 'label' => (app()->getLocale() == 'en' ? 'In Stock (' : "متوفر (") . $inStockCount . ')'];
    }
    if ($outStockCount > 0) {
      $availability[] = ['value' => 'out_of_stock', 'label' => (app()->getLocale() == 'en' ? 'Out of Stock (' : "غير متوفر (") . $outStockCount . ')'];
    }

    if (app()->getLocale() == 'ar') {
      $sortBy = [
        ['value' => 'newest', 'label' => 'الأحدث'],
        ['value' => 'oldest', 'label' => 'الأقدم'],
        // ['value' => 'price_low_to_high', 'label' => 'السعر: من الأقل إلى الأعلى'],
        // ['value' => 'price_high_to_low', 'label' => 'السعر: من الأعلى إلى الأقل'],
        ['value' => 'name_a_z', 'label' => 'الاسم: أ إلى ي'],
        ['value' => 'name_z_a', 'label' => 'الاسم: ي إلى أ'],
      ];
    } else {
      $sortBy = [
        ['value' => 'newest', 'label' => 'Newest'],
        ['value' => 'oldest', 'label' => 'Oldest'],
        // ['value' => 'price_low_to_high', 'label' => 'Price: Low to High'],
        // ['value' => 'price_high_to_low', 'label' => 'Price: High to Low'],
        ['value' => 'name_a_z', 'label' => 'Name: A to Z'],
        ['value' => 'name_z_a', 'label' => 'Name: Z to A'],
      ];
    }

    $filterOptionsData = [
      'categories' => $categoryNameFilters,
      'brands' => $brandNameFilters,
      'availability' => $availability,
      'sortBy' => $sortBy
    ];

    return $filterOptionsData;
  }

  protected function getBrandNameFilters($productQuery)
  {
    $brandIds = $productQuery->pluck('brand_id')->filter()->map(fn($id) => (int) $id)->toArray();

    $brandNames = Brand::whereIn('id', $brandIds)
      ->get()
      ->pluck('name', 'id')
      ->toArray();

    $brandNameFilters = [];

    if (empty($brandNames)) {
      return $brandNameFilters;
    }

    foreach (array_count_values($brandIds) as $id => $count) {
      if (isset($brandNames[$id])) {
        $brandNameFilters[] = [
          'value' => $brandNames[$id],
          'label' => $brandNames[$id] . ' (' . $count . ')',
        ];
      }
    }

    return $brandNameFilters;
  }

  protected function getCategoryNameFilters($productIds)
  {
    // Get all product-category pairs (main + secondary)
    $pairs = collect(
      Product::whereIn('id', $productIds)
        ->pluck('category_id', 'id')
        ->map(fn($catId, $productId) => ['product_id' => $productId, 'category_id' => $catId])
        ->values()
        ->toArray()
    )->merge(
        ProductCategory::whereIn('product_id', $productIds)
          ->select('product_id', 'category_id')
          ->get()
      );

    // Each product counts a category only once
    $uniquePairs = $pairs->unique(fn($item) => $item['product_id'] . '-' . $item['category_id']);

    // Count products per category
    $categoryCounts = $uniquePairs
      ->groupBy('category_id')
      ->map(fn($items) => $items->count());

    // Get category names
    $categoryNames = Category::whereIn('id', $categoryCounts->keys())
      ->get()
      ->pluck('name', 'id');

    // Build final filters
    return $categoryCounts->map(fn($count, $id) => [
      'value' => $categoryNames[$id] ?? '',
      'label' => ($categoryNames[$id] ?? '') . ' (' . $count . ')',
    ])->values()->toArray();
  }

}
