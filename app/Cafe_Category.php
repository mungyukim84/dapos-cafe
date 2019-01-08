<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cafe_Category extends Model
{

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = "cafe_categories";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'code', 'name', 'tab_order', 'created_by'
  ];

  public function cafeMenu() {
    return $this->hasMany('App\Cafe_Menu', 'category_id')->where('is_delete', 'N');
  }
}
