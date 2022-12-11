<?php
include('includes/connection.php');

$reset_id = $_GET['user_id'];

$token = md5($reset_id).rand(10,9999);
$expFormat = mktime(
date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
$expDate = date("Y-m-d H:i:s",$expFormat);
$update_reset_Query = "UPDATE `accounts` SET `reset_token` = '$token', `exp_date` = '$expDate' WHERE `id` = '$reset_id'";
$updateResult = mysqli_query($con, $update_reset_Query);

$account_id = "";
$get_account_id_query = "SELECT * FROM accounts WHERE `id` = '$reset_id'";
$get_account_id_result = mysqli_query($con, $get_account_id_query);
if($get_account_id_result)
{
  while($row = mysqli_fetch_assoc($get_account_id_result))
  {
    $account_id = $row['account_id'];
  }
}

$get_emp_details_query = "SELECT * FROM employees WHERE `account_id` = '$account_id'";
$get_emp_details_result = mysqli_query($con, $get_emp_details_query);
if($get_emp_details_result)
{
  while($row = mysqli_fetch_assoc($get_emp_details_result))
  {
    $userEmail = $row['emp_email'];
    $emp_name = $row['emp_fullname'];
  }
  $link = "https://performancetracker.online/reset_password.php?token=$token";
  $fromEmail = "no-reply@performancetracker.online";
  $toEmail = $userEmail;
  $subjectEmail = "Reset Password Notice";
  $headersEmail = "From: " . $fromEmail;
  $messageEmail = "Greetings! " .$emp_name. "\r\n\r\n" . "Hi ".$emp_name." Please click the link to reset your password. You only have until tomorrow before the link expires" . "\r\n\r\n" . $link;

    if(mail($toEmail,$subjectEmail,$messageEmail, $headersEmail)) {
     echo "<script>
     alert('Please check your email for the verification process of password reset.');
     window.location.href='login.php';
     </script>";
  } else {
     echo "<script>
     alert('INVALID EMAIL ADDRESS!');
     window.location.href='forgot_password.php';
     </script>";
  }
}
?>
