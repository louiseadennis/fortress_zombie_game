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

# By default we arrive at 5,5 - will need to randomize this in future.
$cx = $_POST["cx"];
$cy = $_POST["cy"];
$square_name = "None";

if ($cx == NULL) {
   $location_res = mysql_query("select square_id from characters where c_id = $c_id", $mysql);
   while($row = mysql_fetch_array($location_res)) {
       $square = $row["square_id"];
       if ($square == 0) {
          $square = 1;
       }
       $square_res = mysql_query("select name, xcoord, ycoord from squares where square_id = $square", $mysql);
       while ($row2 = mysql_fetch_array($square_res)) {
             $square_name = $row2["name"];
             $cx = $row2["xcoord"];
             $cy = $row2["ycoord"];
       }
   }
} else {
   $square_res = mysql_query("select name, square_id from squares where xcoord = $cx and ycoord = $cy", $mysql);
   while ($row2 = mysql_fetch_array($square_res)) {
        $square_name = $row2["name"];
        $square = $row2["square_id"];
   }

   $char_update = "UPDATE characters SET square_id=$square WHERE c_id = $c_id";
   if (!mysql_query($char_update)) {
       $message = "Database Error: " . mysql_errno() . " : " . mysql_error();
       header("Location: main.php?msg=$message");
       exit;
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
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<p>' . $msg . '</p>';
} 
?>


<table><tr>
<?php

$res = mysql_query("select name, xcoord, ycoord from squares where xcoord between ($cx - 1) and  ($cx + 1) and ycoord between ($cy - 1) and ($cy + 1)", $mysql);
$previousx = 0;
while($row = mysql_fetch_array($res))
{
   if ($previousx < $row["xcoord"] && $previousx) {
      print "</tr></tr>";
       }
   $background=$cx==$row["xcoord"]&&$cy==$row["ycoord"]?$currentlocation:$otherlocation;
   print "<td class=b {$background}><form action='' method='post'><input type='hidden' name='cx' value='{$row["xcoord"]}'><input type='hidden' name='cy' value='{$row["ycoord"]}'><input type='submit' value='{$row["name"]}'></input></form></td>";
   $previousx=$row["xcoord"];
}
?>
</table>

<?php
  $cname = get_character_name($mysql);
  print "<div class=char><p>You are $cname.<p></div>";
  print "<div class=location><p>You are outside $square_name [$cx, $cy].</p></div>";
?>
</div>


<div class=character_manage><form method="POST" action="character_select.php">
Switch to character: <select name=c_id>
<?php
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

