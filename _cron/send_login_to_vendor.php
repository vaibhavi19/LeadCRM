<?php

include_once("../sys/generalx.php");
include '../sys/security.php';
$obj = new conn();
$error_message = "";

$query = "select email_id, user_name, mobile_no, status from gm_user_master ";
$query = $query . " where user_type = 'VENDOR'  ";
$query = $query . " and status = 'A' and ifnull(login_credentials_mailed_yn,'N') <> 'Y' limit 10 ";
$result = $obj->execute($query, $error_message);
$row_count = 0;
if ($result) {
    while ($row = mysqli_fetch_array($result)) {

        $email_id = $row['email_id'];
        $user_name = $row['user_name'];
        $mobile_no = $row['mobile_no'];
        $status = $row['status'];

        $new_password_text = rand(1000, 9999) . rand(1000, 9999);
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $new_password = hash('sha512', $new_password_text . $random_salt);
        $query = "update gm_user_master set password = '$new_password',login_credentials_mailed_yn='Y', salt = '$random_salt', last_password_change = now(), force_password_change = 'N' where login_id = '$mobile_no'";

        if ($obj->execute($query, $error_message)) {
            echo message_show("Password for $user_name has been mailed successfully to  <b>$email_id</b>.", "info");
            // SEND MAIL
            $sender_mail = $sender_name = $mail_to = $cc = $bcc = $subject = $body = $attachments = "";
            $sender_mail = "system.message@hiranandani.net";
            $sender_name = "Hiranandani";
            $mail_to = $email_id;
            $subject = "Hiranandani Vendor Desk - Login Credential";
            $body = "Dear " . $user_name;
            $body = $body . " <br/><br/> Greeting from Hiranandani!";
            $body = $body . " <br/><br/>Your login credentials for Hiranandani Vendor Desk Portal is as follows :";
            $body = $body . "<br/>Login :" . $mobile_no;
            $body = $body . "<br/>Password :" . $new_password_text;

            $body = $body . "<br/>Link : <a href='https://vendor.net4hgc.in' target='_blank'>https://vendor.net4hgc.in</a>";
            $body = $body . "<br/><br/><a style='background-color:#1383c4;color:white;border:2px solid #1a4d80;margin:0px 0 5px;padding:8px 20px;text-align:center;text-decoration:none;display:inline-block;font-size:18px;border-radius:5px' href='http://vendor.net4hgc.in/data/um/Vendor_Desk_User_Manual_For_Vendor.pdf' target='_blank'>Click Here To Download User Manual</a>";

            $body = $body . "<br/><br/>Regards,<br/><br/>Hiranandani.";

            $obj->create_mail($sender_mail, $sender_name, $mail_to, $cc, $bcc, $subject, $body, $attachments);
      
            // END OF MAIL

            $obj->save_log("Password has been mailed successfully for user $user_name. You will receive the new password in next 2-3 minutes through mail.", $_SERVER["REQUEST_URI"]);
            //    header("Location: master_vendor.php?action=EDIT&pkeyid=".$pkey_id."&viewid=" . $_GET["viewid"]."&msend=1");
        } else {
            echo message_show("Forgot Password failed for user <b>$user_name</b>: " . $error_message, "error");
            $obj->save_log("Forgot Password failed for user $user_name: " . $error_message, $_SERVER["REQUEST_URI"]);
        }
    }


    mysqli_free_result($result);
    unset($result);
}

unset($obj);
