<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Card Desk</title>
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
    <body class="hold-transition login-page">

        <div class="login-box">
            <div class="login-logo">
<!--                <a href="#"><b>Welcome to WIN CRM</b></a>-->
                <img src="dist/img/leadcrm_logo.jpeg" style="height: 100px;width: 358px;">
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body">
                    <p class="login-box-msg">Login Here</p>


                    <form  action="forms/login_process.php" method="post" name="login_form" id="login_form"  autocomplete="off">
                       
                        <div class="form-group">
                            <input type="text" name="txtEncMobile" maxlength="10" id="txtEncMobile" class="form-control" placeholder="Mobile" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" autocomplete="off">

                        </div>
                   

                        <div class="input-group mb-3">
                            <p class="mb-0" style="color: red;"><?php echo $error_text; ?></p>
                        </div>

                        <div class="row">
                            <!-- /.col -->

                            <button type="submit" name="submit" class="col-md-12 btn btn-primary btn-block">Login</button>

                            <!-- /.col -->
                        </div>
                        <br>
                        <div class="form-group">
                            <a href="register.php" class="btn btn-warning col-md-12">Not Register? Please Register</a>
                        </div>
                    </form>


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
        <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="plugins/jquery-validation/additional-methods.min.js"></script>
        <script language="javascript" type="text/javascript">

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
            $(document).ready(function() {






                $.validator.setDefaults({
                    submitHandler: function() {
                        return true;
                    }
                });


                $('#login_form').validate({
                    rules: {
                        txtEncMobile: {
                            required: true
                        },
                        password: {
                            required: true,
                        }
                    },
                    messages: {
                        
                        txtEncMobile: {
                            required: "Please enter mobile"
                        },
                        password: {
                            required: "Please enter password",
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
