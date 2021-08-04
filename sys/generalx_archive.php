<?php

date_default_timezone_set('asia/kolkata');
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

include("define.php");

class conn_archive {

    function getcon() {
        $this->link = mysqli_connect(DB_SERVER_IP_ARCHIVE, DB_LOGIN_ARCHIVE, DB_PASSWORD_ARCHIVE, DB_DATABASE_ARCHIVE);
        if (mysqli_connect_errno($this->link)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        return $this->link;
    }

    function execute($query, &$error_message) {
        $link = $this->getcon();
        $result = mysqli_query($link, $query);
        $error_message = "";
        if (mysqli_errno($link)) {
            $error_message = "Error: " . mysqli_error($link) . ", SQL = $query";
        }
        mysqli_close($link);

        return $result;
    }

    function execute_sqli_array($sql_array, &$error_message) {
        $array_size = count($sql_array);

        /* set autocommit to off */
        $link = $this->getcon();
        $link->autocommit(FALSE);

        for ($i = 0; $i < $array_size; $i++) {
            /* execute the queries */
            //echo "<BR>".$sql_array[$i];
            if (!$link->query($sql_array[$i])) {
                $error_message = "ERROR: " . $link->error . "<br><br>SQL: " . $sql_array[$i];
                /* Rollback */
                $link->rollback();
                return false;
            };
        }

        /* commit transaction */
        $link->commit();
        /* close connection */
        mysqli_close($link);

        //$error_message = "Success";
        $error_message = "";
        return true;
    }

    function get_execute_scalar($query, &$error_message) {
        $retval = "";
        $link = $this->getcon();
        $result = mysqli_query($link, $query);
        //echo $query;
        if ($result) {


            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $retval = $row[0];
            }
            mysqli_free_result($result);
        }
        mysqli_close($link);

        return $retval;
    }

