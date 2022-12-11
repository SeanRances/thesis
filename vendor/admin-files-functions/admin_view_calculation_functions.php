<?php
function display_day($emp_id, $day, $month, $year, $emp_name)
{
  include '../includes/connection.php';
  ?>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col-lg">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <?php
                echo $emp_name . "'s performance for " . $month . " " . $day . ", " . $year;
             ?>
            <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" width="100%" cellspacing="0" role="grid">
                <thead>
                  <tr class="bg-light">
                    <th class="border-top-0"> Computation Process</th>
                    <th class="border-top-0"> QA </th>
                    <th class="border-top-0"> CPH </th>
                    <th class="border-top-0"> ATT </th>
                  </tr>
                </thead>
                <tbody>
                  <?php day_option($emp_id, $day, $month, $year, $emp_name)?>
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

function day_option($emp_id, $day, $month, $year, $emp_name)
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
        $QA = $row['emp_QA'];
        $CPH = $row['emp_CPH'];
        $ATT = $row['emp_att'];
        echo "<tr>
              <td>Key Performance Indicators</td>
              <td>" . $row['emp_QA'] . "</td>
              <td>" . $row['emp_CPH'] . "</td>
              <td>" . $row['emp_att'] . "</td>
              </tr>";
      }
      $result_QA = score_qa($QA);
      $result_CPH = score_cph($CPH);
      $result_ATT = score_att($ATT);

      $final = ((($result_QA * 0.5) + ($result_CPH * 0.5)) * 0.9) + ($result_ATT * 0.1);

      $comment = performance_comment($final);
      echo "<tr>
            <td>Get transmuted score</td>
            <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
            <td>4 - 7 and above <br> 3 - 6 to 6.99 <br> 2 - 5 to 5.99 <br> 1 - below 5</td>
            <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
            </tr>

            <tr>
            <td>Transmuted Score</td>
            <td>" . $result_QA . "</td>
            <td>" . $result_CPH . "</td>
            <td>" . $result_ATT . "</td>
            </tr>

            <tr>
            <td>Calculate Performance Score</td>
            <td colspan='3'>(((" . $result_QA . "* 0.5) + (" . $result_CPH . "* 0.5)) * 0.9) + (" . $result_ATT . "* 0.1) = " . $final . "</td>
            </tr>

            <tr>
            <td>Performance Criteria</td>
            <td colspan='3'> Surpassed - 3.50 and above <br> Achieved - 2 to 3.49 <br> Underachieved - 2 and below </td>
            </tr>

            <tr>
            <td>Performance Comment</td>
            <td colspan='3'>" . $comment . "</td>
            </tr>";
    }
  }
}

function display_month($emp_id, $month, $year, $emp_name)
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
                    <th class="border-top-0"> Computation Process</th>
                    <th class="border-top-0"> QA Computation</th>
                    <th class="border-top-0"> CPH Computation</th>
                    <th class="border-top-0"> ATT Computation</th>
                    <th class="border-top-0"> Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php month_option($emp_id, $month, $year);?>
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

