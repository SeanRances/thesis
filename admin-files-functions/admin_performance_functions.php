<?php
function choice_one_display_table($day, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $type = "day";
        $emp_id = $row['emp_id'];
        $check_perf = $type."||".$emp_id."||".$day."||".$month."||".$year;
        $a = $row['emp_att'];
        $b = $a * 100;
        $att_score = score_att($b);
        echo "<tr>
              <td>" . $row['emp_fullname'] . "</td>
              <td>" . $row['emp_QA'] . "</td>
              <td>" . $row['emp_CPH'] . "</td>
              <td>" . $row['emp_att'] . "</td>
              <td>" . $row['ds_QA'] . "</td>
              <td>" . $row['ds_CPH'] . "</td>
              <td>" . $att_score . "</td>
              <td>" . $row['emp_perf'] . "</td>
              <td>" . $row['perf_comment'] . "</td>
              <td><a class='btn btn-secondary' href='admin_view_emp_perf.php?post=" . $check_perf . "'><span>View Performance</span></a>
              <a class='btn btn-secondary' href='admin_view_calculation.php?post=" . $check_perf . "'><span>Show Calculation</span></a></td>
              </tr>";

      }
    }
  }
}

function table_check()
{
  include '../includes/connection.php';
  $checker = 0;
  $query = "SELECT * FROM performance_record";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $checker = 1;
      return $checker;
    }
    else
    {
      return $checker;
    }
  }
}

function choice_checker_one($choice)
{
  include '../includes/connection.php';

  return $choice;
}

function choice_checker($choice)
{
  include '../includes/connection.php';

  if($choice == 1)
  {
    choice_one();
  }
}

function choice_one()
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $query = "SELECT * FROM performance_record ORDER BY id DESC limit 1";
    $result = mysqli_query($con,$query);

    if($result)
    {
      if(mysqli_num_rows($result))
      {
        while($row = mysqli_fetch_array($result))
        {
          $day = $row['record_day'];
          $month = $row['record_month'];
          $year = $row['record_year'];
          choice_one_display_table($day, $month, $year);
        }
      }
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

function choice_two($day, $month, $year)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year'";
    $result = mysqli_query($con,$query);
    if($result)
    {
      if(mysqli_num_rows($result))
      {
        while($row = mysqli_fetch_array($result))
        {
          $type = "day";
          $emp_id = $row['emp_id'];
          $check_perf = $type."||".$emp_id."||".$day."||".$month."||".$year;
          $a = $row['emp_att'];
          $b = $a * 100;
          $att_score = score_att($b);
          echo "<tr>
                <td>" . $row['emp_fullname'] . "</td>
                <td>" . $row['emp_QA'] . "</td>
                <td>" . $row['emp_CPH'] . "</td>
                <td>" . $row['emp_att'] . "</td>
                <td>" . $row['ds_QA'] . "</td>
                <td>" . $row['ds_CPH'] . "</td>
                <td>" . $att_score . "</td>
                <td>" . $row['emp_perf'] . "</td>
                <td>" . $row['perf_comment'] . "</td>
                <td><a class='btn btn-secondary' href='admin_view_emp_perf.php?post=" . $check_perf . "'><span>View Performance</span></a>
                <a class='btn btn-secondary' href='admin_view_calculation.php?post=" . $check_perf . "'><span>Show Calculation</span></a></td>
                </tr>";
        }
      }
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}


function choice_three($month, $year)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $emp_query = "SELECT * FROM employees";
    $emp_result = mysqli_query($con,$emp_query);

    if($emp_result)
    {
      if(mysqli_num_rows($emp_result))
      {
        while($row = mysqli_fetch_array($emp_result))
        {
          $name = $row['emp_fullname'];
          $emp_id = $row['emp_id'];
          $checker = 0;
          $checker = choice_three_record_check($name, $month, $year); //check if employee has records in table according to date given
          if($checker == 1)
          {
            choice_three_display($name, $month, $year, $emp_id);
          }
        }
      }
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

function choice_three_record_check($name, $month, $year)
{
    include '../includes/connection.php';
    $checker = 0;

    $query = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
    $result = mysqli_query($con, $query);

    if($result)
    {
        if(mysqli_num_rows($result)) //if the user has recrods
        {
            $checker = 1;
        }
        else //if the user does not have records
        {
            $checker = 0;
        }
    }
    return $checker;
}

function choice_three_display($name, $month, $year, $emp_id)
{
    //get the sum of each kpi
    $QA_sum = choice_three_QAsum($name, $month, $year);
    $CPH_sum = choice_three_CPHsum($name, $month, $year);
    $ATT_sum = choice_three_ATTsum($name, $month, $year);

    //get number of records
    $num_records = choice_three_recordCount($name, $month, $year);

    //get the average of each kpi
    $a = $QA_sum / $num_records;
    $b = $CPH_sum / $num_records;
    $c = $ATT_sum  / $num_records;
    $d = $c * 100;

    //convert the average score to transmuted score
    $result_QA = score_qa($a);
    $result_CPH = score_cph($b);
    $result_ATT = score_att($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    $type = "month";
    $check_perf = $type."||".$emp_id."||".$month."||".$year;

    echo "<tr>
          <td>" . $name . "</td>
          <td>" . number_format($a, 2, '.', '') . "</td>
          <td>" . number_format($b, 2, '.', '') . "</td>
          <td>" . $d . "%</td>
          <td>" . $result_QA . "</td>
          <td>" . $result_CPH . "</td>
          <td>" . $result_ATT . "</td>
          <td>" . $finals . "</td>
          <td>" . $comment . "</td>
          <td><a class='btn btn-secondary' href='admin_view_emp_perf.php?post=" . $check_perf . "'><span>View Performance</span></a>
          <a class='btn btn-secondary' href='admin_view_calculation.php?post=" . $check_perf . "'><span>Show Calculation</span></a></td>
          </tr>";
}

function choice_three_QAsum($name, $month, $year)
{
    include '../includes/connection.php';
      $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $QA_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $a = $row['emp_QA'];
                $QA_sum = $QA_sum + $a;
                $count++;
          }
        }
      }
      return $QA_sum;
}