    function fill_combo($query, &$default_value, $mandatory) {
        $link = $this->getcon();
        $cmbfill = '';
        $result = mysqli_query($link, $query);
        $cmbfill = "";
        if ($mandatory == false)
            $cmbfill = "<option value=\"\">All</option>";

        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
//            if ($default_value == "")
//                $default_value = $row[0];

                $cmbfill = $cmbfill . "<option ";

                if ($default_value == $row[0]) {
                    $cmbfill = $cmbfill . " selected ";
                }
                if ($row[1] <> '') {
                    $cmbfill = $cmbfill . " value=\"{$row[0]}\">{$row[1]}</option>";
                } else {
                    $cmbfill = $cmbfill . " value=\"{$row[0]}\">{$row[0]}</option>";
                }
            }
            mysqli_free_result($result);
        }

        mysqli_close($link);
        return $cmbfill;
    }

    function get_next_id($key_type, $key_name, &$return_sql) {
        $link = $this->getcon();
        $sql = "SELECT key_value FROM gm_key_values WHERE key_type = '$key_type' AND key_name = '$key_name' ";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            // increase id by 1
            $next_val = $row["key_value"];
            $next_val += 1;
            /* free result set */
            $result->close();
            // store update query to the reference variable           
            $return_sql = "UPDATE  gm_key_values SET key_value = '$next_val' WHERE key_type = '$key_type' AND key_name = '$key_name' ";
            return $row["key_value"];
        } else {
            $return_sql = $link->error;
            return "";
        }
    }

    function save_log($log_description, $page_url) {
        $query = $error_message = "";
        $user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");
        $ip_address = (isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : $_SERVER['REMOTE_ADDR']);
        $query = "insert into log_user (user_id, session_id, created_on, log_description, page_url, ip_address ) "
                . " values (" . replaceBlank($user_id) . ",'" . session_id() . "', now(), "
                . replaceBlank($log_description) . "," . replaceBlank($page_url) . ","
                . replaceBlank($ip_address) . ")";
        //echo $query;
        //exit();
        return $this->execute($query, $error_message);
    }
    function save_vendor_bank_audit_log($log_description, $vendor_id, $type) {
        $query = $error_message = "";
        $created_by = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");
        $ip_address = (isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : $_SERVER['REMOTE_ADDR']);

        $query = "insert into trn_vendor_bank_audit_log(vendor_id,log_type,log_description, created_by, created_on,  ip_address ) values( ";
        $query = $query . "" . replaceBlank($vendor_id) . " , " . replaceBlank($type) . ",'" . ($log_description) . "' ," . replaceBlank($created_by) . ",now()," . replaceBlank($ip_address) . ") ";

//echo $query;exit;
        return $this->execute($query, $error_message);
    }

    function save_alert($log_description, $page_url) {
        $query = "SELECT user_id FROM gm_user_master where status = 'A' and alerts_enabled = 'Y' ";

        $result = $this->execute($query, $error_message);
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                $query = $error_message = "";

                $ip_address = (isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : $_SERVER['REMOTE_ADDR']);
                $query = "insert into log_user_alerts (user_id, session_id, created_on, log_description, page_url, ip_address ) "
                        . " values (" . $row['user_id'] . ",'" . session_id() . "', now(), "
                        . replaceBlank($log_description) . "," . replaceBlank($page_url) . ","
                        . replaceBlank($ip_address) . ")";
                //echo $query;
                //exit();           
                $this->execute($query, $error_message);
            }
            mysqli_free_result($result);
        }

        return TRUE;
    }

    function get_key_value($key_type, $key_name) {
        $error_message = "";
        $query = "select key_value from gm_key_values where key_type = '$key_type' and key_name = '$key_name' ";

        //echo $query;
        return $this->get_execute_scalar($query, $error_message);
    }

    function create_mail($sender_mail, $sender_name, $mail_to, $cc, $bcc, $subject, $body, $attachments) {
        $created_by = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

        
     $block_id = $this->get_execute_scalar("SELECT block_id FROM sys_blocked_emails where blocked_email =  '$mail_to'", $error_message);
        if ($block_id != "") {
            $mail_to = "";
        }


        $block_id = $this->get_execute_scalar("SELECT block_id FROM sys_blocked_emails where blocked_email =  '$cc'", $error_message);
        if ($block_id != "") {
            $cc = "";
        }
        
              $block_id = $this->get_execute_scalar("SELECT block_id FROM sys_blocked_emails where blocked_email =  '$bcc'", $error_message);
        if ($block_id != "") {
            $bcc = "";
        }

        
        if($mail_to=="" && $cc==""){
            return true ;
        }
        $sql = " INSERT INTO  sys_mailbox (message_from_name, message_from_mail, message_to ,  message_cc, message_subject ,  message_body ,  ";
        $sql = $sql . " message_attachments ,  message_status ,  created_by ,  created_on  )";
        $sql = $sql . " VALUES( ";
        $sql = $sql . " " . replaceBlank($sender_name, 100) . ", ";
        $sql = $sql . " " . replaceBlank($sender_mail, 100) . ", ";
        $sql = $sql . " " . replaceBlank($mail_to, 300) . ", ";
        $sql = $sql . " " . replaceBlank($cc, 300) . ", ";
        //$sql = $sql . " " . replaceBlank($bcc, 300) . ", ";
        $sql = $sql . " " . replaceBlank($subject, 200) . ", ";
        $sql = $sql . " " . replaceBlank($body, 0) . ", ";
        $sql = $sql . " " . replaceBlank($attachments, 1000) . ", ";
        $sql = $sql . " 'A', ";
        $sql = $sql . " '" . $created_by . "', now())";

        // query to increase the mail id
        $error_message = "";
        // echo $sql;exit();
        return $this->execute($sql, $error_message);
    }

    function lastInsertId() {

        return mysqli_insert_id($this);
    }

    function save_bill_log($log_description, $bill_id) {
        $query = $error_message = "";
        $created_by = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");
        $ip_address = (isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : $_SERVER['REMOTE_ADDR']);

        $query = "insert into trn_bills_log(bill_id,log_description, created_by, created_on,  ip_address ) values( ";
        $query = $query . "" . replaceBlank($bill_id) . " , " . replaceBlank($log_description) . " ," . replaceBlank($created_by) . ",now()," . replaceBlank($ip_address) . ") ";


        return $this->execute($query, $error_message);
    }

    // application specific functions

    function getJSONdata($query, $type = 'pichart') {
        $colorArr = array('Submitted' => '#f56954', 'Parked' => '#00a65a', 'Cheque Preparation In Progress' => '#f39c12', 'RTGS Approved' => '#00c0ef', 'Invoice Booked' => '#3c8dbc', 'Rejected' => '#f20e0e', 'Unassigned' => '#5fdf28', 'Cancelled' => '#664852', 'Inprocess' => '#29dfcd', 'Closed/Paid' => '#111414', 'Sent to Accounts' => '#29dfcd','Acknowledged-Subject to Verification'=>'#29dfcd');

        $departmentArr = array('IT' => '#f56954', 'Quantity Survey' => '#00a65a', 'Mechanical' => '#f39c12', 'HVAC' => '#00c0ef', 'Electrical' => '#3c8dbc', 'Plumbing' => '#f20e0e', 'Quality Assurance' => '#5fdf28', 'Consultants' => '#c60b49', 'Maintenance' => '#29dfcd', 'Purchase-BOM' => '#821e0b', 'Horticulture Material' => '#29dfcd', 'Horticulture Services' => '#8412b9', 'Admin' => '#2511ef', 'Engineering' => '#e21acf', 'Sales' => '#ede11c', 'Security' => '#57e913');

        $statusArr = array('Cheque Preparation In Progress' => '#f56954', 'Closed/Paid' => '#00a65a', 'Inprocess' => '#f39c12', 'Rejected' => '#00c0ef', 'Sent to Accounts' => '#3c8dbc', 'Submitted' => '#f20e0e', 'Parked' => '#f3bd1a','Acknowledged-Subject to Verification'=>'#29dfcd');


        switch ($type) {
            case 'pichart':
                $colorArr = $colorArr;
                break;
            case 'department':
                $colorArr = $departmentArr;
                break;
            case 'status':
                $colorArr = $statusArr;
                break;
            default:
                $colorArr = $colorArr;
        }

        $keyText = "";
        $valText = "";
        $colorText = "";
        $result = $this->execute($query, $error_message);
        if ($result) {
            $keyText .= "[";
            $valText .= "[";
            $colorText .= "[";
            $column_count = mysqli_num_fields($result);
            while ($row = mysqli_fetch_array($result)) {

                $i = 0;

                if ($i == 0) {
                    $keyText .= $row['count'] . ",";
                    $valText .= "'" . $row['status_desc'] . "',";
                    $colorText .= "'" . $colorArr[$row['status_desc']] . "',";
                } else {
                    $keyText .= (($row['count'] == "") ? "0" : $row['count']) . ",";  // numeric values
                    $valText .= (($row['status_desc'] == "") ? "0" : "'" . $row['status_desc']) . "',";  // numeric values
                    $colorText .= (($colorArr[$row['status_desc']] == "") ? "0" : "'" . $colorArr[$row['status_desc']]) . "',";  // numeric values
                }
                $i++;
            }
            $keyText = rtrim($keyText, ",");
            $keyText .= "]";
            $valText = rtrim($valText, ",");
            $valText .= "]";
            $colorText = rtrim($colorText, ",");
            $colorText .= "]";

            unset($result);
        }
        // Google chart data building completed....................................................
        $table = array('lable' => $keyText, 'value' => $valText, 'color' => $colorText);
        return $table;
    }

    function execute_sql($sql, &$error_message) {
        // global $con;
        /* execute the queries */
        if (!$this->query($sql)) {
            $error_message = "ERROR: " . $this->error . "<br><br>SQL: " . $sql;
            return false;
        };
        /* close connection */
        //$mysqli->close();
        $error_message = "Success";
        return true;
    }

}

