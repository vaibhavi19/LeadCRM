<?php
ob_start();

include('header.php');
?>

<?php
if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();
//echo "<pre>";
//print_r($_SESSION);
//exit;
$user_id = $client_first_name = $client_last_name = $client_gender = $client_email = $client_mobile = $company_name = $gst_no = "";
$address = $pincode = $city_id = $state_id = $email_verified_yn = $email_verified_on = $mobile_verified_yn = $mobile_verfied_on = $country_id = "";
$client_id = $_SESSION['client_id'];

if (isset($client_id) && $client_id != "") {
    $query = "select * from mst_clients where client_id = '$client_id'";
    $result = $obj->execute($query, $error_message);

    $row = mysqli_fetch_object($result);

    //echo "<pre>"; print_r($row);exit;
    $user_id = $row->user_id;
    $client_first_name = $row->client_first_name;
    $client_last_name = $row->client_last_name;
    $client_gender = $row->client_gender;
    $client_email = $row->client_email;
    $client_mobile = $row->client_mobile;
    $company_name = $row->company_name;
    $gst_no = $row->gst_no;
    $address = $row->address;
    $pincode = $row->pincode;
    $city_id = $row->city_id;
    $state_id = $row->state_id;
    $email_verified_yn = $row->email_verified_yn;
    $email_verified_on = $row->email_verified_on;
    $mobile_verified_yn = $row->mobile_verified_yn;
    $mobile_verfied_on = $row->mobile_verfied_on;
    $country_id = $row->country_id;
//echo 'aegbsdjksgjkasg'.$email_verified_yn;exit;
//    $status = ($row->status == "A") ? "checked" : "";

    unset($row);
    mysqli_free_result($result);
} else {
    echo message_show('Invalid User.Please relogin again.', 'alert');
    exit;
}

$pagetext = 'Create Profile';
$description = "Profile";
$currency = 1; // INDIAN RUPEE
$so_read_only = false;
$status = "checked";

//$challan_number = isset($_REQUEST['cmbChallanNumber']) ? $_REQUEST['cmbChallanNumber'] : "";
//$bill_type = isset($_REQUEST['cmbBillType']) ? $_REQUEST['cmbBillType'] : "";
//
//echo "sandeep".$ses_date;

if (isset($_GET['pkeyid'])) {
    $bill_id = $_GET['pkeyid'];
}

$view_id = "";
if (isset($_GET['viewid'])) {
    $view_id = $_GET['viewid'];
}

if (isset($_GET['flagsave']) == "1") {
    ?>
    <script type="text/javascript">

        $(document).ready(function() {
            var flagvar = '<?php echo $_GET['flagsave']; ?>';
            if (flagvar == '1') {
                $("#custom-tabs-email-tab").trigger("click");
                $('.nav-links a[href="#custom-tabs-email-tab"]').tab('show');
            }

            if (flagvar == '2') {
                $("#custom-tabs-mobile-tab").trigger("click");
                $('.nav-links a[href="#custom-tabs-mobile-tab"]').tab('show');
            }

            if (flagvar == '3') {
                $("#custom-tabs-one-legends-tab").trigger("click");
                $('.nav-links a[href="#custom-tabs-one-legends-tab"]').tab('show');
            }
        });
    </script>
    <?php
}



//if (isset($_GET['flagsave']) == "2") {
//    
?>
<!--    <script type="text/javascript">

$(document).ready(function() {
$("#custom-tabs-mobile-tab").trigger("click");
$('.nav-links a[href="#custom-tabs-mobile-tab"]').tab('show');
});
</script>-->
//<?php
//}


if (isset($_POST['btnClose'])) {
    $obj->save_log("Bill Master page closed by vendor.", $_SERVER["REQUEST_URI"]);
    if ($_GET["viewid"] != "") {
        header("Location: listview.php?id=" . $_GET["viewid"]);
        return;
    } else {
        header("Location:" . $_GET["rurl"]);
        return;
    }
}
// detail data variables
$action = isset($_GET['act']) ? $_GET['act'] : "";
$description = $document_path = $image_name = "";


// CHECK BILL STATUS BEFORE EDITING
//if ($bill_id != "") {
//    $bill_status = $obj->get_execute_scalar("select status from mst_bills where bill_id ='$bill_id'", $error_message);
//    if ($bill_status != "K") {
//        echo message_show("The said bill is already in process and hence cannot be edited.", "alert");
//        exit;
//    }
//}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?php echo $pagetext; ?></h1>
                </div><!-- /.col -->

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $pagetext; ?></li>
                    </ol>
                </div><!-- /.col -->
                <marquee><font color="red">You can not use the CRM unless and untill you fill details.</font></marquee>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="col-12 col-sm-12">
        <div class="card card-gray card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Primary Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-email-tab" data-toggle="pill" href="#email_tab" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Email Id Verification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-mobile-tab" data-toggle="pill" href="#mobile_tab" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Mobile Verification</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-legends-tab" data-toggle="pill" href="#notes_tab" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Terms & Conditions</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">


                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">

                                    <!--/.col (left) -->
                                    <!-- right column -->

                                    <?php
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {


//                                        $utility_vendor_yn = $obj->get_execute_scalar("select ifnull(utility_vendor_yn,'N') as utility_vendor_yn from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);
//                                        $reference_vendor_code = $obj->get_execute_scalar("select reference_vendor_code from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);


                                        if (isset($_POST['submit'])) {

                                            if (isset($_SESSION['client_id'])) {
                                                if (save_header_data() == true) {
                                                    // echo "hello";exit;
                                                    // echo message_show("Bill record updated successfully.", "success");
                                                }
                                            } else {
                                                echo message_show("Something went wrong", "error");
                                            }
                                        }

                                        if (isset($_POST['validate_email'])) {
                                            if (validate_email_user() == true) {
                                                
                                            }
                                        }
                                        if (isset($_POST['validate_mobile'])) {
                                            if (validate_mobile_user() == true) {
                                                
                                            }
                                        }
                                          if (isset($_POST['agree_terms'])) {
                                            if (agree_terms_user() == true) {
                                                
                                            }
                                        }  
                                        
                                        
                                    } else {
                                        $obj->save_log("Bill page opened.", $_SERVER["REQUEST_URI"]);
                                    }

