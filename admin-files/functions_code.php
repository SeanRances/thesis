<?php
//////////////////////////////////////////////////////////////////////////////////////     code.php
function valid_value_checker($emp_QA, $emp_CPH, $ds_QA, $ds_CPH, $emp_att, $emp_perf, $perf_comment)
{
  $checker = 0;
  if($emp_QA > 100 || $emp_QA < 0)
  {
    $checker = 1;
  }
  if($emp_CPH > 100 || $emp_CPH < 0)
  {
    $checker = 1;
  }
  if($emp_att > 1 || $emp_att < 0)
  {
    $checker = 1;
  }
  if($ds_QA > 4 || $ds_QA < 1)
  {
    $checker = 1;
  }
  if($ds_CPH > 4 || $ds_CPH < 1)
  {
    $checker = 1;
  }
  if($emp_perf > 4 || $emp_perf < 1)
  {
    $checker = 1;
  }
  if($perf_comment != "Underachieved" && $perf_comment != "Achieved" && $perf_comment != "Surpassed")
  {
    $checker = 1;
  }
  return $checker;
}


function exist_record($emp_id, $day, $month, $year)
{
  include('../includes/connection.php');
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $dupRecord_check = 1;
      return $dupRecord_check;
    }
    else
    {
      $dupRecord_check = 0;
      return $dupRecord_check;
    }
  }
}

//check which statement should be outputted back in index.php
function session_text($recordCount, $field_missing, $missing_emp, $same_spotted, $value_checker, $string2, $string3, $string4, $string5)
{
  $message = "Successfully Imported " . $recordCount . " records. <br> ";

  if($field_missing == 1)
  {
    $message = $message . "The following line/s have missing fields and were not entered: " . $string2 . "<br> ";
  }
  if($missing_emp == 1)
  {
    $message = $message ."The following line/s are employees who are not registered in the program: " . $string3 . "<br> ";
  }
  if($same_spotted == 1)
  {
    $message = $message . "The following line/s are duplicated names within the file: " . $string4 . "<br> ";
  }
  if($value_checker == 1)
  {
    $message = $message . "The following line/s have invalid values: " . $string5 . "<br> ";
  }

  $_SESSION['message'] = $message;
  header('Location: upload_file.php');
  exit(0);
}


//check if the employee exists in the database
function exists_emp($fullname)
{
  include('../includes/connection.php');
  $empidQuery = "SELECT * FROM employees WHERE emp_fullname = '$fullname'";
  $search_result = mysqli_query($con, $empidQuery);

  if($search_result)
  {
    if(mysqli_num_rows($search_result))
    {
      while($row = mysqli_fetch_array($search_result))
      {
        $emp_id = $row['emp_id'];
        return $emp_id;
      }
    }
    else
    {
      $emp_id = "";
      return $emp_id;
    }
  }
  else
  {
    echo'<script>alert("Result Error")</script>';
    $emp_id = "";
    return $emp_id;
  }
}


//check if the row has missing values
function emptyChecker($fullname,$emp_QA,$emp_CPH,$ds_QA,$ds_CPH,$emp_att,$emp_perf,$perf_comment)
{
    if(empty($fullname) || empty($emp_QA) || empty($emp_CPH) || empty($ds_QA) || empty($ds_CPH) || empty($emp_att) || empty($emp_perf) || empty($perf_comment))
    {
        return 1;
    }
}

