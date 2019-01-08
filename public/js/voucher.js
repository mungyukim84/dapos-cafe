function openVoucherPopup () {
  removeFocusFromBarcode();
  initVoucherPopup();
  $('#disable-bg').show();
  $('#voucherPopup').show();
  $('#item-name').focus();
}

function closeVoucherPopup() {
  $('#disable-bg').hide();
  $('#voucherPopup').hide();
  focusOnBarcode();
}

function initVoucherPopup() {
  $('.voucher-list input').val('');
  $('#voucher-total').val('');
  $('#voucher-cc-amount').val('');
  $('#voucher-cc-type').val('0');
  $('#voucher-ec-amount').val('');
  $('#voucher-cash-amount').val('');
}

function calculateTotal() {
  var total = 0;
  $('.voucher-list').each(function(idx, obj) {
    var amount, dc = 0;
    if($(obj).find('#voucher-code').val() === '') {
      return;
    }
    else {
      amount = $(obj).find('#voucher-amount').val() !== '' ? ePrice($(obj).find('#voucher-amount').val()) : 0;
//      dc = $(obj).find('#voucher-dc').val() !== '' ? ePrice($(obj).find('#voucher-dc').val()) : 0;
      total += ePrice(amount * (1 - eRound(dc / 100, 2)));
    }
  });
  $('#voucher-total').val(total);
}

function validateVoucher() {
  // input Numeric or empty check
  var vouchers = [];
  var faceAmount = 0;
  var isListErr = false;
  $('.voucher-list').each(function(idx, obj) {
    var code = $(obj).find('#voucher-code').val();
    var amount = $(obj).find('#voucher-amount').val();
//    var dc = $(obj).find('#voucher-dc').val() !== '' ? eRound(Number($(obj).find('#voucher-dc').val()) / 100, 2) : 0;
    if(code !== '') {
      if(amount === '' || Number(amount) === 0) {
        isListErr = true;
        alert('Please fill the amount on ' + (idx + 1) + ' row.');
        return false;
      }
    }
    if(Number(amount) > 0) {
      if(code === '') {
        isListErr = true;
        alert('Voucher code is missed on ' + (idx + 1) + ' row.');
        return false;
      }
    }
    vouchers.push({"code":code, "amount":amount, "dc":0});
    faceAmount += ePrice(amount);
  });

  if(isListErr) {
    return false;
  }

  if(!$.isNumeric($('#voucher-total').val()) || Number($('#voucher-total').val()) === 0) {
    alert("Please fill Amount");
    return false;
  }

  // Sum - Payment
  var paymentSum = 0;
  $('.voucher-payment').each(function(idx, obj) {
    paymentSum += $(obj).val() === '' ? 0 : ePrice($(obj).val());
  });

  if(ePrice($('#voucher-total').val()) !== paymentSum) {
    alert("Payment Amount is not the same with Sales Price.");
    return false;
  }

  // Credit Card Type check
  if($('#voucher-cc-amount').val() !== '' && $('#voucher-cc-type').val() === "0"){
    alert("Please select credit card type.");
    return false;
  }

  // send Ajax call
  if(confirm('Do you wish to proceed the payment?')) {
    vouchers = vouchers.filter(function(v){return v.code !== ''});
    createVoucher({
      "vouchers": vouchers,
      "original_price": faceAmount,
      "sales_price": paymentSum,
      "cc_amount": ePrice($('#voucher-cc-amount').val()),
      "cc_type": $('#voucher-cc-type').val(),
      "ec_amount": ePrice($('#voucher-ec-amount').val()),
      "cash_amount": ePrice($('#voucher-cash-amount').val())
    });
  }
}

// Voucher Search
function openVoucherSearchPopup () {
  removeFocusFromBarcode();
  $('#disable-bg').show();
  $('#voucherSearchPopup').show();
  $('#voucher-search-code').val('');
  $('#voucher-search-amount').val('');
  $('#voucher-search-code').focus();
}

function closeVoucherSearchPopup() {
  $('#disable-bg').hide();
  $('#voucherSearchPopup').hide();
  focusOnBarcode();
}
