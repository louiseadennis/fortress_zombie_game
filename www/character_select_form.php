<?php

require_once('./config/accesscontrol.php');
require_once('./config/MySQL.php');
require_once('utilities.php');

session_start();
sessionAuthenticate();
$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
if (!mysql_select_db($mysql_database))
  showerror();
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
<select name="c_id">
<?php
$uid = get_user_id($mysql);
$sql = "SELECT c_id, name from characters WHERE user_id = $uid";
if (!$result = @mysql_query($sql,$mysql)) {
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

