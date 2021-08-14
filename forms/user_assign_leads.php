<?php
include('header.php');
$obj = new conn();
$hierarchy_assign_list = $obj->get_execute_scalar("SELECT GROUP_CONCAT(hierarchy_user_id) as hierarchy_list FROM `gm_user_hierarchy` WHERE user_id=" . $_SESSION['user_id'], $error_message);

$_SESSION['forwarded_date_from'] = "";
$_SESSION['forwarded_date_to'] = "";
$_SESSION['txtSource'] = "";
$_SESSION['txtLeadStatus'] = "";


$comment = "";
$status1 = 'P';

$forwarded_by_font_color = $vendor_name_font_color = $forwarded_from_date_font_color = $forwarded_to_date_font_color = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $condition = get_condition();

    if (isset($_POST['btnForward']) && $_POST['btnForward'] != "") {
//        echo "<pre>";
//        print_r($_SESSION);
//        exit;
        $condition = get_condition();

        $forward_to_id = explode('~', $_POST['cmbForwardTo']);
        $forward_to = $forward_to_id[0];
        $forward_to_name = $forward_to_id[1];

        if (isset($_POST['btnForward'])) {
            $remarks = $_POST['txtComment'];
            $forwordto = $_POST['cmbForwardTo'];
            $count = $_POST['example1_length'];
            $chk = $_POST['chk'];
            $example1_length = $_POST['example1_length'];

            if (!isset($_POST['chk'])) {
                echo message_show("Please select atleast one lead to forward", "info");
            } else {

//                echo "<pre>";
//                print_r($_SESSION);
//                exit;

                $error_message = "";
                $sql_array = array();
                $count = 0;
                for ($i = 0; $i < count($_POST['chk']); $i++) {
                    $lead_id = $_POST['chk'][$i];
                    $query = "insert into trn_lead_assignment (lead_id, user_id, transfer_from, transfer_to, remarks , status, created_on) values  ( ";
                    $query = $query . replaceBlank($lead_id) . "," . $_SESSION['user_id'] . "," . replaceBlank($_SESSION['user_id']) . "," . replaceBlank($forwordto) . "," . replaceBlank($remarks) . ",'P',now())";
                    array_push($sql_array, $query);
                    $query = "update " . $_SESSION['lead_form_table'] . " set lead_status = 'I' where lead_id = '$lead_id'";
                    array_push($sql_array, $query);
                    $count++;
                }
    
                $obj->execute_sqli_array($sql_array, $error_message);
                if ($error_message == "") {
                    $activity_desc = strtoupper($_SESSION['user_name']) . ' has transfered for the lead <b>#' . $lead_id . "</b> on " . date('d-M-Y') . " with comments '" . $remarks . "'";
                    $log_response = $obj->save_lead_log($activity_desc, 'TRANSFER_LEAD', $lead_id, $_SESSION['client_id']);
                    echo message_show($count . " lead transfered successfully", "info");
                    // EMAIL END     
                } else {
                    $obj->save_log("Error occured in transfering leads : $error_message.", $_SERVER["REQUEST_URI"]);
                    echo message_show("ERROR: " . $error_message, "error");
                }
            }
        }






        //  $obj->execute_sqli_array($sql_array, $error_message);
    }


    if (isset($_POST['btnView']) && $_POST['btnView'] != "") {



        $condition = get_condition();
    }

    if (isset($_POST['btnClear']) && $_POST['btnClear'] != "") {
        $_SESSION['forwarded_date_from'] = "";
        $_SESSION['forwarded_date_to'] = "";
        $_SESSION['vendor_name'] = "";
        $_SESSION['forwarded_by_id'] = "";



        $forwarded_from_date_font_color = "";
        $forwarded_to_date_font_color = "";
        $forwarded_by_font_color = "";
        $vendor_name_font_color = "";
        $condition = "";
    }
} else {
    $condition = get_condition();
    $obj->save_log("My Bill Inbox page opened.", $_SERVER["REQUEST_URI"]);
}

