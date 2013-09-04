<?php // character_gen.php
# Connect to DB

require_once('./config/accesscontrol.php');
require_once('./config/MySQL.php');
require_once('utilities.php');
session_start();
sessionAuthenticate();
$max_char = 3;

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!mysql_select_db($mysql_database))
  showerror();

$user_id = get_user_id($mysql);
$cname = mysqlclean($_POST, "charName", 100, $mysql);

if ($cname=='') {
    $message = "One or more required fields were left blank";
    header("Location: character_gen_form.php?msg=$message");
    exit;
} else {
    // Check for existing user with the new id
    $sql = "SELECT * FROM characters WHERE cname = $cname";
    $result = mysql_query($sql);
    if (!$result) {	
        $sql1 = "SELECT * FROM characters WHERE user_id = $user_id";
    	$result = mysql_query($sql1);
        if (!$result || mysql_num_rows($result) <= $max_char) {

 	    $sql2 = "INSERT INTO characters (name, user_id) VALUES ('$cname', $user_id)";
	    if (!mysql_query($sql2)) {
                $message = "Database Error: " . mysql_errno() . " : " . mysql_error();
	        header("Location: character_gen_form.php?msg=$message");
	        exit;
	    } else {	
	        header("Location: main.php");
	        exit;
            }
        } else {
          $message = "Sorry you have exceeded the Maximum Number of Character";
          header("Location: character_gen_form.php?msg=$message");
          exit;
        }
    } else {
      $message = "This character name is already taken";
      header("Location: character_gen_form.php?msg=$message");
      exit;
    }

} 
?>