//                                    if ($bill_id != "") {
//                                        display_data($bill_id);
//                                    }
//
//
//                                    if ($action == "E") {
//                                        display_detail_data($_GET['lid']);
//                                    } elseif ($action == "D") {
//                                        delete_detail_data($_GET['lid']);
//                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <!-- general form elements disabled -->
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Primary Details  <?php if (isset($bts_number) && $bts_number != '') { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b> <font color="red" style="font-size: 24px;">BTS No: <?php echo $bts_number; ?></font></b> <?php } ?> </h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <form id="invoiceForm" name="invoiceForm" method = "post" enctype="multipart/form-data">

                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>First Name<font color="red">*</font></label>
                                                                <input type="text"  class="form-control" name="first_name" id="first_name" value="<?php echo $challan_number; ?>" placeholder="Challan Number..">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Last Name<font color="red">*</font></label>
                                                                <input type="text"  class="form-control" name="last_name" id="last_name" value="<?php echo $challan_number; ?>" placeholder="Challan Number..">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Gender<font color="red">*</font></label>
                                                                <select class="form-control" id="gender" name="gender">
                                                                    <option <?php echo $gender == "" ? "selected" : ""; ?> value="">-- Select Gender--</option>
                                                                    <option <?php echo $gender == "Invoice" ? "selected" : ""; ?> value="Male">Male</option>
                                                                    <option <?php echo $gender == "IOM" ? "selected" : ""; ?> value="Female">Female</option>
                                                                    <option <?php echo $gender == "HOLD RELEASE" ? "selected" : ""; ?> value="Other">Other</option>

                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Company Name<font color="red">*</font></label>
                                                                <input type="text"  class="form-control" name="mobile_no" id="mobile_no" value="<?php echo $challan_number; ?>" placeholder="Company Name..">
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>GSTIN / TAX ID</label>
                                                                <input type="text"  class="form-control" name="gst_no" id="gst_no" value="<?php echo $challan_number; ?>" placeholder="Enetr GSTIN/TAX ID..">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Pincode</label>
                                                                <input type="text"  class="form-control" name="pincode" id="pincode" value="<?php echo $pincode; ?>" placeholder="Pincode..">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>City<font color="red">*</font></label>
                                                                <select name="city" id="city" class="form-control" >
                                                                    <option value="">Select City</option>
                                                                    <option value="Andheri">Andheri</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>State<font color="red">*</font></label>
                                                                <select name="state" id="state" class="form-control" >
                                                                    <option value="">Select state</option>
                                                                    <option value="Maharashtra">Maharashtra</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>Country<font color="red">*</font></label>
                                                                <select name="country" id="country" class="form-control" >
                                                                    <option value="">Select Country</option>
                                                                    <option value="India">India</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Address<font color="red">*</font></label>
                                                                <textarea name="address" id="address" class="form-control" placeholder="Enter Address"><?php echo $address; ?></textarea>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="card-footer">
                                                        <button type="submit" id="submit" name="submit" class="btn btn-primary">Raise Invoice</button>
                                                        <button type="submit" id="btnClose" name="btnClose" class="btn btn-danger" formnovalidate>Go Back</button>

                                                        <!--                                                        <a id="btnClose" name="btnClose"  class="btn btn-danger">Go Back</a>-->
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                        <!-- general form elements disabled -->

                                        <!-- /.card -->
                                    </div>

                                    <!--/.col (right) -->
                                </div>
                                <!-- /.row -->
                            </div><!-- /.container-fluid -->
                        </section>

                    </div>

                    <!--                    <div class="tab-pane fade" id="custom-tabs-one-note" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">
                    
                    
                                            <div class="content-wrapper px-4 py-2" style="min-height: 170px;">
                    
                                            <div class="content px-2">
                                                <p>Following notes to be taken care while uploading invoices through Vendor Desk.</p>
                    
                                                <h5 class="text-bold text-dark mt-3">Which fields are <span class="text-success">Mandatory</span> while raising invoice.</h5>
                                                <ul>
                                                    <li>All the fields marked with asterisk(*) are mandatory.</li>
                                                    <li>Invoice with future dates are not allowed to raise.</li>
                                                    <li>PO/WO number is mandatory for invoice amount greater than Rs.10,000/-.</li>
                                                    <li>Document with PDF as well as image format are allowed to upload.</li>
                                                </ul>
                    
                                                <h5 class="text-bold text-dark mt-3">What the invoices are <span class="text-danger">Rejected</span> by us.</h5>
                                                <ul>
                                                    <li>If any supporting documents if applicable are not attached along with the invoice copy.</li>
                                                    <li>If details in invoice copy attached and details entered are mismatching.</li>
                                                    <li>If Invoice with same invoice number is raised again.</li>
                                                </ul>
                    
                                                <h5 class="text-bold text-dark mt-3">What You <span class="text-warning">Must</span> Do While Raising the Invoice</h5>
                                                <ul>
                                                    <li>Softcopy of invoices must be uploaded along with the necessary supporting documents.</li>
                                                    <li>All the supporting documents which are uploaded should be clearly visible in browser.</li>
                                                </ul>
                    
                                            </div>
                                            </div>
                    
                    
                                        </div>-->

                    <div class="tab-pane fade" id="email_tab" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">


                        <!--<div class="content-wrapper px-4 py-2" style="min-height: 170px;">-->

                        <div class="content px-2">

                            <?php
