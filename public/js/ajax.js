function saveItem() {
  var url = '';
  var method = '';

  var isValidated = true;
  $('.item-validation-field').each(function() {
    if($(this).val() == '') {
      isValidated = false;
      return false;
    }
  })

  if(!isValidated) {
    alert('Name, Price and Tax are required.');
    return false;
  }

  var data = {
    "name": $('#item-name').val(),
    "category_id": $('#item-category').val(),
    "production_cost": $('#item-production-cost').val(),
    "sales_price": $('#item-price').val(),
    "tax_rate_in": $('#item-tax-in').val(),
    "tax_rate_out": $('#item-tax-out').val(),
    "recycle_cost": $('#item-recycle').val(),
    "ean": $('#item-ean').val(),
    "ean_2": $('#item-ean2').val(),
    "ean_3": $('#item-ean3').val(),
    "description": $('#item-description').val()
  };

  if ($('#item-id').val() == '') {
    url = '/item/insertItem';
    method = 'POST';
  }
  else {
    url = '/item/updateItem/' + $('#item-id').val();
    method = 'PUT';
  }

  spinnerPlay();
  $.ajax({
    url: url,
    type: method,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(d){
      spinnerStop();
      if(d.ok) {
        closeItemPopup();
        alert('Item is inserted/updated.');
        location.reload();
      }
      else {
        alert(d.msg);
        console.log(d.err);
      }
    }
  });
}

function deleteItems() {
  var itemIds = Object.keys(deleteItemObj);
  var str = itemIds.reduce(function(s, k){
    var parsedName = deleteItemObj[k].name.replace(/\n/g, ' ');
    console.log(parsedName);
    return s + parsedName +'\n';
  }, '');

  if(confirm('Do you wish to delete these items?\n' + str)) {
    spinnerPlay();
    $.ajax({
      url: '/item/deleteItems',
      type: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: {"itemIds": itemIds},
      success: function(d){
        spinnerStop();
        if(d.ok) {
          alert('Items are deleted.');
          location.reload();
        }
        else {
          alert(d.msg);
          console.log(d.err);
        }
      }
    });
  }
}

function validateCoupon(couponVal) {
  alert(couponVal);
}

function getItemWithBarcode(barcode) {
  var parsedBarcode = barcode.replace(/ß|-/g, '');
  if(parsedBarcode != null && parsedBarcode != '') {
    $.get("item/getItemWithBarcode/"+parsedBarcode, function(d) {
      if(d.item != null) {
        addItemToBasket(d.item);
      }
      else {
        alert('Barcode( ' + parsedBarcode + ' ) does not exist');
      }
    });
  }
  else {
    alert('Invalid Input: ' + parsedBarcode);
  }
}


function searchOrder() {
  var receiptNum = $('#cancelOrder-receiptNum').val();
  if(receiptNum != null && receiptNum != '') {
    spinnerPlay();
    $.get("sales/getOrderByReceiptNum?receiptNum=" + receiptNum + "&kasseId=" + $('#cancelOrder-kasse').val(), function(d) {
      spinnerStop();
      if(!d.isValidReceiptNum) {
        alert('Receipt( ' + receiptNum + ' ) does not exist');
        return false;
      }
      if(d.isCancellable) {
        refundOrderObj = {};
        cancelledItems = [];
        refundOrderObj = $.extend(true, {}, d.originalOrder);
        if(d.cancelledItems.length > 0) {
          cancelledItems = d.cancelledItems.slice(0);
        }
        setRefundPopup();
      }
      else {
        alert('All products have cancelled.');
      }
    });
  }
  else {
    spinnerStop();
    alert('Invalid Input: ' + receiptNum);
  }
}

function getOrdersWithDate() {
  $.get('sales/getOrdersWithDate/?frDate='+ $('#reprint-from-date').val() + '&toDate=' + $('#reprint-to-date').val() + '&kasseId=' + $('#reprint-kasse').val(), function(d) {
    if(d.orders.length > 0) {
      reprintObj = $.extend(true, {}, d.orders);
      setReprintTable();
    }
    else {
      alert('No Order found.');
      initReprintTable();
    }
  });
}