function end_month_checker($day)
{
  $same = 0;
  $lastdateofthemonth = date("Y-m-t");

  $lastworkingday = date('l', strtotime($lastdateofthemonth));

  if($lastworkingday == "Saturday") {
  $newdate = strtotime ('-1 day', strtotime($lastdateofthemonth));
  $lastworkingday = date ('Y-m-j', $newdate);
  }
  elseif($lastworkingday == "Sunday") {
  $newdate = strtotime ('-2 day', strtotime($lastdateofthemonth));
  $lastworkingday = date ( 'Y-m-j' , $newdate );
  }

  $date1 = date('t', strtotime($lastworkingday));
  $date2 = $day;

  $same = same_day_checker($date1, $date2);
  return $same;
  }

  function same_day_checker($date1, $date2)
  {
    $checker = 0;
    if($date1 >= $date2)
    {
      $checker = 1;
    }
    else
    {
      $checker = 0;
    }
    return $checker;
  }

  function calculate_month($name, $month, $year)
  {
    include('../includes/connection.php');
    //get the sum of each kpi
    $QA_sum = sum_qa($name, $month, $year);
    $CPH_sum = sum_cph($name, $month, $year);
    $ATT_sum = sum_ATT($name, $month, $year);

    $month = date('F');
    $year = date('Y');

    $num_records = recordCount($name, $month, $year);


    if($num_records == 0)
    {
      //get the average of each kpi
      $a = $QA_sum / 1;
      $b = $CPH_sum / 1;
      $c = $ATT_sum  / 1;
      $d = $c * 100;
    }
    else
    {
      //get the average of each kpi
      $a = $QA_sum / $num_records;
      $b = $CPH_sum / $num_records;
      $c = $ATT_sum  / $num_records;
      $d = $c * 100;
    }


    //convert the average score to transmuted score
    $result_QA = score_qa($a);
    $result_CPH = score_cph($b);
    $result_ATT = score_att($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    //check if disciplinary action is needed
    $da = da_find($finals);
    return $da;
  }

  function insert_into_da_table($emp_id, $name, $month, $year)
  {
    include('../includes/connection.php');
    //get the sum of each kpi
    $QA_sum = sum_qa($name, $month, $year);
    $CPH_sum = sum_cph($name, $month, $year);
    $ATT_sum = sum_ATT($name, $month, $year);

    $month = date('F');
    $year = date('Y');

    $num_records = recordCount($name, $month, $year);


    if($num_records == 0)
    {
      //get the average of each kpi
      $a = $QA_sum / 1;
      $b = $CPH_sum / 1;
      $c = $ATT_sum  / 1;
      $d = $c * 100;
    }
    else
    {
      //get the average of each kpi
      $a = $QA_sum / $num_records;
      $b = $CPH_sum / $num_records;
      $c = $ATT_sum  / $num_records;
      $d = $c * 100;
    }


    //convert the average score to transmuted score
    $result_QA = score_qa($a);
    $result_CPH = score_cph($b);
    $result_ATT = score_att($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    $insert_into_da_table_query = "INSERT INTO da_table (emp_id, emp_fullname, monthly_QA, monthly_CPH, monthly_ATT, monthly_score_QA, monthly_score_CPH, monthly_score_ATT, monthly_perf, perf_comment, record_month, record_year)
                                    VALUES ('$emp_id', '$name', '$a', '$b', '$c', '$result_QA', '$result_CPH', '$result_ATT', '$finals', '$comment', '$month', '$year')";
    $insert_into_da_table_result = mysqli_query($con, $insert_into_da_table_query);
  }

  function recordCount($name, $month, $year)
  {
      include '../includes/connection.php';
      $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
        $search_result = mysqli_query($con, $empidQuery);
        $count = 0;

        if($search_result)
        {
          if(mysqli_num_rows($search_result))
          {

            while($row = mysqli_fetch_array($search_result))
            {
                  $count++;
            }
          }
        }
        return $count;
  }
  //calculate for qa
function sum_qa($name, $month, $year)
{
    include('../includes/connection.php');
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);
      $QA_sum = 0;

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

//calculate for cph
function sum_cph($name, $month, $year)
{
    include('../includes/connection.php');
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);
      $CPH_sum = 0;

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

//calculate for att
function sum_att($name, $month, $year)
{
    include('../includes/connection.php');
    $empidQuery = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_month = '$month' AND record_year = '$year'";
      $search_result = mysqli_query($con, $empidQuery);
      $ATT_sum = 0;

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

//calculate for equivalent qa score
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
    if($c < 1.50)
      {
        $result_ATT = 1;
        return $result_ATT;
      }
      elseif($c >= 1.50 && $c < 2.50)
      {
        $result_ATT = 2;
        return $result_ATT;
      }
      elseif($c >= 2.50 && $c < 3.50)
      {
        $result_ATT = 3;
        return $result_ATT;
      }
      elseif($c >= 3.50)
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


//calculate for comment on performance
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


//check if disciplinary action is needed
function da_find($finals)
{
    if($finals < 2)
    {
      $da = 1;
      return $da;
    }
    elseif($finals >= 2 && $finals < 3.50)
    {
        $da = 0;
        return $da;
    }
    elseif($finals >= 3.50)
    {
        $da = 0;
        return $da;
    }
}

function update_da($emp_id, $da_detected)
{
  include('../includes/connection.php');
  if($da_detected == 1)
  {
    $query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
    $result = mysqli_query($con, $query);
    if($result)
    {
      if(mysqli_num_rows($result))
      {
        while($row = mysqli_fetch_array($result))
        {
          $add_da = $row['emp_da'];
        }
      }
    }

    $add_da++;
    $query = "UPDATE employees SET emp_da = '$add_da' WHERE emp_id = '$emp_id'";
    $result = $result = mysqli_query($con, $query);
  }
}





///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
///////////////////////////////////////////
/////////////////////////////////////////// GOAL CHECKER
function goal_check($emp_id)
{
  include('../includes/connection.php');
  $query = "SELECT * FROM employee_goals WHERE emp_id = '$emp_id' AND goal_status = 'In Progress'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $start_day = $row['start_day'];
        $start_month = $row['start_month'];
        $start_year = $row['start_year'];
        $end_day = $row['end_day'];
        $end_month = $row['end_month'];
        $end_year = $row['end_year'];
        $goal_QA = $row['goal_QA'];
        $goal_CPH = $row['goal_CPH'];
        $goal_ATT = $row['goal_ATT'];
        $date_checker = goal_check_date($end_day, $end_month, $end_year);
        if($date_checker == 1)
        {
          $performance_checker = goal_performance_checker($emp_id, $start_day, $start_month, $start_year, $end_day, $end_month, $end_year, $goal_QA, $goal_CPH, $goal_ATT);
          if($performance_checker == 1)
          {
            passed_goal($emp_id);
          }
          if($performance_checker == 0)
          {
            failed_goal($emp_id);
          }
        }
      }
    }
  }
}

