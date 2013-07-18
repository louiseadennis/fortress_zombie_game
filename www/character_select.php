<?php
# Connect to DB

require_once('./config/accesscontrol.php');
require_once('./config/MySQL.php');
session_start();
sessionAuthenticate();

$c_id = $_POST['c_id'];

$_SESSION["c_id"] = $c_id;

header("Location: main.php");
exit;
?>
