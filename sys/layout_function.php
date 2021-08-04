<?php

function get_document_list($bill_id) {
    $obj = new conn();
    $html = "";
    $html .= '<div class="col-md-6"><div class="card card-orange"><div class="card-header"><h3 class="card-title">Documents List</h3></div><div class="card-body">';

    $query = "SELECT tpd.`document_id`, tpd.`bill_id`,tpd.`description`, tpd.`document_path`, date_format(tpd.`created_on`,'%d-%b-%Y %h:%i %p') as created_on,  b.user_name , tpd.`created_on` as created_on_date
							FROM trn_bill_documents tpd   join gm_user_master b on tpd.created_by = b.user_id
						where tpd.`bill_id` = $bill_id and tpd.`status` = 'A'  and not document_path is null
						union
						select bd.`comment_id`, bd.`bill_id`, bd.`document_name`, bd.`document_path`, date_format(bd.`created_on` ,'%d-%b-%Y %h:%i %p') `created_on`  , b.user_name, bd.`created_on` as created_on_date
						from trn_bill_comments bd join gm_user_master b on bd.created_by = b.user_id where bd.`bill_id` = $bill_id  and ifnull(document_path,'') <> ''
						order by created_on_date ";

    $result = $obj->execute($query, $error_message);
    $row_count = 0;
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {

            $html.= "<h6>" . ($row_count + 1) . ". " . $row['description'] . "&nbsp;&nbsp;&nbsp;<a href='../data/uploads/" . $row['document_path'] . "' target='_blank'>View in Full Screen</a>&nbsp; | <i>Uploaded by " . $row['user_name'] . " on " . $row['created_on'] . "</i></h6>";
            $html.= "<div class='embed-responsive embed-responsive-16by9'>";
            $html.= "<iframe class='embed-responsive-item' src='../data/uploads/" . $row['document_path'] . "'></iframe>";
            $html.= "</div><br>";
            $row_count++;
        }
        mysqli_free_result($result);
        unset($result);
    }
    if ($row_count == 0) {
        $html.= '<b>No uploaded document found!</b>';
    }

    $html .= '</div></div></div>';

    return $html;
}

function get_document_list_for_vendor($bill_id) {
    $obj = new conn();
    $html = "";
    $html .= '<div class="col-md-6"><div class="card card-orange"><div class="card-header"><h3 class="card-title">Documents List</h3></div><div class="card-body">';

    $query = "SELECT tpd.`document_id`, tpd.`bill_id`,tpd.`description`, tpd.`document_path`, tpd.`created_on`
							FROM trn_bill_documents tpd 
						where tpd.`bill_id` = $bill_id and tpd.`status` = 'A'  and not document_path is null and tpd.created_by=" . $_SESSION['user_id'] . "
						order by created_on";
//echo $query;exit;
    $result = $obj->execute($query, $error_message);
    $row_count = 0;
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {

            $html.= "<h5>" . ($row_count + 1) . ". " . $row['description'] . "&nbsp;&nbsp;&nbsp;<a href='../data/uploads/" . $row['document_path'] . "' target='_blank'>View in Full Screen</a></h5>";
            $html.= "<div class='embed-responsive embed-responsive-16by9'>";
            $html.= "<iframe class='embed-responsive-item' src='../data/uploads/" . $row['document_path'] . "'></iframe>";
            $html.= "</div><br>";
            $row_count++;
        }
        mysqli_free_result($result);
        unset($result);
    }
    if ($row_count == 0) {
        $html.= '<b>No uploaded document found!</b>';
    }

    $html .= '</div></div></div>';

    return $html;
}

