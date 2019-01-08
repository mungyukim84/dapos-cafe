function openSalesSummaryPopup() {
  removeFocusFromBarcode();
  initSalesSummary();
  $('#sales-summary-from-date').val(moment().format("YYYY-MM-DD"));
  $('#sales-summary-to-date').val(moment().format("YYYY-MM-DD"));
  $('#salesSummaryPopup').show();
  $('#disable-bg').show();
}

function closeSalesSummaryPopup() {
  $('#salesSummaryPopup').hide();
  $('#disable-bg').hide();
  focusOnBarcode();
}

function initSalesSummary() {
  initSalesSummaryTable();
  $('#sales-summary-total').val('');
  $('#sales-summary-tax19').val('');
  $('#sales-summary-tax7').val('');
  $('#sales-summary-creditcard').val('');
  $('#sales-summary-eccard').val('');
  $('#sales-summary-cash').val('');
}

function initSalesSummaryTable() {
  var trStr = [];
  for(var i = 0; i < 9;i++) {
    trStr.push('<tr><td class="dptd-sm" colspan="5" width="911px"></td></tr>');
  }
  $('#sales-summary-area').html('');
  $('#sales-summary-area').append(trStr.join(''));
}

function setSalesSummary(data) {
  initSalesSummary();
  setSalesSummaryTable(data.items);
  $('#sales-summary-total').val(eEuro(data.sales[0].totalSum));
  $('#sales-summary-creditcard').val(eEuro(data.sales[0].creditSum));
  $('#sales-summary-eccard').val(eEuro(data.sales[0].ecSum));
  $('#sales-summary-cash').val(eEuro(data.sales[0].cashSum));
  $('#sales-summary-tax19').val(eEuro(data.tax['0.19'] ? data.tax['0.19'].taxSum : 0));
  $('#sales-summary-tax7').val(eEuro(data.tax['0.07'] ? data.tax['0.07'].taxSum : 0));
}

function setSalesSummaryTable(data) {
  var trStr = [];
  var itemCnt = 0;
  $.each(data, function(key, obj) {
    var idx = 0;
    $.each(obj, function(k, o) {
      trStr.push('<tr>');
      if(idx == 0) {
        trStr.push('<td rowspan="' + obj.length + '" class="dptd" style="width:240px;vertical-align:middle;text-align:center;border:1px solid black;">' + o.categoryName + '</td>');
        trStr.push('<td rowspan="' + obj.length + '" class="dptd" style="width:320px;vertical-align:middle;border:1px solid black;">' + o.itemName + '</td>');
        trStr.push('<td rowspan="' + obj.length + '" class="dptd" style="width:80px;vertical-align:middle;text-align:center;border:1px solid black;">' + eEuro(ePrice(o.sales_price)) + '</td>');
      }
      trStr.push('<td class="dptd" style="width:70px;vertical-align:middle;text-align:center;border:1px solid black;">' + eRound(Number(o.discount_rate) * 100, 0) + ' %</td>');
      trStr.push('<td class="dptd" style="width:70px;vertical-align:middle;text-align:center;border:1px solid black;">' + o.qty + '</td>');
      trStr.push('<td class="dptd" style="width:110px;vertical-align:middle;text-align:center;border:1px solid black;">' + eEuro(o.total) + '</td>');
      trStr.push('</tr>');
      idx++;
      itemCnt++;
    });
  });

  if(itemCnt < 6) {
    for(var i = 0; i < (6 - itemCnt);i++) {
      trStr.push('<tr><td class="dptd-sm" colspan="6" width="911px"></td></tr>');
    }
  }

  $('#sales-summary-area').html('');
  $('#sales-summary-area').append(trStr.join(''));
}
