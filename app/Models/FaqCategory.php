<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'name_trans' => 'array',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'category_id');
    }

    protected function getTranslatedValue(array $translations , $default = '')
    {
        $locale = app()->getLocale();
        return $translations[$locale] ?? $translations['en'] ?? $default ?? '';
    }

    public function getNameAttribute( $value): string
    {
        return $this->getTranslatedValue($this->name_trans ?? [] , $value);
    }
}
