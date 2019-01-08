<div class="dapos-popup" id="categoryPopup" style="width:800px;height:800px;left:50%;top:50%; margin-left: -400px; margin-top: -400px;">
  <div class="col-md-12">
    <div class="popup-title">
      <h3>Category</h3>
    </div>
    <div class="block">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="20%" style="text-align:center;vertical-align:middle">Code</th>
            <th width="50%" style="vertical-align:middle">Name</th>
            <th width="10%" style="text-align:center;vertical-align:middle">Order</th>
            <th width="20%" style="text-align:center;vertical-align:middle"><button class="btn btn-success btn-sm" style="width:80px" onclick="addCategory();">Add</button></th>
          </tr>
        </thead>
        <tbody class="fs18" id="category-area">
        </tbody>
      </table>
    </div>
    <div class="popup-footer">
        <button class="btn btn-lg btn-danger" onclick="closeCategoryPopup()">Close</button>
        <button class="btn btn-lg btn-success" onclick="editCategories()">Save</button>
    </div>
  </div>
</div>
