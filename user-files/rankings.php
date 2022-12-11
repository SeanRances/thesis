<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../user-files-functions/rankings_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];
$account_id =  $user_data['account_id'];

$emp_id = get_empID($account_id);

$emp_name = $user_data['name'];
$month = date('F');
$year = date('Y');
$rank = check_rank($emp_name, $month, $year);
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Ranking </title>
  <script>
    $(document).ready(function() {
      $("a").tooltip({
        'selector': '',
        'placement': 'top',
        'container': 'body'
      });

    });
  </script>
</head>
<?php include_once '../includes/user_navbar.php' ?>

<body>

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
  <div class="container-fluid pt-3">
    <div class="row">
      <div class="col-lg-3">
        <div class="card box mb-3 shadow-sm rounded bg-white profile-box text-center">
          <div class="p-5">
            <img src="../img/<?php echo $image; ?>" class="img-fluid" alt="Responsive image" />
          </div>
          <div class="p-3 border-top border-bottom">
            <h5 class="font-weight-bold text-dark mb-1 mt-0"> <?php echo $name ?> </h5>

          </div>
          <div class="p-3">
            <div class="d-flex align-items-top">
              <p class="mb-0 text-muted">Rank</p>
              <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto">25</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-9">
        <div class="card mb-4" style="min-height: 36.3rem;">
          <div class="card-header py-3 bg-transparent d-flex flex-row align-items-center">
            <h6 class="mr-2 font-weight-bold text-primary"> Employees <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="See how you did compared to other agents"></span> </h6>
            <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">
              <form method="POST">
                <select name="ranking_choice" class="custom-select custom-select-sm form-control form-control-sm" onchange="this.form.submit()">
                  <option value=""> --Select-- </option>
                  <option value="1"> Current Rank </option>
                  <option value="2"> Rank on specific day </option>
                  <option value="3"> Rank on specific month </option>
                  <option value="4"> Rank on specific year </option>
                </select>
              </form>
              <?php
              if (isset($_POST['ranking_choice'])) {
                $choice = $_POST['ranking_choice'];
                if ($choice == 2) {
              ?>
                  <form method="POST">
                    <label>Select date: </label>
                    <input type="date" name="date" /><br>
                    <input type="submit" name="submit" />
                  </form>
                <?php
                } elseif ($choice == 3) {
                ?>
                  <form method="POST">
                    <label>Select date: </label>
                    <select name="monthly_date_chosen" onchange="this.form.submit()">
                      <option value=""> --Select-- </option>
                      <?php
                      $query = "SELECT DISTINCT record_month,record_year FROM performance_record ORDER BY id ASC";
                      $result = $result = mysqli_query($con, $query);
                      if ($result) {
                        if (mysqli_num_rows($result)) {
                          while ($row = mysqli_fetch_array($result)) {
                            $month = $row['record_month'];
                            $year = $row['record_year'];
                            $month_year = $row['record_month'] . " " . $row['record_year'];
                      ?>
                            <option value="<?php echo $month_year; ?>"> <?php echo $month_year; ?> </option>
                      <?php
                          }
                        }
                      }
                      ?>
                    </select>
                  </form>
                <?php
                } elseif ($choice == 4) {
                ?>
                  <form method="POST">
                    <label>Select year: </label>
                    <select name="yearly_date_chosen" onchange="this.form.submit()">
                      <option value=""> --Select-- </option>
                      <?php
                      $query = "SELECT DISTINCT record_year FROM performance_record ORDER BY id ASC";
                      $result = $result = mysqli_query($con, $query);
                      if ($result) {
                        if (mysqli_num_rows($result)) {
                          while ($row = mysqli_fetch_array($result)) {
                            $year = $row['record_year'];
                      ?>
                            <option value="<?php echo $year; ?>"> <?php echo $year; ?> </option>
                  <?php
                          }
                        }
                      }
                    }
                  }
                  ?>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" width="100%" cellspacing="0" role="grid">
                <thead>
                  <tr class="bg-light">
                    <th class="border-top-0"> Rank </th>
                    <th class="border-top-0"> Name </th>
                    <th class="border-top-0"> Performance Score </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //check what the admin selected
                  if (isset($_POST['ranking_choice'])) {
                    $choice = $_POST['ranking_choice'];
                    choice_checker($choice);
                  }
                  //if choice 2 was chosen and date was chosen
                  if (isset($_POST['submit'])) {
                    $date = $_POST['date'];
                    $temp = explode('-', $date);
                    $month = str_replace("0", "", $temp[1]);
                    $year = $temp[0];
                    $day = str_replace("0", "", $temp[2]);
                    $dateObj   = DateTime::createFromFormat('!m', $month);
                    $monthName = $dateObj->format('F'); // March
                    $month = $monthName;
                    choice_two($day, $month, $year);
                  }
                  //if choice 3 was chosen and date was chosen
                  if (isset($_POST['monthly_date_chosen'])) {
                    $output = $_POST['monthly_date_chosen'];
                    $str = explode(" ", $output);
                    $month = $str[0];
                    $year = $str[1];
                    choice_three($month, $year);
                  }
                  //if choice 4 was chosen and year was chosen
                  if (isset($_POST['yearly_date_chosen'])) {
                    $output = $_POST['yearly_date_chosen'];
                    choice_four($output);
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


</body>

<?php include '../includes/script.php' ?>

</html>
