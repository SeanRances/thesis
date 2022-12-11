<?php
function remove_da_employees_table($emp_id)
{
  include '../includes/connection.php';
  $emp_da = 0;
  $query = "UPDATE employees SET emp_da = '$emp_da' WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);
}

function remove_da_da_table($emp_id)
{
  session_start();
  include '../includes/connection.php';
  $query = "DELETE FROM da_table WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);
  echo'<script>alert("Employee DA rolled back")</script>';
  echo("<script>window.location = 'admin_da.php';</script>");
}

function check_date($date1, $date2)
{
  if ($date1 > $date2)
  {
    echo'<script>alert("Invalid! Start date cannot be later than End date.")</script>';
    echo("<script>window.location = 'admin_da.php';</script>");
  }
else
    return 0;

}

function insert_into_goals_table($emp_id, $goal_QA, $goal_CPH, $goal_ATT, $date1, $date2)
{
  include '../includes/connection.php';
  //convert date into string formats
  $temp = explode('-', $date1);
  $month = str_replace("0", "", $temp[1]);
  $year1 = $temp[0];
  $day1 = str_replace("0", "", $temp[2]);
  $dateObj   = DateTime::createFromFormat('!m', $month);
  $monthName = $dateObj->format('F'); // March
  $month1 = $monthName;

  $temp = explode('-', $date2);
  $month = str_replace("0", "", $temp[1]);
  $year2 = $temp[0];
  $day2 = str_replace("0", "", $temp[2]);
  $dateObj   = DateTime::createFromFormat('!m', $month);
  $monthName = $dateObj->format('F'); // March
  $month2 = $monthName;

  $query = "SELECT * FROM employees WHERE emp_id = '$emp_id'";
  $result = mysqli_query($con, $query);

  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $emp_name = $row['emp_fullname'];
        $goal_id = 100;
        $goal_status = "In Progress";
        $add_query = "INSERT INTO employee_goals (goal_id,emp_id,emp_fullname,goal_QA,goal_CPH,goal_ATT,start_day,start_month,start_year,end_day,end_month,end_year,goal_status) VALUES ('$goal_id','$emp_id','$emp_name','$goal_QA','$goal_CPH','$goal_ATT','$day1','$month1','$year1','$day2','$month2','$year2','$goal_status')";
        $result = mysqli_query($con, $add_query);
        echo("<script>window.location = 'admin_da.php';</script>");
      }
    }
  }
}
 ?>
