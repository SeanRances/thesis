<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../user-files-functions/da_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$uname = $_SESSION['name'];

$user_data = check_login($con);

//get account ID from user date
$account_id =  $user_data['account_id'];

//get emp_id through account id
$emp_id = get_empID($account_id);

//get number of DAs
$da_count = count_da($emp_id);

$goal_count = count_da($emp_id);
?>

<head>
    <style>
        .Scroll {

            overflow-y: scroll;
            height: 600px;
        }
    </style>



    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> DA </title>
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
                        <h5 class="font-weight-bold text-dark mb-1 mt-0"> <?php echo $uname ?></h5>
                    </div>
                    <div class="p-3">
                        <div class="d-flex align-items-top mb-2">
                            <p class="mb-0 text-muted"> DA </p>
                            <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"> <?php echo $da_count ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card mb-4" style="min-height:36.7rem;">
                    <div class="card-header bg-transparent py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary "><i class="fas fa-tasks mr-2"></i>
                            Goals </h6>
                    </div>
                    <div class="card-body Scroll" style="position: relative; height: 15.5rem">
                        <table class="table  mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">QA Target</th>
                                    <th scope="col">CPH Target</th>
                                    <th scope="col">ATT Target</th>
                                    <th scope="col">Goal Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM employee_goals WHERE emp_id = '$emp_id'";
                                $result = $result = mysqli_query($con, $query);
                                if ($result) {
                                    if (mysqli_num_rows($result)) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $date = $row['start_month'] . " " . $row['start_day'] . " " . $row['start_year'];
                                            $my_date = date('F j Y', strtotime($date));
                                            $date2 = $row['end_month'] . " " . $row['end_day'] . " " . $row['end_year'];
                                            $my_date2 = date('F j Y', strtotime($date2));

                                            echo "<tr class='fw-normal'>
                                                              <td class='align-middle'>
                                                                <span>" . $my_date . "</span>
                                                              </td>
                                                              <td class='align-middle'>
                                                                <span>" . $my_date2 . "</span>
                                                              </td>
                                                              <td class='align-middle'>
                                                                <span>" . $row['goal_QA'] . "</span>
                                                              </td>
                                                              <td class='align-middle'>
                                                                <span>" . $row['goal_CPH'] . "</span>
                                                              </td>
                                                              <td class='align-middle'>
                                                                <span>" . $row['goal_ATT'] . "</span>
                                                              </td>
                                                              <td class='align-middle'>
                                                                <span>" . $row['goal_status'] . "</span>
                                                              </td>
                                                            </tr>";
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- row end -->

        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header py-3 bg-transparent d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"> Dates of DA </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered" width="100%" cellspacing="0" role="grid">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="border-top-0"> Date </th>
                                        <th class="border-top-0"> QA Average </th>
                                        <th class="border-top-0"> CPH Average </th>
                                        <th class="border-top-0"> QA Score </th>
                                        <th class="border-top-0"> CPH Score </th>
                                        <th class="border-top-0"> ATT Score </th>
                                        <th class="border-top-0"> Performance Score </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM da_table WHERE emp_id = '$emp_id'";
                                    $result = $result = mysqli_query($con, $query);
                                    if ($result) {
                                        if (mysqli_num_rows($result)) {
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>
                                                          <td>" . $row['record_month'] . " " . $row['record_year'] . "</td>
                                                          <td>" . $row['monthly_QA'] . "</td>
                                                          <td>" . $row['monthly_CPH'] . "</td>
                                                          <td>" . $row['monthly_score_QA'] . "</td>
                                                          <td>" . $row['monthly_score_CPH'] . "</td>
                                                          <td>" . $row['monthly_ATT'] . "</td>
                                                          <td>" . $row['perf_comment'] . "</td>
                                                          </tr>";
                                            }
                                        }
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
