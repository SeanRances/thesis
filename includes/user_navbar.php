<?php
$user_data = check_login($con);
$account_id =  $user_data['account_id'];

if(isset($_POST["photo_upload"])){
  if($_FILES["image"]["error"] == 4){
    echo
    "<script> alert('Image Does Not Exist'); </script>"
    ;
  }
  else{
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode('.', $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if ( !in_array($imageExtension, $validImageExtension) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    }
    else if($fileSize > 1000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    }
    else{
      $newImageName = uniqid();
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, '../img/' . $newImageName);
      $query = "UPDATE accounts SET picture='$newImageName' WHERE account_id='$account_id'";
      mysqli_query($con, $query);
    }
  }
}
?>

<link rel="stylesheet" href="../assets/css/homepage.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
  integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
  integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
  integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<section>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html"> <img src="../assets/img/logo1.png" style="height:2.5rem;"> </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="fa fa-bars"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav m-auto">
          <li class="nav-item <?= ($activePage == 'user_page') ? 'active':''; ?>"><a href="user_page.php" class="nav-link">Home</a></li>
          <li class="nav-item <?= ($activePage == 'performance') ? 'active':''; ?>"><a href="performance.php" class="nav-link"> Performance </a></li>
          <li class="nav-item <?= ($activePage == 'rankings') ? 'active':''; ?>"><a href="rankings.php" class="nav-link"> Ranking </a></li>
          <li class="nav-item <?= ($activePage == 'da') ? 'active':''; ?>"><a href="da.php" class="nav-link">DA</a></li>
        </ul>
      </div>

    </div>
    <?php
      $fetch_picture_query = "SELECT * FROM accounts WHERE account_id='$account_id'";
      $fetch_picture_result = mysqli_query($con,$fetch_picture_query);

      if($fetch_picture_result)
      {
        if(mysqli_num_rows($fetch_picture_result))
        {
          while($row = mysqli_fetch_array($fetch_picture_result))
          {
            $image = $row['picture'];
          }
        }
      }
     ?>
    <div>
      <ul class="navbar-nav mr-4">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="true" data-bs-target="#drop">

            <img class="rounded-circle" src="../img/<?php echo $image; ?>" width="40" height="40" />
            <span class="mr-2"> <?php echo($_SESSION['name']); ?> </span>

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
                  <input type="file" class="form-control" id="inputGroupFile01" name="image" id="image"
                    accept=".jpg, .jpeg, .png" value="">

                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button class="btn btn-primary" type="submit" name="photo_upload"> Save Profile</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
