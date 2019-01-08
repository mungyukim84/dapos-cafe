function openPaymentPopup() {
  if(Object.keys(basketObj).length > 0) {
    initPayment();
    initPaymentAcceptButtons();
    $('#disable-bg').show();
    $('#paymentPopup').show();
  }
  else {
    alert('To proceed payment, select a item First.');
  }
}

function closePaymentPopup() {
  focusOnBarcode();
  $('#disable-bg').hide();
  $('#paymentPopup').hide();
  discountChanged(0);
  $.each(basketObj, function(key, obj) {
    obj.item.dc = 0;
    obj.applied_tax_rate = 0;
  });
}

function initPayment() {
  removeFocusFromBarcode();
  $('#payment-option-tab').show();
  $('#payment-selection-tab').hide();
  $('#payment-text-listprice').css({'text-decoration':''}).text(eEuro(netPrice) + '€');
  $('#discounted-price-text').hide();
  $('#payment-transaction-tab').hide();
  $('#payment-cashsum-tab').hide();

  $('#payment-option-bell-number').val('');
  $('#payment-option-coupon').val('');
  $('#payment-option-discount').val('');
  $('#payment-selection-creditcard').val('');
  $('#payment-selection-eccard').val('');
  $('#payment-selection-cash').val('');
  $('#payment-transaction-amount').val(0);
  $('#payment-transaction-creditcard-type').val(0);
  $('#payment-option-coupon-validation').hide();

  $('#tpad-text').show();
  $('#partial-price-text').hide();
  $('.btn-group').children('.btn').removeClass('btn-success');
  takeInOut = '';
  membership = '';
  isPartialPayment = 'N';
  cashReceived = 0;
  $.each(basketObj, function(key, obj) {
    obj.discount_rate = 0;
    obj.applied_tax_rate = 0;
    if(obj.item.isPartialDiscount == 0){
      //obj.item.dc = 0;
    }
  });
}

function isTakeInOrOut(el, type) {
  if(!$(el).hasClass('btn-success')) {
    $(el).addClass('btn-success');
    if($(el).siblings('.btn').hasClass('btn-success')) {
      $(el).siblings('.btn').removeClass('btn-success');
    }
  }
  takeInOut = type;

  $.each(basketObj, function(key, obj) {
    if(type == 'in') {
      obj.applied_tax_rate = Number(obj.item.tax_rate_in);
    }
    else {
      obj.applied_tax_rate = Number(obj.item.tax_rate_out);
    }
  });
}

function openPaymentSelection() {
  if(takeInOut != '') {
    initPaymentObj();
    $('#payment-selection-cash').val('');
    $('#payment-selection-creditcard').val('');
    $('#payment-transaction-creditcard-type').val(0);
    $('#payment-selection-eccard').val('');
    $('#payment-selection-voucher').val('');
    $('#payment-selection-voucher-code').val('');
    $('#payment-selection-voucher-checksum').text('');
    $('#payment-text-partial').text(eEuro(netPrice) + ' €');
    $('#payment-option-tab').hide();
    $('#discounted-price-text').hide();
    $('#payment-selection-tab').show();
    $('#partial-price-text').show();
  }
  else {
    alert('Please select In/Out.')
  }
}

function backToPaymentOption() {
  $('#payment-option-tab').show();
  $('#payment-selection-tab').hide();
  $('#partial-price-text').hide();
  if($('#payment-option-discount').val != ''){
    $('#discounted-price-text').show();
  }
  else{
    $('#discounted-price-text').hide();
  }
}

function openPaymentPartial() {
  isPartialPayment = 'Y';
  var partialPaymentMethod = [];
  var type,blockId,inputId= '';
  var sum = 0;
  netPrice = ePrice(netPrice);
  $('#execute-payment').hide();
  $('#payment-selection-tab input').each(function(i, el){
    type,blockId,inputId= '';
    if(el.value != null && eNumber(el.value) > 0) {
      sum += ePrice(eNumber(el.value));
      if(i == 0){
        type = 'cash';
        paymentObj.cash.amount = eNumber(el.value);
        paymentObj.cash.status = '1';
        $('#execute-payment').text('Check Change');
        $('#execute-payment').attr('onclick', 'payWithCash();');
        $('#execute-payment').show();
      } else if(i == 1) {
        type = 'creditcard';
        paymentObj.creditCard.amount = eNumber(el.value);
        paymentObj.creditCard.status = '1';
        $('#partial-payment-creditcard').attr('onclick', 'payByCreditCard();');
        $('#partial-payment-creditcard').show();
      } else if(i == 2) {
        type = 'eccard';
        paymentObj.ecCard.amount = eNumber(el.value);
        paymentObj.ecCard.status = '1';
        $('#partial-payment-eccard').attr('onclick', 'payByEcCard();');
        $('#partial-payment-eccard').show();
      } else if(i == 3) {
        type = 'voucher';
        paymentObj.voucher.amount = eNumber(el.value);
        paymentObj.voucher.status = '1';
        $('#partial-payment-voucher').attr('onclick', 'payWithVoucher();');
        $('#partial-payment-voucher').show();
      }

// payment object 추가
      blockId = type + '-block';
      inputId = 'payment-transaction-' + type;
      $('#'+inputId).val(el.value);
      $('#'+blockId).show();
    }
  });

  sum = ePrice(sum);
  if(sum !== netPrice) {
    alert('Partial Price sum is not the same with payment amount');
  }
  else {
    $('#tpad-text').hide();
    $('#partial-price-text').hide();
    $('#payment-selection-tab').hide();
    $('#payment-transaction-tab').show();
  }
}