function get_bill_detail_table($bill_id) {
    $obj = new conn();
    $html = '';
    $html .= '<div class="card card-blue"><div class="card-header"><h3 class="card-title">Invoice Details</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div><div class="card-body no-padding">';


    $query = " select a.asset_no,a.bill_id,a.bill_type,a.building_name,a.building_id,concat(left(bts_number,4),'-',right(bts_number,4)) as bts_number,department_id,department_name,bill_number,date_format(bill_date,'%d-%b-%Y') as bill_date,date_format(bill_due_date,'%d-%b-%Y') as bill_due_date, ";
    $query = $query . " amount,approved_amount,currency,advance_percentage, advance_amount,currency_desc,company_id,company_name,vendor_name,remarks,";
    $query = $query . " a.status,status_desc,a.created_by,  created_by_name,a.created_on, a.edited_by,";
    $query = $query . " a.`workorder_number`, date_format(a.`workorder_date`,'%d-%b-%Y') as workorder_date, a.`ses_no`, date_format(a.`ses_date`,'%d-%m-%Y') as ses_date, ";
    $query = $query . " edited_by_name,a.edited_on, cheque_no,cheque_date,cheque_amount,bank_name,payment_doc_no,invoice_booking_doc_no,cheque_collected_by,cheque_collected_on,payment_remarks, rejection_description, rejected_by_name , ";
    $query = $query . " date_format(workorder_release_date,'%d-%b-%Y') as  workorder_release_date,activity_description, workorder_status_desc, vendor_image_url, po_number, bid_number,bill_category, service_from_date, service_to_date, vendor_id,advance_request_no ,reference_advance_request_no, serial_no,date_format(warranty_from_date,'%d-%b-%Y') as warranty_from_date,date_format(warranty_to_date ,'%d-%b-%Y') as warranty_to_date ,case when rtgs_approval_needed_yn='Y' then 'Yes' else 'No' end as rtgs_approval_needed_yn,case when advance_bill_yn='Y' then 'Yes' else 'No' end as advance_bill_yn ,challan_number, ";
    $query = $query . " date_format(delivery_date,'%d-%m-%Y') as delivery_date, payment_term_days, payment_term_remarks,date_format(payment_due_date,'%d-%m-%Y') payment_due_date,vendor_contact_person_name, vendor_contact_person_contact_no, hiranandani_contact_person_name, hiranandani_contact_person_contact_no,date_format(rejected_on,'%d-%b-%Y') as  rejected_on , pending_with , date_format(pending_since,'%d-%b-%Y %h:%i %p') as pending_since, a.acknowledged_by_name,date_format(acknowledged_on,'%d-%b-%Y %h:%i %p') acknowledged_on ";
    $query = $query . " from view_mst_bills a where bill_id  = '$bill_id' ";
// $html .=  $query ;
    // echo $query;exit;
    $result = $obj->execute($query, $error_message);
    if (isset($result)) {

        $row = mysqli_fetch_object($result);

        $bts_no = $row->bts_number ;
        $company_name = $row->company_name;
        $bill_no = $row->bill_number;
        $department_name = $row->department_name;
        $bill_date = $row->bill_date;
        $vendor_name = $row->vendor_name;
        $bill_due_date = $row->bill_due_date;
        $vendor_id = $row->vendor_id;
        $amount = $row->amount;
        $remarks = $row->remarks;
        $approved_amount = $row->approved_amount;
        $status_desc = $row->status_desc;
        $advance_percentage =$row->advance_percentage;
        $created_by_name = $row->created_by_name;
        $advance_amount = $row->advance_amount;
        $created_on = $row->created_on;
        $workorder_number = $row->workorder_number;
        $rejected_by_name =$row->rejected_by_name;
        $workorder_date = $row->workorder_date;
        $rejection_description =$row->rejection_description;
        $ses_no =$row->ses_no;
        $activity_description =$row->activity_description;
        $ses_date = $row->ses_date;
        $bid_number = $row->bid_number;
        $workorder_release_date = $row->workorder_release_date;
        $bill_category = $row->bill_category;
        $workorder_status_desc = $row->workorder_status_desc;
        $service_from_date =$row->service_from_date;
        $service_to_date = $row->service_to_date;
        $vendor_image_url = $row->vendor_image_url;
        $advance_request_no =$row->advance_request_no;

        $serial_no = $row->serial_no;
        $warranty_from_date = $row->warranty_from_date;
        $warranty_to_date = $row->warranty_to_date;


        $rtgs_approval_needed_yn = $row->rtgs_approval_needed_yn;
        $advance_bill_yn = $row->advance_bill_yn;
        $challan_number = $row->challan_number;
        $building_name = $row->building_name;
        $rejected_on = $row->rejected_on;

        $delivery_date =$row->delivery_date;
        $payment_term_days = $row->payment_term_days;
        $payment_due_date = $row->payment_due_date;
        $bill_type = $row->bill_type;

        $vendor_contact_person_name = $row->vendor_contact_person_name;
        $vendor_contact_person_contact_no = $row->vendor_contact_person_contact_no;
        $hiranandani_contact_person_name = $row->hiranandani_contact_person_name;
        $hiranandani_contact_person_contact_no = $row->hiranandani_contact_person_contact_no;
        $pending_with = $row->pending_with;
        $pending_since =$row->pending_since;
        $acknowledged_by = $row->acknowledged_by_name;
        $acknowledged_on = $row->acknowledged_on;
        $reference_advance_request_no = $row->reference_advance_request_no;
 	$asset_no = $row->asset_no;
    }


    $html .= '<table class="table table-sm table-condensed table-responsive" style="overflow-x:auto;">';
    $html .= "<tr><td ><b>Bill Type :</b></td><td style='color:maroon;'><b>" . $bill_type . "</b></td><td><b>BTS No :</b></td><td>" . $bts_no . "<input type='hidden' name='txtbtsnumber' id='txtbtsnumber' value='.$bts_no.'></td></tr>";
    $html .= "<tr><td ><b>Company :</b></td><td colspan=3>" . $company_name . "</td></tr>";
    $html .= "<tr><td ><b>Vendor :</b></td><td colspan=3>" . $vendor_name . "</td></tr>";
    $html .= "<tr><td><b>Pending with :</b><td colspan=3>" . $pending_with . " <i>since</i> " . $pending_since . "</td></tr>";

    $html .= "<tr><td><b>  Bill No :</b></td><td>" . $bill_no . "</td><td><b>  Department :</b></td><td>" . $department_name . "</td></tr>";

    $html .= "<tr><td><b>Bill Date :</b></td><td>" . $bill_date . "</td><td><b>Bill Due Date:</b></td><td>" . $bill_due_date . "</td></tr>";
    $html .= "";
    $html .= "";
    $html .= "<tr><td><b>Status:</b></td><td>" . $status_desc . "</td><td><b>Payment Due Date: </b></td><td>" . $payment_due_date . "</td></tr>";
    $html .= "<tr><td><b>Delivery/work Completion Date:</b></td><td>" . $delivery_date . "</td><td><b>Payment Term Days: </b></td><td>" . $payment_term_days . "</td></tr>";
    $html .= "<tr><td><b> Invoice Amt :  </b></td><td>" . $amount . "</td><td><b>Payable Amt:</b></td><td>" . $approved_amount . "</td></tr>";
    $html .= "<tr><td ><b>Remarks :</b></td><td colspan=3>" . $remarks . "</td></tr>";
    $html .= "<tr><td ><b>Created By :</b></td><td colspan=3>" . $created_by_name . " <i>on</i> " . date('d-M-y h:i A', strtotime($created_on)) . "</td></tr>";



    $html .= "<tr><td><b>Rejected By :</b></td><td>" . $rejected_by_name . "</td><td><b>Rejected On</b></td><td>" . $rejected_on . "</td></tr>";
    $html .= "<tr><td ><b>Rejection Remarks:</b></td><td colspan=3><font color=red>" . $rejection_description . "</font></td></tr>";
    $html .= "<tr><td><b>SES/GRN No  :</b></td><td>" . $ses_no . "</td><td><b>SES/GRN Date:</b></td><td>" . $ses_date . "</td></tr>";
    $html .= "<tr><td ><b>Activity Desc:</b></td><td colspan=3>" . $activity_description . "</td></tr>";

    $html .= "<tr><td><b> Bill Category :</b></td><td>" . $bill_category . "</td><td><b>   Bid Number. :</b></td><td>" . $bid_number . "</td></tr>";
    $html .= "<tr><td><b>Service From Date :</b></td><td>" . $service_from_date . "</td><td><b>Service To Date:</b></td><td>" . $service_to_date . "</td></tr>";
    $html .= "<tr><td><b>WO/PO No  :  </b></td><td>" . $workorder_number . "</td><td><b>WO/PO Date : </b></td><td >" . $workorder_date . "</td></tr>";

    $html .= "<tr><td><b>WO Release Date  :</b></td><td>" . $workorder_release_date . "</td><td><b>WO Status   :</b></td><td>" . $workorder_status_desc . "</td></tr><tr><td><b>Advance Request No :</b></td><td>" . $advance_request_no . "</td><td><b>Reference Advance Request No :</b></td><td colspan=3>" . $reference_advance_request_no . "</td></tr>";
    $html .= "</td></tr><tr><td><b> Advance % : </b></td><td>" . $advance_percentage . "</td><td><b>Advance Amount:</b></td><td>" . $advance_amount . "</td></tr>";


    $html .= "<tr><td><b>Warranty From Date:</b><td>" . $warranty_from_date . "</td><td><b>Warranty To Date:</b></td><td>" . $warranty_to_date . "</td></tr>";

  
  $html .= "<tr><td><b>Serial No:</b><td colspan=3>" . $serial_no . "</td></tr>";
    $html .= "<tr><td><b>Challan No:</b><td colspan=3>" . $challan_number . "</td></tr>";

    $html .= "<tr><td><b>RTGS Approval Needed:</b><td>" . $rtgs_approval_needed_yn . "</td><td><b>Advance Bill:</b></td><td>" . $advance_bill_yn . "</td></tr>";
    $html .= "<tr><td><b>Building Name :</b><td colspan=3>" . $building_name . "</td></tr>";
    $html .= "<tr><td><b>Vendor Contact Person :</b><td colspan=3>" . $vendor_contact_person_name . " | " . $vendor_contact_person_contact_no . "</td></tr>";
    $html .= "<tr><td><b>Hiranandani Contact Person :</b><td colspan=3>" . $hiranandani_contact_person_name . " | " . $hiranandani_contact_person_contact_no . "</td></tr>";
 if($acknowledged_by!=""){
      $html .= "<tr><td ><b>Acknowledged By :</b></td><td colspan=3>" . $acknowledged_by . " <i>on</i> " . $acknowledged_on . "</td></tr>";
 }
    $html .= "<tr><td ><b>Asset No :</b></td><td colspan=3>" . $asset_no . "</td></tr>";




    if ($vendor_image_url != "" && isset($vendor_image_url)) {
        $html .= "<tr><td colspan=4><img style='height:50px;width:50px;' src='" . $vendor_image_url . "'></td></tr>";
    } else {
        $html .= "";
    }
    $html .="</tr>";

    $html .= "</table></div></div>";
   mysqli_free_result($result);
        unset($result);
    return $html;
}

