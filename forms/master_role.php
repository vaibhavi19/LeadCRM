<?php 
ob_start();

include('header.php');

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();
ob_start();

$pagetext = 'Add';

$roledesc = $status = $default_page = $error_message = "";

$pkey_id = (isset($_REQUEST['pkeyid'])) ? $_REQUEST['pkeyid'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roledesc = mysqli_real_escape_string($obj->getcon(), $_POST['txtroledesc']);
    $default_page = mysqli_real_escape_string($obj->getcon(), $_POST['txtdefaultpage']);
    $status = (isset($_POST['chkStatus'])) ? $_POST['chkStatus'] : '';

    $status = ($status == "on") ? "A" : "D";
} else {
    $obj->save_log("Role master page opened.", $_SERVER["REQUEST_URI"]);

    if ($_REQUEST['action'] == 'EDIT') {
        $query = "select role_desc,default_page,status from gm_role_master where role_id = '$pkey_id'";
        $result = $obj->execute($query, $error_message);

        $row = mysqli_fetch_object($result);
        $roledesc = $row->role_desc;
        $default_page = $row->default_page;
        $status = ($row->status == "A") ? "checked" : "";

        unset($row);
        mysqli_free_result($result);
    }
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Role master</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $pagetext;?> Role</li>
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
                <h3 class="card-title"><?php echo $pagetext;?> Role</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                 <?php
        if (isset($_REQUEST['submit'])) {
            if ($_REQUEST['action'] == 'NEW') {
                $query = "insert into `gm_role_master`(`role_desc`,default_page,`status`,`created_on`,`created_by`)
                values('$roledesc','$default_page','A',now(),'{$_SESSION['user_id']}')";
            } elseif ($_REQUEST['action'] == 'EDIT') {
                $query = "update `gm_role_master` set `role_desc` = '$roledesc', default_page='$default_page',`status` = '$status', `edited_on` = now(), `edited_by` = '{$_SESSION['user_id']}' where `role_id` = '$pkey_id'";
            }
            //echo $query;exit;
            $obj->execute($query, $error_message);
            if ($error_message == "") {
                $obj->save_log("User role $roledesc saved .", $_SERVER["REQUEST_URI"]);

                echo "Data updated successfully.";
                header("Location: listview.php?id=1");
            } else {
                $obj->save_log("User role $roledesc error: $error_message.", $_SERVER["REQUEST_URI"]);
                echo "ERROR: " . $error_message;
            }
        } elseif (isset($_REQUEST['cancel'])) {
            $obj->save_log("User role master page closed.", $_SERVER["REQUEST_URI"]);
            header("Location: listview.php?id=1");
            return;
        }
        ?>
              <form role="form" id="roleForm" name="departmentForm" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputDepartmentName">Role Description  <font color="red">*</font></label>
                    <input type="text" class="form-control" name="txtroledesc" id="txtroledesc" placeholder="Enter name" value="<?php echo $roledesc; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputDescrition">Default Page  <font color="red">*</font></label>
                    <input type="text" class="form-control" id="txtdefaultpage" name="txtdefaultpage" placeholder="Enter Address" value="<?php echo $default_page; ?>">
                  </div>
                <div class="form-group">
                    <input type="checkbox" class="form-check-input" id="chkStatus" name="chkStatus" <?php echo $status; ?>>
                    <label class="form-check-label" for="exampleCheck1">Status</label>
                  </div>
                    
                  </div>
              
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    <a href="../frm/listview.php?id=1" class="btn btn-danger">Back</a>
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
$(document).ready(function () {
  bsCustomFileInput.init();
});

$(document).ready(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      alert( "Role Created Successfully" );
      return true;
    }
  });
  $('#roleForm').validate({
    rules: {
      txtroledesc: {
        required: true
      },
      txtdefaultpage: {
        required: true
      }
    },
    messages: {
      txtroledesc: {
        required: "Please enter a role description"
      },
      txtdefaultpage: {
        required: "Please enter default page"
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




<?php unset($obj); ?>
<?php 
include('footer.php');
?>