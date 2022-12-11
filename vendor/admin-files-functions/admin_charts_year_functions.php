<?php
function chart_get_years()
{
  include '../includes/connection.php';
  $count = 0;
  $year_sort = [];

  $query = "SELECT DISTINCT record_year FROM performance_record";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $year_sort[] = $row['record_year'];
      }
      sort($year_sort);
      $arrlength = count($year_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        echo $year_sort[$x] . ", ";
      }
    }
  }
}

///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////
/////////////////////////////////////////////////// yearly QA
function chart_yearly_score_QA()
{
  include '../includes/connection.php';
  $count = 0;

  $query = "SELECT DISTINCT record_year FROM performance_record";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $year_sort[] = $row['record_year'];

      }
      sort($year_sort);
      $arrlength = count($year_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_yearly_compute_QA($year_sort[$x], $count, $max_count);
      }
    }
  }
}

function chart_yearly_compute_QA($year, $count, $max_count)
{
  include '../includes/connection.php';
  $QA_sum = chart_QA_yearly($year);
  $num_records = chart_yearly_record_count($year);

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

function chart_yearly_record_count($year)
{
    include '../includes/connection.php';
    $count = 0;

    $query = "SELECT * FROM performance_record WHERE record_year = '$year'";
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


function chart_QA_yearly($year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_year = '$year'";
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
/////////////////////////////////////////////////// yearly CPH
function chart_yearly_score_CPH()
{
  include '../includes/connection.php';
  $count = 0;

  $query = "SELECT DISTINCT record_year FROM performance_record";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $year_sort[] = $row['record_year'];

      }
      sort($year_sort);
      $arrlength = count($year_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_yearly_compute_CPH($year_sort[$x], $count, $max_count);
      }
    }
  }
}

function chart_yearly_compute_CPH($year, $count, $max_count)
{
  include '../includes/connection.php';
  $CPH_sum = chart_CPH_yearly($year);
  $num_records = chart_yearly_record_count($year);

  $a = $CPH_sum / $num_records;

  $result_CPH = score_CPH($a);

  if($count < $max_count)
  {
    echo $result_CPH . ", ";
  }
  else
  {
    echo $result_CPH;
  }

}



function chart_CPH_yearly($year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_year = '$year'";
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
/////////////////////////////////////////////////// yearly ATT
function chart_yearly_score_ATT()
{
  include '../includes/connection.php';
  $count = 0;

  $query = "SELECT DISTINCT record_year FROM performance_record";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      $max_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result))
      {
        $year_sort[] = $row['record_year'];

      }
      sort($year_sort);
      $arrlength = count($year_sort);
      for($x = 0; $x < $arrlength; $x++)
      {
        chart_yearly_compute_ATT($year_sort[$x], $count, $max_count);
      }
    }
  }
}

function chart_yearly_compute_ATT($year, $count, $max_count)
{
  include '../includes/connection.php';
  $ATT_sum = chart_ATT_yearly($year);
  $num_records = chart_yearly_record_count($year);

  $a = $ATT_sum / $num_records;

  $result_ATT = score_ATT($a);

  if($count < $max_count)
  {
    echo $result_ATT . ", ";
  }
  else
  {
    echo $result_ATT;
  }

}



function chart_ATT_yearly($year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_year = '$year'";
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
