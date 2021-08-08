<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../sys/security.php';
include '../sys/generalx.php';

include_once '../sys/send_quick_sms.php';

$mobile_no1 = isset($_GET['mobile'])?$_GET['mobile']:"";
$mobile_otp = isset($_GET['otp'])?$_GET['otp']:"";
$login_verify_mobile = isset($_GET['login_verify_mobile'])?$_GET['login_verify_mobile']:"";


//if(isset($login_verify_mobile) && $login_verify_mobile == 'YES'){
//    $obj = new conn();
//    $query = "SELECT mobile_no from gm_user_master where mobile_no=".$mobile_no1." and status = 'A' LIMIT 1";
//    $verify_mobile = $obj->get_execute_scalar($query, $error_message);
//    
//    if(isset($verify_mobile) && $verify_mobile != ""){
//       // continue 
//    }else{
//        echo json_encode(array('status' => 'Not Register mobile number'));
//exit();
//    }
//    //unset($obj);
//}



if(isset($mobile_no1) && $mobile_no1 != "" && isset($mobile_otp) && $mobile_otp != ""){
  $otpstatus = "failed";
if (validMobile($mobile_no1) == true) {
	//$retval = send_sms($mobile_no1, "$mobile_otp is your OTP for 2-Factor authentication.");
	
        $otpstatus = "success";
//        if (strpos($retval, $mobile_no1) > 0) {
//		$otpstatus = "success";
//	} else {
//		echo "SMS 1 failed due to : <b>$retval</b>";
//	}
}  
}
//echo "<pre>"; print_r($_GET);exit;


echo json_encode(array('status' => $otpstatus));
exit();

function validMobile($mobile){
	if (preg_match('/(6|7|8|9)\d{9}/', $mobile)) {
		return true;
	}
	return false;
}