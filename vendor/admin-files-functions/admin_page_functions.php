<?php
function num_employees()
{
  include '../includes/connection.php';
  $query = "SELECT * FROM employees";
  $result = mysqli_query($con,$query);

  if($result)
  {
    $num_employees = mysqli_num_rows($result);
  }
  return $num_employees;
}

function employees_with_da()
{
  include '../includes/connection.php';
  $query = "SELECT DISTINCT emp_id FROM da_table";
  $result = mysqli_query($con,$query);
  if($result)
  {
    $da_employees = mysqli_num_rows($result);
  }
  return $da_employees;
}

function surpassed_employees_today()
{
  include '../includes/connection.php';
  $day = date('j');
  $month = date('F');
  $year = date('Y');
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year' AND perf_comment = 'Surpassed'";
  $result = mysqli_query($con,$query);
  if($result)
  {
      $check = mysqli_num_rows($result);
  }

  if($check == 0)
  {
    $surpassed_employees = "No record uploaded yet.";
    return $surpassed_employees;
  }
  else
  {
    return $check;
  }

}

function achieved_employees_today()
{
  include '../includes/connection.php';
  $day = date('j');
  $month = date('F');
  $year = date('Y');
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year' AND perf_comment = 'Achieved'";
  $result = mysqli_query($con,$query);
  if($result)
  {
      $check = mysqli_num_rows($result);
  }

  if($check == 0)
  {
    $achieved_employees = "No record uploaded yet.";
    return $achieved_employees;
  }
  else
  {
    return $check;
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


///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// CHART FUNCTIONS

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

///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// DAILY QA
function chart_daily_score_QA()
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
        chart_daily_compute_QA($day_sort[$x], $month, $year, $count, $max_count);
      }
    }
  }
}

function chart_daily_compute_QA($day, $month, $year, $count, $max_count)
{
  include '../includes/connection.php';
  $QA_sum = chart_QA_daily($day, $month, $year);
  $num_records = chart_daily_record_count($day, $month, $year);

  $a = $QA_sum / $num_records;

  $result_QA = score_qa($a);

  if($count < $max_count)
  {
    echo $result_QA . ", ";
  }
  else
  {
    echo $result_QA;
  }

}

function chart_daily_record_count($day, $month, $year)
{
    include '../includes/connection.php';
    $count = 0;

    $query = "SELECT * FROM performance_record WHERE record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
    $result = mysqli_query($con, $query);

    if($result)
    {
        if(mysqli_num_rows($result)) //if the user has recrods
        {
            $count = mysqli_num_rows($result);
        }
    }
    return $count;
}


function chart_QA_daily($day, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $QA_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_QA'];
        $QA_sum = $QA_sum + $a;
      }
    }
  }
  return $QA_sum;
}



///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// DAILY CPH
function chart_daily_score_CPH()
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
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_daily_compute_CPH($day_sort[$x], $month, $year, $count, $max_count);
      }
    }
  }
}


function chart_daily_compute_CPH($day, $month, $year, $count, $max_count)
{
  include '../includes/connection.php';
  $CPH_sum = chart_CPH_daily($day, $month, $year);
  $num_records = chart_daily_record_count($day, $month, $year);

  $a = $CPH_sum / $num_records;

  $result_CPH = score_cph($a);

  if($count < $max_count)
  {
    echo $result_CPH . ", ";
  }
  else
  {
    echo $result_CPH;
  }

}


function chart_CPH_daily($day, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $CPH_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_CPH'];
        $CPH_sum = $CPH_sum + $a;
      }
    }
  }
  return $CPH_sum;
}

///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// DAILY ATT

