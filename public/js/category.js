function openCategoryPopup() {
  removeFocusFromBarcode();
  getCategories();
  $('#disable-bg').show();
  $('#categoryPopup').show();
}

function closeCategoryPopup() {
  location.reload();
}

function addCategory() {
  var trStr = [];
  var randomId = Math.floor(1 + (10000 - 1) * Math.random());
  trStr.push('<tr id="' + randomId + '" category-id="new">');
  trStr.push('<td contenteditable="true" width="20%" style="text-align:center"></td>');
  trStr.push('<td contenteditable="true" width="50%"></td>');
  trStr.push('<td contenteditable="true" width="10%" style="text-align:center"></td>');
  trStr.push('<td width="20%" style="text-align:center"><button class="btn btn-danger btn-sm" style="width:80px" onclick="deleteCategoryRow(' + randomId + ')">Delete</button></td>');
  trStr.push('</tr>');
  $('#category-area').append(trStr.join(''));
}

function setCategoryTable(categories) {
  var trStr = $.map(categories, function(category, idx) {
    var str = '';
    str += '<tr category-id="' + category.id + '">';
    str += '<td contenteditable="true" width="20%" style="text-align:center">' + category.code + '</td>';
    str += '<td contenteditable="true" width="50%">' + category.name + '</td>';
    str += '<td contenteditable="true" width="10%" style="text-align:center">' + category.tab_order + '</td>';
    str += '<td width="20%" style="text-align:center"><button class="btn btn-danger btn-sm" style="width:80px" onclick="deleteCategory(' + category.id + ')">Delete</button></td>';
    str += '</tr>';
    return str;
  });
  $('#category-area').html('');
  $('#category-area').append(trStr.join(''));
}

function deleteCategoryRow(rowId) {
  $('#' + rowId).remove();
}

function validateCategory(categories) {
  var err = {"ok":true};
  var categoryCodeArray = [];
  var tabOrderArray = [];
  $.each(categories, function(idx, category) {
    if(categoryCodeArray.indexOf(category.code) > -1) {
      err = {"ok":false, "row": idx + 1, "msg": "Code can't be duplicated"};
      return false;
    }
    if(category.code == '' || /\s/g.test(category.code)) {
      err = {"ok":false, "row": idx + 1, "msg": "Code can't be NULL and have white space"};
      return false;
    }
    if(categoryCodeArray.indexOf(category.tab_order) > -1) {
      err = {"ok":false, "row": idx + 1, "msg": "Tab Order can't be duplicated"};
      return false;
    }
    if(category.tab_order == '' || !$.isNumeric(category.tab_order)) {
      err = {"ok":false, "row": idx + 1, "msg": "Tab Order should be a number and not NULL."};
      return false;
    }
    categoryCodeArray.push(category.code);
    tabOrderArray.push(category.tab_order);
  });

  return err;
}
