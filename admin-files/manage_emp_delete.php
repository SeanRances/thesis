<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
$user_data = check_login($con);
$name =  $_SESSION['name'];

$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if($_SESSION['usertype'] == 'admin'){
     echo"";
}else{
    echo("<script>alert('You are not allowed to access this webpage!')</script>");
    echo("<script>window.location = '../index.php';</script>");
}

$_GET['post'];

$emp_id = $_GET['post'];

$query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
$result = mysqli_query($con,$query);

if($result)
{
  if(mysqli_num_rows($result))
  {
    while($row = mysqli_fetch_array($result))
    {
      $account_id = $row['account_id'];
    }
  }
}

$account_query = "SELECT * FROM accounts WHERE account_id = '$account_id'";
$account_result = mysqli_query($con,$account_query);
if($account_result)
{
  if(mysqli_num_rows($account_result))
  {
    while($row = mysqli_fetch_array($account_result))
    {
      $account_status = $row['account_status'];
    }
  }
}

if($account_status == "ACTIVE")
{
  $new_status = "DEACTIVATED";
  $update_query = "UPDATE accounts SET account_status='$new_status' WHERE account_id='$account_id'";
  $update_Result = mysqli_query($con, $update_query);
  echo'<script>alert("Employee Deactivated")</script>';
}
else
{
  $new_status = "ACTIVE";
  $update_query = "UPDATE accounts SET account_status='$new_status' WHERE account_id='$account_id'";
  $update_Result = mysqli_query($con, $update_query);
  echo'<script>alert("Employee Activated")</script>';
}


echo("<script>window.location = 'manage_emp.php';</script>");

 ?>
