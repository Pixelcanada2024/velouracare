<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App;

class Category extends Model
{
  use PreventDemoModeChanges;
  protected $fillable = [
    'discount',
    'discount_start_date',
    'discount_end_date',
  ];

  protected $with = ['category_translations'];

  public function getTranslation($field = '', $lang = false)
  {
    $lang = $lang ?: app()->getLocale();

    $translation = $this->category_translations
      ->firstWhere('lang', $lang)
      ?? $this->category_translations->firstWhere('lang', 'en')
      ?? $this;

    return $translation?->$field ?? '';
  }

  public function getNameAttribute($value)
  {
    $translation = $this->getTranslation('name');
    return !empty($translation) ? $translation : $value;
  }

  public function category_translations()
  {
    return $this->hasMany(CategoryTranslation::class);
  }

  public function coverImage()
  {
    return $this->belongsTo(Upload::class, 'cover_image');
  }

  public function catIcon()
  {
    return $this->belongsTo(Upload::class, 'icon');
  }

  public function products()
  {
    return $this->belongsToMany(Product::class, 'product_categories');
  }

  public function preorderProducts()
  {
    return $this->belongsToMany(PreorderProduct::class, 'preorder_product_categories');
  }

  public function bannerImage()
  {
    return $this->belongsTo(Upload::class, 'banner');
  }

  public function classified_products()
  {
    return $this->hasMany(CustomerProduct::class);
  }

  public function categories()
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  public function childrenCategories()
  {
    return $this->hasMany(Category::class, 'parent_id')->with('categories');
  }

  public function parentCategory()
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  public function attributes()
  {
    return $this->belongsToMany(Attribute::class);
  }

  public function sizeChart()
  {
    return $this->belongsTo(SizeChart::class, 'id', 'category_id');
  }

  public function sellerDiscount()
  {
    return $this->hasOne(SellerCategory::class)->where('seller_id', auth()->id());
  }

  public function sellerDiscounts()
  {
    return $this->hasMany(SellerCategory::class);
  }
}
