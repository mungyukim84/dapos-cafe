<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = "vouchers";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'code', 'order_id', 'amount', 'type', 'expired_at', 'dept_id', 'kasse_id', 'cashier_id', 'created_at'
  ];

  public function order() {
    return $this->belongsTo('App\Order', 'order_id');
  }

}
