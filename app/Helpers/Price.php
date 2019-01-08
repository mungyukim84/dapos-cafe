<?php
namespace App\Helpers;

class Price {
  public static function getNetto(float $salesPrice, float $taxRate):float {
    return round($salesPrice / (1 + $taxRate), 2);
  }

  public static function getTax(float $salesPrice, float $taxRate):float {
    return round($salesPrice - self::getNetto($salesPrice, $taxRate), 2);
  }
}
