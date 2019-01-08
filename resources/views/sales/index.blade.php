@extends('layouts.app')
@section('content')
<div class="container-fluid" style="z-index:0;">
  <input type="text" id="barcode" style="width:0px;height:0px;border:none;" autofocus>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-8">
          <ul class="nav-category" id="sales-category">
            @foreach($menus as $i => $menu)
              <li class="{{ $i == 0 ? 'active' : '' }}"><a data-toggle="tab" href="#{{$menu->code}}" style="font-size:18px;">{{$menu->name}}</a></li>
            @endforeach
          </ul>
          <div class="tab-content">
            @foreach($menus as $i => $menu)
              <div id="{{ $menu->code }}" class="tab-pane custom-scroll {{ $i == 0 ? 'active' : '' }}" style="height:630px;overflow:auto">
                @foreach($menu->cafeMenu as $item)
                  <button type="button" class="btn btn-default btn-menu" style="font-size:20px; text-align:center; white-space: normal;" onclick="menuClicked({{ $item }}, this)">
                    <strong>{{ $item->name }}</strong><br/>{{ $item->sales_price }}€
                  </button>
                @endforeach
                @if(Auth::user()->is_admin == 'Y')
                  <button type="button" class="btn btn-default btn-create-menu" onclick="openItemPopup({{ $menu->id }})">Create<br/>Item</button>
                @endif
              </div>
            @endforeach
            <div id="sales-item-buttons" class="mt25">
              @if(Auth::user()->is_admin == 'Y')
                <span style="margin-right: 20px;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Admin Menu</span>
                <button type="button" class="btn btn-md btn-default" onclick="openCategoryPopup();">Category Setting</button>
                <button type="button" class="btn btn-md btn-default" onclick="changeEditStatus();">Edit Item</button>
                <button type="button" class="btn btn-md btn-default" onclick="changeDeleteStatus();">Delete Item</button>
                <button type="button" class="btn btn-md btn-default" onclick="openVoucherPopup();">Create Voucher</button>
                <button type="button" class="btn btn-md btn-default" onclick="openVoucherSearchPopup();">Search-Voucher</button>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-4" style="border-left:1px solid #e0e4e7;">
          <div class="row-fluid">
            <h3 style="display:inline-block;">Item list</h3>
            <button type="button" class="btn btn-lg btn-danger" style="float:right" onclick="clearAll()"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Clear All</button>
          </div>
          <div class="row-fluid">
            <table class="table dptbl">
              <thead>
                <tr>
                  <th width="220">Item</th>
                  <th width="55" style="text-align:center;"></th>
                  <th width="50" style="text-align:center;">Qty</th>
                  <th width="70" style="text-align:center;">Price</th>
                  <th width="70" style="text-align:center;">Total</th>
                  <th width="90"></th>
                </tr>
              </thead>
              <tbody class="fs16 custom-scroll" id="main-data-area">
                @for($i=0;$i<8;$i++)
                  <tr><td class="dptd" colspan="6" width="585px"></td></tr>
                @endfor
              </tbody>
            </table>
          </div>
          <div class="row-fluid">
            <table class="table dpsumtbl">
              <tr style="display:none">
                <td>Subtotal</td>
                <td class="sumtd main-sum"><span id="main-sum-subtotal">0,00</span> €</td>
              </tr>
              <tr style="display:none">
                <td>Tax</td>
                <td class="sumtd main-sum"><span id="main-sum-tax">0,00</span> €</td>
              </tr>
              <tr class="ftbold">
                <td>Total</td>
                <td class="sumtd main-sum"><span id="main-sum-total">0,00</span> €</td>
              </tr>
            </table>
          </div>
          <div class="text-center mt25">
            <button type="button" class="btn btn-success" style="width:49%; height:100px; font-size:30px; margin-right: 8px;" onclick="openPaymentPopup();">Payment</button>
            <button type="button" class="btn btn-warning" style="width:49%; height:100px; font-size:20px" onclick="openCancelOrderPopup();">Cancel Previous<br/>Order</button>
          </div>
          <div class="text-center mt10">
            <button type="button" class="btn btn-default" style="width:49%; height:100px; font-size:25px; margin-right: 8px;" onclick="openReprintPopup();">Print Old Receipt</button>
            <button type="button" class="btn btn-default" style="width:49%; height:100px; font-size:25px;" onclick="openCashBoxPopup();">Open Cash Drawer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="parentDisable" style="display:none;" id="disable-bg"></div>
<div class="popupDisable" style="display:none;" id="disable-popup"></div>
<div class="popupAllDisable" style="display:none;" id="disable-all-popup"></div>

@include('sales.popup.calculator')
@include('sales.popup.calculator-payment')
@include('sales.popup.calculator-sm')
@include('sales.popup.cancelOrder')
@include('sales.popup.cancelOrder-all')
@include('sales.popup.cancelOrder-partial')
@include('sales.popup.cashBox')
@include('sales.popup.category')
@include('sales.popup.item')
@include('sales.popup.payment')
@include('sales.popup.reprint')
@include('sales.popup.sales-summary')
@include('sales.popup.Voucher')
@include('sales.popup.Voucher-Search')
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/sales.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/calculator.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/calculatorPayment.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cancelOrder.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/cashBox.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/category.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/item.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/payment.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/reprint.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/salesSummary.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/voucher.js') }}"></script>
@endsection
