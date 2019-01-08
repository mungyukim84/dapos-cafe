<div class="dapos-popup-cancel-partial" id="cancelOrder-partial">
  <div class="popup-head"></div>
  <div class="col-md-12">
    <div class="mt30" id="refund-partial-select-tab">
      <div class="popup-title">
        <h3>Refund Amount <span style="float:right;" class="refund-partial-price"></span></h3>
      </div>
      <div class="block">
        <table class="table dptbl-sm mt10">
          <thead>
            <tr>
              <th width="320px">Item Name</th>
              <th width="89px" style="text-align:center">Unit Price</th>
              <th width="130px" style="text-align:center">Qty</th>
              <th width="90px" style="text-align:center">Return Price</th>
            </tr>
          </thead>
          <tbody class="fs18" id="refund-partial-area" style="height:300px">
          </tbody>
        </table>
      </div>
      <div class="block-bottom">
        <button class="btn btn-lg btn-danger" onclick="closeCancelPartialPopup()">Cancel</button>
        <button class="btn btn-lg btn-success" id="cancelOrder-partial-select-checkout" onclick="openRefundTab()" style="float:right;">Check Out</button>
      </div>
    </div>
    <div class="mt30" id="refund-partial-refund-tab" style="display:none">
      <div class="popup-title">
        <h3>Refund Amount <span style="float:right;" class="refund-partial-price"></span></h3>
      </div>
      <div class="block" id="cancelOrder-partial-eur-block">
        <label for="payment-transaction-cash" class="fs18 popup-label" id="refund-partial-cash-label" style="width:435px">CASH (€)</label>
        <input type="text" class="popup-sm-input input-lg refund-partial" id="refund-partial-cash" style="text-align:right" readonly> <span style="font-size:24px"> €</span>
      </div>
      <div class="block-bottom">
        <button class="btn btn-lg btn-danger" onclick="closeCancelPartialPopup()">Cancel</button>
        <button class="btn btn-lg btn-warning" onclick="backToRefundSelectionTab()">Back</button>
        <button class="btn btn-lg btn-success" id="cancelOrder-partial-checkout" onclick="event.preventDefault();refundAllSuccess('partial')" style="float:right;">Check Out</button>
      </div>
    </div>
  </div>
</div>
