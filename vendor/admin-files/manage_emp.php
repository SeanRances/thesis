<!DOCTYPE html>
<html lang="en">
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
?>



<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js">
  </script>

  <script>
    $(document).ready(function () {
      $('#emp_table').DataTable();
    });
  </script>


  <title> User </title>

</head>

<body>

  <?php include_once '../includes/admin_navbar.php' ?>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between pull-right">
            <h6 class="m-0 font-weight-bold text-primary"> Employee List </h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="emp_table" class="table table-hover table-striped table-bordered dataTable dtr-inline"
                aria-describedby="example_info" width="100%" cellspacing="0" role="grid">
                <thead>
                  <tr class="bg-light">
                    <th class="border-top-0"> Name </th>
                    <th class="border-top-0"> Email </th>
                    <th class="border-top-0"> Contact Number </th>
                    <th class="border-top-0"> Action </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                          $query = "SELECT * FROM employees";
                          $result = $result = mysqli_query($con,$query);

                          if($result)
                          {
                            if(mysqli_num_rows($result))
                            {
                              while($row = mysqli_fetch_array($result))
                              {
                                 ?>
                  <tr>
                    <td> <?php echo $row['emp_fullname'] ?> </td>
                    <td> <?php echo $row['emp_email']?> </td>
                    <td> <?php echo $row['emp_contact']?> </td>
                    <td>
                      <?php
                                    echo "<a class='btn btn-secondary' href='manage_emp_view.php?post=" . $row['emp_id'] . "'><span class='fa-solid fa-eye'></span></a>
                                    <a class='btn btn-danger' href='manage_emp_delete.php?post=" . $row['emp_id'] . "'><span class='fa-solid fa-trash'></span></a>
                                    <a class='btn btn-success'href='manage_emp_update.php?post=" . $row['emp_id'] . "'><span class='fa-solid fa-user-pen'></span></a>"; ?>
                    </td>
                    <?php

                              }
                            }
                          }
                       ?>

                </tbody>
              </table>

              <br>
              <input type="submit" value="Add Employee" class="d-none d-sm-inline-block btn btn-sm btn-info mr-2"
                name="add_emp" data-toggle="modal" data-target="#add_emp" />

              <!-- modal for add employee -->

              <div class="modal fade" role="dialog" id="add_emp" tabindex="-1" aria-labelledby="add_emp"
                aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="add_emp"> Add Employee </h5>
                    </div>
                    <div class="modal-body">
                      <div class="col">
                        <div class="form-group">
                          <div class="col">
                            <form method="POST">
                              <div class="form-group">

                                <label>Employee Name: </label>
                                <input class="form-control form-control-sm" type="text" name="emp_fullname"
                                  required><br>
                                <label>Employee email address: </label>
                                <input class="form-control form-control-sm" type="text" name="emp_email" required><br>
                                <label>Employee Contact Number: </label>
                                <input class="form-control form-control-sm" type="number" name="emp_contact"
                                  required><br>
                                <label>Account username: </label>
                                <input class="form-control form-control-sm" type="text" name="username" required><br>
                                <label>Account password: </label>
                                <input class="form-control form-control-sm" type="password" name="password" required><br>



                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit" name="add_confirm"> Submit </button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>


              <?php
                              if(isset($_POST['add_confirm']))
                              {
                                  $name1 = "EMP_";
                                  $name2 = time();
                                  $acc1 = "ACC_";
                                  $acc2 = time();
                                  $emp_id = $name1 . $name2;
                                  $account_id = $acc1 . $acc2;
                                  $name = $_POST['emp_fullname'];
                                  $email = $_POST['emp_email'];
                                  $contact = $_POST['emp_contact'];
                                  $username = $_POST['username'];
                                  $password = $_POST['password'];
                                  $encoded_password = base64_encode($password);
                                  $da = 0;
                                  $usertype = "user";
                                  $account_status = "ACTIVE";
                                  $picture = "new_user_photo.jpg";

                                  $query = "INSERT INTO accounts (account_id,username,password,name,usertype,account_status,picture) VALUES ('$account_id','$username','$encoded_password','$name','$usertype','$account_status','$picture')";
                                  $result = mysqli_query($con, $query);

                                  $add_query = "INSERT INTO employees (account_id,emp_id,emp_fullname,emp_da,emp_email,emp_contact) VALUES ('$account_id','$emp_id','$name','$da','$email','$contact')";
                                  $result = mysqli_query($con, $add_query);
                                  echo'<script>alert("Employee Data Added")</script>';
                                  echo("<script>window.location = 'manage_emp.php';</script>");
                              }
                              ?>

            </div>


          </div>
        </div>
      </div>
    </div>






</body>

<?php include '../includes/script.php' ?>

</html>