function get_condition() {

    global $forwarded_by_font_color, $vendor_name_font_color, $forwarded_from_date_font_color, $forwarded_to_date_font_color;
    global $stage_id, $source_id;
//    echo "<pre>";
//    print_r($_POST);
//    exit;
    $_SESSION['forwarded_date_from'] = isset($_POST['txtForwardedDateFrom']) ? $_POST['txtForwardedDateFrom'] : $_SESSION['forwarded_date_from'];
    $_SESSION['forwarded_date_to'] = isset($_POST['txtForwardedDateTo']) ? $_POST['txtForwardedDateTo'] : $_SESSION['forwarded_date_to'];
    $_SESSION['txtSource'] = isset($_POST['txtSource']) ? $_POST['txtSource'] : $_SESSION['txtSource'];
    $_SESSION['txtLeadStatus'] = (isset($_POST['txtLeadStatus'])) ? $_POST['txtLeadStatus'] : $_SESSION['txtLeadStatus'];
    $stage_id = isset($_POST['txtLeadStatus']) ? $_POST['txtLeadStatus'] : "";

    $source_id = isset($_POST['txtSource']) ? $_POST['txtSource'] : "";
    $condition = $condition1 = $condition2 = $condition3 = "";

    // BILL FROM DATE AND TO DATE
    if ($_SESSION['forwarded_date_from'] != "" && $_SESSION['forwarded_date_to'] != "") {
        $condition1 .= " and date(a.created_on) between " . replaceDate($_SESSION['forwarded_date_from']) . " and  " . replaceDate($_SESSION['forwarded_date_to']) . "";
    } else {
        $condition1 = "";
    }


    if ($_SESSION['txtSource'] != "") {
        $condition2 .= " and a.source_id = '" . ($_SESSION['txtSource']) . "'";
    } else {
        $condition2 = "";
    }
    if ($_SESSION['txtLeadStatus'] != "") {
        $condition3 .= " and a.stage_id in (" . $_SESSION['txtLeadStatus'] . ") ";
    } else {
        $condition3 = "";
    }

    $condition = $condition1 . "" . $condition2 . "" . $condition3;
//    $forwarded_from_date_font_color = $_SESSION['forwarded_date_from'] != "" ? "class='flashit'" : "";
//    $forwarded_to_date_font_color = $_SESSION['forwarded_date_to'] != "" ? "class='flashit'" : "";
//    $forwarded_by_font_color = $_SESSION['forwarded_by_id'] != "" ? "class='flashit'" : "";
//    $vendor_name_font_color = $_SESSION['txtSource'] != "" ? "class='flashit'" : "";
    return $condition;
}
?>
<link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <form  role="form" id="tran_unassigned_bills1" name="tran_unassigned_bills1" method="post">
                        <div class="card card-cyan">
                            <div class="card-header">
                                Assign Leads       <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <!--                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>-->
                                </div>

                            </div>

                            <div class="card-body " >
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label <?php echo $forwarded_by_font_color; ?>>Source</label>
                                            <select class="form-control select2bs4" style="width: 100%;" name="txtSource" id="txtSource">
<?php
$query = "SELECT source_id ,source_name  FROM `mst_sources`";
echo $obj->fill_combo($query, $source_id, false);
?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label <?php echo $vendor_name_font_color; ?>>Lead Status</label>
                                            <select class="form-control select2bs4" style="width: 100%;" name="txtLeadStatus" id="txtLeadStatus">
<?php
$query = "select stage_id,stage_name from mst_lead_stages";
echo $obj->fill_combo($query, $stage_id, false);
?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label <?php echo $forwarded_from_date_font_color; ?>>Created From Date</label>
                                            <div class="input-group date" id="fromdate" data-target-input="nearest">
                                                <input type="text" id="txtForwardedDateFrom" name="txtForwardedDateFrom" value="<?php echo $_SESSION['forwarded_date_from']; ?>" class="form-control datetimepicker-input" data-target="#fromdate"/>
                                                <div class="input-group-append" data-target="#fromdate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label <?php echo $forwarded_to_date_font_color; ?>>Created To Date</label>
                                            <div class="input-group date" id="todate" data-target-input="nearest">
                                                <input type="text" id="txtForwardedDateTo" name="txtForwardedDateTo" value="<?php echo $_SESSION['forwarded_date_to']; ?>" class="form-control datetimepicker-input" data-target="#todate"/>
                                                <div class="input-group-append" data-target="#todate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">



                                    <div class="col-md-6"> 
                                        <label>  Options</label><br>
                                        <input type="submit" name="btnView" id="btnView" class="Small .btn-sm btn-success" value="Apply Filter"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="submit" name="btnClear" id="btnClear" class="Small .btn-sm btn-primary" value="Clear Filter"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    </div> 


                                </div>

                            </div>
                            <!-- /.card-header -->

                            <!-- /.card-body -->
                        </div>
                    </form>


                    <div class="card">


                        <!-- /.card-header -->
                        <form  role="form" id="tran_unassigned_bills" name="tran_unassigned_bills" method="post">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--                                            <label>Forword To</label>-->
                                            <select class="form-control" name="cmbForwardTo" id="cmbForwardTo" >