class conn_ess {

    function getcon() {
        $this->link = mysqli_connect(DB_SERVER_IP_ESS, DB_LOGIN_ESS, DB_PASSWORD_ESS, DB_DATABASE_ESS);
        if (mysqli_connect_errno($this->link)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        return $this->link;
    }

    function execute($query, &$error_message) {
        $link = $this->getcon();
        $result = mysqli_query($link, $query);
        $error_message = "";
        if (mysqli_errno($link)) {
            $error_message = "Error: " . mysqli_error($link) . ", SQL = $query";
        }
        mysqli_close($link);

        return $result;
    }

    function get_execute_scalar($query, &$error_message) {
        $retval = "";
        $link = $this->getcon();
        $result = mysqli_query($link, $query);
        //echo $query;
        if ($result) {


            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $retval = $row[0];
            }
            mysqli_free_result($result);
        }
        mysqli_close($link);

        return $retval;
    }

}

function mysqldateformatddmmyyyy1($str) {
    $str = str_replace(" ", "", $str);
    if ($str == "") {
        return "";
    } else {
        $date = explode('-', $str);
        return "" . $date[0] . "-" . $date[1] . "-" . $date[2] . "";
    }
}

function message_show1($message, $message_type) {
    $bg_color = "#e9ffd9";
    $border_color = "#a6ca8a";
    $image_name = "../img/success.png";
    switch ($message_type) {
        case "error":
            $bg_color = "#ffecec";
            $border_color = "#f5aca6";
            $image_name = "../img/cross.png";
            break;

        case "alert":
            $bg_color = "#fff8c4";
            $border_color = "#f2c779";
            $image_name = "../img/exclamation.png";
            break;

        case "info":
            $bg_color = "#e9ffd9";
            $border_color = "#a6ca8a";
            $image_name = "../img/success.png";
            break;

        case "help":
            $bg_color = "#e3f7fc";
            $border_color = "#8ed9f6";
            $image_name = "../img/help.png";
            break;
    }
    $rval = "<div style=\"color:#717880; border:$border_color 1px solid; background:$bg_color; border-radius:5px; padding:10px; margin-bottom:10px;\">";
    $rval = $rval . "<table><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='" . $image_name . "' /></td>";
    $rval = $rval . "<td>&nbsp;&nbsp;" . $message . "</td></tr>";
    $rval = $rval . "</table></div>";
    return $rval;
}