//echo $bill_id;exit;
                            if ($client_id != "") {

                                if ($email_verified_yn == 'Y') {
                                    ?>
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <?php echo message_show('Email Id Verified successfully.', 'success'); ?>
                                        </div>

                                    </div>
                                <?php } else { ?>

                                    <form name ="email_var_form" id="email_var_form" method = "post" >
                                        <input type="hidden" name="otp_send_email" id="otp_send_email" value="">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Email Id</label>
                                                    <input name="client_email" id="client_email" placeholder="Enter Email id" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Get OTP on email</label>
                                                    <button type="button" class="form-control  btn btn-warning " name="otp_for_email" id="otp_for_email" onclick="return getOtpEmail();" >Generate OTP</button>

                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label> Verify Email Id</label>
                                                    <input name="otp_verify_email" id="otp_verify_email" placeholder="Enter OTP" class="form-control"  required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Validate Email</label>
                                                    <input type="submit" class="form-control btn btn-primary"  value="Validate Email" name="validate_email" id="validate_email" />
                                                </div>
                                            </div>
                                        </div>


                                    </form>
                                    <br>
                                <?php }
                                ?>

                                <?php
                            }
                            ?>
                        </div>


                    </div>

                    <div class="tab-pane fade" id="mobile_tab" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">

                        <div class="content px-2">

                            <?php
//echo $bill_id;exit;
                            if ($client_id != "") {

                                if ($mobile_verified_yn == 'Y') {
                                    ?>
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <?php echo message_show('Mobile Number Verified successfully.', 'success'); ?>
                                        </div>

                                    </div>
                                <?php } else { ?>

                                    <form name ="mobile_var_form" id="mobile_var_form" method = "post" >
                                        <input type="hidden" name="otp_send_mobile" id="otp_send_mobile" value="">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input name="client_mobile" id="client_mobile" placeholder="Enter Mobile Number" class="form-control" required="">
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Get OTP on mobile</label>
                                                    <button type="button" class="form-control btn btn-warning"  value="Get OTP" name="otp_for_mobile" id="otp_for_mobile" onclick="return sendOtpMobile();" >Generate OTP</button>

                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label> Verify Mobile Id</label>
                                                    <input name="otp_verify_mobile" id="otp_verify_mobile" placeholder="Enter OTP" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Validate Mobile</label>
                                                    <input type="submit" class="form-control btn btn-primary"  value="Validate Mobile" name="validate_mobile" id="validate_mobile"  />
                                                </div>
                                            </div>
                                        </div>


                                    </form>
                                    <br>
                                <?php }
                                ?>

                                <?php
                            }
                            ?>
                        </div>




                    </div>


                    <div class="tab-pane fade" id="notes_tab" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">




                        <div class="content px-2">
                            <p>Following notes to be taken care while uploading invoices through Vendor Desk.</p>

                            <h5 class="text-bold text-dark mt-3">Which fields are <span class="text-success">Mandatory</span> while raising invoice.</h5>
                            <ul>
                                <li>All the fields marked with asterisk(*) are mandatory.</li>
                                <li>Invoice with future dates are not allowed to raise.</li>
                                <li>PO/WO number is mandatory for invoice amount greater than Rs.10,000/-.</li>
                                <li>Document with PDF as well as image format are allowed to upload.</li>
                            </ul>

                            <h5 class="text-bold text-dark mt-3">What the invoices are <span class="text-danger">Rejected</span> by us.</h5>
                            <ul>
                                <li>If any supporting documents if applicable are not attached along with the invoice copy.</li>
                                <li>If details in invoice copy attached and details entered are mismatching.</li>
                                <li>If Invoice with same invoice number is raised again.</li>
                            </ul>

                            <h5 class="text-bold text-dark mt-3">What You <span class="text-warning">Must</span> Do While Raising the Invoice</h5>
                            <ul>
                                <li>Softcopy of invoices must be uploaded along with the necessary supporting documents.</li>
                                <li>All the supporting documents which are uploaded should be clearly visible in browser.</li>
                            </ul>
   <form name="terms_form" id="terms_form" method="post" >
