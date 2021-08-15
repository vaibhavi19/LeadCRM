<?php
ob_start();

include('header.php');

if (login_check() == false) {
    echo 'You are not authorized to access this page, please login. <br/>';
    exit();
}
$obj = new conn();
ob_start();
$pagetext = 'progressbar';
$lead_id = isset($_GET['lid']) ? $_GET['lid'] : "";
if ($lead_id == "") {
    echo message_show('Invalid request for lead', 'error');
    exit;
}


$stage_id = $obj->get_execute_scalar('select stage_id from ' . $_SESSION['lead_form_table'] . ' where lead_id=' . $lead_id, $error_message);
//$_SESSION['lead_form_table'];
//$_SESSION['lead_form_view'];
//echo "<pre>";
//print_r($_SESSION);
$query = "select lead_status from " . $_SESSION['lead_form_table'] . " where lead_id=" . $lead_id;

$lead_status = $obj->get_execute_scalar($query, $error_message);

$client_name = $display_name = $mobile_number = $whatsapp_number = $mobile_number = $whatsapp_number = $email_id = $lead_status = $stage_id = $source_id = "";

$lead_query = "select * from " . $_SESSION['lead_form_table'] . " where lead_id=" . $lead_id;
$result = $obj->execute($lead_query, $error_message);
if (isset($result)) {
    $row = mysqli_fetch_object($result);
//    echo "<pre>";
//    print_r($row);
//    exit;
    $client_name = $row->client_name;
    $display_name = $row->display_name;
    $mobile_number = $row->mobile_number;
    $whatsapp_number = $row->whatsapp_number;
    $mobile_number = $row->mobile_number;
    $email_id = $row->email_id;
    $lead_status = $row->lead_status;
    $stage_id = $row->stage_id;
    $source_id = $row->source_id;

    unset($row);
    mysqli_free_result($result);
}


$new_status = $inprocess_status = $lost_status = $close_status = $converted_status = "";
switch ($lead_status) {
    case "N":
        $new_status = 'btn-success';
        $inprocess_status = $lost_status = $close_status = $converted_status = "btn-default";

        break;
    case "I":
        $inprocess_status = 'btn-success';
        $new_status = $lost_status = $close_status = $converted_status = "btn-default";

        break;
    case "L":
        $lost_status = 'btn-success';
        $new_status = $inprocess_status = $close_status = $converted_status = "btn-default";

        break;
    case "A":
        $converted_status = 'btn-success';
        $new_status = $inprocess_status = $close_status = $lost_status = "btn-default";

        break;
    case "C":
        $close_status = 'btn-success';
        $new_status = $inprocess_status = $lost_status = $converted_status = "btn-default";
        break;
    default:
        $new_status = $converted_status = $inprocess_status = $lost_status = $close_status = "";
}
?>
<style>
    .testimonial-group > .row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    .testimonial-group > .row > .col-xs-4 {
        flex: 0 0 auto;
    }
