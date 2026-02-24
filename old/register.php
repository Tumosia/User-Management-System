<?php
session_start();
require('connect.php');

$error = '';

if(isset($_POST['submit'])){
    $name = addslashes($_POST['name']);
    $email = addslashes($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $check_sql = "SELECT * FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_sql);

        if($check_result->num_rows > 0){
            $error = 'Email already registered';
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            $result = $conn->query($sql);

            if($result === TRUE){
                $_SESSION['msg'] = array(
                    'success' => true,
                    'message' => 'Registration successful! Please login.'
                );
                header('location: login.php');
                exit;
            } else {
                $error = 'Registration failed';
            }
        }
    } catch (Exception $ex) {
        $error = 'Registration error';
        file_put_contents('error.txt', $ex);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
  <div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
      <div class="loginbackground box-background--white padding-top--64">
        <div class="loginbackground-gridContainer">
          <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
            <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
            </div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
            <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
            <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
            <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
            <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
            <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
            <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
            <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
            <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
          </div>
        </div>
      </div>
      <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
          <h1><a href="http://robisearch.com/" rel="dofollow">Robisearch</a></h1>
        </div>
        <div class="formbg-outer">
          <div class="formbg">
            <div class="formbg-inner padding-horizontal--48">
            <h4><span class="padding-bottom--15" style="text-align: center; display: block;">Register</span><h4>
            
            
            <?php if(!empty($error)): ?>
            <script>
              swal({
                title: 'Registration Error',
                text: <?= json_encode($error) ?>,
                icon: 'error',
                button: true
              });
            </script>
            <?php endif; ?>

            <form id="stripe-login" action="register.php" method="POST">
              <div class="field padding-bottom--24">
                  <label for="name">Name</label>
                  <input type="text" name="name" required>
                </div>
              <div class="field padding-bottom--24">
                  <label for="email">Email</label>
                  <input type="email" name="email" required>
                </div>
                <div class="field padding-bottom--24">
                  <label for="password">Password</label>
                  <input type="password" name="password" required>
                </div>
                
                <div class="field padding-bottom--24">
                  <input type="submit" name="submit" value="Register">
                </div>
              </form>
            </div>
          </div>
          <div class="footer-link padding-top--24">
            <span>Already have an account? <a href="login.php">Login</a></span>
            <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
              <span><a href="#">© 2026</a></span>
              <span><a href="#">Contact</a></span>
              <span><a href="#">Privacy & terms</a></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
?>
