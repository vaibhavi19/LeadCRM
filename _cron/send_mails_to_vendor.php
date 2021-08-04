<?php

include_once("../sys/generalx.php");
$obj = new conn();
$error_message = "";

$query = "select user_id, email_id from gm_user_master where user_type = 'VENDOR' AND email_id <> 'bhoir.sandeep@hiranandani.net' and status = 'A' ";


$result = $obj->execute($query, $error_message);
$row_count = 0;
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $sender_mail = "system.messages@hiranandani.net";
        $sender_name = "Hiranandani";
        $mail_to = $row['email_id']; //$row["email_id"];
        $created_by_id = $row['user_id'];
        $cc = "";
        $bcc = "";
        $subject = "Intimation mail for raising Hiranandani IT invoice";
        $attachments = "";

        $strbody = "<p>Dear Business Partner,</p>";
        $strbody = $strbody . "<p>If you have delivered any material or services to us in the previous month please make sure that Invoices are updated in BTS with supporting documents
on or before 5th of this month.You are not allowed to submit the Invoices beyond this particular date.</p>";
        $strbody = $strbody . "<p>Note- You can view your Bill processing & payment status online in the Vendor Portal.</p>";
        $strbody = $strbody . "<p>In case if your facing any difficulty you can contact Smita B on +91-9892806616 or bhaskaran.smita@hiranandani.net</p>";

        // echo $strbody;

        $obj->create_mail($sender_mail, $sender_name, $mail_to, $cc, $bcc, $subject, $strbody, $attachments);
        echo "<br>Mail sent successfully to $mail_to";


        $query = "insert into trn_vendor_alert (vendor_id, alert_type, alert_caption, alert_message, read_yn,  created_by, created_on) ";
        $query = $query . " values ( ";
        $query = $query . " '$created_by_id','danger','Intimation', " . replaceBlank($strbody) . ",'N', '1',now()) ";
        $obj->execute($query, $error_message);
        if ($error_message == "") {
            echo "<br>Alter Intimation sent successfully to $mail_to";
        } else {

            echo message_show("ERROR: " . $error_message, "error");
        }
    }


    mysqli_free_result($result);
    unset($result);
}

unset($obj);
