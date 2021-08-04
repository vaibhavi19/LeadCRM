<?php

include_once("../sys/generalx.php");
include '../sys/security.php';
$obj = new conn();

$query = " select  vendor_id ,reference_vendor_code,pan_number,vendor_name ,email_id, mobile_number from mst_vendor where ifnull(send_rtgs_mail_yn,'N') = 'N'   ";
$query = $query . " and  email_id <> '' and reference_vendor_code <> ''  limit 50 ";

$result = $obj->execute($query, $error_message);
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $sender_mail = $sender_name = $mail_to = $cc = $bcc = $subject = $body = $attachments = "";

        $vendor_id = $row['vendor_id'];
        $mail_to = $row['email_id'];
        $vendor_name = $row['vendor_name'];
        $vendor_code = $row['reference_vendor_code'];
        $mobile1 = $row['mobile_number'];

        $subject = "Hiranandani - Bank Account Details Update";
        $body = file_get_contents("../templates/update_rtgs_details.html");
        $body = str_replace("{VENDOR_NAME}", $vendor_name, $body);
        $sender_mail = "system.message@hiranandani.net";
        $sender_name = "Hiranandani";

        $obj->create_mail($sender_mail, $sender_name, $mail_to, $cc, $bcc, $subject, $body, $attachments);
        $query = "update mst_vendor set send_rtgs_mail_yn = 'Y' where vendor_id = '$vendor_id'";
        $obj->execute($query, $error_message);

//// SEND SMS
//if(($mobile1<>"")&&(strlen($mobile1)==10)){
//$url_big = "http://dcp.net4hgc.in/frm/mst_rtgs_details.php?v=$vendor_code-$pan_number";
//$url_tiny = file_get_contents("http://vm1.in/create_tiny_url.php?url=" . $url_big . "&days=90");    
// 
//if($url_tiny <> ""){
//$message_subject = "Please update your bank account details with Hiranandani by clicking on the link. $url_tiny";    
//$query = "insert into sys_sms (sender_id,mobile_no, message_subject, message_status, created_by, created_on)values(";
//$query = $query . " 'HIRANA','$mobile1','$message_subject','A','1',now()) ";
//$obj->execute($query, $error_message);
//
//$query = "update mst_vendor set send_sms_yn = 'Y',send_sms_on=now() where vendor_id = '$vendor_id'";
//$obj->execute($query, $error_message);
// }
//    
//    
//}

        echo message_show(" Your email has been sent to $vendor_name at  $mail_to", "success");
    }
    unset($row);
    mysqli_free_result($result);
}
unset($obj);
?>