function get_bill_detail_table_for_vendor($bill_id) {
    $obj = new conn();
    $html = '';
    $html .= '<div class="card card-blue"><div class="card-header"><h3 class="card-title">Invoice Details</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div><div class="card-body no-padding">';


    $query = " select a.bill_id,a.bill_type,a.building_name,a.building_id,concat(left(bts_number,4),'-',right(bts_number,4)) as bts_number,department_id,department_name,bill_number,date_format(bill_date,'%d-%b-%Y') as bill_date,date_format(bill_due_date,'%d-%b-%Y') as bill_due_date, ";
    $query = $query . " amount,approved_amount,currency,advance_percentage, advance_amount,currency_desc,company_id,company_name,pending_with,vendor_name,remarks,";
    $query = $query . " a.status,status_desc,a.created_by,  created_by_name,a.created_on, a.edited_by,";
    $query = $query . " a.`workorder_number`, a.acknowledged_by_name,date_format(a.`workorder_date`,'%d-%b-%Y') as workorder_date, a.`ses_no`, date_format(a.`ses_date`,'%d-%m-%Y') as ses_date, ";
    $query = $query . " edited_by_name,a.edited_on, cheque_no,cheque_date,cheque_amount,bank_name,payment_doc_no,invoice_booking_doc_no,cheque_collected_by,cheque_collected_on,payment_remarks, rejection_description, rejected_by_name , ";
    $query = $query . " date_format(workorder_release_date,'%d-%b-%Y') as  workorder_release_date,activity_description, workorder_status_desc, vendor_image_url, po_number, bid_number,bill_category,  service_from_date,  service_to_date, vendor_id,challan_number ,date_format(acknowledged_on,'%d-%b-%Y %h:%i %p') acknowledged_on";
    $query = $query . " from view_mst_bills a where bill_id  = '$bill_id' ";

    $result = $obj->execute($query, $error_message);
    if (isset($result)) {

        $row = mysqli_fetch_object($result);

        $bts_no = isset($row->bts_number) ? $row->bts_number : "";
        $bill_type = isset($row->bill_type) ? $row->bill_type : "";
        $company_name = isset($row->company_name) ? $row->company_name : "";
        $bill_no = isset($row->bill_number) ? $row->bill_number : "";
        $department_name = isset($row->department_name) ? $row->department_name : "";
        $bill_date = isset($row->bill_date) ? $row->bill_date : "";
        $vendor_name = isset($row->vendor_name) ? $row->vendor_name : "";
        $bill_due_date = isset($row->bill_due_date) ? $row->bill_due_date : "";
        $vendor_id = isset($row->vendor_id) ? $row->vendor_id : "";
        $amount = isset($row->amount) ? $row->amount : "";
        $remarks = isset($row->remarks) ? $row->remarks : "";
        $approved_amount = isset($row->approved_amount) ? $row->approved_amount : "";
        $status_desc = isset($row->status_desc) ? $row->status_desc : "";
        $advance_percentage = isset($row->advance_percentage) ? $row->advance_percentage : "";
        $created_by_name = isset($row->created_by_name) ? $row->created_by_name : "";
        $advance_amount = isset($row->advance_amount) ? $row->advance_amount : "";
        $created_on = isset($row->created_on) ? $row->created_on : "";
        $workorder_number = isset($row->workorder_number) ? $row->workorder_number : "";
        $rejected_by_name = isset($row->rejected_by_name) ? $row->rejected_by_name : "";
        $workorder_date = isset($row->workorder_date) ? $row->workorder_date : "";
        $rejection_description = isset($row->rejection_description) ? $row->rejection_description : "";
        $ses_no = isset($row->ses_no) ? $row->ses_no : "";
        $activity_description = isset($row->activity_description) ? $row->activity_description : "";
        $ses_date = isset($row->ses_date) ? $row->ses_date : "";
        $bid_number = isset($row->bid_number) ? $row->bid_number : "";
        $workorder_release_date = isset($row->workorder_release_date) ? $row->workorder_release_date : "";
        $bill_category = isset($row->bill_category) ? $row->bill_category : "";
        $workorder_status_desc = isset($row->workorder_status_desc) ? $row->workorder_status_desc : "";
        $service_from_date = isset($row->service_from_date) ? $row->service_from_date : "";
        $service_to_date = isset($row->service_to_date) ? $row->service_to_date : "";
        $challan_number = isset($row->challan_number) ? $row->challan_number : "";
        $building_name = isset($row->building_name) ? $row->building_name : "";
$acknowledged_by_name =  isset($row->acknowledged_by_name) ? $row->acknowledged_by_name : "";

$acknowledged_on = isset($row->acknowledged_on) ? $row->acknowledged_on : "";
        if ($row->status == "V" || $row->status == "K") {
            $pending_with = "";
        } elseif ($row->status == "F") {
            $pending_with = isset($row->pending_with) ? $row->pending_with : "";
        } elseif ($row->status == "I" || $row->status == "E" || $row->status == "C" || $row->status == "Q" || $row->status == "T" || $row->status == "P") {
            $pending_with = "Accounts";
        } else {
            $pending_with = "";
        }
    }


    $html .= '<table class="table table-sm table-condensed table-responsive" style="overflow-x:auto;">';

    $html .= "<tr><td ><b>Bill Type :</b></td><td  colspan=3 style=\"color:maroon;\">$bill_type<td></tr>";
    $html .= "<tr><td><b>Company :</b></td><td  colspan=3>$company_name<td></tr>";
    $html .= "<tr><td><b>Department :</b></td><td  colspan=3>$department_name<td></tr>";
    $html .= "<tr><td><b>  BTS No :</b></td><td>" . $bts_no . "<input type='hidden' name='txtbtsnumber' id='txtbtsnumber' value='.$bts_no.'>";
    $html .= "</td><td><b>  Bill No :</b></td><td>" . $bill_no . "";
    $html .= "</td></tr>";
    $html .= "<tr><td><b>Bill Date :</b></td><td>" . $bill_date;
    $html .= "<td><b>Due Date:</b></td><td>" . $bill_due_date . "</td>";
    $html .= "</tr><tr><td><b>Vendor:</b></td><td colspan=3>" . $vendor_name . "</td></tr>";
    $html .= "<tr><td><b> Invoice Amt :  </b></td><td>" . $amount . "</td><td><b> Payable Amt : </b></td><td class='dataarea'>" . $approved_amount . "</td></tr>";
    $html . " <tr><td><b>  Remarks : </b></td><td colsapn=3>" . $remarks . "</td></tr>";
    $html .= "<tr><td><b>Status :  </b></td><td colspan=3>" . $status_desc . "</td></tr>";
//<td><b>Pending with :  </b></td><td>" . $pending_with . "</td>
    $html .= "<tr><td><b>Advance % : </b></td><td>" . $advance_percentage . "</td><td><b>Advance Amt :  </b></td><td class='dataarea'>" . $advance_amount . "</td></tr>";
    $html .= " <tr><td><b>Created By : </b></td><td colspan=3>" . $created_by_name . " <i>on</i> " . date('d-M-y h:i A', strtotime($created_on)) . "</td></tr>";
     $html .= " <tr><td><b>Acknowledge By : </b></td><td colspan=3>" . $acknowledged_by_name . " <i>on</i> " . date('d-M-y h:i A', strtotime($acknowledged_on)) . "</td></tr>";
    
    
    $html .= "<tr><td><b>PO/Work Order#.  </b></td><td>" . $workorder_number . "</td><td><b>PO/Work Order Date : </b></td><td>" . $workorder_date . "</td></tr>";
    $html .= "<tr><td><b>WO Release Date  :</b></td><td>" . $workorder_release_date . "</td><td><b>WO Status   :</b></td><td>" . $workorder_status_desc . "</td></tr>";
    $html .="<tr><td><b>SES/GRN No  :</b></td><td>" . $ses_no . "</td><td><b>SES/GRN Date  :</b></td><td>" . $ses_date . "</td></tr>";
    $html .= "<tr><td><b>Rejected By :</b></td><td >" . $rejected_by_name . "</td><td><b>   Bid Number. :</b></td><td>" . $bid_number . "</td></tr>";
    $html .="<tr><td><b> Rejection Remarks  :  </b></td><td colspan=3>" . $rejection_description . "</td></tr>";
    $html .= "<tr><td><b>Activity Desc.  :  </b></td><td colspan=3>" . $activity_description . "</td></tr>";
    $html .= "<tr><td><b> Bill Category  :</b></td><td>" . $bill_category . "</td><td><b>Challan Number  :</b></td><td>" . $challan_number . "</td></tr>";
    $html .= "<tr><td><b>Building Name  :</b></td><td>" . $building_name . "</td><td></td><td></td></tr>";
    $html .= "<tr><tr><td><b>Service From Date :</b></td><td>" . $service_from_date . "</td><td><b>Service To Date   :</b></td><td>" . $service_to_date . "</td></tr></table></div></div>";
     mysqli_free_result($result);
                                        unset($result);
    return $html;
  
}

