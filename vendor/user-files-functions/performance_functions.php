<?php
function choice_one_display_table($day, $month, $year, $emp_id)
{
  include '../includes/connection.php';
  $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year' AND emp_id='$emp_id'";
  $result = mysqli_query($con,$query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $a = $row['emp_att'];
        $b = $a * 100;
        $att_score = score_att($b);
        echo "<tr>
              <td>" . $row['emp_QA'] . "</td>
              <td>" . $row['emp_CPH'] . "</td>
              <td>" . $row['emp_att'] . "</td>
              <td>" . $row['ds_QA'] . "</td>
              <td>" . $row['ds_CPH'] . "</td>
              <td>" . $att_score . "</td>
              <td>" . $row['emp_perf'] . "</td>
              <td>" . $row['perf_comment'] . "</td>
              </tr>";
      }
    }
  }
}

function choice_checker_one($choice)
{
  include '../includes/connection.php';

  return $choice;
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

function choice_checker($choice)
{
  include '../includes/connection.php';

  if($choice == 1)
  {
    return 1;
  }
}

function choice_one($emp_id)
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
          choice_one_display_table($day, $month, $year, $emp_id);
        }
      }
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

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







function choice_two($day, $month, $year, $emp_id)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $query = "SELECT * FROM performance_record WHERE record_day='$day' AND record_month='$month' AND record_year='$year' AND emp_id='$emp_id'";
    $result = mysqli_query($con,$query);
    if($result)
    {
      if(mysqli_num_rows($result))
      {
        while($row = mysqli_fetch_array($result))
        {
          $a = $row['emp_att'];
          $b = $a * 100;
          $att_score = score_att($b);
          echo "<tr>
                <td>" . $row['emp_QA'] . "</td>
                <td>" . $row['emp_CPH'] . "</td>
                <td>" . $row['emp_att'] . "</td>
                <td>" . $row['ds_QA'] . "</td>
                <td>" . $row['ds_CPH'] . "</td>
                <td>" . $att_score . "</td>
                <td>" . $row['emp_perf'] . "</td>
                <td>" . $row['perf_comment'] . "</td>
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


function choice_three($month, $year, $emp_id)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $checker = 0;
    $checker = choice_three_record_check($emp_id, $month, $year); //check if employee has records in table according to date given
    if($checker == 1)
    {
      choice_three_display($emp_id, $month, $year);
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

function choice_three_record_check($emp_id, $month, $year)
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

function choice_three_display($emp_id, $month, $year)
{
    //get the sum of each kpi
    $QA_sum = choice_three_QAsum($emp_id, $month, $year);
    $CPH_sum = choice_three_CPHsum($emp_id, $month, $year);
    $ATT_sum = choice_three_ATTsum($emp_id, $month, $year);

    //get number of records
    $num_records = choice_three_recordCount($emp_id, $month, $year);

    //get the average of each kpi
    $a = $QA_sum / $num_records;
    $b = $CPH_sum / $num_records;
    $c = $ATT_sum / $num_records;
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
          <td>" . number_format($a, 2, '.', '') . "</td>
          <td>" . number_format($b, 2, '.', '') . "</td>
          <td>" . $c . "</td>
          <td>" . $result_ATT . "</td>
          <td>" . $result_QA . "</td>
          <td>" . $result_CPH . "</td>
          <td>" . $finals . "</td>
          <td>" . $comment . "</td>
          </tr>";
}

function choice_three_QAsum($emp_id, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

function choice_three_CPHsum($emp_id, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

function choice_three_ATTsum($emp_id, $month, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_month = '$month' AND record_year = '$year'";
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

function choice_three_recordCount($emp_id, $month, $year)
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












function choice_four($year, $emp_id)
{
  include '../includes/connection.php';
  $table_checker = table_check();

  if($table_checker == 1)
  {
    $checker = 0;
    $checker = choice_four_record_check($emp_id, $year); //check if employee has records in table according to date given
    if($checker == 1)
    {
      choice_four_display($emp_id, $year);
    }
  }
  else
  {
    echo "No recorded performances.";
  }
}

function choice_four_record_check($emp_id, $year)
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

function choice_four_display($emp_id, $year)
{
    //get the sum of each kpi
    $QA_sum = choice_four_QAsum($emp_id, $year);
    $CPH_sum = choice_four_CPHsum($emp_id, $year);
    $ATT_sum = choice_four_ATTsum($emp_id, $year);

    //get number of records
    $num_records = choice_four_recordCount($emp_id, $year);

    //get the average of each kpi
    $a = $QA_sum / $num_records;
    $b = $CPH_sum / $num_records;
    $c = $ATT_sum / $num_records;
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
          <td>" . number_format($a, 2, '.', '') . "</td>
          <td>" . number_format($b, 2, '.', '') . "</td>
          <td>" . $c . "</td>
          <td>" . $result_ATT . "</td>
          <td>" . $result_QA . "</td>
          <td>" . $result_CPH . "</td>
          <td>" . $finals . "</td>
          <td>" . $comment . "</td>
          </tr>";
}

function choice_four_QAsum($emp_id, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
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

function choice_four_CPHsum($emp_id, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
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

function choice_four_ATTsum($emp_id, $year)
{
    include '../includes/connection.php';
    $empidQuery = "SELECT * FROM performance_record WHERE emp_id = '$emp_id' AND record_year = '$year'";
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

function choice_four_recordCount($emp_id, $year)
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
