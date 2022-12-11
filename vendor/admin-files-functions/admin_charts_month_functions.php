<?php
function convert_months_to_number($compile_months)
{
  for($i=0;$i<count($compile_months);$i++)
  {
    if($compile_months[$i] == "January")
    {
      $compile_months[$i] = 1;
    }
    elseif($compile_months[$i] == "February")
    {
      $compile_months[$i] = 2;
    }
    elseif($compile_months[$i] == "March")
    {
      $compile_months[$i] = 3;
    }
    elseif($compile_months[$i] == "April")
    {
      $compile_months[$i] = 4;
    }
    elseif($compile_months[$i] == "May")
    {
      $compile_months[$i] = 5;
    }
    elseif($compile_months[$i] == "June")
    {
      $compile_months[$i] = 6;
    }
    elseif($compile_months[$i] == "July")
    {
      $compile_months[$i] = 7;
    }
    elseif($compile_months[$i] == "August")
    {
      $compile_months[$i] = 8;
    }
    elseif($compile_months[$i] == "September")
    {
      $compile_months[$i] = 9;
    }
    elseif($compile_months[$i] == "October")
    {
      $compile_months[$i] = 10;
    }
    elseif($compile_months[$i] == "November")
    {
      $compile_months[$i] = 11;
    }
    elseif($compile_months[$i] == "December")
    {
      $compile_months[$i] = 12;
    }
  }
  return $compile_months;
}

function number_to_months($convert_months_number)
{
  for($i=0;$i<count($convert_months_number);$i++)
  {
    if($convert_months_number[$i] == 1)
    {
      $convert_months_number[$i] = "January";
    }
    elseif($convert_months_number[$i] == 2)
    {
      $convert_months_number[$i] = "February";
    }
    elseif($convert_months_number[$i] == 3)
    {
      $convert_months_number[$i] = "March";
    }
    elseif($convert_months_number[$i] == 4)
    {
      $convert_months_number[$i] = "April";
    }
    elseif($convert_months_number[$i] == 5)
    {
      $convert_months_number[$i] = "May";
    }
    elseif($convert_months_number[$i] == 6)
    {
      $convert_months_number[$i] = "June";
    }
    elseif($convert_months_number[$i] == 7)
    {
      $convert_months_number[$i] = "July";
    }
    elseif($convert_months_number[$i] == 8)
    {
      $convert_months_number[$i] = "August";
    }
    elseif($convert_months_number[$i] == 9)
    {
      $convert_months_number[$i] = "September";
    }
    elseif($convert_months_number[$i] == 10)
    {
      $convert_months_number[$i] = "October";
    }
    elseif($convert_months_number[$i] == 11)
    {
      $convert_months_number[$i] = "November";
    }
    elseif($convert_months_number[$i] == 12)
    {
      $convert_months_number[$i] = "December";
    }
  }
  return $convert_months_number;
}

function chart_get_months()
{
  include '../includes/connection.php';
  $year = date('Y');
  $compile_months = [];
  $count = 0;

  $query = "SELECT DISTINCT record_month FROM performance_record WHERE record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $compile_months[] = $row['record_month'];
      }
      $convert_months_number = convert_months_to_number($compile_months);
      sort($convert_months_number);
      $converted_months = number_to_months($convert_months_number);
      $arrlength = count($converted_months);
      for($x = 0; $x < $arrlength; $x++)
      {
        echo "'" . $converted_months[$x] . "', ";
      }
    }
  }
}

///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// MONTHLY QA
function chart_monthly_score_QA()
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $count = 0;

  $query = "SELECT DISTINCT record_month FROM performance_record WHERE record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $month = $row['record_month'];
        chart_monthly_compute_QA($month, $year, $count, $max_count);
      }
    }
  }
}

function chart_monthly_compute_QA($month, $year, $count, $max_count)
{
  include '../includes/connection.php';
  $QA_sum = chart_QA_monthly($month, $year);
  $num_records = chart_monthly_record_count($month, $year);

  $a = $QA_sum / $num_records;

  $result_QA = score_qa($a);

  if($count < $max_count)
  {
    echo sprintf('%.2f', $result_QA) . ", ";
  }
  else
  {
    echo sprintf('%.2f', $result_QA);
  }

}

function chart_monthly_record_count($month, $year)
{
    include '../includes/connection.php';
    $count = 0;

    $query = "SELECT * FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
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


function chart_QA_monthly($month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
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
/////////////////////////////////////////////////// MONTHLY CPH
function chart_monthly_score_CPH()
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');

  $query = "SELECT DISTINCT record_month FROM performance_record WHERE record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $compile_months[] = $row['record_month'];

      }
      $convert_months_number = convert_months_to_number($compile_months);
      sort($convert_months_number);
      $converted_months = number_to_months($convert_months_number);
      $arrlength = count($converted_months);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_monthly_compute_CPH($converted_months[$x], $year);
      }
    }
  }
}

function chart_monthly_compute_CPH($month, $year)
{
  include '../includes/connection.php';
  $CPH_sum = chart_CPH_monthly($month, $year);
  $num_records = chart_monthly_record_count($month, $year);

  $a = $CPH_sum / $num_records;

  $result_CPH = score_CPH($a);

    echo sprintf('%.2f', $result_CPH) . ", ";

}

function chart_CPH_monthly($month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
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
/////////////////////////////////////////////////// MONTHLY ATT
function chart_monthly_score_ATT()
{
  include '../includes/connection.php';
  $month = date('F');
  $year = date('Y');
  $count = 0;

  $query = "SELECT DISTINCT record_month FROM performance_record WHERE record_year = '$year'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $month = $row['record_month'];
        chart_monthly_compute_ATT($month, $year, $count, $max_count);
      }
    }
  }
}

function chart_monthly_compute_ATT($month, $year, $count, $max_count)
{
  include '../includes/connection.php';
  $ATT_sum = chart_ATT_monthly($month, $year);
  $num_records = chart_monthly_record_count($month, $year);

  $a = $ATT_sum / $num_records;
  $b = $a * 100;
  $result_ATT = score_att($b);


  if($count < $max_count)
  {
    echo sprintf('%.2f', $result_ATT) . ", ";
  }
  else
  {
    echo sprintf('%.2f', $result_ATT);
  }

}



function chart_ATT_monthly($month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_month = '$month' AND record_year = '$year'";
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
























 ?>
