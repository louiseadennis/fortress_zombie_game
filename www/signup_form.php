<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<h1>Register</h1>
<form method="POST" action="signup.php">
<?php
  if (isset($_GET['msg'])) {
     $msg = $_GET['msg'];
     echo '<p>' . $msg . '</p>';
  } 
?>
<table>
 <tr>
  <td>Enter your username:</td>
  <td><input type="text" size="10" name="loginUsername"></td>
 </tr>
 <tr>
  <td>Enter your password:</td>
  <td><input type ="password" size="10" name="loginPassword"></td>
  <td>Confirm password:</td>
  <td><input type ="password" size="10" name="cloginPassword"></td>
 </tr>
 <tr>
  <td>Enter your email address:</td>
  <td><input type ="email" size="10" name="loginEmail"></td>
 </tr>
</table>
<p><input type="submit" value="Register">
</form>
</body>
</html>

