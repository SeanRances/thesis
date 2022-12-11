<?php

function chart_get_days()
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $day_sort = [];
  $count = 0;

  $query = "SELECT DISTINCT record_day FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        echo $day_sort[$x] . ", ";
      }
    }
  }
}
function chart_daily_score_QA($emp_id)
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $day_sort = [];
  $count = 0;

  $query = "SELECT DISTINCT record_day FROM performance_record WHERE record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        //get all the days first to sort them
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_daily_compute_QA($day_sort[$x], $month, $year, $count, $max_count, $emp_id);
      }
    }
  }
}

function chart_daily_compute_QA($day, $month, $year, $count, $max_count, $emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $result_QA = $row['ds_QA'];
      }
    }
  }
  if($count < $max_count)
  {
    echo $result_QA . ", ";
  }
  else
  {
    echo $result_QA;
  }
}


function chart_daily_score_CPH($emp_id)
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $day_sort = [];
  $count = 0;

  $query = "SELECT DISTINCT record_day FROM performance_record WHERE record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        //get all the days first to sort them
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_daily_compute_CPH($day_sort[$x], $month, $year, $count, $max_count, $emp_id);
      }
    }
  }
}

function chart_daily_compute_CPH($day, $month, $year, $count, $max_count, $emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $result_CPH = $row['ds_CPH'];
      }
    }
  }
  if($count < $max_count)
  {
    echo $result_CPH . ", ";
  }
  else
  {
    echo $result_CPH;
  }
}

function chart_daily_score_ATT($emp_id)
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $day_sort = [];
  $count = 0;

  $query = "SELECT DISTINCT record_day FROM performance_record WHERE record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        //get all the days first to sort them
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_daily_compute_ATT($day_sort[$x], $month, $year, $count, $max_count, $emp_id);
      }
    }
  }
}

function chart_daily_compute_ATT($day, $month, $year, $count, $max_count, $emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month = '$month' AND record_year = '$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $result_ATT = $row['emp_att'];
      }
    }
  }

  if($result_ATT == 1)
  {
    $result = 4;
  }
  else
  {
    $result = 1;
  }
  if($count < $max_count)
  {
    echo $result . ", ";
  }
  else
  {
    echo $result;
  }
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
    if($c < 91.99)
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_goals($emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM employee_goals WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);
  $count = 0;
  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $count++;
      }
    }
  }
  return $count;
}

function get_count_da($emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM da_table WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);
  $count = 0;
  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $count++;
      }
    }
  }
  return $count;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function check_rank($emp_name, $month, $year)
{
  include '../includes/connection.php';
  $ar1 = [];
  $ar2 = [];
  $missing = [];
  $display = 0;
  $zero_record = 0;
  $query = "SELECT * FROM employees";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $checker = 0;
        $checker = choice_one_check($row['emp_fullname'], $month, $year);
        if($checker == 1)
        {
          $ar1[] = choice_one_finals($row['emp_fullname'], $month, $year);
          $ar2[] = $row['emp_fullname'];
          $display = 1;
        }
        else
        {
          $missing[] = $row['emp_fullname'];
          $zero_record = 1;
        }
      }
    }
  }

  if($display == 1)
  {
    array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2);
    $arrlength = count($ar1);
    for($x = 0; $x < $arrlength; $x++)
    {
      if($ar2[$x] == $emp_name)
      {
        return $x+1;
      }
    }
  }
}

function choice_one_check($name, $month, $year)
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


function choice_one_finals($name, $month, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = choice_one_emp_perf_sum($name, $month, $year);

  //get number of records
  $num_records = choice_one_recordCount($name, $month, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  return $emp_perf_quotient;
}

function choice_one_emp_perf_sum($name, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $emp_perf_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_perf'];
        $emp_perf_sum = $emp_perf_sum + $a;
        $count++;
      }
    }
  }
  return $emp_perf_sum;

}

function choice_one_recordCount($name, $month, $year)
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function get_empID($account_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM employees WHERE account_id = '$account_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $emp_id = $row['emp_id'];
        return $emp_id;
      }
    }
  }

}

function get_name($emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $name = $row['emp_fullname'];
        return $name;
      }
    }
  }
}

function eligibility_checker_month($emp_id)
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');

  $query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  $checker = record_check($emp_id, $month, $year);
  if($checker == 1)
  {
    $eligibility_status = eligibility_result($emp_id, $month, $year);
    return $eligibility_status;
  }
  else
  {
    return "No records for this month yet.";
  }

}

function record_check($emp_id, $month, $year)
{
    include '../includes/connection.php';
    $checker = 0;

    $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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
function eligibility_result($emp_id, $month, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = emp_perf_sum($emp_id, $month, $year);

  //get number of records
  $num_records = recordCount($emp_id, $month, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  //calculate for comment
  $comment = performance_comment($emp_perf_quotient);

  return $comment;
}

function emp_perf_sum($emp_id, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month='$month' AND record_year='$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $emp_perf_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_perf'];
        $emp_perf_sum = $emp_perf_sum + $a;
        $count++;
      }
    }
  }
  return $emp_perf_sum;

}


function recordCount($emp_id, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

function eligibility_checker_year($emp_id)
{
  include '../includes/connection.php';
  $year = date('Y');

  $query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  $checker = record_check_year($emp_id, $year);
  if($checker == 1)
  {
    $eligibility_status = eligibility_result_year($emp_id, $year);
    return $eligibility_status;
  }
  else
  {
    return "No records for this year yet.";
  }

}

function record_check_year($emp_id, $year)
{
    include '../includes/connection.php';
    $checker = 0;

    $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
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
function eligibility_result_year($emp_id, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = emp_perf_sum_year($emp_id, $year);

  //get number of records
  $num_records = recordCount_year($emp_id, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  //calculate for comment
  $comment = performance_comment($emp_perf_quotient);

  return $comment;
}

function emp_perf_sum_year($emp_id, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year='$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $emp_perf_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_perf'];
        $emp_perf_sum = $emp_perf_sum + $a;
        $count++;
      }
    }
  }
  return $emp_perf_sum;

}


function recordCount_year($emp_id, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
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

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

function get_QA($emp_id)
{
  $month = date('F');
  $year = date('Y');
  include '../includes/connection.php';

  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month='$month' AND record_year='$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $ATT_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $c = $row['emp_QA'];
        $ATT_sum = $ATT_sum + $c;
        $count++;
      }
      $result = ($ATT_sum / $count);
      return $result;
    }
  }


}

function get_CPH($emp_id)
{
  $month = date('F');
  $year = date('Y');
  include '../includes/connection.php';

  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month='$month' AND record_year='$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $ATT_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $c = $row['emp_CPH'];
        $ATT_sum = $ATT_sum + $c;
        $count++;
      }
      $b = ($ATT_sum / $count);
      $result = score_cph($b);
      $result = ($result / 4) * 100;
      return $result;
    }
  }


}



function get_ATT($emp_id)
{
  $month = date('F');
  $year = date('Y');
  include '../includes/connection.php';

  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month='$month' AND record_year='$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $sum = 0;
      $count = 0;
      $ATT_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $c = $row['emp_att'];
        $ATT_sum = $ATT_sum + $c;
        $count++;
      }
      $result = ($ATT_sum / $count) * 100;
      return $result;
    }
  }


}

 ?>
