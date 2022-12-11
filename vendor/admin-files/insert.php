<?php
session_start();
include('../includes/connection.php');
include('functions_code.php');
include('date_config.php');

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


        $month = "July";
        $day = "29";
        $year = "2022";

        //counter variables
        $count = "0";
        $recordCount = "0";
        $missing_row = "1";

        //arrays for recording missing values and/or nonexistent employees
        $missing = [];
        $nonexistent = [];

        $same_name = [];
        $same_string = [];

        //initialize variables for checking
        $missing_field = "0";
        $missing_emp = "0";
        $same_spotted = "0";
        $same_reset = "0";
        $existing_record = "0";


        foreach($data as $row)
        {
            if($count > 0)
            {
                $fullname = $row['0'];
                $emp_QA = $row['1'];
                $emp_CPH = $row['2'];
                $ds_QA = $row['3'];
                $ds_CPH = $row['4'];
                $emp_att = $row['5'];
                $emp_perf = $row['6'];
                $perf_comment = $row['7'];
                $emp_id = exists_emp($fullname);
                $studentQuery = "INSERT INTO performance_record (emp_id,emp_fullname,emp_QA,emp_CPH,ds_QA,ds_CPH,emp_att,emp_perf,perf_comment,record_day,record_month,record_year) VALUES ('$emp_id','$fullname','$emp_QA','$emp_CPH','$ds_QA','$ds_CPH','$emp_att','$emp_perf','$perf_comment','$day','$month','$year')";
                                $result = mysqli_query($con, $studentQuery);
                $msg = true;
            }
            else
            {
                $count = "1";
            }
        }

        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index2.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index2.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File Type! Please try again.";
        header('Location: index2.php');
        exit(0);
    }
}
?>
