<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];

$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if($_SESSION['usertype'] == 'admin'){
     echo"";
}else{
    echo("<script>alert('You are not allowed to access this webpage!')</script>");
    echo("<script>window.location = '../index.php';</script>");
}

if(isset($_POST['add_confirm']))
{
    $emp_id = $_POST['emp_id'];
    $name = $_POST['emp_fullname'];
    $da = 0;
    $add_query = "INSERT INTO employees (emp_id,emp_fullname,emp_da) VALUES ('$emp_id','$name','$da')";
    $result = mysqli_query($con, $add_query);
}
?>
<html>
<head>
</head>


<body>


<form method="POST">
    <label>Employee ID: </label>
    <input type="text" name="emp_id" required><br>
    <label>Employee Name: </label>
    <input type="text" name="emp_fullname" required><br>
    <input type="submit" name="add_confirm" value="Submit"/>
  </form>

</body>
</html>
