<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/rewards_functions.php';
$user_data = check_login($con);
$name =  $_SESSION['name'];

$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if ($_SESSION['usertype'] == 'admin') {
  echo "";
} else {
  echo ("<script>alert('You are not allowed to access this webpage!')</script>");
  echo ("<script>window.location = '../index.php';</script>");
}

?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title> Rewards </title>

  <style>
    div.tooltip-inner {
      max-width: 350px;
    }

    .text1 {
      font-size: 16px;
      line-height: 1.5em;
      font-family: Core Sans, Century Gothic, CenturyGothic, AppleGothic, sans-serif;
      font-weight: 400;

    }
  </style>

</head>

<script>
  $(document).ready(function() {
    $("a").tooltip({
      'selector': '',
      'placement': 'top',
      'container': 'body'
    });

  });
</script>
<?php include_once '../includes/admin_navbar.php' ?>

<body>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col-lg">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <div class="col-md-3">
              <div class="card" style="width: 40rem;">
                <div class="card border-bottom-danger h-100">
                  <div class="card-body">
                    <h6 class="font-weight-bold text-primary"> Reward Eligibility <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Click to Show Rewards Eligibility"></span>
                      </a> </h6>


                    <div class="collapse" id="collapseExample">
                      <div class="card card-body">
                        <p class="text1"> <strong> Achieved </strong> - An employee with a performance comment of 'Achieved' has a performance score between 2 to 3.49. An employee with this performance status is eligible for the rewards set by the manager. </p>

                        <p class="text1"> <strong> Surpassed </strong> - An employee with a performance comment of 'Surpassed' has a performance score of 3.50 and higher. An employee with this performance status is eligible for more than the rewards set by the manager. </p>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <?php
            if (isset($_POST['submit'])) {
              $date = $_POST['date'];
              $temp = explode('-', $date);
              $month = str_replace("0", "", $temp[1]);
              $year = $temp[0];
              $day = str_replace("0", "", $temp[2]);
              $dateObj   = DateTime::createFromFormat('!m', $month);
              $monthName = $dateObj->format('F'); // March
              $month = $monthName;
              $display_date = "Date: " . $month . " " . $day . ", " . $year . "<br>";

              echo $display_date;
            }

            if (isset($_POST['monthly_date_chosen'])) {
              $output = $_POST['monthly_date_chosen'];
              $str = explode(" ", $output);
              $month = $str[0];
              $year = $str[1];
              $display_date = "Date: " . $month . " "  . $year . "<br>";
              echo $display_date;
            }

            if (isset($_POST['yearly_date_chosen'])) {
              $output = $_POST['yearly_date_chosen'];

              echo "Year: " . $output . "<br>";
            }
            ?>

            <div class="col-md-3 ms-auto">
              <form method="POST">
                <label>View eligible employees: </label>
                <select name="rewards_choice" class="custom-select custom-select-sm form-control form-control-sm" onchange="this.form.submit()">
                  <option value=""> --Select-- </option>
                  <option value="1"> This month </option>
                  <option value="2"> Specific month </option>
                  <option value="3"> This year </option>
                  <option value="4"> Specific year </option>
                </select>
              </form>
              <?php
              if (isset($_POST['rewards_choice'])) {
                $choice = $_POST['rewards_choice'];
                if ($choice == 2) {
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
                    <th class="border-top-0"> Name </th>
                    <th class="border-top-0"> Performance Score </th>
                    <th class="border-top-0"> Comment </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //check what the admin selected
                  if (isset($_POST['rewards_choice'])) {
                    $choice = $_POST['rewards_choice'];
                    choice_checker($choice);
                  }
                  if (isset($_POST['monthly_date_chosen'])) {
                    $output = $_POST['monthly_date_chosen'];
                    $str = explode(" ", $output);
                    $month = $str[0];
                    $year = $str[1];
                    choice_two($month, $year);
                  }
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

</body>

<?php include '../includes/script.php' ?>

</html>