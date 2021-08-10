<?php
ob_start();

include('header.php');

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();
ob_start();

$pkey_id = (isset($_REQUEST['pkeyid'])) ? $_REQUEST['pkeyid'] : '';
$pagetext = 'Add';
$user_name = $group = $header = $login_id = $email_id = $designation = $error_message = $status = "";
$mobile_no = $twoFactorAuth = $user_sign = $department = $user_type = $company_id = $user_image = "";
$ip_restriction = $time_restriction_from = $time_restriction_to = $extra_time_approval = $wrong_password_attempt = "";
$vendor_desc = "";
$view_id = $_GET['viewid'];

$status = "checked";
$user_type = "U";
//Array ( [txtUserName] => [txtusertype] => [txtuserempid] => [txtLoginID] => [txtPassword] => [txtusercpass] => [txtEmailID] => [txtDesignation] => [submit] => Submit ) 
$pkey_id = (isset($_REQUEST['pkeyid'])) ? $_REQUEST['pkeyid'] : '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//echo "<pre>"; print_r($_POST);exit;
    $user_name = $_POST['txtUserName'];
  //  $login_id = $_POST['txtLoginID'];
    $email_id = $_POST['txtEmailID'];
   // $designation = $_POST['txtDesignation'];
    $password = $_POST['txtPassword'];
    $mobile_no = $_POST['txtMobileNo'];
  //  $department = $_POST['cmbDepartment'];
    $user_type = 'U';
    // $vendor_desc = $_POST['txtVendorDesc'];
 //   $company_id = $_POST['cmbCompany'];
    $user_image = $_POST['txtUserImage'];
  //  $twoFactorAuth = $_POST['chktwofactorAuth'];
  //  $user_sign = $_POST['user_sign'];
    $status = (isset($_POST['chkStatus'])) ? $_POST['chkStatus'] : '';
    $status = ($status == "on") ? "A" : "D";
    $twoFactorAuth = ($twoFactorAuth == "on") ? "Y" : "N";
   // $ip_restriction = $_POST['txtAllowedIP'];
   // $time_restriction_from = $_POST['txtTimeFrom'];
  //  $time_restriction_to = $_POST['txtTimeTo'];
  //  $extra_time_approval = $_POST['cmbApprover'];
  //  $wrong_password_attempt = $_POST['cmbAttempt'];
  //  $group = $_POST['cmbGroup'];
} else {
    $obj->save_log("User master page opened.", $_SERVER["REQUEST_URI"]);
    if ($_REQUEST['action'] == 'EDIT') {
        $query = "select `user_id`,`user_name`,`password`,email_id,salt,"
                . "status, mobile_no,user_type, "
                . "client_id,user_image "
                . "from tbl_users where user_id = '$pkey_id'";

        $result = $obj->execute($query, $error_message);

        $row = mysqli_fetch_object($result);
        $user_name = $row->user_name;
        //$login_id = $row->login_id;
        $email_id = $row->email_id;
     //   $designation = $row->designation;
        $password = $row->password;
        $mobile_no = $row->mobile_no;
      //  $department = $row->department;
        $status = ($row->status == "A") ? "checked" : "";
        $user_type = $row->user_type;
       
       
        $user_image = $row->user_image;
       
    
        unset($row);
        mysqli_free_result($result);

        $header = 'Edit User - ' . $user_name;
    } else {
        $header = 'Create New User';
    }
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 3 | Advanced form elements</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">User master</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><?php echo $pagetext; ?> User</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-orange ">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo $pagetext; ?> User</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <?php
                            if (isset($_REQUEST['submit'])) {
                                $new_password = hash('sha512', $password);
                                // update the new password, create a random salt
                                $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
                                $new_password = hash('sha512', $new_password . $random_salt);

                                $ip_restriction = ""; //$_POST['txtAllowedIP'];
                                $time_restriction_from = ""; //$_POST['txtTimeFrom'];
                                $time_restriction_to = ""; //$_POST['txtTimeTo'];
                                $extra_time_approval = ""; //$_POST['cmbApprover'];
                                $wrong_password_attempt = ""; //$_POST['cmbAttempt'];
                                //  CODE FOR USER IMAGE UPLOAD
                                $max_filesize = 2097152; // Maximum filesize in BYTES (currently 2 MB).
                                $upload_path = '../data/uploads/'; // The place the files will be uploaded to (currently a 'files' directory).
                                $upload_path_sign = '../data/sign/';
                                global $filename, $fname;
                                // $fname = "";
                                $upload_success = 1;
                                $userlogoimg_flag = $signimg_flag = "";
                                // if any attachment specified, upload first
                                if (is_uploaded_file($_FILES['userfileLogo']['tmp_name'])) {
                                    // echo "useryes";
                                    $userlogoimg_flag = 1;
                                    $userlogoimg_flag = fileupload('userfileLogo', $upload_path);
                                } else {
                                    $userlogoimg_flag = 0;
                                    //   echo "userno";
                                }
//                                if (is_uploaded_file($_FILES['user_sign']['tmp_name'])) {
//                                    // echo "signyes";
//                                    $signimg_flag = 1;
//                                    $isSignUpload = fileupload('user_sign', $upload_path_sign);
//                                } else {
//                                    $signimg_flag = 0;
//                                    //echo "signno";
//                                }
                                //   exit;
                                //   'filename' => $fname,'success'
                                $user_image = $upload_path . $userlogoimg_flag['filename'];
                              //  $user_sign = $isSignUpload['filename'];
//            
//            echo "sandeep".$upload_success;
                                $sql_array = array();
                                if ($upload_success == 1) {
                                    if ($_REQUEST['action'] == 'NEW') {
                                        $query = "insert into `tbl_users`(`user_name`,`password`,email_id,salt,";
                                        $query = $query . " status,mobile_no,  user_type,  created_on,created_by,user_image,client_id,industry_id) ";
                                        $query = $query . " values('" . $user_name . "','" . $new_password . "','" . $email_id . "','" . $random_salt . "', ";
                                        $query = $query . "'" . $status . "','$mobile_no','$user_type',now(),".replaceBlank($_SESSION['user_id'])."," . replaceBlank($user_image) . ",".  replaceBlank($_SESSION['client_id']).",".  replaceBlank($_SESSION['industry_id']).")";
                                        array_push($sql_array, $query);

                                        // HARDCODE BILL CREATION  ROLE TO NEW USER
                                        //  $query = "insert into gm_user_roles values((select max(user_id) from gm_user_master) , 22,'{$_SESSION['user_id']}', now())";
                                        //  array_push($sql_array, $query);
                                    } elseif ($_REQUEST['action'] == 'EDIT') {
                                        $query = "update `tbl_users` set `user_name` = '$user_name',";
                                        $query = $query . "`email_id` = '$email_id',";

                                        if ($userlogoimg_flag == 1) {
                                            $query = $query . "user_image = " . replaceBlank($user_image) . ",";
                                        }
                           
                                        //  image_url = " . replaceBlank($user_image) . ",user_sign = " . replaceBlank($user_sign). ",";
                                        $query = $query . "mobile_no='$mobile_no',`status` = '$status',user_type = " . replaceBlank($user_type) . ",";
                                        $query = $query . "`edited_by` = ".replaceBlank($_SESSION['user_id']).",`edited_on` = now()";
                                        $query = $query . " where `user_id` = '$pkey_id'";
                                        array_push($sql_array, $query);
                                    }
//                    echo $query;
//                    exit;
                                    $obj->execute_sqli_array($sql_array, $error_message);
                                    //echo $error_message;
                                    if ($error_message == "") {
                                        $obj->save_log("User name $user_name saved .", $_SERVER["REQUEST_URI"]);
                                        //echo "Data updated successfully.";
                                        header("Location: listview.php?id=" . $view_id);
                                    } else {
                                        $obj->save_log("User name $user_name error: $error_message.", $_SERVER["REQUEST_URI"]);
                                        if (strpos($error_message, "login_id_UNIQUE") > 0) {
                                            // user friendly message for unique key
                                            echo message_show("The login ID <b>$login_id</b> is already exits, please choose a different ID.", "error");
                                        } else {
                                            echo message_show("ERROR: " . $error_message, "error");
                                        }
                                    }
                                }
                            } elseif (isset($_REQUEST['cancel'])) {
                                $obj->save_log("User master page closed.", $_SERVER["REQUEST_URI"]);
                                header("Location: listview.php?id=" . $view_id);
                                return;
                            }

                            function fileupload($txtfieldname, $upload_path) {
                                $max_filesize = 2097152; // Maximum filesize in BYTES (currently 2 MB).
                                // $upload_path = '../data/uploads/'; // The place the files will be uploaded to (currently a 'files' directory).
                                global $filename, $fname;
                                if (is_uploaded_file($_FILES[$txtfieldname]['tmp_name'])) {

                                    $filename = $_FILES[$txtfieldname]['name']; // Get the name of the file (including file extension).
                                    //$fname = $upload_path . $filename;
                                    $variable = $_SESSION['user_id'] . "_" . rand(1, 1000000);
                                    //$variable = random_salt(0);
                                    $fname = $_FILES[$txtfieldname]['name'];
                                    $fname = $variable . "." . pathinfo($filename, PATHINFO_EXTENSION);

                                    // Check the filesize, if it is too large then DIE and inform the user.
                                    if (filesize($_FILES[$txtfieldname]['tmp_name']) > $max_filesize)
                                        echo message_show("The file you attempted to upload is too large.", "error");

                                    // Check if we can upload to the specified path, if not DIE and inform the user.
                                    if (!is_writable($upload_path))
                                        echo message_show("You cannot upload to the specified directory, please CHMOD it to 777.", "error");

                                    // Upload the file to your specified path.
                                    if (move_uploaded_file($_FILES[$txtfieldname]['tmp_name'], $upload_path . $fname))
                                        $upload_success = 1;
                                    else
                                        echo message_show("There was an error during the file upload.  Please try again.", "error");
                                }
                                else {
                                    if ($fname != "") {
                                        switch ($_FILES[$txtfieldname]['error']) {

                                            case 0: //no error; possible file attack!
                                                echo message_show("There was a problem with your upload.", "error");
                                                break;
                                            case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                                                echo message_show("The file you are trying to upload is too big.", "error");
                                                break;
                                            case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                                                echo message_show("The file you are trying to upload is too big.", "error");
                                                break;
                                            case 3: //uploaded file was only partially uploaded
                                                echo message_show("The file you are trying upload was only partially uploaded.", "error");
                                                break;
                                            default: //a default error, just in case!  :)
                                                echo message_show("There was a problem with your upload.", "error");
                                                break;
                                        }
                                    }
//                else {
//                    $upload_success = 1;
//                }
                                }
                                if ($fname != "") {
                                    $user_image = $fname;
                                } else {
                                    $user_image = replaceBlank($upload_path . $fname);
                                }

                                return array('filename' => $fname, 'success' => $upload_success);
                            }
                            ?>
                            <form role="form" id="userForm" name="userForm" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputDepartmentName">User Name <font color="red">*</font></label>
                                                <input type="text" class="form-control" name="txtUserName" id="txtUserName" placeholder="Enter name" value="<?php echo $user_name; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputDepartmentName">Email Id <font color="red">*</font></label>
                                                <input type="text" class="form-control" name="txtEmailID" id="txtEmailID" placeholder="Enter email id" value="<?php echo $email_id; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputDescrition">Mobile Number <font color="red">*</font></label>
                                                <input type="text" class="form-control" id="txtMobileNo" name="txtMobileNo" placeholder="Enter Mobile Id" value="<?php echo $mobile_no; ?>">
                                            </div>
                                        </div>
                                    </div>



                                    <?php if ($_REQUEST['action'] == 'NEW') { ?>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputDepartmentName">Password <font color="red">*</font></label>
                                                    <input type="password" class="form-control" name="txtPassword" id="txtPassword" placeholder="Enter name" >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputDescrition">Confirm Password <font color="red">*</font></label>
                                                    <input type="password" class="form-control" id="txtusercpass" name="txtusercpass" placeholder="Enter LoginId" >
                                                </div>
                                            </div>
                                        </div>


                                    <?php } ?>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">User Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="userfileLogo" name="userfileLogo">
                                                        <input type="hidden" id="txtUserImage" name="txtUserImage" value="<?php echo $user_image; ?>"/>
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if ($user_image != "") { ?>
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Uploaded User Image</label>
                                                    <div class="input-group">
                                                        <?php
                                                        echo "<img  src='$user_image' style='height:50px;' ></img>";
                                                        //  echo "<a href='$vendor_logo' target=_blank>" . $vendor_logo . "</a>";                        
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="chkStatus" name="chkStatus" <?php echo $status; ?>>
                                        <label class="form-check-label" for="exampleCheck1">Status</label>
                                    </div>

                                </div>

                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                    <a href="../forms/listview.php?id=2" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

            </div>
        </section>
        <!-- /.row -->
    </div><!-- /.container-fluid -->


</div>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script type="text/javascript">
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
    });


    $(document).ready(function() {
        bsCustomFileInput.init();
    });

    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {
                alert("Form successful submitted!");
                return true;
            }
        });
        $('#userForm').validate({
            rules: {
                txtEmailID: {
                    required: true,
                    email: true
                },
                txtMobileNo:{
                    required: true
                },
                txtUserName: {
                    required: true
                },
//                txtLoginID: {
//                    required: true
//                },
                txtPassword: {
                    required: true
                },
                txtusercpass: {
                    equalTo: "#txtPassword"
                }

            },
            messages: {
                txtUserName: {
                    required: "Please enter a user name"
                },
                txtMobileNo:{
                      required: "Please enter a mobile number"
                },
//                txtLoginID: {
//                    required: "Please enter Login Id"
//                },
                txtPassword: {
                    required: "Please enter Password"
                },
                txtusercpass: {
                    required: "Please enter Confirm Password",
                    equal_to: "Password does not match"
                },
                txtEmailID: {
                    required: "Please enter description",
                    email: "Please enter valid Email Id"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

</body>
</html>


<?php unset($obj); ?>
<?php
include('footer.php');
?>