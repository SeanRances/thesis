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

function count_da($emp_id)
{
  include '../includes/connection.php';
  $count = 0;
  $query = "SELECT * FROM da_table WHERE emp_id = '$emp_id'";
  $result = $result = mysqli_query($con,$query);
  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $count++;
      }
    }
  }

  return $count;
}

function count_goals($emp_id)
{
  include '../includes/connection.php';
  $count = 0;
  $query = "SELECT * FROM employee_goals WHERE emp_id = '$emp_id'";
  $result = $result = mysqli_query($con,$query);
  if($result)
  {
    if(mysqli_num_rows($result))
    {
      while($row = mysqli_fetch_array($result))
      {
        $count++;
      }
    }
  }

  return $count;
}

?>
