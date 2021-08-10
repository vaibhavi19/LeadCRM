<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include '../sys/security.php';
include '../sys/generalx.php';

include_once '../sys/send_quick_mail.php';

$email = isset($_GET['email']) ? $_GET['email'] : "";
$email_otp = isset($_GET['num']) ? $_GET['num'] : "";

$mail_to = "";
if (validEmail($email) == true) {
    $mail_to .= $email;
}

//if (trim($email) == "" || (validEmail($email) == false)) {
//    return false;
//} else {
//    $mail_send = send_mail($mail_display_name, $message_from_mail, $message_to, $message_cc, $message_subject, $message_body);
//}


function validEmail($email){
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
	if (filter_var($emailB, FILTER_VALIDATE_EMAIL) === false ||$emailB != $email) {
		return false;
	} 

	$domain = substr($emailB, strpos($emailB, '@') + 1);
	if  (checkdnsrr($domain) == FALSE) {
		//echo "Domain $domain is invalid";
		return false;
	}
	return true;
}

if ($mail_to != "") {


    $mail_display_name = 'WINCRM';
    $message_from_mail = 'mail@wincrm.in';
    $message_to = $email;
    $message_cc = '';
    $message_subject = 'Wincrm password assistance';
    $message_body = 'To authenticate, please use the following One Time Password (OTP), <br><font size=4px;>OTP : <b>'.$email_otp.'</b></font>.<br>Dont share this OTP with anyone.<br>We hope to see you again soon.';

   // $output = send_mail($from_name, $from_mail, $mail_to, $mail_cc, "", $mail_subject, $mail_content);

     $output = send_mail($mail_display_name, $message_from_mail, $message_to, $message_cc, $message_subject, $message_body);

     if($output == 'success'){
          $otpstatus = "success";
     }else{
         $otpstatus = "failure";
     }
  //  echo $output;exit;
   // $jo = json_decode($output);
//echo "<pre>"; print_r($jo);exit;
//    if ($jo->{'status'} == "success") {
//        $otpstatus = "success";
//    } else {
//        echo "Mail failed : " . $jo->{'status'};
//    }
}

echo json_encode(array('status' => $otpstatus));
exit();
