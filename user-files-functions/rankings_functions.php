<?php
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

function choice_checker($choice)
{
  include '../includes/connection.php';

  if($choice == 1)
  {
    $month = date('F');
    $year = date('Y');
    choice_one($month, $year);
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

///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
/////////////////////////////////////// choice one functions
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


function choice_one($month, $year)
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
      echo "<tr>
            <td>" . $x+1 . "</td>
            <td>" . $ar2[$x] . "</td>
            <td>" . $ar1[$x] . "</td></tr>";
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

///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
/////////////////////////////////////// choice two functions

function choice_two($day, $month, $year)
{
  include '../includes/connection.php';
  $table_checker = table_check();
  $ar1 = [];
  $ar2 = [];

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
          $checker = 0;
          $checker = choice_two_check($row['emp_fullname'], $day, $month, $year);
          if($checker == 1)
          {
            $ar1[] = $row['emp_perf'];
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
  }

  if($display == 1)
  {
    array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2);
    $arrlength = count($ar1);
    for($x = 0; $x < $arrlength; $x++)
    {
      echo "<tr>
            <td>" . $x+1 . "</td>
            <td>" . $ar2[$x] . "</td>
            <td>" . $ar1[$x] . "</td></tr>";
    }
  }
}

function choice_two_check($name, $day, $month, $year)
{
    include '../includes/connection.php';
    $checker = 0;

    $query = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_day = '$day' AND record_month = '$month' AND record_year = '$year'";
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

///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
/////////////////////////////////////// choice three functions

function choice_three($month, $year)
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
      echo "<tr>
            <td>" . $x+1 . "</td>
            <td>" . $ar2[$x] . "</td>
            <td>" . $ar1[$x] . "</td></tr>";
    }
  }
}

///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////
/////////////////////////////////////// choice four functions

function choice_four($year)
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
        $checker = choice_four_check($row['emp_fullname'], $year);
        if($checker == 1)
        {
          $ar1[] = choice_four_finals($row['emp_fullname'], $year);
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
      echo "<tr>
            <td>" . $x+1 . "</td>
            <td>" . $ar2[$x] . "</td>
            <td>" . $ar1[$x] . "</td></tr>";
    }
  }
}

function choice_four_check($name, $year)
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

function choice_four_emp_perf_sum($name, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_fullname = '$name' AND record_year = '$year'";
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

function choice_four_finals($name, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = choice_four_emp_perf_sum($name, $year);

  //get number of records
  $num_records = choice_four_recordCount($name, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  return $emp_perf_quotient;
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

 ?>
