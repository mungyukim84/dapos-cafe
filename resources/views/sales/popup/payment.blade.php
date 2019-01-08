<div class="dapos-popup" id="paymentPopup">
  <div class="col-md-12">
    <div class="popup-title" id="tpad-text">
      <h3><span class="block-title-price">Total Price</span><span id="payment-text-listprice" class="sum-number"></span></h3>
    </div>
    <div class="popup-title" id="discounted-price-text" style="display:none">
      <h3>Discounted Price <span style="float:right;" id="payment-text-discount"></span></h3>
    </div>
    <div class="popup-title" id="partial-price-text" style="display:none">
      <h3>Partial Price Minus Sum <span style="float:right;" id="payment-text-partial"></span></h3>
    </div>
    <div class="mt15" id="payment-option-tab">
      <div class="block">
        <label for="payment-option-inout" class="fs18 popup-label">In / Out</label>
        <div class="btn-group takeout-type">
          <button class="btn btn-lg btn-default" onclick="isTakeInOrOut(this, 'in')">In</button>
          <button class="btn btn-lg btn-default" onclick="isTakeInOrOut(this, 'out')">Out</button>
        </div>
      </div>
      <div class="block">
        <label for="payment-option-bell-number" class="fs18 popup-label">Bell </label>
        <input type="text" class="popup-input-text input-lg" id="payment-option-bell-number" style="width:80px;text-align:center;" onclick="openCalculatorPayment(this)">
      </div>
      <div class="block">
        <label for="payment-option-coupon" class="fs18 popup-label">Coupon</label>
        <input type="text" class="popup-input-text input-lg" id="payment-option-coupon" style="width: 320px;">
        <button class="btn btn-lg btn-default" onclick="validateCoupon(document.getElementById('payment-option-coupon').value)" style="width:100px; vertical-align: top;">Check</button>
        <span style="margin-left:5px;color:orange;font-size:16px;display:none" id="payment-option-coupon-validation"></span>
      </div>
      <div class="block">
        <label for="payment-option-discount" class="fs18 popup-label">Discount (%)</label>
        <input type="text" class="popup-input-number input-lg" id="payment-option-discount" style="width: 320px;" onclick="openCalculatorPayment(this)" readonly>
      </div>
      <div class="block-bottom">
        <button class="btn btn-xl btn-danger" onclick="closePaymentPopup()">Cancel</button>
        <button class="btn btn-xl btn-primary" onclick="openPaymentSelection()" style="float:right;">Next</button>
        <button class="btn btn-xl btn-warning" onclick="printLieferschein(document.getElementById('payment-option-discount').value)" style="float:right;margin-right:8px;">Lieferschein</button>
      </div>
    </div>
    <div class="mt30" id="payment-selection-tab" style="display:none">
      <div class="block">
        <label for="payment-selection-cash" class="fs18 popup-label">Cash</label>
        <button type="button" class="btn btn-lg btn-success popup-search-button" onclick="paymentAll('cash')">All</button>
        <button type="button" class="btn btn-lg btn-warning popup-search-button" onclick="restAll('payment-selection-cash')">Rest All</button>
        <input type="text" class="popup-input-number input-lg" id="payment-selection-cash" style="width: 320px;" onclick="openCalculatorPayment(this)" readonly>
      </div>
      <div class="block">
        <label for="payment-selection-creditcard" class="fs18 popup-label">Credit card</label>
        <button type="button" class="btn btn-lg btn-success popup-search-button" onclick="paymentAll('creditcard')">All</button>
        <button type="button" class="btn btn-lg btn-warning popup-search-button" onclick="restAll('payment-selection-creditcard')">Rest All</button>
        <input type="text" class="popup-input-number input-lg partial-amount" id="payment-selection-creditcard" style="width: 320px;" onclick="openCalculatorPayment(this)" readonly>
      </div>
      <div class="block">
        <label for="payment-selection-eccard" class="fs18 popup-label">EC card</label>
        <button type="button" class="btn btn-lg btn-success popup-search-button" onclick="paymentAll('eccard')">All</button>
        <button type="button" class="btn btn-lg btn-warning popup-search-button" onclick="restAll('payment-selection-eccard')">Rest All</button>
        <input type="text" class="popup-input-number input-lg" id="payment-selection-eccard" style="width: 320px;" onclick="openCalculatorPayment(this)" readonly>
      </div>
      <div class="block">
        <label for="payment-selection-voucher" class="fs18 popup-label">Voucher</label>
        <button type="button" class="btn btn-lg btn-success popup-search-button" onclick="paymentAll('voucher')">All</button>
        <button type="button" class="btn btn-lg btn-warning popup-search-button" onclick="restAll('payment-selection-voucher')">Rest All</button>
        <input type="text" class="popup-input-number input-lg" id="payment-selection-voucher" style="width: 320px;" onclick="if(paymentObj.voucher.max > 0) openCalculatorPayment(this); else alert('Invalid Code or No amount.');" readonly>
      </div>
      <div class="block">
        <label for="payment-selection-voucher" class="fs18 popup-label">Voucher Check</label>
        <input type="text" class="popup-input-number input-lg" id="payment-selection-voucher-code" style="width:320px;;text-align:left" onkeypress="if(event.keyCode == '13' || event.which == '13') checkVoucherByCode(this.value);">
        <button type="button" class="btn btn-lg btn-warning popup-search-button" style="width:100px;" onclick="checkVoucherByCode(document.getElementById('payment-selection-voucher-code').value)">Check</button>
        <span id="payment-selection-voucher-checksum" style="font-size:20px;font-weight:bold;margin-left:5px"></span>
      </div>
      <div class="block-bottom">
        <button class="btn btn-xl btn-danger" onclick="closePaymentPopup()">Cancel</button>
        <button class="btn btn-xl btn-warning" onclick="backToPaymentOption()">Back</button>
        <button class="btn btn-xl btn-primary" onclick="openPaymentPartial()" style="float:right;">Partial payment</button>
      </div>
    </div>
    <div class="mt30" id="payment-transaction-tab" style="display:none">
      <div class="block payment-transaction-block" id="creditcard-block" style="display:none">
        <label for="payment-transaction-creditcard" class="fs18 popup-label">Credit card</label>
        <select class="popup-selectbox input-lg" id="payment-transaction-creditcard-type" onchange="creditCardTypeChanged(this)" style="width:185px;font-size:16px">
          <option value="0">CreditCard Type...</option>
          @foreach($creditCards as $cC)
            <option value="{{ $cC->id }}">{{ $cC->name }}</option>
          @endforeach
        </select>
        <input type="text" class="popup-input-number input-lg" id="payment-transaction-creditcard" style="width: 200px;" disabled> <span style="font-size:24px"> €</span>
        <button class="btn btn-lg btn-success popup-search-button btn-exec-payment" id="partial-payment-creditcard" style="display:none" onclick="event.preventDefault();payByCreditCard(this.value)">Accept</button>
      </div>
      <div class="block payment-transaction-block" id="eccard-block" style="display:none">
        <label for="payment-transaction-eccard" class="fs18 popup-label">EC card</label>
        <input type="text" class="popup-input-number input-lg" id="payment-transaction-eccard" style="width: 390px;" disabled> <span style="font-size:24px"> €</span>
        <button class="btn btn-lg btn-success popup-search-button btn-exec-payment" style="display:none" id="partial-payment-eccard" onclick="event.preventDefault();payByEcCard(this.value)">Accept</button>
      </div>
      <div class="block payment-transaction-block" id="voucher-block" style="display:none">
        <label for="payment-transaction-voucher" class="fs18 popup-label">Voucher</label>
        <input type="text" class="popup-input-number input-lg" id="payment-transaction-voucher" style="width: 390px;" disabled> <span style="font-size:24px"> €</span>
        <button class="btn btn-lg btn-success popup-search-button btn-exec-payment" style="display:none" id="partial-payment-voucher" onclick="event.preventDefault();payWithVoucher(this.value)">Accept</button>
      </div>
      <div class="block payment-transaction-block" id="cash-block" style="display:none">
        <div class="block-cash">
          <label for="payment-transaction-cash" class="fs18 popup-label">Cash</label>
          <input type="text" class="popup-input-number input-lg" id="payment-transaction-cash" style="width: 390px;" disabled> <span style="font-size:24px"> €</span>
        </div>
        <div class="block-cash">
          <label for="payment-transaction-cash" class="fs18 popup-label">Received Amount</label>
          <input type="text" class="popup-input-number input-lg" id="payment-transaction-amount" style="width: 390px;" value="0" onclick="openCalculatorPayment(this)" readonly> <span style="font-size:24px"> €</span><button type="button" class="btn btn-lg btn-warning popup-search-button" onclick="document.getElementById('payment-transaction-amount').value=0">Clear</button>
        </div>
        <div class="block-cash">
          <div class="block-cash-btn">
            <button type="button" class="btn btn-lg btn-success btn-cash-amount-all" onclick="document.getElementById('payment-transaction-amount').value=document.getElementById('payment-transaction-cash').value">All</button>
          </div>
          <div class="block-cash-btn">
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(0.01)">0,01€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(0.02)">0,02€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(0.10)">0,10€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(0.20)">0,20€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(0.50)">0,50€</button>
          </div>
          <div class="block-cash-btn">
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(1)">1€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(2)">2€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(5)">5€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(10)">10€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(20)">20€</button>
          </div>
          <div class="block-cash-btn">
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(50)">50€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(100)">100€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(200)">200€</button>
            <button type="button" class="btn btn-lg btn-default btn-cash-amount" onclick="addCash(500)">500€</button>
          </div>
        </div>
      </div>
      <div class="block-bottom">
        <button class="btn btn-xl btn-danger" onclick="closePaymentPopup()">Cancel</button>
        <button class="btn btn-xl btn-warning" onclick="backToSelectionTab()">Back</button>
        <button class="btn btn-xl btn-success" id="execute-payment" style="float:right;">SUCCESS</button>
      </div>
    </div>
    <div class="mt30" id="payment-cashsum-tab" style="display:none">
      <div class="popup-title">
        <h3>Cash <span style="float:right;" id="payment-cashsum-cash"></span></h3>
      </div>
      <div class="popup-title">
        <h3>Received Amount <span style="float:right;" id="payment-cashsum-received"></span></h3>
      </div>
      <div class="popup-title">
        <h3>Changes <span style="float:right;" id="payment-cashsum-change"></span></h3>
      </div>
      <div class="block-bottom">
        <button class="btn btn-xl btn-danger" onclick="closePaymentPopup()">Cancel</button>
        <button class="btn btn-xl btn-warning" onclick="backToTransactionTab()">Back</button>
        <button class="btn btn-xl btn-success" style="float:right;" onclick="payWithCashSucess()">Accept</button>
      </div>
    </div>
  </div>
</div>
