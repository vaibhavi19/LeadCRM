<?php

// This cron executes daily table clean-up codes : need to be executed once in a day IN vendor desk,
// Preferrably in the mid-night

include("../sys/generalx.php");
include("../sys/security.php");

$obj = new conn();
$sql_array = array();

// delete 2 days old user log
$query = "delete from log_user where timestampdiff(HOUR, created_on, now()) > 48 ";
array_push($sql_array, $query);

if (!$obj->execute_sqli_array($sql_array, $error_message) == true) {
    echo "ERROR " . $error_message . "<br/><br/>";
}
unset($sql_array);
unset($obj);
echo "Daily clean-up completed.";
exit();
?>