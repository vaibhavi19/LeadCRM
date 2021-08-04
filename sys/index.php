<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Bill Tracker</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
   <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
 <?php include_once 'sys/generalx.php'; 
  session_unset();
 $error_text = $login = $password = $remember = "";
 if(isset($_REQUEST['error'])){
      if($_REQUEST['error'] == '1'){
      $error_text = 'Invalid Credential';
 }else{
      $error_text ="";
 }
 //echo $error_text;exit;
 }


 
 ?>
<body class="hold-transition login-page">
    
    <?php
		if ($_SERVER["REQUEST_METHOD"] != "POST") {

			$login = isset($_COOKIE["vendor_desk_user_login"]) ? $_COOKIE["vendor_desk_user_login"] : "";
			$password = isset($_COOKIE["vendor_desk_user_pwd"]) ? $_COOKIE["vendor_desk_user_pwd"] : "";
					$remember = isset($_COOKIE["vendor_desk_user_remember"]) ? $_COOKIE["vendor_desk_user_remember"] : "";
		}
		?>
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Welcome to Hiranandani Vendor Desk Portal</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="frm/login_process.php" method="post" name="login_form">
        <div class="input-group mb-3">
          <input type="text" name="txtLoginID" id="txtLoginID" class="form-control" placeholder="Email" value="<?php echo $login; ?>" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="txtEncPassword" id="txtEncPassword" class="form-control" placeholder="Password" value="<?php echo $password; ?>" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
<!--          <div class="input-group mb-3">
              <select name="txtloginAs" id="txtloginAs" class="form-control">
                  <option value="VENDOR">Vendor</option>
                  <option value="U">Staff</option>
                  
              </select>
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>-->
           <div class="input-group mb-3">
          <p class="mb-0" style="color: red;"><?php echo $error_text; ?></p>
           </div>
          
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" <?php echo $remember;?> id="chkRemember" name="chkRemember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
<!--      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
   <script language="javascript" type="text/javascript">
                function checkValidation() {
                    retVal = true;
                    document.getElementById('txtLoginID_validation').style.display = 'none';
                    document.getElementById('txtPassword_validation').style.display = 'none';

                    if (document.forms['login_form'].txtLoginID.value == "") {
                        retVal = false;
                        document.forms['login_form'].txtLoginID.focus();
                        document.getElementById('txtLoginID_validation').style.display = 'block';
                    }

                    if (document.forms['login_form'].txtPassword.value == "") {
                        retVal = false;
                        document.forms['login_form'].txtLoginID.focus();
                        document.getElementById('txtPassword_validation').style.display = 'block';
                    }
                    if (retVal == true)
                        document.forms['login_form'].txtEncPassword.value = hex_sha512(document.forms['login_form'].txtPassword.value);
                    else
                        return false;
                }
            </script>
</body>
</html>
