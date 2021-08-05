<?php
ob_start();
include 'header.php';

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();

$pkey_id = (isset($_REQUEST['pkeyid'])) ? $_REQUEST['pkeyid'] : '';

if (isset($_POST['btnClose'])) {
    $obj->save_log("Bill Master page closed.", $_SERVER["REQUEST_URI"]);
    header("Location: listview.php?id=" . $_GET["viewid"]);
    return;
}


if ($_REQUEST['action'] == 'EDIT') {
    $pagetext = 'Edit';
    $query = " select * from mst_lead where lead_id  = '$pkey_id'";

    $result = $obj->execute($query, $error_message);

    $row = mysqli_fetch_object($result);

    $lead_id = $row->lead_id;
    $user_id = $row->user_id;
    $client_name = $row->client_name;
    $display_name = $row->display_name;
    $mobile_number = $row->mobile_number;
    $whatsapp_number = $row->whatsapp_number;
    $email_id = $row->email_id;
    $source_id = $row->source_id;
    $remarks = $row->remarks;
    $lead_status = $row->lead_status;

    //$status = $row->status;
    $status = ($row->status == "A") ? "checked" : "";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_REQUEST['submit'])) {
        $array = array();
        $client_name = $_POST['client_name'];
        $display_name = $_POST['display_name'];
        $mobile_number = $_POST['mobile_no'];
        $whatsapp_number = $_POST['whatsapp_no'];
        $email_id = $_POST['email_id'];
        //   $source_id = $row->source_id;
        $remarks = $_POST['remarks'];
//        echo "<pre>";
//        print_r($_POST);
//        exit;

        $query = "insert into `mst_lead`(`client_name`,`display_name`,`mobile_number`,`whatsapp_number`,`email_id`,`remarks`,`lead_status`,`created_by`,`created_on`,`user_id`,`status`) values (";
        $query = $query . " " . replaceBlank($client_name) . "," . replaceBlank($display_name) . "," . replaceBlank($mobile_number) . "," . replaceBlank($whatsapp_number) . "," . replaceBlank($email_id) . ","
                . replaceBlank($remarks) . ",'N'," . replaceBlank($_SESSION['user_id']) . ",now()," . replaceBlank($_SESSION['user_id']) . ",'A')";

        array_push($array, $query);
        if (!$obj->execute_sqli_array($array, $error_message)) {
            $obj->save_log("BTS creation error: $error_message.", $_SERVER["REQUEST_URI"]);
            echo message_show($error_message, "error");
            return FALSE;
        } else {
            echo message_show("Bill entered successfully with BTS Number:" . $bts_number, "info");
          
            header("Location:listview.php?id=1&viewid=" . $_REQUEST['viewid'] . "");
            return TRUE;
        }
    }
}
?>

<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Create Lead
        </h1>
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><i class="fa fa-angle-right"></i> Create Lead
            </li>
        </ol>
    </div>

    <!-- Main content -->
    <div class="content"> 
        <form name="lead_form" id="lead_form" method="post">
            <div class="info-box">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Client Name</label>
                            <input class="form-control" id="client_name" name="client_name" aria-describedby="helpBlock2" type="text" placeholder="Enter Client Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Display Name</label>
                            <input class="form-control" id="display_name" name="display_name" aria-describedby="helpBlock2" type="text" placeholder="Enter Display Name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Mobile Number</label>
                            <input class="form-control" id="mobile_no" name="mobile_no" aria-describedby="helpBlock2" type="text" placeholder="Enter Mobile Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Whatsapp Number</label>
                            <input class="form-control" id="whatsapp_no" name="whatsapp_no" aria-describedby="helpBlock2" type="text" placeholder="Enter Whatsapp Number">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" >Email Id</label>
                    <input class="form-control" id="email_id" name="email_id" aria-describedby="helpBlock2" type="text" placeholder="Enter Email Id">
                </div>
                <div class="form-group">
                    <label class="control-label" >Remarks</label>
                    <textarea rows="2" cols="2" name="remarks" id="remarks" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Create Lead">
<!--                    <input type="submit" name="btnClose" id="btnClose" class="btn btn-danger" value="Back">-->
                    <a href="listview.php?id=1" class="btn btn-danger">Back</a>
                    
                </div>
            </div>
        </form>
 
    </div>

</div>
<!-- ./wrapper --> 



<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                alert("Form successful submitted!");
                return true;
            }
        });
        $('#lead_form').validate({
            rules: {
                client_name: {
                    required: true
                },
                display_name: {
                    required: true
                },
                mobile_no: {
                    required: true
                },
                email_id: {
                    required: true
                }
            },
            messages: {
                client_name: {
                    required: "Please enter client name"
                },
                display_name: {
                    required: "Please enter display name"
                },
                mobile_no: {
                    required: "Please enter mobile"
                },
                email_id: {
                    required: "Please enter email id"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
</body>
</html>
