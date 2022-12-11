<?php
session_start();
include('includes/connection.php');
include('includes/head.php');

$getTOKEN = $_GET['token'];
if (is_null($getTOKEN)) {
  header("location: logout.php");
} else {
  $select_token_query = "SELECT * FROM accounts WHERE reset_token = '$getTOKEN'";
  $select_token_result = mysqli_query($con, $select_token_query);
  $checkDate = date("Y-m-d H:i:s");

  if ($select_token_result) {
    while ($row = mysqli_fetch_assoc($select_token_result)) {
      $expDate = $row['exp_date'];
      $loginID = $row['id'];
      $tokenExist = $row['reset_token'];
    }
    if ($expDate >= $checkDate) {
      " ";
    } elseif ($tokenExist != $getTOKEN) {
      echo "<script>
     alert('Your link has expired, please contact the System Administrator to reset your password');
     window.location.href='logout.php';
     </script>";
    } else {
      echo "<script>
     alert('Your link has expired, please contact the System Administrator to reset your password');
     window.location.href='logout.php';
     </script>";
    }
  }
}

if (isset($_POST['reset_pass'])) {
  $new_pass = $_POST['new_password'];
  $verify_pass = $_POST['verify_password'];
  if ($new_pass == $verify_pass) {
    $encoded_password = base64_encode($new_pass);
    $update_password_query = "UPDATE accounts SET password = '$encoded_password' WHERE `reset_token` = '$getTOKEN'";
    $update_password_result = mysqli_query($con, $update_password_query);
    if ($update_password_result) {
      $removeToken = "UPDATE accounts SET `reset_token` = ' ', `exp_date` = ' ' WHERE `id` = '$loginID'";
      $removeResults = mysqli_query($con, $removeToken);
      echo ("<script>alert('Password update successful!')</script>");
      echo ("<script>window.location = 'login.php';</script>");
    }
  } else {
    echo ("<script>alert('Password does not match! Please try again.')</script>");
    echo ("<script>window.location = 'reset_password.php?token=$getTOKEN';</script>");
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="assets/css/reset_password.css" />
</head>

<body class="form-v5">
  <div class="page-content">
    <div class="form-v5-content shadow">
      <form class="form-detail" method="post">
        <h2> Reset your password </h2>
        <div class="wrapper">
          <p class="p-text">Please type in your new password.</p>
        </div>
        <div class="form-row form-floating">
          <input type="password" name="new_password" id="floatingInput" class="form-control input-text" placeholder="Password" required>
          <label for="floatingInput">
            New Password
          </label>
        </div>
        <div class="form-row form-floating">
          <input type="password" name="verify_password" id="floatingPassword" class="form-control input-text" placeholder="Password" required>
          <label for="floatingPassword">
            Verify Password
          </label>
        </div>
        <div class="form-row-last">
          <input type="submit" name="reset_pass" class="register" value="Continue">
          <a href="login.php" class="return">
            <p class="mt-1"></p>Return to sign in
          </a>
        </div>
      </form>
    </div>
  </div>
</body>

</html>