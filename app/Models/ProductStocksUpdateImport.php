<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use DB;
use Validator;

class ProductStocksUpdateImport implements ToCollection, WithHeadingRow
{
  public function collection(Collection $rows)
  {
    $allErrors = [];
    $validUpdates = [];

    // First pass: Validate ALL data
    foreach ($rows as $index => $row) {
      $rowArray = is_array($row) ? $row : $row->toArray();
      $rowNumber = $index + 2;

      if (empty($rowArray['stock_id'])) {
        continue;
      }

      $validator = Validator::make($rowArray, [
        'stock_id' => 'required|numeric|exists:product_stocks,id',
        'variant_name' => 'required|string|max:255',
        'sku' => 'required|max:255',
        'price' => 'required|numeric|min:0',
        'qty' => 'required|numeric|min:0',
        'has_special_discount' => 'required|boolean',
        'discount_type' => 'nullable|required_if:has_special_discount,1|in:amount,percent',
        'discount' => 'required_if:has_special_discount,1|min:0',
      ], [
        'stock_id.required' => 'Stock ID is required',
        'stock_id.exists' => 'Stock with this ID does not exist',
        'variant_name.required' => 'Variant name is required',
        'price.required' => 'Price is required',
        'qty.required' => 'Quantity is required',
        'has_special_discount.required' => 'Special discount flag is required',
      ]);

      if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
          $allErrors[] = "Row {$rowNumber}: {$error}";
        }
        continue;
      }

      if (!empty($rowArray['image'])) {
        if (!is_numeric($rowArray['image'])) {
          $allErrors[] = "Row {$rowNumber}: Variant image must be a numeric ID";
          continue;
        }
        if (!\DB::table('uploads')->where('id', $rowArray['image'])->exists()) {
          $allErrors[] = "Row {$rowNumber}: Variant image ID {$rowArray['image']} does not exist";
          continue;
        }
      }

      // Get the product stock and related product
      $stock = ProductStock::with('product')->find($rowArray['stock_id']);

      if (!$stock) {
        $allErrors[] = "Row {$rowNumber}: Stock with ID {$rowArray['stock_id']} not found";
        continue;
      }

      // Store valid updates
      $validUpdates[] = [
        'row' => $rowNumber,
        'data' => $rowArray,
        'stock' => $stock
      ];
    }

    // Second pass: Only update if NO errors found
    if (empty($allErrors)) {
      foreach ($validUpdates as $update) {
        try {
          DB::transaction(function () use ($update) {
            $stock = $update['stock'];
            $rowArray = $update['data'];
            $hasSpecial = (bool)$rowArray['has_special_discount'];

            $updateData = [
              'variant' => $rowArray['variant_name'],
              'variant_name' => $rowArray['variant_name'],
              'price' => $rowArray['price'],
              'sku' => $rowArray['sku'],
              'qty' => $rowArray['qty'],
              'has_special_discount' => $hasSpecial ? 1 : 0,
              'discount_type' => $hasSpecial ? $rowArray['discount_type'] : $stock->product->discount_type,
              'discount' => $hasSpecial ? $rowArray['discount'] : $stock->product->discount,
            ];

            // Update image only if provided
            if (!empty($rowArray['image'])) {
              $updateData['image'] = $rowArray['image'];
            }

            $stock->update($updateData);
          });
        } catch (\Exception $e) {
          $allErrors[] = "Row {$update['row']}: Update failed - " . $e->getMessage();
        }
      }
    }

    if (count($allErrors) > 0) {
      session()->flash('import_errors', $allErrors);
      session()->flash('import_result', 'error');
      flash(translate('⚠️ Import aborted due to errors'))->error();
    } else {
      session()->flash('import_result', 'success');
      session()->flash('updated_count', count($validUpdates));
      flash('✅ All Variants updated successfully!')->success();
    }
  }
}
