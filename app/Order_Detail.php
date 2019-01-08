<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = "order_details";

  protected $fillable = [
    'date', 'order_num', 'item_num', 'order_id', 'dept_id', 'is_cancelled', 'cancelled_date', 'item_id', 'qty', 'note',
    'sales_price', 'tax_rate', 'discount_rate', 'bookkeeping_number', 'cashier_id', 'kasse_id', 'created_at', 'updated_at'
  ];

  public function order() {
    return $this->belongsTo('App\Order', 'order_id');
  }

  public function cafeMenu() {
    return $this->belongsTo('App\Cafe_Menu', 'item_id');
  }

  public function kasse() {
    return $this->belongsTo('App\Kasse', 'kasse_id');
  }
}
