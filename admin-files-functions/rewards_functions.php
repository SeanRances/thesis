<?php
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
function choice_checker($choice)
{
  include '../includes/connection.php';

  if($choice == 1)
  {
    choice_one();
  }
  elseif($choice == 3)
  {
    $year = date('Y');
    choice_three($year);
  }
}

function choice_one()
{
  include '../includes/connection.php';
  $ar1 = []; //array for performance score
  $ar2 = []; //array for employee name
  $ar3 = []; //array for performance comment
  $missing = [];
  $display = 0;
  $zero_record = 0;
  $month = date('F');
  $year = date('Y');

  $query = "SELECT * FROM employees";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $checker = 0;
        $checker = choice_one_record_check($row['emp_fullname'], $month, $year);
        if($checker == 1)
        {
          $eligible_checker = choice_one_performance($row['emp_fullname'], $month, $year);
          if($eligible_checker == "Achieved" || $eligible_checker == "Surpassed")
          {
            $ar1[] = choice_one_finals($row['emp_fullname'], $month, $year);
            $ar2[] = $row['emp_fullname'];
            $ar3[] = $eligible_checker;
            $display = 1;
          }
          else
          {
            $a = 1;
          }
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
        array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2, $ar3);
        $arrlength = count($ar1);
        for($x = 0; $x < $arrlength; $x++)
        {
          $b = $ar1[$x];
          echo "<tr>
                <td>" . $ar2[$x] . "</td>
                <td>" . number_format($ar1[$x], 2, '.', '') . "</td>
                <td>" . $ar3[$x] . "</td></tr>";
        }
    }
    if($zero_record == 1)
    {
        $no_record = json_encode($missing);
        echo "These users do not have records for that date: " . $no_record;
    }
}

function choice_one_record_check($name, $month, $year)
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

function choice_one_performance($name, $month, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = choice_one_emp_perf_sum($name, $month, $year);

  //get number of records
  $num_records = choice_one_recordCount($name, $month, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  //calculate for comment
  $comment = performance_comment($emp_perf_quotient);

  return $comment;
}

function choice_one_emp_perf_sum($name, $month, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_fullname='$name' AND record_month='$month' AND record_year='$year'";
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

function choice_two($month, $year)
{
  include '../includes/connection.php';
  $ar1 = []; //array for performance score
  $ar2 = []; //array for employee name
  $ar3 = []; //array for performance comment
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
        $checker = choice_one_record_check($row['emp_fullname'], $month, $year);
        if($checker == 1)
        {
          $eligible_checker = choice_one_performance($row['emp_fullname'], $month, $year);
          if($eligible_checker == "Achieved" || $eligible_checker == "Surpassed")
          {
            $ar1[] = choice_one_finals($row['emp_fullname'], $month, $year);
            $ar2[] = $row['emp_fullname'];
            $ar3[] = $eligible_checker;
            $display = 1;
          }
          else
          {
            $a = 1;
          }
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
        array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2, $ar3);
        $arrlength = count($ar1);
        for($x = 0; $x < $arrlength; $x++)
        {
          $b = $ar1[$x];
          echo "<tr>
                <td>" . $ar2[$x] . "</td>
                <td>" . number_format($ar1[$x], 2, '.', '') . "</td>
                <td>" . $ar3[$x] . "</td></tr>";
        }
    }
    if($zero_record == 1)
    {
        $no_record = json_encode($missing);
        echo "These users do not have records for that date: " . $no_record;
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
/////////////////////////////////////// choice three functions

function choice_three($year)
{
  include '../includes/connection.php';
  $ar1 = []; //array for performance score
  $ar2 = []; //array for employee name
  $ar3 = []; //array for performance comment
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
        $checker = choice_three_record_check($row['emp_fullname'], $year);
        if($checker == 1)
        {
          $eligible_checker = choice_three_performance($row['emp_fullname'], $year);
          if($eligible_checker == "Achieved" || $eligible_checker == "Surpassed")
          {
            $ar1[] = choice_three_finals($row['emp_fullname'], $year);
            $ar2[] = $row['emp_fullname'];
            $ar3[] = $eligible_checker;
            $display = 1;
          }
          else
          {
            $a = 1;
          }
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
        array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2, $ar3);
        $arrlength = count($ar1);
        for($x = 0; $x < $arrlength; $x++)
        {
          $b = $ar1[$x];
          echo "<tr>
                <td>" . $ar2[$x] . "</td>
                <td>" . number_format($ar1[$x], 2, '.', '') . "</td>
                <td>" . $ar3[$x] . "</td></tr>";
        }
    }
    if($zero_record == 1)
    {
        $no_record = json_encode($missing);
        echo "These users do not have records for that date: " . $no_record;
    }
}

function choice_three_record_check($name, $year)
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

function choice_three_performance($name, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = choice_three_emp_perf_sum($name, $year);

  //get number of records
  $num_records = choice_three_recordCount($name, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  //calculate for comment
  $comment = performance_comment($emp_perf_quotient);

  return $comment;
}

function choice_three_emp_perf_sum($name, $year)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE emp_fullname='$name' AND record_year='$year'";
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

function choice_three_finals($name, $year)
{
  //get sum of emp_perf for the given year and month
  $emp_perf_sum = choice_three_emp_perf_sum($name, $year);

  //get number of records
  $num_records = choice_three_recordCount($name, $year);

  //get the average of each kpi
  $emp_perf_quotient = $emp_perf_sum / $num_records;

  return $emp_perf_quotient;
}

function choice_three_recordCount($name, $year)
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

function choice_four($year)
{
  include '../includes/connection.php';
  $ar1 = []; //array for performance score
  $ar2 = []; //array for employee name
  $ar3 = []; //array for performance comment
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
        $checker = choice_three_record_check($row['emp_fullname'], $year);
        if($checker == 1)
        {
          $eligible_checker = choice_three_performance($row['emp_fullname'], $year);
          if($eligible_checker == "Achieved" || $eligible_checker == "Surpassed")
          {
            $ar1[] = choice_three_finals($row['emp_fullname'], $year);
            $ar2[] = $row['emp_fullname'];
            $ar3[] = $eligible_checker;
            $display = 1;
          }
          else
          {
            $a = 1;
          }
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
        array_multisort($ar1, SORT_DESC, SORT_NUMERIC, $ar2, $ar3);
        $arrlength = count($ar1);
        for($x = 0; $x < $arrlength; $x++)
        {
          $b = $ar1[$x];
          echo "<tr>
                <td>" . $ar2[$x] . "</td>
                <td>" . number_format($ar1[$x], 2, '.', '') . "</td>
                <td>" . $ar3[$x] . "</td></tr>";
        }
    }
    if($zero_record == 1)
    {
        $no_record = json_encode($missing);
        echo "These users do not have records for that date: " . $no_record;
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
