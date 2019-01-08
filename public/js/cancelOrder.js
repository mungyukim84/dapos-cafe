function initRefund() {
  $('#cancelOrder-receiptNum').val('');
  $('#cancelOrder-paymentMethod').text('-');
  $('.btn-group').children('.btn').removeClass('btn-warning');
  $('.card-cancel-yn').hide();
  if($('#cancel-item-chkAll').is(':checked')) {
    $('#cancel-item-chkAll').prop('checked', false);
  }

  refundOrderObj = {};
  initRefundTable();
}

function openCancelOrderPopup() {
  if(listPrice > 0) {
    alert('Unable to open Cancel menu during checkout process');
    return false;
  }
  if(originalReceiptNum != '') {
    alert('Please complete partial cancel process first');
    return false;
  }
  removeFocusFromBarcode();
  initRefund();
  $('#cancelOrderPopup').show();
  $('#disable-bg').show();
  $('#cancelOrder-receiptNum').focus();
}

function closeCancelOrderPopup() {
  enableRefundPartial();
  $('#cancelOrderPopup').hide();
  $('#disable-bg').hide();
  focusOnBarcode();
}

function openCancelAllPopup() {
  if(!isCardCancellableSelected()) {
    return false;
  }
  var returnAmount = refundOrderObj.sales_price;

  if(cancelledItems.length > 0) {
      var cancelledAmount = cancelledItems.reduce(function(total, item){
        return total + (ePrice(item.sales_price) * Number(item.qty));
      }, 0);
      returnAmount -= cancelledAmount;
  }

  $('#refund-all-price').text(eEuro(returnAmount) + ' €');

  $('#cancelOrder-all-creditcard-block').hide();
  $('#cancelOrder-all-eccard-block').hide();
  $('#cancelOrder-all-cash-block').hide();
  $('#refund-all-creditcard').val(0);
  $('#refund-all-eccard').val(0);
  $('#refund-all-cash').val(0);

  if($('#cancel-item-chkAll').is(':checked')) {
    $('#cancel-item-chkAll').prop('checked', false);
  }

  if(cardCancellable == 'Y') {
    if(Number(refundOrderObj.creditcard_amount) > 0) {
      $('#cancelOrder-all-creditcard-block').show();
      $('#refund-all-creditcard').val(eEuro(refundOrderObj.creditcard_amount));
    }
    if(Number(refundOrderObj.eccard_amount) > 0) {
      $('#cancelOrder-all-eccard-block').show();
      $('#refund-all-eccard').val(eEuro(refundOrderObj.eccard_amount));
    }
    if(Number(refundOrderObj.cash_amount) > 0) {
      $('#cancelOrder-all-cash-block').show();
      $('#refund-all-cash').val(eEuro(refundOrderObj.cash_amount));
    }
  }
  else {
    $('#cancelOrder-all-cash-block').show();
    $('#refund-all-cash').val(eEuro(returnAmount));
  }

  $('#disable-popup').show();
  $('#cancelOrder-all').show();
}

function closeCancelAllPopup() {
  $('#disable-popup').hide();
  $('#cancelOrder-all').hide();
}

function openCancelPartialPopup() {
  if(!isCardCancellableSelected()) {
    return false;
  }

  if(isProductSelected()) {
    $('#refund-partial-select-tab').show();
    $('#refund-partial-refund-tab').hide();
    setRefundPartialPopup();
    $('#disable-popup').show();
    $('#cancelOrder-partial').show();
  }
  else {
    alert('To cancel the order partially, please select at least one item on table');
  }
}

function closeCancelPartialPopup() {
  $('#disable-popup').hide();
  $('#cancelOrder-partial').hide();
}

function openRefundTab() {
  if(Number(refundAmount) === 0) {
    alert('No item is selected to cancel.');
    return false;
  }
  $('#cancelOrder-partial-usd-block').hide();
//  $('#refund-partial-cash-label').css('width', 430);
  $('#refund-partial-all-euro-btn').hide();
  $('#refund-partial-cash').val(eEuro(refundAmount));

  $('#refund-partial-select-tab').hide();
  $('#refund-partial-refund-tab').show();
}

function backToRefundSelectionTab() {
  $('#refund-partial-refund-tab').hide();
  $('#refund-partial-select-tab').show();
}

