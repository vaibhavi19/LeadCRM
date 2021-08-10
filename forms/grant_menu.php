<?php
ob_start();
include('header.php');
$pagetext = 'Grant Menu';
$role_id = $_REQUEST['rid'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnClose'])) {
        $obj->save_log("Grant menu rights page closed.", $_SERVER["REQUEST_URI"]);
        header("location: listview.php?id=1");
        exit;
    }
    if (isset($_POST['btnSave'])) {
        if (save_data() == true) {
            $obj->save_log("Grant menu rights page - data saved succesfully.", $_SERVER["REQUEST_URI"]);
            header("location: listview.php?id=3");
            exit;
        }
    }
}
?>
<!--<style>
    #accordion1 li.panel{
        margin-bottom: 0px;
    }
</style>-->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
<!--            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Grant Menu Role</h1>
                </div> /.col 
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $pagetext; ?> Role</li>
                    </ol>
                </div> /.col 
            </div>-->
            
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-orange ">
                        <div class="card-header">
                            <h3 class="card-title">Grant menu rights to Roles</h3>
                        </div>
                        <br><br>
                        <form name="form1" id="form1" method="post">
                            <div class="col-md-6">
                            <?php
            $elementStructure = "";
            getMenuElements2("0");
            echo $elementStructure;
                            ?>
                                </div>
                            <input type="submit" class="btn btn-primary" value="Submit" id="submit" name="btnSave"/>
                            <input type="submit" class="btn btn-danger" value="Cancel" id="cancel" name="btnClose"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<?php
unset($obj);
function getMenuElements2($ParentID) {
    global $elementStructure, $obj, $role_id;

    $query = "select sm.menu_id, sm.menu_caption, 
                        (SELECT COUNT(*) FROM sys_menus WHERE parent_id =  sm.menu_id) AS child_count,
                        case when ifnull(rm.menu_id,0) = 0 then '' else 'checked' end as chk
                        from sys_menus sm left join gm_role_menu rm on sm.menu_id = rm.menu_id
                        and rm.role_id = '$role_id' and rm.menu_type = 'M' where sm.status = 'A' and sm.parent_id = '$ParentID' order by sm.display_order";

    $obj = new conn();
    $result = $obj->execute($query, $error_message);
     $elementStructure = "";
     $i = 0;
  while ($row = mysqli_fetch_array($result)) {
        
     $elementStructure .=  "<div id='accordion_".$row['menu_id']."'><div class='card'>";
     $elementStructure .= "<div class='card-header collapsed' data-toggle='collapse'
                                             data-target='#purchbillCollase_".$row['menu_id']."'>
                                            <i class='fas fa-plus float-left '></i>
                                            <b class='header-title float-left'>&nbsp;&nbsp;&nbsp;
                                                <input type='checkbox' name='chkMenuID[]' id='chkMenuID[]' value='m:{$row['menu_id']}' " . (($row['chk'] == "checked") ? "checked" : "") . ">
                                               ".$row['menu_caption']."
                                            </b>
                                        </div>";
       If ($row['child_count'] > 0) {
               $elementStructure .= "<div id='purchbillCollase_".$row['menu_id']."' class='collapse' data-parent='#accordion_".$row['menu_id']."'>
                                            <div class='card-body'>";
     
      $query = "select sm.menu_id, sm.menu_caption, 
                        (SELECT COUNT(*) FROM sys_menus WHERE parent_id =  sm.menu_id) AS child_count,
                        case when ifnull(rm.menu_id,0) = 0 then '' else 'checked' end as chk
                        from sys_menus sm left join gm_role_menu rm on sm.menu_id = rm.menu_id
                        and rm.role_id = '$role_id' and rm.menu_type = 'M' where sm.status = 'A' and sm.parent_id = '".$row['menu_id']."' order by sm.display_order";

    $obj = new conn();
    $resultchild = $obj->execute($query, $error_message);
     while ($rowchild = mysqli_fetch_array($resultchild)) {     
                   $elementStructure .= "<div class='card-header'>
                                            <i class='fas fa-minus float-left '></i>
                                            <b class='header-title float-left'>&nbsp;&nbsp;&nbsp;
                                                <input type='checkbox' name='chkMenuID[]' id='chkMenuID[]' value='m:{$rowchild['menu_id']}' " . (($rowchild['chk'] == "checked") ? "checked" : "") . ">
                                               ".$rowchild['menu_caption']."
                                            </b>
                                        </div>";
     }
     
              $elementStructure .= "</div></div>";
        }
 
      $elementStructure .= "</div></div>";
  }
}

function save_data() {
    global $obj, $role_id;
    $array = array();
    array_push($array, "delete from gm_role_menu where role_id = '$role_id';");
    $checkBox = $_POST['chkMenuID'];

    for ($i = 0; $i < sizeof($checkBox); $i++) {
        $menu_id = substr($checkBox[$i], 2);
        $query = "INSERT INTO gm_role_menu (role_id, menu_id, menu_type) VALUES ('$role_id','$menu_id','M');";
        array_push($array, $query);
    }
    $user_list = $obj->get_execute_scalar("select group_concat(user_id) from gm_user_roles where role_id ='$role_id'", $error_message);

//     $query = "update gm_user_master set rebuild_menu = 'Y' where user_id in ($user_list) and status = 'A'";
//        array_push($array, $query);
    
    $error_message = "";
    if (!$obj->execute_sqli_array($array, $error_message)) {
        echo message_show($error_message, "error");
        $obj->save_log("Grant menu rights page error: $error_message.", $_SERVER["REQUEST_URI"]);

        return false;
    } else {
        return true;
    }
}
?>
































<?php
include('footer.php');
