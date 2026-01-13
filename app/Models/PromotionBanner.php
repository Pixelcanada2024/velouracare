<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionBanner extends Model
{
  use HasFactory;
  protected $guarded = ['id'];

  protected $casts = [
    'start_at' => 'datetime',
    'end_at' => 'datetime',
    'discount_percent' => 'float'
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  public function tabletBannerUpload()
  {
      return $this->belongsTo(Upload::class, 'tablet_banner');
  }

  public function mobileBannerUpload()
  {
      return $this->belongsTo(Upload::class, 'mobile_banner');
  }


}
