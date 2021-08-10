<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
/*
 * The login processing page
 */
//include '../sys/config.php';
include '../sys/security.php';
include '../sys/generalx.php';
sec_session_start(); // Our custom secure way of starting a php session.
//echo "<pre>"; print_r($_POST);
$obj = new conn();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    echo "<pre>";
//    print_r($_POST);
//    exit;
    $mobile = $_POST['txtEncMobile'];
    $obj = new conn();
    $mobile_exist = $obj->get_execute_scalar('select mobile_no from tbl_users where mobile_no=' . $mobile, $error_message);

    if ($mobile_exist == "") {
        $industry_id = $_POST['industry_name'];
        $name = $_POST['txtName'];
     
//        $shop_arr = explode("_", $shop_details);
//
        $sql_array = array();
//        $shop_id = $shop_name = "";
//        if (!empty($shop_arr)) {
//            $shop_id = $shop_arr[0];
//            $shop_name = $shop_arr[1];
//        }
        $password = $_POST['password'];
        $confirm_password = $_POST['conpassword'];
        $email = $_POST['txtEncEmail'];

        if ($password == $confirm_password) {
            $new_password = hash('sha512', $password);
            // update the new password, create a random salt
            $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $new_password = hash('sha512', $new_password . $random_salt);
            $user_type = 'U';
            
                 $query = "insert into `tbl_users`(`user_name`,`user_type`,`password`,email_id,salt,mobile_no,industry_id, created_on, created_by,status)";
            $query = $query . " values(" . replaceBlank($name) . "," . replaceBlank($user_type) . ",'" . $new_password . "','" . $email . "','" . $random_salt . "','".$mobile . "','" . $industry_id . "',now(),1,'A') ";
            array_push($sql_array, $query);


      

            $query = "insert into gm_user_roles values((select max(user_id) from tbl_users) , 1,'1', now())";
            array_push($sql_array, $query);

           // echo "<pre>"; print_r($sql_array);exit;
            
         $query = "insert into mst_clients (user_id,client_first_name,client_email,client_mobile,created_on,created_by)  values((select max(user_id) from tbl_users) ,".  replaceBlank($name).",".  replaceBlank($email).",".  replaceBlank($mobile).",'1', now())";
            array_push($sql_array, $query);
            
         $query = "update  tbl_users set client_id=(select max(client_id) from mst_clients) where user_id=(select max(user_id) from tbl_users)";
            array_push($sql_array, $query);

            
            $obj->execute_sqli_array($sql_array, $error_message);
        //   echo $error_message;exit;
            if ($error_message == "") {
                echo message_show('You have register successfully. Please login now.', 'success');
                header("refresh:3;url=../index.php");
            } else {
                header("Location: ../register_new.php?error=1");
            }
        } else {
            header("Location: ../register_new.php?error=2");
        }
    } else {
        header("Location: ../register_new.php?error=3");
    }
}

?>
<!--<script>
   function generateotp() {

                var fourdigitsrandom = Math.floor(1000 + Math.random() * 9000);
                $("#otp_send").val(fourdigitsrandom);
//alert(fourdigitsrandom);


                //    var email = $("#txtEncEmail").val();
                var mobile = $("#txtEncMobile").val();
                if (mobile != "") {
                    $.ajax({
                        url: 'forms/sendOtp.php',
                        type: 'get',
                        dataType: 'json',
                        data: {'mobile': mobile, 'otp': fourdigitsrandom},
                        contentType: 'application/json',
                        success: function(data) {
                            if (data.status == 'success') {
                                alert("OTP send on your mobile number");
                            } else {
                                alert("Wrong mobile number entered or otp service is not working");
                            }
                            //  alert(data.status);
                            // $('#target').html(data.msg);
                        }
                        //   data: JSON.stringify(person)
                    });

                } else {
                    alert("Please fill the data");
                }

            }
            
</script>-->
