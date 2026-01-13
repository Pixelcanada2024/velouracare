<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;

class ProductStockService
{
  public function store(array $data, $product)
  {

    $variant_price = request('variant_price.0', 0);
    $variant_sku = request('variant_sku.0', '');
    $variant_qty = request('variant_qty.0', 0);
    $variant_barcode = request('variant_barcode.0', '');
    $variant_box_qty = request('variant_box_qty.0', 0);
    $variant_lead_time = request('variant_lead_time.0', '');
    $variant_made_in_country_id = request('variant_made_in_country_id.0', null);
    $variant_available_document = request('variant_available_document.0', null);
    $variant_msrp = request('variant_msrp.0', 0);

    // Create the single variant
    $product_stock = new ProductStock();
    $product_stock->product_id = $product->id;

    // Use product name as variant name
    $product_stock->variant = $product->name;
    $product_stock->variant_name = $product->name;

    $product_stock->price = $variant_price;
    $product_stock->sku = $variant_sku;
    $product_stock->qty = $variant_qty;
    
    // New fields (Stock)
    $product_stock->barcode = $variant_barcode;
    $product_stock->box_qty = $variant_box_qty;
    $product_stock->lead_time = $variant_lead_time;
    $product_stock->made_in_country_id = $variant_made_in_country_id;
    $product_stock->available_document = $variant_available_document;
    $product_stock->msrp = $variant_msrp;

    //No color or image fields
    $product_stock->image = null;
    $product_stock->color_id = null;

    //use product discount
    $product_stock->has_special_discount = 0;
    $product_stock->discount_type = $product->discount_type;
    $product_stock->discount = $product->discount;

    $product_stock->save();

    $product->unit_price = $variant_price;
    $product->current_stock = $variant_qty;
    $product->variant_product = 1;
    $product->save();
  }


  public function updateVariants($product)
  {
    $variant_price = request('variant_price.0');
    $variant_sku = request('variant_sku.0', '');
    $variant_qty = request('variant_qty.0', 0);
    $variant_barcode = request('variant_barcode.0', '');
    $variant_box_qty = request('variant_box_qty.0', 0);
    $variant_lead_time = request('variant_lead_time.0', '');
    $variant_made_in_country_id = request('variant_made_in_country_id.0', null);
    $variant_available_document = request('variant_available_document.0', null);
    $variant_msrp = request('variant_msrp.0', 0);

    $existing_variant = $product->stocks()->first();

    //Update existing variant
    $existing_variant->variant = $product->name;
    $existing_variant->variant_name = $product->name;
    $existing_variant->price = $variant_price;
    $existing_variant->sku = $variant_sku;
    $existing_variant->qty = $variant_qty;
    
    // Update new fields (Stock)
    $existing_variant->barcode = $variant_barcode;
    $existing_variant->box_qty = $variant_box_qty;
    $existing_variant->lead_time = $variant_lead_time;
    $existing_variant->made_in_country_id = $variant_made_in_country_id;
    $existing_variant->available_document = $variant_available_document;
    $existing_variant->msrp = $variant_msrp;

    //use product discount
    $existing_variant->has_special_discount = 0;
    $existing_variant->discount_type = $product->discount_type;
    $existing_variant->discount = $product->discount;

    $existing_variant->save();

    $product->unit_price = $variant_price;
    $product->current_stock = $variant_qty;
    $product->variant_product = 1;
    $product->save();

    return true;
  }

  public function product_duplicate_store($product_stocks, $product_new)
  {
    foreach ($product_stocks as $key => $stock) {
      $product_stock              = new ProductStock;
      $product_stock->product_id  = $product_new->id;
      $product_stock->variant     = $stock->variant;
      $product_stock->variant_name = $stock->variant_name;
      $product_stock->price       = $stock->price;
      $product_stock->sku         = $stock->sku;
      $product_stock->qty         = $stock->qty;
      $product_stock->barcode     = $stock->barcode;
      $product_stock->box_qty     = $stock->box_qty;
      $product_stock->lead_time   = $stock->lead_time;
      $product_stock->made_in_country_id = $stock->made_in_country_id;
      $product_stock->available_document = $stock->available_document;
      $product_stock->msrp        = $stock->msrp;
      $product_stock->image       = $stock->image;
      $product_stock->color_id    = $stock->color_id;
      $product_stock->has_special_discount = $stock->has_special_discount;
      $product_stock->discount_type = $stock->discount_type;
      $product_stock->discount    = $stock->discount;
      $product_stock->save();
    }
  }
}
