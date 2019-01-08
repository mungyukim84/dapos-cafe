function openCalculator(prop, productCode) {
  $('#calculator-value').val(0);
  $("#insert-index-val").off("click");
  $('#insert-index-val').click(function() {
    insertCalculatorValue(prop, productCode);
  });

  $('#disable-bg').show();
  $('#CalculatorPopup').show();
}

function closeCalculator() {
  $('#disable-bg').hide();
  $('#CalculatorPopup').hide();
}

function insertCalculatorValue(prop, basketKey) {
  if(prop == 'qty') {
    basketObj[basketKey].qty = Number($('#calculator-value').val());
    setMainTable();
  }
  closeCalculator();
}

function setCalculatorValue(val) {
  var calculatorInput = $('#calculator-value').val();

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
      $('#calculator-value').val(0);
    }
    else {
      $('#calculator-value').val(calculatorInput.substring(0, calculatorInput.length-1));
    }
  } else if (val == 'clear') {
      $('#calculator-value').val(0);
    }
    else {
    if(calculatorInput.split(".")[1] != null && calculatorInput.split(".")[1].length == 2) {
      alert("Can't add more decimal.");
      return false;
    }
    $('#calculator-value').val(calculatorInput + val);
  }
}

function openSmCalculator(el) {
  $("#calculator-sm-enter").off("click");
  $('#calculator-sm-enter').click(function() {
    insertSmCalculatorValue(el);
  });

  $('#calculator-sm-value').val(0);
  $('#disable-all-popup').show();
  $('#smCalculatorPopup').show();
}

function closeSmCalculator() {
  $('#disable-all-popup').hide();
  $('#smCalculatorPopup').hide();
}


function setSmCalculatorValue(val) {
  var calculatorInput = $('#calculator-sm-value').val();

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
      $('#calculator-sm-value').val(0);
    }
    else {
      $('#calculator-sm-value').val(calculatorInput.substring(0, calculatorInput.length-1));
    }
  } else if (val == 'clear') {
      $('#calculator-sm-value').val(0);
    }
    else {
    if(calculatorInput.split(".")[1] != null && calculatorInput.split(".")[1].length == 2) {
      alert("Can't add more decimal.");
      return false;
    }
    $('#calculator-sm-value').val(calculatorInput + val);
  }
}

function insertSmCalculatorValue(el) {
//  $(el).val($('#calculator-sm-value').val());
  var val = $('#calculator-sm-value').val();
  var $inputEl = $(el);

  if($inputEl.hasClass('refund-partial-qty')) {
    var itemId = Number($inputEl.parents('tr').attr('data-itemId'));
    var originalItem = refundOrderObj.order_detail.find(function(item){
      return item.item_id === itemId;
    });

    var changedItem = partialItems.find(function(item){
      return item.item_id === itemId;
    });

    if(val > originalItem.cancellableQty) {
      alert("You can't cancel over the original quality.");
      return false;
    }
    else {
      var $row = $inputEl.parents('tr').find('.refund-partial-return-amount');
      changedItem.qty = Number(val);
      $inputEl.val(val);
      setReturnAmount($row, changedItem);
      setRefundAmount();
    }
  }
  else {
    $inputEl.val(val)
  }
  closeSmCalculator();
}
