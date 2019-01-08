function openCashBoxPopup() {
  removeFocusFromBarcode();
  initCashBoxPopup();
  $('#disable-bg').show();
  $('#cashBoxPopup').show();
  $('#cashbox-amount').focus();
}

function closeCashBoxPopup() {
  $('#disable-bg').hide();
  $('#cashBoxPopup').hide();
  focusOnBarcode();
}

function initCashBoxPopup() {
  $('#cashbox-reason').val(0);
  $('#cashbox-amount').val('');
  $('#cashbox-note').val('');
}

function openCashBox() {
  spinnerPlay();
  if(!validateCashBoxData()) {
    spinnerStop();
    return false;
  }
  var data = {
    "open_type":"1",
    "reason_id":$('#cashbox-reason').val(),
    "note":$('#cashbox-note').val()
  };
  if(isCashFlow()) {
    data['amount'] = $('#cashbox-amount').val();
  }
  openCashier(data);
}

function validateCashBoxData() {
  if($('#cashbox-reason').val() == '0') {
    alert('Please choose the reason.');
    return false;
  }
  if(isCashFlow()) {
    if($('#cashbox-amount').val() == 0 || $('#cashbox-amount').val() == '') {
      alert('Amount is required.');
      return false;
    }
  }
  return true;
}

function setCashBoxReason() {
  if(isCashFlow()) {
    $('#cashbox-amount').prop('disabled', false);
  }
  else {
    $('#cashbox-amount').val('');
    $('#cashbox-amount').prop('disabled', true);
  }
}

function isCashFlow() {
  return $.inArray($('#cashbox-reason').val(), cashAmountRequired) != -1 ? true : false;
}
