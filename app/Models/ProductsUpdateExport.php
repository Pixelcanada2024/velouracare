<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsUpdateExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['product_translations', 'stocks.country'])->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'name_ar',
            'description',
            'category_id',
            'multi_categories',
            'brand_id',
            'tags',
            'meta_title',
            'meta_description',
            'thumbnail_img',
            'discount',
            'discount_type',
            'sku',
            'barcode',
            'weight',
            'price',
            'msrp',
            'qty',
            'box_qty',
            'made_in_country_code',
            'available_document',
            'lead_time',
        ];
    }

    public function map($product): array
    {
        $product->load('product_translations');
        // Get product's additional categories
        $multiCategories = ProductCategory::where('product_id', $product->id)
            ->pluck('category_id')
            ->toArray();

        $multiCategoriesStr = !empty($multiCategories) ? implode(';', $multiCategories) : '';

        // Get the main product stock entry (already eager loaded)
        $productStock = $product->stocks->first();

        // Get country code from relationship
        $countryCode = $productStock && $productStock->country ? $productStock->country->code : '';

        return [
            'id'                => $product->id,
            'name'              => $product->product_translations->where('lang', 'en')->first()?->name ?? '',
            'name_ar'              => $product->product_translations->where('lang', 'ar')->first()?->name ?? '',
            'description'       => $product->description,
            'category_id'       => $product->category_id,
            'multi_categories'  => $multiCategoriesStr,
            'brand_id'          => $product->brand_id,
            'tags'              => $product->tags,
            'meta_title'        => $product->meta_title,
            'meta_description'  => $product->meta_description,
            'thumbnail_img'     => $product->thumbnail_img,
            'discount'          =>  "{$product->discount}" ?? '0',
            'discount_type'     => $product->discount_type,
            'sku'               =>  $productStock->sku ?? '',
            'barcode'           =>  $productStock->barcode ?? '',
            'weight'            =>  "{$product->weight}" ?? '0',
            'price'             =>  "{$productStock->price}" ?? '0',
            'msrp'              =>  "{$productStock->msrp}" ?? '0',
            'qty'               =>  "{$productStock->qty}" ?? '0',
            'box_qty'           =>  "{$productStock->box_qty}" ?? '0',
            'made_in_country_code' => $countryCode,
            'available_document' =>  "{$productStock->available_document}" ?? '0',
            'lead_time'          =>  $productStock->lead_time ,
        ];
    }
}
