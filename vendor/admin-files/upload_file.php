<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
include '../admin-files-functions/upload_file_functions.php';
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$user_data = check_login($con);
$name =  $_SESSION['name'];

$_SESSION['usertype'] = ($user_data['usertype'] == 'admin');

if ($_SESSION['usertype'] == 'admin') {
    echo "";
} else {
    echo ("<script>alert('You are not allowed to access this webpage!')</script>");
    echo ("<script>window.location = '../index.php';</script>");
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> User </title>
</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>



    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">

                <?php
                if (isset($_SESSION['message'])) {
                    echo "<h4>" . $_SESSION['message'] . "</h4>";
                    unset($_SESSION['message']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary"> Employee Scorecard </h6>
                    </div>
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-daily-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#upload" role="tab" aria-controls="nav-home" aria-selected="true"> <span class="m-0 font-weight-bold text-primary">
                                    Upload </span> </a>
                            <a class="nav-item nav-link" id="nav-monthly-tab" data-toggle="tab" href="#insert" role="tab" aria-controls="nav-profile" aria-selected="false"> <span class="m-0 font-weight-bold text-primary"> Insert </span> </a>
                        </div>
                    </nav>
                    <!-- tabs for scorecard -->
                    <div class="card-body">
                        <!-- upload scorecard tab -->
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="nav-upload-tab">
                                <form action="code.php" method="POST" enctype="multipart/form-data">

                                    <input type="file" name="import_file" class="form-control" />
                                    <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>
                                    <a href="download.php?path=downloads/template.xlsx"><button type="button" name="save_excel_data" class="btn btn-light mt-3">Download
                                            Template</button></a>
                                </form>

                            </div>

                            <!-- form tab -->
                            <div class="tab-pane fade" id="insert" role="tabpanel" aria-labelledby="nav-insert-tab">


                                <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                                    <div class="col-lg-8">
                                        <label><b>Select number of records:</b></label>
                                        <select name='num_employees' required>
                                            <?php echo $shownum; ?>
                                        </select><br><br>
                                        <input class=" btn btn-primary" type='submit' name='select_employees' value='Submit' />
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['select_employees'])) {
                                    $num = $_POST['num_employees'];
                                ?>
                                    <br>
                                    <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                                        <label>Select date: </label>
                                        <input type="date" name="date" required /><br>
                                        <?php

                                        for ($a = 0; $a < $num; $a++) {
                                        ?>
                                            <div class="col-12">
                                                <label> Choose Employee </label>
                                                <select name="employee_name[]" class="custom-select custom-select-sm form-control form-control-sm" required>
                                                    <?php echo $showemp; ?>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label>New QA: </label>
                                                <input class="form-control form-control-sm" type="number" name="emp_qa[]" min="0" max="100" required>

                                            </div>
                                            <div class="col-6">

                                                <label>New CPH: </label>
                                                <input class="form-control form-control-sm" type="number" name="emp_cph[]" min="0" required>
                                            </div>
                                            <div class="col-6">

                                                <label><b>Attendance:</b></label>
                                                <select name="emp_att[]" required>
                                                    <option value="1"> Present </option>
                                                    <option value="0"> Absent </option>
                                                </select><br><br>

                                            </div>
                                        <?php
                                        }

                                        ?>
                                        <div class="footer">
                                            <input type="hidden" name="count" value="<?php echo $num; ?>">
                                            <input type="submit" name="submit_performance" value="Submit" />

                                        </div>
                                    </form>
                                <?php
                                }


                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

</body>

<?php include '../includes/script.php' ?>

</html>