function get_approver_list($bill_id) {
    $obj = new conn();
    $html = "";
    $html .= '<div class="card card-yellow"><div class="card-header"><h3 class="card-title">Approver List</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div>';
    $html .= '<div class="card-body">';

    $result = $pending_with = "";
    $query = "select a.status,a.forward_action,ifnull(a.show_vendor_remark,'N')  as `show_vendor_remark`,a.forward_comment,abc.status as 'bill_status', a.forwarded_on, a.transaction_id, a.bill_id,case when ifnull(c.user_sign, '')= '' then '' else  concat('../data/sign/', ifnull(c.user_sign, '')) end as user_sign, b.user_name as 'forwarded_by',c.user_name forwarded_to,   a.delayed_days,
										a.user_comment as comment, a.forwarded_to as forwarded_to_id, date_format(forwarded_on, '%d-%m-%Y %h:%i %p') as curtimestamp, ifnull(user2fa_image_url, '../data/sign/verified.png') as user2fa_image_url ";
    $query = $query . " from trn_bills_transactions  a";
    $query = $query . " join gm_user_master b on a.created_by = b.user_id";
    $query = $query . " join gm_user_master c on a.forwarded_to = c.user_id";
    $query = $query . " join mst_bills abc on a.bill_id = abc.bill_id";
    $query = $query . " where a.bill_id = '$bill_id'";
    $query = $query . " order by forwarded_on ";
//echo $query;
    //  $html .=  $query;exit;                                                         
    $result = $obj->execute($query, $error_message);
    $row_count = 1;

    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $comments = $show_vendor_image = "";
            $forward_to_test = $user_sign = "";
            if ($row['comment'] != "") {
                $comments = $row['comment'];
            }
            if ($comments != "") {
                $comments = "<b>Comment:</b>&ldquo;<i><font color=#00008B>" . $comments . "</font></i>&rdquo;";
            } else {
                $comments = "";
            }
            $pending_with = "";
            $name = $row['forwarded_to'];
            $date = date('d-M-y h:i A', strtotime($row['forwarded_on']));
            if ($row['status'] != "F") {

                if ($row['status'] == "R") {
                    $pending_image = "../data/sign/reject.jpg";
                    $user_image = "<img id='imgApproved' name='imgApproved' style='height: 50px;width: 50px;' class='img-circle zoom' src='" . $pending_image . "' alt='User Image'>";

                    $user_sign = "";
                    $ver_approver = "";
                } else {
                    $pending_image = "../data/sign/verified_pending.png";
                    $user_image = "<img id='imgApproved' name='imgApproved' style='height: 50px;width: 50px;' class='img-circle zoom' src='" . $pending_image . "' alt='User Image'>";

                    $user_sign = "";
                    $ver_approver = "";
                }
            } else {
  if($row['show_vendor_remark'] == 'Y'){
                   $show_vendor_image  = "&nbsp;<img class='imageclass' title='This comment is visible to vendor.' src='../img/tick2.png'>";
               }else{
                   $show_vendor_image = "";
               }

                if ($row['forward_action'] == 'R') {
                    $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'>".$show_vendor_image."</b></font>";
                } elseif ($row['forward_action'] == 'A') {
                    $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'>".$show_vendor_image."</b></font>";
                } elseif ($row['forward_action'] == 'P') {
                    $ver_approver = "<font color=blue><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'>".$show_vendor_image."</b></font>";
                } elseif ($row['forward_action'] == 'F') {
                    $ver_approver = "<font color=maroon><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'>".$show_vendor_image."</b></font>";
                } else {
                    $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;".$show_vendor_image."</b></font>";
                }


                //  $ver_approver = "<img  style='height: 50px;width: 50px;' class='img-circle  zoom' src='http://vendor.net4hgc.in/img/veandapp.jpg' alt='Sign Image'>";
//                if ($row['forward_action'] != 'P') {
////               if($row['forward_action'] == 'R'){
////                   $ver_approver = "<font color=red><b>Rejected and Forwarded&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
////                   }else{
////                        $ver_approver = "<font color=green><b>Approved and Forwarded&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
////               
////                }
//
//                    if ($row['forward_action'] == 'R') {
//                        $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
//                    } elseif ($row['forward_action'] == 'A') {
//                        $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                    } elseif ($row['forward_action'] == 'P') {
//                        $ver_approver = "<font color=blue><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    } elseif ($row['forward_action'] == 'F') {
//                        $ver_approver = "<font color=maroon><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    } else {
//                        $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    }
//                } else {
//
//                    if ($row['bill_status'] != "P") {
////                    if($row['forward_action'] == 'R'){
////                        $ver_approver = "<font color=red><b>Rejected and Forwarded&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
////                    }else{
////                        $ver_approver = "<font color=green><b>Approved and Forwarded&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
////                    }
//                        if ($row['forward_action'] == 'R') {
//                            $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'A') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'P') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'F') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } else {
//                            $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                        }
//                    } else {
//                        $ver_approver = "";
//                    }
//                }


                if ($row['user2fa_image_url'] <> "") {
                    $user_image = "<img style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $row['user2fa_image_url'] . "' alt='Sign Image'>";
                } else {
                    $pending_image = "../data/sign/verified.png";
                    $user_image = "<img style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $pending_image . "' alt='Sign Image'>";
                }

                if ($row['user_sign'] <> "") {
                    $user_sign = "<img  style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $row['user_sign'] . "' alt='Sign Image'>";
                } else {
                    $user_sign = "";
                }
            }


            $html .='<div class="row">' . $user_image;
            $html .='<div class="col-md-1">' . $user_sign . '</div>';
//                  $html .='<div class="col-md-1">' . $ver_approver . '</div>';
            $html .='<div class="col-md-8"><span>' . $name . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $date . '</span>';
            $html .='<br><span class="col-md-2">' . $ver_approver . '</span>';
            $html .='<br><span class="col-md-2">' . $comments . '</span>';

            $html .='</div></div><hr>';
            $row_count++;
        }
    }
    mysqli_free_result($result);
    unset($result);
    $html .= '</div></div>';

    return $html;
}
function get_approver_list_for_vendor($bill_id) {
    $obj = new conn();
    $html = "";
    $html .= '<div class="card card-yellow"><div class="card-header"><h3 class="card-title">Hiranandani Comments</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div>';
    $html .= '<div class="card-body">';

    $result = $pending_with = "";
    $query = "select a.status,a.forward_action,a.forward_comment,abc.status as 'bill_status', a.forwarded_on, a.transaction_id, a.bill_id,case when ifnull(c.user_sign, '')= '' then '' else  concat('../data/sign/', ifnull(c.user_sign, '')) end as user_sign, b.user_name as 'forwarded_by',c.user_name forwarded_to,   a.delayed_days,
										a.user_comment as comment, a.forwarded_to as forwarded_to_id, date_format(forwarded_on, '%d-%m-%Y %h:%i %p') as curtimestamp, ifnull(user2fa_image_url, '../data/sign/verified.png') as user2fa_image_url ";
    $query = $query . " from trn_bills_transactions  a";
    $query = $query . " join gm_user_master b on a.created_by = b.user_id";
    $query = $query . " join gm_user_master c on a.forwarded_to = c.user_id";
    $query = $query . " join mst_bills abc on a.bill_id = abc.bill_id";
    $query = $query . " where a.bill_id = '$bill_id' and a.show_vendor_remark='Y'";
    $query = $query . " order by forwarded_on ";
//echo $query;
    //  $html .=  $query;exit;                                                         
    $result = $obj->execute($query, $error_message);
    $row_count = 1;

    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $comments = "";
            $forward_to_test = $user_sign = "";
            if ($row['comment'] != "") {
                $comments = $row['comment'];
            }
            if ($comments != "") {
                $comments = "<b>Comment:</b>&ldquo;<i><font color=#00008B>" . $comments . "</font></i>&rdquo;";
            } else {
                $comments = "";
            }
            $pending_with = "";
            $name = $row['forwarded_to'];
            $date = date('d-M-y h:i A', strtotime($row['forwarded_on']));
            if ($row['status'] != "F") {

                if ($row['status'] == "R") {
                    $pending_image = "../data/sign/reject.jpg";
                    $user_image = "<img id='imgApproved' name='imgApproved' style='height: 50px;width: 50px;' class='img-circle zoom' src='" . $pending_image . "' alt='User Image'>";

                    $user_sign = "";
                    $ver_approver = "";
                } else {
                    $pending_image = "../data/sign/verified_pending.png";
                    $user_image = "<img id='imgApproved' name='imgApproved' style='height: 50px;width: 50px;' class='img-circle zoom' src='" . $pending_image . "' alt='User Image'>";

                    $user_sign = "";
                    $ver_approver = "";
                }
            } else {


                if ($row['forward_action'] == 'R') {
                    $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
                } elseif ($row['forward_action'] == 'A') {
                    $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
                } elseif ($row['forward_action'] == 'P') {
                    $ver_approver = "<font color=blue><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
                } elseif ($row['forward_action'] == 'F') {
                    $ver_approver = "<font color=maroon><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
                } else {
                    $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
                }


                //  $ver_approver = "<img  style='height: 50px;width: 50px;' class='img-circle  zoom' src='http://vendor.net4hgc.in/img/veandapp.jpg' alt='Sign Image'>";
//                if ($row['forward_action'] != 'P') {
////               if($row['forward_action'] == 'R'){
////                   $ver_approver = "<font color=red><b>Rejected and Forwarded&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
////                   }else{
////                        $ver_approver = "<font color=green><b>Approved and Forwarded&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
////               
////                }
//
//                    if ($row['forward_action'] == 'R') {
//                        $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
//                    } elseif ($row['forward_action'] == 'A') {
//                        $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                    } elseif ($row['forward_action'] == 'P') {
//                        $ver_approver = "<font color=blue><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    } elseif ($row['forward_action'] == 'F') {
//                        $ver_approver = "<font color=maroon><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    } else {
//                        $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                    }
//                } else {
//
//                    if ($row['bill_status'] != "P") {
////                    if($row['forward_action'] == 'R'){
////                        $ver_approver = "<font color=red><b>Rejected and Forwarded&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
////                    }else{
////                        $ver_approver = "<font color=green><b>Approved and Forwarded&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
////                    }
//                        if ($row['forward_action'] == 'R') {
//                            $ver_approver = "<font color=red><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/cross.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'A') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'P') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } elseif ($row['forward_action'] == 'F') {
//                            $ver_approver = "<font color=green><b>" . $row['forward_comment'] . "&nbsp;&nbsp;<img src='../img/success.png'></b></font>";
//                        } else {
//                            $ver_approver = "<font color=gray><b>" . $row['forward_comment'] . "&nbsp;</b></font>";
//                        }
//                    } else {
//                        $ver_approver = "";
//                    }
//                }


                if ($row['user2fa_image_url'] <> "") {
                    $user_image = "<img style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $row['user2fa_image_url'] . "' alt='Sign Image'>";
                } else {
                    $pending_image = "../data/sign/verified.png";
                    $user_image = "<img style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $pending_image . "' alt='Sign Image'>";
                }

                if ($row['user_sign'] <> "") {
                    $user_sign = "<img  style='height: 50px;width: 50px;' class='img-circle  zoom' src='" . $row['user_sign'] . "' alt='Sign Image'>";
                } else {
                    $user_sign = "";
                }
            }


            $html .='<div class="row">' . $user_image;
            $html .='<div class="col-md-1">' . $user_sign . '</div>';
//                  $html .='<div class="col-md-1">' . $ver_approver . '</div>';
            $html .='<div class="col-md-8"><span>' . $name . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $date . '</span>';
            $html .='<br><span class="col-md-2">' . $ver_approver . '</span>';
            $html .='<br><span class="col-md-2">' . $comments . '</span>';

            $html .='</div></div><hr>';
            $row_count++;
        }
    }
    mysqli_free_result($result);
    unset($result);
    $html .= '</div></div>';

    return $html;
}
function get_timeline($bill_id) {

    global $obj;
    $error_message = "";
    $html = "";
    $html = "";
    $html .= '<div class="card card-yellow"><div class="card-header"><h3 class="card-title">Bill Timeline</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div>';
    $html .= '<div class="card-body">';
    $query = " select a.bill_id, a.log_description, a.created_by,b.user_name,  date_format(a.created_on,'%d-%b-%Y %h:%i %p') as 'created_on',  ";
    $query = $query . " date_format(a.created_on,'%d %b, %Y') as 'print_date',date_format(a.created_on,'%h:%i %p') as 'print_time'";
    $query = $query . " from trn_bills_log a join gm_user_master b on a.created_by = b.user_id ";
    $query = $query . " where a.bill_id = '$bill_id'  order by  a.created_on   ";
    $result = $obj->execute($query, $error_message);

    if ($result) {
        $html .= "<div class=\"timeline\">";
        $print_date = "";
        $color_array = array("bg-red", "bg-green", "bg-yellow");
        $html .= "<div><i class=\"fas fa-clock bg-gray\"></i></div><br>";
        while ($row = mysqli_fetch_array($result)) {

            if ($print_date != $row['print_date']) {
                $html .= "<div class=\"time-label\"><span class=\"" . $color_array[rand(0, 2)] . "\">" . $row['print_date'] . "</span></div>";
            }
            $html .= "<div>";
            $html .= "<i class=\"fas fa-user bg-green\"></i>";
            $html .= "<div class=\"timeline-item\">";
            $html .= "<span class=\"time\"><i class=\"fas fa-clock\"></i>&nbsp;" . $row['print_time'] . "</span>";
            $html .= "<h3 class=\"timeline-header\"><a href=''>" . $row['user_name'] . "</a></h3>";
            $html .= "<div class=\"timeline-body\">" . $row['log_description'] . "</div>";
            $html .= "</div>";
            $html .= "</div>";
            $print_date = $row['print_date'];
        }
        $html .= "<div><i class=\"fas fa-clock bg-gray\"></i></div>";
        $html .= "</div></div></div>";
    }
    return $html;
      mysqli_free_result($result);
        unset($result);
    unset($obj);
}

