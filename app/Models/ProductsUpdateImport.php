<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use DB;
use Validator;

class ProductsUpdateImport implements ToCollection, WithHeadingRow
{
    private $countryCodeToId = [];

    public function __construct()
    {
        // Build country code => id lookup array once
        $this->countryCodeToId = \DB::table('countries')
            ->pluck('id', 'code')
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        $allErrors = [];
        $rowIndex = 2;
        $validUpdates = [];

        // First pass: Validate ALL data
        foreach ($rows as $row) {
            $rowArray = is_array($row) ? $row : $row->toArray();
            $rowNumber = $rowIndex++;

            // Skip empty rows
            if (collect($rowArray)->filter()->isEmpty()) {
                continue;
            }

            // Skip if no ID provided
            if (empty($rowArray['id'])) {
                continue;
            }

            $rowArray['sku'] = isset($rowArray['sku']) ? (string)$rowArray['sku'] : null;
            $rowArray['barcode'] = isset($rowArray['barcode']) ? (string)$rowArray['barcode'] : null;


            // Validate main product data
            $validator = \Validator::make($rowArray, [
                'id' => 'required|exists:products,id',
                'name' => 'required|max:255',
                'name_ar' => 'required|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'price' => 'required|numeric|min:0',
                'qty' => 'required|numeric|min:0',
                'sku' => 'required|max:255',
                'discount_type' => 'required|in:amount,percent,0',
                'discount' => 'required_if:discount_type,amount,percent|min:0',
                'barcode' => 'required|string|max:255',
                'box_qty' => 'required|numeric|min:0',
                'lead_time' => 'required|string|max:255',
                'made_in_country_code' => 'required|exists:countries,code',
                'available_document' => 'required|numeric',
                'msrp' => 'required|numeric|min:0',
                'weight' => 'required|numeric|min:0',
            ]);




            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $allErrors[] = "Row {$rowNumber}: {$error}";
                }
                continue;
            }

            // Validate country code exists in our mapping
            $countryCode = strtoupper(trim($rowArray['made_in_country_code']));
            if (!isset($this->countryCodeToId[$countryCode])) {
                $allErrors[] = "Row {$rowNumber}: Country code '{$countryCode}' does not exist";
                continue;
            }

            // Map country code to ID for later use
            $rowArray['made_in_country_id'] = $this->countryCodeToId[$countryCode];

            // Add image validation for main product
            if (!empty($rowArray['thumbnail_img'])) {
                if (!is_numeric($rowArray['thumbnail_img'])) {
                    $allErrors[] = "Row {$rowNumber}: Thumbnail image must be a numeric ID";
                    continue;
                }
                if (!\DB::table('uploads')->where('id', $rowArray['thumbnail_img'])->exists()) {
                    $allErrors[] = "Row {$rowNumber}: Thumbnail image ID {$rowArray['thumbnail_img']} does not exist";
                    continue;
                }
            }

            // Validate multi_categories if provided
            if (isset($rowArray['multi_categories'])) {
                $categoryIds = array_filter(explode(';', $rowArray['multi_categories']));
                foreach ($categoryIds as $categoryId) {
                    if (!is_numeric(trim($categoryId))) {
                        $allErrors[] = "Row {$rowNumber}: Invalid category ID format: {$categoryId}";
                        continue 2;
                    }
                    if (!\DB::table('categories')->where('id', trim($categoryId))->exists()) {
                        $allErrors[] = "Row {$rowNumber}: Category ID {$categoryId} does not exist";
                        continue 2;
                    }
                }
            }

            // Store valid updates
            $validUpdates[] = $rowArray;
        }

        // Second pass: Only update if NO errors found
        if (empty($allErrors)) {
            DB::beginTransaction();
            try {
                foreach ($validUpdates as $data) {
                    $product = Product::find($data['id']);

                    // Update product
                    $product->update([
                        'name' => $data['name'],
                        'description' => $data['description'] ?? '',
                        'category_id' => $data['category_id'],
                        'brand_id' => $data['brand_id'],
                        'tags' => $data['tags'] ?? '',
                        'weight' => $data['weight'],
                        'meta_title' => $data['meta_title'] ?? '',
                        'meta_description' => $data['meta_description'] ?? '',
                        'thumbnail_img' => $data['thumbnail_img'] ?? '',
                        'photos' => isset($data['photos']) ? str_replace(';', ',', $data['photos']) : '',
                        'discount_type' => ($data['discount_type'] == 'percent') ? 'percent' : 'amount',
                        'discount' => $data['discount'] ?? 0,
                        'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($data['name']))) . '-' . Str::random(5),

                    ]);

                    //Update English
                    ProductTranslation::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'lang'       => 'en',
                        ],
                        [
                            'name'        => $data['name'],
                        ]
                    );

                    //Update Arabic
                    ProductTranslation::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'lang'       => 'ar',
                        ],
                        [
                            'name'        => $data['name_ar'],
                        ]
                    );


                    // Add multi categories if provided
                    if (!empty($data['multi_categories'])) {
                        // Clean up old categories first
                        ProductCategory::where('product_id', $product->id)->delete();

                        // Remove duplicates in the row itself
                        $categoryIds = array_unique(array_filter(explode(';', $data['multi_categories'])));

                        foreach ($categoryIds as $category_id) {
                            ProductCategory::insert([
                                'product_id' => $product->id,
                                'category_id' => trim($category_id)
                            ]);
                        }
                    }



                    // Update product stock
                    ProductStock::where('product_id', $product->id)->update([
                        'price' => $data['price'],
                        'qty' => $data['qty'],
                        'sku' => $data['sku'],
                        'barcode' => $data['barcode'],
                        'box_qty' => $data['box_qty'],
                        'lead_time' => $data['lead_time'],
                        'made_in_country_id' => $data['made_in_country_id'],
                        'available_document' => $data['available_document'],
                        'msrp' => $data['msrp'],
                        'discount_type' => ($data['discount_type'] == 'percent') ? 'percent' : 'amount',
                        'discount' => $data['discount'] ?? 0,
                    ]);
                }
                DB::commit();
                flash(translate('Products updated successfully!'))->success();
            } catch (\Exception $e) {
                DB::rollback();
                flash(translate('Error updating products: ') . $e->getMessage())->error();
            }
        } else {
            session()->flash('import_errors', $allErrors);
            flash(translate('Import failed due to validation errors'))->error();
        }
    }
}
