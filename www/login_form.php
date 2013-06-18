<html>
<head>
<title>Log In</title>
<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<h1>Log In</h1>
<form method="POST" action="logincheck.php">
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
 </tr>
</table>
<p><input type="submit" value="Log In">
</form>

<p>Or <a href=signup_form.php>Sign Up</a></p>
</body>
</html>

