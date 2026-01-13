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

class ProductsImageImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
  private $rows = 0;
  private $countryCodeToId = [];

  public function __construct()
  {
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
        'sku' => 'required|max:255',
        'barcode' => 'required|string|max:255',
      ]);

      if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
          $allErrors[] = "Row {$rowNumber}: {$error}";
        }
        continue;
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
            $thumbnailId = $this->downloadAndStoreImage($data['photo_link'], $data['sku']);
          }

          if (!$thumbnailId) {
            $allErrors[] = "Failed to download image for product '{$data['name']}' from URL: {$data['photo_link']}";
            continue;
          }

          Product::whereHas('stocks', function ($query) use ($data) {
              $query->where('sku', $data['sku'])
              ->where('barcode', $data['barcode']);
            })
            ->update(['thumbnail_img' => $thumbnailId]);
        } catch (\Exception $e) {
          $allErrors[] = "Failed to update  product image '{$data['sku']}': " . $e->getMessage();
        }
      }
    }

    // Handle final results
    if (empty($allErrors)) {
      flash(translate(' All products updated successfully!'))->success();
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
    return [];
  }

  /**
   * Download image from URL and store it in uploads table
   *
   * @param string $url
   * @return int|null Upload ID
   */
  private function downloadAndStoreImage($sallaUrl, $sku)
  {
    try {
      $response = \Illuminate\Support\Facades\Http::withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'Referer' => 'https://salla.sa/',
      ])->get($sallaUrl);

      if ($response->successful()) {
        $str = \Illuminate\Support\Str::random(40);
        $mime = $response->header('Content-Type'); // e.g. image/jpeg
        $extension = match ($mime) {
          'image/jpeg' => '.jpg',
          'image/png' => '.png',
          'image/webp' => '.webp',
          default => '.jpg',
        };
        $folders = 'uploads/all';
        $dir = public_path($folders);
        if (!is_dir($dir)) {
          mkdir($dir, 0755, true);
        }
        $fileName = $folders . '/' . $str . $extension;
        $filePath = public_path($fileName);
        file_put_contents($filePath, $response->body());
        $fileSize = filesize($filePath);

        $upload = new \App\Models\Upload();
        $upload->file_original_name = "productSku-" . $sku;
        $upload->file_name = $fileName;
        $upload->user_id = 15;
        $upload->file_size = $fileSize;
        $upload->extension = $extension;
        $upload->type = 'image';
        $upload->external_link = null;
        $upload->save();

        return $upload->id;

      }

    } catch (\Exception $e) {
      \Log::error('Image download failed: ' . $e->getMessage());
      return null;
    }
  }
}
