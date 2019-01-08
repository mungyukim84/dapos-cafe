<div class="dapos-popup" id="cancelOrderPopup">
  <div class="col-md-12">
    <div class="popup-title">
      <h3>Cancel Previous Order</h3>
    </div>
    <div class="block">
      <label for="cancelOrder-transactionId" class="fs18 popup-label">Receipt Number</label>
      <select class="popup-selectbox input-lg" id="cancelOrder-kasse" style="width:150px;">
        @foreach($kasses as $kasse)
          @if(Auth::user()->kasse_id == $kasse->id)
            <option value="{{ $kasse->id }}">{{ $kasse->name }} ({{ $kasse->id }})</option>
          @endif
        @endforeach
      </select>
      <input type="text" class="popup-input input-lg" id="cancelOrder-receiptNum" style="width: 270px;" onkeypress="if(event.keyCode == '13' || event.which == '13') { searchOrder() }">
      <button type="button" class="btn btn-lg btn-default popup-search-button" onclick="searchOrder()">Search</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label">Payment Method</label>
      <span class="fs18" id="cancelOrder-paymentMethod">-</span>
      <span class="card-cancel-yn">
        <label for="payment-option-inout" class="fs18">Card Cancellable</label>
        <div class="btn-group ml10">
          <button class="btn btn-lg btn-default" id="cardCancellable-yes"  onclick="isCardCancellable(this, 'Y')">Yes</button>
          <button class="btn btn-lg btn-default" id="cardCancellable-no" onclick="isCardCancellable(this, 'N')">No</button>
        </div>
      </span>
    </div>
    <div class="block mt15">
      <label class="fs18 popup-label">Included Items</label>
      <table class="table table-bordered table-striped mt10" id="refund-item-selection-table">
        <thead>
          <tr>
            <th width="50px" style="text-align:center"><input class="cancelOrder-item-checkbox" type="checkbox" onclick="checkAll(this)" data-cl="cancelOrder-item-checkbox"/></th>
            <th width="463px">Item Name</th>
            <th width="105px" style="text-align:center">Qty</th>
            <th width="105px" style="text-align:center">Price</th>
            <th width="105px" style="text-align:center">Total</th>
          </tr>
        </thead>
        <tbody id="refund-main-area">
        </tbody>
      </table>
    </div>
    <div class="block-bottom" style="top:535px">
      <button class="btn btn-lg btn-danger" onclick="closeCancelOrderPopup()">Cancel</button>
      <button class="btn btn-lg btn-default" id="refund-partial-btn" onclick="openCancelPartialPopup()" style="float: right;">Selected Item Edit</button>
      <button class="btn btn-lg btn-default" onclick="openCancelAllPopup()" style="float: right; margin-right: 8px;">All Items Cancel</button>
    </div>
  </div>
</div>
