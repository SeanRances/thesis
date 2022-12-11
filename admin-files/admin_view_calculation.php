<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/admin_view_calculation_functions.php';

$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];

$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if ($_SESSION['usertype'] == 'admin') {
  echo "";
} else {
  echo ("<script>alert('You are not allowed to access this webpage!')</script>");
  echo ("<script>window.location = '../index.php';</script>");
}
$holder = $_GET['post'];
$arr = explode("||", $holder);
$type = $arr[0];

$get_user_name_query = "SELECT * FROM employees WHERE emp_id = '$arr[1]'";
$get_user_name_result = mysqli_query($con, $get_user_name_query);

if ($get_user_name_result) {
  if (mysqli_num_rows($get_user_name_result)) {
    while ($row = mysqli_fetch_array($get_user_name_result)) {
      $emp_name = $row['emp_fullname'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title> Performance Calculation </title>
</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>

  <?php
  if ($type == "day") {
    $emp_id = $arr[1];
    $day = $arr[2];
    $month = $arr[3];
    $year = $arr[4];
    display_day($emp_id, $day, $month, $year, $emp_name);
  } elseif ($type == "month") {
    $emp_id = $arr[1];
    $month = $arr[2];
    $year = $arr[3];
    display_month($emp_id, $month, $year, $emp_name);
  } elseif ($type == "year") {
    $emp_id = $arr[1];
    $year = $arr[2];
    display_year($emp_id, $year, $emp_name);
  }
  ?>
</body>
<?php include '../includes/script.php' ?>

</html>