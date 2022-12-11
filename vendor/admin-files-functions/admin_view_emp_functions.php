<?php
function day_performance_output($emp_id, $day, $month, $year)
{
  include '../includes/connection.php';

  $day_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $day_performance_result = mysqli_query($con,$day_performance_query);

  if($day_performance_result)
  {
    if(mysqli_num_rows($day_performance_result))
    {
      while($row = mysqli_fetch_array($day_performance_result))
      {
        echo "<tr>
              <td>" . $row['emp_fullname'] . "</td>
              <td>" . $row['emp_QA'] . "</td>
              <td>" . $row['emp_CPH'] . "</td>
              <td>" . $row['emp_att'] . "</td>
              <td>" . $row['ds_QA'] . "</td>
              <td>" . $row['ds_CPH'] . "</td>
              <td>4</td>
              <td>" . $row['emp_perf'] . "</td>
              <td>" . $row['perf_comment'] . "</td>
              </tr>";
      }
    }
  }
}

function month_performance_output($emp_id, $month, $year)
{
  include '../includes/connection.php';

  $day_array = [];
  $emp_QA_array = [];
  $emp_CPH_array = [];
  $emp_ATT_array = [];
  $ds_QA_array = [];
  $ds_CPH_array = [];
  $emp_perf_array = [];
  $perf_comment_array = [];
  $row_count = 0;
  $loop_counter = 0;
  $month_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
  $month_performance_result = mysqli_query($con,$month_performance_query);

  if($month_performance_result)
  {
    if(mysqli_num_rows($month_performance_result))
    {
      $row_count = mysqli_num_rows($month_performance_result);
      while($row = mysqli_fetch_array($month_performance_result))
      {
        $day_array[] = $row['record_day'];
        $emp_QA_array[] = $row['emp_QA'];
        $emp_CPH_array[] = $row['emp_CPH'];
        $emp_ATT_array[] = $row['emp_att'] ;
        $ds_QA_array[] = $row['ds_QA'];
        $ds_CPH_array[] = $row['ds_CPH'];
        $emp_perf_array[] = $row['emp_perf'];
        $perf_comment_array[] = $row['perf_comment'];
        $loop_counter++;
        if($loop_counter == $row_count)
        {
          array_multisort($day_array, SORT_ASC, SORT_NUMERIC, $emp_QA_array, $emp_CPH_array, $emp_ATT_array, $ds_QA_array, $ds_CPH_array, $emp_perf_array, $perf_comment_array);
          for($x = 0; $x < $loop_counter; $x++)
          {
            $date = $month . "-" . $day_array[$x] . "-" . $year;
            echo "<tr>
                  <td>" . $row['emp_fullname'] . "</td>
                  <td>" . $emp_QA_array[$x] . "</td>
                  <td>" . $emp_CPH_array[$x] . "</td>
                  <td>" . $emp_ATT_array[$x] . "</td>
                  <td>" . $ds_QA_array[$x] . "</td>
                  <td>" . $ds_CPH_array[$x] . "</td>
                  <td>4</td>
                  <td>" . $emp_perf_array[$x] . "</td>
                  <td>" . $perf_comment_array[$x] . "</td>
                  <td>" . $date . "</td>
                  </tr>";
          }
        }
      }
    }
  }
}

function year_performance_output($emp_id, $year, $emp_name)
{
  include '../includes/connection.php';

  $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  for($x = 0; $x < 12; $x++)
  {
    $year_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month[$x]' AND record_year = '$year'";
    $year_performance_result = mysqli_query($con,$year_performance_query);

    if($year_performance_result)
    {
      if(mysqli_num_rows($year_performance_result))
      {
        print_out_performance_yearly($emp_id, $month[$x], $year, $emp_name);
      }
      else
      {
      }
    }
  }
}

function print_out_performance_yearly($emp_id, $month, $year, $emp_name)
{
  include '../includes/connection.php';
  ?>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col-lg">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <?php
                echo $emp_name . "'s performance for " . $month . " " . $year;
             ?>
            <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">

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
                    <th class="border-top-0"> Date </th>
                  </tr>
                </thead>
                <tbody>
                  <?php output_year($emp_id, $month, $year);?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
}

function output_year($emp_id, $month, $year)
{
  include '../includes/connection.php';

  $day_array = [];
  $emp_QA_array = [];
  $emp_CPH_array = [];
  $emp_ATT_array = [];
  $ds_QA_array = [];
  $ds_CPH_array = [];
  $emp_perf_array = [];
  $perf_comment_array = [];
  $row_count = 0;
  $loop_counter = 0;
  $month_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
  $month_performance_result = mysqli_query($con,$month_performance_query);

  if($month_performance_result)
  {
    if(mysqli_num_rows($month_performance_result))
    {
      $row_count = mysqli_num_rows($month_performance_result);
      while($row = mysqli_fetch_array($month_performance_result))
      {
        $day_array[] = $row['record_day'];
        $emp_QA_array[] = $row['emp_QA'];
        $emp_CPH_array[] = $row['emp_CPH'];
        $emp_ATT_array[] = $row['emp_att'] ;
        $ds_QA_array[] = $row['ds_QA'];
        $ds_CPH_array[] = $row['ds_CPH'];
        $emp_perf_array[] = $row['emp_perf'];
        $perf_comment_array[] = $row['perf_comment'];
        $loop_counter++;
        if($loop_counter == $row_count)
        {
          array_multisort($day_array, SORT_ASC, SORT_NUMERIC, $emp_QA_array, $emp_CPH_array, $emp_ATT_array, $ds_QA_array, $ds_CPH_array, $emp_perf_array, $perf_comment_array);
          for($x = 0; $x < $loop_counter; $x++)
          {
            $date = $month . "-" . $day_array[$x] . "-" . $year;
            echo "<tr>
                  <td>" . $emp_QA_array[$x] . "</td>
                  <td>" . $emp_CPH_array[$x] . "</td>
                  <td>" . $emp_ATT_array[$x] . "</td>
                  <td>" . $ds_QA_array[$x] . "</td>
                  <td>" . $ds_CPH_array[$x] . "</td>
                  <td>4</td>
                  <td>" . $emp_perf_array[$x] . "</td>
                  <td>" . $perf_comment_array[$x] . "</td>
                  <td>" . $date . "</td>
                  </tr>";
          }
        }
      }
    }
  }
}
?>