function get_bill_payment_table($bill_id) {
    $obj = new conn();
    $html = '';
    $html .= '<div class="card card-gray"><div class="card-header"><h3 class="card-title">Payment Details</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div><div class="card-body" style="background-color: antiquewhite;">';
    $query = " select a.bill_id,concat(left(bts_number,4),'-',right(bts_number,4)) as bts_number,department_id,department_name,bill_number,bill_date,date_format(bill_due_date,'%d-%b-%Y') as bill_due_date, ";
    $query = $query . " amount,approved_amount,currency,advance_percentage, advance_amount,currency_desc,company_id,company_name,vendor_name,remarks,";
    $query = $query . " a.status,status_desc,a.created_by,  created_by_name,a.created_on, a.edited_by,";
    $query = $query . " a.`workorder_number`, a.`workorder_date`, a.`ses_no`, date_format(a.`ses_date`,'%d-%m-%Y') as ses_date, ";
    $query = $query . " edited_by_name,a.edited_on, cheque_no,date_format(cheque_date,'%d-%b-%Y') as cheque_date,cheque_amount,bank_name,payment_doc_no,invoice_booking_doc_no,cheque_collected_by,date_format(cheque_collected_on,'%d-%b-%Y') cheque_collected_on,payment_remarks, rejection_description, rejected_by_name , ";
    $query = $query . " date_format(workorder_release_date,'%d-%b-%Y') as  workorder_release_date,activity_description, workorder_status_desc, vendor_image_url, po_number, bid_number,bill_category, service_from_date, service_to_date, vendor_id , date_format(rtgs_approved_date,'%d-%b-%Y') as rtgs_approved_date,cheque_collection_vendor_remarks";
    $query = $query . " from view_mst_bills a where bill_id  = '$bill_id' ";
    // echo $query ;
    $result = $obj->execute($query, $error_message);
    if (isset($result)) {
        $row = mysqli_fetch_object($result);
        $cheque_no = $row->cheque_no;
        $status = $row->status;
        $cheque_amount = $row->cheque_amount;
        $cheque_date = $row->cheque_date;
        $bank = $row->bank_name;
        $cheque_collection_vendor_remarks = $row->cheque_collection_vendor_remarks;
        $cheque_collected_by = $row->cheque_collected_by;
        $cheque_collected_on = $row->cheque_collected_on;
        $invoice_booking_doc_no = $row->invoice_booking_doc_no;
        $payment_doc_no = $row->payment_doc_no;
        $payment_remarks = $row->payment_remarks;
        $vendor_name = $row->vendor_name;
        $bill_number = $row->bill_number;

        if ($status == "C" || $status == "E" || $status == "Q") {
            $html .= '<table class="table table-bordered" style="overflow-x:auto;">';
            $html .= "<tr><td style='width:200px;'><b>  Cheque / RTGS  No :</b></td><td>" . $cheque_no . "</td></tr>";
            $html .= "<tr><td><b>  Cheque / RTGS Amount :</b></td><td>" . $cheque_amount . "</td></tr>";
            $html .= "<tr><td><b>  Cheque / RTGS Date :</b></td><td>" . $cheque_date . "</td></tr>";
            $html .= "<tr><td><b>  Bank :</b></td><td>" . $bank . "</td></tr>";
            $html .= "<tr><td><b> Payment Remarks :</b></td><td>" . $payment_remarks . "</td></tr>";
            $html .= "<tr><td><b> Cheque/ RTGS collection Remarks :</b></td><td>" . $cheque_collection_vendor_remarks . "</td></tr>";
            $html .= "<tr><td><b>  Payment Doc No. :</b></td><td>" . $payment_doc_no . "</td></tr>";
            $html .= "<tr><td><b> Invoice booking doc no. :</b></td><td>" . $invoice_booking_doc_no . "</td></tr>";
            $html .= "<tr><td><b>Cheque/RTGS Collected By :</b></td><td>" . $cheque_collected_by . "</td></tr>";
            $html .= "<tr><td><b> Cheque/RTGS Collected On:</b></td><td>" . $cheque_collected_on . "</td></tr>";
            $html .= "</table></div></div>";
        } else {
            $html = "";
        }
    }
      mysqli_free_result($result);
        unset($result);
    return $html;
}