</style>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//echo "<pre>";print_r($_POST);exit;
    if (isset($_POST['followup_submit'])) {
        $followup_date = date('Y-m-d H:i:s', strtotime($_POST['followup_date']));
        $remarks = $_POST['followup_remarks'];
        $array = array();
        $log_text = 'raised';

        $query = " insert into lead_followup (lead_id,client_id, followup_date,remarks, created_by, created_on,status) values ( ";
        $query = $query . " " . replaceBlank($lead_id) . "," . replaceBlank($_SESSION['client_id']) . "," . replaceBlank($followup_date) . "," . replaceBlank($remarks) . "," . replaceBlank($_SESSION['user_id']) . ",now(),'A')";
        array_push($array, $query);
        if (!$obj->execute_sqli_array($array, $error_message)) {
            echo message_show($error_message, "error");
        } else {
            if ($lead_id) {
                $activity_desc = strtoupper($_SESSION['user_name']) . ' has added followup for the lead <b>#' . $lead_id . "</b> with followup date  " . $followup_date;
                $log_response = $obj->save_lead_log($activity_desc, 'FOLLOWUP_REQUEST', $lead_id, $_SESSION['client_id']);
            }
            echo message_show('Followup request successfully added for this lead.', 'success');
        }
    }

    if (isset($_POST['reminder_submit'])) {
        $reminder_date = date('Y-m-d H:i:s', strtotime($_POST['reminder_date']));
        $remarks = $_POST['reminder_remarks'];
        $array = array();
        // $log_text = 'raised';

        $query = " insert into lead_reminder (lead_id,client_id, reminder_date,remarks, created_by, created_on,status) values ( ";
        $query = $query . " " . replaceBlank($lead_id) . "," . replaceBlank($_SESSION['client_id']) . "," . replaceBlank($reminder_date) . "," . replaceBlank($remarks) . "," . replaceBlank($_SESSION['user_id']) . ",now(),'A')";
        array_push($array, $query);
        if (!$obj->execute_sqli_array($array, $error_message)) {
            echo message_show($error_message, "error");
        } else {
            if ($lead_id) {
                $activity_desc = strtoupper($_SESSION['user_name']) . ' has added reminder for the lead <b>#' . $lead_id . "</b> with reminder date  " . $reminder_date;
                $log_response = $obj->save_lead_log($activity_desc, 'REMINDER_REQUEST', $lead_id, $_SESSION['client_id']);
            }
            echo message_show('Reminder request successfully added for this lead.', 'success');
        }
    }

    if (isset($_POST['note_submit'])) {
        //  $reminder_date = date('Y-m-d H:i:s', strtotime($_POST['reminder_date']));
        $remarks = $_POST['note_remarks'];
        $array = array();
        // $log_text = 'raised';

        $query = " insert into lead_notes (lead_id,client_id,notes, created_by, created_on,status) values ( ";
        $query = $query . " " . replaceBlank($lead_id) . "," . replaceBlank($_SESSION['client_id']) . "," . replaceBlank($remarks) . "," . replaceBlank($_SESSION['user_id']) . ",now(),'A')";
        array_push($array, $query);
        if (!$obj->execute_sqli_array($array, $error_message)) {
            echo message_show($error_message, "error");
        } else {
            if ($lead_id) {
                $activity_desc = strtoupper($_SESSION['user_name']) . ' has added notes for the lead <b>#' . $lead_id . "</b> on " . date('d-M-Y');
                $log_response = $obj->save_lead_log($activity_desc, 'ADD_NOTE', $lead_id, $_SESSION['client_id']);
            }
            echo message_show('Notes addedd successfully added for this lead.', 'success');
        }
    }

    if (isset($_POST['change_status'])) {
        $array = array();
        $stage_id = $_POST['lead_status'];
        $lead_status = '';
        if ($stage_id == '1') {
            $lead_status = 'N';
            $current_status = 'New';
        } elseif ($stage_id == '2') {
            $lead_status = 'I';
            $current_status = 'Inprocess';
        } elseif ($stage_id == '3') {
            $lead_status = 'L';
            $current_status = 'Lost';
        } elseif ($stage_id == '4') {
            $lead_status = 'A';
            $current_status = 'Converted';
        } elseif ($stage_id == '5') {
            $lead_status = 'C';
            $current_status = 'Close';
        }
        $query = "update " . $_SESSION['lead_form_table'] . " set stage_id=" . $stage_id . ",lead_status='$lead_status' where lead_id=" . $lead_id;

        array_push($array, $query);
        if (!$obj->execute_sqli_array($array, $error_message)) {
            echo message_show($error_message, "error");
        } else {
            if ($lead_id) {
                $activity_desc = strtoupper($_SESSION['user_name']) . ' has change the status of the lead to';
                $log_response = $obj->save_lead_log($activity_desc, 'CHANGE_STATUS', $lead_id, $_SESSION['client_id']);
            }
            echo message_show('Change status of this lead successfully.', 'success');

            header("Location:dashboard_lead.php?lid=" . $lead_id);
        }

//        echo "<pre>";
//        print_r($_POST);
//        exit;
    }
}
?>