function insertOrder() {
  spinnerPlay();
  var data = {
    listPrice: listPrice,
    discountRate: discountRate,
    netPrice: netPrice,
    membership: membership,
    takeInOut: takeInOut,
    basket: basketObj,
    payment: paymentObj,
    bellNo: $('#payment-option-bell-number').val(),
    cashReceived: cashReceived,
    originalReceiptNum: originalReceiptNum != '' ? originalReceiptNum : ''
  };

  $.ajax({
    url:'/sales/insertOrder',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(d){
      spinnerStop();
      if(d.ok) {
        if(!d.printerOK.ok) {
          alert('Unable to print. Please check the status of Printer.');
          console.log(d.printerOK.msg);
        }
        getCashSumByKasse();
        originalReceiptNum = '';
        closePaymentPopup();
        clearAll();
      }
      else {
        alert('Transaction Error.');
        console.log(d.msg);
      }
    }
  });
}


function refundAllSuccess(type) {
  if(!confirm('Do you want to proceed refund?')) {
    return false;
  }
  spinnerPlay();
  var prefix = '#refund-' + type + '-';
  var data = {order:refundOrderObj};

  console.log(data);

  if(type === 'all') {
    refundOrderObj.creditcard_amount = eNumber($(prefix + 'creditcard').val());
    refundOrderObj.eccard_amount = eNumber($(prefix + 'eccard').val());

    if(cancelledItems.length > 0) {
      $.each(refundOrderObj.order_detail, function(key, obj) {
        var cItem = cancelledItems.filter(function(item){return item.item_id === obj.item_id});
        if(cItem.length > 0) {
          obj.qty = Number(obj.qty) - cItem.reduce(function(t, v) {return t + Number(v.qty)}, 0);
        }
      });
      refundOrderObj.order_detail = refundOrderObj.order_detail.filter(function(obj){return obj.qty > 0});
    }
  }
  else {
    refundOrderObj.creditcard_amount = 0;
    refundOrderObj.eccard_amount = 0;
    refundOrderObj.order_detail = partialItems.filter(function(obj){return obj.qty > 0});
  }

  refundOrderObj.cash_amount = eNumber($(prefix + 'cash').val());
  refundOrderObj.sales_price = refundOrderObj.creditcard_amount + refundOrderObj.eccard_amount + refundOrderObj.cash_amount;

  console.log(data);
  
  $.ajax({
    url:'/sales/cancelOrder',
    type:'PUT',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(d){
      spinnerStop();
      if(d.ok) {
        if(!d.printerOK.ok) {
          alert('Unable to print. Please check the status of Printer.');
          console.log(d.printerOK.msg);
        }
        getCashSumByKasse();
        if(cardCancellable === 'Y') {
          if(confirm('Do you want to list up the original Order?')){
            setbasketObj();
          }
        }
        if(type === 'all') {
          closeCancelAllPopup();
        }
        else {
          closeCancelPartialPopup();
        }
        closeCancelOrderPopup();
      }
      else {
        alert('Transaction Error.');
        console.log(d.msg);
      }
    }
  });
}

function reprintReceipt(orderId) {
//  spinnerPlay();
  $.ajax({
    url:'/sales/reprintReceipt',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: {orderId:orderId},
    success: function(d){
//      spinnerStop();
      if(!d.ok) {
        alert('Unable to print. Please check the status of Printer.');
        console.log(d.msg);
      }
    }
  });
}

function printLieferschein(discountRate) {
  if(takeInOut == null || takeInOut == '') {
    alert('Please select In/Out first.');
    return false;
  }
  spinnerPlay();
  $.ajax({
    url:'/sales/printLiederschein',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: {basket:basketObj, discountRate:discountRate},
    success: function(d){
      spinnerStop();
      if(!d.ok) {
        alert('Unable to print. Please check the status of Printer.');
        console.log(d.msg);
      }
    }
  });
}

function openCashier(data) {
  $.ajax({
    url:'/sales/openCashier',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(d){
      spinnerStop();
      if(!d.ok) {
        alert('Unable to print. Please check the status of Printer.');
        console.log(d.msg);
      }
      else {
        getCashSumByKasse();
      }
    }
  });
}

function getSalesSummary() {
  $.get('sales/getSalesSummary/?frDate='+ $('#sales-summary-from-date').val() + '&toDate=' + $('#sales-summary-to-date').val() + '&kasseId=' + $('#sales-summary-kasse').val(), function(d) {
    setSalesSummary(d.data);
  });
}

