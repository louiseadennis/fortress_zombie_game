<?php

require_once('./config/accesscontrol.php');
require_once('./config/MySQL.php');
session_start();
sessionAuthenticate();
?>

<html>
<head>
<title>Pick a Character</title>
<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<h1>Pick a Character</h1>
<form method="POST" action="character_select.php">
<?php
  if (isset($_GET['msg'])) {
     $msg = $_GET['msg'];
     echo '<p>' . $msg . '</p>';
  } 
?>
<select>
<?php
$uid = get_user_id();
$sql = "SELECT (c_id, name) from characters WHERE uid = $uid";
if (!$result = @mysql_query($sql)) {
    header("Location: character_gen_form.php");
    exit;
}

while ($row = mysql_fetch_array($result)) {
     print ("<option value=");
     print $row["c_id"];
     print (">");
     print $row["name"];
     print ("</option>");
}
?>
</select>

Or <a href=character_gen_form.php>Create a new Character</a>.

<p><input type="submit" value="Select">
</form>
</body>
</html>

