<?php
require_once('globals.php');

// Stolen from PHP and MySQL by Hugh E. Williams and David Lane
function showerror() 
{
  die("Error " . mysql_errno() . " : " . mysql_error());
}

function get_user_id($connection)
{
  $uname = $_SESSION["loginUsername"];

  $sql = "SELECT user_id FROM users WHERE name = '{$uname}'";

  if (!$result = mysql_query($sql, $connection)) 
      showerror();
  
  if (mysql_num_rows($result) != 1)
      return 0;
  else {
     while ($row=mysql_fetch_array($result)) {
         $uid = $row["user_id"];
   	 return $uid;
     }
   }
}

// Get the name of the current character from a given database and session
function get_character_name($c_id, $connection)
{
  $sql = "SELECT name FROM characters WHERE c_id = $c_id";

  if (!$result = mysql_query($sql,$connection)) 
      showerror();
  
  if (mysql_num_rows($result) != 1)
      return 0;
  else {
     while ($row=mysql_fetch_array($result)) {
         $name = $row["name"];
   	 return $name;
    }
  }
}


function get_character_details($c_id, $connection)
{
  $sql = "SELECT (name, ap) FROM characters WHERE c_id = $c_id";

  if (!$result = mysql_query($sql,$connection)) 
      showerror();
  
  if (mysql_num_rows($result) != 1)
      return 0;
  else {
     while ($row=mysql_fetch_array($result)) {
         $character=array();
         $character['name'] = $row["name"];
         $character['ap'] = $row["ap"];
   	 return $character;
    }
  }
}

// Check if a character is selected
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

// Get an array of characters at a given coordinates
function otherCharactersAt($square_id, $inside, $c_id, $connection) 
{
   $other_chars_res = mysql_query("select name from characters where square_id = $square_id and inside = $inside and c_id != $c_id", $connection);
   $other_chars = array();
   if ($other_chars_res) {
   while ($row = mysql_fetch_array($other_chars_res)) {
       $other_chars[] = $row["name"];
   }
   return $other_chars;
}


}

function squareFromChar($c_id, $connection)
{
   $square_details = array();
   $location_res = mysql_query("select square_id, inside from characters where c_id = $c_id", $connection);
   while($row = mysql_fetch_array($location_res)) {
       $square = $row["square_id"];
       $square_details['inside'] = $row["inside"];
       if ($square == 0) {
          $square = 1;
       }
       $square_details['square_id'] = $square;

       $square_res = mysql_query("select name, xcoord, ycoord, type from squares where square_id = $square", $connection);
       while ($row2 = mysql_fetch_array($square_res)) {
             $square_details['name'] = $row2["name"];
             $square_details['xcoord'] = $row2["xcoord"];
             $square_details['ycoord'] = $row2["ycoord"];
             $square_details['type'] = $row2["type"];
       }
   }

   return $square_details;
}

function squareFromCoords($cx, $cy, $inside, $connection)
{
   $square_details = array();
   $square_details['xcoord'] = $cx;
   $square_details['ycoord'] = $cy;
   $square_details['inside'] = $inside;
   $square_res = mysql_query("select name, square_id, type from squares where xcoord = $cx and ycoord = $cy", $connection);
   while ($row2 = mysql_fetch_array($square_res)) {
        $square_details['name'] = $row2["name"];
        $square_details['square_id'] = $row2["square_id"];
        $square_details['type'] = $row2["type"];
   }

  return $square_details;

}

function check_legal($c_id, $cx, $cy, $inside, $mysql)
{
  $charsquare = squareFromChar($c_id, $mysql);

  $charx = $charsquare['xcoord'];
  $chary = $charsquare['ycoord'];
  $char_inside = $charsquare['inside'];
 
  if (abs($charx - $cx) < 2) {
     if (abs($chary - $cy) < 2) {
        // This is a legal square
 
        if ($charx != $cx || $chary != $cy) {
            // must go outside if leaving this square
            if ($inside == 0) {
               return TRUE;
            } else {
               return FALSE;
            }
        }

        return TRUE;
     }
  }

  return FALSE;
}

function deduct_ap($c_id, $connection)
{
  $max_ap = get_max_ap($connection);
  $ap_refresh_rate = get_ap_refresh_rate($connection);

  $sql = "select ap, last_action_time, accrued_time from characters where c_id  = $c_id";

  $ap_res = mysql_query($sql, $connection);
  while ($row = mysql_fetch_array($ap_res)) {
    $ap = $row["ap"];
    $last_time = $row["last_action_time"];
    $accrued_time = $row["accrued_time"];
  }

  $current_time = time();
  // convert mysql timestamp to a php timestamp;
  $last_timestamp = date('H:i:s',strtotime($last_time));

  $time_passed = ($current_time - $last_timestamp) + $accrued_time;
  $ap_gained = $time_passed/$ap_refresh_rate;
  if ($ap_gained >= 1) {
     if ($ap + $ap_gained - 1 > $max_ap) {
        $new_ap = $max_ap;
        $new_acc_time = 0;
     } else {
        $new_ap = $ap + $ap_gained - 1;
        $new_acc_time = $time_passed % $ap_refresh_rate;
     }
  } else {
     $new_ap = $ap - 1;
     $new_acc_time = $time_passed;
  }
}

?>