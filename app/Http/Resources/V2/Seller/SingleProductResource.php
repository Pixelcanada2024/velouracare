<?php

namespace App\Http\Resources\V2\Seller;

use App\Models\Product;
use App\Models\Upload;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SingleProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request,$isWithFrequently = true)
  {
    // Process gallery images
    $galleryImages = [];

    if (!empty($this->photos)) {
      $photoIds = explode(',', $this->photos);

      // Get all image records in one query
      $images = Upload::whereIn('id', $photoIds)->get();

      // Map them into URLs
      $galleryImages = $images->map(function ($image) {
        if ($image->file_name) {
          return asset('/public/' . $image->file_name);
        } elseif ($image->external_link) {
          return $image->external_link;
        }
        return null; // fallback
      })->filter()->values(); // filter out nulls, reset keys
    }

    // Get frequently bought products
    $frequentlyBoughtProducts = null;
    
    if ($this->frequently_bought_selection_type === 'product') {
      // Get specific frequently bought products
      $frequentlyBoughtProducts = $this->frequently_bought_product_items;
    } elseif ($this->frequently_bought_selection_type === 'category') {
      // Get products from the selected category
      $categoryId = $this->frequently_bought_products()->where('category_id', '!=', null)->first()->category_id ?? null;
      if ($categoryId) {
        $frequentlyBoughtProducts  = $this->frequently_bought_category_product_items();
      }
    }

    
    return [
      ...(new ProductResource($this))->toArray($request),
      'galleryImages' => $galleryImages,
      'description' => $this->description ?: '',
      'stocks' => ProductStockResource::collection($this->stocks)->toArray($request),
      'frequently_bought_products' => $isWithFrequently ? ProductResource::collection($frequentlyBoughtProducts)->toArray($request) : [],
    ];
  }
}
