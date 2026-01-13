<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Upload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use App\Traits\PreventDemoModeChanges;

class BrandsImport implements ToCollection, WithHeadingRow
{
  use PreventDemoModeChanges;

  private $rows = 0;

  public function collection(Collection $rows)
  {
    $rowIndex = 2;
    $allErrors = [];
    $validBrands = [];
    $seenNames = [];

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

      // Validate main brand data
      $validator = \Validator::make($rowArray, [
        'name' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'logo_id' => 'missing',
        'logo_url' => 'required|url',
        'web_banner_id_ar' => 'missing',
        'mobile_banner_id_ar' => 'missing',
        'web_banner_id_en' => 'missing',
        'mobile_banner_id_en' => 'missing',
        'web_banner_url_ar' => 'nullable|url',
        'mobile_banner_url_ar' => 'nullable|url',
        'web_banner_url_en' => 'nullable|url',
        'mobile_banner_url_en' => 'nullable|url',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
      ]);

      if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
          $allErrors[] = "Row {$rowNumber}: {$error}";
        }
        continue;
      }

      // Check for duplicates within the import file
      $nameLower = strtolower(trim($rowArray['name']));
      if (isset($seenNames[$nameLower])) {
        $allErrors[] = "Row {$rowNumber}: Duplicate brand name '{$rowArray['name']}' found in row {$seenNames[$nameLower]}";
        continue;
      }
      $seenNames[$nameLower] = $rowNumber;

      // Store all valid rows for creation
      $validBrands[] = $rowArray;
    }

    // Second pass: Only create brands if NO validation errors found
    if (empty($allErrors)) {
      foreach ($validBrands as $data) {
        try {
          // Download images and get upload IDs
          $logoId = !empty($data['logo_id']) ? $data['logo_id'] : (!empty($data['logo_url']) ? $this->downloadAndStoreImage($data['logo_url']) : null);
          $webBannerIdAr = !empty($data['web_banner_id_ar']) ? $data['web_banner_id_ar'] : (!empty($data['web_banner_url_ar']) ? $this->downloadAndStoreImage($data['web_banner_url_ar']) : null);
          $webBannerIdEn = !empty($data['web_banner_id_en']) ? $data['web_banner_id_en'] : (!empty($data['web_banner_url_en']) ? $this->downloadAndStoreImage($data['web_banner_url_en']) : null);
          $mobileBannerIdAr = !empty($data['mobile_banner_id_ar']) ? $data['mobile_banner_id_ar'] : (!empty($data['mobile_banner_url_ar']) ? $this->downloadAndStoreImage($data['mobile_banner_url_ar']) : null);
          $mobileBannerIdEn = !empty($data['mobile_banner_id_en']) ? $data['mobile_banner_id_en'] : (!empty($data['mobile_banner_url_en']) ? $this->downloadAndStoreImage($data['mobile_banner_url_en']) : null);

          // Check if required logo download failed
          if (empty($logoId)) {
            $allErrors[] = "Row {$data['row']}: Failed to download logo for brand '{$data['name']}'";
            break;
          }

          $brand = Brand::create([
            'name' => $data['name'],
            'name_trans' => [
              'ar' => $data['name_ar'],
              'en' => $data['name'],
            ],
            'logo' => $logoId,
            'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $data['name'])) . '-' . Str::random(5),
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
          ]);

          if (isset($webBannerIdAr) && $webBannerIdAr != null) {
            $brand->setImage('web_banner', $webBannerIdAr, "ar");
          }
          if (isset($mobileBannerIdAr) && $mobileBannerIdAr != null) {
            $brand->setImage('mobile_banner', $mobileBannerIdAr, "ar");
          }
          if (isset($webBannerIdEn) && $webBannerIdEn != null) {
            $brand->setImage('web_banner', $webBannerIdEn, "en");
          }
          if (isset($mobileBannerIdEn) && $mobileBannerIdEn != null) {
            $brand->setImage('mobile_banner', $mobileBannerIdEn, "en");
          }
        } catch (\Exception $e) {
          $allErrors[] = "Row {$data['row']}: Failed to create brand '{$data['name']}': " . $e->getMessage();
          break;
        }
      }
    }

    // Update row count
    $this->rows = count($validBrands);

    // Handle final results
    if (empty($allErrors)) {
      flash(translate('All brands imported successfully!'))->success();
    } else {
      session()->flash('import_errors', $allErrors);
      flash(translate('Import aborted due to errors'))->error();
    }
  }

  public function getRowCount(): int
  {
    return $this->rows;
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