function get_bill_payment_table_for_vendor($bill_id) {
    $obj = new conn();
    $html = '';
    $html .= '<div class="card card-gray"><div class="card-header"><h3 class="card-title">Payment Details</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div><div class="card-body" style="background-color: antiquewhite;">';
    $query = " select a.bill_id,concat(left(bts_number,4),'-',right(bts_number,4)) as bts_number,department_id,department_name,bill_number,bill_date,date_format(bill_due_date,'%d-%b-%Y') as bill_due_date, ";
    $query = $query . " amount,approved_amount,currency,advance_percentage, advance_amount,currency_desc,company_id,company_name,vendor_name,remarks,";
    $query = $query . " a.status,status_desc,a.created_by,  created_by_name,a.created_on, a.edited_by,";
    $query = $query . " a.`workorder_number`, a.`workorder_date`, a.`ses_no`, date_format(a.`ses_date`,'%d-%m-%Y') as ses_date, ";
    $query = $query . " edited_by_name,a.edited_on, cheque_no,date_format(cheque_date,'%d-%b-%Y') as cheque_date,cheque_amount,bank_name,payment_doc_no,invoice_booking_doc_no,cheque_collected_by,date_format(cheque_collected_on,'%d-%b-%Y') cheque_collected_on,payment_remarks, rejection_description, rejected_by_name , ";
    $query = $query . " date_format(workorder_release_date,'%d-%b-%Y') as  workorder_release_date,activity_description, workorder_status_desc, vendor_image_url, po_number, bid_number,bill_category, service_from_date, service_to_date, vendor_id , date_format(rtgs_approved_date,'%d-%b-%Y') as rtgs_approved_date,cheque_collection_vendor_remarks";
    $query = $query . " from view_mst_bills a where bill_id  = '$bill_id' ";
    // echo $query ;
    $result = $obj->execute($query, $error_message);
    if (isset($result)) {
        $row = mysqli_fetch_object($result);
        $cheque_no = $row->cheque_no;
        $status = $row->status;
        $cheque_amount = $row->cheque_amount;
        $cheque_date = $row->cheque_date;
        $bank = $row->bank_name;
        $payment_doc_no = $row->payment_doc_no;

        $invoice_booking_doc_no = $row->invoice_booking_doc_no;
        $cheque_collected_by = $row->cheque_collected_by;
        $cheque_collected_on = $row->cheque_collected_on;
        $payment_remarks = $row->payment_remarks;
        $cheque_collection_vendor_remarks = $row->cheque_collection_vendor_remarks;
        $vendor_name = $row->vendor_name;
        $bill_number = $row->bill_number;

        if ($status == "C" || $status == "E") {
            $html .= '<table class="table table-bordered" style="overflow-x:auto;">';
            $html .= "<tr><td style='width:200px;'><b>  Cheque No :</b></td><td>" . $cheque_no . "</td></tr>";
            $html .= "<tr><td><b>  Amount :</b></td><td>" . $cheque_amount . "</td></tr>";
            $html .= "<tr><td><b>  Cheque Date :</b></td><td>" . $cheque_date . "</td></tr>";
            $html .= "<tr><td><b>  Bank :</b></td><td>" . $bank . "</td></tr>";
            $html .= "<tr><td><b> Payment Remarks :</b></td><td>" . $payment_remarks . "</td></tr>";
            $html .= "<tr><td><b> Cheque collection Remarks :</b></td><td>" . $cheque_collection_vendor_remarks . "</td></tr>";

            $html .= "<tr><td><b>Cheque Collected By :</b></td><td>" . $cheque_collected_by . "</td></tr>";
            $html .= "<tr><td><b> Cheque Collected On:</b></td><td>" . $cheque_collected_on . "</td></tr>";
            $html .= "</table></div></div>";
        } else {
            $html = "";
        }
    }
      mysqli_free_result($result);
        unset($result);
    return $html;
}