function chart_performance()
{
  include '../includes/connection.php';

  $QA_var = 0;
  $CPH_var = 0;
  $ATT_var = 0;
  $record_number = 0;

  $month = date('F');
  $year = date('Y');

  $performance_query = "SELECT * FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
  $performance_result = mysqli_query($con,$performance_query);

  if($performance_result)
  {
    if(mysqli_num_rows($performance_result))
    {
      $record_number = mysqli_num_rows($performance_result);
      while($row = mysqli_fetch_array($performance_result))
      {
        $QA_var = $QA_var + $row['emp_QA'];
        $CPH_var = $CPH_var + $row['emp_CPH'];
        $ATT_var = $ATT_var + $row['emp_att'];
      }

      $QA_var = $QA_var / $record_number;
      $CPH_var = $CPH_var / $record_number;
      $ATT_var = $ATT_var / $record_number;
      $QA_var = score_qa($QA_var);
      $CPH_var = score_cph($CPH_var);
      $ATT_var = $ATT_var * 100;
      $ATT_var = score_att($ATT_var);
    }
  }
  $value = $QA_var . ", " . $CPH_var . ", " . $ATT_var;
  return $value;
}

function chart_daily_score_ATT()
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
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $day_sort[] = $row['record_day'];
      }
      sort($day_sort);
      $arrlength = count($day_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_daily_compute_ATT($day_sort[$x], $month, $year, $count, $max_count);
      }
    }
  }
}


function chart_daily_compute_ATT($day, $month, $year, $count, $max_count)
{
  include '../includes/connection.php';
  $ATT_sum = chart_ATT_daily($day, $month, $year);
  $num_records = chart_daily_record_count($day, $month, $year);

  $a = $ATT_sum / $num_records;
  $b = $a * 100;
  $result_ATT = score_att($b);


  if($count < $max_count)
  {
    echo $result_ATT . ", ";
  }
  else
  {
    echo $result_ATT;
  }

}


function chart_ATT_daily($day, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $ATT_sum = 0;
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_att'];
        $ATT_sum = $ATT_sum + $a;
      }
    }
  }
  return $ATT_sum;
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



function current_performance()
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
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
          $checker = 0;
          $checker = choice_three_record_check($name, $month, $year); //check if employee has records in table according to date given
          if($checker == 1)
          {
            choice_three_display($name, $month, $year);
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

function choice_three_display($name, $month, $year)
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



function show_da()
{
  include '../includes/connection.php';
  $query = "SELECT DISTINCT record_month,record_year FROM da_table";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $month = $row['record_month'];
        $year = $row['record_year'];
        echo "<li class='feed-item'>" . $row['record_month'] . " " . $row['record_year'] . "</li>";
        get_emp_da($month, $year);
      }
    }
  }
}

function get_emp_da($month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM da_table WHERE record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    { echo "<ul class='list-style-none feed-body m-0 p-b-20'>";
      while($row = mysqli_fetch_array($result))
      {
        $name = $row['emp_fullname'];
        echo "<li class='feed-item'>" . $name . "</li>";
      }
      echo "</ul>";
    }
  }
}

function show_emp_goals_in_progress()
{
  include '../includes/connection.php';
  $status = "In Progress";
  $query = "SELECT * FROM employee_goals WHERE goal_status = '$status'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    { echo "<li class='feed-item'> In Progress </li>
            <ul class='list-style-none feed-body m-0 p-b-20'>";
      while($row = mysqli_fetch_array($result))
      {
        $name = $row['emp_fullname'];
        echo "<li class='feed-item'>" . $name . "</li>";
      }
      echo "</ul>";
    }
  }
}

function show_emp_goals_achieved()
{
  include '../includes/connection.php';
  $status = "Achieved";
  $query = "SELECT * FROM employee_goals WHERE goal_status = '$status'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    { echo "<li class='feed-item'> Achieved </li>
            <ul class='list-style-none feed-body m-0 p-b-20'>";
      while($row = mysqli_fetch_array($result))
      {
        $name = $row['emp_fullname'];
        echo "<li class='feed-item'>" . $name . " (" . $row['end_month'] . " " . $row['end_year'] . ")" . "</li>";
      }
      echo "</ul>";
    }
  }
}

