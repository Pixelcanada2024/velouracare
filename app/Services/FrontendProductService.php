<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\LastViewedProduct;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BrandResource;
use App\Http\Resources\V2\Seller\ProductResource;
use App\Http\Resources\V2\Seller\SingleProductResource;
use App\Http\Resources\V2\Seller\PromotionBannerResource;
use App\Http\Resources\V2\Seller\ProductWithStockResource;

class FrontendProductService
{
  public function singleProductPage(Product $product)
  {
    $product->load(['thumbnail', 'brand', 'frequently_bought_products']);

    if (Auth::check()) {
      $userId = Auth::id();
      $existingView = LastViewedProduct::where('user_id', $userId)
        ->where('product_id', $product->id)
        ->first();

      if ($existingView) {
        $existingView->touch();
      } else {
        LastViewedProduct::create([
          'user_id' => $userId,
          'product_id' => $product->id
        ]);
      }
    }

    // Get frequently bought products BEFORE transforming to resource
    $frequentlyBoughtProducts = null;

    if ($product->frequently_bought_selection_type == 'product') {
      $frequentlyBoughtProducts = $product->frequently_bought_product_items;
    } elseif ($product->frequently_bought_selection_type == 'category') {
      $categoryId = $product->frequently_bought_products()
        ->where('category_id', '!=', null)
        ->first()
        ->category_id ?? null;

      if ($categoryId) {
        $frequentlyBoughtProducts = $product->frequently_bought_category_product_items();
      }
    }

    // Now convert to resource
    $productResource = (new ProductWithStockResource($product))->toArray(request());
    $frequentlyBoughtProductsResource = ProductWithStockResource::collection($frequentlyBoughtProducts)->toArray(request());

    return inertia('Products/SingleProduct', [
      'product' => $productResource,
      'frequentlyBoughtProducts' => $frequentlyBoughtProductsResource,
    ]);
  }


  public function getQuickProduct(Product $product)
  {
    $product->load(['stocks.color', 'stocks.imageFile']);

    return response()->json([
      'data' => (new SingleProductResource($product))->toArray(request(), false),
      'status' => 'success',
      'success' => true,
    ]);
  }

  public function ajaxSearch(Request $request)
  {

    if ($request->has('search')) {
      $search = $request->input('search');
      $search = strtolower($search);

      // Products
      $products = auth()->check() ? Product::where(function ($query) use ($search) {
        $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
          ->orWhereHas('product_translations', function ($query) use ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
          })
          // ->orWhere('description', 'like', "%{$search}%")
          ->orWhereHas('stocks', function ($query) use ($search) {
            $query->whereRaw('LOWER(variant) LIKE ?', ["%{$search}%"]);
          });
      })->limit(10)
        ->get()
        : [];

      // Brands
      $brands = Brand::whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name_trans, "$.en"))) LIKE ?', ["%{$search}%"])
        ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name_trans, "$.ar"))) LIKE ?', ["%{$search}%"])
        ->orWhereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
        ->limit(10)
        ->get();

      return response()->json([
        'brands' => $brands,
        'products' => $products,
        'status' => 'success',
        'success' => true
      ]);
    }
  }

  public function productsPage(Request $request)
  {
    $frontendProductServiceHelpers = new FrontendProductServiceHelpers();

    $categoryIds = $frontendProductServiceHelpers->getCategoryIds($request);
    $brandIds = $frontendProductServiceHelpers->getBrandIds($request);

    // get brand & promotion info
    $brand = Brand::whereIn('id', $brandIds)->first() ?? null;
    $promotion = $brand?->promotionBanner ?? null;
    $brand = !!$brand ? BrandResource::make($brand)->toArray($request) : null;
    $promotion = !!$promotion ? PromotionBannerResource::make($promotion)->toArray($request) : null;

    $categoryProductIds = ProductCategory::whereIn('category_id', $categoryIds)
        ?->pluck('product_id')
        ?->toArray() ?? [];

    $productQuery = Product::query()->isApprovedPublished();

    if ($request->has('categories')) {
      $productQuery = $productQuery
        ->whereIn('id', $categoryProductIds)
        ->orWhereIn('category_id', $categoryIds);
    }

    if ($request->has('brands')) {
      $productQuery = $productQuery->whereIn('brand_id', $brandIds);
    }

    if ($request->has('search')) {
      $search = $request->input('search');
      $search = strtolower($search);
      $productQuery = $productQuery->where(function ($query) use ($search) {
        $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
          ->orWhereHas('product_translations', function ($query) use ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
          })
          // ->orWhere('description', 'like', "%{$search}%")
          ->orWhereHas('stocks', function ($query) use ($search) {
            $query->whereRaw('LOWER(variant) LIKE ?', ["%{$search}%"]);
          });
      });
    }

    if ($request->has('sortBy')) {
      $sort = $request->input('sortBy');
      if ($sort === 'newest') {
        $productQuery = $productQuery->orderBy('created_at', 'desc');
      } elseif ($sort === 'oldest') {
        $productQuery = $productQuery->orderBy('created_at', 'asc');
      } elseif ($sort === 'name_a_z') {
        $productQuery = $productQuery->orderBy('name', 'asc');
      } elseif ($sort === 'name_z_a') {
        $productQuery = $productQuery->orderBy('name', 'desc');
      } elseif ($sort === 'price_low_high') {
        $productQuery = $productQuery->select('*')
          ->addSelect([
            'min_price' => ProductStock::select('price')
              ->whereColumn('product_stocks.product_id', 'products.id')
              ->orderBy('price', 'asc')
              ->limit(1)
          ])
          ->orderBy('min_price', 'asc');
        ;
      } elseif ($sort === 'price_high_low') {
        $productQuery = $productQuery->select('*')
          ->addSelect([
            'max_price' => ProductStock::select('price')
              ->whereColumn('product_stocks.product_id', 'products.id')
              ->orderBy('price', 'desc')
              ->limit(1)
          ])
          ->orderBy('max_price', 'desc');
      }
    }

    // dd($productQuery->toSql(), $productQuery->getBindings());

    $filterOptionsData = $frontendProductServiceHelpers->getFilterData($productQuery);

    $perPage = $request->input('show', 16);

    $products = $productQuery->paginate($perPage)->withQueryString();

    return inertia('Products/Products', [
      'paginatedProducts' => [
        'data' => ProductWithStockResource::collection($products->items())->toArray(request()),
        'last_page' => $products->lastPage(),
        'links' => $products->toArray()['links'],
      ],
      'brand' => $brand,
      'promotion' => $promotion,
      'filterOptionsData' => $filterOptionsData,
    ]);
  }

}
