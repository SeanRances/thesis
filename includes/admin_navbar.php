<?php
$user_data = check_login($con);
$account_id =  $user_data['account_id'];

if (isset($_POST["photo_upload"])) {
  if ($_FILES["image"]["error"] == 4) {
    echo
    "<script> alert('Image Does Not Exist'); </script>";
  } else {
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

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
}

$status = "UNREAD";
$notif_count = 0;
$notification_amount_query = "SELECT * FROM notifications WHERE notif_status = '$status'";
$notification_amount_result = mysqli_query($con, $notification_amount_query);

if($notification_amount_result)
{
  if(mysqli_num_rows($notification_amount_result))
  {
    $notif_count = mysqli_num_rows($notification_amount_result);
  }
  else
  {
    $notif_count = 0;
  }
}


?>

<?php
$query = "SELECT * FROM accounts WHERE account_id='$account_id'";
$result = mysqli_query($con, $query);

if ($result) {
  if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_array($result)) {
      $image = $row['picture'];
    }
  }
}
?>

<style>
  .sticky {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10;
  }

  .notification-ui a:after {
    display: none;
  }

  .notification-ui_icon {
    position: relative;
  }

  .notification-ui_dd {
    padding: 0;
    border-radius: 10px;
    -webkit-box-shadow: 0 5px 20px -3px rgba(0, 0, 0, 0.16);
    box-shadow: 0 5px 20px -3px rgba(0, 0, 0, 0.16);
    border: 0;
    max-width: 400px;
  }

  .notification-ui_dd .notification-ui_dd-header {
    border-bottom: 1px solid #ddd;
    padding: 15px;
  }

  .notification-ui_dd .notification-ui_dd-header h3 {
    margin-bottom: 0;
  }

  .notification-ui_dd .notification-ui_dd-content {
    max-height: 500px;
    overflow: auto;
  }

  .notification-list {
    display: flex;
    justify-content: space-between;
    padding: 20px 0;
    margin: 0 25px;
    border-bottom: 1px solid #ddd;
  }

  .notification-list_detail {
    font-family: "Montserrat", Sans-serif;
    line-height: 1.2;
    font-weight: 400;
  }


  .notification-list .notification-list_img img {
    height: 48px;
    width: 48px;
    border-radius: 50px;
    margin-right: 20px;
  }

  .notification-list .notification-list_detail p {
    margin-bottom: 5px;
    line-height: 1.2;
    color: #f8f9fa;
  }


  .notification-header {
    font-family: 'Open Sans', sans-serif;
    letter-spacing: 2px;
    font-weight: 600;
    font-size: 20px;
  }
</style>

