<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cafe_Menu extends Model
{

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $table = "cafe_menus";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'description', 'production_cost', 'sales_price', 'recycle_cost',
        'tax_rate_in', 'tax_rate_out', 'ean', 'ean_2', 'ean_3', 'created_at', 'created_by'
    ];

    public function category() {
      return $this->belongsTo('App\Cafe_Category', 'category_id');
    }

    public function orderDetail() {
      return $this->hasMany('App\Order_Detail', 'item_id');
    }
}
