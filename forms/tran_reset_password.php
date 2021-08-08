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
$view_id = (isset($_REQUEST['viewid'])) ? $_REQUEST['viewid'] : '';


$user_id = $_GET['id'];
$query = "SELECT user_name FROM tbl_users WHERE user_id = '$user_id' ";
$user_name = $obj->get_execute_scalar($query, $error_message);

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
<!--        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reset Password</h1>
          </div> /.col 
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Reset Password</li>
            </ol>
          </div> /.col 
        </div>-->
          
          <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Reset Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                   <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['btnChangePass'])) {
                ValidatePasswords();
            }
        }
        ?>
                   <form role="form" id="resetPassForm" name="resetPassForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputPassword">New Password  <font color="red">*</font></label>
                    <input type="password" class="form-control" name="txtNewPassword" id="txtNewPassword" placeholder="Enter Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputCPassword">Confirm Password  <font color="red">*</font></label>
                    <input type="password" class="form-control" id="txtConfirmPassword" name="txtConfirmPassword" placeholder="Enter Confirm Password">
                  </div>
                      </div>
                  
               
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="btnChangePass" name="btnChangePass" class="btn btn-primary">Reset Password</button>
                  <a href="../frm/listview.php?id=<?php echo $view_id; ?>" class="btn btn-danger">Back</a>
                </div>
               
              </form>
           
         
                </div>
            </div>
            <!-- /.card -->
             <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Password Tips</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                    <p>
            <b>TIPS ON PASSWORD SECURITY</b><br />
            1. For security reasons we recommend that you change your password from time to time.<br />
            2. Never choose a password that is easy to guess, such as data that is personal to you.<br />
            3. Since online dictionaries and encyclopedias can be helpful to potential hackers in figuring out weak passwords, choosing terms from these references should be avoided.<br />
            4. Also, simple letter and number sequences (e.g., abcdefgh, 12345678) as well as unimaginative alterations of words (e.g., words spelled backwards or alternating letter cases) can be easily hacked. <br />
            5. Avoid telephone numbers, license plates, and well known numbers. <br />
            6. In addition, common keystroke combinations such as "qwerty" or "zxcvbn" should not be used as passwords.<br />
            7. The length of a password determines its security strength as the time required to crack longer passwords increases exponentially. Therefore, passwords should consist of at least 8 characters and contain both capital and lower case lettering, as well as numbers and special characters.<br />
        </p>
              </div>
                </div>
            </div>
          </div>
         
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

</div>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});

$(document).ready(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      alert( "Password Reset Successfully" );
      return true;
    }
  });
  $.validator.addMethod("pwcheck", function(value) {
   return  /^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/.test(value)
      
});
  $('#resetPassForm').validate({
    rules: {
      txtNewPassword: {
        required: true
      //  pwcheck: true
      },
      txtConfirmPassword: {
         equalTo : "#txtNewPassword"
      }
    },
    messages: {
      txtNewPassword: {
        required: "Please enter a password"
      //  pwcheck : "Please enter valid password as per rules."
      },
      txtConfirmPassword: {
        equalTo: "Password does not match"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>


<?php
unset($obj);

function ValidatePasswords() {
    global $obj, $user_id, $user_name;

    // validation 1: match new and confirm password
    $new_password = $_POST['txtNewPassword'];
    $confirm_password = $_POST['txtConfirmPassword'];
    if ($new_password != $confirm_password) {
        echo message_show("The new password and confirm password does not match.", "error");
        return false;
    }

    // to store in session
    $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
  
    $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    $new_password = hash('sha512', $new_password . $random_salt);

    $query = "update tbl_users set password = '$new_password', salt = '$random_salt' where user_id = '$user_id'";
    if ($obj->execute($query, $error_message)) {
      //  echo message_show("Password has been updated successfully for user <b>$user_name</b>.", "info");
        
                    header("location: ../forms/listview.php?id=2");
        $obj->save_log("Password has been updated successfully for user $user_name.", $_SERVER["REQUEST_URI"]);
    } else {
        echo message_show("Password updation failed for user <b>$user_name</b>: " . $error_message, "error");
        $obj->save_log("Password updation failed for user $user_name: " . $error_message, $_SERVER["REQUEST_URI"]);
    }
    unset($obj);
}
?>
<?php 
include('footer.php');
?>