function show_validation_message1($object_id, $object_message) {
    return "<div name=\"" . $object_id . "\" id=\"" . $object_id . "\" style=\"display: none\" ><font color=red>" . $object_message . "</font></div>";
}

function replaceBlank1($str) {
    if ($str == "") {
        return "NULL";
    } else {
        $obj = new conn();
        $str = mysqli_escape_string($obj->getcon(), $str);
        unset($obj);
        return "'" . $str . "'";
    }
}

// converts date string to mysql date format (YYYY-Month-Day) - 2014-07-19
function replaceDate1($dateval) {
    if ($dateval == "")
        $dateval = "null";
    else {
        $dateval = "'" . date('Y-m-d', strtotime($dateval)) . "'";
    }
    return $dateval;
}

// converts datetime to mysql date format (YYYY-Month-Day) - 2014-07-19
function replaceDateTime1($dateval) {
    if ($dateval == "")
        $dateval = "null";
    else {
        $dateval = "'" . date('Y-m-d', $dateval) . "'";
    }
    return $dateval;
}

// converts mysql db date to printale format
function db_date_to_print1($dateval) {
    if ($dateval == "")
        return "";
    return date('d-m-Y', strtotime($dateval));
}

function encryptdecrypt1($data, $encrypt) {
    if ($encrypt == "true")
        $intValue = 1;
    else
        $intValue = -1;

    $retVal = "";
    $i = 0;
    while ($i < strlen($data)) {
        // echo substr($data, $i, 1) . "<br />";
        $retVal = $retVal . chr(ord(substr($data, $i, 1)) + $intValue);
        $i++;
    }

    //$retVal = str_replace("@", "Z", $retval);
    return $retVal;
}

function pivot_columns1($table_name, $static_columns, $ctab_text_column, $ctab_value_column, $where_condition, $group_by, $order_by, $no_total) {
    $obj = new conn();

    $pv_columns = $pv_columns_sum = $pv_column_total = $error_message = "";
    $query = "select distinct $ctab_text_column from $table_name where $where_condition";

    $result = $obj->execute($query, $error_message);
    while ($row = mysqli_fetch_array($result)) {
        $pv_columns .= "case when $ctab_text_column = '{$row[0]}' then $ctab_value_column else 0 end  as `{$row[0]}`,";
        $pv_columns_sum .= " sum(`{$row[0]}`) as `{$row[0]}` ,";
        $pv_column_total .= " `{$row[0]}` +";
    }
    mysqli_free_result($result);

    $pv_columns = rtrim($pv_columns, ',');
    $pv_columns_sum = rtrim($pv_columns_sum, ',');
    $pv_column_total = rtrim($pv_column_total, '+');

    $query = " select $static_columns, $pv_columns from $table_name where $where_condition ";

    if ($no_total == "true") {
        $wrapper_query = "select $static_columns, $pv_columns_sum 
                            from ( $query ) detail_tab 
                            group by $group_by  order by $order_by ";
    } else {
        $wrapper_query = "select $static_columns, $pv_columns_sum, sum($pv_column_total) as Total_D 
                            from ( $query ) detail_tab 
                            group by $group_by  order by $order_by ";
    }

    unset($obj);
    return $wrapper_query;
}

