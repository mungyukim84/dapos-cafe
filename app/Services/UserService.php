<?php
namespace App\Services;

use App\Kasse;
use App\User;

use Auth;

class UserService {
  public function bindKasseToUser() {
    $kasse = Kasse::where('pc_ip', $_SERVER['REMOTE_ADDR'])->first();
    User::where('id', Auth::User()->id)->update(['kasse_id' => empty($kasse) ? 4 : $kasse->id]);
  }
}
