<?php // signup.php
# Connect to DB
require_once('./config/MySQL.php');

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!mysql_select_db($mysql_database))
  showerror();

$uname = mysqlclean($_POST, "loginUsername", 10, $mysql);
$email = mysqlclean($_POST, "loginEmail", 10, $mysql);
$pwd = mysqlclean($_POST, "loginPassword", 10, $mysql);

  if ($uname=='' or $email=='' or $pwd == '') {
    $_SESSION["message"] = "One or more required fields were left blank";
    header("Location: signup.html");
    exit;
 } else {
    // Check for existing user with the new id
    $sql = "SELECT * FROM users WHERE uname = $uname";
    $result = mysql_query($sql);
    if (!$result) {	
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
!!!!!	$pass = md5(trim($pwd));

	$sql = "INSERT INTO users (name,email,password,salt) VALUES ($uname, $email, $pwd, $salt)";
	if (!mysql_query($sql)) {
        error('A database error occurred in processing your '.
              'submission.<br>If this error persists, please '.
              'contact ?????<br>' . mysql_error());
	    header("Location: login.html");
	    exit;
	} else {	
	    header("Location: login.html");
	    exit;
        }
    } else {
      $_SESSION["message"] = "This user name is already taken";
      header("Location: signup.html");
      exit;
    }

}


?>