function setRefundPopup() {
  cardCancellable = '';
  enableRefundPartial();
  $('.btn-group').children('.btn').removeClass('btn-warning');
  $('.card-cancel-yn').hide();
  $('#cardCancellable-yes').prop('disabled', false);

  var rowCnt =0;
  var trStr = [];

  if(Number(refundOrderObj.creditcard_amount) > 0 || Number(refundOrderObj.eccard_amount) > 0) {
    $('.card-cancel-yn').show();
  }

  $.each(refundOrderObj.order_detail, function(key, obj) {
    var isAllCancelled = false;
    obj.cancellableQty = Number(obj.qty);

    if(cancelledItems.length > 0) {
      var cItem = cancelledItems.filter(function(item){return item.item_id === obj.item_id});
      if(cItem.length > 0) {
        obj.cancellableQty -= cItem.reduce(function(t, v) {return t + Number(v.qty)}, 0);
        if(obj.cancellableQty === 0) {
          isAllCancelled = true;
        }
      }
    }

    trStr.push('<tr>');
    if(isAllCancelled) {
      trStr.push('<td width="50px" class="dptd-sm vmiddle" style="text-align:center"></td>');
    }
    else {
      trStr.push('<td width="50px" class="dptd-sm vmiddle" style="text-align:center"><input class="cancelOrder-item-checkbox" type="checkbox" data-itemId="' + obj.item_id + '" data-maxCancelQty="' + obj.cancellableQty +  '"/></td>');
    }
    trStr.push('<td width="463px" class="dptd-sm vmiddle">' + obj.cafe_menu.name + '</td>');
    trStr.push('<td width="105px" class="dptd-sm vmiddle" style="text-align:center">' + obj.cancellableQty + ' / ' + obj.qty +  '</td>');
    trStr.push('<td width="105px" class="dptd-sm vmiddle" style="text-align:right">' + eEuro(ePrice(obj.sales_price))+ ' €</td>');
    trStr.push('<td width="88px" class="dptd-sm vmiddle" style="text-align:right">' + eEuro(ePrice(obj.sales_price) * Number(obj.cancellableQty))+ ' €</td>');
    trStr.push('</tr>');
    rowCnt++;
  });

  if(cancelledItems.length > 0) {
    isCardCancellable(document.getElementById("cardCancellable-no"), 'N');
    $('#cardCancellable-yes').prop('disabled', true);
    trStr.push('<tr><td class="dptd-sm" colspan="5" width="763px" style="text-align:center;font-weight:bold;">Cancelled Items...</td></tr>');
    rowCnt++;
    $.each(cancelledItems, function(key, obj) {
      trStr.push('<tr style="text-decoration:line-through">');
      trStr.push('<td width="50px" class="dptd-sm vmiddle" style="text-align:center"></td>');
      trStr.push('<td width="463px" class="dptd-sm vmiddle">' + obj.cafe_menu.name + '</td>');
      trStr.push('<td width="105px" class="dptd-sm vmiddle" style="text-align:center">' + obj.qty + '</td>');
      trStr.push('<td width="105px" class="dptd-sm vmiddle" style="text-align:right">' + eEuro(Number(obj.sales_price)) + ' €</td>');
      trStr.push('<td width="88px" class="dptd-sm vmiddle" style="text-align:right">' + eEuro(Number(obj.sales_price) * Number(obj.qty))+ ' €</td>');
      trStr.push('</tr>');
      rowCnt++;
    });
  }

  if(rowCnt < 6) {
    for(var i=0;i < 6 - rowCnt;i++) {
      trStr.push('<tr><td class="dptd-sm" colspan="5" width="763px" height="39px"></td></tr>');
    }
  }

  $('#refund-main-area').html('');
  $('#cancelOrder-paymentMethod').text('');
  $('#cancelOrder-paymentMethod').text(setRefundPaymentObj().join(', '));
  $('#refund-main-area').append(trStr.join(''));
}

function initRefundTable() {
  var trStr = [];
  for(var i=0;i < 6;i++) {
    trStr.push('<tr><td class="dptd" colspan="5" style="width:828px;height:39px">&nbsp;</td></tr>');
  }
  $('.cancelOrder-item-checkbox').prop("checked", false);
  $('#refund-main-area').html('');
  $('#refund-main-area').append(trStr.join(''));
}

function isProductSelected() {
  refundQtyChangeProductId = [];
  $('.cancelOrder-item-checkbox').each(function(idx, el) {
    if($(el).is(':checked')){
      refundQtyChangeProductId.push($(el).attr('data-itemId'));
    }
  });
  return refundQtyChangeProductId.length > 0 ? true : false;
}


