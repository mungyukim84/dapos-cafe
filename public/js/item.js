function openItemPopup (categoryId, item = null) {
  removeFocusFromBarcode();
  initItemPopup();
  if(item != null) {
    setItemData(item);
  }
  $('#item-category').val(categoryId);
  $('#disable-bg').show();
  $('#itemPopup').show();
  $('#item-name').focus();
}

function closeItemPopup() {
  $('#disable-bg').hide();
  $('#itemPopup').hide();
  focusOnBarcode();
}

function initItemPopup() {
  $('#item-id').val('');
  $('#item-name').val('');
  $('#item-category').val('');
  $('#item-production-cost').val('');
  $('#item-price').val('');
  $('#item-tax-in').val(0.19);
  $('#item-tax-out').val(0.19);
  $('#item-recycle').val('');
  $('#item-ean').val('');
  $('#item-ean2').val('');
  $('#item-ean3').val('');
  $('#item-description').val('');
}

function setItemData(item) {
  $('#item-id').val(item.id);
  $('#item-name').val(item.name);
//  $('#item-category').val(item.id);
  $('#item-production-cost').val(item.production_cost);
  $('#item-price').val(item.sales_price);
  $('#item-tax-in').val(item.tax_rate_in);
  $('#item-tax-out').val(item.tax_rate_out);
  $('#item-recycle').val(item.recycle_cost);
  $('#item-ean').val(item.ean);
  $('#item-ean2').val(item.ean_2);
  $('#item-ean3').val(item.ean_3);
  $('#item-description').val(item.description);
}
