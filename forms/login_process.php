<?php

/*
 * The login processing page
 */
//include '../sys/config.php';
include '../sys/security.php';
include '../sys/generalx.php';
sec_session_start(); // Our custom secure way of starting a php session.
//echo "<pre>"; print_r($_POST);exit;
if (isset($_POST['txtEncMobile'], $_POST['password'])) {
    $mobile = $_POST['txtEncMobile'];
    $password = $_POST['password']; // The hashed password.
        $retval = login($mobile, $password);
        //$obj = new conn();
        if ($retval == true) {
            //   print_r($_POST);
            if (isset($_POST['chkRemember'])) {
                setcookie("vendor_desk_user_login", $login_id, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("vendor_desk_user_pwd", $_POST['txtEncPassword'], time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("vendor_desk_user_remember", "checked", time() + (86400 * 30), "/"); // 86400 = 1 day
            } else {

                setcookie("vendor_desk_user_login", "", time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("vendor_desk_user_pwd", "", time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("vendor_desk_user_remember", "", time() + (86400 * 30), "/"); // 86400 = 1 day
            }

            header('Location: dashboard.php');
        } else {
            header('Location: ../index.php?error=1');
        }
    
} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}
?>