function month_option($emp_id, $month, $year)
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

        $QA_sum = 0;
        $CPH_sum = 0;
        $ATT_sum = 0;
        $loop_counter++;
        if($loop_counter == $row_count)
        {
          array_multisort($day_array, SORT_ASC, SORT_NUMERIC, $emp_QA_array, $emp_CPH_array, $emp_ATT_array, $ds_QA_array, $ds_CPH_array, $emp_perf_array, $perf_comment_array);

          for($x = 0; $x < $loop_counter; $x++)
          {
            $date = $month . "-" . $day_array[$x] . "-" . $year;
            $QA_sum = $QA_sum + $emp_QA_array[$x];
            $CPH_sum = $CPH_sum + $emp_CPH_array[$x];
            $ATT_sum = $ATT_sum + $emp_ATT_array[$x];
            if($x == 0)
            {
              echo "<tr>
              <td>Get the sum of each KPI</td>
              <td>" . $emp_QA_array[$x] . "</td>
              <td>" . $emp_CPH_array[$x] . "</td>
              <td>" . $emp_ATT_array[$x] . "</td>
              <td>" . $date . "</td>
              </tr>
              <tr>
              <td></td>
              <td colspan='3' align='center'>+</td>
              </tr>";
            }
            else
            {
              if($x == $loop_counter - 1)
              {
                echo "<tr>
                <td></td>
                <td>" . $emp_QA_array[$x] . "</td>
                <td>" . $emp_CPH_array[$x] . "</td>
                <td>" . $emp_ATT_array[$x] . "</td>
                <td>" . $date . "</td>
                </tr>
                <tr>
                <td></td>
                <td colspan='3' align='center'>=</td>
                </tr>";
              }
              else
              {
                echo "<tr>
                <td></td>
                <td>" . $emp_QA_array[$x] . "</td>
                <td>" . $emp_CPH_array[$x] . "</td>
                <td>" . $emp_ATT_array[$x] . "</td>
                <td>" . $date . "</td>
                </tr>
                <tr>
                <td></td>
                <td colspan='3' align='center'>+</td>
                </tr>";
              }
            }

          }
          $a = $QA_sum/$ATT_sum;
          $b = $CPH_sum/$ATT_sum;
          $c = 100 * ($ATT_sum/$ATT_sum);

          $result_QA = score_qa($a);
          $result_CPH = score_cph($b);
          $result_ATT = score_att($c);

          $final = ((($result_QA * 0.5) + ($result_CPH * 0.5)) * 0.9) + ($result_ATT * 0.1);

          $comment = performance_comment($final);
          echo "<tr>
                <td>Sum of each KPI</td>
                <td>" . $QA_sum . "</td>
                <td>" . $CPH_sum . "</td>
                <td>" . $ATT_sum . "</td>
                <td></td>
                </tr>

                <tr>
                <td>Divide by number of records</td>
                <td>" . $QA_sum . " / " . $ATT_sum . " = " . number_format($QA_sum/$ATT_sum, 2, '.', '') . "</td>
                <td>" . $CPH_sum . " / " . $ATT_sum . " = " . number_format($CPH_sum/$ATT_sum, 2, '.', '') .  "</td>
                <td>" . $ATT_sum . " / " . $ATT_sum . " = " . number_format($ATT_sum/$ATT_sum, 2, '.', '') .  "</td>
                <td></td>
                </tr>

                <tr>
                <td>Get transmuted score</td>
                <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
                <td>4 - 7 and above <br> 3 - 6 to 6.99 <br> 2 - 5 to 5.99 <br> 1 - below 5</td>
                <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
                <td></td>
                </tr>

                <tr>
                <td>Transmuted Score</td>
                <td>" . $result_QA . "</td>
                <td>" . $result_CPH . "</td>
                <td>" . $result_ATT . "</td>
                <td></td>
                </tr>

                <tr>
                <td>Calculate Performance Score</td>
                <td colspan='3'>(((" . $result_QA . "* 0.5) + (" . $result_CPH . "* 0.5)) * 0.9) + (" . $result_ATT . "* 0.1) = " . $final . "</td>
                <td></td>
                </tr>

                <tr>
                <td>Performance Criteria</td>
                <td colspan='3'> Surpassed - 3.50 and above <br> Achieved - 2 to 3.49 <br> Underachieved - 2 and below </td>
                </tr>

                <tr>
                <td>Performance Comment</td>
                <td colspan='3'>" . $comment . "</td>
                <td></td>
                </tr>";
        }
      }
    }
  }
}

