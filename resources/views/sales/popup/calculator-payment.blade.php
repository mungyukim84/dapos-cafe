<div class="dapos-calculator" id="calculatorPaymentPopup">
  <div class="col-md-12">
    <button type="button" class="close" aria-label="Close" onclick="closeCalculatorPayment()">
      <span aria-hidden="true" style="font">&times;</span>
    </button>
  </div>
  <div class="col-md-12">
    <div class="block">
      <input type="text" class="popup-input-number input-lg" id="calculator-payment-value" style="width: 300px;" readonly>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(1)">1</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(2)">2</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(3)">3</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(4)">4</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(5)">5</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(6)">6</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(7)">7</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(8)">8</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(9)">9</button>
    </div>
    <div class="block">
      <label class="fs18 popup-label"></label>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue('del')">Del</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue(0)">0</button>
      <button type="button" class="btn btn-lg btn-default calculator-button" onclick="setCalculatorPValue('.')">.</button>
    </div>
    <div class="block-bottom">
      <div class="text-center">
        <button class="btn btn-xl btn-primary"  id="calculator-payment-enter" style="width:300px">Enter</button>
      </div>
    </div>
  </div>
</div>
