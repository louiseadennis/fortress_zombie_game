<?php // signup.php
# Connect to DB
require_once('./config/MySQL.php');

function showerror()
{
  die("Error " . mysql_errno(). ":" . mysql_error());
}

function error($message)
{
  die($message);
}

if (!isset($_POST['submitok'])) {
    // Display the user signup form
    ?>
<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title> Zombie Game Sign Up </title>
  <meta http-equiv="Content-Type"
    content="text/html; charset=iso-8859-1"
</head>
<body>

   <p>All fields are compulsory.</p>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align="right">
            <p>User Name</p>
        </td>
        <td>
            <input name="uname" type="text" maxlength="100" size="25" />
        </td>
    </tr>
    <tr>
        <td align="right">
            <p>E-Mail Address</p>
        </td>
        <td>
            <input name="email" type="text" maxlength="100" size="25" />
        </td>
    </tr>
    <tr>
        <td align="right">
            <p>Password</p>
        </td>
        <td>
            <input name="pwd" type="password" />
        </td>
    </tr>
    <tr>
        <td align="right" colspan="2">
            <hr noshade="noshade" />
            <input type="reset" value="Reset Form" />
            <input type="submit" name="submitok" value="   OK   " />
        </td>
    </tr>
</table>
</form>

</body>
</html>
<?php
    } else {
    // Process signup submission
  if (strip_tags($_POST['uname'])=='' or strip_tags($_POST['email'])=='' or strip_tags($_POST['pwd']) == '') {
    error('One or more required fields were left blank.<br>'.
              'Please fill them in and try again.');
  } else {
    $uname = strip_tags($_POST['uname']);
    $email = strip_tags($_POST['email'])."\r";
    $pwd = strip_tags($_POST['pwd']);

    $connection = mysql_connect($mysql_host,&mysql_user,$mysql_password);

    mysql_select_db($mysql_database, $connection) or showerror();

    // Check for existing user with the new id
    $sql = "SELECT * FROM users WHERE uname = $uname";
    $result = mysql_query($sql);
    if (!$result) {	
	$pass = md5(trim($pwd));


	$sql = "INSERT INTO users (uname,email,passwor) VALUES ($uname, $email, $pwd)";
	if (!mysql_query($sql)) {
        error('A database error occurred in processing your '.
              'submission.<br>If this error persists, please '.
              'contact ?????<br>' . mysql_error());
	} else {
	        error('This user name is already taken'.
        	      'Please fill in the form again.');
}


?>