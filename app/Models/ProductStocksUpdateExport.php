<?php

namespace App\Models;

use App\Models\ProductStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class ProductStocksUpdateExport implements FromCollection, WithHeadings, WithMapping
{
  public function collection()
  {
    return ProductStock::with('product')->get();
  }

  public function headings(): array
  {
    return [
      'product_name',
      'stock_id',
      'variant_name',
      'sku',
      'price',
      'qty',
      'image',
      'has_special_discount',
      'discount',
      'discount_type'
    ];
  }

  public function map($stock): array
  {

    return [
      'product_name'  => $stock->product ? $stock->product->name : 'Unknown Product',
      'stock_id'      => $stock->id,
      'variant_name'  => $stock->variant_name,
      'sku'           => $stock->sku,
      'price'         => $stock->price,
      'qty'           => $stock->qty,
      'image'         => $stock->image,
      'has_special_discount' => (string) ($stock->has_special_discount ?? '0'),
      'discount'      =>  (string) ($stock->discount ?? '0'),
      'discount_type' => $stock->discount_type,
    ];
  }
}
