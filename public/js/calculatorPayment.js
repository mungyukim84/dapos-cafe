function openCalculatorPayment(el) {
  $('#calculator-payment-value').val(0);
  $('.dapos-popup').css({'margin-left':'-600px'});
  $("#calculator-payment-enter").off("click");
  $('#calculator-payment-enter').click(function() {
    insertCalculatorPValue(el);
  });

  $('#disable-popup').show();
  $('#calculatorPaymentPopup').show();
}

function openCalculatorPartialPayment(prop, productCode) {
  $('#calculator-payment-value').val(0);
  $("#calculator-payment-enter").off("click");
  $('.dapos-calculator').css({'left':'30%'});
  $('#calculator-payment-enter').click(function() {
    insertCalculatorPartialValue(prop, productCode);
  });

  $('#disable-popup').show();
  $('#calculatorPaymentPopup').show();
}

function insertCalculatorPartialValue(prop, productCode) {
  var calculatorVal = Number($('#calculator-payment-value').val());
  if(calculatorVal > 100) {
    alert("You can't discount more than 100%.");
    document.getElementById('calculator-payment-value').value = 0;
    return false;
  }
  else {
    if(calculatorVal == 0) {
      basketObj[productCode].item.dc = 0;
      basketObj[productCode].item.discountedUVP = 0;
      basketObj[productCode].item.isPartialDiscount = 0;
    }
    else {
      basketObj[productCode].item.dc = eRound(calculatorVal / 100, 2);
      basketObj[productCode].item.isPartialDiscount = 1;
    }
    $('.dapos-calculator').css({'left':'50%'});
    setMainTable();
  }
  closeCalculatorPayment();
}

function closeCalculatorPayment() {
  $('.dapos-calculator').css({'left':'50%'});
  $('#disable-popup').hide();
  if($('#payment-selection-tab').is(':visible')){
    calculatePartialMinusSum();
  }
  $('#calculatorPaymentPopup').hide();
  $('.dapos-popup').css({'margin-left':'-420px'});
}


function insertCalculatorPValue(el) {
  var val = Number($('#calculator-payment-value').val());
//  $(el).val(eEuro(val));
  if($(el).attr('id') === 'payment-option-discount') {
    if(val > 100) {
      alert("You can't discount more than 100%.");
      return false;
    }
    else {
      $(el).val(val);
      discountChanged(val);
    }
  }
  else if($(el).attr('id') === 'payment-option-bell-number') {
    $(el).val(val);
  }
  else if($(el).attr('id') === 'payment-selection-voucher') {
    if(ePrice($(el).val()) > paymentObj.voucher.max) {
      alert("Voucher Amount is less than the set value.");
      return false;
    }
    $(el).val(eEuro(val));
  }
  else {
    $(el).val(eEuro(val));
  }
  closeCalculatorPayment();
}

function setCalculatorPValue(val) {
  var calculatorInput = $('#calculator-payment-value').val();

  if(val == '.') {
    if(calculatorInput.indexOf(".") > -1) {
      alert("Can't use more than one decimal point.");
      return false;
    }
  }

  if(calculatorInput.length == 1 && calculatorInput == 0) {
    if(val != '.') {
      calculatorInput = '';
    }
  }

  if(val == 'del') {
    if(calculatorInput.length < 2) {
      $('#calculator-payment-value').val(0);
    }
    else {
      $('#calculator-payment-value').val(calculatorInput.substring(0, calculatorInput.length-1));
    }
  } else if (val == 'clear') {
      $('#calculator-payment-value').val(0);
    }
    else {
    if(calculatorInput.split(".")[1] != null && calculatorInput.split(".")[1].length == 2) {
      alert("Can't add more decimal.");
      return false;
    }
    $('#calculator-payment-value').val(calculatorInput + val);
  }
}
