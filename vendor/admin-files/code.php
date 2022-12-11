<?php
session_start();
include('../includes/connection.php');
include('functions_code.php');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $day = date('j');
        $month = date('F');
        $year = date('Y');

        //counter variables
        $count = "0";
        $recordCount = "0";
        $missing_row = "1";

        //arrays for recording missing values and/or nonexistent employees
        $missing = [];
        $nonexistent = [];

        $same_name = [];
        $same_string = [];

        $invalid_values = [];

        //initialize variables for checking
        $missing_field = "0";
        $missing_emp = "0";
        $same_spotted = "0";
        $same_reset = "0";
        $existing_record = "0";
        $value_checker = "0";


        foreach($data as $row)
        {
            if($count > 0)
            {
                $missing_row++;
                $fullname = $row['0'];
                $emp_QA = $row['1'];
                $emp_CPH = $row['2'];
                $ds_CPH = $row['3'];
                $ds_QA = $row['4'];
                $emp_att = $row['5'];
                $emp_perf = $row['6'];
                $perf_comment = $row['7'];

                //check if any of the fields in the row have missing values
                $missing_field = emptyChecker($fullname,$emp_QA,$emp_CPH,$ds_QA,$ds_CPH,$emp_att,$emp_perf,$perf_comment);

                if($missing_field == 1)
                {
                    $missing[] = $missing_row;
                    $field_missing = 1;
                }
                else
                {
                    //check if employee exists in the database
                    $emp_id = exists_emp($fullname);

                    if(empty($emp_id))
                    {
                        $nonexistent[] = $missing_row;
                        $missing_emp = 1;
                    }
                    else
                    {
                        //check if there are duplicates
                            $j = 0;
                            foreach($same_name as $name_check)
                            {
                                $same_name[$j] = strtolower($name_check);
                                $fullname_check = strtolower($fullname);

                                if($same_name[$j] == $fullname_check)
                                {
                                    $same_reset = 1;
                                }
                                $j++;
                            }

                        if($same_reset == 1)
                        {
                            $same_string[] = $missing_row;
                            $same_spotted = 1;
                        }
                        else
                        {
                            $dupRecord_check= exist_record($emp_id, $day, $month, $year);

                            if($dupRecord_check == 1)
                            {
                                $query_overwrite = "UPDATE performance_record SET emp_QA='$emp_QA', emp_CPH='$emp_CPH', ds_QA='$ds_QA', ds_CPH='$ds_CPH', emp_att='$emp_att', emp_perf='$emp_perf', perf_comment='$perf_comment' WHERE record_day='$day' AND record_month='$month' AND record_year='$year' AND emp_fullname='$fullname'";
                                $result = mysqli_query($con, $query_overwrite);
                                $recordCount++;
                                $msg = true;
                            }
                            else
                            {
                              $end_check = 0;
                              $end_check = end_month_checker($day);
                                if($end_check == 1)
                                {
                                    $da_detected = calculate_month($fullname, $month, $year);

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
                                        $query = "UPDATE employees SET emp_da = '$add_da'";
                                        $result = $result = mysqli_query($con, $query);
                                    }

                                  //check if they have goals
                                  goal_check($emp_id);
                                  $Query = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$fullname','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_att','$emp_perf','$perf_comment','$day','$month','$year')";
                                  $result = mysqli_query($con, $Query);
                                  $msg = true;
                                  $recordCount++;
                                }
                                else
                                {
                                  $field_value_checker = valid_value_checker($emp_QA, $emp_CPH, $ds_QA, $ds_CPH, $emp_att, $emp_perf, $perf_comment);

                                  if($field_value_checker == 1)
                                  {
                                    $invalid_values[] = $missing_row;
                                    $value_checker = 1;
                                  }
                                  else
                                  {
                                    $Query = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$fullname','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_att','$emp_perf','$perf_comment','$day','$month','$year')";
                                    $result = mysqli_query($con, $Query);
                                    $msg = true;
                                    $recordCount++;
                                  }
                                }
                            }
                        }
                        $same_reset = 0;
                        $same_name[] = $fullname;
                    }

                }

            }
            else
            {
                $count = "1";
            }

            $string2 = json_encode($missing); //empty fields
            $string3 = json_encode($nonexistent); //employee not in database
            $string4 = json_encode($same_string); //name duplicated
            $string5 = json_encode($invalid_values); //invalid value detected in the field
        }

        if(isset($msg))
        {
            $session_result = session_text($recordCount, $field_missing, $missing_emp, $same_spotted, $value_checker, $string2, $string3, $string4, $string5);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: upload_file.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File Type! Please try again.";
        header('Location: upload_file.php');
        exit(0);
    }
}
?>