<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">




            <div class="row">
                <div  class="col-md-12">


                    <div class="card-header card-blue">
                        <h3 class="card-title card-blue flashit" style="font-size:25px;"><b>#LEAD <?php echo $lead_id; ?></b></h3>
                    </div>

                    <div class="card-body">
                        <div id="actions" class="row">
                            <div class="col-lg-12">
                                <div class="btn-group w-100">
                                    <span class="btn <?php echo $new_status; ?> col fileinput-button ">
                                        <i class="fas fa-hourglass-start"></i>
                                        <span>NEW</span>
                                    </span>
                                    <button type="submit" class="btn <?php echo $inprocess_status; ?> col start">
                                        <i class="far fa-arrow-alt-circle-right"></i>
                                        <span>In Process</span>
                                    </button>
                                    <button type="reset" class="btn <?php echo $converted_status; ?> col cancel">
                                        <i class="far fa-check-circle"></i>
                                        <span>Converted</span>
                                    </button>
                                    <button type="reset" class="btn <?php echo $lost_status; ?> col cancel">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Lost</span>
                                    </button>
                                    <button type="reset" class="btn <?php echo $close_status; ?> col cancel">
                                        <i class="fas fa-hourglass-end"></i>
                                        <span>Close</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <br><br>
                        <div class="card" style='background-color: #8fa0f4;'>
                            <div class="testimonial-group">
                                <div class="row text-center" >

                                    <div class="col-md-3 ">
                                        <div class="description-block border-right">
    <!--                                                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>-->
                                            <h5 class="description-header" style='color:#db1007;'><?php echo $client_name; ?></h5>
                                            <span class="description-text"><b>Client Name</b></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3 ">
                                        <div class="description-block border-right">
    <!--                                                <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>-->
                                            <h5 class="description-header" style='color:#db1007;'><?php echo $display_name; ?></h5>
                                            <span class="description-text"><b>Display Name</b></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="description-block border-right">
    <!--                                                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>-->
                                            <h5 class="description-header" style='color:#db1007;'><?php echo $email_id; ?></h5>
                                            <span class="description-text"><b>Email Id</b></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-3">
                                        <div class="description-block">
    <!--                                                <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>-->
                                            <h5 class="description-header" style='color:#db1007;'><?php echo $mobile_number; ?></h5>
                                            <span class="description-text"><b>Mobile Number</b></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <div class="col-md-3">
                                        <div class="description-block">
    <!--                                                <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>-->
                                            <h5 class="description-header" style='color:#db1007;'><?php echo $whatsapp_number; ?></h5>
                                            <span class="description-text"><b>Whatsapp Number</b></span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>


                                </div>
                            </div>
                        </div>

                        <br><br> 
                        <!-- /.row -->


                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text" data-toggle="modal" data-target="#modal-followup" >Add Followup Request</span>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-gradient-orange elevation-1">
                                        <i class="fas fa-stopwatch"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text" data-toggle="modal" data-target="#modal-reminder" >Set Reminder</span>
<!--                                        <span class="info-box-number">41,410</span>-->
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <!-- fix for small devices only -->
                            <div class="clearfix hidden-md-up"></div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1">
                                        <i class="fas fa-toggle-on"></i>
  <!--                                        <i class="fas fa-phone-volume"></i>-->
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text" data-toggle="modal" data-target="#modal-change-status">Change Status</span>
<!--                                        <span class="info-box-number">760</span>-->
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1">
                                        <i class="far fa-clipboard"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text" data-toggle="modal" data-target="#modal-note">Add Notes</span>
<!--                                        <span class="info-box-number">2,000</span>-->
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>


                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1">
                                        <i class="far fa-handshake"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text" data-toggle="modal" data-target="#modal-meeting">Schedule Meeting</span>
