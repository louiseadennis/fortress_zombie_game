<?php

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

?>