function get_dashboard_count() {
     $obj = new conn();
    $html = "<section class='content'>";
   $total_bill_raised_today = $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where date(created_on) = date(now())", $error_message);
  
//        $query = "select ifnull(count(*),0) as count,sum(amount) as `amount` from mst_bills where date(created_on) = date(now())";
//        $result = $obj->execute($query, $error_message);
//        $row = mysqli_fetch_object($result);
//        $total_bill_raised_amount = $row->amount;
//        $total_bill_raised_today = $row->count; 
//        unset($row);
//        mysqli_free_result($result);
        
       $total_bill_rejected_today = $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where status = 'R' and date(rejected_on) = date(now())", $error_message);
     
        
   // $total_bill_count = $obj->get_execute_scalar("SELECT ifnull(count(*),0) as `count`,sum(amount) from mst_bills", $error_message);
    $query = "SELECT ifnull(count(*),0) as `count`,round(sum(amount)) as `amount` from mst_bills";
   
        $result = $obj->execute($query, $error_message);
        $row = mysqli_fetch_object($result);
        $total_bill_amount = $row->amount;
        $total_bill_count = $row->count; 
        unset($row);
        mysqli_free_result($result);
        
      //  $total_processing_bills = $obj->get_execute_scalar("SELECT ifnull(count(*),0) as `count` from mst_bills WHERE status not in ('R','C','E')", $error_message);
        $query = "SELECT ifnull(count(*),0) as `count`,round(sum(amount)) as `amount` from mst_bills WHERE status not in ('R','C','E')";
        $result = $obj->execute($query, $error_message);
        $row = mysqli_fetch_object($result);
        $total_bill_processing_amount = $row->amount;
        $total_processing_bills = $row->count; 
        unset($row);
        mysqli_free_result($result);
        
  //  $total_close_bills = $obj->get_execute_scalar("SELECT ifnull(count(*),0) as `count` from mst_bills WHERE status='C' || status='E' ", $error_message);
     $query ="SELECT ifnull(count(*),0) as `count`,round(sum(amount)) as `amount` from mst_bills WHERE status='C' || status='E' ";
        $result = $obj->execute($query, $error_message);
        $row = mysqli_fetch_object($result);
        $total_bill_close_amount = $row->amount;
        $total_close_bills = $row->count; 
        unset($row);
        mysqli_free_result($result);
        
   // $total_rejected_bills = $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where status = 'R'", $error_message);
      $query = "select ifnull(count(*),0) as count,round(sum(amount)) as `amount` from mst_bills where status = 'R'";
   
        $result = $obj->execute($query, $error_message);
        $row = mysqli_fetch_object($result);
        $total_bill_rejected_amount = $row->amount;
        $total_rejected_bills = $row->count; 
        unset($row);
        mysqli_free_result($result);
    
//$total_bill_processing_today = $obj->get_execute_scalar("SELECT count(DISTINCT bill_id) FROM `trn_bills_transactions` WHERE date(forwarded_on)='" . date('Y-m-d') . "'", $error_message);
  $total_bill_close_today = $obj->get_execute_scalar("SELECT count(*) from mst_bills WHERE status IN ('C','E') and date(bill_cheque_ready_on)='" . date('Y-m-d') . "'", $error_message);
$total_bill_processing_today = $obj->get_execute_scalar("  SELECT count(DISTINCT bill_id ) FROM `trn_bills_log` WHERE date(created_on) = '" . date('Y-m-d') . "'", $error_message);
  

    $html .= "<div class='container-fluid'><div class='row'><div class='col-md-12'><div class='row'>";
    $html .= "<div class='col-md-3 col-sm-6 col-12'><div class='info-box bg-info'><span class='info-box-icon'><i class='far fa-bookmark'></i></span><div class='info-box-content'>";
    $html .= "<span class='info-box-text'>Total Bills Received</span><span class='info-box-number'>" . $total_bill_count ." | &nbsp;<font color=maroon><i class='fas fa-rupee-sign'></i></font> ". number_format($total_bill_amount,0,".",",")."</span>";
    $html .= "<div class='progress'><div class='progress-bar' style='width: 100%'></div></div><span class='progress-description'><i>";
    $html .=  $total_bill_raised_today . " Bills received Today</i>";
    $html .= "</span></div></div></div>";
    $html .= "<div class='col-md-3 col-sm-6 col-12'>";
    $html .= "<div class='info-box bg-warning'>";
    $html .= "<span class='info-box-icon'><i class='far fa-thumbs-up'></i></span>";
    $html .= "<div class='info-box-content'>";
    $html .= "<span class='info-box-text'>Total Bills In-Process</span>";
    $html .= "<span class='info-box-number'>" . $total_processing_bills .  " | &nbsp;<font color=maroon><i class='fas fa-rupee-sign'></i></font> ".  number_format($total_bill_processing_amount,0,".",",") . "</span>";
    $html .= "<div class='progress'>";
    $html .= "<div class='progress-bar' style='width: 100%'></div>";
    $html .= "</div>";
    $html .= "<span class='progress-description'><i>";
    $html .= $total_bill_processing_today . " Bills Attended Today</i>";
    $html .= "</span>";
    $html .= "</div>";
    $html .= "</div></div>";
    $html .= "<div class='col-md-3 col-sm-6 col-12'><div class='info-box bg-success'><span class='info-box-icon'><i class='far fa-calendar-alt'></i></span>";
    $html .= "<div class='info-box-content'><span class='info-box-text'>Total Closed/Paid Bills</span>";
    $html .= "<span class='info-box-number'>" . $total_close_bills . " | &nbsp;<font color=maroon><i class='fas fa-rupee-sign'></i></font> ". number_format($total_bill_close_amount,0,".",",") ."</span>";
    $html .= "<div class='progress'><div class='progress-bar' style='width: 100%'></div></div>";
    $html .= "<span class='progress-description'><i>";
    $html .=  $total_bill_close_today . " Bills Close/Paid Today</i>";
    $html .= "</span></div></div></div>";
    $html .= "<div class='col-md-3 col-sm-6 col-12'><div class='info-box bg-danger'><span class='info-box-icon'><i class='fas fa-comments'></i></span>";
    $html .= "<div class='info-box-content'>";
    $html .= "<span class='info-box-text'>Total Rejected Bills</span>";
    $html .= "<span class='info-box-number'>" . $total_rejected_bills . " | &nbsp;<font color=maroon><i class='fas fa-rupee-sign'></i></font> ". number_format($total_bill_rejected_amount,0,".",",") . "</span>";
    $html .= "<div class='progress'><div class='progress-bar' style='width: 100%'></div></div><span class='progress-description'><i>";
    $html .=  $total_bill_rejected_today . " Bill Rejected Today</i>";
    $html .= "</span></div></div></div></div></div></div></div></section>";
    
    return $html;
}


