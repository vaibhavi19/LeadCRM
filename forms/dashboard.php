<?php
include_once '../sys/generalx.php';
include '../sys/security.php';

sec_session_start();
if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}

 $obj = new conn();
//echo "<pre>"; print_r($_SESSION);exit;
$user_img = $_SESSION['user_img'];
$username = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Card Desk</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- JQVMap -->
        <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
        <!-- summernote -->
        <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>
    <body class="sidebar-mini layout-fixed sidebar-collapse">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-dark navbar-warning" style="background-color:#752676;">
<!--                #1f4290;-->
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="main_new.php" class="nav-link">Dashboard</a>
                    </li>
                
                </ul>
                <ul class="navbar-nav ml-auto">
<!--                    <img src="../dist/img/crm1.png" alt="AdminLTE Logo" height="60px" width="60px" class="brand-image img-bordered-sm elevation-4">-->
                </ul>

            </nav>
            <!-- /.navbar -->
<?php //} ?>
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar elevation-4 sidebar-light-primary">
                <!-- Brand Logo -->
                <a href="main_new.php" class="brand-link">Lead
<!--                    <img src="../dist/img/crm.jpg" alt="AdminLTE Logo" class="brand-image img-bordered-sm elevation-3"
                         style="opacity: .8">-->
                    <span class="brand-text font-weight-light"><b>CRM</b></span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                 <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image2">
                                  
                           
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?php echo $_SESSION['user_name']; ?></a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <!--          here dynamic menus -->
                        <?php
                        $html = "";
                        getMenuElements("0");
                        echo $html;
                        ?>

                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->



            <?php

            function getMenuElements($ParentID) {
                // declare the global variable
                global $html,$obj;

             

                ////////////////////////////////////////////////start ////////////////////////////////////////////
                $query = "SELECT distinct mm.menu_id, mm.menu_caption, mm.menu_url, mm.icon_url, mm.target,mm.icon_url,
       (SELECT COUNT(*) FROM sys_menus WHERE parent_id =  mm.menu_id) AS child_count
			FROM sys_menus mm join gm_role_menu rm on mm.menu_id = rm.menu_id
				join gm_user_roles ur on ur.role_id = rm.role_id
                WHERE mm.parent_id = '$ParentID' AND  mm.status = 'A' 
				and ur.user_id = '" . $_SESSION['user_id'] . "'
                ORDER BY display_order ";
                // $query = "select * from sys_menus where parent_id = 0 and status = 'A' order by display_order";
               
                //echo $_SESSION['user_id'].' '.$query;exit;
                $result = $obj->execute($query, $error_message);
                $html = '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
                $i = 0;
                while ($row = mysqli_fetch_array($result)) {
                    if ($i == 0) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }

                    $html .= '<li class="nav-item has-treeview" id="' . $i . '" onclick="return add(' . $i . ')">';
                    $html .= '<a href="' . $row['menu_url'] . '" id="submenu_' . $i . '" class="nav-link " target="'. $row['target'] .'">';
                //    $html .= '<i class="nav-icon fas fa-tachometer-alt"></i>';
                     $html .= '<i class="nav-icon fas '.$row['icon_url'].'"></i>';
                    
                    $html .= '<p>' . $row['menu_caption'] . '<i class="right fas fa-angle-left"></i></p></a>';

                    if ($row['child_count'] > 0) {


                        $html .= '<ul class="nav nav-treeview">';
                        $query = "SELECT distinct mm.menu_id, mm.menu_caption, mm.menu_url, mm.icon_url, mm.target,mm.badge_name,
       (SELECT COUNT(*) FROM sys_menus WHERE parent_id =  mm.menu_id) AS child_count
			FROM sys_menus mm join gm_role_menu rm on mm.menu_id = rm.menu_id
				join gm_user_roles ur on ur.role_id = rm.role_id
                WHERE mm.parent_id = '" . $row['menu_id'] . "' AND  mm.status = 'A' 
				and ur.user_id = '" . $_SESSION['user_id'] . "'
                ORDER BY display_order ";
                        //	$query ="select * from sys_menus where parent_id = ".$row['menu_id']." and status = 'A' order by display_order";
                        $result1 = $obj->execute($query, $error_message);
                        if ($result1) {
                            while ($row1 = mysqli_fetch_array($result1)) {
                                $html .= ' <li class="nav-item" id="subsubmenu_' . $i . '">';
                                $html .='<a href="' . $row1['menu_url'] . '" class="nav-link active" target="'. $row1['target'] .'">';
                                $html .= ' <i class="fas fa-circle nav-icon"></i><p>' . $row1['menu_caption'] . '</p>';
                                if($row1['badge_name']!=""){
                                    $html .= '<span class="badge badge-info right">' .  get_badge_count($row1['badge_name']) . '</span>';
                                }
                                $html .= '</a> </li>';
                            
                                
                            }
                        }
                        $html .='</ul>';
                    }


                    $html .= '</li>';
                    $i++;
                }
                $html .= '</ul>';
                mysqli_free_result($result);
                unset($obj);
            }
            
            
            function get_badge_count($badge_name){
              
                global $obj;
                $error_message = "";
               $count =  $obj->get_execute_scalar("select ifnull($badge_name,0) as count from gm_user_master where user_id = '". $_SESSION['user_id'] ."'", $error_message);
               if($count==0){
               return "";    
               }else{
               return $count;    
               } 
               
            }
            
            ?>


            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="../plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="../plugins/chart.js/Chart.min.js"></script>
        <!-- Sparkline -->
        <script src="../plugins/sparklines/sparkline.js"></script>
        <!-- JQVMap -->
        <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
        <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="../plugins/moment/moment.min.js"></script>
        <script src="../plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="../plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="../dist/js/pages/dashboard.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>
        <script>
          function add(id) {
              //  $("#subsubmenu_"+id).css('display','block');
              $('a').removeClass('active');
              $("#submenu_" + id).addClass('active');
          }
        </script>
    </body>
</html>
