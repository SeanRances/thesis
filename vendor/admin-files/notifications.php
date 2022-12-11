<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once '../includes/head.php';
include '../includes/function.php';
include '../includes/connection.php';
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

if (isset($_GET['mark_read'])) {
    $notif_id = $_GET['mark_read'];
    $notif_status = "READ";
    $update_notif_status = "UPDATE notifications SET notif_status='$notif_status' WHERE notif_id='$notif_id'";
    $update_notif_status_result = mysqli_query($con, $update_notif_status);
}

if (isset($_GET['mark_unread'])) {
    $notif_id = $_GET['mark_unread'];
    $notif_status = "UNREAD";
    $update_notif_status = "UPDATE notifications SET notif_status='$notif_status' WHERE notif_id='$notif_id'";
    $update_notif_status_result = mysqli_query($con, $update_notif_status);
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Notifications </title>

    <style>
        .dropdown-list-image {
            position: relative;
            height: 2.5rem;
            width: 2.5rem;
        }

        .dropdown-list-image img {
            height: 2.5rem;
            width: 2.5rem;
        }

        .btn-light {
            color: #2cdd9b;
            background-color: #e5f7f0;
            border-color: #d8f7eb;
        }
    </style>
</head>
<?php include_once '../includes/admin_navbar.php' ?>

<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <div class="container">
        <div class="row mt-4">
            <div class="col-lg-3 left">
                <div class="box mb-3 shadow-sm rounded bg-white profile-box text-center">
                    <div class="p-5">
                        <img src="../img/<?php echo $image; ?>" class="img-fluid" alt="Responsive image" />
                    </div>
                    <div class="p-3 border-top border-bottom">
                        <h5 class="font-weight-bold text-dark mb-1 mt-0"> <?php echo ($_SESSION['name']); ?></h5>
                        <p class="mb-0 text-muted"> Manager </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 right">
                <div class="box shadow-sm rounded bg-white mb-3">
                    <div class="box-title border-bottom p-3">
                        <h6 class="m-0">Recent</h6>
                    </div>
                    <div class="box-body p-0">
                        <?php
                        $id_list = [];
                        $date_list = [];
                        $emp_id_list = [];
                        $comment = [];
                        $image_list = [];
                        $notif_status = [];
                        $notif_id = [];
                        $get_notif_dates_query = "SELECT * FROM notifications";
                        $get_notif_dates_result = mysqli_query($con, $get_notif_dates_query);

                        if ($get_notif_dates_result) {
                            if (mysqli_num_rows($get_notif_dates_result)) {
                                while ($row = mysqli_fetch_array($get_notif_dates_result)) {
                                    $id_list[] = $row['id'];
                                    $date_list[] = $row['record_month'] . " " . $row['record_day'] . ", " . $row['record_year'];
                                    $emp_id_list[] = get_name($row['emp_id']);
                                    $comment[] = $row['notif_desc'];
                                    $image_list[] = get_image($row['emp_id']);
                                    $notif_status[] = $row['notif_status'];
                                    $notif_id[] = $row['notif_id'];
                                }
                            }
                        }
                        array_multisort($id_list, SORT_DESC, SORT_NUMERIC, $date_list, $emp_id_list, $comment, $image_list, $notif_status, $notif_id);
                        $notif_counts = count($id_list);
                        for ($x = 0; $x < $notif_counts; $x++) {
                        ?>
                            <!-- ITEM  -->
                            <div class="p-3 d-flex align-items-center border-bottom osahan-post-header">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="../img/<?php echo $image_list[$x]; ?>" alt="" />
                                </div>
                                <div class="font-weight-bold mr-3">
                                    <div class="text-truncate"> <?php echo $emp_id_list[$x]; ?> </div>
                                    <div class="small"> <?php echo $comment[$x]; ?> </div>
                                </div>
                                <span class="ml-auto mb-auto">
                                    <div class="btn-group">
                                        <?php
                                        if ($notif_status[$x] == "UNREAD") {
                                            echo "<a class='btn btn-success'href='?mark_read=" . $notif_id[$x] . "'><span>Mark as read</span></a>";
                                        } else {
                                            echo "<a class='btn btn-danger'href='?mark_unread=" . $notif_id[$x] . "'><span>Mark as unread</span></a>";
                                        }
                                        ?>
                                    </div>
                                    <br />
                                    <div class="text-right text-muted pt-1"><?php echo $date_list[$x]; ?></div>
                                </span>
                            </div>
                            <!-- END ITEM -->
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<?php include '../includes/script.php' ?>

</html>
