<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="WINCrm - All in one Lead Management & Automation Solutions">
    <meta name="author" content="Mukesh Mamtora">
    <title>WinCrm - Register With Us</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/fav.png" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/fav.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/fav.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/fav.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/fav.png">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- BASE CSS -->
<!--    <link href="outer/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="outer/css/vendors.css" rel="stylesheet">
    <link href="outer/css/style.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="outer/css/custom.css" rel="stylesheet">
    
         <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
    <?php
    include_once 'sys/generalx.php';
    $obj = new conn();
    session_unset();
    $error_text = $login = $password = $remember = "";
    if (isset($_REQUEST['error'])) {
        if ($_REQUEST['error'] == '1') {
            $error_text = 'Invalid Credential';
        }
        if ($_REQUEST['error'] == '2') {
            $error_text = "Password does not match";
        }
        if ($_REQUEST['error'] == '3') {
            $error_text = "Mobile Number already exist.";
        }

        //echo $error_text;exit;
    }
    ?>
<body class="background-image" data-background="url(img/bg6.jpg)" class="hold-transition login-page">
	
<!--	<div id="preloader">
		<div data-loader="circle-side"></div>
	</div> /Preload -->
	
	<div class="panel">
	    <aside>
	        <figure>
            <a href="#0"><img src="outer/img/logo-1.png" width="100%" height="100%" alt=""></a>
	        </figure>
<!--	        <form class="input_style_1" method="post">
			    <div class="form-group">
			        <label for="full_name">Full Name</label>
			        <input type="text" name="full_name" id="full_name" class="form-control">
			    </div>
				  <div class="form-group">
			        <label for="full_name">Mobile No</label>
			        <input type="text" name="mobile_no" id="full_name" class="form-control">
			    </div>
			    <div class="form-group">
			        <label for="email_address">Email Address</label>
			        <input type="email" name="email_address" id="email_address" class="form-control">
			    </div>
			    <div class="form-group">
			        <label for="password1">Password</label>
			        <input type="password" name="password1" id="password1" class="form-control">
			    </div>
			    <div class="form-group">
			        <label for="password2">Confirm Password</label>
			        <input type="password" name="password2" id="password2" class="form-control">
			    </div>
			    <div id="pass-info" class="clearfix"></div>
			    <div class="mb-4">
			        <label class="container_check">I agree to the <a href="#" data-toggle="modal" data-target="#terms-txt">Terms and Privacy Policy</a>.
			            <input type="checkbox">
			            <span class="checkmark"></span>
			        </label>
			    </div>
			    <button type="submit" class="btn_1 full-width">Sign Up</button>
			</form>-->
                   <form  action="forms/register_process.php" method="post" name="register_form" id="register_form"  autocomplete="off">
                        <input type="hidden" id="otp_send" name="otp_send" value="">
                        <div class="form-group">
                            <select name="industry_name" id="industry_name" class="form-control">
                                <option value="">Select Industry</option>
                                <?php
                                $shop_id = "";
                                $query = " select  industry_id ,industry_name from mst_industries  ";
                                echo $obj->fill_combo($query, $industry_id, true);
                                ?>
                            </select> 
                        </div>

                        <div class="form-group">
                            <input type="text" name="txtName" id="txtName" class="form-control" placeholder="Name" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <input type="email" name="txtEncEmail" id="txtEncEmail" class="form-control" placeholder="Email" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <input type="text" name="txtEncMobile" maxlength="10" id="txtEncMobile" class="form-control" placeholder="Mobile" autocomplete="off">
                        </div>

<!--                        <div class="form-group">
                            <button type="button" name="generate_otp" id="generate_otp" class="col-md-12 btn btn-primary" onclick="generateotp()">Generate OTP</button>
                        </div>
  <div class="form-group">
                            <input type="password" name="otp_verify" id="otp_verify" class="form-control" placeholder="Enter OTP" autocomplete="off">

                        </div>-->
                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" autocomplete="off">

                        </div>
                      <div class="form-group">
                            <input type="password" name="conpassword" id="conpassword" class="form-control" placeholder="Enter confirm password" autocomplete="off">

                        </div>

                        <div class="input-group mb-3">
                            <p class="mb-0" style="color: red;"><?php echo $error_text; ?></p>
                        </div>

                        <div class="row">
                            <!-- /.col -->

                            <button type="submit" name="submit" class="col-md-12 btn btn-primary btn-block">Register</button>

                            <!-- /.col -->
                        </div>
                        <br>
                        <div class="form-group">
                            <a href="index.php" class="btn btn-warning col-md-12">Already Registered? Please login</a>
                        </div>
                    </form>
                
	        <p class="text-center mt-3">Already have an account? <a href="#0">Sign In</a></p>
	        <form class="input_style_1" method="post">
	            <div id="forgot_pw">
	                <div class="form-group">
	                    <label for="email_forgot">Login email</label>
	                    <input type="email" class="form-control" name="email_forgot" id="email_forgot">
	                </div>
	                <p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
	                <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
	            </div>
	        </form>
	        <div class="copy">© 2021 WinCrm - All Rights Reserved.</div>
	    </aside>
	</div>
	<!-- /panel -->

	<!-- Modal terms -->
	<div class="modal fade" id="terms-txt" tabindex="-1" role="dialog" aria-labelledby="termsLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="termsLabel">Terms and conditions</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in <strong>nec quod novum accumsan</strong>, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus. Lorem ipsum dolor sit amet, <strong>in porro albucius qui</strong>, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn_1" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	
	<!-- COMMON SCRIPTS -->
<!--    <script src="outer/js/common_scripts.js"></script>
	<script src="outer/js/common_func.js"></script>

	 SPECIFIC SCRIPTS 
	<script src="outer/js/pw_strenght.js"></script>	-->
 <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="plugins/jquery-validation/additional-methods.min.js"></script>
        <script language="javascript" type="text/javascript">
    
            $(document).ready(function() {

                $.validator.setDefaults({
                    submitHandler: function() {
                        return true;
                    }
                });


                $('#register_form').validate({
                    rules: {
                        industry_name: {
                            required: true
                        },
                        txtName: {
                            required: true
                        },
                        txtEncEmail: {
                            required: true
                        },
                        txtEncMobile: {
                            required: true
                        },
//                        otp_verify: {
//                            required: true,
//                            equalTo: "#otp_send"
//                        },
                        conpassword:{
                              required: true,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        industry_name: {
                            required: "Please select shop"
                        },
                        txtName: {
                            required: "Please enter Company Name"
                        },
                        txtEncEmail: {
                            required: "Please enter email"
                        },
                        txtEncMobile: {
                            required: "Please enter mobile"
                        },
//                        otp_verify: {
//                            required: "Please enter OTP",
//                            equalTo: "Incorrect OTP"
//                        },
                        conpassword:{
                             required: "Please enter password",
                            equalTo: "Password does not match"
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