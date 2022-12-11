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

if ($_SESSION['usertype'] == 'admin') {
  echo "";
} else {
  echo ("<script>alert('You are not allowed to access this webpage!')</script>");
  echo ("<script>window.location = '../index.php';</script>");
}

$_GET['post'];
$emp_id = $_GET['post'];

$query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
$result = $result = mysqli_query($con, $query);

if ($result) {
  if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_array($result)) {
      $emp_name = $row['emp_fullname'];
      $emp_contact = $row['emp_contact'];
      $emp_email = $row['emp_email'];
      $account_id = $row['account_id'];
    }
  }
}

$query = "SELECT * FROM accounts WHERE account_id = '$account_id'";
$result = $result = mysqli_query($con, $query);

if ($result) {
  if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_array($result)) {
      $username = $row['username'];
      $password = $row['password'];
    }
  }
}

if (isset($_POST['update_confirm'])) {
  if ($_FILES['image']['error'] == 4) {
  } else {
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];
    $tmpName = $_FILES['image']['tmp_name'];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if (!in_array($imageExtension, $validImageExtension)) {
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    } else if ($fileSize > 1000000) {
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    } else {
      $newImageName = uniqid();
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, '../img/' . $newImageName);
      $query = "UPDATE accounts SET picture='$newImageName' WHERE account_id='$account_id'";
      mysqli_query($con, $query);
    }
  }

  $emp_name = $_POST['emp_fullname'];
  $emp_contact = $_POST['emp_contact'];
  $emp_email = $_POST['emp_email'];
  $username = $_POST['username'];

  $update_query = "UPDATE employees SET emp_fullname='$emp_name', emp_contact='$emp_contact', emp_email='$emp_email' WHERE emp_id='$emp_id'";
  $update_Result = mysqli_query($con, $update_query);

  $account_query = "UPDATE accounts SET username='$username', name='$emp_name' WHERE account_id='$account_id'";
  $account_Result = mysqli_query($con, $account_query);
  echo '<script>alert("Employee Data Updated")</script>';
  echo ("<script>window.location = 'manage_emp.php';</script>");
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
    $(document).ready(function() {
      $('#emp_table').DataTable();
    });
  </script>


  <title> User </title>

</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between pull-right">
            <h6 class="m-0 font-weight-bold text-primary"> Employee Information </h6>
          </div>
          <div class="card-body">

            <form method="POST" enctype="multipart/form-data" enctype="multipart/form-data">
              <div class="form-group">

                <label>Employee Name: </label>
                <input class="form-control form-control-sm" type="text" name="emp_fullname" value="<?php echo $emp_name ?>" required><br>
                <label>Employee email address: </label>
                <input class="form-control form-control-sm" type="text" name="emp_email" value="<?php echo $emp_email ?>" required><br>
                <label>Employee Contact Number: </label>
                <input class="form-control form-control-sm" type="number" name="emp_contact" value="<?php echo $emp_contact ?>" required><br>
                <label>Account username: </label>
                <input class="form-control form-control-sm" type="text" name="username" value="<?php echo $username ?>" required><br>
                <label for="inputGroupFile01" class="form-label"> Upload Image</label>
                <input type="file" class="form-control" id="inputGroupFile01" name='image' id='image' accept=".jpg, .jpeg, .png" value="">
                <br>



              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="window.location.href = 'manage_emp.php'">Close</button>
                <button class="btn btn-success" type="submit" name="update_confirm"> Save </button>
              </div>
            </form>


          </div>
        </div>
      </div>
    </div>
</body>

<?php include '../includes/script.php' ?>

</html>