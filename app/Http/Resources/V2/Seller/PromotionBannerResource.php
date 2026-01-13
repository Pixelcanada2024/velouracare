<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionBannerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,

      'brand_id' => $this->brand_id,
      'brand_name' => $this->brand?->name,

      'discount_percent' => $this->discount_percent,

      'start_at' => $this->start_at ? $this->start_at->format('Y.m.d') : null,

      'end_at' => $this->end_at ? $this->end_at->format('Y.m.d') : null,

      'tablet_banner' => $this->tabletBannerUpload
        ? (
          $this->tabletBannerUpload->external_link
          ?: asset('/public/' . $this->tabletBannerUpload->file_name)
        )
        : '/public/assets/img/placeholder.jpg',

      'mobile_banner' => $this->mobileBannerUpload
        ? (
          $this->mobileBannerUpload->external_link
          ?: asset('/public/' . $this->mobileBannerUpload->file_name)
        )
        : '/public/assets/img/placeholder.jpg',


      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }


}
