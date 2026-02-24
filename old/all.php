<?php
session_start();
require('connect.php');

// Check if user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header('location: login.php');
    exit;
}

$sql = "SELECT * FROM users";

$users = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashbord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container py-5 border-green">
<?php
      // capture flash message and then clear it so it can be used in JS
      $flash = null;
      if(isset($_SESSION['msg'])){
          $flash = $_SESSION['msg'];
          unset($_SESSION['msg']);
      }
?>
<div class="col-12 py-2">
<div class="row row-cols-cols-3">
<div class="col"><h3><span class="badge text-dark">System Users</span></h3></div>
<div class="row " style="display: flex; align-items: center; justify-content: center;">
<div class="col"><button type="button" class="btn btn-danger float-center" onclick="massDel()">Mass Delete</button></div>
<div class="col"><a  href="add.php"><button class="btn btn-success btn-sm float-right">+ Add User</button></a></div>
<div class="col"><a href="logout.php"><button class="btn btn-warning btn-sm float-right">Logout</button></a></div>
</div>
</div>
</div>
<div class="table-responsive">
<table class="table table-striped py-2 table-hover">
  <div class="col"><caption><span style="color: blue; font-weight: bold;"><?= $users->num_rows; ?> users</span></caption></div>
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Edit</th>
      <th scope="col">Delete</th>
      <th scope="col">Select</th>


    </tr>
  </thead>
  <tbody>
  <?php foreach($users as $index => $data){ ?>

    <tr>
      <th scope="row"><?= $index + 1; ?></th>
      <td><?= $data['name']; ?></td>
      <td><?= $data['email']; ?></td>
      <td><a href="edit.php?id=<?=$data['id']?>"><button type="button" class="btn btn-primary">Edit</button></a></td>
      <td><button type="button" class="btn btn-danger" onclick='delUser(<?=json_encode($data);?>)'>Delete</button></td>
      <td onclick="select(<?=$data['id']?>)"><input type="checkbox" class="form-control select_user"/></td>
      

    </tr>
    <?php } ?>
  </tbody>
</table>

</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<script>
function delUser(row){
  if(confirm('are you sure you want to delete ' + row.name + ' ?')){
    location.href = 'delete.php?id='+ row.id
  }
}
var selected = []

function select(id){
  let sel_ids = selected.includes(id)
if(sel_ids){
  let index = selected.findIndex(s => id)
  selected.splice(index, 1) 
}else{
  let s = selected.push(id)
  console.log(s)
 
}
}

function massDel(){
  if(selected.length == 0){
    alert('No user selected')
  }  else {
    if(confirm('Are you sure you want to delete selected users?')){
      let ids = selected.join(',')
      location.href = 'delete.php?many=1&id='+ ids
    }
  }
}


</script>

<?php if(!empty($flash)): ?>
<script>
  swal({
    title: <?= json_encode($flash['success'] ? 'Success' : 'Error') ?>,
    text: <?= json_encode($flash['message']) ?>,
    icon: <?= json_encode($flash['success'] ? 'success' : 'error') ?>,
    timer: 4000,
    buttons: false
  });
</script>
<?php endif; ?>

</body>
</html>