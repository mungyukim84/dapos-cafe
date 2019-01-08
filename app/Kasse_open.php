<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kasse_Open extends Model
{

  protected $fillable = [
    'kasse_id', 'open_type', 'cashier_id', 'reason_id', 'amount', 'note', 'craeted_at'
  ];
}