function emp_of_the_day()
{
  include '../includes/connection.php';
  $day = date('j');
  $month = date('F');
  $year = date('Y');

  $performance_array = [];
  $emp_id_array = [];
  $emp_fullname = [];
  $select_emp_query = "SELECT * FROM performance_record WHERE record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $select_emp_result = mysqli_query($con, $select_emp_query);

  if($select_emp_result)
  {
    if(mysqli_num_rows($select_emp_result))
    {
      while($row = mysqli_fetch_array($select_emp_result))
      {
        $performance_array[] = $row['emp_perf'];
        $emp_id_array[] = $row['emp_id'];
        $emp_fullname[] = $row['emp_fullname'];
      }
      array_multisort($performance_array, SORT_DESC, SORT_NUMERIC, $emp_id_array, $emp_fullname);

      $account_id = "";
      $select_account_id_query = "SELECT * FROM employees WHERE emp_id = '$emp_id_array[0]'";
      $select_account_id_result = mysqli_query($con, $select_account_id_query);

      if($select_account_id_result)
      {
        if(mysqli_num_rows($select_account_id_result))
        {
          while($row = mysqli_fetch_array($select_account_id_result))
          {
            $account_id = $row['account_id'];
          }
        }
      }

      $select_image_query = "SELECT * FROM accounts WHERE account_id = '$account_id'";
      $select_image_result = mysqli_query($con, $select_image_query);

      if($select_image_result)
      {
        if(mysqli_num_rows($select_image_result))
        {
          $holder_image = "";
          $day_image = "";
          while($row = mysqli_fetch_array($select_image_result))
          {
            $holder_image = $row['picture'];
          }
          $day_image = $holder_image."||".$emp_id_array[0]."||".$emp_fullname[0];
          return $day_image;
        }
      }
    }
    else
    {
      $no_records = "";
      $no_records = "No records";
      return $no_records;
    }
  }

}

function emp_of_the_month()
{
  include '../includes/connection.php';
  $month = "July"; //date('F')
  $year = date('Y');
  $performance_array = [];
  $emp_id_array = [];
  $account_id_array = [];
  $emp_name = [];

  $employee_select_query = "SELECT * FROM employees";
  $employee_select_result = mysqli_query($con, $employee_select_query);

  if($employee_select_result)
  {
    if(mysqli_num_rows($employee_select_result))
    {
      while($row = mysqli_fetch_array($employee_select_result))
      {
        $checker = 0;
        $checker = existing_records_function($row['emp_id'], $month, $year);
        if($checker == 1)
        {
          $performance_array[] = emp_performance($row['emp_id'], $month, $year);
          $emp_id_array[] = $row['emp_id'];
          $account_id_array[] = $row['account_id'];
          $emp_name[] = $row['emp_fullname'];
        }
      }
      array_multisort($performance_array, SORT_DESC, SORT_NUMERIC, $emp_id_array, $account_id_array, $emp_name);

      $image_selected = "";
      $holder = "";
      $image_select_query = "SELECT * FROM accounts WHERE account_id = '$account_id_array[0]'";
      $image_select_result = mysqli_query($con, $image_select_query);
      if($image_select_result)
      {
        if(mysqli_num_rows($image_select_result))
        {
          while($row = mysqli_fetch_array($image_select_result))
          {
            $image_selected = $row['picture'];
          }
        }
      }

      $holder = $image_selected."||".$emp_id_array[0]."||".$emp_name[0];
      return $holder;
    }
    else
    {
      $no_records = "";
      $no_records = "No records";
      return $no_records;
    }
  }

}

