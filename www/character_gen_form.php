<html>
<head>
<title>Create a Character</title>
<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<h1>Create a Character</h1>
<form method="POST" action="signup.php">
<?php
  if (isset($_GET['msg'])) {
     $msg = $_GET['msg'];
     echo '<p>' . $msg . '</p>';
  } 
?>
<table>
 <tr>
  <td>Character Name:</td>
  <td><input type="text" size="10" name="charName"></td>
 </tr>
</table>
<p><input type="submit" value="Create">
</form>
</body>
</html>