function getPaginationString1($page = 1, $totalitems, $limit = 15, $adjacents = 1, $targetpage = "/", $pagestring = "?page=") {
    //defaults
    if (!$adjacents)
        $adjacents = 1;
    if (!$limit)
        $limit = 15;
    if (!$page)
        $page = 1;
    if (!$targetpage)
        $targetpage = "/";

    //other vars
    $prev = $page - 1;         //previous page is page - 1
    $next = $page + 1;         //next page is page + 1
    $lastpage = ceil($totalitems / $limit);    //lastpage is = total items / items per page, rounded up.
    $lpm1 = $lastpage - 1;        //last page minus 1

    /*
      Now we apply our rules and draw the pagination object.
      We're actually saving the code to a variable in case we want to draw it more than once.
     */
    $margin = '5';
    $pagination = "";
    $padding = '5';
    if ($lastpage > 1) {
        $pagination .= "<div class=\"pagination\"";
        if ($margin || $padding) {
            $pagination .= " style=\"";
            if ($margin)
                $pagination .= "margin: $margin;";
            if ($padding)
                $pagination .= "padding: $padding;";
            $pagination .= "\"";
        }
        $pagination .= ">";

        //previous button
        if ($page > 1)
            $pagination .= "<a href=\"$targetpage$pagestring$prev\">&laquo; prev</a>";
        else
            $pagination .= "<span class=\"disabled\">&laquo; prev</span>";

        //pages	
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<span class=\"current\">$counter</span>";
                else
                    $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
            }
        }
        elseif ($lastpage >= 7 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 3)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                }
                $pagination .= "<span class=\"elipses\">...</span>";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
                $pagination .= "<span class=\"elipses\">...</span>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                }
                $pagination .= "...";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
            }
            //close to end; only hide early pages
            else {
                $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
                $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
                $pagination .= "<span class=\"elipses\">...</span>";
                for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class=\"current\">$counter</span>";
                    else
                        $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">next &raquo;</a>";
        else
            $pagination .= "<span class=\"disabled\">next &raquo;</span>";
        $pagination .= "</div>\n";
    }

    return $pagination;
}

function no_to_words1($no) {
    $words = array('0' => '', '1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six', '7' => 'seven', '8' => 'eight', '9' => 'nine', '10' => 'ten', '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen', '14' => 'fourteen', '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen', '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty', '30' => 'thirty', '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy', '80' => 'eighty', '90' => 'ninety', '100' => 'hundred ', '1000' => 'thousand', '100000' => 'lakh', '10000000' => 'crore');
    if ($no == 0)
        return ' ';
    else {
        $novalue = '';
        $highno = $no;
        $remainno = 0;
        $value = 100;
        $value1 = 1000;
        while ($no >= 100) {
            if (($value <= $no) && ($no < $value1)) {
                $novalue = $words["$value"];
                $highno = (int) ($no / $value);
                $remainno = $no % $value;
                break;
            }
            $value = $value1;
            $value1 = $value * 100;
        }
        if (array_key_exists("$highno", $words))
            return $words["$highno"] . " " . $novalue . " " . no_to_words($remainno);
        else {
            $unit = $highno % 10;
            $ten = (int) ($highno / 10) * 10;
            return $words["$ten"] . " " . $words["$unit"] . " " . $novalue . " " . no_to_words($remainno);
        }
    }
}

