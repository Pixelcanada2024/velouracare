<?php

namespace App\Models;

use App\Traits\HasTranslatedImages;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use PreventDemoModeChanges, SoftDeletes, HasFactory, HasTranslatedImages;

    protected $with = ['translatedImages.upload'];

    protected $guarded = ['id'];

    protected $casts = [
        'title_trans' => 'array',
        'short_description_trans' => 'array',
        'description_trans' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function bannerUpload()
    {
        return $this->belongsTo(Upload::class, 'banner');
    }

    protected function getTranslatedValue(array $translations , $default = '')
    {
        $locale = app()->getLocale();
        return $translations[$locale] ?? $translations['en'] ?? $default ?? '';
    }

    public function getTitleAttribute($value): string
    {
        return $this->getTranslatedValue($this->title_trans ?? [] , $value);
    }

    public function getDescriptionAttribute($value): string
    {
        return $this->getTranslatedValue($this->description_trans ?? [] , $value);
    }

    public function getShortDescriptionAttribute($value): string
    {
        return $this->getTranslatedValue($this->short_description_trans ?? [] , $value);
    }

    public function getBannerAttribute($value)
    {
        return $this->getImageId('banner') ?? $value;
    }

    public function delete()
    {
        $this->translatedImages()?->delete();
        parent::delete();
    }
}
