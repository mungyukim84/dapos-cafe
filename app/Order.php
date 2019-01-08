<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = "orders";

  protected $fillable = [
    'date', 'order_num', 'receipt_num', 'cancelled_receipt_num', 'dept_id', 'is_cancelled', 'cancelled_date', 'original_price', 'sales_price', 'payment_method',
    'creditcard_type_id', 'creditcard_amount', 'eccard_amount', 'cash_amount', 'cash_received', 'voucher_amount', 'voucher_code', 'is_takeout', 'delievery_cost',
    'is_voucher_sales', 'member_id', 'kasse_id', 'cashier_id', 'created_at', 'updated_at'
  ];

  public function orderDetail() {
    return $this->hasMany('App\Order_Detail', 'order_id');
  }

  public function kasse() {
    return $this->belongsTo('App\Kasse', 'kasse_id');
  }

  public function voucher() {
    return $this->hasMany('App\Voucher', 'order_id');
  }
}
