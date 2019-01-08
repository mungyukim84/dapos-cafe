<div class="dapos-popup" id="reprintPopup" style="width:1000px;height:730px;left:45%;top:40%">
  <div class="popup-head"></div>
  <div class="col-md-12">
    <div class="popup-title">
      <h3>Reprint</h3>
    </div>
    <div class="block form-inline">
      <label class="fs18 popup-label" style="width: 120px">Date</label>
      <select class="popup-selectbox input-lg" id="reprint-kasse" style="width:150px;">
        <option value="all">Cafe All</option>
        @foreach($kasses as $kasse)
          <option value="{{ $kasse->id }}">{{ $kasse->name }}</option>
        @endforeach
      </select>
      <div class="form-group" style="margin-left:10px">
          <input type="text" class="form-control input-lg datepicker" style="width:200px;text-align:center" id="reprint-from-date" data-date-format="yyyy-mm-dd" readonly> -
          <input type="text" class="form-control input-lg datepicker" style="width:200px;text-align:center" id="reprint-to-date" data-date-format="yyyy-mm-dd" readonly>
          <button type="button" class="btn btn-lg btn-primary popup-search-button" onclick="getOrdersWithDate()">Search</button>
      </div>
    </div>
    <div class="block">
      <table class="table dptbl-sm mt30">
        <thead>
          <tr>
            <th width="100px" style="text-align:center">Kasse</th>
            <th width="206px">Bill Number</th>
            <th width="340px">Item List</th>
            <th width="60px" style="text-align:center">Status</th>
            <th width="120px" style="text-align:center">Amount</th>
            <th width="140px"></th>
          </tr>
        </thead>
        <tbody class="fs18" id="reprint-area" style="height:408px">
          @for($i=0;$i<9;$i++)
            <tr><td class="dptd-sm" colspan="5" width="911px"></td></tr>
          @endfor
        </tbody>
      </table>
    </div>
    <div class="text-center">
      <button class="btn btn-lg btn-danger" onclick="closeReprintPopup()">Close</button>
    </div>
  </div>
</div>
