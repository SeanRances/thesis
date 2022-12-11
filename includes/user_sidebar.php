<nav id="sidebar">
		<div class="custom-menu">
			<button type="button" id="sidebarCollapse" class="btn btn-primary"></button>
        </div>
	  	<div class="img bg-wrap text-center py-4" style="background-image: url(../assets/img/bg_1.jpg);">
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
          <li class="<?= ($activePage == 'user_page') ? 'active':''; ?>">
            <a href="user_page.php"><span class="fa fa-home mr-3"></span> Profile </a>
          </li>
          <li class="<?= ($activePage == 'performance') ? 'active':''; ?>">
              <a href="performance.php"><span class="fa fa-record-vinyl mr-3"><small class="d-flex align-items-center justify-content-center"></small></span> Performance Records </a>
          </li>
          <li class="<?= ($activePage == 'da') ? 'active':''; ?>">
            <a href="da.php"><span class="fa fa-triangle-exclamation mr-3"></span> DA </a>
          </li>
          <li class="<?= ($activePage == 'rankings') ? 'active':''; ?>">
            <a href="rankings.php"><span class="fa fa-trophy mr-3"></span> Ranking </a>
          </li>

          <li>
            <a href="signout.php"><span class="fa fa-sign-out mr-3"></span> Sign Out</a>
          </li>
        </ul>

    </nav>
