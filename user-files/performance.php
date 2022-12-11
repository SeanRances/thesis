<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../user-files-functions/performance_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];
$account_id =  $user_data['account_id'];

$emp_id = get_empID($account_id);
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title> Performance </title>
</head>
<?php include_once '../includes/user_navbar.php' ?>

<body>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col-lg">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

            <?php
            if (isset($_POST['performance_choice'])) {
              $choice = $_POST['performance_choice'];
              $display_checker = choice_checker_one($choice);

              if ($display_checker == 1) {
                echo "Date: " . date('F') . " " . date('j') . ", " . date('Y');
              }
            }

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
            <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">
              <form method="POST" action="">
                <label>Select viewing option: </label>
                <select name="performance_choice" class="custom-select custom-select-sm form-control form-control-sm" onchange="this.form.submit()">
                  <option value=""> --Select-- </option>
                  <option value="1"> Recently Recorded </option>
                  <option value="2"> By day </option>
                  <option value="3"> By month </option>
                  <option value="4"> By year </option>
                </select>
              </form>
              <?php
              if (isset($_POST['performance_choice'])) {
                $choice = $_POST['performance_choice'];
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
                    <th class="border-top-0"> QA </th>
                    <th class="border-top-0"> CPH </th>
                    <th class="border-top-0"> ATT </th>
                    <th class="border-top-0"> QA score </th>
                    <th class="border-top-0"> CPH score </th>
                    <th class="border-top-0"> ATT score </th>
                    <th class="border-top-0"> PERF </th>
                    <th class="border-top-0"> Comment </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //check what the admin selected
                  if (isset($_POST['performance_choice'])) {
                    $choice = $_POST['performance_choice'];
                    $result = choice_checker($choice);
                    if ($result == 1) {
                      choice_one($emp_id);
                    }
                  }
                  //if choice 2 was chosen and submit was entered
                  if (isset($_POST['submit'])) {
                    $date = $_POST['date'];
                    $temp = explode('-', $date);
                    $month = str_replace("0", "", $temp[1]);
                    $year = $temp[0];
                    $day = str_replace("0", "", $temp[2]);
                    $dateObj   = DateTime::createFromFormat('!m', $month);
                    $monthName = $dateObj->format('F'); // March
                    $month = $monthName;
                    choice_two($day, $month, $year, $emp_id);
                    $display_date = "Date: " . $month . " " . $day . ", " . $year . "<br>";
                  }
                  //if choice 3 was chosen and date was chosen
                  if (isset($_POST['monthly_date_chosen'])) {
                    $output = $_POST['monthly_date_chosen'];
                    $str = explode(" ", $output);
                    $month = $str[0];
                    $year = $str[1];
                    choice_three($month, $year, $emp_id);
                  }
                  //if choice 3 was chosen and year was chosen
                  if (isset($_POST['yearly_date_chosen'])) {
                    $output = $_POST['yearly_date_chosen'];
                    choice_four($output, $emp_id);
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

</body>

<?php include '../includes/script.php' ?>

</html>