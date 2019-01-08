<div class="dapos-popup-cancel" id="cancelOrder-all">
  <div class="popup-head"></div>
  <div class="col-md-12">
    <div class="popup-title">
      <h3>Refund Amount <span style="float:right;" id="refund-all-price"></span></h3>
    </div>
    <div class="block" id="cancelOrder-all-cash-block">
      <label for="refund-all-cash" class="fs18 popup-label">CASH</label>
      <input type="text" class="popup-sm-input input-lg refund-all" id="refund-all-cash" style="margin-left:130px;text-align:right" readonly> <span style="font-size:24px"> €</span>
      <!--button class="btn btn-lg btn-success popup-sm-button" id="refund-all-cash-btn" onclick="cashRefund()">Accept</button-->
    </div>
    <div class="block" id="cancelOrder-all-creditcard-block">
      <label for="refund-all-creditcard" class="fs18 popup-label">CREDIT CARD</label>
      <input type="text" class="popup-sm-input input-lg refund-all" id="refund-all-creditcard" style="margin-left:130px;text-align:right" readonly> <span style="font-size:24px"> €</span>
      <!--button class="btn btn-lg btn-success popup-sm-button" id="refund-all-creditcard-btn" onclick="creditCardRefund()">Accept</button-->
    </div>
    <div class="block" id="cancelOrder-all-eccard-block">
      <label for="refund-all-eccard" class="fs18 popup-label">EC CARD</label>
      <input type="text" class="popup-sm-input input-lg refund-all" id="refund-all-eccard" style="margin-left:130px;text-align:right" readonly> <span style="font-size:24px"> €</span>
      <!--button class="btn btn-lg btn-success popup-sm-button" id="refund-all-eccard-btn" onclick="ecCardRefund()">Accept</button-->
    </div>
    <div class="block-bottom" style="top:340px">
      <button class="btn btn-lg btn-danger" onclick="closeCancelAllPopup()">Cancel</button>
      <button class="btn btn-lg btn-success" id="cancelOrder-all-checkout" onclick="event.preventDefault();refundAllSuccess('all')" style="float:right;">Check Out</button>
    </div>
  </div>
</div>