<?php
$query = "select concat(user_id,'~',upper(user_name))as user_id , upper(user_name) as user_name ";
$query = $query . " from tbl_users where status = 'A' and created_by=" . replaceBlank($_SESSION['user_id']) . " and user_type='U' ";

$query = $query . " order by user_name ";

echo $obj->fill_combo($query, $user_id, false, 'Select');
?>
                                            </select>
                                                <?php echo show_validation_message('val_cmbForwardTo', 'Please select user.'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" name="txtComment" id="txtComment" class="form-control" required placeholder="Enter Remark" value="<?php echo $comment; ?>">
                                            &nbsp;<?php //echo "<b><font size=3>Total Count    : " . $total_count . "</font></b>";            ?>    
                                        </div>  
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--                                            <label>Submit</label>-->
                                            <input type="submit" class="form-control btn btn-primary" name="btnForward" id="btnForward" value="Assign Leads"  onclick="return validateForm();">


                                            <!--                                               <button type="submit" name="btnForward" id="btnForward" class="btn btn-primary">Forward</button>-->
                                            <!--                                            <input type="submit" class="form-control btn btn-primary" name="btnForward" id="btnForward" value="Forward">-->

                                        </div>
                                    </div>

                                </div>

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input name="empty" id="empty" onclick="CheckAll(this);" type="checkbox">
                                            </th>
                                            <th>SR.NO</th>
                                            <th>Lead ID</th>
                                            <th> View</th>
                                            <th> Client Name</th>
                                            <th> Display Name</th>
                                            <th>Mobile Number</th>
                                            <th>Whatsapp Number</th> 
                                            <th> Email</th>
                                            <th> Flat type</th>
                                            <th> Flat Size</th>
                                            <th> Project Type</th>
                                            <th> Property Type</th>
                                            <th> Area</th>
                                            <th> Location</th>
                                            <th> Budget</th>



                                        </tr>
                                    </thead>
                                    <tbody>
<?php
if ($_SESSION['lead_form_table'] != "") {
   // $listview_sql = "select * from " . $_SESSION['lead_form_view'] . " a  where 1=1 and a.lead_status ='N' and  a.user_id=" . $_SESSION['user_id'] . $condition;
  
      // $listview_sql = "select * from " . $_SESSION['lead_form_view'] . " a  where 1=1 and a.lead_status ='N' and  a.user_id=" . $_SESSION['user_id'] . $condition;
  $listview_sql = "select * from ".$_SESSION['lead_form_view']." a left join trn_lead_assignment t on t.lead_id=a.lead_id where 1=1 and a.lead_status ='I' and t.transfer_to=".$_SESSION['user_id'];
} else {
    $listview_sql = "select * from mst_lead a  where 1=1 and a.user_id=" . $_SESSION['user_id'] . $condition;
    
    
    
}
//echo $listview_sql;exit;
$result = $obj->execute($listview_sql, $error_message);
$row_count = $row_total = 0;

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        //   echo "<pre>"; print_r($row);exit;
        // $row_total = $row_total + $row['amount'];
        echo "<tr id=\"tr_" . $row['lead_id'] . "\" onclick=\"OnClickRow(this.id, 'chk_" . $row['lead_id'] . "');\">";
        echo "<td><input id=\"chk_" . $row['lead_id'] . "\"  type=\"checkbox\" class=\"chk_class\" name=\"chk[]\" value=" . $row['lead_id'] . "></td>";
        echo "<td>" . ($row_count + 1) . "</td>";
        echo "<td>" . "#".$row['lead_id'] . "</td>";
        echo "<td><a href=\"dashboard_lead.php?lid=" . $row['lead_id'] . "&type=ib\" target=\"_blank\">View Lead</td>";
        echo "<td>" . $row['client_name'] . "</td>";
        echo "<td>" . $row['display_name'] . "</td>";
        echo "<td>" . $row['mobile_number'] . "</td>";
        echo "<td>" . $row['whatsapp_number'] . "</td>";
        echo "<td>" . $row['email_id'] . "</td>";
        echo "<td>" . $row['flat_type'] . "</td>";
        echo "<td>" . $row['flat_size'] . "</td>";
        echo "<td>" . $row['project_type'] . "</td>";
        echo "<td>" . $row['property_type'] . "</td>";
        echo "<td>" . $row['area'] . "</td>";
        echo "<td>" . $row['location'] . "</td>";
        echo "<td>" . $row['budget'] . "</td>";

        echo "</tr>";
        $row_count++;
    }
}
if ($row_count == 0) {
    //   printf("<tr><td colspan=12><b>No data found!</b></td></tr>");
}
?>

                                    <input type="hidden" name="txtPKey" id="txtPKey" value="<?PHP echo $txtPKey; ?>"/>
                                </table>
                            </div>
                        </form>
                        <!-- /.card-body -->
                    </div>
                </div><!-- /.container-fluid -->
            </div></div>
    </section>

    <!-- /.content -->
