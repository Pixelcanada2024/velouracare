<?php 

namespace App\Enums;

class OrderStatus
{
  public static function getOrderStatusOrderArray () 
  {
    return [
      'processing' => 1,
      'cancelled' => 2,
      'payment' => 3,
      'confirmed' => 4,
      'picked_up' => 5,
      'on_the_way' => 6,
      'delivered' => 7,
    ];
  }
}