function existing_records_function($emp_id, $month, $year)
{
  include '../includes/connection.php';
  $checker = 0;

  $record_checker_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
  $record_checker_result = mysqli_query($con, $record_checker_query);

  if($record_checker_result)
  {
      if(mysqli_num_rows($record_checker_result)) //if the user has recrods
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


function emp_performance($emp_id, $month, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = emp_perf_sum($emp_id, $month, $year);

  //get number of records
  $num_records = emp_record_count($emp_id, $month, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  return $emp_perf_quotient;
}

function emp_perf_sum($emp_id, $month, $year)
{
  include '../includes/connection.php';
  $perf_sum_query = "SELECT * FROM performance_record WHERE emp_id='$emp_id' AND record_month='$month' AND record_year='$year'";
  $perf_sum_result = mysqli_query($con, $perf_sum_query);

  if($perf_sum_result)
  {
    if(mysqli_num_rows($perf_sum_result))
    {
      $sum = 0;
      $count = 0;
      $emp_perf_sum = 0;
      while($row = mysqli_fetch_array($perf_sum_result))
      {
        $a = $row['emp_perf'];
        $emp_perf_sum = $emp_perf_sum + $a;
        $count++;
      }
    }
  }
  return $emp_perf_sum;
}

function emp_record_count($emp_id, $month, $year)
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

function emp_of_the_year()
{
  include '../includes/connection.php';
  $year = date('Y');
  $performance_array = [];
  $emp_id_array = [];
  $account_id_array = [];
  $emp_name_year = [];

  $employee_select_query = "SELECT * FROM employees";
  $employee_select_result = mysqli_query($con, $employee_select_query);

  if($employee_select_result)
  {
    if(mysqli_num_rows($employee_select_result))
    {
      while($row = mysqli_fetch_array($employee_select_result))
      {
        $checker = 0;
        $checker = existing_records_function_year($row['emp_id'], $year);
        if($checker == 1)
        {
          $performance_array[] = emp_performance_year($row['emp_id'], $year);
          $emp_id_array[] = $row['emp_id'];
          $account_id_array[] = $row['account_id'];
          $emp_name_year[] = $row['emp_fullname'];
        }
      }
      array_multisort($performance_array, SORT_DESC, SORT_NUMERIC, $emp_id_array, $account_id_array, $emp_name_year);

      $image_selected = "";
      $holder2 = "";
      $image_select_query = "SELECT * FROM accounts WHERE account_id = '$account_id_array[0]'";
      $image_select_result = mysqli_query($con, $image_select_query);
      if($image_select_result)
      {
        if(mysqli_num_rows($image_select_result))
        {
          while($row = mysqli_fetch_array($image_select_result))
          {
            $image_selected = $row['picture'];
          }
        }
      }
      $holder2 = $image_selected."||".$emp_id_array[0]."||".$emp_name_year[0];
      return $holder2;
    }
    else
    {
      $no_records = "";
      $no_records = "No records";
      return $no_records;
    }
  }


}

function existing_records_function_year($emp_id, $year)
{
  include '../includes/connection.php';
  $checker = 0;

  $record_checker_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
  $record_checker_result = mysqli_query($con, $record_checker_query);

  if($record_checker_result)
  {
      if(mysqli_num_rows($record_checker_result)) //if the user has recrods
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

function emp_performance_year($emp_id, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = emp_perf_sum_year($emp_id, $year);

  //get number of records
  $num_records = emp_record_count_year($emp_id, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  return $emp_perf_quotient;
}

function emp_perf_sum_year($emp_id, $year)
{
  include '../includes/connection.php';
  $perf_sum_query = "SELECT * FROM performance_record WHERE emp_id='$emp_id' AND record_year='$year'";
  $perf_sum_result = mysqli_query($con, $perf_sum_query);

  if($perf_sum_result)
  {
    if(mysqli_num_rows($perf_sum_result))
    {
      $sum = 0;
      $count = 0;
      $emp_perf_sum = 0;
      while($row = mysqli_fetch_array($perf_sum_result))
      {
        $a = $row['emp_perf'];
        $emp_perf_sum = $emp_perf_sum + $a;
        $count++;
      }
    }
  }
  return $emp_perf_sum;
}

function emp_record_count_year($emp_id, $year)
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

function emp_of_day_performance($emp_id, $day, $month, $year)
{
  include '../includes/connection.php';

  $emp_of_day_performance_query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $emp_of_day_performance_result = mysqli_query($con, $emp_of_day_performance_query);
  $QA = 0;
  $CPH = 0;
  $result = "";

  if($emp_of_day_performance_result)
  {
    if(mysqli_num_rows($emp_of_day_performance_result))
    {
      while($row = mysqli_fetch_array($emp_of_day_performance_result))
      {
        $QA = $row['emp_QA'];
        $CPH = $row['emp_CPH'];
      }
    }
    $result = $QA . "||" . $CPH;
    return $result;
  }

}
 ?>
