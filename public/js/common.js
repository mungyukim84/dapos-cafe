// init Page & payment
function clearAll() {
  if(originalReceiptNum != '') {
    if(!confirm('Repayment is proceeding. Do you wish to discard this process?')) {
      return false;
    }
  }

  var trStr = [];
  basketObj = {};
  listPrice = 0;
  netPrice = 0;
  discountRate = 0;
  buyGroup = 0;
  originalReceiptNum = '';

  for(var i=0;i<8;i++) {
    trStr.push('<tr><td class="dptd" colspan="4" width="560px"></td></tr>');
  }

  $('#main-sum-subtotal').text('0,00');
  $('#main-sum-tax').text('0,00');
  $('#main-sum-total').text('0,00');

  $('#main-data-area').html("");
  $('#main-data-area').append(trStr.join(''));
}

function eEuro(x) {
  var formatter = new Intl.NumberFormat('de-DE', {
    minimumFractionDigits: 2,
  });
  return formatter.format(x);
}

function eNumber(x) {
  return Number(x.replace(/[.]/gi, '').replace(',', '.'));
}

function eRound(value, decimals) {
  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function ePrice(v) {
  return eRound(eRound(Number(v), 4), 2);
}

function focusOnBarcode() {
  $("#barcode").focus().bind('blur', function() {
      $(this).focus();
  });
  $("html").click(function() {
      $("#barcode").val($("#barcode").val()).focus();
  });
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      getItemWithBarcode($('#barcode').val());
//      alert($('#barcode').val());
      $('#barcode').val('');
      $('#barcode').focus();
    }
  });
  $('#barcode').focus();
}

function checkAll(el) {
  var chkClass = $(el).attr('data-cl');
  $("."+chkClass).prop('checked',el.checked)
}

function removeFocusFromBarcode() {
  $("#barcode").focus().off('blur');
  $("html").off("click");
  $(window).off("keydown");
}

function spinnerPlay() {
  $('.dapos-loading').preloader({
    text:'Please wait...',
    zIndex:'10'
  });
}

function spinnerStop() {
  $('.dapos-loading').preloader('remove');
}

function checkAll(el) {
  var chkClass = $(el).attr('data-cl');
  $("."+chkClass).prop('checked',el.checked)
}

function createBasketKey(itemId) {
  return 'cafe' + itemId;
}

function getNetto(brutto, tax) {
  return ePrice(Number(brutto) / (1 + Number(tax)));
}

function getTax(brutto, tax) {
  return Number(brutto) - getNetto(brutto, tax);
}

function getDiscountedSalesPrice(brutto, dc) {
  return ePrice(Number(brutto) * (1 - Number(dc)));
}
