<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class FrequentlyBoughtProduct extends Model
{
    use HasFactory,PreventDemoModeChanges;


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function frequently_bought_product()
    {
        return $this->belongsTo(Product::class, 'frequently_bought_product_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function thumbnail()
    {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

}
