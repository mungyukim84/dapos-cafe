<div class="dapos-calculator-sm" style="left:25%" id="CalculatorPopup">
  <div class="col-md-12">
    <button type="button" class="close" aria-label="Close" onclick="closeCalculator()">
      <span aria-hidden="true" style="font">&times;</span>
    </button>
  </div>
  <div class="col-md-12">
    <div class="block">
      <input type="text" class="popup-input-number input-lg" id="calculator-value" style="width: 300px;" readonly>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(1)">1</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(2)">2</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(3)">3</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(4)">4</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(5)">5</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(6)">6</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(7)">7</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(8)">8</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(9)">9</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue('del')">Del</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue(0)">0</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorValue('clear')">C</button>
    </div>
    <div class="block-bottom">
      <div class="text-center">
        <button class="btn btn-lg btn-primary" id="insert-index-val" style="width:300px">Enter</button>
      </div>
    </div>
  </div>
</div>
