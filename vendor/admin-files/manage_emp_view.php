<?php
?>

<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../user-files-functions/user_page_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");

$user_data = check_login($con);
$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if ($_SESSION['usertype'] == 'admin') {
  echo "";
} else {
  echo ("<script>alert('You are not allowed to access this webpage!')</script>");
  echo ("<script>window.location = '../index.php';</script>");
}

$_GET['post'];
$emp_id = $_GET['post'];

$name = get_name($emp_id);
$monthly_eligibility = eligibility_checker_month($emp_id);
$annual_eligibility = eligibility_checker_year($emp_id);

$chart_ATT = get_ATT($emp_id);
$chart_QA = get_QA($emp_id);
$chart_CPH = get_CPH($emp_id);

$month = date('F');
$year = date('Y');
$rank = check_rank($name, $month, $year);
$goals = get_goals($emp_id);
$da_count = get_count_da($emp_id);
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <title> User </title>
</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>
  <section>
    <div class="container py-5">
      <div class="row">
        <div class="col-lg-4">
          <div class="card shadow mb-4">
            <div class="card-body text-center">
              <?php
              $query = "SELECT * FROM accounts WHERE name='$name'";
              $result = mysqli_query($con, $query);

              if ($result) {
                if (mysqli_num_rows($result)) {
                  while ($row = mysqli_fetch_array($result)) {
                    $image = $row['picture'];
                  }
                }
              }
              ?>
              <img src="../img/<?php echo $image; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
              <h5 class="my-3"> <?php echo $name ?> </h5>

              <div class="container">
                <div class="row">
                  <div class="col">
                    <i class="fa-solid fa-ranking-star"></i>
                    <p class="text-muted mb-1"> <?php echo $rank ?> </p>
                  </div>
                  <div class="col">
                    <i class="fa-solid fa-clipboard"></i>
                    <p class="text-muted mb-1"> <?php echo $goals ?></p>
                  </div>
                  <div class="col">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="text-muted mb-1"> <?php echo $da_count ?></p>
                  </div>
                </div>
                <div class="row py-3">
                  <div class="col">
                    <p class="mb-4"><span class="text-primary font-italic me-1"> Rewards Eligibility <br> This Month:
                        <?php echo $monthly_eligibility ?> <br> This Year: <?php echo $annual_eligibility ?> </span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center mb-2">
              </div>
            </div>
          </div>

        </div>
        <div class="col-lg">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary"> Performance </h6>
            </div>
            <div class="card-body">

              <canvas id="performance_chart" height="161rem"></canvas>
              <script>
                new Chart(document.getElementById("performance_chart"), {
                  type: 'line',
                  data: {
                    labels: [<?php chart_get_days() ?>],
                    datasets: [{
                      data: [<?php chart_daily_score_QA($emp_id) ?>],
                      label: "QA",
                      borderColor: "#3e95cd",
                      fill: false
                    }, {
                      data: [<?php chart_daily_score_CPH($emp_id) ?>],
                      label: "CPH",
                      borderColor: "#8e5ea2",
                      fill: false
                    }, {
                      data: [<?php chart_daily_score_ATT($emp_id) ?>],
                      label: "ATT",
                      borderColor: "#3cba9f",
                      fill: false
                    }, {
                      data: [],
                      label: "PERF",
                      borderColor: "#e8c3b9",
                      fill: false


                    }]
                  },

                });
              </script>

            </div>
          </div>

        </div>
      </div>

    </div>
    </div>
  </section>




</body>

<?php include '../includes/script.php' ?>

</html>