<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kasse extends Model
{

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = "kasses";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'port', 'name', 'short_name', 'pc_name'
  ];

  public function transaction() {
    return $this->hasMany('App\Transaction', 'kasse_id');
  }

  public function transactionDetail() {
    return $this->hasMany('App\Transaction_Detail', 'kasse_id');
  }
}
