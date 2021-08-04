<?php

include '../sys/security.php';
include '../sys/generalx.php';

sec_session_start();

$obj = new conn();
$obj->save_log("Logout success.", $_SERVER["REQUEST_URI"]);
unset($obj);

// Unset all session values
$_SESSION = array();
// get session parameters
$params = session_get_cookie_params();
// Delete the actual cookie.
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Destroy session
session_destroy();
header('Location: ../index.php');
?>