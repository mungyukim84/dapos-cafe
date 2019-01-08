<div class="dapos-popup" id="salesSummaryPopup" style="width:1000px;height:730px;left:45%;top:40%">
  <div class="popup-head"></div>
  <div class="col-md-12">
    <div class="popup-title">
      <h3>Sales Summary</h3>
    </div>
    <div class="block form-inline">
      <label class="fs18 popup-label" style="width: 120px">Date</label>
      <select class="popup-selectbox input-lg" id="sales-summary-kasse" style="width:150px;">
        <option value="all">Cafe All</option>
        @foreach($kasses as $kasse)
          <option value="{{ $kasse->id }}">{{ $kasse->name }}</option>
        @endforeach
      </select>
      <div class="form-group" style="margin-left:10px">
        <input type="text" class="form-control input-lg datepicker" style="width:200px;text-align:center" id="sales-summary-from-date" data-date-format="yyyy-mm-dd" readonly> -
        <input type="text" class="form-control input-lg datepicker" style="width:200px;text-align:center" id="sales-summary-to-date" data-date-format="yyyy-mm-dd" readonly>
        <button type="button" class="btn btn-lg btn-primary popup-search-button" onclick="getSalesSummary()">Search</button>
      </div>
    </div>
    <div class="block form-inline">
      <label class="fs18 popup-label" style="width:120px">Total</label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-total" readonly>
      </div>
      <label class="fs18 popup-label" style="width:90px;margin-left:10px">19% </label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-tax19" readonly>
      </div>
      <label class="fs18 popup-label" style="width:60px;margin-left:10px">7% </label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-tax7" readonly>
      </div>
    </div>
    <div class="block form-inline">
      <label class="fs18 popup-label" style="width:120px">Credit Card</label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-creditcard" readonly>
      </div>
      <label class="fs18 popup-label" style="width:90px;margin-left:10px">EC Card</label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-eccard" readonly>
      </div>
      <label class="fs18 popup-label" style="width:60px;margin-left:10px">Cash</label>
      <div class="form-group">
        <input type="text" class="form-control input-lg datepicker" style="width:175px;text-align:center" id="sales-summary-cash" readonly>
      </div>
    </div>
    <div class="block">
      <table class="table dptbl-sm mt30">
        <thead>
          <tr>
            <th width="240px" style="text-align:center">Category</th>
            <th width="320px">Item Name</th>
            <th width="80px" style="text-align:center">S/Price</th>
            <th width="70px" style="text-align:center">D/C</th>
            <th width="70px" style="text-align:center">Qty</th>
            <th width="110px" style="text-align:center">Amount</th>
          </tr>
        </thead>
        <tbody class="fs18" id="sales-summary-area" style="height:256px">
          @for($i=0;$i<6;$i++)
            <tr><td class="dptd-sm" colspan="6" width="911px"></td></tr>
          @endfor
        </tbody>
      </table>
    </div>
    <div class="text-center">
      <button class="btn btn-lg btn-danger" onclick="closeSalesSummaryPopup()">Close</button>
    </div>
  </div>
</div>
