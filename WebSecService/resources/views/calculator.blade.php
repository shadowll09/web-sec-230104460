@extends('layouts.master')
@section('title', 'Calculator')
@section('content')
<div class="card m-4 col-sm-6">
  <div class="card-header">Simple Calculator</div>
  <div class="card-body">
    <form id="calculator-form">
      <div class="mb-3">
        <label for="number1" class="form-label">Number 1</label>
        <input type="number" class="form-control" id="number1" required>
      </div>
      <div class="mb-3">
        <label for="number2" class="form-label">Number 2</label>
        <input type="number" class="form-control" id="number2" required>
      </div>
      <div class="mb-3">
        <label for="operation" class="form-label">Operation</label>
        <select class="form-select" id="operation" required>
          <option value="add">Add</option>
          <option value="subtract">Subtract</option>
          <option value="multiply">Multiply</option>
          <option value="divide">Divide</option>
        </select>
      </div>
      <button type="button" class="btn btn-primary" onclick="calculate()">Calculate</button>
    </form>
    <div class="mt-3">
      <h4>Result: <span id="result"></span></h4>
    </div>
  </div>
</div>

<script>
  function calculate() {
    const number1 = parseFloat(document.getElementById('number1').value);
    const number2 = parseFloat(document.getElementById('number2').value);
    const operation = document.getElementById('operation').value;
    let result;

    switch (operation) {
      case 'add':
        result = number1 + number2;
        break;
      case 'subtract':
        result = number1 - number2;
        break;
      case 'multiply':
        result = number1 * number2;
        break;
      case 'divide':
        result = number1 / number2;
        break;
      default:
        result = 'Invalid operation';
    }

    document.getElementById('result').innerText = result;
  }
</script>
@endsection
