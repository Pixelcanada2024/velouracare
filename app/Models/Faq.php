<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'question_trans' => 'array',
        'answer_trans' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }

    protected function getTranslatedValue(array $translations , $default = '')
    {
        $locale = app()->getLocale();
        return $translations[$locale] ?? $translations['en'] ?? $default ?? '';
    }

    public function getAnswerAttribute($value): string
    {
        return $this->getTranslatedValue($this->answer_trans ?? [] , $value);
    }

    public function getQuestionAttribute($value)
    {
        return $this->getTranslatedValue($this->question_trans ?? [] , $value);
    }
}