function display_year($emp_id, $year, $emp_name)
{
  include '../includes/connection.php';

  $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

  ?>
  <div class="container-fluid py-5">
    <div class="row">
      <div class="col-lg">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <div class="col-md-3 col-sm-4 col-xs-6 ms-auto">

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" width="100%" cellspacing="0" role="grid">
                <thead>
                  <tr class="bg-light">
                    <th class="border-top-0"> Computation Process</th>
                    <th class="border-top-0"> QA </th>
                    <th class="border-top-0"> CPH </th>
                    <th class="border-top-0"> ATT </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $month_holder = [];
                  $QA_sum_holder = [];
                  $CPH_sum_holder = [];
                  $ATT_sum_holder = [];
                  $record_count = 0;
                    for($x = 0; $x < 12; $x++)
                    {
                      $year_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month[$x]' AND record_year = '$year'";
                      $year_performance_result = mysqli_query($con,$year_performance_query);

                      if($year_performance_result)
                      {
                        if(mysqli_num_rows($year_performance_result))
                        {
                          $month_holder[] = $month[$x];
                          $QA_holder = 0;
                          $CPH_holder = 0;
                          $ATT_holder = 0;
                          while($row = mysqli_fetch_array($year_performance_result))
                          {
                            $QA_holder = $QA_holder + $row['emp_QA'];
                            $CPH_holder = $CPH_holder + $row['emp_CPH'];
                            $ATT_holder = $ATT_holder + $row['emp_att'];
                            $record_count++;
                          }
                          $QA_sum_holder[] = $QA_holder;
                          $CPH_sum_holder[] = $CPH_holder;
                          $ATT_sum_holder[] = $ATT_holder;
                        }
                        else
                        {
                        }
                      }
                    }
                    $month_count = count($month_holder);

                    $QA_sum_year = 0;
                    $CPH_sum_year = 0;
                    $ATT_sum_year = 0;
                    for($x = 0; $x < $month_count; $x++)
                    {
                      $QA_sum_year = $QA_sum_year + $QA_sum_holder[$x];
                      $CPH_sum_year = $CPH_sum_year + $CPH_sum_holder[$x];
                      $ATT_sum_year = $ATT_sum_year + $ATT_sum_holder[$x];
                      echo "<tr>
                            <td>KPI sum for the month of " . $month_holder[$x] . "</td>
                            <td>" . $QA_sum_holder[$x] . "</td>
                            <td>" . $CPH_sum_holder[$x] . "</td>
                            <td>" . $ATT_sum_holder[$x] . "</td>
                            </tr>";
                    }

                    $a = $QA_sum_year/$record_count;
                    $b = $CPH_sum_year/$record_count;
                    $c = 100 * ($ATT_sum_year/$record_count);

                    $result_QA = score_qa($a);
                    $result_CPH = score_cph($b);
                    $result_ATT = score_att($c);

                    $final = ((($result_QA * 0.5) + ($result_CPH * 0.5)) * 0.9) + ($result_ATT * 0.1);

                    $comment = performance_comment($final);

                    echo "<tr>
                          <td>KPI sum for the year</td>
                          <td>" . $QA_sum_year . "</td>
                          <td>" . $CPH_sum_year . "</td>
                          <td>" . $ATT_sum_year . "</td>
                          </tr>

                          <tr>
                          <td>Divide by number of records</td>
                          <td>" . $QA_sum_year . " / " . $record_count . " = " . number_format($QA_sum_year/$record_count, 2, '.', '') . "</td>
                          <td>" . $CPH_sum_year . " / " . $record_count . " = " . number_format($CPH_sum_year/$record_count, 2, '.', '') .  "</td>
                          <td>" . $ATT_sum_year . " / " . $record_count . " = " . number_format($ATT_sum_year/$record_count, 2, '.', '') .  "</td>
                          </tr>

                          <tr>
                          <td>Get transmuted score</td>
                          <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
                          <td>4 - 7 and above <br> 3 - 6 to 6.99 <br> 2 - 5 to 5.99 <br> 1 - below 5</td>
                          <td>4 - 97% and above <br> 3 - 95% to 96.99% <br> 2 - 92% to 94.99% <br> 1 - 91.99% and below</td>
                          </tr>

                          <tr>
                          <td>Transmuted Score</td>
                          <td>" . $result_QA . "</td>
                          <td>" . $result_CPH . "</td>
                          <td>" . $result_ATT . "</td>
                          </tr>

                          <tr>
                          <td>Calculate Performance Score</td>
                          <td colspan='3'>(((" . $result_QA . "* 0.5) + (" . $result_CPH . "* 0.5)) * 0.9) + (" . $result_ATT . "* 0.1) = " . $final . "</td>
                          </tr>

                          <tr>
                          <td>Performance Criteria</td>
                          <td colspan='3'> Surpassed - 3.50 and above <br> Achieved - 2 to 3.49 <br> Underachieved - 2 and below </td>
                          </tr>

                          <tr>
                          <td>Performance Comment</td>
                          <td colspan='3'>" . $comment . "</td>
                          </tr>";
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php

  for($x = 0; $x < 12; $x++)
  {
    $year_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month[$x]' AND record_year = '$year'";
    $year_performance_result = mysqli_query($con,$year_performance_query);

    if($year_performance_result)
    {
      if(mysqli_num_rows($year_performance_result))
      {
        year_option($emp_id, $month[$x], $year, $emp_name);
      }
      else
      {
      }
    }
  }
}

function year_option($emp_id, $month, $year, $emp_name)
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
                    <th class="border-top-0"> Computation Process</th>
                    <th class="border-top-0"> QA Computation</th>
                    <th class="border-top-0"> CPH Computation</th>
                    <th class="border-top-0"> ATT Computation</th>
                    <th class="border-top-0"> Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php month_option($emp_id, $month, $year);?>
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

function score_qa($a)
{
    if($a < 91.99)
    {
      $result_QA = 1;
      return $result_QA;
    }
    elseif($a >= 91.99 && $a < 94.99)
    {
      $result_QA = 2;
      return $result_QA;
    }
    elseif($a >= 94.99 && $a < 96.99)
    {
      $result_QA = 3;
      return $result_QA;
    }
    elseif($a >= 96.99)
    {
      $result_QA = 4;
      return $result_QA;
    }
}

//calculate for equivalent cph score
function score_cph($b)
{
    if($b < 5)
      {
        $result_CPH = 1;
        return $result_CPH;
      }
      elseif($b >= 5 && $b < 5.99)
      {
        $result_CPH = 2;
        return $result_CPH;
      }
      elseif($b >= 5.99 && $b < 6.99)
      {
        $result_CPH = 3;
        return $result_CPH;
      }
      elseif($b >= 6.99)
      {
        $result_CPH = 4;
        return $result_CPH;
      }
}


//calculate for equivalent att score
function score_att($c)
{
    if($c < 92)
      {
        $result_ATT = 1;
        return $result_ATT;
      }
      elseif($c >= 92 && $c < 95)
      {
        $result_ATT = 2;
        return $result_ATT;
      }
      elseif($c >= 95 && $c < 97)
      {
        $result_ATT = 3;
        return $result_ATT;
      }
      elseif($c >= 97)
      {
        $result_ATT = 4;
        return $result_ATT;
      }
}

function performance_comment($finals)
{
    if($finals < 2)
    {
      $comment = "Underachieved";
      return $comment;
    }
    elseif($finals >= 2 && $finals < 3.50)
    {
      $comment = "Achieved";
      return $comment;
    }
    elseif($finals >= 3.50)
    {
      $comment = "Surpassed";
      return $comment;
    }
}
?>
