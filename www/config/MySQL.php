<?php

require_once('/Users/louiseadennis/Personal/urbandead/fortress_zombie_game/localfiles/config.inc.php');

// Stolen from PHP and MySQL by Hugh E. Williams and David Lane
function mysqlclean($array, $index, $maxlength, $connection) 
{
  if (isset($array["{$index}"]))
  {
    $input = substr($array["{$index}"], 0, $maxlength);
    $input = mysql_real_escape_string($input, $connection);
    return ($input);
  }
  return NULL;
}

function showerror() 
{
  die("Error " . mysql_errno() . " : " . mysql_error());
}

function get_user_id()
{
  $uname = $_SESSION["loginUsername"];

  $sql = "SELECT user_id FROM users WHERE uname = $uname;

  if (!$result = mysql_query($sql)) 
      showerror();
  
  if (mysql_num_rows($result) != 1)
      return 0;
  else {
     while ($row=mysql_fetch_array($result)) {
   	 return $row["user_id"];
   }
}

function get_character_name()
{
  $c_id = $_SESSION["c_id"];

  $sql = "SELECT name FROM characters WHERE c_id = $c_id;

  if (!$result = mysql_query($sql)) 
      showerror();
  
  if (mysql_num_rows($result) != 1)
      return 0;
  else {
     while ($row=mysql_fetch_array($result)) {
   	 return $row["name"];
   }
}

function characterSelected() 
{
 // Check if the user hasn't logged in
 if (!isset($_SESSION["c_id"]))
 {
   // The resquest does not identify a session
   $message = "No Character Selected";
   header("Location: character_select_form.php?msg=$message");
   exit;
 }
}

}
?>