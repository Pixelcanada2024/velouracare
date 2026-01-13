<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderDetailsExport implements FromCollection, WithMapping, WithHeadings
{
    use PreventDemoModeChanges;

    protected $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    public function collection()
    {
        return Order::find($this->order_id)->load('orderDetails')->orderDetails;
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Brand',
            'Barcode',
            'SKU',
            'Unit Price',
            'Quantity(Pcs)',
            'Total Price',
            "Currency"
        ];
    }

    public function map($orderDetail): array
    {
        return [
            $orderDetail->variation['name'],
            $orderDetail->variation['brand'],
            $orderDetail->variation['barcode'],
            $orderDetail->variation['sku'],
            $orderDetail->variation['price'],
            $orderDetail->variation['box_qty'] * $orderDetail->quantity,
            $orderDetail->price,
            $orderDetail->order->additional_info['invoice']['currency']
        ];
    }
}