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

// header data variables
$bill_id = $bill_type = $advance_request_number = $building_id = $building_name = $challan_number = $bts_number = $department_id = $bill_number = $bill_date = $bill_due_date = $amount = $advance_percentage = $advance_amount = "";
$payable_amount = $currency = $company_id = $remarks = $vendor_name = $bid_number = $bill_delivery_location = $bill_delivery_date = "";
$bill_category = $service_from_date = $service_to_date = "";
$workorder_number = $workorder_date = $activity_description = $workorder_status = $workorder_release_date = $service_to_date = $serial_no = $warranty_from_date = $warranty_to_date = "";
$vendor_contact_person_name = $vendor_contact_person_contact_no = $hiranandani_contact_person_name = $hiranandani_contact_person_contact_no = "";
$delivery_location = "";
$harcopy_send_yn = "";
$ses_no = $ses_date = "";

$pagetext = 'Raise Invoice';
$description = "Invoice";
$currency = 1; // INDIAN RUPEE
$so_read_only = false;
$status = "checked";

$challan_number = isset($_REQUEST['cmbChallanNumber']) ? $_REQUEST['cmbChallanNumber'] : "";
$bill_type = isset($_REQUEST['cmbBillType']) ? $_REQUEST['cmbBillType'] : "";
$advance_request_number = isset($_REQUEST['txtAdvanceRequestNo']) ? $_REQUEST['txtAdvanceRequestNo'] : "";
$vendor_id = isset($_REQUEST['txtVendorName']) ? $_REQUEST['txtVendorName'] : "";
$amount = isset($_REQUEST['txtAmount']) ? $_REQUEST['txtAmount'] : "";
$company_id = isset($_REQUEST['cmbCompany']) ? $_REQUEST['cmbCompany'] : "";
$bill_number = isset($_REQUEST['txtBillNo']) ? $_REQUEST['txtBillNo'] : "";
$bill_date = isset($_REQUEST['txtBillDate']) ? $_REQUEST['txtBillDate'] : "";
$bill_due_date = isset($_REQUEST['txtBillDueDate']) ? $_REQUEST['txtBillDueDate'] : "";
$payable_amount = isset($_REQUEST['txtPayableAmount']) ? $_REQUEST['txtPayableAmount'] : "";
$bill_category = isset($_REQUEST['cmbBillCategory']) ? $_REQUEST['cmbBillCategory'] : "";
$service_from_date = isset($_REQUEST['txtServiceFromDate']) ? $_REQUEST['txtServiceFromDate'] : "";
$service_to_date = isset($_REQUEST['txtServiceToDate']) ? $_REQUEST['txtServiceToDate'] : "";

$vendor_contact_person_name = isset($_REQUEST['txtVendorContactPerson']) ? $_REQUEST['txtVendorContactPerson'] : "";
$vendor_contact_person_contact_no = isset($_REQUEST['txtVendorContactPersonContactNo']) ? $_REQUEST['txtVendorContactPersonContactNo'] : "";
$hiranandani_contact_person_name = isset($_REQUEST['txtHiranandaniContactPerson']) ? $_REQUEST['txtHiranandaniContactPerson'] : $_SESSION['user_name'];
$hiranandani_contact_person_contact_no = isset($_REQUEST['txtHiranandaniContactPersonContactNo']) ? $_REQUEST['txtHiranandaniContactPersonContactNo'] : $_SESSION['mobile_no'];