function validate_invoice_date1($invoice_date, $department) {
    $today_date = date('Y-m-d');
    // $invoice_date = $_POST['invoice_date'];//"2020-07-01";
    $invoice_date = date('Y-m-d', strtotime($invoice_date));

    $first_day_of_month = date("Y-m-1");
    $first_day_of_previous_month = date("Y-m-d", strtotime("first day of previous month"));

    if ($department == '6') {
        // IT ACCPET PREVIOUS MONTH BILLS TILL 5TH OF CURRENT MONTH 
        $cutoff_date = date("Y-m-5");
        if ((strtotime($invoice_date) > strtotime($today_date))) {
            // echo "<br>Future date not acceptable";
            $response = array('msg' => 'Invoice not raised :  Future dated invoice is not acceptable.', 'success' => '0');
        } elseif ((strtotime($today_date) > strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_month))) {
            //    echo "<br>NOPE...Old bills are not acceptable";
            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_month)) . ' is not allowed.', 'success' => '0');
        } elseif ((strtotime($today_date) <= strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_previous_month))) {
            // echo "<br>NOPE NOPE...Old bills are not acceptable";
            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_previous_month)) . ' is not allowed.', 'success' => '0');
        } else {
            // echo "<br>DONE...Current month bills are welcome";
            $response = array('msg' => 'DONE...Current month bills are welcome', 'success' => '1');
        }
    } elseif ($department == '17' || $department == '20' || $department == '22' || $department== "23") {
        // ADMIN AND SECURITY AND MDS OFFICE AND MARKETING ACCPET PREVIOUS MONTH BILLS TILL 20TH OF NEXT MONTH 
        $cutoff_date = date("Y-m-20");
        if ((strtotime($invoice_date) > strtotime($today_date))) {
            // echo "<br>Future date not acceptable";
            $response = array('msg' => 'Invoice not raised :  Future dated invoice is not acceptable.', 'success' => '0');
        } elseif ((strtotime($today_date) > strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_month))) {
            //    echo "<br>NOPE...Old bills are not acceptable";
            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_month)) . ' is not allowed.', 'success' => '0');
        } elseif ((strtotime($today_date) <= strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_previous_month))) {
            // echo "<br>NOPE NOPE...Old bills are not acceptable";
            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_previous_month)) . ' is not allowed.', 'success' => '0');
        } else {
            // echo "<br>DONE...Current month bills are welcome";
            $response = array('msg' => 'DONE...Current month bills are welcome', 'success' => '1');
        }
    } elseif ($department == "15") {
        // PURCHASE-BOM ACCPET PREVIOUS 90 DAYS OLD BILLS TILL TODAY 

        $invoice_date_for = date_create($invoice_date);
        $today_date_for = date_create($today_date);
        $diff = date_diff($invoice_date_for, $today_date_for);

        $diff_days = $diff->format("%a");


        if ((strtotime($invoice_date) > strtotime($today_date))) {
            $response = array('msg' => 'Invoice not raised :  Future dated invoice is not acceptable.', 'success' => '0');
        } elseif ($diff_days > 90) {
            $response = array('msg' => 'Invoice not raised :  90 days old invoice is not acceptable.', 'success' => '0');
        } else {
            $response = array('msg' => 'DONE...Current month bills are welcome', 'success' => '1');
        }
    } else {
        // FOR REST DEPARTMENT DONT ACCEPT PREVIOUS MONTH BILLS
    $cutoff_date = date("Y-m-30");
        if (strtotime($invoice_date) < strtotime($first_day_of_month)) {
            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_month)) . ' is not allowed.', 'success' => '0');
        } elseif (strtotime($invoice_date) > strtotime($today_date)) {
            $response = array('msg' => 'Invoice not raised :  Future dated invoice is not acceptable.', 'success' => '0');
        } elseif ((strtotime($invoice_date) <= strtotime($cutoff_date)) && (strtotime($invoice_date) >= strtotime($first_day_of_month))) {
            $response = array('msg' => 'DONE...Current month bills are welcome', 'success' => '1');
        }
        
//          $cutoff_date = date("Y-m-20");
//        if ((strtotime($invoice_date) > strtotime($today_date))) {
//            // echo "<br>Future date not acceptable";
//            $response = array('msg' => 'Invoice not raised :  Future dated invoice is not acceptable.', 'success' => '0');
//        } elseif ((strtotime($today_date) > strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_month))) {
//            //    echo "<br>NOPE...Old bills are not acceptable";
//            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_month)) . ' is not allowed.', 'success' => '0');
//        } elseif ((strtotime($today_date) <= strtotime($cutoff_date)) && (strtotime($invoice_date) < strtotime($first_day_of_previous_month))) {
//            // echo "<br>NOPE NOPE...Old bills are not acceptable";
//            $response = array('msg' => 'Invoice not raised : Invoice with date before ' . date('d-m-Y', strtotime($first_day_of_previous_month)) . ' is not allowed.', 'success' => '0');
//        } else {
//            // echo "<br>DONE...Current month bills are welcome";
//            $response = array('msg' => 'DONE...Current month bills are welcome', 'success' => '1');
//        }
//        
        
    }

    return $response;
}

function validate_invoice_due_date1($invoice_date, $invoice_due_date) {
    if ($invoice_due_date == '') {
        $response = array('msg' => 'Invoice Due Date is perfect', 'success' => '1');
    } else {
        if ((strtotime($invoice_date) <= strtotime($invoice_due_date))) {
            $response = array('msg' => 'Invoice Due Date is perfect', 'success' => '1');
        } else {
            $response = array('msg' => 'Invoice Due Date Should be greater than or equal to invoice date', 'success' => '0');
        }
    }

    return $response;
}

?>