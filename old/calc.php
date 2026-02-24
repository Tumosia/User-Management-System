<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>
<div class="container">
<div class="jumbotron">
<input type="text" id="input" class="form-control P-8" placeholder="0"  >
<hr>
<div class="row justify-content-center row-cols-4">
    <div class="col p-4 "><button class="btn btn-primary">CE</button></div>
    <div class="col p-4"><button class="btn btn-danger" onclick="clearAll()">C</button></div>
    <div class="col p-4"><button class="btn btn-danger" onclick="deleteV()">Del</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">/</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">7</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">8</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">9</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">*</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">4</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">5</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">6</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">-</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">1</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">2</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">3</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">+</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">+/-</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">0</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">.</button></div>
    <div class="col p-4"><button class="btn btn-secondary" onclick="addinput(this)">=</button></div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>


<script>
    var sum = 0;
    var results = [];
    var values = [];
    var firstVal = 0;
    var output = 0;
    var input = document.getElementById('input');       
    var operands = ['+','-','*','/'];

function addinput(e){  
  var input = document.getElementById('input');
   
    if(operands.includes(e.innerHTML)){
        let v = input.value.toString();
      if(v.trim().length > 0  ){

        let last_char = v.charAt(v.length - 1);

        if(!operands.includes(last_char)){
            input.value += e.innerHTML;
        } 
      }else{
       console.log('error');
      }

    }else if(e.innerHTML == "="){
      input.value = eval(input.value);

    }else if(Number.isInteger(e.innerHTML)){
        input.value += e.innerHTML;
    }else{
        input.value += e.innerHTML;
    }
    
}
function clearAll(){
    input.value = "";
}

function deleteV(){  
    input.value = input.value.toString().slice(0, -1);
}

</script>

</body>
</html>
