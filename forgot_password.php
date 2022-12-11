<?php
session_start();
include('includes/connection.php');
include('includes/head.php');
include('includes/function.php');

if (isset($_POST['reset_password'])) {
  $username = $_POST['username'];
  $reset_id = "";

  $valid_username_checker_query = "SELECT * FROM accounts WHERE username = '$username'";
  $valid_username_checker_result = mysqli_query($con, $valid_username_checker_query);
  if ($valid_username_checker_result) {
    if (mysqli_num_rows($valid_username_checker_result) > 0) {
      while ($row = mysqli_fetch_array($valid_username_checker_result)) {
        $reset_id = $row['id'];
        header("location: forgot_password_email.php?user_id=$reset_id");
      }
    } else {
      echo '<script>alert("Email address is not registered in the system.")</script>';
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="assets/css/forgot-password.css" />
</head>

<body class="form-v5">
  <div class="page-content">
    <div class="form-v5-content shadow">
      <form class="form-detail" method="post">
        <h2> Reset your password </h2>
        <div class="wrapper">
          <p class="p-text">Enter the username associated with your account and we'll send you a link to reset your password.</p>
        </div>
        <div class="form-row form-floating">
          <input type="text" name="username" id="floatingInput" class="form-control input-text" placeholder="Username" required>
          <label for="floatingInput">
            Username
          </label>
        </div>
        <div class="form-row-last">
          <input type="submit" name="reset_password" class="register" value="Continue">
          <a href="login.php" class="return">
            <p class="mt-2"> Return to sign in</p>
          </a>
        </div>
      </form>
    </div>
  </div>
</body>

</html>