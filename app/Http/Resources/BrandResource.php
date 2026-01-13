<?php

namespace App\Http\Resources;

use App\Http\Resources\V2\Seller\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'logo' => !!$this->logo_image  ? uploaded_asset($this->logo_image) : null,
            'web_banner' => !!$this->web_banner  ? uploaded_asset($this->web_banner) : null,
            'mobile_banner' =>  !!$this->mobile_banner  ? uploaded_asset($this->mobile_banner) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y.m.d') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y.m.d') : null,
        ];
    }
}
