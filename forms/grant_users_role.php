<?php
ob_start();
include 'header.php';

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();
$user_id = $_GET['uid'];
$view_id = $_GET['viewid'];
$error_message = "";
$user_name = $obj->get_execute_scalar("select user_name from tbl_users where user_id = '$user_id'", $error_message);
$obj->save_log("Grant user role page opened for $user_name.", $_SERVER["REQUEST_URI"]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if save button clicked
    if (isset($_POST['btnSave'])) {
        if (save_data() == true) {
            $obj->save_log("Data saved successfully.", $_SERVER["REQUEST_URI"]);
            header("location: listview.php?id=$view_id");
            exit;
        }
    }

    // if close button clicked
    if (isset($_POST['btnClose'])) {
        $obj->save_log("Data saved successfully.", $_SERVER["REQUEST_URI"]);
        $obj->save_log("Grant user role page closed.", $_SERVER["REQUEST_URI"]);
        header("location: listview.php?id=$view_id");
        exit;
    }
}
?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grant Role to <?php echo $user_name; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Grant Role</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body">
                             <form name="grant_role_form" id="grant_role_form" method="post" >
                            <table class="table table-bordered">
                                <thead>                  
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Role Name</th>
                                        <th> Grant </th>
                                    </tr>
                                </thead>
                                <tbody>
                               
                                    <?php
                                     $row_count = 0;
                                     $sql = "select prj.role_id, prj.role_desc , 
                                case when pju.user_id is null then '' else 'checked' end as checked
                                from gm_role_master prj left join gm_user_roles pju 
                                on prj.role_id = pju.role_id and pju.user_id = '$user_id'
                                where prj.status='A' and prj.created_by=".$_SESSION['user_id']."  order by prj.role_desc";
                                 //   $sql = "select u.vendor_category_id,la.vendor_id,u.vendor_category_name , case when la.vendor_id is null then '' else 'checked' end as STATUS from mst_vendor_category u left join trn_vendor_category la on la.vendor_category_id = u.vendor_category_id and la.vendor_id=$pkey_id";
                                    $obj = new conn();
                                   // echo $sql;exit;
//$viewres = mysqli_query($obj, $sql);
                                    $result = $obj->execute($sql, $error_message);
                                    if (mysqli_num_rows($result) > 0) {
                                      $i=1;
                                        while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i;$i++; ?></td>
                                                <td><?php echo $row['role_desc']; ?></td>
                                                <td>
                                                    <input type="checkbox" name="user_role_<?php echo $row['role_id']; ?>" id="user_role_<?php echo $row['role_id']; ?>" value="<?php echo $row['role_id']; ?>"  <?php echo $row['checked'];?>>

                                                </td>
                                            </tr>
                                            <?php
                                            $row_count++;
                                        }
                                    }
                                    unset($row);
		mysqli_free_result($result);
		unset($result);
                                    ?>
                                    <tr>
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
                                        <td colspan="3">  <input class="btn btn-primary" type="submit" value="Grant Role" id="btnSave" name="btnSave"/>
                                           <a href="../frm/listview.php?id=2" class="btn btn-danger">Back</a>
                                          
                                    </tr>
                              
                                </tbody>
                            </table>
                                   </form>
                        </div>

                    </div>

                </div>
                <!-- /.col -->

                <!-- /.col -->
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<?php unset($obj);

function save_data() {
    global $obj, $user_id;
    $array = array();
    $query = "delete from gm_user_roles where user_id = '$user_id'";
    array_push($array, $query);
//echo "<pre>"; print_r($_POST);
$post = $_POST;
    $i = 0;
        foreach ($post as $key => $val) {
                if (($key == 'user_id') || ($key == 'btnSave')) {
                    continue;
                } else {
                    if (isset($val)) {
                       $query = "insert into gm_user_roles (role_id, user_id, created_by, created_on)
                        values ('$val','$user_id','{$_SESSION['user_id']}', now())";
                        array_push($array, $query);
                    }
                }
            }
//             $query = "update gm_user_master set rebuild_menu = 'Y' where user_id ='$user_id'";
//                    array_push($array, $query);
            
//    echo "<pre>";
//    print_r($array);
//exit;
    if ($obj->execute_sqli_array($array, $error_message)) {
      //  echo message_show("Data updated successfully.", "info");
        
        $obj->save_log("Grant user role page error: $error_message.", $_SERVER["REQUEST_URI"]);
           header("Location: listview.php?id=2");
    } else {
        echo message_show("ERROR: " . $error_message, "error");
    }
} ?>
<?php include 'footer.php'; 
unset($obj);
?>
