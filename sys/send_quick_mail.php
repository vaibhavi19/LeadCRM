<?php

//error_reporting(E_ALL);
//error_reporting(E_STRICT);
//date_default_timezone_set('America/Toronto');
//include("config/config.php");

function send_mail($mail_display_name, $message_from_mail, $message_to, $message_cc, $message_subject, $message_body) {
    // continue mails are there...
    require_once('../class/class.phpmailer.php');

    $mail = new PHPMailer();

    $mail->IsSMTP(); // telling the class to use SMTP
    //$mail->SMTPDebug = 2;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
//    $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
//    $mail->SMTPAuth = true;                  // enable SMTP authentication
//    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
//    $mail->Port = 465;                   // set the SMTP port for the GMAIL server
//    //$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
//    //$mail->Port = 587;                   // set the SMTP port for the GMAIL server

    // trying thrigy mandrill
    $mail->Host = "smtp.mandrillapp.com";      // sets GMAIL as the SMTP server
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    //$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Port = 587;                   // set the SMTP port for the GMAIL server

    $mail->Username = "nair.saji@hiranandani.net";  // GMAIL username
    $mail->Password = "MxHYflcT5NVIiWdOVXKz8g";            // GMAIL password
    //$mail->SetFrom($row['message_from_mail'], $row['message_from_name']);
    $mail->SetFrom($message_from_mail, $mail_display_name);

    $mail->AddReplyTo($message_from_mail, $mail_display_name);

    $mail->Subject = $message_subject;

    $message_body = eregi_replace("[\]", '', $message_body);

    //$mail->AltBody    = ""; // optional, comment out and test

    $mail->MsgHTML($message_body);

    // $mail->AddAddress($row['message_to']);
    // HANDLE multiple To's, seperated by ,
    $string = $message_to;
    $string = str_replace(';', ',', $string); // convert ; to ,
    $string = str_replace(' ', '', $string); // remove blank spaces

    $array = explode(',', $string); //split string into array seperated by ,
    foreach ($array as $value) { //loop over values
        $value = str_replace(';', '', $value); //Remove dot at end if exists
        $value = str_replace(',', '', $value); //Remove dot at end if exists

        if ($value != "") {
            //echo "-" . $value . "-<br />"; //print value
            $mail->AddAddress($value);
        }
    }

    // $mail->AddCC($row['message_cc']);
    // HANDLE multiple CC's, seperated by ,
    $string = $message_cc;
    $string = str_replace(';', ',', $string); // convert ; to ,
    $string = str_replace(' ', '', $string); // remove blank spaces

    $array = explode(',', $string); //split string into array seperated by ,
    foreach ($array as $value) { //loop over values
        $value = str_replace(';', '', $value); //Remove dot at end if exists
        $value = str_replace(',', '', $value); //Remove dot at end if exists

        if ($value != "") {
            //echo "-" . $value . "-<br />"; //print value
            $mail->AddCC($value);
        }
    }

    $mail->AddBCC("nair.saji@hiranandani.net");
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

    $m_outmsg = "Message sent!";
    if (!$mail->Send()) {
        $m_outmsg = "Mailer Error: " . $mail->ErrorInfo;
    }
    return $m_outmsg;
}

?>