</div>

<link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>


<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
                                                    $(document).ready(function() {
                                                        $("#example1").DataTable({
                                                            "responsive": true,
                                                            "autoWidth": false,
                                                        });
                                                        $('.select2').select2();
                                                        $('.select2bs4').select2({
                                                            theme: 'bootstrap4'
                                                        });



                                                        $('#fromdate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                        $('#todate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                    });


                                                    $("#fromdate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });
                                                    $("#todate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });

                                                    function validateForm() {
                                                        var valreturn = true;
                                                        var cmbForwardTo = $('#cmbForwardTo').val();
                                                        if (cmbForwardTo == "") {
                                                            $('#val_cmbForwardTo').css("display", "block");
                                                            valreturn = false;
                                                        } else {
                                                            $('#val_cmbForwardTo').css("display", "none");
                                                            valreturn = true;
                                                        }
                                                        return valreturn;
                                                    }

                                                    function redirectToURL(URL, target, viewID) {
                                                        var str = txtPKey.value;
                                                        //alert(str);
                                                        if (URL.indexOf("action=NEW") == -1)
                                                            URL = URL + "&pkeyid=" + str + "&viewid=" + viewID;
                                                        else
                                                            URL = URL + "&viewid=" + viewID;

                                                        //alert(URL);
                                                        //        alert(target);
                                                        if (target == "_blank")
                                                            window.open(URL);
                                                        else
                                                            self.location = URL;
                                                    }

                                                    function CheckAll(checkAllBox)
                                                    {
                                                        var frm = document.tran_unassigned_bills;
                                                        var ChkState = checkAllBox.checked;
                                                        for (i = 0; i < frm.length; i++)
                                                        {
                                                            e = frm.elements[i];
                                                            if (e.type == 'checkbox')
                                                            {
                                                                e.checked = ChkState;
                                                                //alert(e.value);
                                                                $("#tr_" + e.value).children('td, th').css('background-color', '#ffff99')
                                                            }
                                                        }
                                                        // showTotal();
                                                    }

                                                    function deletebill(id)
                                                    {
                                                        var yesno = confirm("Are you sure you want to delete this bill?");
                                                        if (yesno)
                                                        {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "ajaxdropdown.php?type=DELETE_BILL&id=" + id,
                                                                success: function(html) {
                                                                    $("#tr_" + id).remove();
                                                                    $("#errorMsg").html(html);

                                                                }
                                                            });
                                                        }
                                                    }

                                                    function OnClickRow(row, chkBID)
                                                    {
                                                        if ($("#" + chkBID).is(':checked'))
                                                        {
                                                            $("#" + chkBID).prop('checked', true); // Checks it
                                                            //  $("#" + chkBID).removeAttr('checked');
                                                            $("#" + row).children('td, th').css('background-color', '#ffffff');
                                                        } else
                                                        {
                                                            $("#" + chkBID).prop('checked', false);
                                                            //  $("#" + chkBID).prop('checked', 'checked');
                                                            $("#" + row).children('td, th').css('background-color', '#ffff99');

                                                        }
                                                    }

</script>
<?php
mysqli_free_result($result);
unset($result);
include('footer.php');
?>  