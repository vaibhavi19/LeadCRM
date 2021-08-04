<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Lead Desk</title>
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />

<!-- v4.0.0-alpha.6 -->
<link rel="stylesheet" href="dist/bootstrap/css/bootstrap.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- Theme style -->
<link rel="stylesheet" href="dist/css/style.css">
<link rel="stylesheet" href="dist/css/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="dist/css/et-line-font/et-line-font.css">
<link rel="stylesheet" href="dist/css/themify-icons/themify-icons.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
    <?php
    include_once 'sys/generalx.php';
    session_unset();
    $error_text = $login = $password = $remember = "";
    if (isset($_REQUEST['error'])) {
        if ($_REQUEST['error'] == '1') {
            $error_text = 'Invalid Credential';
        } else {
            $error_text = "";
        }
        //echo $error_text;exit;
    }
    ?>
<body class="hold-transition login-page" style="background: url(dist/img/bg-image/bg4.jpg)">
<div class="login-box">
  <div class="login-box-body">
    <h3 class="login-box-msg">Sign In</h3>
    <form name="login_form" id="login_form" action="forms/login.php" method="post">
      <div class="form-group has-feedback">
          <input type="text" class="form-control sty1" name="txtLoginID" id="txtLoginID" placeholder="User Name" required="">
      </div>
      <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" placeholder="Password" id="txtEncPassword" name="txtEncPassword" required="">
      </div>
      <div>
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox">
              Remember Me </label>
<!--            <a href="pages-recover-password.html" class="pull-right"><i class="fa fa-lock"></i> Forgot pwd?</a>-->
          </div>
        </div>
                  <div class="input-group mb-3">
                            <p class="mb-0" style="color: red;"><?php echo $error_text; ?></p>
                        </div>
        <!-- /.col -->
        <div class="col-xs-4 m-t-1">
          <button type="submit" name="login_submit" id="login_submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col --> 
      </div>
    </form>
<!--    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
      Facebook</a> <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
      Google+</a> </div>-->
    <!-- /.social-auth-links -->
    
    <div class="m-t-2">Don't have an account? <a href="pages-register.html" class="text-center">Sign Up</a></div>
  </div>
  <!-- /.login-box-body --> 
</div>
<!-- /.login-box --> 

<!-- jQuery 3 --> 
<script src="dist/js/jquery.min.js"></script> 

<!-- v4.0.0-alpha.6 --> 
<script src="dist/bootstrap/js/bootstrap.min.js"></script> 

<!-- template --> 
<script src="dist/js/niche.js"></script>
</body>
</html>