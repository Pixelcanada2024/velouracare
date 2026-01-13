<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProductResource extends JsonResource
{


  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {

    $product = [
      'id' => $this->id,
      'name' => $this->getTranslation('name'),
      'name_ar'=>$this->getTranslation('name','ar') ?? null,
      'name_en'=>$this->getTranslation('name','en') ?? null,
      'brand' => $this->brand ? $this->brand->name : '',
      'brand_id' => $this->brand ? $this->brand->id : '',
      'image' => $this?->thumbnail_img
        ? (
          $this->thumbnail?->external_link
          ?: asset('/public/' . $this->thumbnail?->file_name)
        )
        : '/public/assets/img/placeholder.jpg',
      'image_relative_path' =>  uploaded_asset($this?->thumbnail_img,false) ,
      'featured'   => (bool)$this->featured,
      'weight' => $this->weight > 0 ? $this->weight : '-',
      'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
      'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
    ];


    return $product;
  }
}
