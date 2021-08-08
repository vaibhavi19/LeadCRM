<?php
ob_start();
include 'header.php';

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();

$pkey_id = (isset($_REQUEST['pkeyid'])) ? $_REQUEST['pkeyid'] : '';
$pagetext = "";
$card_number = $company_name = $company_logo = $company_address = $employee_code = $status = $company_name = $employee_image = $company_name = $employee_name = $employee_address = $employee_contact_number = $employee_emergency_name = $employee_emergency_number = "";
$status = "checked";
$client_name = $display_name = $mobile_number = $whatsapp_number = $email_id = $source_id = $remarks = $lead_status = "";
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
        $status = $_POST['chkStatus'];
        $status = ($status == "on") ? "A" : "";
//        echo "<pre>";
//        print_r($_POST);
//        exit;

        if ($_REQUEST['action'] == 'EDIT') {

            $query = "update mst_lead set client_name=" . replaceBlank($client_name) . ",display_name=" . replaceBlank($client_name) . ",mobile_number=" . replaceBlank($mobile_number) . ",whatsapp_number=" . replaceBlank($mobile_number) .
                    ",email_id=" . replaceBlank($email_id) . ",remarks=" . replaceBlank($remarks) . ",status=" . replaceBlank($status) . ""
                    . " where lead_id=" . $lead_id;
            array_push($array, $query);
        } else {
            $query = "insert into `mst_lead`(`client_name`,`display_name`,`mobile_number`,`whatsapp_number`,`email_id`,`remarks`,`lead_status`,`created_by`,`created_on`,`user_id`,`status`) values (";
            $query = $query . " " . replaceBlank($client_name) . "," . replaceBlank($display_name) . "," . replaceBlank($mobile_number) . "," . replaceBlank($whatsapp_number) . "," . replaceBlank($email_id) . ","
                    . replaceBlank($remarks) . ",'N'," . replaceBlank($_SESSION['user_id']) . ",now()," . replaceBlank($_SESSION['user_id']) . ",'A')";

            array_push($array, $query);
        }

        if (!$obj->execute_sqli_array($array, $error_message)) {

            // $obj->save_lead_log($activity_desc, $activity_type,$lead_id,$client_id);

            echo message_show($error_message, "error");
            return FALSE;
        } else {
            echo message_show("Lead saved successfully.", "info");

            header("Location:listview.php?id=1&viewid=" . $_REQUEST['viewid'] . "");
            return TRUE;
        }
    }
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Create Lead</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Lead</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
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
                            <h3 class="card-title">Create Card</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form role="form" id="lead_form" name="lead_form" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">

                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="exampleInputDescrition">Client Name <font color="red">*</font></label>
                                            <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Enter Client Name" value="<?php echo $client_name; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="exampleInputDescrition">Display Name <font color="red">*</font></label>
                                            <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Enter Display Name" value="<?php echo $display_name; ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class='col-md-3'>
                                        <div class="form-group">
                                            <label for="exampleInputDescrition">Mobile No <font color="red">*</font></label>
                                            <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter Mobile No" value="<?php echo $mobile_number; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-3'> 
                                        <div class="form-group">
                                            <label for="exampleInputLocationName">Whatsapp No <font color="red">*</font></label>
                                            <input type="text" class="form-control" name="whatsapp_no" id="whatsapp_no" placeholder="Enter Whatsapp No" value="<?php echo $whatsapp_number; ?>">
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label for="exampleInputDescrition">Email Id <font color="red">*</font></label>
                                            <input type="text" class="form-control" id="email_id" name="email_id" placeholder="Enter Email Id" value="<?php echo $email_id; ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class='col-md-12'>
                                        <div class="form-group">
                                            <label for="exampleInputDescrition" >Remarks</label>
                                            <textarea rows="2" cols="2" name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks"><?php echo $remarks; ?></textarea>
                                        </div>
                                    </div>

                                </div>


                                <div class="row">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="chkStatus" name="chkStatus" <?php echo $status; ?>>
                                        <label class="form-check-label" for="exampleCheck1">Status</label>
                                    </div>
                                </div>

                            </div>

                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Create Lead</button>
                                <a href="../frm/listview.php?id=1" class="btn btn-danger">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div>
    </section>
    <!-- /.row -->
</div><!-- /.container-fluid -->
<?php //echo $company_logo; ?>

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
                    required: true,
                    email: true
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




<?php unset($obj); ?>
<?php
include('footer.php');
?>


