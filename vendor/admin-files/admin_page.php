<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/admin_page_functions.php';
include '../admin-files-functions/admin_charts_month_functions.php';
include '../admin-files-functions/admin_charts_year_functions.php';
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

//get num Employees
$num_employees = num_employees();

//get employees with da
$da_employees = employees_with_da();

//get surpassed employees
$surpassed_employees = surpassed_employees_today();

//get achieved employees
$achieved_employees = achieved_employees_today();

$a = 123.43;
$b = 23.39;
?>

<head>
  <style>
    .Scroll {

      overflow-y: scroll;
      height: 600px;
    }

    .emp {
      transition: 0.3s;
    }

    .emp:hover {
      opacity: .5;
    }
  </style>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
  </script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/easypiechart.js"></script>

  <title> Admin </title>
</head>

<body>
  <?php include_once '../includes/admin_navbar.php' ?>
  <section class="m-2">


    <!-- Page Content  -->


    <section>
      <div class="container-fluid py-5">
        <div class="row">
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Number of Employees </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $num_employees ?> </div>
                    <br>
                    <div class="progress" style="height:8px">
                      <div class="progress-bar" role="progressbar" style="width: <?php echo $num_employees  . '%'; ?>" aria-valuenow="<?php echo $num_employees ?>" aria-valuemin="0" aria-valuemax="100"> </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"> </i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Number of Employees with
                      DA</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $da_employees ?> </div>
                    <br>
                    <div class="progress" style="height:8px">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $da_employees  . '%'; ?>" aria-valuenow="<?php echo $da_employees ?>" aria-valuemin="0" aria-valuemax="100"> </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-person-circle-exclamation fa-2x text-gray-300"> </i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Number of Surpassed
                      Employees (Today) </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $surpassed_employees ?> </div>
                    <br>
                    <div class="progress" style="height:8px">
                      <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $surpassed_employees  . '%'; ?>" aria-valuenow="<?php echo $surpassed_employees ?>" aria-valuemin="0" aria-valuemax="100"> </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-star fa-2x text-gray-300"> </i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Number of Achieved
                      Employees (Today) </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $achieved_employees ?> </div>
                    <br>
                    <div class="progress" style="height:8px">
                      <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $achieved_employees  . '%'; ?>" aria-valuenow="<?php echo $achieved_employees ?>" aria-valuemin="0" aria-valuemax="100"> </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-user-check fa-2x text-gray-300"> </i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-9 col-lg-7">
            <div class="card mb-4 Scroll">
              <div class="card-header bg-transparent px-4 py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"> Company Performance </h6>
              </div>
              <div class="card-body justify-content-center" style="position: relative; height: 25rem">
                <nav>
                  <div class="nav nav-tabs nav-fill" id="nav-daily-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-daily-tab" data-toggle="tab" href="#daily" role="tab" aria-controls="nav-profile" aria-selected="false"> <span class="m-0 font-weight-bold text-primary"> Daily </span> </a>
                    <a class="nav-item nav-link" id="nav-monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="nav-profile" aria-selected="false"> <span class="m-0 font-weight-bold text-primary"> Monthly </span> </a>
                    <a class="nav-item nav-link" id="nav-yearly-tab" data-toggle="tab" href="#yearly" role="tab" aria-controls="nav-profile" aria-selected="false"> <span class="m-0 font-weight-bold text-primary"> Yearly </span> </a>
                  </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="daily" role="tabpanel" aria-labelledby="nav-daily-tab">
                    <!-- daily line chart -->
                    <canvas id="daily-chart" height="104rem"></canvas>
                    <script>
                    <?php $daily_score = chart_performance(); ?>
                      // Bar chart
                      new Chart(document.getElementById("daily-chart"), {
                        type: 'bar',
                        data: {
                          labels: ["QA", "CPH", "ATT"],
                          datasets: [{
                            backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f"],
                            data: [<?php echo $daily_score;?>, 1]
                          }]
                        },
                        options: {
                          legend: {
                            display: false
                          },
                          title: {
                            display: true,
                            text: 'Daily Employee Performance'
                          }
                        }
                      });
                    </script>

                  </div>
                  <!-- monthly -->
                  <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="nav-monthly-tab">
                    <canvas id="monthly_chart" height="104rem"></canvas>
                    <!-- monthly line chart -->
                    <script>
                      new Chart(document.getElementById("monthly_chart"), {
                        type: 'line',
                        data: {
                          //x-axis
                          labels: [<?php chart_get_months(); ?>],
                          datasets: [{
                            //x-axis data
                            data: [<?php chart_monthly_score_QA(); ?>],
                            label: "QA",
                            borderColor: "#3e95cd",
                            fill: false
                          }, {
                            data: [<?php chart_monthly_score_CPH(); ?>],
                            label: "CPH",
                            borderColor: "#8e5ea2",
                            fill: false
                          }, {
                            data: [<?php chart_monthly_score_ATT(); ?>],
                            label: "ATT",
                            borderColor: "#3cba9f",
                            fill: false
                          }]
                        },
                        options: {
                          title: {
                            display: true,
                            text: 'Monthly Employee Scores'
                          }
                        }
                      });
                    </script>

                  </div>
                  <!-- yearly -->
                  <div class="tab-pane fade" id="yearly" role="tabpanel" aria-labelledby="nav-yearly-tab">
                    <canvas id="yearly_chart" height="104rem"></canvas>
                    <!-- yearly line chart -->
                    <script>
                      new Chart(document.getElementById("yearly_chart"), {
                        type: 'line',
                        data: {
                          labels: [<?php chart_get_years(); ?>],
                          datasets: [{
                            data: [<?php chart_yearly_score_QA(); ?>],
                            label: "QA",
                            borderColor: "#3e95cd",
                            fill: false
                          }, {
                            data: [<?php chart_yearly_score_CPH(); ?>],
                            label: "CPH",
                            borderColor: "#8e5ea2",
                            fill: false
                          }, {
                            data: [<?php chart_yearly_score_ATT(); ?>],
                            label: "ATT",
                            borderColor: "#3cba9f",
                            fill: false
                          }]
                        },
                        options: {
                          title: {
                            display: true,
                            text: 'Yearly Employee Scores'
                          }
                        }
                      });
                    </script>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-5">
            <div class="card mb-4" style="height:37.4rem;">
              <div class="card-header bg-transparent px-4 py-3">
                <h6 class="m-0 font-weight-bold text-primary"> Employee of The Day </h6>
              </div>
              <div class="card-body Scroll">
                <div class="text-center">
                  <?php
                  $holder = emp_of_the_day();
                  if ($holder == "No records") {
                  ?><p>No records yet.</p><?php
                                        } else {
                                          $arr = explode("||", $holder);
                                          $day_image = $arr[0];
                                          $emp_id = $arr[1];
                                          $emp_name = $arr[2];

                                          $type = "day";
                                          $day = date('j');
                                          $month = date('F');
                                          $year = date('Y');
                                          $check_perf = $type . "||" . $emp_id . "||" . $day . "||" . $month . "||" . $year;
                                          $performance_holder = emp_of_day_performance($emp_id, $day, $month, $year);

                                          $arr1 = explode("||", $performance_holder);
                                          $QA_score_emp = $arr1[0];
                                          $CPH_score_emp = $arr1[1];
                                          ?>
                    <a href="admin_view_emp_perf.php?post=<?php echo $check_perf ?>">
                      <img alt="nope" class=" emp img-thumbnail img-fluid" style="height: 180px;" src="../img/<?php echo $day_image ?>" />
                    </a>
                    <div class="mt-4 border-top border-bottom">
                      <p class="font-weight-bold text-dark mb-1 mt-2"><?php echo $emp_name; ?></p>
                      <p class="text-muted mb-0"> Ranked 1st out of <?php echo $num_employees ?> Employees </p>
                      <div class="container mt-2">
                        <div class="row">
                          <div class="col-sm-4">
                            <p class="font-weight-bold text-dark mb-1 mt-0 mt-2"> CPH </p>
                            <p class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $CPH_score_emp; ?> </p>
                            <span class="chart1" data-percent="<?php echo $CPH_score_emp; ?>">
                              <span class="percent"> </span>
                            </span>
                            <script>
                              const myChart1 = new EasyPieChart(document.querySelector('.chart1'), {



                              });
                            </script>
                          </div>
                          <div class="col-sm-4">
                            <p class="font-weight-bold text-dark mb-1 mt-0 mt-2"> ATT </p>
                            <p class="h5 mb-0 font-weight-bold text-gray-800"> 100 </p>
                            <span class="chart2" data-percent="100">
                              <span class="percent"></span>
                            </span>
                            <script>
                              const myChart2 = new EasyPieChart(document.querySelector('.chart2'), {

                                // options here

                              });
                            </script>
                          </div>
                          <div class="col-sm-4">
                            <p class="font-weight-bold text-dark mb-1 mt-0 mt-2"> QA </p>
                            <p class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $QA_score_emp; ?> </p>
                            <span class="chart3" data-percent="<?php echo $QA_score_emp; ?>">
                              <span class="percent"></span>
                            </span>
                            <script>
                              const myChart3 = new EasyPieChart(document.querySelector('.chart3'), {

                                // options here

                              });
                            </script>
                          </div>
                        </div>
                      </div>

                    </div>

                  <?php
                                        }

                  ?>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class=" row">
          <div class="col">
            <div class="card Scroll mb-4" style="position: relative; height: 30rem">
              <div class="card-header bg-transparent px-4 py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"> Current Monthly Performance </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="table_id" class="table table-bordered" width="100%" cellspacing="0" role="grid">
                    <thead>
                      <tr class="bg-light">
                        <th class="border-top-0"> Name </th>
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
                      current_performance();
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

    </section>

    </div>
    </div>


</body>

<?php include '../includes/script.php' ?>

</html>
