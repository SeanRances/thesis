<?php
session_start();
include('includes/connection.php');
include('includes/function.php');

if (isset($_POST['signin'])) {
  //For the inputs
  $login_name = $_POST['username_form'];
  $login_password = $_POST['password_form'];

  if (!empty($login_name) && ($login_password)) {
    $query = "SELECT * FROM accounts where username ='$login_name' limit 1";
    $result = mysqli_query($con, $query);

    if ($result) {
      if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $decoded_password = base64_decode($user_data['password']);
        $account_status = $user_data['account_status'];

        if ($decoded_password === $login_password) {
          $_SESSION['id'] = $user_data['id'];
          $_SESSION['name'] = $user_data['name'];

          if ($account_status == "ACTIVE") {
            //Check if they are a normal user
            if ($user_data['usertype'] == "user") {
              header("location: user-files/user_page.php");
              die;
            }
            //check if they are an admin
            elseif ($user_data['usertype'] == "admin") {
              header("location: admin-files/admin_page.php");
              die;
            } else {
              die;
            }
          } else {
            echo '<script>alert("Your account has been deactivated!")</script>';
          }
        } else {
          echo '<script>alert("Wrong username or password")</script>'; //If the password or username is incorrect
        }
      }
    }
  } else {
    echo '<script>alert("Error exception handling!")</script>'; //If the entire logic breaks
  }
}

?>


<!doctype html>
<html lang="en">

<head>
  <title> Login </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body class="form-v5">
  <div class="page-content">
    <div class="form-v5-content shadow">
      <form class="form-detail text-center" method="post">
        <h2>
          <a href="index.php">
            <p class="mb-4"> <img src="assets/img/logo.png" style="height:2.5rem;"> </p>
          </a>
        </h2>
        <!-- <div class="wrapper">
          <p class="p-text">Enter the username associated with your account and we'll send you a link to reset your password.</p>
        </div> -->
        <div class="form-row form-floating">
          <input type="text" name="username_form" id="floatingInput" class="form-control input-text" placeholder="Username" required>
          <label for="floatingInput">
            Username
          </label>
        </div>
        <div class="form-row form-floating">
          <input type="password" name="password_form" id="floatingPassword" class="form-control input-text" placeholder="Password" required>
          <label for="floatingPassword">
            Password
          </label>
        </div>
        <div class="form-row-last">
          <input type="submit" name="signin" value="Log In" class="register btn btn-block btn-primary">
          <a href="forgot_password.php" class="return">
            <p class="mt-2"> Forgot Password? </p>
          </a>
        </div>
      </form>
    </div>
  </div>
</body>

</html>