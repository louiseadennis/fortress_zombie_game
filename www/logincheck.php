<?php
require ('./config/accesscontrol.php');
require ('./config/MySQL.php');

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!mysql_select_db($mysql_database))
  showerror();

// Clean the data collected in the form
$loginUsername = mysqlclean($_POST, "loginUsername", 10, $mysql);
$loginPassword = mysqlclean($_POST, "loginPassword", 10, $mysql);

session_start();

// Authenticate the User
if (authenticateUser($mysql, $loginUsername, $loginPassword))
{
  // Register the loginUsername
  $_SESSION["loginUsername"] = $loginUsername;

  // Register the IP address that started this session
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

  // Relocate back to the first page
  header("Location: character_select_form.php");
  exit;
} else {
  // The authentication failed
  $message = 
    "Could not connect to Fortress Zombie Game as '{$loginUsername}'";

  // Relocate back to login page
  header("Location: login_form.php?msg=$message");
  exit;
}
?>