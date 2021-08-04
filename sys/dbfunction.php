<?php
include_once("../sys/generalx.php");
$obj = new conn();

function get_total_invoices($user_id) {
    global $obj;
    return $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where created_by = '$user_id'", $error_message);
    unset($obj);
}



function get_total_invoices_inprocess($user_id) {
    global $obj;
    return $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where status NOT in ('C','R','X') and created_by = '$user_id'", $error_message);
    unset($obj);
}

function get_total_invoices_paid($user_id) {
    global $obj;
    return $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where status in ('C') and created_by = '$user_id'", $error_message);
    unset($obj);
}

function get_total_invoices_rejected($user_id) {
    global $obj;
    return $obj->get_execute_scalar("select ifnull(count(*),0) as count from mst_bills where status in ('R','X') and created_by = '$user_id'", $error_message);
    unset($obj);
}

function get_total_my_bill_inbox($user_id) {
    global $obj;
        
    return $obj->get_execute_scalar("select count(*) as count from view_mst_bills a join trn_bills_transactions b on a.bill_id = b.bill_id where b.forwarded_to = '$user_id' and b.status = 'P' and a.status in ('F')", $error_message);
    unset($obj);
}

function get_total_my_parked_bills($user_id) {
     global $obj;
        
    return $obj->get_execute_scalar("select count(*) as count from view_mst_bills a join trn_bills_transactions b on a.bill_id = b.bill_id where b.forwarded_to = '$user_id' and b.status = 'P' and a.status in ('P')", $error_message);
    unset($obj);
}

function get_total_my_bill_outbox($user_id) {
    global $obj;

    return $obj->get_execute_scalar("select count(*) as count from view_mst_bills a join trn_bills_transactions b on a.bill_id = b.bill_id where b.created_by = '$user_id' and a.status <> 'U'", $error_message);
    unset($obj);
}

function get_total_my_bill_outbox_inprocess($user_id) {
    global $obj;

    return $obj->get_execute_scalar("select count(distinct a.bill_id) as count from view_mst_bills a join trn_bills_transactions b on a.bill_id = b.bill_id where b.created_by = '$user_id' and a.status  not IN ( 'E','C','R') ", $error_message);
    unset($obj);
}

function get_total_my_bill_outbox_closed($user_id) {
    global $obj;

    return $obj->get_execute_scalar("select count(distinct a.bill_id) as count from view_mst_bills a join trn_bills_transactions b on a.bill_id = b.bill_id where b.created_by = '$user_id' and a.status  IN ( 'E','C','R') ", $error_message);
    unset($obj);
}