<!--                            <div class="col-md-6">
                                <input type="checkbox" class="form-control" id="chkStatus" name="chkStatus" required="">
                            </div>
                            <div class="col-md-6">
                                  <input type="submit" name="agree_terms" id="agree_terms" class="btn btn-primary">
                            </div>-->
<br><br>
<ul>
          <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <input type="checkbox" class="form-check-input" id="chkStatus" name="chkStatus" required="">
                                      <label class="form-check-label" for="exampleCheck1"><b>I agree the above terms and conditions</b></label>
                                          
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" name="agree_terms" id="agree_terms" class="btn btn-primary">
                                </div> 
                            </div>
</ul>

                      



<!--                            <input type="checkbox" name="terms_box" id="terms_box" class="form-control">
                            -->

                        </form>
                        </div>

                     

                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->


</div>

<?php

function invoicedateValidate() {
//date_default_timezone_set('asia/kolkata');
    $today_date = date('Y-m-d');
    $invoice_date = "2020-07-01";

    $first_day_of_month = date("Y-m-1");
    $first_day_of_previous_month = date("Y-m-d", strtotime("first day of previous month"));
    $cutoff_date = date("Y-m-05");

//echo "<br>Invoice Date :     " . $invoice_date;
//echo "<br>Todays :     " . $today_date;
//
//echo "<br>First day of month :     " . $first_day_of_month;
//echo "<br>First day of previous month :     " . $first_day_of_previous_month;
//echo "<br>Cutoff Date :     " . $cutoff_date;
//
//echo "<br>====================<br>";

    if ((strtotime($invoice_date) > strtotime($today_date))) {
        return false;
        //echo "<br>Future date not acceptable";
    } elseif ((strtotime($today_date) > strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_month))) {
        return false;
        //   echo "<br>NOPE...Old bills are not acceptable";
    } elseif ((strtotime($today_date) <= strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_previous_month))) {
        return false;
        // echo "<br>NOPE NOPE...Old bills are not acceptable";
    } else {
        return true;
        // echo "<br>DONE...Current month bills are welcome";
    }
}
?>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script type="text/javascript">


                                                $(document).ready(function() {
                                                    $.validator.setDefaults({
                                                        submitHandler: function() {

                                                            //  alert("Form successful submitted!");
                                                            return true;
                                                        }
                                                    });


                                                    $('#invoiceForm').validate({
                                                        rules: {
                                                            txtBuildingName: {
                                                                required: true
                                                            },
                                                            txtVendorName: {
                                                                required: true
                                                            },
                                                            cmbBillType: {
                                                                required: true
                                                            },
                                                            cmbChallanNumber: {
                                                                maxlength: 100
                                                            },
                                                            txtDeliveryLocation: {
                                                                required: true
                                                            },
                                                            cmbCompany: {
                                                                required: true
                                                            },
                                                            cmbDepartment: {
                                                                required: true
                                                            },
                                                            txtBillNo: {
                                                                required: true,
                                                                maxlength: 50

                                                            },
                                                            txtBidNumber: {
                                                                maxlength: 50
                                                            },
                                                            txtBillDate: {
                                                                required: true
                                                            },
                                                            txtAmount: {
                                                                required: true
                                                            },
                                                            txtPayableAmount: {
                                                                required: true
                                                            },
                                                            cmbBillCategory: {
                                                                required: true
                                                            },
                                                            txtServiceFromDate: {
                                                                required: function(element) {
                                                                    if ($('#cmbBillCategory').val() == 'Service') {
                                                                        return true;
                                                                    } else {
                                                                        return false;
                                                                    } // return $('#cmbBillCategory').val() == 'Service';
                                                                }
                                                            },
                                                            txtAdvanceRequestNo: {
                                                                required: function(element) {
                                                                    // var str = $('#txtAdvanceRequestNo').val();
                                                                    if ($('#cmbBillType').val() == 'IOM') {
                                                                        return true;
                                                                    } else {
                                                                        return false;
                                                                    } // return $('#cmbBillCategory').val() == 'Service';
                                                                },
                                                                maxlength: 200,
                                                                minlength: 10
                                                            },
                                                            txtServiceToDate: {
                                                                required: function(element) {
                                                                    return $('#cmbBillCategory').val() == 'Service';
                                                                }
                                                            },
                                                            txtVendorContactPerson: {
                                                                required: true
                                                            },
                                                            txtVendorContactPersonContactNo: {
                                                                required: true
                                                            },
                                                            txtHiranandaniContactPerson: {
                                                                required: true
                                                            },
                                                            txtHiranandaniContactPersonContactNo: {
                                                                required: true
                                                            },
                                                            // txtBillDueDate: {
                                                            //      required: true
                                                            //              //   mindateallow : true
                                                            //  },
                                                            txtWorkorderNumber: {
                                                                required: function(element) {

                                                                    if (($('#txtAmount').val() >= 10000) || ($('#cmbBillType').val() == "IOM")) {
                                                                        return true;
                                                                    } else {
                                                                        return false;
                                                                    }
                                                                    // return $('#cmbBillCategory').val() == 'Service';
                                                                },
                                                                maxlength: 50
                                                            }
                                                        },
                                                        messages: {
                                                            txtBuildingName: {
                                                                required: "Please select building"
                                                            },
                                                            txtVendorName: {
                                                                required: "Please select Vendor"
                                                            },
                                                            cmbBillType: {
                                                                required: "Please select Bill Type"
                                                            },
                                                            cmbChallanNumber: {
                                                                maxlength: "Maximum length is 100"
                                                            },
                                                            txtDeliveryLocation: {
                                                                required: "Please select Delivery Location"
                                                            },
                                                            cmbCompany: {
                                                                required: "Please select company"
                                                            },
                                                            cmbDepartment: {
                                                                required: "Please select department"
                                                            },
                                                            txtBillNo: {
                                                                required: "Please select bill no"
                                                            },
                                                            txtBidNumber: {
                                                                maxlength: "Maximum length is 50"
                                                            },
                                                            txtWorkorderNumber: {
                                                                required: "PO/WO Required",
                                                                maxlength: "Maximum length is 50"
                                                            },
                                                            txtBillDate: {
                                                                required: "Please select bill date"
                                                                        //  invoicedatevalidation: 'Invoice date is not proper'
                                                            },
                                                            txtAmount: {
                                                                required: "Please enter amount"
                                                            },
                                                            txtPayableAmount: {
                                                                required: "Please enter payable amount"
                                                            },
                                                            cmbBillCategory: {
                                                                required: "Please select bill category"
                                                            },
                                                            txtServiceFromDate: {
                                                                required: "Please select service from date"
                                                            },
                                                            txtServiceToDate: {
                                                                required: "Please select service to date"
                                                            },
                                                            txtVendorContactPerson: {
                                                                required: "Please select contact person name"
                                                            },
                                                            txtVendorContactPersonContactNo: {
                                                                required: "Please enter vendor contact person no"
                                                            },
                                                            txtHiranandaniContactPerson: {
                                                                required: "Please enter hiranandani contact person name"
                                                            },
                                                            txtHiranandaniContactPersonContactNo: {
                                                                required: "Please enter hiranandani contact person no"
                                                            },
                                                            //  txtBillDueDate: {
                                                            //        required: "Please select invoice due date",
                                                            //        //     mindateallow: "Bill due date should greater than invoice date "
                                                            //    }
                                                            txtAdvanceRequestNo: {
                                                                required: "Please enter Advance Request Number"
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





                                                function sendOtpMobile() {

                                                    var fourdigitsrandom = Math.floor(1000 + Math.random() * 9000);
                                                    $("#otp_send_mobile").val(fourdigitsrandom);
//alert(fourdigitsrandom);


                                                    //    var email = $("#txtEncEmail").val();
                                                    var mobile = $("#client_mobile").val();
                                                    if (mobile != "") {
                                                        $.ajax({
                                                            url: 'sendOtp.php',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {'mobile': mobile, 'otp': fourdigitsrandom},
                                                            contentType: 'application/json',
                                                            success: function(data) {
                                                                if (data.status == 'success') {
                                                                    alert("OTP send on your mobile number");
                                                                } else {
                                                                    alert("Wrong mobile number entered or otp service is not working");
                                                                }
                                                                //  alert(data.status);
                                                                // $('#target').html(data.msg);
                                                            }
                                                            //   data: JSON.stringify(person)
                                                        });

                                                    } else {
                                                        alert("Please enter mobile no");
                                                    }

                                                }
                                                function getOtpEmail() {

                                                    var fourdigitsrandom = Math.floor(1000 + Math.random() * 9000);
                                                    $("#otp_send_email").val(fourdigitsrandom);
//alert(fourdigitsrandom);


                                                    //    var email = $("#txtEncEmail").val();
                                                    var mobile = $("#client_email").val();
                                                    if (mobile != "") {
                                                        $.ajax({
                                                            url: 'sendOtp.php',
                                                            type: 'get',
                                                            dataType: 'json',
                                                            data: {'mobile': mobile, 'otp': fourdigitsrandom},
                                                            contentType: 'application/json',
                                                            success: function(data) {
                                                                if (data.status == 'success') {
                                                                    alert("OTP send on your mobile number");
                                                                } else {
                                                                    alert("Wrong mobile number entered or otp service is not working");
                                                                }
                                                                //  alert(data.status);
                                                                // $('#target').html(data.msg);
                                                            }
                                                            //   data: JSON.stringify(person)
                                                        });

                                                    } else {
                                                        alert("Please enter email id");
                                                    }

                                                }
</script>
<?php

function save_header_data() {

    global $obj, $client_id, $first_name, $last_name, $gender, $mobile_no, $gst_no, $pincode, $city, $state, $country, $address;
    $status = "checked";
//
//    echo "<pre>";
//    print_r($_POST);
//    exit;
    $client_id = $_SESSION['client_id'];
    $error_message = $out_message = "";
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $mobile_no = $_POST['mobile_no'];
    $gst_no = $_POST['gst_no'];
    $pincode = $_POST['pincode'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    $array = array();

    $task = $log_text = "";
    if ($client_id != "") {

        $task = 'updated';
        $query = " update mst_clients set ";
        $query = $query . " client_first_name = " . replaceBlank($first_name) . ",client_last_name = " . replaceBlank($last_name) . ", client_gender = " . replaceblank($gender) . ", ";
        $query = $query . " client_mobile = " . replaceBlank($mobile_no) . ", gst_no = " . replaceBlank(gst_no) . ",pincode =" . replaceBlank($pincode) . ",city_id =" . replaceBlank($city) . ",state_id =" . replaceBlank($state) . ",country_id=" . replaceBlank($country) . " ,address=" . replaceBlank($address) . " where client_id=" . $client_id;



        array_push($array, $query);
        $log_text = "updated";

        $out_message = "Profile updated successfully.";
    }

//    echo "<pre>";
//    print_r($array);
//    exit;

    if (!$obj->execute_sqli_array($array, $error_message)) {

        // $obj->save_log("BTS creation error: $error_message.", $_SERVER["REQUEST_URI"]);
        // $bill_id = $obj->get_execute_scalar('select max(bill_id) as bill_id from mst_bills', $error_message);
        //if($bill_id){
        //  save_bill_log(" Bill ".$task." by ".$_SESSION['user_name']." with ".$_SESSION['user_id'], $_SERVER["REQUEST_URI"],$bill_id);
        // }
        echo message_show($error_message, "error");
        return FALSE;
    } else {
        //  $obj->save_log($out_message, $_SERVER["REQUEST_URI"]);
        //  echo message_show("Bill entered successfully with BTS Number:" . $bts_number, "info");

        if ($client_id == "") {
            $client_id = $_SESSION['client_id'];
        }
        //  $obj->save_bill_log(" Invoice  " . $log_text . " successfully by " . $_SESSION['user_name'], $bill_id);

        header("Location:client_info.php?action=EDIT&msg=success&viewid=" . $_REQUEST['viewid'] . "&flagsave=1");
        return TRUE;
    }
//}else{
//    echo "byee";exit;
//}
}

function save_detail_data() {


    global $obj, $bill_id, $description, $document_path, $status, $action, $view_id;
    $max_filesize = 5097152; // Maximum filesize in BYTES (currently 2 MB).
    $upload_path = '../data/uploads/'; // The place the files will be uploaded to (currently a 'files' directory).
    $filename = "";
    //    $fname = "";
    $upload_success = 0;

    // if any attachment specified, upload first
    if (is_uploaded_file($_FILES['flDocumentPath']['tmp_name'])) {

        $f_type = $_FILES['flDocumentPath']['type'];
        if ($f_type == "application/pdf" OR $f_type == "image/gif" OR $f_type == "image/png" OR $f_type == "image/jpeg" OR $f_type == "image/JPEG" OR $f_type == "image/PNG" OR $f_type == "image/GIF") {



            $filename = $_FILES['flDocumentPath']['name']; // Get the name of the file (including file extension).
            //$fname = $upload_path . $filename;

            $variable = $_SESSION['user_id'] . "_" . rand(1, 1000000);
            $variable = random_string(0);
            //echo "filename" . $fname = $_FILES['flDocumentPath']['name'];
            $filename = $variable . "." . pathinfo($filename, PATHINFO_EXTENSION);

            // Check the filesize, if it is too large then DIE and inform the user.
            if (filesize($_FILES['flDocumentPath']['tmp_name']) > $max_filesize)
                echo message_show("The file you attempted to upload is too large.", "error");

            // Check if we can upload to the specified path, if not DIE and inform the user.
            if (!is_writable($upload_path))
                echo message_show("You cannot upload to the specified directory, please CHMOD it to 777.", "error");

            // Upload the file to your specified path.
            if (move_uploaded_file($_FILES['flDocumentPath']['tmp_name'], $upload_path . $filename))
                $upload_success = 1;
            else
                echo message_show("There was an error during the file upload.  Please try again.", "error");
        }else {
            echo message_show("Only files with format pdf, gif, jpeg, JPEG, PNG, GIF are allowed. Please try again.", "error");
        }
    } else {
        $_FILES['flDocumentPath']['error'];

        switch ($_FILES['flDocumentPath']['error']) {
            case 0: //no error; possible file attack!
                echo message_show("There was a problem with your upload.", "error");
                break;
            case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                echo message_show("The file you are trying to upload is too big.", "error");
                break;
            case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                echo message_show("The file you are trying to upload is too big.", "error");
                break;
            case 3: //uploaded file was only partially uploaded
                echo message_show("The file you are trying upload was only partially uploaded.", "error");
                break;
            case 4: //no file was uploaded
                $upload_success = 1;
                break;
            default: //a default error, just in case!  :)
                echo message_show("There was a problem with your upload.", "error");
                break;
        }
    }


    if ($upload_success == 1) {      // document uploded, store the details
        if (isset($_GET['lid'])) {
            $document_id = $_GET['lid'];
        }

        $array = array();

        //        $fname = replaceBlank($fname);
        $filename = replaceBlank($filename);

        $error_message = $out_message = "";
        $description = $_POST['txtDescription'];

        if ($filename == "NULL") {
            // file is not uploaded
            // so take the default value
            $fname = replaceBlank($_POST['txtOldFileUrl']);
            $filename = $fname;
        }

        if ($action == "E") {
            $query = " update trn_bill_documents set bill_id = $bill_id, description = " . replaceBlank($description) . ", document_path = $filename , status = 'A',";
            $query = $query . " edited_by = " . $_SESSION['user_id'] . ", edited_on = now() where document_id = $document_id";
            array_push($array, $query);
            $out_message = "Document updated successfully.";
        } else {
            $query = "insert into trn_bill_documents (bill_id,description, document_path, status, created_by, created_on)";
            $query = $query . " values($bill_id, " . replaceBlank($description) . ", $filename,'A', " . $_SESSION['user_id'] . ",now())";
            array_push($array, $query);
            $out_message = "Bill Created successfully.";
        }

        if (!$obj->execute($query, $error_message)) {
            $obj->save_log("Bill Document creation error: $error_message.", $_SERVER["REQUEST_URI"]);
            echo message_show($error_message, "error");
            return FALSE;
        } else {
            $log_text = $_SESSION['user_name'] . " has uploded the document $description on " . date('d-m-Y');
            $obj->save_bill_log($log_text, $bill_id);

            $obj->save_log($out_message, $_SERVER["REQUEST_URI"]);
            echo message_show($out_message, "success");
            header("location: master_bills_it.php?viewid=$view_id&pkeyid=" . $bill_id . "&flagsave=1");
            return TRUE;
        }
    }
}

function display_data($bill_id) {
    global $obj, $bill_type, $advance_request_number, $building_id, $building_name, $bill_id, $bts_number, $department_id, $bill_number, $bill_date, $bill_due_date, $amount, $payable_amount, $advance_percentage, $advance_amount, $currency, $company_id,
    $remarks, $vendor_name, $bid_number, $vendor_contact_person_name, $vendor_contact_person_contact_no, $hiranandani_contact_person_name, $hiranandani_contact_person_contact_no, $delivery_location,
    $workorder_number, $workorder_date, $activity_description, $workorder_release_date, $workorder_status, $vendor_id, $ses_date, $ses_no,
    $bill_delivery_location, $challan_number, $bill_delivery_date, $harcopy_send_yn, $status, $bill_category, $service_from_date, $service_to_date, $serial_no, $warranty_from_date, $warranty_to_date;
    ;

    $error_message = "";
    $query = "  select department_id,bill_type, advance_request_no,bill_number,bts_number,vendor_id, date_format(bill_date,'%d-%m-%Y') as bill_date,date_format(bill_due_date,'%d-%m-%Y') as bill_due_date,
			amount,approved_amount,building_id,building_name,advance_percentage,advance_amount, currency,company_id,remarks, bid_number,vendor_contact_person_name,vendor_contact_person_contact_no,hiranandani_contact_person_name,hiranandani_contact_person_contact_no,delivery_location, 
			vendor_name,status,ifnull(hardcopy_send_yn,'N') as hardcopy_send_yn,ses_date, ses_no,
                        workorder_number,date_format(workorder_date,'%d-%m-%Y') as workorder_date,activity_description,date_format(workorder_release_date,'%d-%m-%Y') as workorder_release_date, workorder_status,bill_delivery_location,date_format(bill_delivery_date,'%d-%m-%Y') as bill_delivery_date,
                        bill_category, date_format(service_from_date,'%d-%m-%Y') as service_from_date, date_format(service_to_date,'%d-%m-%Y') as service_to_date,serial_no,date_format(warranty_from_date,'%d-%m-%Y') as warranty_from_date,date_format(warranty_to_date,'%d-%m-%Y') as warranty_to_date,challan_number
from  mst_bills where bill_id = '$bill_id' ";
//echo $query;
    $result = $obj->execute($query, $error_message);
    if (isset($result)) {
        $row = mysqli_fetch_object($result);
        $department_id = $row->department_id;
        $bill_number = $row->bill_number;
        $bts_number = $row->bts_number;
        $bill_date = $row->bill_date;
        $bill_due_date = $row->bill_due_date;
        $amount = $row->amount;
        $payable_amount = $row->approved_amount;
        $advance_amount = $row->advance_amount;
        $advance_percentage = $row->advance_percentage;

        $bill_type = $row->bill_type;
        $advance_request_number = $row->advance_request_no;

        $currency = $row->currency;
        $company_id = $row->company_id;
        $vendor_id = $row->vendor_id;
        $vendor_name = $row->vendor_name;
        $remarks = $row->remarks;
        $vendor_contact_person_name = $row->vendor_contact_person_name;
        $vendor_contact_person_contact_no = $row->vendor_contact_person_contact_no;
        $hiranandani_contact_person_name = $row->hiranandani_contact_person_name;
        $hiranandani_contact_person_contact_no = $row->hiranandani_contact_person_contact_no;
        $delivery_location = $row->delivery_location;
        $harcopy_send_yn = ($row->hardcopy_send_yn == "Y") ? "checked" : "";

        $bid_number = $row->bid_number;
        $workorder_number = $row->workorder_number;
        $workorder_date = $row->workorder_date;
        $activity_description = $row->activity_description;
        $workorder_release_date = $row->workorder_release_date;
        $workorder_status = $row->workorder_status;

        $bill_delivery_location = $row->bill_delivery_location;
        $bill_delivery_date = $row->bill_delivery_date;

        $bill_category = $row->bill_category;
        $service_from_date = $row->service_from_date;
        $service_to_date = $row->service_to_date;
        $serial_no = $row->serial_no;
        $warranty_from_date = $row->warranty_from_date;
        $warranty_to_date = $row->warranty_to_date;

        $ses_date = $row->ses_date;
        $ses_no = $row->ses_no;
        $challan_number = $row->challan_number;
        $status = ($row->status == "A") ? "checked" : "";
        $building_id = $row->building_id . '_' . $row->building_name;
        //  $building_name = $row->building_name;

        unset($row);
        mysqli_free_result($result);
    }
}

function validate_email_user() {
    global $obj, $client_id, $otp_send_mobile, $otp_verify_mobile, $client_mobile;

//    echo "<pre>";
//    print_r($_POST);
//    


    if (isset($_POST['validate_email'])) {
        $otp_send_email = $_POST['otp_send_email'];
        $otp_verify_email = $_POST['otp_verify_email'];
        $client_email = $_POST['client_email'];
  $array = array();
        if ($otp_send_email == $otp_verify_email) {

            $query = " update mst_clients set email_verified_yn='Y',email_verified_on=now() where client_id=" . $client_id;

            array_push($array, $query);

          //  echo "<pre>";            print_r($array);exit;
            if (!$obj->execute_sqli_array($array, $error_message)) {

                echo message_show($error_message, "error");
                return FALSE;
            } else {

                if ($client_id == "") {
                    $client_id = $_SESSION['client_id'];
                }

                header("Location:client_info.php?action=EDIT&msg=success&viewid=" . $_REQUEST['viewid'] . "&flagsave=2");
                return TRUE;
            }
        } else {
            echo message_show('Invalid OTP', "error");
            return FALSE;
        }
    }
}

function validate_mobile_user() {
    global $obj, $client_id, $otp_send_mobile, $otp_verify_mobile, $client_mobile;

//    echo "<pre>";
//    print_r($_POST);
//    exit;


    if (isset($_POST['validate_mobile'])) {
        $otp_send_mobile = $_POST['otp_send_mobile'];
        $otp_verify_mobile = $_POST['otp_verify_mobile'];
        $client_mobile = $_POST['client_mobile'];
  $array = array();
        if ($otp_send_mobile == $otp_verify_mobile) {

            $query = " update mst_clients set mobile_verified_yn='Y',mobile_verfied_on=now() where client_id=" . $client_id;

            array_push($array, $query);

            if (!$obj->execute_sqli_array($array, $error_message)) {

                echo message_show($error_message, "error");
                return FALSE;
            } else {

                if ($client_id == "") {
                    $client_id = $_SESSION['client_id'];
                }

                header("Location:client_info.php?action=EDIT&msg=success&viewid=" . $_REQUEST['viewid'] . "&flagsave=3");
                return TRUE;
            }
        } else {
            echo message_show('Invalid OTP', "error");
            return FALSE;
        }
    }
}

function agree_terms_user(){
    global $obj, $client_id, $otp_send_mobile, $otp_verify_mobile, $client_mobile;

//    echo "<pre>";
//    print_r($_POST);
//    exit;



    if (isset($_POST['agree_terms'])) {
       $chkStatus = $_POST['chkStatus'];
  $array = array();
        if ($chkStatus == 'on') {

            $query = " update mst_clients set agree_terms_yn='Y',agree_terms_on=now() where client_id=" . $client_id;

            array_push($array, $query);

            if (!$obj->execute_sqli_array($array, $error_message)) {

                echo message_show($error_message, "error");
                return FALSE;
            } else {

                if ($client_id == "") {
                    $client_id = $_SESSION['client_id'];
                }

                header("Location:dashboard.php");
                return TRUE;
            }
        } else {
            echo message_show('Invalid OTP', "error");
            return FALSE;
        }
    }
}
function display_detail_data($document_id) {

    global $obj, $bill_id, $description, $document_path;
    $error_message = "";
    $query = "SELECT bill_id,description,document_path FROM trn_bill_documents where document_id  = $document_id";

    $result = $obj->execute($query, $error_message);
    if (isset($result)) {
        $row = mysqli_fetch_object($result);
        $bill_id = $row->bill_id;
        $description = $row->description;
        $document_path = $row->document_path;


        unset($row);
        mysqli_free_result($result);
    }
}

function delete_detail_data($document_id) {


    global $obj, $view_id, $bill_id;
    $error_message = "";
    $array = array();

    // delete sub items, if any
    $query = "delete from trn_bill_documents where document_id = $document_id";
    array_push($array, $query);


    if ($obj->execute_sqli_array($array, $error_message)) {

        $obj->save_log("Bill document data deleted successfully.", $_SERVER["REQUEST_URI"]);
        echo message_show("Bill document data deleted successfully.", "success");
        header("location: master_bills_it.php?viewid=$view_id&pkeyid=" . $bill_id . "&flagsave=1");
    } else {
        echo message_show($error_message, "error");
    }
}

function random_string($length) {

    $uid = uniqid(rand(), true);
    $stamp = date("Ymdhis");
    $ip = $_SERVER['REMOTE_ADDR'];
    $retval = $stamp . $ip . $uid;
    $retval = str_replace(".", "", $retval);
    $retval = str_replace(":", "", $retval);
    if ($length > 0) {
        $retval = substr($retval, $length);
    }
    return $retval;
}

include('footer.php');
unset($obj);
?>

