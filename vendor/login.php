<?php
session_start();
include('includes/connection.php');
include('includes/function.php');

if(isset($_POST['signin']))
{
    //For the inputs
    $login_name = $_POST['username_form'];
    $login_password = $_POST['password_form'];

    if(!empty($login_name)&&($login_password))
    {
        $query = "SELECT * FROM accounts where username ='$login_name' limit 1";
        $result = mysqli_query($con, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);
                $decoded_password = base64_decode($user_data['password']);
                $account_status = $user_data['account_status'];

                if($decoded_password === $login_password)
                {
                    $_SESSION['id'] = $user_data['id'];
                    $_SESSION['name'] = $user_data['name'];

                    if($account_status == "ACTIVE")
                    {
                      //Check if they are a normal user
                      if($user_data['usertype'] == "user")
                      {
                          header("location: user-files/user_page.php");
                          die;
                      }
                      //check if they are an admin
                      elseif($user_data['usertype'] == "admin")
                      {
                          header("location: admin-files/admin_page.php");
                          die;
                      }
                      else
                      {
                          die;
                      }
                    }
                    else
                    {
                      echo'<script>alert("Your account has been deactivated!")</script>';
                    }


                }
                else
                {
                  echo'<script>alert("Wrong username or password")</script>'; //If the password or username is incorrect
                }
            }
        }

    }else
    {
        echo'<script>alert("Error exception handling!")</script>';//If the entire logic breaks
    }

}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">



    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Style -->
    <link rel="stylesheet" href="assets/css/login1.css">

    <title> Login Page </title>
  </head>
  <body>


  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('img/bg_1.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <div class="mb-4">

              <p class="mb-4"> <img src="assets/img/logo1.png" style="height:2.5rem;"> </p>
            </div>
            <form action="#" method="post">
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username_form" id="username">

              </div>
              <div class="form-group last mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password_form" id="password">

              </div>

              <div class="d-flex mb-5 align-items-center">
              <span><a href="index.php" class="forgot-pass">Back</a></span>

                <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
              </div>

              <input type="submit" name="signin" value="Log In" class="btn btn-block btn-primary">




            </form>
          </div>
        </div>
      </div>
    </div>


  </div>

    <script src="login-js/jquery-3.3.1.min.js"></script>
    <script src="login-js/popper.min.js"></script>
    <script src="login-js/bootstrap.min.js"></script>
    <script src="login-js/main.js"></script>



  </body>

</html>
