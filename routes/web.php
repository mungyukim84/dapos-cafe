<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect('/sales');
});

Auth::routes();

Route::group(['prefix' => 'sales'], function () {
  Route::get('/', 'SalesController@index')->name('sales.index');
  Route::get('getOrderByReceiptNum', 'SalesController@getOrderByReceiptNum');
  Route::get('getOrdersWithDate', 'SalesController@getOrdersWithDate');
  Route::get('getSalesSummary', 'SalesController@getSalesSummary');
  Route::post('openCashier', 'SalesController@openCashier');
  Route::get('getCashSumByKasse', 'SalesController@getCashSumByKasse');
  Route::post('insertOrder', 'SalesController@insertOrder');
  Route::put('cancelOrder', 'SalesController@cancelOrder');
  Route::get('dailyClosing', 'SalesController@dailyClosing');
  Route::post('reprintReceipt', 'SalesController@reprintReceipt');
  Route::post('printLiederschein', 'SalesController@printLiederschein');
  Route::post('createVoucher', 'SalesController@createVoucher');
  Route::get('checkVoucherByCode/{voucherCode}', 'SalesController@checkVoucherByCode');
  Route::post('refundVoucher', 'SalesController@refundVoucher');
});

Route::group(['prefix' => 'order'], function () {
});

Route::group(['prefix' => 'item'], function () {
  Route::post('insertItem', 'ItemController@insertItem');
  Route::put('updateItem/{itemId}', 'ItemController@updateItem');
  Route::delete('deleteItems', 'ItemController@deleteItems');
  Route::get('getItemWithBarcode/{barcode}', 'ItemController@getItemWithBarcode');
  Route::get('getCategories', 'ItemController@getCategories');
  Route::put('updateCategories', 'ItemController@updateCategories');
  Route::delete('deleteCategory', 'ItemController@deleteCategory');
});
