<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockResource extends JsonResource
{
  public function toArray($request)
  {
    // this data is stored also in order variation in order details table as json


    $priceIsHidden =  $request?->price_is_hidden ?? true;
    static $exchangeRate = null;

    if (!$exchangeRate) {
        $isAmerica = config('app.sky_type') == 'America';
        $currencyCode = $isAmerica ? 'USD' : 'SAR';
        $currentCurrency = session()->get('currency', $currencyCode);
        $exchangeRate = $isAmerica ? 1 : (\App\Models\Currency::where('code', $currentCurrency)?->first()?->exchange_rate ?? 1);
    }

    // Calculate discounted price if applicable
    $hasDiscount = false;
    if ($priceIsHidden) {
        $originalPrice = 0;
        $currentPrice = 0;
    } else {
        $originalPrice = $this?->price  * $exchangeRate;
        $currentPrice = $this?->price  * $exchangeRate;
    }

    if ($this->discount > 0) {
      $hasDiscount = true;
      if ($this->discount_type == 'percent') {
        $currentPrice = $originalPrice * ( 1 -  $this->discount / 100);
      } else {
        $currentPrice = $originalPrice - $this->discount * $exchangeRate;
      }
    }


    $lead_time = $this->lead_time ?? "2-3 weeks";

    $patterns = [
        '/\bweeks?\b/i' => 'أسابيع',
        '/\bweek\b/i'   => 'أسبوع',
        '/\bdays?\b/i'  => 'أيام',
        '/\bday\b/i'    => 'يوم',
    ];

    $locale = app()->getLocale();
    if ($locale == 'ar') {
      $lead_time = preg_replace(array_keys($patterns), array_values($patterns), $lead_time);
    }

    $variant = [
      'variant_id' => $this->id,
      'product_id' => $this->product_id,
      'sku' => $this->sku ,
      'barcode' => $this->barcode ,
      'qty' => $this->qty, // stock quantity per piece
      'box_qty' => $this->box_qty ,
      'box_stock_qty' => (  $this->qty / $this->box_qty  ),
      'lead_time' => $lead_time,
      'made_in_country_id' => $this->made_in_country_id,
      'made_in_country' => $this->country?->name,
      'available_document' => match ($this->available_document) {
        0 => app()->getLocale() == 'ar' ? 'فاتورة تجارية' : 'Commercial Invoice',
        1 => 'MSDS',
        2 => app()->getLocale() == 'ar' ? 'MSDS, فاتورة تجارية' : 'MSDS, Commercial Invoice',
        default => null,
      },
      'msrp' => $priceIsHidden 
      ? number_format(0, 2, '.', '') 
      : number_format($this->msrp * $exchangeRate, 2, '.', ''),
    ];

    if ($priceIsHidden) {
      $variant['price'] = number_format(0, 2, '.', '');
      $variant['originalPrice'] = number_format(0, 2, '.', '');
      $variant['promotion'] = 0;
      $variant["order_price_per_box"] = number_format(0, 2, '.', '');

    } else if (!!request()?->user()?->id) {

      $variant['price'] = number_format($currentPrice, 2, '.', '');
      $variant['originalPrice'] = number_format($originalPrice, 2, '.', '');
      $variant['promotion'] = $hasDiscount
        ? number_format((1 - $currentPrice / $originalPrice) * 100, 0, '.', '') . "%"
        : 0;
      $variant["order_price_per_box"] = number_format($variant['price'] * $variant['box_qty'], 2, '.', '');

    } else {
      $variant["order_price_per_box"] = number_format($variant['msrp'] * $variant['box_qty'], 2, '.', '');
    }

    return $variant;
  }
}