<!--                                        <span class="info-box-number">2,000</span>-->
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <!-- DIRECT CHAT -->
                                <div class="card direct-chat direct-chat-warning">
                                    <div class="card-header bg-cyan">
                                        <h3 class="card-title">Followup Request</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th style="width: 40px">Status</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $query = "SELECT remarks, case when status='A' then 'Pending' else 'Done' end as `status`,date_format(followup_date,'%d-%b-%Y %h:%i %p') as `followup_date` FROM `lead_followup`  where lead_id=" . $lead_id . " order by created_on ASC";
                                            $result = $obj->execute($query, $error_message);
                                            $i = 1;
                                            ?>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows != 0) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($row['status'] == 'active') {
                                                            $class = 'bg-danger';
                                                        } else {
                                                            $class = 'bg-warning';
                                                        }
                                                        ?>


                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $row['followup_date']; ?></td>
                                                            <td><?php echo $row['remarks']; ?></td>
        <!--                                                    <td>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                                </div>
                                                                
                                                                
                                                            </td>-->
                                                            <td><span class="badge <?php echo $class; ?>"><?php echo $row['status']; ?></span></td>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan=4>No Records Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--/.direct-chat -->
                            </div>
                            <div class="col-md-3">
                                <!-- DIRECT CHAT -->
                                <div class="card direct-chat direct-chat-warning">
                                    <div class="card-header bg-gradient-orange">
                                        <h3 class="card-title">Reminder Request</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th style="width: 40px">Status</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $query = "SELECT remarks, case when status='A' then 'Pending' else 'Done' end as `status`,date_format(reminder_date,'%d-%b-%Y %h:%i %p') as `reminder_date` FROM `lead_reminder`  where lead_id=" . $lead_id . "  order by created_on ASC";
                                            $result = $obj->execute($query, $error_message);
                                            $i = 1;
                                            ?>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows != 0) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($row['status'] == 'active') {
                                                            $class = 'bg-danger';
                                                        } else {
                                                            $class = 'bg-warning';
                                                        }
                                                        ?>


                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $row['reminder_date']; ?></td>
                                                            <td><?php echo $row['remarks']; ?></td>
        <!--                                                    <td>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                                </div>
                                                                
                                                                
                                                            </td>-->
                                                            <td><span class="badge <?php echo $class; ?>"><?php echo $row['status']; ?></span></td>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan=4>No Records Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--/.direct-chat -->
                            </div>
                            <div class="col-md-3">
                                <!-- DIRECT CHAT -->
                                <div class="card direct-chat direct-chat-warning">
                                    <div class="card-header bg-success">
                                        <h3 class="card-title">Calling Request</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th style="width: 40px">Status</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $query = "SELECT remarks, case when status='A' then 'Pending' else 'Done' end as `status`,date_format(reminder_date,'%d-%b-%Y %h:%i %p') as `reminder_date` FROM `lead_reminder`  where lead_id=" . $lead_id . "  order by created_on ASC";
                                            $result = $obj->execute($query, $error_message);
                                            $i = 1;
                                            ?>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows != 0) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($row['status'] == 'active') {
                                                            $class = 'bg-danger';
                                                        } else {
                                                            $class = 'bg-warning';
                                                        }
                                                        ?>


                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $row['reminder_date']; ?></td>
                                                            <td><?php echo $row['remarks']; ?></td>
        <!--                                                    <td>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                                </div>
                                                                
                                                                
                                                            </td>-->
                                                            <td><span class="badge <?php echo $class; ?>"><?php echo $row['status']; ?></span></td>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan=4>No Records Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--/.direct-chat -->
                            </div>
                            <div class="col-md-3">
                                <!-- DIRECT CHAT -->
                                <div class="card direct-chat direct-chat-warning">
                                    <div class="card-header bg-warning">
                                        <h3 class="card-title">All Notes</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">#</th>

                                                    <th>Remarks</th>
                                                    <th style="width: 40px">Status</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $query = "SELECT notes, case when status='A' then 'Pending' else 'Done' end as `status` FROM `lead_notes`  where lead_id=" . $lead_id . "  order by created_on ASC";
                                            $result = $obj->execute($query, $error_message);
                                            $i = 1;
                                            ?>
                                            <tbody>
                                                <?php
                                                // print_r($result['num_rows']);exit;
                                                if ($result->num_rows != 0) {
                                                    //  echo "vrvbjvnj";exit;
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($row['status'] == 'active') {
                                                            $class = 'bg-danger';
                                                        } else {
                                                            $class = 'bg-warning';
                                                        }
                                                        ?>


                                                        <tr>
                                                            <td><?php echo $i; ?></td>

                                                            <td><?php echo $row['notes']; ?></td>
        <!--                                                    <td>
                                                                <div class="progress progress-xs">
                                                                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                                                </div>
                                                                
                                                                
                                                            </td>-->
                                                            <td><span class="badge <?php echo $class; ?>"><?php echo $row['status']; ?></span></td>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan=3>No Records Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--/.direct-chat -->
                            </div>
                        </div>
                        <!--                        <div class="row">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-call">
                                                        Call to customer
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-mail">
                                                        Mail to customer
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-whatsapp">
                                                        Whatspapp to customer</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-sms">
                                                        Send SMS to customer
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-note">
                                                        Add Notes
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-lead-status">
                                                        Change Status of lead
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>-->

                    </div>
                </div>
            </div>


        </div>
    </section>
    <!-- /.row -->
</div><!-- /.container-fluid -->

<div class="modal fade" id="modal-followup">
    <div class="modal-dialog">
        <form name="followup_form" id="followup_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Followup Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>Date and time:</label>
                    <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                        <input type="text" name="followup_date" id="followup_date" class="form-control datetimepicker-input" data-target="#reservationdatetime"/>
                        <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <label>Remarks </label>
                    <textarea name="followup_remarks" id="followup_remarks" class="form-control" rows="2" cols="3"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="followup_submit" id="followup_submit" class="btn btn-primary"> Add Followup Activity</button>
                </div>
            </div>
        </form>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-reminder">
    <div class="modal-dialog">
        <form name="reminder_form" id="reminder_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Reminder Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>Date and time:</label>
                    <div class="input-group date" id="reminderdatetime" data-target-input="nearest">
                        <input type="text" name="reminder_date" id="reminder_date" class="form-control datetimepicker-input" data-target="#reminderdatetime"/>
                        <div class="input-group-append" data-target="#reminderdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <label>Remarks </label>
                    <textarea name="reminder_remarks" id="reminder_remarks" class="form-control" rows="2" cols="3"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="reminder_submit" id="reminder_submit" class="btn btn-primary"> Add Reminder Activity</button>
                </div>
            </div>
        </form>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-note">
    <div class="modal-dialog">
        <form name="add_notes_form" id="add_notes_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Notes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>Remarks </label>
                    <textarea name="note_remarks" id="note_remarks" class="form-control" rows="5" cols="4"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="note_submit" id="note_submit" class="btn btn-primary"> Add  Notes</button>
                </div>
            </div>
        </form>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-change-status">
    <div class="modal-dialog">
        <form name="change_status_form" id="change_status_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change status of this lead</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <label>Status </label>
                    <select name="lead_status" id="" class="form-control"  >
                        <!--                        <option>Select lead status</option>-->
                        <?php
                        $query = " select stage_id,stage_name  from mst_lead_stages ";
                        echo $obj->fill_combo($query, $stage_id, false);
                        ?>
                    </select>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="change_status" id="change_status" class="btn btn-primary">Change Status of lead</button>
                </div>
            </div>
        </form>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-meeting">
    <div class="modal-dialog">
        <form name="meeting_form" id="meeting_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Schedule Meeting</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
//                    echo "<pre>";
//                    print_r($_SESSION);
                    ?>
                    <label>Date and time:</label>
                    <div class="input-group date" id="reminderdatetime" data-target-input="nearest">
                        <input type="text" name="reminder_date" id="reminder_date" class="form-control datetimepicker-input" data-target="#reminderdatetime"/>
                        <div class="input-group-append" data-target="#reminderdatetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <label>To Email id </label>
                    <input name='to_email' id='to_email' class="form-control" placeholder="To Email id" >
                    <label>CC Email id </label>
                    <input name='cc_email' id='cc_email' class="form-control" placeholder="CC Email id">
                    <label>Meeting URL </label>
                    <input name='meeting_url' id='meeting_url' class="form-control" placeholder="Meeting URL">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="schedule_meeting" id="schedule_meeting" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </div>
        </form>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<script>
    $(function() {
        $('#reservationdatetime').datetimepicker({icons: {time: 'far fa-clock'}});
        $('#reminderdatetime').datetimepicker({icons: {time: 'far fa-clock'}});
    });
    $(document).ready(function() {
        $.validator.setDefaults({
            submitHandler: function() {

                //  alert("Form successful submitted!");
                return true;
            }
        });
        $('#followup_form').validate({
            rules: {
                followup_date: {
                    required: true
                }
            },
            messages: {
                followup_date: {
                    required: "Please Select date"
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

        $('#reminder_form').validate({
            rules: {
                reminder_date: {
                    required: true
                }
            },
            messages: {
                reminder_date: {
                    required: "Please Select date"
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
        $('#change_status_form').validate({
            rules: {
                lead_status: {
                    required: true
                }
            },
            messages: {
                lead_status: {
                    required: "Please Select status"
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

  $('#add_notes_form').validate({
            rules: {
                note_remarks: {
                    required: true
                }
            },
            messages: {
                note_remarks: {
                    required: "Please enter note"
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