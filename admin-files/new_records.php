<?php
include '../includes/function.php';
include '../includes/connection.php';

$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$year = array("2022");
$emp_name = array("Employee 1", "Employee 2", "Employee 3", "Employee 4", "Employee 5", "Employee 6");

for($x = 0; $x < 1; $x++)
{
  for($y = 0; $y < 12; $y++)
  {
    for($z = 0; $z < 22; $z++)
    {
      for($xx = 0; $xx < 6; $xx++)
      {
        // echo $emp_name[$xx] . "<br>";
        if($emp_name[$xx] == "Employee 1")
        {
          employee1($emp_name[$xx], $z+1, $month[$y], $year[$x]);
        }
        elseif($emp_name[$xx] == "Employee 2" || $emp_name[$xx] == "Employee 3" || $emp_name[$xx] == "Employee 4")
        {
          employee2to4($emp_name[$xx], $z+1, $month[$y], $year[$x]);
        }
        else
        {
          employee5to6($emp_name[$xx], $z+1, $month[$y], $year[$x]);
        }
      }
    }
  }
  echo "Done";
}

function employee1($emp_name, $day, $month, $year)
{
  include '../includes/connection.php';
  $emp_QA = rand(95,99);
  $emp_CPH = rand(6,9);
  $emp_ATT = 1;
  $ds_QA = score_qa($emp_QA);
  $ds_CPH = score_cph($emp_CPH);
  $ds_ATT = 4;
  $emp_id = emp_id_grab($emp_name);
  $emp_perf = performance_score($ds_QA, $ds_CPH, $ds_ATT);
  $perf_comment = performance_comment($emp_perf);

  $insert_record_query = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$emp_name','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_ATT','$emp_perf','$perf_comment','$day','$month','$year')";
  $insert_record_result = mysqli_query($con, $insert_record_query);

}

function employee2to4($emp_name, $day, $month, $year)
{
  include '../includes/connection.php';
  $emp_QA = rand(92,95);
  $emp_CPH = rand(5,6);
  $emp_ATT = 1;
  $ds_QA = score_qa($emp_QA);
  $ds_CPH = score_cph($emp_CPH);
  $ds_ATT = 4;
  $emp_id = emp_id_grab($emp_name);
  $emp_perf = performance_score($ds_QA, $ds_CPH, $ds_ATT);
  $perf_comment = performance_comment($emp_perf);

  $insert_record_query = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$emp_name','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_ATT','$emp_perf','$perf_comment','$day','$month','$year')";
  $insert_record_result = mysqli_query($con, $insert_record_query);
}

function employee5to6($emp_name, $day, $month, $year)
{
  include '../includes/connection.php';
  $emp_QA = rand(85,93);
  $emp_CPH = rand(4,5);
  $emp_ATT = 1;
  $ds_QA = score_qa($emp_QA);
  $ds_CPH = score_cph($emp_CPH);
  $ds_ATT = 4;
  $emp_id = emp_id_grab($emp_name);
  $emp_perf = performance_score($ds_QA, $ds_CPH, $ds_ATT);
  $perf_comment = performance_comment($emp_perf);

  $insert_record_query = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$emp_name','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_ATT','$emp_perf','$perf_comment','$day','$month','$year')";
  $insert_record_result = mysqli_query($con, $insert_record_query);
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

function emp_id_grab($fullname)
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
}
}
 ?>
