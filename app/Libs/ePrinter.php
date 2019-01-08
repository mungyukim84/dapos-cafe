<?php
namespace App\Libs;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use App\Helpers\Price;
use App\Kasse;

class ePrinter {
  private $ePrinter;
  private $aLeft = Printer::JUSTIFY_LEFT;
  private $aCenter = Printer::JUSTIFY_CENTER;
  private $aRight = Printer::JUSTIFY_RIGHT;

  public function __construct()
  {
    $kasse = Kasse::where('pc_ip', $_SERVER['REMOTE_ADDR'])->first();
    if(empty($kasse)) {
      $connector = new WindowsPrintConnector("posprinter");
    }
    else {
      $connector = new WindowsPrintConnector("smb://".$kasse->pc_account.":".$kasse->pc_password."@".$kasse->pc_ip."/posprinter");
    }
    $this->ePrinter = new Printer($connector);
  }

  public function printOK($order, $copy = 'N', $bellNo = null) {
    try {
      for($i=0;$i<2;$i++) {
        if($i == 0) {
          $this->header('Accepted', '- For Shop -', $copy);
        }
        else {
          $this->header('Accepted', '- For Customer -', $copy);
        }

        $brutto7Sum = 0;
        $brutto19Sum = 0;
        $priceSum = 0;
        $cafeArray = [];
        $kitchenArray = [];

        $this->ePrinter->text('Kasse ID . '.$order->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Transaktions-Nr . '.$order->receipt_num);
        $this->ePrinter->feed(1);
        if(!empty($bellNo)) {
          $this->ePrinter->text('Bell-Nr . '.$bellNo);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);

        foreach($order->orderDetail as $item) {
          $UVP = (float)$item->cafeMenu->sales_price;
          $UVPStr = number_format($item->cafeMenu->sales_price, 2, ',', '.');
          $UVPWithClass = $UVPStr.' '.((float)$item->tax_rate == 0.07 ? 'A' : 'B');

          $price = 0;
          $price = round($UVP * (int)$item->qty, 2);
          $priceStr = number_format($price, 2, ',', '.');

          $itemName = substr(str_replace(array("\n", "\r"), "", $item->cafeMenu->name), 0, 25);
          $sumStr = $item->qty.' * '.$UVPStr;
          $this->ePrinter->text($itemName.$this->textPos($itemName, $UVPWithClass).$UVPWithClass);
          $this->ePrinter->feed(1);

          $this->ePrinter->setEmphasis(true);
          if($item->discount_rate > 0) {
            $UVP = round((float)$item->sales_price, 2);
            $price = $UVP * (int)$item->qty;
            $priceStr = number_format($price, 2, ',', '.');
            $sumStr .= ' * -'.round((float)$item->discount_rate* 100, 0).'%';

            $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
            $this->ePrinter->feed(1);
          }
          else {
            $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
            $this->ePrinter->feed(1);
          }
          $this->ePrinter->setEmphasis(false);

          if((float)$item->tax_rate === 0.07) {
            $brutto7Sum += $price;
          }
          else if((float)$item->tax_rate === 0.19) {
            $brutto19Sum += $price;
          }
          $priceSum += $price;

          if($i == 1) {
            if((int)$item->cafeMenu->category_id < 6) {
              array_push($cafeArray, $item);
            }
            else {
              array_push($kitchenArray, $item);
            }
          }
        }

        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setEmphasis(true);
        $this->ePrinter->setTextSize(2, 1);
        $this->ePrinter->text('Summe'.$this->textPos('Summe', number_format($priceSum, 2, ',', '.').' EUR', 2).number_format($priceSum, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->setEmphasis(false);

        $this->ePrinter->feed(1);
        $this->ePrinter->text('A = 7%, B = 19%');
        $this->ePrinter->feed(1);

        if($brutto7Sum > 0) {
          $netto7Sum = Price::getNetto($brutto7Sum, 0.07);
          $this->ePrinter->text('7% Netto.'.$this->textPos('7% Netto.', number_format($netto7Sum, 2, ',', '.').' EUR').number_format($netto7Sum, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('MwSt 7%'.$this->textPos('MwSt 7%', number_format($brutto7Sum - $netto7Sum, 2, ',', '.').' EUR').number_format($brutto7Sum - $netto7Sum, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if ($brutto19Sum > 0) {
          $netto19Sum = Price::getNetto($brutto19Sum, 0.19);
          $this->ePrinter->text('19% Netto.'.$this->textPos('19% Netto.', number_format($netto19Sum, 2, ',', '.').' EUR').number_format($netto19Sum, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('MwSt 19%'.$this->textPos('MwSt 19%', number_format($brutto19Sum - $netto19Sum, 2, ',', '.').' EUR').number_format($brutto19Sum - $netto19Sum, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->feed(1);

        if(abs($order->creditcard_amount) != 0) {
          $this->ePrinter->text('Credit Card: '.$this->textPos('Credit Card: ', number_format($order->creditcard_amount, 2, ',', '.').' EUR').number_format($order->creditcard_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs($order->eccard_amount) != 0) {
          $this->ePrinter->text('EC Card: '.$this->textPos('EC Card: ', number_format($order->eccard_amount, 2, ',', '.').' EUR').number_format($order->eccard_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs($order->voucher_amount) != 0) {
          $this->ePrinter->text('Gutschein('.$order->voucher_code.'): '.$this->textPos('Gutschein('.$order->voucher_code.'): ', number_format($order->voucher_amount, 2, ',', '.').' EUR').number_format($order->voucher_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs($order->cash_amount) != 0) {
          $this->ePrinter->text('BAR: '.$this->textPos('BAR: ', number_format($order->cash_amount, 2, ',', '.').' EUR').number_format($order->cash_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('BAR received: '.$this->textPos('BAR received: ', number_format($order->cash_received, 2, ',', '.').' EUR').number_format($order->cash_received, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('BAR change: '.$this->textPos('BAR change: ', number_format(((float)$order->cash_received - (float)$order->cash_amount), 2, ',', '.').' EUR').number_format(((float)$order->cash_received - (float)$order->cash_amount), 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        $this->footer();
      }

      if(count($cafeArray) > 0) {
        $this->ePrinter->setJustification($this->aLeft);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Kasse ID . '.$order->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Transaktions-Nr . '.$order->receipt_num);
        $this->ePrinter->feed(1);
        if(!empty($bellNo)) {
          $this->ePrinter->text('Bell-Nr . '.$bellNo);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 2);
        foreach($cafeArray as $item) {
          $itemName = substr(str_replace(array("\n", "\r"), "", $item->cafeMenu->name), 0, 25);
          $this->ePrinter->text($itemName.$this->textPos($itemName, $item->qty).$item->qty);
          $this->ePrinter->feed(1);
        };
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(2);
        $this->ePrinter->cut();
      }

      if(count($kitchenArray) > 0) {
        $this->ePrinter->setJustification($this->aLeft);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Kasse ID . '.$order->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Transaktions-Nr . '.$order->receipt_num);
        $this->ePrinter->feed(1);
        if(!empty($bellNo)) {
          $this->ePrinter->text('Bell-Nr . '.$bellNo);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 2);
        foreach($kitchenArray as $item) {
          $itemName = substr(str_replace(array("\n", "\r"), "", $item->cafeMenu->name), 0, 25);
          $this->ePrinter->text($itemName.$this->textPos($itemName, $item->qty).$item->qty);
          $this->ePrinter->feed(1);
        };
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(2);
        $this->ePrinter->cut();
      }

//      if(abs((float)$order->cash_amount) != 0) {
//        $this->ePrinter->pulse();
//      }
      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function cancelData($order, $copy = 'N') {
    try {
      for($i=0;$i<2;$i++) {
        $brutto7Sum = 0;
        $brutto19Sum = 0;

        if($i == 0) {
          $this->header('Canceled', '- For Shop -', $copy);
        }
        else {
          $this->header('Canceled', '- For Customer -', $copy);
        }

        $this->ePrinter->text('Kasse ID . '.$order->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);

        foreach($order->orderDetail as $item) {
          $UVP = round((float)$item->sales_price, 2);
          $price = $UVP * (int)$item->qty;
          $itemStr = substr(str_replace(array("\n", "\r"), "",$item->cafeMenu->name), 0 , 25).' * '.$item->qty;
          $priceStr = number_format($price * -1, 2, ',', '.').($item->tax_rate == 0.07 ? ' A' : ' B');
          $this->ePrinter->text($itemStr.$this->textPos($itemStr, $priceStr).$priceStr);
          $this->ePrinter->feed(1);

          if((float)$item->tax_rate == 0.07) {
            $brutto7Sum += $price;
          }
          else if((float)$item->tax_rate == 0.19) {
            $brutto19Sum += $price;
          }
        }

        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setEmphasis(true);
        $this->ePrinter->setTextSize(2, 1);
        $this->ePrinter->text('Summe'.$this->textPos('Summe', number_format((float)$order->sales_price * -1, 2, ',', '.').' EUR', 2).number_format((float)$order->sales_price * -1, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->setEmphasis(false);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('A = 7%, B = 19%');
        $this->ePrinter->feed(1);

        if($brutto7Sum > 0) {
          $netto7Sum = Price::getNetto($brutto7Sum, 0.07);
          $this->ePrinter->text('7% Netto.'.$this->textPos('7% Netto.', number_format($netto7Sum * -1, 2, ',', '.').' EUR').number_format($netto7Sum * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('MwSt 7%'.$this->textPos('MwSt 7%', number_format(($brutto7Sum - $netto7Sum) * -1, 2, ',', '.').' EUR').number_format(($brutto7Sum - $netto7Sum) * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if ($brutto19Sum > 0) {
          $netto19Sum = Price::getNetto($brutto19Sum, 0.19);
          $this->ePrinter->text('19% Netto.'.$this->textPos('19% Netto.', number_format($netto19Sum * -1, 2, ',', '.').' EUR').number_format($netto19Sum * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
          $this->ePrinter->text('MwSt 19%'.$this->textPos('MwSt 19%', number_format(($brutto19Sum - $netto19Sum) * -1, 2, ',', '.').' EUR').number_format(($brutto19Sum - $netto19Sum) * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }

        $this->ePrinter->feed(1);

        if(abs($order->creditcard_amount) != 0) {
          $this->ePrinter->text('Credit Card: '.$this->textPos('Credit Card: ', number_format($order->creditcard_amount * -1, 2, ',', '.').' EUR').number_format($order->creditcard_amount * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs($order->eccard_amount) != 0) {
          $this->ePrinter->text('EC Card: '.$this->textPos('EC Card: ', number_format($order->eccard_amount * -1, 2, ',', '.').' EUR').number_format($order->eccard_amount * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs($order->cash_amount) != 0) {
          $this->ePrinter->text('BAR: '.$this->textPos('BAR: ', number_format($order->cash_amount * -1, 2, ',', '.').' EUR').number_format($order->cash_amount * -1, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }

        if($i == 0) {
          $this->footer('del');
        }
        else {
          $this->footer();
        }
      }

      if(abs((float)$order->cash_amount) != 0) {
        $this->ePrinter->pulse();
      }
      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function printLiederschein($basket, float $discountRate = 0) {
    try {
      $discountRate = $discountRate > 0 ? $discountRate / 100 : 0;
      $priceSum = 0;
      $brutto7Sum = 0;
      $brutto19Sum = 0;
      $cafeArray = [];
      $kitchenArray = [];

      $this->header('Lieferschein');

      foreach ($basket as $obj) {
        $price = 0;
        $UVP = round((float)$obj['item']['sales_price'], 2);
        $price = $UVP * (int)$obj['qty'];
        $priceStr = number_format($price, 2, ',', '.');

        $UVPStr = number_format($UVP, 2, ',', '.');
        $UVPWithClass = $UVPStr.' '.((float)$obj['applied_tax_rate'] == 0.07 ? 'A' : 'B');
        $itemName = substr(str_replace(array("\n", "\r"), "", $obj['item']['name']), 0, 25);
        $sumStr = $obj['qty'].' * '.$UVPStr;

        $this->ePrinter->text($itemName.$this->textPos($itemName, $UVPWithClass).$UVPWithClass);
        $this->ePrinter->feed(1);

        $this->ePrinter->setEmphasis(true);
        if($discountRate > 0) {
          $UVP = round((float)$obj['item']['sales_price'] * (1 - $discountRate), 2);
          $price = $UVP * (int)$obj['qty'];
          $priceStr = number_format($price, 2, ',', '.');
          $sumStr .= ' * -'.round((float)$discountRate* 100, 0).'%';

          $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
          $this->ePrinter->feed(1);
        }
        else {
          $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->setEmphasis(false);

        if((float)$obj['applied_tax_rate'] == 0.07) {
          $brutto7Sum += $price;
        }
        else if((float)$obj['applied_tax_rate'] == 0.19) {
          $brutto19Sum += $price;
        }
        $priceSum += $price;

        if((int)$obj['item']['category_id'] < 6) {
          array_push($cafeArray, $obj);
        }
        else {
          array_push($kitchenArray, $obj);
        }
      }

      $this->ePrinter->text('------------------------------------------');
      $this->ePrinter->feed(1);
      $this->ePrinter->setEmphasis(true);
      $this->ePrinter->setTextSize(2, 1);
      $this->ePrinter->text('Summe'.$this->textPos('Summe', number_format((float)$priceSum, 2, ',', '.').' EUR', 2).number_format((float)$priceSum, 2, ',', '.').' EUR');
      $this->ePrinter->feed(1);
      $this->ePrinter->setTextSize(1, 1);
      $this->ePrinter->setEmphasis(false);
      $this->ePrinter->feed(1);
      $this->ePrinter->text('A = 7%, B = 19%');
      $this->ePrinter->feed(1);

      if($brutto7Sum > 0) {
        $netto7Sum = Price::getNetto($brutto7Sum, 0.07);
        $this->ePrinter->text('7% Netto.'.$this->textPos('7% Netto.', number_format($netto7Sum, 2, ',', '.').' EUR').number_format($netto7Sum, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('MwSt 7%'.$this->textPos('MwSt 7%', number_format(($brutto7Sum - $netto7Sum), 2, ',', '.').' EUR').number_format(($brutto7Sum - $netto7Sum), 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
      }
      if ($brutto19Sum > 0) {
        $netto19Sum = Price::getNetto($brutto19Sum, 0.19);
        $this->ePrinter->text('19% Netto.'.$this->textPos('19% Netto.', number_format($netto19Sum, 2, ',', '.').' EUR').number_format($netto19Sum, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('MwSt 19%'.$this->textPos('MwSt 19%', number_format(($brutto19Sum - $netto19Sum), 2, ',', '.').' EUR').number_format(($brutto19Sum - $netto19Sum), 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
      }


      $this->ePrinter->setJustification($this->aCenter);
      $this->ePrinter->text('------------------------------------------');
      $this->ePrinter->feed(1);
      $this->ePrinter->text('UST-ID Nr : DE296 413 204');
      $this->ePrinter->feed(1);
      $this->ePrinter->text('Dami GmbH');
      $this->ePrinter->feed(2);
      $this->ePrinter->cut();

      if(count($cafeArray) > 0) {
        $this->ePrinter->setJustification($this->aLeft);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Drinks');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 2);
        foreach($cafeArray as $obj) {
          $itemName = substr(str_replace(array("\n", "\r"), "", $obj['item']['name']), 0, 25);
          $this->ePrinter->text($itemName.$this->textPos($itemName, $obj['qty']).$obj['qty']);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(2);
        $this->ePrinter->cut();
      }

      if(count($kitchenArray) > 0) {
        $this->ePrinter->setJustification($this->aLeft);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Foods');
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 2);
        foreach($kitchenArray as $obj) {
          $itemName = substr(str_replace(array("\n", "\r"), "", $obj['item']['name']), 0, 25);
          $this->ePrinter->text($itemName.$this->textPos($itemName, $obj['qty']).$obj['qty']);
          $this->ePrinter->feed(1);
        }
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(2);
        $this->ePrinter->cut();
      }
      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function openCashier() {
    try {
      $this->ePrinter->pulse();
      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  private function header(String $type, String $for = '', String $copy = 'N') {
    $this->ePrinter->setJustification($this->aCenter);
    $this->ePrinter->setEmphasis(true);
    $this->ePrinter->setTextSize(2, 1);
    $this->ePrinter->text("LOUISE26");
    $this->ePrinter->setTextSize(1, 1);
    $this->ePrinter->setEmphasis(false);
    $this->ePrinter->feed(1);
    $this->ePrinter->text("Louisenstraße 26");
    $this->ePrinter->feed(1);
    $this->ePrinter->text("61348 Bad Homburg vor der Höhe");
    $this->ePrinter->feed(1);
    $this->ePrinter->text(date('d/m/Y H:i'));
    $this->ePrinter->feed(1);
    if($copy === 'Y') {
      $this->ePrinter->text('- Copied -');
      $this->ePrinter->feed(1);
    }
    if($for !== '') {
      $this->ePrinter->text($for);
      $this->ePrinter->feed(1);
    }
    $this->ePrinter->text($type);
    $this->ePrinter->feed(2);
    $this->ePrinter->text("------------------------------------------");
    $this->ePrinter->feed(1);
    $this->ePrinter->setJustification($this->aLeft);
  }

  private function footer(String $type = '') {
    $this->ePrinter->setJustification($this->aCenter);
    $this->ePrinter->text('------------------------------------------');
    $this->ePrinter->feed(1);
    $this->ePrinter->text('Vielen Dank für Ihren Einkauf.');
    $this->ePrinter->feed(1);
    $this->ePrinter->text('Thank you for your visit.');
    $this->ePrinter->feed(2);
    $this->ePrinter->text('UST-ID Nr : DE296 413 204');
    $this->ePrinter->feed(1);
    $this->ePrinter->text('Dami GmbH');
    $this->ePrinter->feed(2);

    if($type == 'del') {
      $this->ePrinter->feed(4);
      $this->ePrinter->setJustification($this->aCenter);
      $this->ePrinter->text('_________________________');
      $this->ePrinter->feed(1);
      $this->ePrinter->text('Signiture');
      $this->ePrinter->feed(1);
    }
    $this->ePrinter->feed(2);
    $this->ePrinter->cut();
  }

  private function textPos($leftStr, $rightStr, $fontSize = 1) {
    $maxChar = (int)(42 / $fontSize);
    $emptySpace = $maxChar - strlen($leftStr) - strlen($rightStr);
    return str_repeat(' ', $emptySpace);
  }

  public function voucherOK($order, $copy = 'N') {
    try {
      for($i=0;$i<2;$i++) {
        if($i == 0) {
          $this->header('Accepted', '- For Shop -', $copy);
        }
        else {
          $this->header('Accepted', '- For Customer -', $copy);
        }

        $this->ePrinter->text('Kasse ID . '.$order->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('Transaktions-Nr . '.$order->receipt_num);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);

        foreach($order->voucher as $voucher) {
          $UVP = (float)$voucher['amount'];
          $UVPStr = number_format($UVP, 2, ',', '.');
          $priceStr = number_format($UVP, 2, ',', '.');

          $itemName = 'Gutschein';
          $sumStr = '1 * '.$UVPStr;
          $this->ePrinter->text($itemName.$this->textPos($itemName, $UVPStr).$UVPStr);
          $this->ePrinter->feed(1);

          $this->ePrinter->setEmphasis(true);
          if((float)$voucher['discount_rate'] > 0) {
            $UVP = round((float)$voucher['amount'] * (1 - (float)$voucher['discount_rate']), 2);
            $sumStr .= ' * -'.round((float)$voucher['discount_rate'] * 100, 0).'%';
            $priceStr = number_format($UVP, 2, ',', '.');

            $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
            $this->ePrinter->feed(1);
          }
          else {
            $this->ePrinter->text($sumStr.$this->textPos($sumStr, $priceStr).$priceStr);
            $this->ePrinter->feed(1);
          }
          $this->ePrinter->setEmphasis(false);
        }

        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setEmphasis(true);
        $this->ePrinter->setTextSize(2, 1);
        $this->ePrinter->text('Summe'.$this->textPos('Summe', number_format((float)$order->sales_price, 2, ',', '.').' EUR', 2).number_format((float)$order->sales_price, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->setEmphasis(false);
        $this->ePrinter->feed(1);

        if(abs((float)$order->creditcard_amount) != 0) {
          $this->ePrinter->text('Credit Card: '.$this->textPos('Credit Card: ', number_format((float)$order->creditcard_amount, 2, ',', '.').' EUR').number_format((float)$order->creditcard_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs((float)$order->eccard_amount) != 0) {
          $this->ePrinter->text('EC Card: '.$this->textPos('EC Card: ', number_format((float)$order->eccard_amount, 2, ',', '.').' EUR').number_format((float)$order->eccard_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        if(abs((float)$order->cash_amount) != 0) {
          $this->ePrinter->text('BAR: '.$this->textPos('BAR: ', number_format((float)$order->cash_amount, 2, ',', '.').' EUR').number_format((float)$order->cash_amount, 2, ',', '.').' EUR');
          $this->ePrinter->feed(1);
        }
        $this->footer();
      }

      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }

  public function voucherRefund($voucher, $copy = 'N') {
    try {
      for($i=0;$i<2;$i++) {
        if($i == 0) {
          $this->header('Accepted', '- For Shop -', $copy);
        }
        else {
          $this->header('Accepted', '- For Customer -', $copy);
        }

        $this->ePrinter->text('Kasse ID . '.$voucher->kasse_id);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);

        $UVPStr = '- '.number_format($voucher->amount, 2, ',', '.');
        $itemName = 'Gutschein ('.$voucher->code.')';
        $this->ePrinter->text($itemName.$this->textPos($itemName, $UVPStr).$UVPStr);
        $this->ePrinter->feed(1);
        $this->ePrinter->text('------------------------------------------');
        $this->ePrinter->feed(1);
        $this->ePrinter->setEmphasis(true);
        $this->ePrinter->setTextSize(2, 1);
        $this->ePrinter->text('Summe'.$this->textPos('Summe', number_format((float)$voucher->amount, 2, ',', '.').' EUR', 2).number_format((float)$voucher->amount, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->ePrinter->setTextSize(1, 1);
        $this->ePrinter->setEmphasis(false);
        $this->ePrinter->feed(1);

        $this->ePrinter->text('BAR: '.$this->textPos('BAR: ', number_format((float)$voucher->amount, 2, ',', '.').' EUR').number_format((float)$voucher->amount, 2, ',', '.').' EUR');
        $this->ePrinter->feed(1);
        $this->footer();
      }
      $this->ePrinter->pulse();
      /* Close printer */
      $this->ePrinter->close();
    } catch(\Exception $e) {
      return ['ok' => false, 'msg' => $e->getMessage()];
    }
    return ['ok' => true];
  }
}
