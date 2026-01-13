<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use PreventDemoModeChanges;

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'category_name_trans' => 'array',
    ];

    protected function getTranslatedValue(array $translations , $default = '')
    {
        $locale = app()->getLocale();
        return $translations[$locale] ?? $translations['en'] ?? $default ?? '';
    }

    public function getCategoryNameAttribute($value): string
    {
        return $this->getTranslatedValue($this->category_name_trans ?? [] , $value);
    }

    public function posts()
    {
        return $this->hasMany(Blog::class);
    }
}
