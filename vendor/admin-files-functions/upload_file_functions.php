<?php
if(isset($_POST['submit_performance']))
{

  //number of employees selected
  $num = $_POST['count'];

  //declare array variables
  $arr = [];
  $emp_id = [];
  $emp_name = [];
  $qa_raw = [];
  $cph_raw = [];
  $att_raw = [];
  $qa_score = [];
  $cph_score = [];
  $att_score = [];

  //date
  $date = $_POST['date'];
  $temp = explode('-', $date);
  $day = ltrim($temp[2], "0");
  $year = $temp[0];
  $month = $temp[1];
  $dateObj   = DateTime::createFromFormat('!m', $month);
  $monthName = $dateObj->format('F'); // March
  $month = $monthName;
  $display_date = "Date: " . $month . " " . $day. ", " . $year . "<br>";

  $name_list = [];
  for($a = 0; $a < $num; $a++)
  {
    $arr = explode("||",$_POST['employee_name'][$a]);
    $name_list[$a] = $arr[1];
  }
  $name_list_count = count(array_unique($name_list));

  if($name_list_count < $num)
  {
    $_SESSION['message'] = "Duplicate detected";
  }
  if($name_list_count == $num)
  {
    for($a = 0; $a < $num; $a++)
    {
      $arr = explode("||",$_POST['employee_name'][$a]);
      $emp_id[$a] = $arr[0];
      $emp_name[$a] = $arr[1];

      $qa_raw[$a] = $_POST['emp_qa'][$a];
      $cph_raw[$a] = $_POST['emp_cph'][$a];
      $att_raw[$a] = $_POST['emp_att'][$a];

      $qa_score[$a] = score_qa($qa_raw[$a]);
      $cph_score[$a] = score_cph($cph_raw[$a]);
      $att_score[$a] = score_att($att_raw[$a]);

      $perf_score = performance_score($qa_score[$a], $cph_score[$a], $att_score[$a]);

      $perf_comment = performance_comment($perf_score);

      //insert the records in performance_record table
      insert_records($emp_id[$a], $emp_name[$a], $qa_raw[$a], $cph_raw[$a], $att_raw[$a], $qa_score[$a], $cph_score[$a], $perf_score, $perf_comment, $day, $month, $year);

      goal_check($emp_id[$a], $day, $month, $year);

      $da_detected = calculate_month($emp_id[$a], $month, $year);

      if($da_detected == 1)
      {
        $add_da_query = "SELECT * FROM employees WHERE emp_id = '$emp_id[$a]'";
        $add_da_result = mysqli_query($con, $add_da_query);
        if($add_da_result)
        {
          if(mysqli_num_rows($add_da_result))
          {
            while($row = mysqli_fetch_array($add_da_result))
            {
              $add_da = $row['emp_da'];
            }
          }
        }
        insert_into_da_table($emp_id[$a], $emp_name[$a], $month, $year);

        $add_da++;
        $update_da_query = "UPDATE employees SET emp_da = '$add_da' WHERE emp_id = '$emp_id[$a]'";
        $update_da_result = mysqli_query($con, $update_da_query);

        $notif_id = "nt_" . time();
        $notif_desc = "received a disciplinary action";
        $notif_type = "DA";
        $notif_status = "UNREAD";
        $insert_notif_query = "INSERT INTO notifications (notif_id, emp_id, notif_desc, notif_type, notif_status, record_day, record_month, record_year)
                               VALUES ('$notif_id', '$emp_id[$a]', '$notif_desc', '$notif_type', '$notif_status', '$day', '$month', '$year')";
        $insert_notif_result = mysqli_query($con, $insert_notif_query);
      }
    }
    $_SESSION['message'] = "Successfully Imported " . $name_list_count . " records";
  }



}