function goal_check_date($end_day, $end_month, $end_year)
{
  $checker = 0;
  $day = date('j');
  $month = date('F');
  $year = date('Y');

  $date1 = $year . "-" . $month . "-" . $day;
  $date2 = $end_year . "-" . $end_month . "-" . $end_day;

  if ($date1 >= $date2)
  {
    $checker = 1;
    return $checker;
  }
  else
  {
    $checker = 0;
    return $checker;
  }
}

function goal_performance_checker($emp_id, $start_day, $start_month, $start_year, $end_day, $end_month, $end_year, $goal_QA, $goal_CPH, $goal_ATT)
{

  $start_date = $start_year . "-" . $start_month . "-" . $start_day;
  $end_date = $end_year . "-" . $end_month . "-" . $end_day;

  $QA_sum = sum_for_qa($emp_id, $start_date, $end_date);
  $CPH_sum = sum_for_cph($emp_id, $start_date, $end_date);
  $ATT_sum = sum_for_att($emp_id, $start_date, $end_date);

  $num_records = record_Count($emp_id, $start_date, $end_date);

  if($num_records == 0)
  {
    //get the average of each kpi
    $a = $QA_sum / 1;
    $b = $CPH_sum / 1;
    $c = $ATT_sum  / 1;
    $d = $c * 100;
  }
  else
  {
    //get the average of each kpi
    $a = $QA_sum / $num_records;
    $b = $CPH_sum / $num_records;
    $c = $ATT_sum  / $num_records;
    $d = $c * 100;
  }

  $result_QA = score_qa($a);
  $result_CPH = score_cph($b);
  $result_ATT = score_att($d);

  $final_result = final_result($goal_QA, $goal_CPH, $goal_ATT, $result_QA, $result_CPH, $result_ATT);
  return $final_result;
}

