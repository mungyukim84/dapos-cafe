$(function() {
  $('#barcode').focus();
  focusOnBarcode();
  $('.datepicker').datepicker();
  getCashSumByKasse();
});

var basketObj = {};
var deleteItemObj = {};
var paymentObj = {};
var refundOrderObj = {};
var cancelledItems = [];

var membership = '';
var isPartialPayment = 'N';
var listPrice = 0;
var discountRate = 0;
var tax = 0;
var netPrice = 0;
var discountedPrice = 0;
var takeInOut = '';
var originalReceiptNum = '';
var cashReceived = 0;
var cashAmountRequired = ['1', '2', '3'];
var cardCancellable = '';
var refundAmount = 0;
var partialItems = [];

function menuClicked(item, el) {
  if($('.btn-menu').hasClass('btn-menu-editable')){
    openItemPopup(item.category_id, item);
  }
  else if($('.btn-menu').hasClass('btn-menu-delete')) {
    if($(el).hasClass('btn-menu-delete-ready')) {
      delete deleteItemObj[item.id];
      $(el).removeClass('btn-menu-delete-ready');
    }
    else {
      deleteItemObj[item.id] = item;
      $(el).addClass('btn-menu-delete-ready');
    }
  }
  else {
    addItemToBasket(item);
  }
}

function addItemToBasket(item) {
  // casting key to Str to preserve object order
  var basketKey = createBasketKey(item.id);
  if(typeof basketObj[basketKey] === "undefined"){
    basketObj[basketKey] = {item:item, qty:1};
  }
  else {
    basketObj[basketKey].qty += 1;
  }
  setMainTable();
}

function setMainTable() {
  var trStr = [];
  var subTotal = 0;
  var tax = 0;
  netPrice = 0;
  var basketSize = 0;
  listPrice = 0;

  $.each(basketObj, function(key, obj) {
    trStr.push('<tr>');
    trStr.push('<td class="dptd" style="font-size:16px" width="220">' + obj.item.name + '</td>');

    if(obj.item.isPartialDiscount == 1) {
      trStr.push('<td class="dptd" width="55px" style="text-align:center;"><span style="color:#42f442">' + eRound(obj.item.dc * 100, 0) +' %</span></td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="50"><input type="text" class="popup-sm-qty-input" onclick="openCalculator(\'qty\', \'' + key + '\')" style="width:50px;text-align:center" value="' + obj.qty + '" readonly></td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="70"><span style="text-decoration:line-through">' + eEuro(obj.item.sales_price) + ' €</span><br/>' + eEuro(ePrice(obj.item.sales_price - obj.item.sales_price*obj.item.dc)) + ' €</td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="70"><span style="text-decoration:line-through">' + eEuro(obj.qty*(obj.item.sales_price)) + ' €</span><br/>' + eEuro(obj.qty*ePrice(obj.item.sales_price - obj.item.sales_price*obj.item.dc)) + ' €</td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="120">' +
      '<button class="btn btn-warning" onclick="openCalculatorPartialPayment(\'dc\', \'' + key + '\')">%</button>&nbsp;' +
      '<button class="btn btn-danger" onclick="removeProductOnBasket(\'' + key + '\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>');
      trStr.push('</tr>');

      subTotal += getNetto(ePrice(obj.item.sales_price - obj.item.sales_price*obj.item.dc), obj.item.tax_rate) * obj.qty;
      tax += getTax(ePrice(obj.item.sales_price - obj.item.sales_price*obj.item.dc), obj.item.tax_rate) * obj.qty;
      netPrice += ePrice(obj.item.sales_price - obj.item.sales_price*obj.item.dc) * obj.qty;
    }
    else{
      trStr.push('<td class="dptd" width="55px" style="text-align:center;"><span style="color:#42f442"></span></td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="50"><input type="text" class="popup-sm-qty-input" onclick="openCalculator(\'qty\', \'' + key + '\')" style="width:50px;text-align:center" value="' + obj.qty + '" readonly></td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="70">' + eEuro(obj.item.sales_price) + '</td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="70">' + eEuro(obj.item.sales_price * obj.qty) + '</td>');
      trStr.push('<td class="dptd" style="text-align:center;" width="120">' +
      '<button class="btn btn-warning" onclick="openCalculatorPartialPayment(\'dc\', \'' + key + '\')">%</button>&nbsp;' +
      '<button class="btn btn-danger" onclick="removeProductOnBasket(\'' + key + '\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>');
      trStr.push('</tr>');

      subTotal += getNetto(obj.item.sales_price, obj.item.tax_rate) * obj.qty;
      tax += getTax(obj.item.sales_price, obj.item.tax_rate) * obj.qty;
      netPrice += obj.item.sales_price * obj.qty;
    }
    listPrice += obj.item.sales_price * obj.qty;
    basketSize++;
  });

  if(basketSize < 8) {
    for(var i = 0; i < 8 - basketSize;i++) {
      trStr.push('<tr><td class="dptd" colspan="6" width="585px"></td></tr>');
    }
  }

  setSummary(subTotal, tax, netPrice);
  $('#main-data-area').html("");
  $('#main-data-area').append(trStr.join(''));
}

function setSummary(subTotal, tax, netPrice) {
  netPrice = ePrice(netPrice);
  $('#main-sum-subtotal').text(eEuro(subTotal));
  $('#main-sum-tax').text(eEuro(tax));
  $('#main-sum-total').text(eEuro(netPrice));
}

function removeProductOnBasket(basketKey) {
  delete basketObj[basketKey];
  setMainTable();
}

function changeEditStatus() {
  if(!($.isEmptyObject(basketObj))) {
    alert('Please empty Item List first.');
    return false;
  }

  if($('.btn-menu').hasClass('btn-menu-delete')) {
    alert('Please turn off Delete Mode first');
    return false;
  }

  if($('.btn-menu').hasClass('btn-menu-editable')){
    $('.btn-menu').removeClass('btn-menu-editable');
  }
  else {
    $('.btn-menu').addClass('btn-menu-editable');
  }
}

function changeDeleteStatus() {
  if(!($.isEmptyObject(basketObj))) {
    alert('Please empty Item List first.');
    return false;
  }

  if($('.btn-menu').hasClass('btn-menu-editable')) {
    alert('Please turn off Edit Mode first');
    return false;
  }

  if($('.btn-menu').hasClass('btn-menu-delete')){
    if(!$.isEmptyObject(deleteItemObj)) {
      deleteItems();
    }
    $('.btn-menu').removeClass('btn-menu-delete').removeClass('btn-menu-delete-ready');
  }
  else {
    deleteItemObj = {};
    $('.btn-menu').addClass('btn-menu-delete');
  }
}

function setCashSum(d) {
  $('#nav-cash').text('');
  $('#nav-cash').text(eEuro(d));
}
