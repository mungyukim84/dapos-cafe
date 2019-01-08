<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SalesService;
use App\Services\ItemService;

class SalesController extends Controller
{
  private $itemService;
  private $salesService;

  public function __construct(SalesService $salesService, ItemService $itemService)
  {
      $this->middleware('auth');
      $this->itemService = $itemService;
      $this->salesService = $salesService;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $menus = $this->itemService->getMenu();
    $kasses = $this->salesService->getAllKasse();
    $reasons = $this->salesService->getKasseOpenReason();
    $creditCards = $this->salesService->getCreditCardAll();
    return view('sales.index', [
      'menus' => $menus,
      'kasses' => $kasses,
      'reasons' => $reasons,
      'creditCards' => $creditCards
    ]);
  }

  public function getOrderByReceiptNum(Request $request) {
    return response($this->salesService->getOrderByReceiptNum($request->all()));
  }

  public function getOrdersWithDate(Request $request) {
    $orders = $this->salesService->getOrdersWithDate($request->all());
    return response(['orders' => $orders]);
  }

  public function getSalesSummary(Request $request) {
    $data = $this->salesService->getSalesSummary($request->all());
    return response(['data' => $data]);
  }

  public function insertOrder(Request $request) {
    return response($this->salesService->insertOrder($request->all()));
  }

  public function cancelOrder(Request $request) {
    return response($this->salesService->cancelOrder($request->all()));
  }

  public function openCashier(Request $request) {
    return response($this->salesService->openCashier($request->all()));
  }

  public function getCashSumByKasse() {
    return response($this->salesService->getCashSumByKasse());
  }

  public function dailyClosing() {
    return response($this->salesService->dailyClosing());
  }

  public function reprintReceipt(Request $request) {
    return response($this->salesService->reprintReceipt((int)$request->orderId));
  }

  public function printLiederschein(Request $request) {
    return response($this->salesService->printLiederschein($request->basket, (float)$request->discountRate));
  }

  public function createVoucher(Request $request) {
    return response($this->salesService->createVoucher($request->all()));
  }

  public function checkVoucherByCode($voucherCode) {
    return response($this->salesService->checkVoucherByCode($voucherCode));
  }

  public function refundVoucher(Request $request) {
    return response($this->salesService->refundVoucher($request->all()));
  }
}
