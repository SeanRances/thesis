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

        $month = date('F');
        $year = date('Y');
        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
              $fullname = $row['0'];
              $emp_QA = $row['1'];
              $emp_CPH = $row['2'];
              $ds_CPH = $row['3'];
              $ds_QA = $row['4'];
              $emp_att = $row['5'];
              $emp_perf = $row['6'];
              $perf_comment = $row['7'];

              $emp_id = exists_emp($fullname);
                echo $fullname . "<br>" . $emp_QA . "<br>" . $emp_CPH . "<br>" . $ds_CPH . "<br>" . $ds_QA . "<br>" . $emp_att . "<br>" . $emp_perf . "<br>" . $perf_comment . "<br>";
                $da_detected = calculate_month($fullname, $month, $year);
                echo "DA Detected: " . $da_detected . "<br>";

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
                    insert_into_da_table($emp_id, $fullname, $month, $year);

                    $add_da++;
                    $query = "UPDATE employees SET emp_da = '$add_da' WHERE emp_id = '$emp_id'";
                    $result = $result = mysqli_query($con, $query);
                }

                $msg = true;

            }
            else
            {
                $count = "1";
            }
        }
        
    if(isset($msg))
        {
            $_SESSION['message'] = "Successfully Imported 6 records.";
            header('Location: upload_file.php');
            exit(0);
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
        $_SESSION['message'] = "Invalid File";
        header('Location: upload_file.php');
        exit(0);
    }
}
?>