function get_iom_data($bill_id) {
    $obj = new conn();
    $query = "select reference_advance_request_no,company_id from mst_bills where bill_id='" . $bill_id . "'";
    $result = $obj->execute($query, $error_message);
    while ($row1 = mysqli_fetch_array($result)) {
        $reference_advance_request_no = $row1['reference_advance_request_no'];
        $company_id = $row1['company_id'];
    }

    $query1 = "select amount,advance_request_no,bill_type,bill_id,date_format(`created_on`,'%d-%b-%Y') as created_on, date_format(`bill_date`,'%d-%b-%Y') as bill_date,bts_number,bill_number,status_desc,company_name from view_mst_bills where advance_request_no='" . $reference_advance_request_no . "' and company_id=" . $company_id . " and bill_type='IOM'";

    $result1 = $obj->execute($query1, $error_message);
    $row_count = 1;
    $html = "";

    if (mysqli_num_rows($result1) > 0) {
        $html = '<div class="card card-red"><div class="card-header"><h3 class="card-title">Alert ! IOM found against this invoice...<i title="You have updated Reference advance number w.r.t  which this IOM is listed"  id="iom_tab" class="fa fa-info-circle" aria-hidden="true"></i></h3><div class="card-tools">';
        $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div>';
        $html .= '<div class="card-body no-padding" style="background-color:#FFECEC"><table  class="table table-sm table-condensed table-responsive" style="overflow-x:auto;">';
        $html .= "<tr>";
        $html .= "<th>BTS No.</th><th>Advance Request No</th><th>Amount</th><th>Bill Date</th><th>Created On</th><th>Status</th><th>Company</th>";
        $html .= "</tr>";
        while ($row = mysqli_fetch_array($result1)) {

            $html .= '<tr><td class="flashit">';
            $html .= "<a target=_blank href='rpt_bill_track.php?bill_id='" . $row['bill_id'] . ">" . $row['bts_number'] . "</a></td>";
            $html .= '<td>' . $row['advance_request_no'] . '</td>';
            $html .= '<td>' . $row['amount'] . '</td>';
            $html .= '<td>' . $row['bill_date'] . '</td>';
            $html .= '<td>' . $row['created_on'] . '</td>';
            $html .= '<td>' . $row['status_desc'] . '</td>';
            $html .= '<td>' . $row['company_name'] . '</td>';
            $html .= '</tr>';
        }
        $html .= "</table><br/>";
        $html .= '</div>';
        $html .= '</div>';
    }

    return $html;
}

function get_bank_details_of_vendor($vendor_id) {
    $obj = new conn();
    $html = '';
    $html .= '<div class="card card-blue"><div class="card-header"><h3 class="card-title">Bank Account Details</h3><div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button></div></div><div class="card-body no-padding">';

    $query = "select * from mst_vendor where vendor_id=" . $vendor_id;

    $result = $obj->execute($query, $error_message);
    if (isset($result)) {

        $row = mysqli_fetch_object($result);
        $vendor_name = $row->vendor_name;
        $gst_number = $row->gst_number;
        $pan_number = $row->pan_number;
        $reference_vendor_code = $row->reference_vendor_code;
        $bank_account_name = $row->bank_account_name;
        $bank_account_number = $row->bank_account_number;
        $bank_name = $row->bank_name;
        $bank_branch_name = $row->bank_branch_name;
        $bank_ifsc_code = $row->bank_ifsc_code;
        $bank_micr_code = $row->bank_micr_code;
        $bank_account_type = $row->bank_account_type;

        $bank_branch_city = $row->bank_branch_city;
        $bank_branch_street = $row->bank_branch_street;
        $bank_swift_code = $row->bank_swift_code;
        $industry_type = $row->industry_type;
        if ($row->rtgs_yn == 'Y') {
            $rtgs = 'RTGS is uploaded on ' . date('d-M-Y', strtotime($row->rtgs_updated_on));
        } else {
            $rtgs = 'Bank details not yet uploaded.';
        }
        if ($row->sap_audited_yn == 'Y') {
            $sap_audit = '<img src="../img/success.png"> SAP audit approved  on ' . date('d-M-Y', strtotime($row->sap_audited_on)) . ' by ' . $row->sap_audited_by_name;
        } else {
            $sap_audit = '<img src="../img/cross.png"> SAP audit pending';
        }
        if ($row->audited_yn == 'Y') {
            $bts_audit = '<img src="../img/success.png"> BTS Audit approved on ' . date('d-M-Y', strtotime($row->audited_on)) . ' by ' . $row->audited_by_name;
        } else {
            $bts_audit = '<img src="../img/cross.png"> BTS audit pending';
        }
    }

    $html .= '<table class="table table-sm table-condensed table-responsive" style="overflow-x:auto;">';
    $html .= "<tr><td ><b>Vendor Name :</b></td><td colspan=3 >" . $vendor_name . "| " . $reference_vendor_code . "</td></tr>";
    $html .= "<tr><td ><b>Pan Number :</b></td><td>" . $pan_number . "</td> <td><b>GST Number:</b></td><td>" . $gst_number . "</td></tr>";
    $html .= "<tr><td ><b>RTGS :</b></td><td  colspan=3>" . $rtgs . "</td></tr>";
    $html .= "<tr><td ><b> BTS Audit:</b></td><td  colspan=3>" . $bts_audit . "</td></tr>";
    $html .= "<tr><td ><b>SAP Audit :</b></td><td  colspan=3>" . $sap_audit . "</td></tr>";
    $html .= "<tr><td ><b>Bank Account Number :</b></td><td colspan=3>" . $bank_account_number . "</td></tr>";
    $html .= "<tr><td><b> Bank Name :</b></td><td colspan=3>" . $bank_name . "</td></tr>";
    $html .= "<tr><td><b> Bank Account Name :</b></td><td>" . $bank_account_name . "</td><td><b>  Bank Branch Name :</b></td><td>" . $bank_branch_name . "</td></tr>";
    $html .= "<tr><td><b> Bank IFSC Code :</b></td><td>" . $bank_ifsc_code . "</td><td><b>  Bank MICR Code :</b></td><td>" . $bank_micr_code . "</td></tr>";
    $html .= "<tr><td><b> Bank Account Type :</b></td><td>" . $bank_account_type . "</td><td><b>  Bank Branch City :</b></td><td>" . $bank_branch_city . "</td></tr>";
    $html .= "<tr><td><b> Bank Branch Street :</b></td><td>" . $bank_branch_street . "</td><td><b>  Bank Swift Code :</b></td><td>" . $bank_swift_code . "</td></tr>";
    $html .= "<tr><td><b> Industry Type :</b></td><td colspan=3>" . $industry_type . "</td></tr>";
    $html .="</tr>";
    $html .= "</table></div></div>";
    mysqli_free_result($result);
    unset($result);
    return $html;
}



