<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Storage;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
  private $rows = 0;
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
    $user = Auth::user();
    $rowIndex = 2;
    $allErrors = [];
    $validProducts = []; // Store validated products temporarily

    // Prepare rows with line numbers
    foreach ($rows as $index => $row) {
      $row['row'] = $rowIndex++;
      $rows[$index] = $row;
    }

    // First pass: Validate ALL data without creating anything
    foreach ($rows as $row) {
      $rowArray = is_array($row) ? $row : $row->toArray();
      $rowNumber = $rowArray['row'] ?? 'unknown';

      // Skip empty rows
      if (collect($rowArray)->except('row')->filter()->isEmpty()) {
        continue;
      }

      $rowArray['sku'] = isset($rowArray['sku']) ? (string) $rowArray['sku'] : null;
      $rowArray['barcode'] = isset($rowArray['barcode']) ? (string) $rowArray['barcode'] : null;

      // Validate main product data
      $validator = \Validator::make($rowArray, [
        'name' => 'required|max:255',
        'name_ar' => 'required|max:255',
        'description' => 'nullable',
        'tags' => 'nullable',
        'photo_link'=>'required|url',
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
        'weight' => 'required|numeric|min:0',
        'msrp' => 'required|numeric|min:0',
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

      // Only store for creation if no errors so far
      if (empty($allErrors)) {
        $validProducts[] = $rowArray;
      }
    }

    // Second pass: Only create if NO errors found
    if (empty($allErrors)) {
      foreach ($validProducts as $data) {
        try {

          // Download photo if photo_link is provided
          $thumbnailId = null;
          if (!empty($data['photo_link'])) {
            $thumbnailId = $this->downloadAndStoreImage($data['photo_link']);
          }

          $product = Product::create([
            'name' => $data['name'],
            'added_by' => 'admin',
            'user_id' => $user->id,
            'approved' => 1,
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'video_provider' => 'youtube',
            'video_link' => null,
            'tags' => $data['tags'] ?? null,
            'unit_price' => $data['price'],
            'current_stock' => $data['qty'],
            'weight' => $data['weight'],
            'unit' => 'Pc',
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'discount_type' => ($data['discount_type'] == 'percent') ? 'percent' : 'amount',
            'discount' => $data['discount'] ?? 0,
            'est_shipping_days' => 5,
            'colors' => json_encode([]),
            'choice_options' => json_encode([]),
            'variations' => json_encode([]),
            'variant_product' => 1,
            'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($data['name']))) . '-' . Str::random(5),
            'thumbnail_img' => $thumbnailId,
            'photos' => str_replace(';', ',', $data['photos'] ?? ''),
          ]);

          // Create English translation
          ProductTranslation::create([
            'product_id' => $product->id,
            'lang' => 'en',
            'name' => $data['name'],
            'unit' => 'Pc',
          ]);

          // Create Arabic translation
          if (!empty($data['name_ar'])) {
            ProductTranslation::create([
              'product_id' => $product->id,
              'lang' => 'ar',
              'name' => $data['name_ar'],
              'unit' => 'Pc',
            ]);
          }

          // Add multi categories if provided
          if (!empty($data['multi_categories'])) {
            foreach (explode(';', $data['multi_categories']) as $category_id) {
              ProductCategory::insert([
                'product_id' => $product->id,
                'category_id' => trim($category_id)
              ]);
            }
          }

          // Create single product stock entry
          ProductStock::create([
            'product_id' => $product->id,
            'variant' => $product->name,
            'variant_name' => $product->name,
            'price' => $data['price'],
            'sku' => $data['sku'],
            'qty' => $data['qty'],
            'barcode' => $data['barcode'] ?? '',
            'box_qty' => $data['box_qty'] ?? 0,
            'lead_time' => $data['lead_time'] ?? '',
            'made_in_country_id' => $data['made_in_country_id'] ?? null,
            'available_document' => $data['available_document'] ?? null,
            'msrp' => $data['msrp'] ?? 0,
            'image' => null,
            'color_id' => null,
            'has_special_discount' => 0,
            'discount_type' => $product->discount_type,
            'discount' => $product->discount,
          ]);
        } catch (\Exception $e) {
          $allErrors[] = "Failed to create product '{$data['name']}': " . $e->getMessage();
        }
      }
    }

    // Handle final results
    if (empty($allErrors)) {
      flash(translate(' All products imported successfully!'))->success();
    } else {
      session()->flash('import_errors', $allErrors);
      flash(translate(' Import aborted due to errors'))->error();
    }
  }

  public function model(array $row)
  {
    ++$this->rows;
  }

  public function getRowCount(): int
  {
    return $this->rows;
  }

  public function rules(): array
  {
    return [
      // Can also use callback validation rules
      'unit_price' => function ($attribute, $value, $onFailure) {
        if (!is_numeric($value)) {
          $onFailure('Unit price is not numeric');
        }
      }
    ];
  }

  public function downloadThumbnail($url)
  {
    try {
      $upload = new Upload;
      $upload->external_link = $url;
      $upload->type = 'image';
      $upload->save();

      return $upload->id;
    } catch (\Exception $e) {
    }
    return null;
  }

  public function downloadGalleryImages($urls)
  {
    $data = array();
    foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
      $data[] = $this->downloadThumbnail($url);
    }
    return implode(',', $data);
  }

  /**
   * Download image from URL and store it in uploads table
   *
   * @param string $url
   * @return int|null Upload ID
   */
  private function downloadAndStoreImage($url)
  {
    try {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);

      $imageData = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($httpCode != 200 || $imageData === false) {
        return null;
      }

      $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
      $extension = isset($pathInfo['extension']) ? strtolower($pathInfo['extension']) : 'jpg';
      $originalName = isset($pathInfo['filename']) ? $pathInfo['filename'] : 'image';

      $fileName = 'uploads/all/' . Str::random(40) . '.' . $extension;
      $filePath = public_path($fileName);

      $directory = dirname($filePath);
      if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
      }

      file_put_contents($filePath, $imageData);
      $fileSize = filesize($filePath);

      $upload = new Upload();
      $upload->file_original_name = $originalName;
      $upload->file_name = $fileName;
      $upload->user_id = auth()->id() ?? null;
      $upload->file_size = $fileSize;
      $upload->extension = $extension;
      $upload->type = 'image';
      $upload->external_link = null;
      $upload->save();

      return $upload->id;

    } catch (\Exception $e) {
      \Log::error('Image download failed: ' . $e->getMessage());
      return null;
    }
  }
}
