<?php
ob_start();
include 'header.php';

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}

$obj = new conn();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><i class="fa fa-angle-right"></i> Dashboard</li>
        </ol>
    </div>

    <!-- Main content -->
    <div class="content"> 
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-darkblue"> <span class="info-box-icon bg-transparent"><i class="ti-stats-up text-white"></i></span>
                    <div class="info-box-content">
                        <h6 class="info-box-text text-white">New Orders</h6>
                        <h1 class="text-white">1,150</h1>
                        <span class="progress-description text-white"> 70% Increase in 30 Days </span> </div>
                    <!--             /.info-box-content  -->
                </div>
                <!--           /.info-box  -->
            </div>
            <!--         /.col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green text-white"> <span class="info-box-icon bg-transparent"><i class="ti-face-smile"></i></span>
                    <div class="info-box-content">
                        <h6 class="info-box-text text-white">New Users</h6>
                        <h1 class="text-white">565</h1>
                        <span class="progress-description text-white"> 45% Increase in 30 Days </span> </div>
                    <!--             /.info-box-content  -->
                </div>
                <!--           /.info-box  -->
            </div>
            <!--         /.col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua"> <span class="info-box-icon bg-transparent"><i class="ti-bar-chart"></i></span>
                    <div class="info-box-content">
                        <h6 class="info-box-text text-white">Online Revenue</h6>
                        <h1 class="text-white">$5,450</h1>
                        <span class="progress-description text-white"> 50% Increase in 30 Days </span> </div>
                    <!--             /.info-box-content  -->
                </div>
                <!--           /.info-box  -->
            </div>
            <!--         /.col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange"> <span class="info-box-icon bg-transparent"><i class="ti-money"></i></span>
                    <div class="info-box-content">
                        <h6 class="info-box-text text-white">Total Profit</h6>
                        <h1 class="text-white">$8,188</h1>
                        <span class="progress-description text-white"> 35% Increase in 30 Days </span> </div>
                    <!--             /.info-box-content  -->
                </div>
                <!--           /.info-box  -->
            </div>
            <!--         /.col  -->
        </div>
        <!-- /.row --> 

    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper --> 

<?php
include 'footer.php';
?>