<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Support\Facades\Log;

class CartItemsImport implements ToCollection, WithHeadingRow
{
    use PreventDemoModeChanges;

    private int $rows = 0;
    private int $validRows = 0;
    private int $skippedRows = 0;

    public array $items = [
        'all_barcodes'         => [],
        'valid_barcodes_ids_obj' => [],
        'valid_barcodes'       => [],
        'filtered_quantities'  => [],
        'errors'               => [],
        'count_valid_barcodes' => 0,
    ];

    public function collection(Collection $rows): void
    {
        Log::info('🔹 Starting CartItemsImport...');

        // --- Step 1: Extract and normalize barcodes ---
        $barcodes = $rows->pluck('barcode')
            ->map(fn($barcode) => trim((string) $barcode))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $intBarcodes = collect($barcodes)->map(fn($barcode) => (int) $barcode)->toArray();

        $this->items['all_barcodes'] = $barcodes;
        Log::info('🧾 Extracted Barcodes:', $barcodes);

        if (empty($barcodes)) {
            Log::warning('⚠️ No barcodes found in Excel file.');
            $this->items['errors'][] = 'No barcodes found in the uploaded file.';
            return;
        }

        // --- Step 2: Fetch valid barcodes from DB ---
        $validStocks = ProductStock::whereIn('barcode', $intBarcodes)
            ->orWhereIn('barcode', $barcodes)
            ->select('product_id', 'barcode')
            ->get()
            ->toArray();

        $this->items['valid_barcodes_ids_obj'] = $validStocks;
        $this->items['valid_barcodes'] = array_column($validStocks, 'barcode');
        $this->items['count_valid_barcodes'] = count($validStocks);

        Log::info('✅ Valid Product Stocks:', $validStocks);
        Log::info('📦 Valid Barcodes:', $this->items['valid_barcodes']);
        Log::info('📊 Count of Valid Barcodes: ' . $this->items['count_valid_barcodes']);

        if (empty($validStocks)) {
            Log::warning('⚠️ No valid barcodes found in database.');
        }

        // --- Step 3: Process each Excel row ---
        foreach ($rows as $index => $row) {
            $this->rows++;

            $barcode = trim((string)($row['barcode'] ?? ''));
            $boxQuantity = $row['box_quantity'] ?? null;
            $rowNumber = $index + 2; // +2 for header + Excel row offset

            Log::info("➡️ Processing Row {$rowNumber}: Barcode={$barcode}, Quantity={$boxQuantity}");

            // --- Validate barcode ---
            if ($barcode === '') {
                $this->skipRow($rowNumber, 'Missing barcode');
                continue;
            }

            if (!in_array($barcode, $this->items['valid_barcodes'])) {
                $this->skipRow($rowNumber, "Invalid barcode '{$barcode}'");
                continue;
            }

            // --- Validate quantity ---
            if (!is_numeric($boxQuantity) || $boxQuantity < 1) {
                $this->skipRow($rowNumber, "Invalid quantity '" . ($boxQuantity ?? 'null') . "'");
                continue;
            }

            // --- Find product_id by barcode ---
            $match = collect($this->items['valid_barcodes_ids_obj'])
                ->firstWhere('barcode', $barcode);

            if (!$match) {
                $this->skipRow($rowNumber, "Product not found for barcode '{$barcode}'");
                continue;
            }

            $productId = (int) $match['product_id'];
            $this->items['filtered_quantities'][$productId] = (int) $boxQuantity;
            $this->validRows++;

            Log::info("✅ Row {$rowNumber} Valid → ProductID={$productId}, Quantity={$boxQuantity}");
        }

        Log::info('🏁 Import Completed. Summary:', $this->getSummary());
    }

    /**
     * Helper to mark a row as skipped and record an error.
     */
    private function skipRow(int $rowNumber, string $message): void
    {
        $this->skippedRows++;
        $errorMessage = "Row {$rowNumber}: {$message}";
        $this->items['errors'][] = $errorMessage;
        Log::warning("⛔ {$errorMessage}");
    }


    public function getItems(): array
    {
        return $this->items;
    }

        public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getSummary(): array
    {
        return [
            'total_rows'       => $this->rows,
            'valid_rows'       => $this->validRows,
            'skipped_rows'     => $this->skippedRows,
            'unique_products'  => count($this->items['filtered_quantities']),
            'has_errors'       => !empty($this->items['errors']),
        ];
    }
}
