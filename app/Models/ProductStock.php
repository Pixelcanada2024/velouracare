<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class ProductStock extends Model
{
    use PreventDemoModeChanges;

    protected $guarded =['id'];
    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function wholesalePrices() {
        return $this->hasMany(WholesalePrice::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function imageFile()
    {
        return $this->belongsTo(Upload::class, 'image');
    }

  public function country()
  {
      return $this->belongsTo(Country::class, 'made_in_country_id');
  }

}