$remarks = isset($_REQUEST['txtRemarks']) ? $_REQUEST['txtRemarks'] : "";
$advance_amount = isset($_REQUEST['txtAdvanceAmount']) ? $_REQUEST['txtAdvanceAmount'] : "";
$workorder_number = isset($_REQUEST['txtWorkorderNumber']) ? $_REQUEST['txtWorkorderNumber'] : "";
$workorder_date = isset($_REQUEST['txtWorkorderDate']) ? $_REQUEST['txtWorkorderDate'] : "";
$workorder_release_date = isset($_REQUEST['txtWorkorderReleaseDate']) ? $_REQUEST['txtWorkorderReleaseDate'] : "";
$workorder_status = isset($_REQUEST['cmbWorkorderStatus']) ? $_REQUEST['cmbWorkorderStatus'] : "";
$delivery_location = isset($_REQUEST['txtDeliveryLocation']) ? $_REQUEST['txtDeliveryLocation'] : "";
$bill_delivery_location = isset($_REQUEST['txtBillDeliveryLocation']) ? $_REQUEST['txtBillDeliveryLocation'] : "";
$bill_delivery_date = isset($_REQUEST['txtBillDeliveryDate']) ? $_REQUEST['txtBillDeliveryDate'] : "";
$bid_number = isset($_REQUEST['txtBidNumber']) ? $_REQUEST['txtBidNumber'] : "";
$serial_no = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : "";
$warranty_from_date = isset($_REQUEST['txtwarrantyfromdate']) ? $_REQUEST['txtwarrantyfromdate'] : "";
$warranty_to_date = isset($_REQUEST['txtwarrantytodate']) ? $_REQUEST['txtwarrantytodate'] : "";
$activity_description = isset($_REQUEST['txtActivityDescription']) ? $_REQUEST['txtActivityDescription'] : "";
$ses_no = isset($_REQUEST['txtSESNo']) ? $_REQUEST['txtSESNo'] : "";
$ses_date = isset($_REQUEST['txtSESDate']) ? $_REQUEST['txtSESDate'] : "";


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
            $("#custom-tabs-one-files-tab").trigger("click");
            //            $("#custom-tabs-one-home-tab").removeClass('active');
            //            $("#custom-tabs-one-files-tab").addClass('active');
            $('.nav-links a[href="#custom-tabs-one-files-tab"]').tab('show');
        });
    </script>
    <?php
}

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
if ($bill_id != "") {
    $bill_status = $obj->get_execute_scalar("select status from mst_bills where bill_id ='$bill_id'", $error_message);
    if ($bill_status != "K") {
        echo message_show("The said bill is already in process and hence cannot be edited.", "alert");
        exit;
    }
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Raise Invoice</h1>
                </div><!-- /.col -->

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $pagetext; ?></li>
                    </ol>
                </div><!-- /.col -->
                <marquee><font color="red">You can not raise the bill of vendors who have not updated their bank details.</font></marquee>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Raise Invoice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-files-tab" data-toggle="pill" href="#custom-tabs-one-files" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Uploads Files</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-note" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-legends-tab" data-toggle="pill" href="#custom-tabs-one-legends" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Legends</a>
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
                                        $vendor_id = $_POST['txtVendorName'];

                                        $vendor_pan_number = $obj->get_execute_scalar("select REPLACE(`pan_number`, ' ', '' ) as `pan_number` from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);
                                        $vendor_pan_number = trim($vendor_pan_number);
                                        $length = strlen($vendor_pan_number);
                                        if ($length != 10) {
                                            echo message_show("Bills cannot be submitted for vendor who dont have PAN number", "error");
                                            exit;
                                        }

                                        $utility_vendor_yn = $obj->get_execute_scalar("select ifnull(utility_vendor_yn,'N') as utility_vendor_yn from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);
                                        $reference_vendor_code = $obj->get_execute_scalar("select reference_vendor_code from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);

                                        
                                        if ($utility_vendor_yn == 'N') {
                                            
                                            if($reference_vendor_code == 'NONSAP'){
                                                   $audited_yn = $obj->get_execute_scalar("select ifnull(audited_yn,'N') as audited_yn from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);
                                            if ($audited_yn != "Y") {
                                                echo message_show("You cannot raise the invoice as vendor haven't yet updated bank account details or updated bank details are not yet audited.", "alert");
                                                exit;
                                            }
                                            }else{
                                                   $sap_audited_yn = $obj->get_execute_scalar("select ifnull(sap_audited_yn,'N') as sap_audited_yn from mst_vendor where vendor_id ='" . $vendor_id . "'", $error_message);
                                            if ($sap_audited_yn != "Y") {
                                                echo message_show("You cannot raise the invoice as vendor haven't yet updated bank account details or updated bank details are not yet audited.", "alert");
                                                exit;
                                            }
                                            }
                                         
                                        }
                                        //////////////// validation for tds declaration ///////////////////////////////
                                        $tds_declaration_required = $obj->get_execute_scalar("select ifnull(tds_declaration_audit_status,'N') as tds from mst_vendor where ifnull(tds_declaration_yn,'Y')='Y' and  vendor_id ='" . $vendor_id . "'", $error_message);

                                        if ($tds_declaration_required == 'N') {
                                            echo message_show("You cannot raise the invoice as vendor haven't yet uploaded / audited tds declaration.", 'alert');
                                            exit;
                                        }
                                        ////////////////  end  ///////////////////////////////

                                        if (isset($_POST['submit'])) {
                                            $bill_no = $_POST['txtBillNo'];
                                            $bill_no = trim($bill_no);
                                            $isDuplicateBill_btsno = $obj->get_execute_scalar("select bts_number from mst_bills where vendor_id='" . $_POST['txtVendorName'] . "' and bill_number='" . $bill_no . "' and status <> 'R' and bill_id <> '$bill_id'", $error_message);
                                            //     $isDuplicateBill_btsno = $con->get_execute_scalar($query, $error_message);
                                            if (isset($isDuplicateBill_btsno) && $isDuplicateBill_btsno != "") {
                                                echo message_show('Invoice number ' . $bill_no . ' with BTS no ' . $isDuplicateBill_btsno . ' is already In Process. You can not submit multiple invoices with same invoice number.', "error");
                                            } else {
                                                //END OF INVOICE NUMBER VALIDATION
                                                //     $invoice_date_validated = validate_invoice_date($_POST['txtBillDate']);
                                                $invoice_due_date_validated = validate_invoice_due_date($_POST['txtBillDate'], $_POST['txtBillDueDate']);

//                                    print_r($invoice_date_validated);
//                                    print_r($invoice_due_date_validated);
//                                    exit;
                                                //  if ($invoice_date_validated['success'] == '1') {
                                                if ($invoice_due_date_validated['success'] == '1') {
                                                    if (save_header_data() == true) {
                                                        // echo "hello";exit;
                                                        // echo message_show("Bill record updated successfully.", "success");
                                                    }
                                                } else {
                                                    echo message_show($invoice_due_date_validated['msg'], "error");
                                                }
                                                //   } else {
                                                //       echo message_show($invoice_date_validated['msg'], "error");
                                                //   }
                                            }
                                        }
                                        if (isset($_POST['btnAdd'])) {
                                            if (save_detail_data() == true) {
                                                
                                            }
                                        }
                                    } else {
                                        $obj->save_log("Bill page opened.", $_SERVER["REQUEST_URI"]);
                                    }

                                    if ($bill_id != "") {
                                        display_data($bill_id);
                                    }


                                    if ($action == "E") {
                                        display_detail_data($_GET['lid']);
                                    } elseif ($action == "D") {
                                        delete_detail_data($_GET['lid']);
                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <!-- general form elements disabled -->
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Invoice Details  <?php if (isset($bts_number) && $bts_number != '') { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b> <font color="red" style="font-size: 24px;">BTS No: <?php echo $bts_number; ?></font></b> <?php } ?> </h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <form id="invoiceForm" name="invoiceForm" method = "post" enctype="multipart/form-data">

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Invoice Type<font color="red">*</font></label>
                                                                <select class="form-control" id="cmbBillType" name="cmbBillType">
                                                                    <option <?php echo $bill_type == "" ? "selected" : ""; ?> value="">-- Select Bill Type--</option>
                                                                    <option <?php echo $bill_type == "Invoice" ? "selected" : ""; ?> value="Invoice">INVOICE</option>
                                                                    <option <?php echo $bill_type == "IOM" ? "selected" : ""; ?> value="IOM">IOM</option>
                                                                    <option <?php echo $bill_type == "HOLD RELEASE" ? "selected" : ""; ?> value="HOLD RELEASE">HOLD RELEASE</option>
                                                                    <option <?php echo $bill_type == "REFUND" ? "selected" : ""; ?> value="REFUND">REFUND</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Vendor Name<font color="red">*</font></label>
                                                                <select class="form-control select2bs4" style="width: 100%;" name="txtVendorName" id="txtVendorName">
                                                                    <?php
                                                                    $query = "select vendor_id,concat(ifnull(vendor_name,'') ,' | ',ifnull(reference_vendor_code,''),' | ',ifnull(pan_number,''),' | ',ifnull(gst_number,'')) as vendor_name from mst_vendor where status = 'A' order by vendor_name";
// $query = "select vendor_id,concat(vendor_name ,' - ',reference_vendor_code) as vendor_name from mst_vendor where status = 'A'  order by vendor_name ";
                                                                    echo $obj->fill_combo($query, $vendor_id, false);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Building Name<font color="red">*</font><span data-toggle="tooltip" data-placement="top" title="If it is not building level invoice Kindly select Not Applicable option."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control select2bs4" style="width: 100%;" name="txtBuildingName" id="txtBuildingName">
                                                                    <?php
                                                                    $query = "select concat(building_id,'_',building_name) as `building_id`,building_name from mst_building where status = 'A' order by building_name";
                                                                    echo $obj->fill_combo($query, $building_id, false);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Challan Number</label>
                                                                <input type="text"  class="form-control" name="cmbChallanNumber" id="cmbChallanNumber" value="<?php echo $challan_number; ?>" placeholder="Challan Number..">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Company <font color="red">*</font> </label>
                                                                <select class="form-control" name="cmbCompany" id="cmbCompany"  >
                                                                    <?php
                                                                    $query = " select  company_id, company_name from mst_company where status = 'A' order by company_name ";
                                                                    echo $obj->fill_combo($query, $company_id, false);
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Department <font color="red">*</font><span data-toggle="tooltip" data-placement="top" title="Select proper department for the bill it belogs to."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control" name="cmbDepartment" id="cmbDepartment"  >
                                                                    <?php
                                                                    $query = " select department_id, upper(department_name) as department_name from mst_department where status = 'A' order by department_name ";
                                                                    echo $obj->fill_combo($query, $department_id, false);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Invoice No. <font color="red">*</font></label>
                                                                <input type="text" id="txtBillNo" name="txtBillNo" class="form-control" placeholder="Invoice No.." value="<?php echo $bill_number; ?>">
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row">

                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Invoice Date <font color="red">*</font> <span data-toggle="tooltip" data-placement="top" title="Future dated invoice is not allowed."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <div class="input-group date" id="invoicedate" data-target-input="nearest">
                                                                    <input type="text" id="txtBillDate" name="txtBillDate" value="<?php echo $bill_date; ?>" class="form-control datetimepicker-input" data-target="#invoicedate"/>

                                                                    <div class="input-group-append" data-target="#invoicedate" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Invoice Due Date  <span data-toggle="tooltip" data-placement="top" title="Invoice Due date should be always greater than Invoice date."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <div class="input-group date" id="txtBillDueDate" data-target-input="nearest">
                                                                    <input type="text" id="txtBillDueDate" name="txtBillDueDate" value="<?php echo $bill_due_date; ?>" class="form-control datetimepicker-input" data-target="#txtBillDueDate"/>
                                                                    <div class="input-group-append" data-target="#txtBillDueDate" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Amount <font color="red">*</font>&nbsp<span data-toggle="tooltip" data-placement="top" title="PO/WO Number is mandatory for Invoice Amount greter than Rs.10,000/-."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input type="number" id="txtAmount" name="txtAmount"  class="form-control" placeholder="Amount..." value="<?php echo $amount; ?>" onblur="fill_payable_amount(this.value);">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Payable Amount <font color="red">*</font></label>
                                                                <input type="number" id="txtPayableAmount" name="txtPayableAmount" class="form-control" placeholder="Payable Amount..." value="<?php echo $payable_amount; ?>" >
                                                            </div>
                                                        </div>   

                                                    </div>
                                                    <div class="row">
                                                         <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Project Location <font color="red">*</font> <span data-toggle="tooltip" data-placement="top" title="Select proper department for the bill it belogs to."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control" name="project_location" id="project_location"  >
                                                                    <?php
                                                                    $query = " select  company_id, company_name from mst_company where status = 'A' order by company_name ";
                                                                    echo $obj->fill_combo($query, $company_id, false);
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                         <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Project <font color="red">*</font> <span data-toggle="tooltip" data-placement="top" title="Select proper department for the bill it belogs to."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control" name="project_location" id="project_location"  >
                                                                    <?php
                                                                    $query = " select  company_id, company_name from mst_company where status = 'A' order by company_name ";
                                                                    echo $obj->fill_combo($query, $company_id, false);
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                         <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Project Unit <font color="red">*</font> <span data-toggle="tooltip" data-placement="top" title="Select proper department for the bill it belogs to."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control" name="project_location" id="project_location"  >
                                                                    <?php
                                                                    $query = " select  company_id, company_name from mst_company where status = 'A' order by company_name ";
                                                                    echo $obj->fill_combo($query, $company_id, false);
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Remarks</label>
                                                                <textarea class="form-control" rows="3"  id="txtRemarks" name="txtRemarks"  placeholder="Remarks ..."><?php echo $remarks; ?></textarea>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Invoice Category <font color="red">*</font> <span data-toggle="tooltip" data-placement="top" title="If Invoice category is Service then Service From Date and Service To Date is mandatory."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <select class="form-control" id="cmbBillCategory" name="cmbBillCategory">
                                                                    <option <?php echo $bill_category == "" ? "selected" : ""; ?> value="">-- Select Invoice Category --</option>
                                                                    <option <?php echo $bill_category == "Service" ? "selected" : ""; ?> value="Service">Service</option>
                                                                    <option <?php echo $bill_category == "Material" ? "selected" : ""; ?> value="Material">Material</option>
                                                                    <option <?php echo $bill_category == "Contract" ? "selected" : ""; ?> value="Contract">Contract</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Advance Amount</label>
                                                                <input type="number" id="txtAdvanceAmount" name="txtAdvanceAmount" class="form-control" placeholder="Advance Amount..." value="<?php echo $advance_amount; ?>" >
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label>Service From Date <font color="red">*</font></label>
                                                                    <div class="input-group date" id="servicefromdate" data-target-input="nearest">
                                                                        <input type="text" id="txtServiceFromDate" name="txtServiceFromDate" class="form-control datetimepicker-input" data-target="#servicefromdate" value="<?php echo $service_from_date; ?>"/>
                                                                        <div class="input-group-append" data-target="#servicefromdate" data-toggle="datetimepicker">
                                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <div class="form-group">

                                                                    <label>Service To Date <font color="red">*</font></label>
                                                                    <div class="input-group date" id="servicetodate" data-target-input="nearest">
                                                                        <input type="text" id="txtServiceToDate" name="txtServiceToDate" class="form-control datetimepicker-input" value="<?php echo $service_to_date; ?>" data-target="#servicetodate"/>
                                                                        <div class="input-group-append" data-target="#servicetodate" data-toggle="datetimepicker">
                                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Vendor Contact Person <font color="red">*</font>&nbsp<span data-toggle="tooltip" data-placement="top" title="Name of the person from vendor side to whom we can contact in case of any query regarding this invoice."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input type="text"  id="txtVendorContactPerson" name="txtVendorContactPerson" class="form-control" placeholder="Vendor Contact Person..." value="<?php echo $vendor_contact_person_name; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Vendor Contact Person No. <font color="red">*</font></label>
                                                                <input type="number" id="txtVendorContactPersonContactNo" name="txtVendorContactPersonContactNo" class="form-control" placeholder="Contact No..." value="<?php echo $vendor_contact_person_contact_no; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Hiranandani Contact Person <font color="red">*</font>&nbsp<span data-toggle="tooltip" data-placement="top" title="Name of the person from Hiranandani with whom vendor is in touch for the said invoice."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input type="text" id="txtHiranandaniContactPerson" name="txtHiranandaniContactPerson" class="form-control" placeholder="Hiranandani Contact Person ..." value="<?php echo $hiranandani_contact_person_name; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Hiranandani Contact Person No. <font color="red">*</font></label>
                                                                <input type="number" id="txtHiranandaniContactPersonContactNo" name="txtHiranandaniContactPersonContactNo" class="form-control" placeholder="Contact No ..." value="<?php echo $hiranandani_contact_person_contact_no; ?>">
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>PO/WO Number&nbsp<span data-toggle="tooltip" data-placement="top" title="PO/WO number is mandatory if billing amount is greater than Rs.10,000/-"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input type="text" id="txtWorkorderNumber" name="txtWorkorderNumber" class="form-control" placeholder="PO/WO Number..." value="<?php echo $workorder_number; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>PO/WO Date</label>
                                                                <div class="input-group date" id="podate" data-target-input="nearest">
                                                                    <input type="text" id="txtWorkorderDate" name="txtWorkorderDate" class="form-control datetimepicker-input" data-target="#podate" value="<?php echo $workorder_date; ?>"/>
                                                                    <div class="input-group-append" data-target="#podate" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>WO Release Date</label>
                                                                <div class="input-group date" id="woreldate" data-target-input="nearest">
                                                                    <input type="text" id="txtWorkorderReleaseDate" name="txtWorkorderReleaseDate" class="form-control datetimepicker-input" data-target="#woreldate" value="<?php echo $workorder_release_date; ?>"/>
                                                                    <div class="input-group-append" data-target="#woreldate" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>WO Release Status</label>
        <!--                                                       <input type="text" id="txtWorkorderNumber" name="txtWorkorderNumber" class="form-control" placeholder="Enter ..." value="<?php echo $workorder_number; ?>">-->
                                                                <select class="form-control" name="cmbWorkorderStatus" id="cmbWorkorderStatus" >
                                                                    <option value="">Any</option>
                                                                    <option  <?php echo $workorder_status == "R" ? "selected" : ""; ?> value="R">Released</option>
                                                                    <option  <?php echo $workorder_status == "N" ? "selected" : ""; ?> value="N">Not Released</option>
                                                                </select>  
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Material/Service Delivery Location <font color="red">*</font></label>
                                                                <select class="form-control" name="txtDeliveryLocation" id="txtDeliveryLocation"  >
                                                                    <?php
                                                                    $query = " select location_name, location_name as location_name from mst_location where status = 'A' order by location_name ";
                                                                    echo $obj->fill_combo($query, $delivery_location, false);
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Invoice Delivery Location</label>
                                                                <select class="form-control" name="txtBillDeliveryLocation" id="txtBillDeliveryLocation"  >
                                                                    <?php
                                                                    $query = " select location_name, location_name as location_name from mst_location where status = 'A' order by location_name ";
                                                                    echo $obj->fill_combo($query, $bill_delivery_location, false);
                                                                    ?>
                                                                </select>


                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Delivery Date</label>
                                                                <div class="input-group date" id="deliverydate" data-target-input="nearest">
                                                                    <input type="text" id="txtBillDeliveryDate" name="txtBillDeliveryDate" class="form-control datetimepicker-input" data-target="#deliverydate" value="<?php echo $bill_delivery_date; ?>"/>
                                                                    <div class="input-group-append" data-target="#deliverydate" data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>BID Number</label>
                                                                <input type="text" id="txtBidNumber" name="txtBidNumber" class="form-control" placeholder="BID No..." value="<?php echo $bid_number; ?>">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <label>SES/GRN Date <span data-toggle="tooltip" data-placement="top" title="In case of material kindly update warranty from date"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                            <div class="input-group date" id="ses_date" data-target-input="nearest">
                                                                <input type="text" id="txtSESDate" name="txtSESDate" class="form-control datetimepicker-input" data-target="#ses_date" value="<?php echo $ses_date; ?>"/>
                                                                <div class="input-group-append" data-target="#ses_date" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label>Serial No <span data-toggle="tooltip" data-placement="top" title="In case of material kindly update Serial No."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input type="text" id="serial_no" name="serial_no" class="form-control" placeholder="Serial No..." value="<?php echo $serial_no; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <label>Warranty From Date <span data-toggle="tooltip" data-placement="top" title="In case of material kindly update warranty from date"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                            <div class="input-group date" id="warranty_from_date" data-target-input="nearest">
                                                                <input type="text" id="txtwarrantyfromdate" name="txtwarrantyfromdate" class="form-control datetimepicker-input" data-target="#warranty_from_date" value="<?php echo $warranty_from_date; ?>"/>
                                                                <div class="input-group-append" data-target="#warranty_from_date" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <label>Warranty To Date <span data-toggle="tooltip" data-placement="top" title="If Invoice category is Service then Service From Date and Service To Date is mandatory."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                            <div class="input-group date" id="warranty_to_date" data-target-input="nearest">
                                                                <input type="text" id="txtwarrantytodate" name="txtwarrantytodate" class="form-control datetimepicker-input" data-target="#warranty_to_date" value="<?php echo $warranty_to_date; ?>"/>
                                                                <div class="input-group-append" data-target="#warranty_to_date" data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-sm-3">
                                                            <div class="form-group">

                                                                <label for="exampleInputEmail1"> SES/GRN No</label>
                                                                <input class="form-control" type="text" id="txtSESNo" name="txtSESNo"  value="<?php echo $ses_no; ?>" autocomplete="off" />
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <div class="form-group">

                                                                <label for="exampleInputEmail1">Advance Request No.<span data-toggle="tooltip" data-placement="top" title="If Invoice type is IOM then Advance Request No and PO/WO number is mandatory."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                                                <input class="form-control" type="text" id="txtAdvanceRequestNo" name="txtAdvanceRequestNo"  value="<?php echo $advance_request_number; ?>" maxlength="200" autocomplete="off" />
                                                            </div>
                                                        </div>



                                                        <div class="col-sm-6">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Activity Description</label>
                                                                <textarea class="form-control" id="txtActivityDescription" name="txtActivityDescription" rows="3" placeholder="Activity Description ..."><?php echo $activity_description; ?></textarea>
                                                            </div>
                                                        </div>






                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <!-- text input -->
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="chkHardCopySend" name="chkHardCopySend" <?php echo $harcopy_send_yn; ?>>
                                                                <label >Hardcopy Send&nbsp<span data-toggle="tooltip" data-placement="top" title="If hardcopy of invoice is sent to us then kindly tick this."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
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

                    <div class="tab-pane fade" id="custom-tabs-one-note" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">


                        <!--<div class="content-wrapper px-4 py-2" style="min-height: 170px;">-->

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
                        <!--</div>-->


                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-files" role="tabpanel" aria-labelledby="custom-tabs-one-note-tab">


                        <!--<div class="content-wrapper px-4 py-2" style="min-height: 170px;">-->

                        <div class="content px-2">

                            <?php
//echo $bill_id;exit;
                            if ($bill_id != "") {
                                ?>

                                <form name ="uploadDocForm" id="uploadDocForm" method = "post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="hidden" name="txtVendorName" id="vendor_docs" value="<?php echo $vendor_id; ?>">
                                                     <!--                                                                    <select class="form-control" name="cmbCompany" id="cmbCompany"  >
                                                <?php
//                                                                        $query = " select  company_id, company_name from mst_company where status = 'A' order by company_name ";
//                                                                        echo $obj->fill_combo($query, $company_id, false);
                                                ?>
                                                                                             </select>-->
                                                <select  class="form-control" id="txtDescription" name="txtDescription" >
                                                    <option value="">Select</option> 
                                                    <?php
                                                    $query = "SELECT key_value,key_value FROM `gm_key_values` WHERE key_type='DOCUMENT' order by key_value";
                                                    echo $obj->fill_combo($query, $departmentowner, true);
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label for="exampleInputFile">Upload</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="flDocumentPath" name="flDocumentPath">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        <?php
                                                        if ($document_path != "") {
                                                            echo "<a href='../data/uploads/$document_path' target=_blank>Download " . $description . "</a>";
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php if ($document_path != "") { ?>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Uploaded document</label>
                                                    <div class="input-group">
                                                        <?php
                                                        //  echo "<img  src='../data/uploads/$document_path' style='height:50px;' ></img>";
                                                        echo "<a href='../data/uploads/$document_path' target=_blank>Download " . $description . "</a>";
                                                        //  echo "<a href='$vendor_logo' target=_blank>" . $vendor_logo . "</a>";                        
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Submit</label>
                                                <input type="submit" class="form-control btn btn-primary"  value="<?php echo ($action == "E") ? "Update" : "Add" ?> Document" name="btnAdd" id="btnAdd" onclick="return validateDocument();" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <?php
                            }
                            ?>
                        </div>
                        <!--</div>-->
                        <div class="content px-2">

                            <div class="col-md-12">
                                <div class="card-body">
                                    <table class="table  table-responsive">
                                        <thead>                  
                                            <tr>
                                                <th>#</th>
                                                <th>Document Name</th>
                                                <th>Download</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "select tpd.document_id, tpd.bill_id,tpd.description, tpd.document_path ";
                                            $query = $query . " from trn_bill_documents tpd ";
                                            $query = $query . " where tpd.bill_id = $bill_id and tpd.status = 'A' order by tpd.created_on";
                                            $result = $obj->execute($query, $error_message);

                                            $row_count = 0;
                                            if ($result) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<tr height=\"18\">";
                                                    printf("<td>%s</td>", ($row_count + 1));
                                                    printf("<td>%s</td>", $row['description']);
                                                    printf("<td class='text-left py-0 align-middle'><div class='btn-group btn-group-sm'><a class='btn btn-warning' href='../data/uploads/%s' target='_blank'><i class='fas fa-download'></i></a></div></td>", $row['document_path']);


                                                    if ($so_read_only == false) {
                                                        printf("<td class='text-left py-0 align-middle'><div class='btn-group btn-group-sm'><a class='btn btn-info' href=master_bills_it.php?viewid=%s&pkeyid=%s&lid=%s&act=E&flagsave=1><i class='fas fa-eye'></i></a></div></td>", $view_id, $bill_id, $row['document_id']);
                                                        printf("<td class='text-left py-0 align-middle'><div class='btn-group btn-group-sm'><a class='btn btn-danger' href=master_bills_it.php?viewid=%s&pkeyid=%s&lid=%s&act=D&flagsave=1 onClick=\"return confirm('Are you sure to delete this entry?')\"><i class='fas fa-trash'></i></a></div></td>", $view_id, $bill_id, $row['document_id']);
                                                    }
                                                    echo "</tr>";

                                                    $row_count++;
                                                }
                                                mysqli_free_result($result);
                                            }
                                            if ($row_count == 0) {
                                                printf("<tr><td style=\"text-align: center;\" colspan=5><b>No data found!</b></td></tr>");
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="card-footer">
                                <a href="../frm/listview.php?id=12" class="btn btn-danger">Back</a>
                                <?php //     printf("<td><a class='btn btn-danger' href=../frm/listview.php?id=12>Back</a></td>", $view_id, $bill_id);   ?>
                                <!--                                                        <a href="frm/master_bills_vendor.php?viewid=&pkeyid=5&flagsave=1" class="btn btn-danger">Back</a>-->
                            </div>


                        </div>

                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-legends" role="tabpanel" aria-labelledby="custom-tabs-one-legends-tab">



                        <div class="content px-2">
                            <p>Following are description of various Invoice's status.</p>
                            <?php
                            $query = "select status_name, status_description  from mst_bill_status where status_description is not null order by display_order";
                            $result = $obj->execute($query, $error_message);

                            echo " <ul>";
                            while ($row = mysqli_fetch_array($result)) {


                                echo "<li><h5 class=\"text-bold text-dark mt-1\">" . $row["status_name"] . "</h5>" . $row["status_description"] . "</li>";
                            }
                            echo "</ul>";

                            unset($row);
                            mysqli_free_result($result);
                            unset($obj);
                            ?>



                        </div>
                        <!--</div>-->


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
                                                    function invoicedateValidation123() {

                                                        var isSuccess = "16";


                                                    }
                                                    function fill_payable_amount(invoice_amount) {
                                                        $("#txtPayableAmount").val(invoice_amount);
                                                    }

                                                    $("#txtBillDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });
                                                    $("#txtServiceFromDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });
                                                    $("#txtServiceToDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });
                                                    $("#txtWorkorderDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });

                                                    $("#txtWorkorderReleaseDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });

                                                    $("#txtBillDeliveryDate").keypress(function(event) {
                                                        event.preventDefault();
                                                    });

                                                    $("#ses_date").keypress(function(event) {
                                                        event.preventDefault();
                                                    });





                                                    $(document).ready(function() {

                                                        $('[data-toggle="tooltip"]').tooltip();
                                                        bsCustomFileInput.init();

                                                        //Date range picker
                                                        $('#servicefromdate').datetimepicker({
                                                            // format: 'L'
                                                            format: 'DD-MM-YYYY'
                                                        });

                                                        $('#txtBillDueDate').datetimepicker({
                                                            format: 'DD-MM-YYYY',
                                                            mindateallow: true

                                                        });

                                                        $('#ses_date').datetimepicker({
                                                            format: 'DD-MM-YYYY',
                                                        });

                                                        $('#servicetodate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                        $('#podate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                        $('#woreldate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                        $('#deliverydate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });
                                                        $('#invoicedate').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                                    //  maxDate: moment()
                                                        });
                                                        $('#warranty_from_date').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });

                                                        $('#warranty_to_date').datetimepicker({
                                                            format: 'DD-MM-YYYY'
                                                        });

                                                    });




                                                    jQuery.validator.addMethod("invoicedatevalidation", function(value, element) {
                                                        var abc = "";
                                                        var invoice_date = $("#txtBillDate").val();

                                                        $.ajax({
                                                            url: "a.php",
                                                            data: {invoice_date: invoice_date},
                                                            type: 'POST',
                                                            dataType: "json",
                                                            success: function(result) {
                                                                //isSuccess = result.success;
                                                                if (result.success == 'yes') {

                                                                    abc = true;
                                                                    $("#invoice_hidden").val(abc);

                                                                } else {
                                                                    abc = false;
                                                                    $("#invoice_hidden").val(abc);

                                                                }

                                                            }});


                                                    });
//, 'Invoice date is not proper'
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

                                                        $('#uploadDocForm').validate({
                                                            rules: {
                                                                txtDescription: {
                                                                    required: true
                                                                },
                                                                flDocumentPath: {
                                                                    required: true,
                                                                    extension: "pdf|jpeg|jpg|png|gif"
                                                                }

                                                            },
                                                            messages: {
                                                                txtDescription: {
                                                                    required: "Please Select Description"
                                                                },
                                                                flDocumentPath: {
                                                                    required: "Please upload document",
                                                                    extension: "Only pdf,jpeg,jpg,png,gif allowed to upload"
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
<?php

function save_header_data() {

    global $obj, $bill_id, $bill_type, $advance_request_number, $building_id, $building_name, $bts_number, $department_id, $bill_number, $bill_date, $bill_due_date,
    $amount, $payable_amount, $advance_amount, $advance_percentage, $currency, $company_id, $remarks, $vendor_name, $bill_category, $service_from_date, $service_to_date,
    $vendor_contact_person_name, $vendor_contact_person_contact_no, $hiranandani_contact_person_name, $hiranandani_contact_person_contact_no, $delivery_location, $bid_number, $harcopy_send_yn, $view_id,
    $workorder_number, $challan_number, $workorder_date, $activity_description, $workorder_release_date, $workorder_status, $bill_delivery_location, $bill_delivery_date, $warranty_from_date, $warranty_to_date, $serial_no, $ses_date, $ses_no;
    $status = "checked";

    $error_message = $out_message = "";

    $vendor_details = array();

    //  echo "<pre>"; print_r($_POST);exit;
    $building = isset($_POST['txtBuildingName']) ? $_POST['txtBuildingName'] : "";
    if ($building != "") {
        $buildingArr = explode("_", $building);
        if (!empty($buildingArr)) {
            $building_id = $buildingArr[0];
            $building_name = $buildingArr[1];
        }
    }

//echo "<pre>"; print_r($buildingArr);exit;
    $department_id = $_POST['cmbDepartment'];
    $bill_number = ltrim($_POST['txtBillNo']);
    if ($_POST['txtBillDate'] != "") {
        $bill_date = date('Y-m-d', strtotime($_POST['txtBillDate']));
    }
    if ($_POST['txtBillDueDate'] != "") {
        $bill_due_date = date('Y-m-d', strtotime($_POST['txtBillDueDate']));
    }
    $challan_number = $_POST['cmbChallanNumber'];

    $bill_type = $_POST['cmbBillType'];
    $advance_request_number = $_POST['txtAdvanceRequestNo'];

    $amount = $_POST['txtAmount'];
    $payable_amount = $_POST['txtPayableAmount'];
    $currency = 1;
    $company_id = $_POST['cmbCompany'];
    $vendor_id = $_POST['txtVendorName'];
    $bill_type = $_POST['cmbBillType'];
    $vendor_name = $obj->get_execute_scalar("select  vendor_name from mst_vendor where vendor_id = '$vendor_id'", $error_message);
    $remarks = $_POST['txtRemarks'];
    $bid_number = $_POST['txtBidNumber'];
    $workorder_number = $_POST['txtWorkorderNumber'];
    if ($_POST['txtWorkorderDate'] != "") {
        $workorder_date = date('Y-m-d', strtotime($_POST['txtWorkorderDate']));
    }

    $activity_description = $_POST['txtActivityDescription'];
    if ($_POST['txtWorkorderReleaseDate'] != "") {
        $workorder_release_date = date('Y-m-d', strtotime($_POST['txtWorkorderReleaseDate']));
    }

    $workorder_status = $_POST['cmbWorkorderStatus'];
    $harcopy_send_yn = (isset($_POST['chkHardCopySend'])) ? $_POST['chkHardCopySend'] : '';
    $harcopy_send_yn = ($harcopy_send_yn == "on") ? "Y" : "N";
    $vendor_contact_person_name = $_POST['txtVendorContactPerson'];
    $vendor_contact_person_contact_no = $_POST['txtVendorContactPersonContactNo'];
    $hiranandani_contact_person_name = $_POST['txtHiranandaniContactPerson'];
    $hiranandani_contact_person_contact_no = $_POST['txtHiranandaniContactPersonContactNo'];
    $delivery_location = $_POST['txtDeliveryLocation'];
    $bill_delivery_location = $_POST['txtBillDeliveryLocation'];
    if ($_POST['txtBillDeliveryDate'] != "") {
        $bill_delivery_date = date('Y-m-d', strtotime($_POST['txtBillDeliveryDate']));
    }


    $bill_category = $_POST['cmbBillCategory'];
    if ($_POST['txtServiceFromDate'] != "") {
        $service_from_date = date('Y-m-d', strtotime($_POST['txtServiceFromDate']));
    }
    if ($_POST['txtServiceToDate'] != "") {
        $service_to_date = date('Y-m-d', strtotime($_POST['txtServiceToDate']));
    }
    if ($_POST['txtwarrantyfromdate'] != "") {
        $warranty_from_date = date('Y-m-d', strtotime($_POST['txtwarrantyfromdate']));
    }
    if ($_POST['txtwarrantytodate'] != "") {
        $warranty_to_date = date('Y-m-d', strtotime($_POST['txtwarrantytodate']));
    }

    if ($_POST['txtSESDate'] != "") {
        $ses_date = date('Y-m-d', strtotime($_POST['txtSESDate']));
    }

    $ses_no = $_POST['txtSESNo'];
    $serial_no = $_POST['serial_no'];

    $advance_percentage = ""; //$_POST['txtAdvancePercentage'];
    $advance_amount = $_POST['txtAdvanceAmount'];

    $array = array();

    $task = $log_text = "";
    if ($bill_id != "") {

        $task = 'updated';
        $query = " update mst_bills set ";
        $query = $query . " bill_type = " . replaceBlank($bill_type) . ",advance_request_no = " . replaceBlank($advance_request_number) . ", department_id = " . replaceblank($department_id) . ", ";
        $query = $query . " bill_number = " . replaceblank($bill_number) . ", bill_date = " . replaceDate($bill_date) . ", bill_due_date = " . replaceDate($bill_due_date) . ", ";
        $query = $query . " amount = " . replaceblank($amount) . ",approved_amount = " . replaceblank($payable_amount) . ",advance_amount = " . replaceblank($advance_amount) . ",advance_percentage = " . replaceblank($advance_percentage) . ", currency = " . replaceblank($currency) . ", company_id = " . replaceblank($company_id) . ",remarks=" . replaceBlank($remarks) . ",  ";
        $query = $query . " vendor_name = " . replaceBlank($vendor_name) . ",bid_number = " . replaceBlank($bid_number) . ",";
        $query = $query . " vendor_contact_person_name = " . replaceBlank($vendor_contact_person_name) . ",vendor_contact_person_contact_no = " . replaceBlank($vendor_contact_person_contact_no) . ",hiranandani_contact_person_name = " . replaceBlank($hiranandani_contact_person_name) . ",hiranandani_contact_person_contact_no = " . replaceBlank($hiranandani_contact_person_contact_no) . ",delivery_location = " . replaceBlank($delivery_location) . ",hardcopy_send_yn='$harcopy_send_yn',";
        $query = $query . " bill_category = " . replaceBlank($bill_category) . ",service_from_date = " . replaceDate($service_from_date) . ",service_to_date = " . replaceDate($service_to_date) . ",";
        $query = $query . " workorder_number = " . replaceBlank($workorder_number) . ", workorder_date = " . replaceDate($workorder_date) . ",activity_description = " . replaceblank($activity_description) . "  ,workorder_release_date = " . replaceDate($workorder_release_date) . " ,workorder_status = " . replaceBlank($workorder_status) . " , ";
        $query = $query . " bill_delivery_location = " . replaceBlank($bill_delivery_location) . " ,bill_delivery_date = " . replaceDate($bill_delivery_date) . ",ses_date = " . replaceDate($ses_date) . ",ses_no = " . replaceBlank($ses_no) . ",vendor_id = '$vendor_id',";
        $query = $query . " edited_by = " . $_SESSION['user_id'] . ", edited_on = now() ,warranty_from_date =" . replaceBlank($warranty_from_date) . ",warranty_to_date =" . replaceBlank($warranty_to_date) . ",serial_no =" . replaceBlank($serial_no) . ",challan_number=" . replaceBlank($challan_number) . " ,building_id=" . replaceBlank($building_id) . ",building_name=" . replaceBlank($building_name) . " where bill_id = $bill_id ";



        array_push($array, $query);
        $log_text = "updated";

        $out_message = "Bill details updated successfully.";
    } else {
        $log_text = 'raised';

        $bts_number = $obj->get_next_id("BTS", "NUMBER", $return_sql);
        array_push($array, $return_sql);
        $query = " insert into mst_bills (bts_number,bill_type, advance_request_no,department_id, bill_number, bill_date, bill_due_date, ";
        $query = $query . " amount,approved_amount, advance_amount,advance_percentage, currency, company_id,  vendor_name, remarks,vendor_image_url, bid_number, ";
        $query = $query . " bill_category,service_from_date,service_to_date ,ses_date, ses_no,";
        $query = $query . " vendor_contact_person_name,vendor_contact_person_contact_no,hiranandani_contact_person_name,hiranandani_contact_person_contact_no,delivery_location,workorder_number,workorder_date, activity_description,workorder_release_date, workorder_status,bill_delivery_location,bill_delivery_date,    hardcopy_send_yn,vendor_id, status,created_by, created_on,serial_no,warranty_from_date,warranty_to_date,challan_number,building_id,building_name) values ( ";
        $query = $query . " " . replaceBlank($bts_number) . "," . replaceBlank($bill_type) . "," . replaceBlank($advance_request_number) . "," . replaceBlank($department_id) . "," . replaceBlank($bill_number) . "," . replaceDate($bill_date) . "," . replaceDate($bill_due_date) . ",";
        $query = $query . " " . replaceBlank($amount) . "," . replaceBlank($payable_amount) . "," . replaceBlank($advance_amount) . "," . replaceBlank($advance_percentage) . "," . replaceBlank($currency) . "," . replaceBlank($company_id) . "," . replaceBlank($vendor_name) . "," . replaceBlank($remarks) . "," . replaceBlank($_SESSION['2fa_image_url']) . "," . replaceBlank($bid_number) . "," . replaceBlank($bill_category) . "," . replaceDate($service_from_date) . "," . replaceDate($service_to_date) . "," . replaceDate($ses_date) . "," . replaceBlank($ses_no) . "," . replaceBlank($vendor_contact_person_name) . "," . replaceBlank($vendor_contact_person_contact_no) . "," . replaceBlank($hiranandani_contact_person_name) . "," . replaceBlank($hiranandani_contact_person_contact_no) . "," . replaceBlank($delivery_location) . ",";
        $query = $query . " " . replaceBlank($workorder_number) . "," . replaceDate($workorder_date) . "," . replaceBlank($activity_description) . ", " . replaceDate($workorder_release_date) . " , " . replaceBlank($workorder_status) . " ," . replaceBlank($bill_delivery_location) . " ," . replaceBlank($bill_delivery_date) . " ,'$harcopy_send_yn','$vendor_id','K', ";
        $query = $query . " '" . $_SESSION['user_id'] . "',now()," . replaceBlank($serial_no) . "," . replaceBlank($warranty_from_date) . "," . replaceBlank($warranty_to_date) . "," . replaceBlank($challan_number) . "," . replaceBlank($building_id) . "," . replaceBlank($building_name) . ")";
        array_push($array, $query);

        $query = " update gm_user_master set acknowledge_pending_count = ifnull(acknowledge_pending_count,0)+1 where user_id in (select department_bill_owner from mst_department where department_id =  '6')";
        array_push($array, $query);
        $out_message = "Bill details updated successfully.";
    }

//    echo "<pre>";
//    print_r($array);
//    exit;

    if (!$obj->execute_sqli_array($array, $error_message)) {

        $obj->save_log("BTS creation error: $error_message.", $_SERVER["REQUEST_URI"]);

        // $bill_id = $obj->get_execute_scalar('select max(bill_id) as bill_id from mst_bills', $error_message);
        //if($bill_id){
        //  save_bill_log(" Bill ".$task." by ".$_SESSION['user_name']." with ".$_SESSION['user_id'], $_SERVER["REQUEST_URI"],$bill_id);
        // }
        echo message_show($error_message, "error");
        return FALSE;
    } else {
        $obj->save_log($out_message, $_SERVER["REQUEST_URI"]);
        //  echo message_show("Bill entered successfully with BTS Number:" . $bts_number, "info");

        if ($bill_id == "") {
            $bill_id = $obj->get_execute_scalar("select max(bill_id) from mst_bills", $error_message);
        }
        $obj->save_bill_log(" Invoice  " . $log_text . " successfully by " . $_SESSION['user_name'], $bill_id);

        //  $bill_id = $obj->get_execute_scalar("select max(bill_id) from mst_bills", $error_message);
//   echo "sandeep".$_POST['txtBTSNumber'];exit;
        header("Location:master_bills_it.php?action=EDIT&pkeyid=$bill_id&msg=success&viewid=" . $_REQUEST['viewid'] . "&flagsave=1");
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

