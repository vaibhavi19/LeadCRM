<?php
ob_start();
include 'header.php';

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_REQUEST['submit'])) {


        if (isset($_POST["submit"])) {

            if (isset($_FILES["file"])) {

                $user_id = $_SESSION['user_id'];
                $industry_id = $_SESSION['industry_id'];

                $target_dir = "../public/uploads/";
                if (!file_exists($target_dir . $user_id)) {
                    mkdir($target_dir . $user_id, 0777, true);
                }
                $target_dir = "../public/uploads/" . $user_id . "/";
                $target_file = $target_dir . date('mdYHis') . basename($_FILES["file"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $newfile = date('m-d-Y_H:i:s') . '.' . $imageFileType;
                //$target_file = $target_dir . $newfile;
                $errormsg = $successmsg = '';




                //if there was an error uploading the file
                if ($_FILES["file"]["error"] > 0) {
                    $errormsg .= "Return Code: " . $_FILES["file"]["error"] . "<br />";
                } else {


                    //if file already exists
                    // if (file_exists($target_dir . $_FILES["file"]["name"])) {
                    //     $errormsg .= $_FILES["file"]["name"] . " already exists. ";
                    // } else {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                    if ($imageFileType != "csv") {
                        $errormsg .= "Sorry, only CSV files are allowed.";
                        $uploadOk = 0;
                    } else {


                        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                        $successmsg .= "Stored in: " . $target_dir . $_FILES["file"]["name"] . "<br />";

//echo "123";exit;
                        $link = mysqli_connect("localhost", "root", "", "lead_crm");
                        $success = file_get_contents_chunked($link, $target_file, 2048, '', function ($chunk, &$handle, $iteration, &$queryValuePrefix, $link) {

                            $chunk = $queryValuePrefix . $chunk;
                            $user_id = $_SESSION['user_id'];
                            $industry_id = $_SESSION['industry_id'];
                            //$industry_id = 2;
                            $industry_param_arr = [
                                1 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id', 'flat_type', 'project_type', 'property_type', 'area', 'location', 'budget'],
                                2 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id', 'service', 'location'],
                                3 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id', 'occasion', 'booking_date', 'additional_option', 'menu'],
                                4 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id', 'loan_type', 'loan_amount', 'yearly_income', 'profession'],
                                5 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id',],
                                6 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id',],
                                7 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id',],
                                8 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id',],
                                9 => ['client_name', 'display_name', 'mobile_number', 'whatsapp_number', 'email_id',]
                            ];

                            $industry_tablename_arr = [
                                1 => 'mst_lead_real_estate',
                                2 => 'mst_lead_spa',
                                3 => 'mst_lead_banquets',
                                4 => 'mst_lead_loan',
                                5 => 'mst_lead_health_care',
                                6 => 'mst_lead_eduacation',
                                7 => 'mst_lead_digital_agency',
                                8 => 'mst_lead_banking',
                                9 => 'mst_lead_travel'
                            ];

                            $TABLENAME = $industry_tablename_arr[$industry_id];

                            // split the chunk of string by newline. Not using PHP's EOF
                            // as it may not work for content stored on external sources
                            $lineArray = preg_split("/\r\n|\n|\r/", $chunk);
                            $query = 'INSERT INTO ' . $TABLENAME . '(
                              user_id, ';
                            $i_params = $industry_param_arr[$industry_id];
//                             print_r($i_params);exit;
                            $i_params_str = implode(',', $i_params);
                            $query .= $i_params_str;
                            $query .= ',source_id,lead_status) VALUES ';
                            $numberOfRecords = count($lineArray);
//                            print_r($lineArray);exit;
                            for ($i = 1; $i < $numberOfRecords - 2; $i ++) {
                                // split single CSV row to columns
                                $colArray = explode(',', $lineArray[$i]);
                                $query = $query . '("' . $user_id . '",';
//                                print_r($colArray);exit;
                                foreach ($colArray as $key => $val) {
                                    $query = $query . ' "' . $val . '",';
                                }
                                $query = $query . ' 2,"N"),';
                            }
//                            echo $query;exit;
                            // last row without a comma
                            $colArray = explode(',', $lineArray[$i]);
                            $query = $query . '("' . $user_id . '",';
                            foreach ($colArray as $key => $val) {
                                $query = $query . ' "' . $val . '",';
                            }
                            $query = $query . '2,"N")';
                            $i = $i + 1;

                            // storing the last truncated record and this will become the
                            // prefix in the next run
                            //echo $query;exit;
                            $queryValuePrefix = $lineArray[$i];
                            mysqli_query($link, $query) or die(mysqli_error($link));
                        });

                        // }
                    }
                }
                echo message_show('Leads imported successfully.', 'success');
            } else {
                $errormsg .= "No file selected <br />";
            }
        }
    }
}

function file_get_contents_chunked($link, $file, $chunk_size, $queryValuePrefix, $callback) {
    try {
        $handle = fopen($file, "r");
        $i = 0;
        while (!feof($handle)) {
            call_user_func_array($callback, array(
                fread($handle, $chunk_size),
                &$handle,
                $i,
                &$queryValuePrefix,
                $link
            ));
            $i ++;
        }
        fclose($handle);
    } catch (Exception $e) {
        trigger_error("file_get_contents_chunked::" . $e->getMessage(), E_USER_NOTICE);
        return false;
    }

    return true;
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Bulk Upload Leads</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Bulk Upload Leads</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <?php // echo "<pre>";print_r($_SESSION);      ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-cyan">
                        <div class="card-header">
                            <h3 class="card-title">Bulk Upload Leads</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="lead_form" name="lead_form" method="post" enctype="multipart/form-data">

                                <div class="row">

                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload</label>
                                            <div class="input-group">
                                                <div class="custom-file">

                                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="file" >
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Submit</label>
                                            <input type="submit" class="form-control btn btn-warning"  value="Upload Document" name="submit" id="submit" />
                                        </div>
                                    </div>
                                </div>
                              
                            </form>
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div>
    </section>
    <!-- /.row -->
</div><!-- /.container-fluid -->
<?php //echo $company_logo;    ?>

<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {

                //  alert("Form successful submitted!");
                return true;
            }
        });

        $('#lead_form').validate({
            rules: {
                file: {
                    required: true,
                    extension: "csv"
                }

            },
            messages: {
                file: {
                    required: "Please upload document",
                    extension: "Only csv file allowed to upload"
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




<?php unset($obj); ?>
<?php
include('footer.php');
?>