function choice_three_CPHsum($name, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $CPH_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $b = $row['emp_CPH'];
                $CPH_sum = $CPH_sum + $b;
                $count++;
          }
        }
      }
      return $CPH_sum;
}

function choice_three_ATTsum($name, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $ATT_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $c = $row['emp_att'];
                $ATT_sum = $ATT_sum + $c;
                $count++;
          }
        }
      }
      return $ATT_sum;
}

function choice_three_recordCount($name, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $count = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $count++;
          }
        }
      }
      return $count;
}












function choice_four($year)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $emp_query = "SELECT * FROM employees";
    $emp_result = mysqli_query($con,$emp_query);

    if($emp_result)
    {
      if(mysqli_num_rows($emp_result))
      {
        while($row = mysqli_fetch_array($emp_result))
        {
          $name = $row['emp_fullname'];
          $emp_id = $row['emp_id'];
          $checker = 0;
          $checker = choice_four_record_check($name, $year); //check if employee has records in table according to date given
          if($checker == 1)
          {
            choice_four_display($name, $year, $emp_id);
          }
        }
      }
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

function choice_four_record_check($name, $year)
{
    include '../includes/connection.php';
    $checker = 0;

    $query = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
    $result = mysqli_query($con, $query);

    if($result)
    {
        if(mysqli_num_rows($result)) //if the user has recrods
        {
            $checker = 1;
        }
        else //if the user does not have records
        {
            $checker = 0;
        }
    }
    return $checker;
}

function choice_four_display($name, $year, $emp_id)
{
    //get the sum of each kpi
    $QA_sum = choice_four_QAsum($name, $year);
    $CPH_sum = choice_four_CPHsum($name, $year);
    $ATT_sum = choice_four_ATTsum($name, $year);

    //get number of records
    $num_records = choice_four_recordCount($name, $year);

    //get the average of each kpi
    $a = $QA_sum / $num_records;
    $b = $CPH_sum / $num_records;
    $c = $ATT_sum  / $num_records;
    $d = $c * 100;

    //convert the average score to transmuted score
    $result_QA = score_qa($a);
    $result_CPH = score_cph($b);
    $result_ATT = score_att($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    $type = "year";
    $check_perf = $type."||".$emp_id."||".$year;
    echo "<tr>
          <td>" . $name . "</td>
          <td>" . number_format($a, 2, '.', '') . "</td>
          <td>" . number_format($b, 2, '.', '') . "</td>
          <td>" . $d . "%</td>
          <td>" . $result_QA . "</td>
          <td>" . $result_CPH . "</td>
          <td>" . $result_ATT . "</td>
          <td>" . $finals . "</td>
          <td>" . $comment . "</td>
          <td><a class='btn btn-secondary' href='admin_view_emp_perf.php?post=" . $check_perf . "'><span>View Performance</span></a>
          <a class='btn btn-secondary' href='admin_view_calculation.php?post=" . $check_perf . "'><span>Show Calculation</span></a></td>
          </tr>";
}

function choice_four_QAsum($name, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $QA_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $a = $row['emp_QA'];
                $QA_sum = $QA_sum + $a;
                $count++;
          }
        }
      }
      return $QA_sum;
}

function choice_four_CPHsum($name, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $CPH_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $b = $row['emp_CPH'];
                $CPH_sum = $CPH_sum + $b;
                $count++;
          }
        }
      }
      return $CPH_sum;
}

function choice_four_ATTsum($name, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $sum = 0;
          $count = 0;
          $ATT_sum = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $c = $row['emp_att'];
                $ATT_sum = $ATT_sum + $c;
                $count++;
          }
        }
      }
      return $ATT_sum;
}

function choice_four_recordCount($name, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);

      if($search_result)
      {
        if(mysqli_num_rows($search_result))
        {
          $count = 0;
          while($row = mysqli_fetch_array($search_result))
          {
                $count++;
          }
        }
      }
      return $count;
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

//calculate performance score
function performance_score($result_QA, $result_CPH, $result_ATT)
{
    $final = ((($result_QA * 0.5) + ($result_CPH * 0.5)) * 0.9) + ($result_ATT * 0.1);
    return $final;
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
