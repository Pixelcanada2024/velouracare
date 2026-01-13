<?php

namespace App\Models;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BrandsExport implements FromCollection, WithHeadings, WithMapping
{
  public function collection()
  {
    return Brand::all();
  }

  public function headings(): array
  {
    return [
      'id',
      'name',
      'name_ar',
      'logo_id',
      'logo_url',
      'web_banner_id_ar',
      'mobile_banner_id_ar',
      'web_banner_id_en',
      'mobile_banner_id_en',
      'web_banner_url_ar',
      'mobile_banner_url_ar',
      'web_banner_url_en',
      'mobile_banner_url_en',
      'meta_title',
      'meta_description',
    ];
  }

  public function map($brand): array
  {
    $web_banner_image_id_ar = $brand->getImageId('web_banner',"ar") ?? null;
    $mobile_banner_image_id_ar = $brand->getImageId('mobile_banner',"ar") ?? null;
    $web_banner_image_id_en = $brand->getImageId('web_banner',"en") ?? null;
    $mobile_banner_image_id_en = $brand->getImageId('mobile_banner',"en") ?? null;

    return [
      'id' => $brand?->id,
      'name' => $brand?->name_trans['en'] ?? $brand?->name ?? "",
      'name_ar' => $brand?->name_trans['ar'] ?? "",
      'logo_id' =>  !!$brand->logo ? $brand->logo : null,
      'logo_url' => $brand->logo ? uploaded_asset($brand->logo) : '',
      'web_banner_id_ar' =>  $web_banner_image_id_ar,
      'mobile_banner_id_ar' =>  $mobile_banner_image_id_ar ,
      'web_banner_id_en' =>  $web_banner_image_id_en,
      'mobile_banner_id_en' =>  $mobile_banner_image_id_en,
      'web_banner_url_ar' => $web_banner_image_id_ar ? uploaded_asset($web_banner_image_id_ar) : '',
      'mobile_banner_url_ar' => $mobile_banner_image_id_ar ? uploaded_asset($mobile_banner_image_id_ar) : '',
      'web_banner_url_en' => $web_banner_image_id_en ? uploaded_asset($web_banner_image_id_en) : '',
      'mobile_banner_url_en' => $mobile_banner_image_id_en ? uploaded_asset($mobile_banner_image_id_en) : '',
      'meta_title' => $brand?->meta_title ?? null,
      'meta_description' => $brand?->meta_description ?? null,
    ];
  }
}