function passed_goal($emp_id)
{
  $goal_status = "Achieved";
  include('../includes/connection.php');
  $query = "UPDATE employee_goals SET goal_status='$goal_status' WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);
}

function failed_goal($emp_id)
{
  $goal_status = "Failed";
  include('../includes/connection.php');
  $query = "UPDATE employee_goals SET goal_status='$goal_status' WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);
}

function final_result($goal_QA, $goal_CPH, $goal_ATT, $result_QA, $result_CPH, $result_ATT)
{
  $checker = 0;
  if($result_QA >= $goal_QA && $result_CPH >= $goal_CPH && $result_ATT >= $goal_ATT)
  {
    $checker = 1;
    return $checker;
  }
  else
  {
    $checker = 0;
    return $checker;
  }
}

function record_Count($emp_id, $start_date, $end_date)
{
    include '../includes/connection.php';
    $count = 0;
    $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id'";
    $result = mysqli_query($con, $query);

    if($result)
    {
      if(mysqli_num_rows($result))
      {
        while($row = mysqli_fetch_array($result))
        {
          $record_day = $row['record_day'];
          $record_month = $row['record_month'];
          $record_year = $row['record_year'];
          $record_date = $row['record_year'] . "-" . $row['record_month'] . "-" . $row['record_day'];
          $date_compare = date_compare($start_date, $end_date, $record_date);

          if($date_compare == 1)
          {
            $count++;
          }
        }
      }
    }
      return $count;
}

function sum_for_qa($emp_id, $start_date, $end_date)
{
  include('../includes/connection.php');
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  $QA_sum = 0;

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $record_day = $row['record_day'];
        $record_month = $row['record_month'];
        $record_year = $row['record_year'];
        $record_date = $row['record_year'] . "-" . $row['record_month'] . "-" . $row['record_day'];
        $date_compare = date_compare($start_date, $end_date, $record_date);

        if($date_compare == 1)
        {
          $a = $row['emp_QA'];
          $QA_sum = $QA_sum + $a;
        }
      }
    }
  }
  return $QA_sum;
}

function sum_for_cph($emp_id, $start_date, $end_date)
{
  include('../includes/connection.php');
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  $CPH_sum = 0;

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $record_day = $row['record_day'];
        $record_month = $row['record_month'];
        $record_year = $row['record_year'];
        $record_date = $row['record_year'] . "-" . $row['record_month'] . "-" . $row['record_day'];
        $date_compare = date_compare($start_date, $end_date, $record_date);

        if($date_compare == 1)
        {
          $a = $row['emp_CPH'];
          $CPH_sum = $CPH_sum + $a;
        }
      }
    }
  }
  return $CPH_sum;
}

function sum_for_att($emp_id, $start_date, $end_date)
{
  include('../includes/connection.php');
  $query = "SELECT * FROM performance_record WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  $ATT_sum = 0;

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $record_day = $row['record_day'];
        $record_month = $row['record_month'];
        $record_year = $row['record_year'];
        $record_date = $row['record_year'] . "-" . $row['record_month'] . "-" . $row['record_day'];
        $date_compare = date_compare($start_date, $end_date, $record_date);

        if($date_compare == 1)
        {
          $a = $row['emp_att'];
          $ATT_sum = $ATT_sum + $a;
        }
      }
    }
  }
  return $ATT_sum;
}

function date_compare($start_date, $end_date, $record_date)
{
  $checker = 0;

  if($record_date >= $start_date && $record_date <= $end_date)
  {
    $checker = 1;
    return $checker;
  }
  else
  {
    $checker = 0;
    return $checker;
  }
}
?>