function backToSelectionTab() {
  var voucherCode = paymentObj.voucher.code;
  initPaymentObj();
  $('#payment-transaction-amount').val(0);
  isPartialPayment = 'N';

  if(voucherCode !== '') {
    checkVoucherByCode(voucherCode);
  }

  initPaymentAcceptButtons();

  $('.payment-transaction-block').hide();
  $('#partial-payment-creditcard').hide();
  $('#partial-payment-eccard').hide();
  $('#execute-payment').removeAttr('onclick');
  $('#tpad-text').show();
  $('#partial-price-text').show();
  $('#payment-transaction-tab').hide();
  $('#payment-selection-tab').show();
}

function backToTransactionTab() {
  $('#payment-cashsum-cash').text('');
  $('#payment-cashsum-received').text('');
  $('#payment-cashsum-change').text('');
  $('#payment-cashsum-tab').hide();
  $('#payment-transaction-tab').show();
}

function discountChanged(dcRate) {
  var discountedPrice = 0;
  discountRate = eRound(Number(dcRate) / 100, 2);

  if(discountRate > 0) {
    $('#discounted-price-text').show();
    $('#payment-text-listprice').css({'text-decoration':'line-through'}).text(eEuro(netPrice) + ' €');
  }
  else if (discountRate == 0) {
    $('#discounted-price-text').hide();
    $('#payment-text-listprice').css({'text-decoration':''})
  }

  $.each(basketObj, function(key, obj) {
    if(obj.item.isPartialDiscount == 1) {
      discountedPrice += getDiscountedSalesPrice(obj.item.sales_price, obj.item.dc) * Number(obj.qty);
    }
    else if(obj.item.isPartialDiscount == undefined || obj.item.isPartialDiscount == 0){
      obj.item.isPartialDiscount = 0;
      obj.item.dc = discountRate;
      discountedPrice += getDiscountedSalesPrice(obj.item.sales_price, discountRate) * Number(obj.qty);
    }
  });

  netPrice = discountedPrice;
  $('#payment-text-discount').text(eEuro(netPrice) + ' €');
}

function restAll(elId) {
  var rest = eNumber($('#payment-text-partial').text().split(' ')[0]);
    if(rest < netPrice && rest > 0) {
    if(elId === 'payment-selection-voucher' && rest > paymentObj.voucher.max) {
      alert('Voucher amount is less than the rest.');
      return false;
    }
    $('#'+elId).val(eEuro(rest));
    calculatePartialMinusSum();
  }
}

function initPaymentObj() {
  paymentObj = {
    "creditCard":{"amount":0, "status":0, "type":0},
    "ecCard":{"amount":0, "status":0},
    "cash":{"amount":0, "status":0},
    "voucher":{"amount":0, "status":0, "code":'', "max":0},
  };
}

function calculatePartialMinusSum() {
  var partialSum = 0;
  $('#payment-selection-tab input').each(function(i, el){
    if(el.value != null && eNumber(el.value) > 0) {
      partialSum += eNumber(el.value);
    }
  });
  $('#payment-text-partial').text(eEuro(ePrice(netPrice) - ePrice(partialSum)) + ' €');
}

