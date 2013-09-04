<?php // signup.php
# Connect to DB

require_once('./config/MySQL.php');
require_once('./config/accesscontrol.php');

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!mysql_select_db($mysql_database))
  showerror();

$uname = mysqlclean($_POST, "loginUsername", 10, $mysql);
$email = mysqlclean($_POST, "loginEmail", 100, $mysql);
$pwd = mysqlclean($_POST, "loginPassword", 10, $mysql);
$cpwd = mysqlclean($_POST, "cloginPassword", 10, $mysql);

if ($uname=='' or $email=='' or $pwd == '' or $cpwd == '') {
    $message = "One or more required fields were left blank";
    header("Location: signup_form.php?msg=$message");
    exit;
} else if ($pwd != $cpwd) {
    $message = "Your passwords weren't equal.  Please check";
    header("Location: signup_form.php?msg=$message");
    exit;
} else if (VerifyMailAddress($email)) {
    // Check for existing user with the new id
    $sql = "SELECT * FROM users WHERE uname = $uname";
    $result = mysql_query($sql);
    if (!$result) {	
        //  IIUC $pass contains both encrypted password and a randomly generated salt.
	$pass = crypt($pwd);

	$sql = "INSERT INTO users (name,email,password) VALUES ('$uname', '$email', '$pass')";
	if (!mysql_query($sql)) {
            $message = "Database Error: " . mysql_errno() . " : " . mysql_error();
	    header("Location: signup_form.php?msg=$message");
	    exit;
	} else {	
	    header("Location: login_form.php");
	    exit;
        }
    } else {
      $message = "This user name is already taken";
      header("Location: signup_form.php?msg=$message");
      exit;
    }

} else {
  $message = "This is not a valid Email Address $email";
  header("Location: signup_form.php?msg=$message");
  exit;
}

function VerifyMailAddress($address) 
{
   if(filter_var($address, FILTER_VALIDATE_EMAIL))
      return true;
   else
     return false;
}
?>