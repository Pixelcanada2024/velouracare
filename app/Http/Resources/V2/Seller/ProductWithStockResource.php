<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithStockResource extends JsonResource
{

  public function toArray($request)
  {
    // this data is stored also in order variation in order details table as json

    $this->loadMissing('stocks');

    $stock = $this->stocks->first();

    return [
      ...(new ProductResource($this))->toArray($request),
      ...(new ProductStockResource($stock))->toArray($request),
    ];
  }
}
