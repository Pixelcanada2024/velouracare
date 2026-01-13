<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInfo extends Model
{
  use HasFactory;
  protected $guarded = ['id'];

  public function User()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function shopCountry()
  {
    return $this->belongsTo(Country::class, 'shop_country_id');
  }
  public function shopState()
  {
    return $this->belongsTo(State::class, 'shop_state_id');
  }
  public function companyCountry()
  {
    return $this->belongsTo(Country::class, 'company_country_id');
  }
  public function companyState()
  {
    return $this->belongsTo(State::class, 'company_state_id');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_id');
  }
}
