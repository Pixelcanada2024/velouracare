<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use App\Traits\HasTranslatedImages;

use App;

class Brand extends Model
{
    use HasTranslatedImages;

    // protected $with = ['brand_translations'];
    protected $with = ['translatedImages.upload'];
    protected $guarded=['id'];

    protected $casts = [
      'name_trans' => 'array',
  ];


    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $brand_translation = $this->brand_translations->where('lang', $lang)->first();
        return $brand_translation != null ? $brand_translation->$field : $this->$field;
    }

    public function brand_translations()
    {
        return $this->hasMany(BrandTranslation::class);
    }

    public function logo_image()
    {
        return $this->belongsTo(Upload::class, 'logo');
    }

    public function web_banner_image()
    {
        return $this->belongsTo(Upload::class, 'web_banner');
    }

    public function mobile_banner_image()
    {
        return $this->belongsTo(Upload::class, 'mobile_banner');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function promotionBanner()
    {
        return $this->hasOne(PromotionBanner::class);
    }

    protected function getTranslatedValue(array $translations , $default = '')
    {
        $locale = app()->getLocale();
        return $translations[$locale] ?? $translations['en'] ?? $default ?? '';
    }


    public function getNameAttribute($value)
    {
        return $this->getTranslatedValue($this->name_trans ?? [] , $value);
    }

    public function getWebBannerAttribute($value)
    {
        return $this->getImageId('web_banner') ?? $value;
    }

    public function getMobileBannerAttribute($value)
    {
        return $this->getImageId('mobile_banner') ?? $value;
    }

    public function delete()
    {
        $this->translatedImages()?->delete();
        parent::delete();
    }
}