///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///
function insert_records($emp_id, $emp_name, $qa_raw, $cph_raw, $att_raw, $qa_score, $cph_score, $perf_score, $perf_comment, $day, $month, $year)
{
  include '../includes/connection.php';

  $insert_records_query = "INSERT INTO performance_record (emp_id, emp_fullname, emp_QA, emp_CPH, emp_att, ds_QA, ds_CPH, emp_perf, perf_comment, record_day, record_month, record_year)
                           VALUES ('$emp_id', '$emp_name', '$qa_raw', '$cph_raw', '$att_raw', '$qa_score', '$cph_score', '$perf_score', '$perf_comment', '$day', '$month', '$year')";
  $insert_records_result = mysqli_query($con, $insert_records_query);
}

if(isset($_POST['select_employees']))
{
  $showemp = "";
  $get_user_names_and_id_query = "SELECT * FROM employees";
  $get_user_names_and_id_result = mysqli_query($con, $get_user_names_and_id_query);

  if($get_user_names_and_id_result)
  {
    if(mysqli_num_rows($get_user_names_and_id_result) > 0)
    {
      while($row=mysqli_fetch_assoc($get_user_names_and_id_result))
      {
        $emp_id = $row['emp_id'];
        $emp_name = $row['emp_fullname'];

        $showemp .= "<option value ='".$emp_id."||".$emp_name."'>" . $emp_name . "</option>";
      }
    }
  }
}
///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///
///INSERT RECORDS FUNCTION///

