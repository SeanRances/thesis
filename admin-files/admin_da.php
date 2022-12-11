<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/admin_da_functions.php';
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
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title> User </title>
</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>



  <!-- Page Content  -->



  <div class="container-fluid py-5">
    <div class="row">
      <div class="col">

        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"> Employees with DA </h6>
          </div>

          <div class="card-body">
            <nav>
              <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#list" role="tab" aria-controls="nav-home" aria-selected="true"> <span class="m-0 font-weight-bold text-primary">
                    Employee List </span> </a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#status" role="tab" aria-controls="nav-profile" aria-selected="false"> <span class="m-0 font-weight-bold text-primary">
                    Employee Goals </span> </a>

              </div>
            </nav>
            <div class="table-responsive">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="nav-home-tab">
                  <table id="table_id" class="table table-hover table-striped table-bordered" width="100%" cellspacing="0" role="grid">
                    <thead>
                      <tr class="bg-light">
                        <th class="border-top-0"> Name </th>
                        <th class="border-top-0"> QA Average </th>
                        <th class="border-top-0"> CPH Average </th>
                        <th class="border-top-0"> ATT Average </th>
                        <th class="border-top-0"> QA Score </th>
                        <th class="border-top-0"> CPH Score </th>
                        <th class="border-top-0"> ATT Score </th>
                        <th class="border-top-0"> Performance Score </th>
                        <th class="border-top-0"> Month </th>
                        <th class="border-top-0"> Year </th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      $query = "SELECT * FROM da_table";
                      $result = $result = mysqli_query($con, $query);

                      if ($result) {
                        if (mysqli_num_rows($result)) {
                          while ($row = mysqli_fetch_array($result)) {
                      ?>
                            <tr>
                              <td><?php echo $row['emp_fullname'] ?></td>
                              <td><?php echo $row['monthly_QA'] ?></td>
                              <td><?php echo $row['monthly_CPH'] ?></td>
                              <td><?php echo $row['monthly_ATT'] ?></td>
                              <td><?php echo $row['monthly_score_QA'] ?></td>
                              <td><?php echo $row['monthly_score_CPH'] ?></td>
                              <td><?php echo $row['monthly_score_ATT'] ?></td>
                              <td><?php echo $row['monthly_perf'] ?></td>
                              <td><?php echo $row['record_month'] ?></td>
                              <td><?php echo $row['record_year'] ?></td>
                        <?php
                          }
                        }
                      }
                        ?>
                    </tbody>
                  </table>
                  <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-info mb-2 py-2 mr-2" data-toggle="modal" data-target="#Rollback">
                    Rollback Employee
                  </button>
                  <div class="modal fade" role="dialog" id="Rollback" tabindex="-1" aria-labelledby="Rollback" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="Rollback">Rollback Employee</h5>


                        </div>

                        <div class="modal-body">
                          <div class="col">

                            <div class="form-group">
                              <form method="POST" action="">
                                <label class="mr-2">Select Employee: </label>
                                <select name="employee_choice" class="custom-select custom-select-sm form-control form-control-sm">
                                  <option value=""> --Select-- </option>
                                  <?php
                                  $query = "SELECT DISTINCT emp_fullname,emp_id FROM da_table";
                                  $result = $result = mysqli_query($con, $query);

                                  if ($result) {
                                    if (mysqli_num_rows($result)) {
                                      while ($row = mysqli_fetch_array($result)) {
                                        $emp_name = $row['emp_fullname'];
                                        $emp_id = $row['emp_id'];
                                  ?>
                                        <option value="<?php echo $emp_id; ?>"> <?php echo $emp_name; ?> </option>
                                  <?php
                                      }
                                    }
                                  }
                                  ?>
                                </select>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button class="btn btn-success" type="submit" name="btn_save">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <?php
                        if (isset($_POST['employee_choice'])) {
                          $emp_id = $_POST['employee_choice'];
                          remove_da_employees_table($emp_id);
                          remove_da_da_table($emp_id);
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <table id="table_id" class="table table-hover table-striped table-bordered" width="100%" cellspacing="0" role="grid">
                    <thead>
                      <tr class="bg-light">
                        <th class="border-top-0"> Name </th>
                        <th class="border-top-0"> QA Target</th>
                        <th class="border-top-0"> CPH Target</th>
                        <th class="border-top-0"> ATT Target</th>
                        <th class="border-top-0"> Start Date</th>
                        <th class="border-top-0"> End Date</th>
                        <th class="border-top-0"> Goal Status</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      $query = "SELECT * FROM employee_goals";
                      $result = $result = mysqli_query($con, $query);

                      if ($result) {
                        if (mysqli_num_rows($result)) {
                          while ($row = mysqli_fetch_array($result)) {
                      ?>
                            <tr>
                              <td><?php echo $row['emp_fullname'] ?></td>
                              <td><?php echo $row['goal_QA'] ?></td>
                              <td><?php echo $row['goal_CPH'] ?></td>
                              <td><?php echo $row['goal_ATT'] ?></td>
                              <?php
                              $date = $row['start_month'] . " " . $row['start_day'] . " " . $row['start_year'];
                              $my_date = date('F j Y', strtotime($date));
                              $date2 = $row['end_month'] . " " . $row['end_day'] . " " . $row['end_year'];
                              $my_date2 = date('F j Y', strtotime($date2));
                              ?>
                              <td><?php echo $my_date ?></td>
                              <td><?php echo $my_date2 ?></td>
                              <td><?php echo $row['goal_status'] ?></td>
                        <?php
                          }
                        }
                      }
                        ?>
                    </tbody>
                  </table>
                  <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-info mb-2 py-2 mr-2" data-toggle="modal" data-target="#GoalModal">
                    Set Goals
                  </button>
                  <div class="modal fade" role="dialog" id="GoalModal" tabindex="-1" aria-labelledby="GoalModal" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="GoalModal"> Set Goal</h5>
                        </div>
                        <div class="modal-body">
                          <div class="col">
                            <div class="form-group">
                              <form method="POST" action="">
                                <label class="mr-2">Select Employee: </label>
                                <select name="goal_set_choice" class="custom-select custom-select-sm form-control form-control-sm">
                                  <option value=""> --Select-- </option>
                                  <?php
                                  $query = "SELECT DISTINCT emp_fullname,emp_id FROM da_table";
                                  $result = $result = mysqli_query($con, $query);

                                  if ($result) {
                                    if (mysqli_num_rows($result)) {
                                      while ($row = mysqli_fetch_array($result)) {
                                        $emp_name = $row['emp_fullname'];
                                        $emp_id = $row['emp_id'];
                                  ?>
                                        <option value="<?php echo $emp_id; ?>"> <?php echo $emp_name; ?> </option>
                                  <?php
                                      }
                                    }
                                  }
                                  ?>
                                </select>

                                <label class="py-2">Quality Assurance Score Goal: </label>
                                <input class="form-control form-control-sm" type="text" name="goal_QA" required>
                                <label class="py-2">Charts Per Hour Score Goal: </label>
                                <input class="form-control form-control-sm" type="text" name="goal_CPH" required>
                                <label class="py-2">Attendance Goal: </label>
                                <input class="form-control form-control-sm" type="text" name="goal_ATT" required>
                                <label class="py-2">Goal start date: </label>
                                <input class="form-control form-control-sm" type="date" name="date1" required>
                                <label class="py-2">Goal end date: </label>
                                <input class="form-control form-control-sm" type="date" name="date2" required>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button class="btn btn-success" type="submit" name="btn_save">Save</button>
                                </div>
                              </form>
                            </div>
                            <?php
                            if (isset($_POST['btn_save'])) {
                              $date1 = $_POST['date1'];
                              $date2 = $_POST['date2'];
                              $date_checker = check_date($date1, $date2);
                              if ($date_checker == 0) {
                                $emp_id = $_POST['goal_set_choice'];
                                $goal_QA = $_POST['goal_QA'];
                                $goal_CPH = $_POST['goal_CPH'];
                                $goal_ATT = $_POST['goal_ATT'];
                                insert_into_goals_table($emp_id, $goal_QA, $goal_CPH, $goal_ATT, $date1, $date2);
                              }
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="tab-pane fade" id="goals" role="tabpanel" aria-labelledby="nav-contact-tab">
                  <table id="table_id" class="table table-hover table-striped table-bordered" width="100%" cellspacing="0" role="grid">
                    <thead>
                      <tr class="bg-light">
                        <th class="border-top-0"> Name </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td> IU </td>

                      </tr>
                      <tr>
                        <td> Yujin </td>
                      </tr>
                    </tbody>

                  </table>

                </div>
              </div>
            </div>
          </div>

          <!-- next row if needed

        <div class="row">
          <div class="col-xl-6 col-lg">
            <div class="card border-left-danger shadow h-100 py-2">
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"> Feed </h6>
              </div>
              <div class="card-body">
                <div class="feed-widget">
                  <ul class="list-style-none feed-body m-0 p-b-20">
                    <li class="feed-item">
                      IU received a disciplinary action (Performance Score: 77)
                      <span class="text-primary font-italic mr-2"> Just Now </span>
                    </li>
                    <li class="feed-item">
                      Yujin received a disciplinary action (Performance Score: 77)
                      <span class="text-primary font-italic mr-2"> 30 mins ago </span>
                    </li>
                    <li class="feed-item">
                      You have 2 Employees Requiring Attention

                    </li>

                  </ul>
                </div>
              </div>

            </div>
          </div>
        </div>
-->
        </div>


      </div>

    </div>
  </div>

</body>

<?php include '../includes/script.php' ?>

</html>