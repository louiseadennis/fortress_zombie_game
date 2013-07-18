<?php 

require_once('./config/accesscontrol.php');

# Connect to DB
require_once('./config/MySQL.php');
session_start();
sessionAuthenticate();
characterSelected();

$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_database);

# By default we arrive at 5,5 - will need to randomize this in future.
$cx = $_POST["cx"];
$cy = $_POST["cy"];
if ($cx == NULL) {
   $cx = 5;
   $cy = 5;
}
$currentlocation='bgcolor="#C0C0C0"';
$otherlocarion='bgcolor="#F5F5F5"';

?>
<html>
<head>
<title>Urban Dead 2 - The City</title>

<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<div class=main>

<table><tr>
<?php

$res = mysql_query("select name, xcoord, ycoord from squares where xcoord between ($cx - 1) and  ($cx + 1) and ycoord between ($cy - 1) and ($cy + 1)", $mysql);
$previousx = 0;
while($row = mysql_fetch_array($res))
{
   if ($previousx < $row["xcoord"] && $previousx) {
      print "</tr></tr>";
       }
   $background=$cx==$row["xcoord"]&&$cy==$row["ycoord"]?$currentlocation:$otherlocarion;
   print "<td class=b {$background}><form action='' method='post'><input type='hidden' name='cx' value='{$row["xcoord"]}'><input type='hidden' name='cy' value='{$row["ycoord"]}'><input type='submit' value='{$row["name"]}'></input></form></td>";
   $previousx=$row["xcoord"];
}
?>
</table>

<p>You are
<?php
  $cname = get_character_name($mysql);
  print $cname;
?>.
</div>


<form method="POST" action="character_select.php">
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

</body>
</html>