///DISCIPLINARY ACTION FUNCTIONS///
///DISCIPLINARY ACTION FUNCTIONS///
///DISCIPLINARY ACTION FUNCTIONS///
///DISCIPLINARY ACTION FUNCTIONS///
function calculate_month($emp_id, $month, $year)
  {
    include('../includes/connection.php');
    //get the sum of each kpi
    $QA_sum = sum_qa_da($emp_id, $month, $year);
    $CPH_sum = sum_cph_da($emp_id, $month, $year);
    $ATT_sum = sum_ATT_da($emp_id, $month, $year);

    $month = date('F');
    $year = date('Y');

    $num_records = recordCount($emp_id, $month, $year);


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
    $result_ATT = score_att_goal($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    //check if disciplinary action is needed
    $da = da_find($finals);
    return $da;
  }

  function sum_qa_da($emp_id, $month, $year)
  {
      include('../includes/connection.php');
      $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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


  function sum_cph_da($emp_id, $month, $year)
  {
      include('../includes/connection.php');
      $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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


  function sum_att_da($emp_id, $month, $year)
  {
      include('../includes/connection.php');
      $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

  function recordCount($emp_id, $month, $year)
  {
      include '../includes/connection.php';
      $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

  function insert_into_da_table($emp_id, $name, $month, $year)
  {
    include('../includes/connection.php');
    //get the sum of each kpi

    $QA_sum = sum_qa_da($emp_id, $month, $year);
    $CPH_sum = sum_cph_da($emp_id, $month, $year);
    $ATT_sum = sum_ATT_da($emp_id, $month, $year);

    $month = date('F');
    $year = date('Y');

    $num_records = recordCount($emp_id, $month, $year);


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
    $result_ATT = score_att_goal($d);

    //calculate for performance score
    $finals = performance_score($result_QA, $result_CPH, $result_ATT);

    //calculate for comment
    $comment = performance_comment($finals);

    $insert_into_da_table_query = "INSERT INTO da_table (emp_id, emp_fullname, monthly_QA, monthly_CPH, monthly_ATT, monthly_score_QA, monthly_score_CPH, monthly_score_ATT, monthly_perf, perf_comment, record_month, record_year)
                                    VALUES ('$emp_id', '$name', '$a', '$b', '$c', '$result_QA', '$result_CPH', '$result_ATT', '$finals', '$comment', '$month', '$year')";
    $insert_into_da_table_result = mysqli_query($con, $insert_into_da_table_query);
  }
///DISCIPLINARY ACTION FUNCTIONS END///
///DISCIPLINARY ACTION FUNCTIONS END///
///DISCIPLINARY ACTION FUNCTIONS END///
///DISCIPLINARY ACTION FUNCTIONS END///



///GOALS FUNCTIONS///
///GOALS FUNCTIONS///
///GOALS FUNCTIONS///
///GOALS FUNCTIONS///
function goal_check($emp_id, $day, $month, $year)
{
  include('../includes/connection.php');
  $get_inprogress_query = "SELECT * FROM employee_goals WHERE emp_id = '$emp_id' AND goal_status = 'In Progress'";
  $get_inprogress_result = mysqli_query($con, $get_inprogress_query);

  if($get_inprogress_result)
  {
    if(mysqli_num_rows($get_inprogress_result) > 0)
    {
      while($row = mysqli_fetch_array($get_inprogress_result))
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
            echo "Passed <br>";
            passed_goal($emp_id, $day, $month, $year);
          }
          if($performance_checker == 0)
          {
            echo "Failed <br>";
            failed_goal($emp_id, $day, $month, $year);
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
  $result_ATT = score_att_goal($d);
  // echo "QA: " . $result_QA . " <br>CPH: " . $result_CPH . " <br>ATT: " . $result_ATT;
  $final_result = final_result($goal_QA, $goal_CPH, $goal_ATT, $result_QA, $result_CPH, $result_ATT);
  return $final_result;
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

function passed_goal($emp_id, $day, $month, $year)
{
  $goal_status = "Achieved";
  include('../includes/connection.php');
  $query = "UPDATE employee_goals SET goal_status='$goal_status' WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);

  $notif_id = "nt_" . time();
  $notif_desc = "achieved their goal";
  $notif_type = "GOAL_PASSED";
  $notif_status = "UNREAD";
  $insert_notif_query = "INSERT INTO notifications (notif_id, emp_id, notif_desc, notif_type, notif_status, record_day, record_month, record_year)
                         VALUES ('$notif_id', '$emp_id', '$notif_desc', '$notif_type', '$notif_status', '$day', '$month', '$year')";
  $insert_notif_result = mysqli_query($con, $insert_notif_query);
}

function failed_goal($emp_id, $day, $month, $year)
{
  $goal_status = "Failed";
  include('../includes/connection.php');
  $query = "UPDATE employee_goals SET goal_status='$goal_status' WHERE emp_id='$emp_id'";
  $result = mysqli_query($con, $query);

  $notif_id = "nt_" . time();
  $notif_desc = "failed their goal";
  $notif_type = "GOAL_FAILED";
  $notif_status = "UNREAD";
  $insert_notif_query = "INSERT INTO notifications (notif_id, emp_id, notif_desc, notif_type, notif_status, record_day, record_month, record_year)
                         VALUES ('$notif_id', '$emp_id', '$notif_desc', '$notif_type', '$notif_status', '$day', '$month', '$year')";
  $insert_notif_result = mysqli_query($con, $insert_notif_query);
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

if(isset($_SESSION['name']))
{
  $shownum = "";
  $num_users = 0;

  $num_users_query = "SELECT * FROM employees";
  $num_users_result = mysqli_query($con, $num_users_query);

  if($num_users_result)
  {
    if(mysqli_num_rows($num_users_result) > 0)
    {
      while($row=mysqli_fetch_assoc($num_users_result))
      {
        $num_users++;
      }
    }
  }

  for($a = 0; $a < $num_users; $a++)
  {
    $shownum .= "<option value =". $a+1 . ">" . $a+1 . "</option>";
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
    if($c == 1)
      {
        $result_ATT = 4;
        return $result_ATT;
      }
      else
      {
        $result_ATT = 0;
        return $result_ATT;
      }
}

//calculate for equivalent att score
function score_att_goal($c)
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
///GOALS FUNCTIONS END///
///GOALS FUNCTIONS END///
///GOALS FUNCTIONS END///
///GOALS FUNCTIONS END///

 ?>