function setRefundPartialPopup() {
  var productCnt = 0;
  var trStr = [];
  partialItems = [];

  refundQtyChangeProductId.forEach(function(val) {
    $.each(refundOrderObj.order_detail, function(key, obj) {
      if(val == obj.item_id) {
        trStr.push('<tr class="refund-partial-item-row" data-itemId ="' + val + '">');
        trStr.push('<td width="320px" class="dptd-sm" style="font-size:12px">' + obj.cafe_menu.name + '</td>');
        trStr.push('<td width="89px" class="dptd-sm refund-partial-unitprice" style="text-align:right;font-size:14px">' + eEuro(ePrice(obj.sales_price)) + ' €</td>');
        trStr.push('<td width="130px" class="dptd-sm" style="text-align:center"><input type="text" class="popup-sm-qty-input refund-partial-qty" onclick="openSmCalculator(this)" style="text-align:center" value="0" readonly> / ' + obj.cancellableQty + '</td>');
        trStr.push('<td width="90px" class="dptd-sm" style="text-align:right;font-size:14px"><span class="refund-partial-return-amount"> 0,00 €</span></td>');
        trStr.push('</tr>');
        partialItems.push($.extend(true, {}, obj));
        productCnt++;
      }
    });
  });

  if(productCnt < 6) {
    for(var i=0;i<6 - productCnt;i++) {
      trStr.push('<tr><td class="dptd-sm" colspan="5" width="518px" height="46px"></td></tr>');
    }
  }

  refundAmount = 0;
  initPartialItems();
  $('.refund-partial-price').text('0,00 €');
  $('#refund-partial-area').html('');
  $('#cancelOrder-paymentMethod').text('');
  $('#cancelOrder-paymentMethod').text(setRefundPaymentObj().join(', '));
  $('#refund-partial-area').append(trStr.join(''));
}

function initPartialItems() {
  $.each(partialItems, function(k, o) {
    o.qty = 0;
  });
}

function setRefundPaymentObj () {
  var paymentMethodArr = [];
  refundOrderObj.payment_method.split('').forEach(function(val, idx){
    if(val == 1) {
      if(idx == 0) {
        paymentMethodArr.push('CreditCard');
      } else if (idx == 1) {
        paymentMethodArr.push('EC Card');
      } else if (idx == 2) {
        paymentMethodArr.push('Cash');
      } else if (idx == 3) {
        paymentMethodArr.push('Voucher');
      }
    }
  });

  return paymentMethodArr;
}

function setbasketObj() {
  originalReceiptNum = refundOrderObj.receipt_num;
  $.each(refundOrderObj.order_detail, function(key, obj) {
    var basketKey = createBasketKey(obj.item_id);
    basketObj[basketKey] = {"item":obj.cafe_menu, "qty":obj.qty};
  });
  setMainTable();
}

function isCardCancellable(el, type) {
  cardCancellable = type;
  if(!$(el).hasClass('btn-warning')) {
    $(el).addClass('btn-warning');
    if($(el).siblings('.btn').hasClass('btn-warning')) {
      $(el).siblings('.btn').removeClass('btn-warning');
    }
  }
  if(cardCancellable === 'Y') {
    disableRefundPartial();
  }
  else {
    enableRefundPartial();
  }
}

function isCardCancellableSelected() {
  var result = true;
  if($('.card-cancel-yn').is(':visible') && cardCancellable === "") {
    result = false;
    alert('Please select Card Cancellable.');
  }
  return result;
}


function setReturnAmount($row, changedProduct) {
  var returnAmount = Number(changedProduct.sales_price) * changedProduct.qty;
  $row.html("");
  $row.text(eEuro(returnAmount) + ' €');
}

function setRefundAmount() {
  refundAmount = 0;
  $('.refund-partial-item-row').each(function(idx, row) {
    var unitPrice = eNumber($(row).find('.refund-partial-unitprice').text().split(' ')[0]);
    var qty = Number($(row).find('.refund-partial-qty').val());
    refundAmount += unitPrice * qty;
  });
  $('.refund-partial-price').text(eEuro(refundAmount) + ' €');
}

function enableRefundPartial() {
  $('.cancelOrder-item-checkbox').prop('disabled', false);
  $('#refund-item-selection-table').css('opacity', 1);
  $('#refund-partial-btn').prop('disabled', false);
}

function disableRefundPartial() {
  if($('#cancel-item-chkAll').is(':checked')) {
    $('#cancel-item-chkAll').prop('checked', false);
  }
  $('.cancelOrder-item-checkbox').prop("checked", false);
  $('.cancelOrder-item-checkbox').prop('disabled', true);
  $('#refund-item-selection-table').css('opacity', 0.5);
  $('#refund-partial-btn').prop('disabled', true);
}
