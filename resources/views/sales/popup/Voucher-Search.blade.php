<div class="dapos-popup-voucher" style="left:15%;top:50%;width:800px;height:210px" id="voucherSearchPopup">
  <div class="popup-head">
    <span class="popup-head-title" id="popup-item-title">Voucher Search</span>
  </div>
  <div class="col-md-12">
    <div class="block voucher-list">
      <label for="voucher-amount" class="fs18 popup-label">Code: </label>
      <input type="text" class="popup-input-text input-lg" style="width:200px;text-align:left" id="voucher-search-code" onkeypress="if(event.keyCode == '13' || event.which == '13') checkVoucherByCode(this.value);">
      <button type="button" class="btn btn-lg btn-success" onclick="checkVoucherByCode(document.getElementById('voucher-search-code').value);">Search</button>
      <label for="voucher-amount" class="fs18 popup-label" style="margin-left:10px">Amount (€): </label>
      <input type="text" class="popup-input-text input-lg" style="width:200px;text-align:right" id="voucher-search-amount" readonly>
    </div>
    <div class="block-bottom">
      <div class="text-center">
        <button class="btn btn-lg btn-warning" style="width:180px" onclick="closeVoucherSearchPopup()">CANCEL</button>
        <button class="btn btn-lg btn-danger" style="width:180px;margin-left:10px" onclick="refundVoucher(document.getElementById('voucher-search-code').value, document.getElementById('voucher-search-amount').value)">REFUND</button>
      </div>
    </div>
  </div>
</div>