function getCategories() {
  $.get('item/getCategories', function(d) {
    setCategoryTable(d.categories);
  });
}

function deleteCategory(categoryId) {
  if(confirm('Do you wish to delete this category?')) {
    spinnerPlay();
    $.ajax({
      url: '/item/deleteCategory',
      type: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: {"categoryId": categoryId},
      success: function(d){
        spinnerStop();
        if(d.ok) {
          alert('Category is deleted.');
          getCategories();
        }
        else {
          alert(d.msg);
          console.log(d.err);
        }
      }
    });
  }
}

function editCategories() {
  var data = $.map($('#category-area').children('tr'), function(row){
    return {
      "id": $(row).attr('category-id'),
      "code": $(row).children('td').eq(0).text(),
      "name": $(row).children('td').eq(1).text(),
      "tab_order": Number($(row).children('td').eq(2).text())
    };
  });
  var result = validateCategory(data);
  if(!result.ok) {
    alert(result.row + 'row(s), ' + result.msg);
    return false;
  }
  spinnerPlay();
  $.ajax({
    url: '/item/updateCategories',
    type: 'PUT',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: {categories: data},
    success: function(d){
      spinnerStop();
      if(d.ok) {
        alert('Categories are updated.');
        getCategories();
      }
      else {
        alert(d.msg);
        console.log(d.err);
      }
    }
  });
}

function getCashSumByKasse() {
  $.get('sales/getCashSumByKasse', function(d) {
    setCashSum(d);
  });
}

function dailyClosing() {
  if(confirm('Do you wish to proceed closing?\nPlease make sure that cash in the box is the same with cash sum.')) {
    $.get('sales/dailyClosing', function(d) {
      if(d.ok) {
        alert('Feuerabend :*)');
      }
      else {
        alert('Pleaese contact to IT team.');
        console.log(d.msg);
      }
    });
  }
}


function createVoucher(data) {
  spinnerPlay();
  $.ajax({
    url:'/sales/createVoucher',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: data,
    success: function(d){
      spinnerStop();
      if(d.ok) {
        closeVoucherPopup();
        alert('Voucher is created.');
        if(!d.printerOK.ok) {
          alert('Printer has a problem. Please reprint it. Later');
        }
      }
      else {
        alert('Duplicate Voucher Code.');
        console.log(d.msg);
      }
    }
  });
}

function checkVoucherByCode(voucherCode) {
  $('#voucher-search-amount').val('');
  $.get("sales/checkVoucherByCode/"+voucherCode, function(d) {
    if(d.ok) {
      if($('#paymentPopup').is(':visible')) {
        paymentObj.voucher.code = d.voucher[0].code;
        paymentObj.voucher.max = ePrice(d.voucher[0].checkSum);
        $('#payment-selection-voucher-checksum').text(eEuro(paymentObj.voucher.max) + ' €');
      }
      if($('#voucherSearchPopup').is(':visible')) {
        $('#voucher-search-amount').val(eEuro(d.voucher[0].checkSum));
      }
    }
    else {
      if($('#paymentPopup').is(':visible')) {
        paymentObj.voucher.code = '';
        paymentObj.voucher.max = 0;
        $('#payment-selection-voucher-checksum').text('Invalid Code.');
      }
      if($('#voucherSearchPopup').is(':visible')) {
        alert('Invalid Code.');
      }
    }
  });
}

function refundVoucher(voucherCode, amount) {
  var restAmount = eNumber(amount);
  if(restAmount === '' || restAmount === 0) {
    alert('Please check Voucher Code and the rest amount');
    return false;
  }

  spinnerPlay();
  $.ajax({
    url:'/sales/refundVoucher',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data: {"code":voucherCode, "amount":restAmount},
    success: function(d){
      spinnerStop();
      if(d.ok) {
        alert('Voucher refunds success.');
        if(!d.printerOK.ok) {
          alert('Printer has a problem. Please reprint it. Later');
        }
        closeVoucherSearchPopup();
      }
      else {
        alert('Voucher refunds failed. Did you change the Voucher code?');
        console.log(d.msg);
      }
    }
  });
}
