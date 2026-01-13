<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslatedImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }
}
