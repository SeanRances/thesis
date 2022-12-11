<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/admin_view_emp_functions.php';

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
$holder = $_GET['post'];
$arr = explode("||", $holder);
$type = $arr[0];

$get_user_name_query = "SELECT * FROM employees WHERE emp_id = '$arr[1]'";
$get_user_name_result = mysqli_query($con,$get_user_name_query);

if($get_user_name_result)
{
  if(mysqli_num_rows($get_user_name_result))
  {
    while($row = mysqli_fetch_array($get_user_name_result))
    {
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

  <title> Performance </title>
</head>
<?php include_once '../includes/admin_navbar.php' ?>
<body>
  <?php
    if($type == "day" || $type == "month")
    {
      ?>
      <div class="container-fluid py-5">
        <div class="row">
          <div class="col-lg">
            <div class="card shadow mb-4">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <?php
                  if($type == "day")
                  {
                    echo $emp_name . "'s performance for " . $arr[3] . " " . $arr[2] . ", " . $arr[4];
                  }
                  elseif($type == "month")
                  {
                    echo $emp_name . "'s performance for " . $arr[2] . " " . $arr[3];
                  }
                 ?>

                <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">

                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0" role="grid">
                    <thead>
                      <tr class="bg-light">
                        <th class="border-top-0"> Name </th>
                        <th class="border-top-0"> QA </th>
                        <th class="border-top-0"> CPH </th>
                        <th class="border-top-0"> ATT </th>
                        <th class="border-top-0"> QA score </th>
                        <th class="border-top-0"> CPH score </th>
                        <th class="border-top-0"> ATT score </th>
                        <th class="border-top-0"> PERF </th>
                        <th class="border-top-0"> Comment </th>
                        <?php
                            if($type == "month")
                            {
                              ?>
                                <th class="border-top-0"> Date </th>
                              <?php
                            }
                         ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if($type == "day")
                        {
                          $emp_id = $arr[1];
                          $day = $arr[2];
                          $month = $arr[3];
                          $year = $arr[4];
                          day_performance_output($emp_id, $day, $month, $year);
                        }
                        elseif($type == "month")
                        {
                          $emp_id = $arr[1];
                          $month = $arr[2];
                          $year = $arr[3];
                          month_performance_output($emp_id, $month, $year);
                        }
                       ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    else
    {
      $emp_id = $arr[1];
      $year = $arr[2];
      year_performance_output($emp_id, $year, $emp_name);
    }
   ?>




</body>
<?php include '../includes/script.php' ?>
</html>
