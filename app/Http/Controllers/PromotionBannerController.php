<?php

namespace App\Http\Controllers;

use App\Models\PromotionBanner;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V2\Seller\PromotionBannerResource;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class PromotionBannerController extends Controller
{


  public function promotions()
  {
    $promotions = PromotionBanner::with(['tabletBannerUpload', 'mobileBannerUpload', 'brand'])
      ->where('start_at', '<=', now())
      ->where('end_at', '>=', now())
      ->get();


    return inertia('Promotions/Promotions', [
      'promotions' => PromotionBannerResource::collection($promotions)->toArray(request()),
    ]);
  }

  public function index(Request $request)
  {
    $sort_search = null;
    $banners = PromotionBanner::orderBy('created_at', 'desc');

    if ($request->search != null) {
      $banners = $banners->where('title', 'like', '%' . $request->search . '%');
      $sort_search = $request->search;
    }

    $banners = $banners->paginate(15);
    return view('backend.promotion.banner.index', compact('banners', 'sort_search'));
  }

  public function create()
  {
    // Get brands that do not have an active promotion
    $brandsWithPromotion = PromotionBanner::pluck('brand_id')->toArray();
    $brands = Brand::whereNotIn('id', $brandsWithPromotion)->get();

    return view('backend.promotion.banner.create', compact('brands'));
  }


  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|max:255',
      'description' => 'required',
      'brand_id' => 'required|exists:brands,id',
      'discount_percent' => 'required|numeric|min:0|max:100',
      'tablet_banner' => 'nullable',
      'mobile_banner' => 'nullable',
      'end_at' => 'required|date|after:start_at',
    ], [
      'title.required' => translate('Banner title is required'),
      'title.max' => translate('Banner title cannot exceed 255 characters'),
      'description.required' => translate('Description is required'),
      'brand_id.required' => translate('Brand is required'),
      'brand_id.exists' => translate('Selected brand is invalid'),
      'discount_percent.required' => translate('Discount percent is required'),
      'discount_percent.numeric' => translate('Discount percent must be a number'),
      'discount_percent.min' => translate('Discount percent cannot be less than 0'),
      'discount_percent.max' => translate('Discount percent cannot exceed 100'),
      'tablet_banner.required' => translate('Tablet banner image is required'),
      'mobile_banner.required' => translate('Mobile banner image is required'),
      'end_at.required' => translate('End date is required'),
      'end_at.date' => translate('End date must be a valid date'),
      'end_at.after' => translate('End date must be after start date'),
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $banner = new PromotionBanner;
    $banner->title = $request->title;
    $banner->description = $request->description;
    $banner->brand_id = $request->brand_id;
    $banner->discount_percent = $request->discount_percent;
    $banner->tablet_banner = $request->tablet_banner;
    $banner->mobile_banner = $request->mobile_banner;
    $banner->start_at = now();
    $banner->end_at = $request->end_at;
    $banner->save();

    // Apply discount to brand products
    $this->applyBrandDiscount($banner->brand_id, 'percent', $request->discount_percent);

    flash(translate('Banner has been created successfully'))->success();
    return redirect()->route('promotion-banners.index');
  }

  public function edit($id)
  {
    $banner = PromotionBanner::findOrFail($id);
    // Get brands that do not have an active promotion or the current brand
    $brandsWithPromotion = PromotionBanner::where('id', '!=', $banner->id)->pluck('brand_id')->toArray();
    $brands = Brand::whereNotIn('id', $brandsWithPromotion)->orWhere('id', $banner->brand_id)->get();

    return view('backend.promotion.banner.edit', compact('banner', 'brands'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|max:255',
      'description' => 'required',
      'brand_id' => 'required|exists:brands,id',
      'discount_percent' => 'required|numeric|min:0|max:100',
      'tablet_banner' => 'nullable',
      'mobile_banner' => 'nullable',
      'end_at' => 'required|date|after:start_at',
    ], [
      'title.required' => translate('Banner title is required'),
      'title.max' => translate('Banner title cannot exceed 255 characters'),
      'description.required' => translate('Description is required'),
      'brand_id.required' => translate('Brand is required'),
      'brand_id.exists' => translate('Selected brand is invalid'),
      'discount_percent.required' => translate('Discount percent is required'),
      'discount_percent.numeric' => translate('Discount percent must be a number'),
      'discount_percent.min' => translate('Discount percent cannot be less than 0'),
      'discount_percent.max' => translate('Discount percent cannot exceed 100'),
      'tablet_banner.required' => translate('Tablet banner image is required'),
      'mobile_banner.required' => translate('Mobile banner image is required'),
      'end_at.required' => translate('End date is required'),
      'end_at.date' => translate('End date must be a valid date'),
      'end_at.after' => translate('End date must be after start date'),
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $banner = PromotionBanner::findOrFail($id);
    $banner->title = $request->title;
    $banner->description = $request->description;
    $banner->brand_id = $request->brand_id;
    $banner->discount_percent = $request->discount_percent;
    $banner->tablet_banner = $request->tablet_banner;
    $banner->mobile_banner = $request->mobile_banner;
    $banner->end_at = $request->end_at;
    $banner->save();

    // Apply discount to brand products
    $this->applyBrandDiscount($banner->brand_id, 'percent', $request->discount_percent);

    flash(translate('Banner has been updated successfully'))->success();
    return redirect()->route('promotion-banners.index');
  }

  public function destroy($id)
  {
    $banner = PromotionBanner::findOrFail($id);

    // Reset discounts before deleting the banner
    $this->applyBrandDiscount($banner->brand_id, 'amount', 0);

    Storage::disk(env('FILESYSTEM_DRIVER'))->delete($banner->tabletBannerUpload->file_name);
    if (file_exists(public_path() . '/' . $banner->tabletBannerUpload->file_name)) {
      unlink(public_path() . '/' . $banner->tabletBannerUpload->file_name);
    }
    $banner->tabletBannerUpload->delete();

    Storage::disk(env('FILESYSTEM_DRIVER'))->delete($banner->mobileBannerUpload->file_name);
    if (file_exists(public_path() . '/' . $banner->mobileBannerUpload->file_name)) {
      unlink(public_path() . '/' . $banner->mobileBannerUpload->file_name);
    }
    $banner->mobileBannerUpload->delete();

    $banner->delete();

    flash(translate('Banner has been deleted successfully'))->success();
    return redirect()->route('promotion-banners.index');
  }


  public function applyBrandDiscount($brand_id, $discount_type, $discount_value)
  {
    // Update products
    Product::where('brand_id', $brand_id)
      ->update([
        'discount_type' => $discount_type,
        'discount' => $discount_value
      ]);

    // Update variants
    ProductStock::whereIn('product_id', function ($query) use ($brand_id) {
      $query->select('id')
        ->from('products')
        ->where('brand_id', $brand_id);
    })->update([
          'has_special_discount' => 0,
          'discount_type' => $discount_type,
          'discount' => $discount_value
        ]);

  }


  public function popUpMarketing()
  {
    return view('backend.marketing.pop_up_marketing');
  }

  public function searchProductsForPopUpMarketing(Request $request)
  {
    $query = $request->input('q');
    $products = Product::query()
      ->select('id', 'name')
      ->when($query, function ($q) use ($query) {
        $q->where('name', 'like', "%$query%");
      })
      ->limit(20)
      ->get();
    return response()->json($products);
  }

}