function paymentAll(type) {
  var blockId = type + '-block';
  var inputId = 'payment-transaction-' + type;

  $('#'+inputId).val(eEuro(netPrice));
  $('#'+blockId).show();
  $('#execute-payment').text('SUCCESS');

  if(type == 'creditcard') {
    paymentObj.creditCard.amount = netPrice;
    paymentObj.creditCard.status = '1';
    $('#eccard-block').hide();
    $('#cash-block').hide();
    $('#voucher-block').hide();
    $('#execute-payment').attr('onclick', 'payByCreditCard();');
  } else if(type == 'eccard') {
    paymentObj.ecCard.amount = netPrice;
    paymentObj.ecCard.status = '1';
    $('#creditcard-block').hide();
    $('#cash-block').hide();
    $('#voucher-block').hide();
    $('#execute-payment').attr('onclick', 'payByEcCard();');
  } else if(type == 'cash') {
    paymentObj.cash.amount = netPrice;
    paymentObj.cash.status = '1';
    $('#creditcard-block').hide();
    $('#eccard-block').hide();
    $('#voucher-block').hide();
    $('#execute-payment').text('Check Change');
    $('#execute-payment').attr('onclick', 'payWithCash();');
  } else if(type == 'voucher') {
    if(ePrice(netPrice) > paymentObj.voucher.max) {
      alert('Voucher amount is less than the payment amount');
      return false;
    }
    paymentObj.voucher.amount = netPrice;
    paymentObj.voucher.status = '1';
    $('#creditcard-block').hide();
    $('#eccard-block').hide();
    $('#cash-block').hide();
    $('#execute-payment').attr('onclick', 'payWithVoucher();');
  }


// payment object 추가
  $('#tpad-text').hide();
  $('#partial-price-text').hide();
  $('#payment-selection-tab').hide();
  $('#execute-payment').show();
  $('#payment-transaction-tab').show();
}

function payByCreditCard() {
  if(Number(paymentObj.creditCard.type) == 0) {
    alert('Please select Credit Card Type.');
    return false;
  }
  // Status hardcoding due to no API connection
  paymentObj.creditCard.status = '2';

  // when Success
  if(isPartialPayment == 'N') {
    insertOrder();
  }
  else {
    paymentButtonChange('partial-payment-creditcard', true);
  }

}

function payByEcCard() {
  // Status hardcoding due to no API connection
  paymentObj.ecCard.status = '2';
  // when Success
  if(isPartialPayment == 'N') {
    insertOrder();
  }
  else {
    paymentButtonChange('partial-payment-eccard', true);
  }
}

function payWithVoucher() {
  // Status hardcoding due to no API connection
  paymentObj.voucher.status = '2';
  // when Success
  if(isPartialPayment == 'N') {
    insertOrder();
  }
  else {
    paymentButtonChange('partial-payment-voucher', true);
  }
}

function payWithCash() {
  if(isPartialPayment == 'Y') {
    if(!checkOtherPaymentStatus()) {
      alert('Please clear other paymentmethod first');
      return false;
    }
  }
  if(paymentObj.cash.amount > eNumber($('#payment-transaction-amount').val())) {
    alert('Received amount is smaller than payment');
    return false;
  }
  showCashSum();
}

function checkOtherPaymentStatus() {
  if(paymentObj.creditCard.status === 1 || paymentObj.creditCard.status === -1) {
    return false;
  }
  if(paymentObj.ecCard.status === 1 || paymentObj.ecCard.status === -1) {
    return false;
  }
  if(paymentObj.voucher.status === 1 || paymentObj.voucher.status === -1) {
    return false;
  }
  return true;
}

function showCashSum() {
  openCashier({"open_type":"1", "reason_id":"5"});
  cashReceived = eNumber($('#payment-transaction-amount').val());
  $('#payment-cashsum-cash').text(eEuro(paymentObj.cash.amount)  + ' €');
  $('#payment-cashsum-received').text(eEuro(cashReceived) + ' €');
  $('#payment-cashsum-change').text(eEuro(cashReceived - Number(paymentObj.cash.amount)) + ' €');
  $('#payment-transaction-tab').hide();
  $('#payment-cashsum-tab').show();
}

function addCash(amount) {
  $('#payment-transaction-amount').val(function(i, val) {
    return eEuro(eNumber(val) + parseFloat(amount));
  });
}

function payWithCashSucess() {
  paymentObj.cash.status = '2';
  insertOrder();
}

function paymentButtonChange(elId, status) {
  if(status) {
    $('#' + elId).removeClass('btn-success').addClass('btn-default').html('<span class="glyphicon glyphicon-ok text-success"></span> Success');
  }
  isPartialPaymentDone();
}

function creditCardTypeChanged(el) {
  paymentObj.creditCard.type = $(el).val();
}

function isPartialPaymentDone() {
  return Object.keys(paymentObj).filter(function(key){return paymentObj[key].status === '1';}).length > 0 ? false : insertOrder();
}

function initPaymentAcceptButtons() {
  $('.btn-exec-payment').each(function(idx, obj){
    if($(obj).hasClass('btn-default')) {
      $(obj).removeClass('btn-default').addClass('btn-success').html('Accept');
    }
  });
}
