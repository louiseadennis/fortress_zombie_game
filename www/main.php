<?php 

require_once('./config/accesscontrol.php');

# Connect to DB
require_once('./config/MySQL.php');
session_start();
sessionAuthenticate();
characterSelected();

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_database);

# Get Character ids
$c_id = $_SESSION["c_id"];

# Get Location details from form
$cx = $_POST["cx"];
$cy = $_POST["cy"];
$inside = $_POST["inside"];
$square_name = "None";

# Get Location details from database if there isn't a form
if ($cx == NULL) {
   $location_res = mysql_query("select square_id, inside from characters where c_id = $c_id", $mysql);
   while($row = mysql_fetch_array($location_res)) {
       $square = $row["square_id"];
       $inside = $row["inside"];
       if ($square == 0) {
          $square = 1;
       }
       $square_res = mysql_query("select name, xcoord, ycoord, type from squares where square_id = $square", $mysql);
       while ($row2 = mysql_fetch_array($square_res)) {
             $square_name = $row2["name"];
             $cx = $row2["xcoord"];
             $cy = $row2["ycoord"];
             $square_type = $row2["type"];
       }
   }
   $other_chars_res = mysql_query("select name from characters where square_id = $square and inside = $inside and c_id != $c_id", $mysql);
} else {
# Get Location details from database if there is a form
   $square_res = mysql_query("select name, square_id, type from squares where xcoord = $cx and ycoord = $cy", $mysql);
   while ($row2 = mysql_fetch_array($square_res)) {
        $square_name = $row2["name"];
        $square = $row2["square_id"];
        $square_type = $row2["type"];
   }

   $char_update = "UPDATE characters SET square_id=$square, inside=$inside WHERE c_id = $c_id";
   if (!mysql_query($char_update)) {
       $message = "Database Error: " . mysql_errno() . " : " . mysql_error();
       header("Location: main.php?msg=$message");
       exit;
    } 
   $other_chars_res = mysql_query("select name from characters where square_id = $square and inside = $inside and c_id != $c_id", $mysql);
}


# Create array of other characters in this location
$other_chars = array();
if ($other_chars_res) {
  while ($row = mysql_fetch_array($other_chars_res)) {
       $other_chars[] = $row["name"];
  }
}


$currentlocation='bgcolor="#C0C0C0"';
$otherlocation='bgcolor="#F5F5F5"';

?>

<html>
<head>
<title>Urban Dead 2 - The City</title>

<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<div class=main>
<?php
# Display any error, warning or debugging messages passed with  the form.
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<p>' . $msg . '</p>';
} 
?>


<table><tr>
<?php

# Display the "mini map"
$res = mysql_query("select name, xcoord, ycoord from squares where xcoord between ($cx - 1) and  ($cx + 1) and ycoord between ($cy - 1) and ($cy + 1)", $mysql);
$previousx = 0;
while($row = mysql_fetch_array($res))
{
   if ($previousx < $row["xcoord"] && $previousx) {
      print "</tr></tr>";
   }
   $background=$cx==$row["xcoord"]&&$cy==$row["ycoord"]?$currentlocation:$otherlocation;

   print "<td class=b {$background}><form action='' method='post'><input type='hidden' name='cx' value='{$row["xcoord"]}'><input type='hidden' name='cy' value='{$row["ycoord"]}'><input type='hidden' name='inside' value=0><input type='submit' value='{$row["name"]}'></input></form>";
   if ($cx==$row["xcoord"]&&$cy==$row["ycoord"]) {
      if (count($other_chars) > 0) {
         foreach($other_chars as $oc) {
            print "<div class=minimap_char>$oc</div>";
         }
      }
   }

   print"</td>";

   $previousx=$row["xcoord"];
}
?>
</table>


<?php
# CHaracter details
  $cname = get_character_name($mysql);
  print "<div class=char><p>You are $cname.<p></div>";


# Location Details
  print "<div class=location><p>You are ";
  if (!$inside) {
    print "outside";
    $button_text = "Enter Building";
    $new_inside = 1;
  } else {
    print "inside";
    $button_text = "Exit Building";
    $new_inside = 0;
  }
  print " $square_name [$cx, $cy].</p>";

  $num_chars = count($other_chars);
  if ($num_chars > 0) {
    print "<p>Also here ";
    if ($num_chars == 1) {
      print "is ";
    } else {
      print "are ";
    }
    for ($i = 0; $i < $num_chars; $i++) {
      print "$other_chars[$i]";
      if ($i < $num_chars - 1) {
      print ", ";
      }
      
    }
    print "</p>";
  }

# Enter/Exit Building button
  if ($square_type != "0") {
     print "<form action='' method='post'><input type='hidden' name='cx' value='$cx'><input type='hidden' name='cy' value='$cy'><input type='hidden' name='inside' value=$new_inside><input type='submit' value='$button_text'></form>";
  }
  print "</div>";

   
?>
</div>

<div class=character_manage><form method="POST" action="character_select.php">
Switch to character: <select name=c_id>
<?php
# Switching Characters
$uid = get_user_id($mysql);
$sql = "SELECT c_id, name from characters WHERE user_id = $uid";
if (!$result = @mysql_query($sql,$mysql))
     showerror();
while ($row = mysql_fetch_array($result)) {
     print ("<option value=");
     print $row["c_id"];
     print (">");
     print $row["name"];
     print ("</option>");
}
?>
</select>
<input type="submit" value="Select New Character">
</form>
Or <a href=character_gen_form.php>Create a new Character</a>.

<form method="POST" action="logout.php">
<input type="submit" value="Log Out">
</form>
</div>

</body>
</html>

