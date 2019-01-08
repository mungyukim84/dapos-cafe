<div class="dapos-popup-sm" style="left:15%;top:30%;width:700px;height:820px" id="itemPopup">
  <input type="hidden" id="item-id"/>
  <div class="popup-head">
    <span class="popup-head-title" id="popup-item-title">Create Item</span>
  </div>
  <div class="col-md-12">
    <div class="block">
      <label for="item-name" class="fs18 popup-label" style="vertical-align:top">Name: </label>
      <textarea type="text" class="popup-input-text input-lg item-validation-field" style="width:150px;height:150px;font-size:20px;text-align:center;resize: none;" id="item-name" row="5"></textarea>
    </div>
    <div class="block">
      <label for="item-category" class="fs18 popup-label">Category: </label>
      <select class="popup-selectbox input-lg" id="item-category" required>
        @foreach($menus as $menu)
          <option value="{{ $menu->id }}">{{$menu->name}}</option>
        @endforeach
      </select>
    </div>
    <div class="block">
      <label for="item-production-cost" class="fs18 popup-label">Production Cost: </label>
      <input type="number" class="popup-input-text input-lg item-validation-field" style="width:200px;text-align:right" id="item-production-cost"> <span class="fs18"> €</span>
    </div>
    <div class="block">
      <label for="item-price" class="fs18 popup-label">Price (Brutto): </label>
      <input type="number" class="popup-input-text input-lg item-validation-field" style="width:200px;text-align:right" id="item-price"> <span class="fs18"> €</span>
    </div>
    <div class="block">
      <label for="item-tax-in" class="fs18 popup-label">Tax(IN): </label>
      <select class="popup-selectbox input-lg" id="item-tax-in">
        <option value="0.19" selected>19%</option>
        <option value="0.07">7%</option>
        <option value="0">0%</option>
      </select>
    </div>
    <div class="block">
      <label for="item-tax-out" class="fs18 popup-label">Tax(OUT): </label>
      <select class="popup-selectbox input-lg" id="item-tax-out">
        <option value="0.19" selected>19%</option>
        <option value="0.07">7%</option>
        <option value="0">0%</option>
      </select>
    </div>
    <div class="block">
      <label for="item-recycle" class="fs18 popup-label">Pfand: </label>
      <input type="number" class="popup-input-text input-lg" style="width:200px;text-align:right" id="item-recycle"> <span class="fs18"> €</span>
    </div>
    <div class="block">
      <label for="item-ean" class="fs18 popup-label">EAN </label>
      <input type="text" class="popup-input-text input-lg" style="font-size:13px;width:145px;" id="item-ean">
      <input type="text" class="popup-input-text input-lg" style="font-size:13px;margin-left:3px;width:145px;" id="item-ean2">
      <input type="text" class="popup-input-text input-lg" style="font-size:13px;margin-left:3px;width:145px;" id="item-ean3">
    </div>
    <div class="block">
      <label for="item-description" class="fs18 popup-label" style="vertical-align:top">Description: </label>
      <textarea class="popup-input-text" id="item-description" style="resize: none" rows="3"></textarea>
    </div>
    <div class="block-bottom">
      <div class="text-center">
        <button class="btn btn-lg btn-warning" style="width:180px" onclick="closeItemPopup()">CANCEL</button>
        <button class="btn btn-lg btn-success" style="width:180px;margin-left:10px" onclick="saveItem()">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
