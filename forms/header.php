
<!DOCTYPE html>
<html lang="en">
    <head>

        <?php
        ob_start();
        include_once '../sys/generalx.php';
        include '../sys/security.php';

        sec_session_start();
//        if (login_check() == false) {
//            echo 'You are not authorized to access this page, please login. <br/>';
//            exit();
//        }

        $obj = new conn();
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Lead Desk</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

        <!-- v4.0.0-alpha.6 -->
        <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/style.css">
        <link rel="stylesheet" href="../dist/css/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../dist/css/et-line-font/et-line-font.css">
        <link rel="stylesheet" href="../dist/css/themify-icons/themify-icons.css">

        <!-- Chartist CSS -->
        <link rel="stylesheet" href="../dist/plugins/chartist-js/chartist.min.css">
        <link rel="stylesheet" href="../dist/plugins/chartist-js/chartist-init.css">
        <link rel="stylesheet" href="../dist/plugins/chartist-js/chartist-plugin-tooltip.css">
<script src="../dist/js/all.js" data-auto-replace-svg="nest"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper boxed-wrapper">
            <header class="main-header"> 
                <!-- Logo --> 
                <a href="index.html" class="logo" style="background-color: #2f3742;font-family: fantasy;font-size: 34px;"> 
                    <!-- mini logo for sidebar mini 50x50 pixels --> 
                 
                    <!-- logo for regular state and mobile devices --> 
                    <i>Lead CRM</i> </a> 
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar blue-bg navbar-static-top"> 
                    <!-- Sidebar toggle button-->
                    <ul class="nav navbar-nav pull-left">
                        <li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
                    </ul>
                    <div class="pull-left search-box">
                        <form action="#" method="get" class="search-form">
                            <div class="input-group">
                                <input name="search" class="form-control" placeholder="Search..." type="text">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> </button>
                                </span></div>
                        </form>
                        <!-- search form --> </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-envelope-o"></i>
                                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 new messages</li>
                                    <li>
                                        <ul class="menu">
                                            <li><a href="#">
                                                    <div class="pull-left"><img src="../dist/img/img1.jpg" class="img-circle" alt="User Image"> <span class="profile-status online pull-right"></span></div>
                                                    <h4>Alex C. Patton</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">9:30 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left"><img src="../dist/img/img3.jpg" class="img-circle" alt="User Image"> <span class="profile-status offline pull-right"></span></div>
                                                    <h4>Nikolaj S. Henriksen</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">10:15 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left"><img src="../dist/img/img2.jpg" class="img-circle" alt="User Image"> <span class="profile-status away pull-right"></span></div>
                                                    <h4>Kasper S. Jessen</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">8:45 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left"><img src="../dist/img/img4.jpg" class="img-circle" alt="User Image"> <span class="profile-status busy pull-right"></span></div>
                                                    <h4>Florence S. Kasper</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">12:15 AM</span></p>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">View All Messages</a></li>
                                </ul>
                            </li>
                            <!-- Notifications: style can be found in dropdown.less -->
                            <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell-o"></i>
                                    <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">Notifications</li>
                                    <li>
                                        <ul class="menu">
                                            <li><a href="#">
                                                    <div class="pull-left icon-circle red"><i class="icon-lightbulb"></i></div>
                                                    <h4>Alex C. Patton</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">9:30 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left icon-circle blue"><i class="fa fa-coffee"></i></div>
                                                    <h4>Nikolaj S. Henriksen</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">1:30 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left icon-circle green"><i class="fa fa-paperclip"></i></div>
                                                    <h4>Kasper S. Jessen</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">9:30 AM</span></p>
                                                </a></li>
                                            <li><a href="#">
                                                    <div class="pull-left icon-circle yellow"><i class="fa  fa-plane"></i></div>
                                                    <h4>Florence S. Kasper</h4>
                                                    <p>I've finished it! See you so...</p>
                                                    <p><span class="time">11:10 AM</span></p>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">Check all Notifications</a></li>
                                </ul>
                            </li>
                  
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar"> 
                <!-- sidebar: style can be found in sidebar.less -->
                <div class="sidebar"> 
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="info" style="font-size: large;"> 
                          <font><?php echo strtoupper($_SESSION['user_name']);?></font>
                        </div>
                    </div>

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                     
                        <li class="active treeview"> <a href="#"> <i class="fa fa-dashboard"></i> <span>Configuration</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
                            <ul class="treeview-menu">
                                <li class="active"><a href="#">Configuration</a></li>
                                <li><a href="listview.php?id=2">User Master</a></li>
                            </ul>
                        </li>

                    </ul>
                         <ul class="sidebar-menu" data-widget="tree">
                        <li class="active treeview"> <a href="#"> <i class="fa fa-dashboard"></i> <span>Lead Info</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
                            <ul class="treeview-menu">
                                <li class="active"><a href="#">Lead Info</a></li>
                                <li><a href="listview.php?id=1">Lead Listing</a></li>
                            </ul>
                        </li>

                    </ul>
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header"><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <!-- /.sidebar --> 
            </aside>


            <!-- jQuery 3 --> 
            <script src="../dist/js/jquery.min.js"></script> 

            <!-- v4.0.0-alpha.6 --> 
            <script src="../dist/bootstrap/js/bootstrap.min.js"></script> 

            <!-- template --> 
            <script src="../dist/js/niche.js"></script> 

            <!-- Chartjs JavaScript --> 
            <script src="../dist/plugins/chartjs/chart.min.js"></script>
            <script src="../dist/plugins/chartjs/chart-int.js"></script>

            <!-- Chartist JavaScript --> 
            <script src="../dist/plugins/chartist-js/chartist.min.js"></script> 
            <script src="../dist/plugins/chartist-js/chartist-plugin-tooltip.js"></script> 
            <script src="../dist/plugins/functions/chartist-init.js"></script>
    </body>


</html>
