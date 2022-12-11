<?php
$user_data = check_login($con);
$account_id =  $user_data['account_id'];
?>
<nav id="sidebar">
		<div class="custom-menu">
			<button type="button" id="sidebarCollapse" class="btn btn-primary"></button>
        </div>
	  	<div class="img bg-wrap text-center py-4"  style="background-image: url(../assets/img/bg_1.jpg);">
	  		<div class="user-logo">
					<?php
						$query = "SELECT * FROM accounts WHERE account_id='$account_id'";
						$result = mysqli_query($con,$query);

						if($result)
						{
							if(mysqli_num_rows($result))
							{
								while($row = mysqli_fetch_array($result))
								{
									$image = $row['picture'];
								}
							}
						}
					 ?>
	  			<img class="img" src="../img/<?php echo $image; ?>" />
	  				<h3> <?php echo($_SESSION['name']); ?> </h3>
	  			</div>
	  		</div>
        <ul class="list-unstyled components mb-5">
          <li class="<?= ($activePage == 'admin_page') ? 'active':''; ?>">
            <a href="admin_page.php"><span class="fa fa-chart-line mr-3"></span> Dashboard </a>
          </li>
          <li class="<?= ($activePage == 'admin_performance') ? 'active':''; ?>">
              <a href="admin_performance.php"><span class="fa fa-chart-bar mr-3"><small class="d-flex align-items-center justify-content-center"></small></span> Employee Performance </a>
          </li>
          <li class="<?= ($activePage == 'admin_rankings') ? 'active':''; ?>">
            <a href="admin_rankings.php"><span class="fa fa-ranking-star mr-3"></span> Employee Rankings </a>
          </li>
          <li class="<?= ($activePage == 'manage_emp') ? 'active':''; ?>">
            <a href="manage_emp.php"><span class="fa fa-users mr-3"></span> Manage Employee </a>
          </li>
          <li class="<?= ($activePage == 'rewards') ? 'active':''; ?>">
            <a href="rewards.php"><span class="fa fa-award mr-3"></span> Manage Rewards </a>
          </li>
          <li class="<?= ($activePage == 'admin_da') ? 'active':''; ?>">
            <a href="admin_da.php"><span class="fa fa-note-sticky mr-3"></span> Manage DA </a>
          </li>
          <li class="<?= ($activePage == 'upload_file') ? 'active':''; ?>">
            <a href="upload_file.php"><span class="fa fa-upload mr-3"></span> Upload File </a>
          </li>
          <li>
            <a href="signout.php"><span class="fa fa-sign-out mr-3"></span> Sign Out</a>
          </li>
        </ul>

    </nav>
