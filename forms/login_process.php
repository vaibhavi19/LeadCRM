<?php

/*
 * The login processing page
 */
//include '../sys/config.php';
include '../sys/security.php';
include '../sys/generalx.php';
   $obj = new conn();
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
   if (isset($_SESSION['client_id'])) {
            $query = "select * from mst_clients where client_id='" . $_SESSION['client_id'] . "'";

            $result = $obj->execute($query, $error_message);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_object($result);
                $email_verified_yn = $row->email_verified_yn;
                $mobile_verified_yn = $row->mobile_verified_yn;
                $agree_terms_yn = $row->agree_terms_yn;
                
                if($email_verified_yn == 'Y' && $mobile_verified_yn == 'Y' && $agree_terms_yn == 'Y'){
                      header('Location: dashboard.php');
                }else{
                      header('Location: client_info.php');
                }
                
                
            }
        }else{
              header('Location: ../index.php?error=1');
        }
          
        } else {
            header('Location: ../index.php?error=1');
        }
    
} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}
?>