<link rel="stylesheet" href="../assets/css/homepage.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<section>
  <nav class="navbar sticky navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html"> <img src="../assets/img/logo1.png" style="height:2.5rem;"> </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="fa fa-bars"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav m-auto">
          <li class="nav-item <?= ($activePage == 'admin_page') ? 'active' : ''; ?>"><a href="admin_page.php" class="nav-link">Home</a></li>
          <li class="nav-item dropdown <?= ($activePage == 'manage_emp' || $activePage == 'rewards' || $activePage == 'admin_da') ? 'active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Manage </a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="manage_emp.php"> Employee</a>
              <a class="dropdown-item" href="rewards.php"> Rewards </a>
              <a class="dropdown-item" href="admin_da.php"> DA </a>
            </div>
          </li>
          <li class="nav-item dropdown <?= ($activePage == 'admin_performance' || $activePage == 'admin_rankings') ? 'active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Employee </a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="admin_performance.php"> Performance </a>
              <a class="dropdown-item" href="admin_rankings.php"> Rankings </a>
            </div>
          </li>
          <li class="nav-item <?= ($activePage == 'upload_file') ? 'active' : ''; ?>"><a href="upload_file.php" class="nav-link">Upload</a></li>
        </ul>
      </div>
    </div>
    <div class="d-flex justify-content-center">
      <ul class="navbar-nav mr-4">
        <li class="nav-item dropdown">
          <?php
            if($notif_count > 0)
            {
              ?>
                <span class="badge badge-pill badge-danger" style="float:right; margin-top: 17px;"> <?php echo $notif_count; ?> </span> <!-- NUMBER OF NOTIF HERE -->
              <?php
            }
          ?>
          <a class="nav-link dropdown-toggle notification-ui_icon" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-bs-target="#notif">
            <img class="rounded-circle" src="../assets/img/bell.png" width="40" height="40" /> </a>
          <div class="dropdown-menu notification-ui_dd" style="width: 21rem;" aria-labelledby="navbarDropdown" id="notif">
            <div class="notification-ui_dd-header">
              <h3 class="text-center notification-header" style="color:#f8f9fa;">Notifications</h3>
            </div>
            <div class="notification-ui_dd-content">

              <?php
              function get_name($emp_id)
              {
                include('connection.php');
                $get_name_query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
                $get_name_result = mysqli_query($con, $get_name_query);
                if($get_name_result)
                {
                  if(mysqli_num_rows($get_name_result))
                  {
                    while ($row = mysqli_fetch_array($get_name_result))
                    {
                      return $row['emp_fullname'];
                    }
                  }
                }
              }

              function get_image($emp_id)
              {
                include('connection.php');
                $account_id = "";
                $get_account_id_query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
                $get_account_id_result = mysqli_query($con, $get_account_id_query);
                if($get_account_id_result)
                {
                  if(mysqli_num_rows($get_account_id_result))
                  {
                    while ($row = mysqli_fetch_array($get_account_id_result))
                    {
                      $account_id = $row['account_id'];
                    }
                  }
                }

                $image = "";
                $get_image_query = "SELECT * FROM accounts WHERE account_id = '$account_id'";
                $get_image_result = mysqli_query($con, $get_image_query);
                if($get_image_result)
                {
                  if(mysqli_num_rows($get_image_result))
                  {
                    while ($row = mysqli_fetch_array($get_image_result))
                    {
                      $image = $row['picture'];
                    }
                  }
                }

                return $image;
              }
                  $id_list = [];
                  $date_list = [];
                  $emp_id_list = [];
                  $comment = [];
                  $image_list = [];
                  $get_notif_dates_query = "SELECT * FROM notifications WHERE notif_status = '$status'";
                  $get_notif_dates_result = mysqli_query($con, $get_notif_dates_query);

                  if($get_notif_dates_result)
                  {
                    if(mysqli_num_rows($get_notif_dates_result))
                    {
                      while ($row = mysqli_fetch_array($get_notif_dates_result))
                      {
                        $id_list[] = $row['id'];
                        $date_list[] = $row['record_month'] . " " . $row['record_day'] . ", " . $row['record_year'];
                        $emp_id_list[] = get_name($row['emp_id']);
                        $comment[] = $row['notif_desc'];
                        $image_list[] = get_image($row['emp_id']);
                      }
                    }
                  }
                  array_multisort($id_list, SORT_DESC, SORT_NUMERIC, $date_list, $emp_id_list, $comment, $image_list);
                  $notif_counts = count($id_list);
                  for($x = 0; $x < $notif_counts; $x++)
                  {
                    ?>
                    <!-- ITEM START -->
                    <div class="notification-list notification-list">
                      <div class="notification-list_img">
                        <img src="../img/<?php echo $image_list[$x]; ?>" alt="user">
                      </div>
                      <div class="notification-list_detail">
                        <p><b> <?php echo $emp_id_list[$x]; ?></b> <?php echo $comment[$x]; ?></p>
                        <p><small><?php echo $date_list[$x]; ?></small></p>
                      </div>
                    </div>
                    <!-- ITEM END -->
                    <?php
                  }
              ?>
            </div>
            <div class="notification-ui_dd-footer mt-2">
              <div class="wrapper text-center">
                <a href="../admin-files/notifications.php" class="btn btn-danger">View All</a>
              </div>
            </div>


        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-bs-target="#drop">
            <img class="rounded-circle" src="../img/<?php echo $image; ?>" width="40" height="40" />
            <span class="mr-2"> <?php echo ($_SESSION['name']); ?> </span>
          </a>
          <div class="dropdown-menu dropdown-menu-end shadow pull-right" id="drop" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#profile" data-bs-toggle="modal" data-target="#profile">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              Edit Profile
            </a>
            <a class="dropdown-item" href="signout.php"> <i class="fas fa-sign-out fa-sm fa-fw mr-2 text-gray-400"></i>
              Sign out
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- END nav -->
</section>


<div class="modal fade" role="dialog" id="profile" tabindex="-1" aria-labelledby="profile" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profile">Edit Profile</h5>
      </div>
      <div class="modal-body">

        <div class="col">
          <div class="form-group">
            <form method="POST" enctype="multipart/form-data" enctype="multipart/form-data">

              <label for="inputGroupFile01" class="form-label"> Upload Image</label>
              <input type="file" class="form-control" id="inputGroupFile01" name="image" id="image" accept=".jpg, .jpeg, .png" value="">

              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="submit" name="photo_upload"> Save Profile</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
