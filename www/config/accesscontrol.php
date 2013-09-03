<?php
// Shamelessly stolen from PHP and MySQL by Hugh E. Williams and David Lane

function authenticateUser($connection, $username, $password)
{
 // Test the username and password parameter
 if (!isset($username) || !isset($password))
    return false;

 //Formulate the SQL find the user and their password
 $query = "SELECT password FROM users WHERE name = '{$username}'";

 // Execute the query
 if (!$result = @mysql_query($query, $connection))
     showerror();

 // Exactly oe row? then we have found the user
 if (mysql_num_rows($result) != 1)
  return false;
 else {
    while($row = mysql_fetch_array($result)) {
         $crypt_password = $row["password"];
         // Create a digest of the password  collected from the challenge
         // $crypt_password acts as the salt.
        $password_digest = crypt($password, $crypt_password);
        if ($password_digest == $crypt_password) {
           return true;
        } else {
          return false;
        }
    }
 }
}

// Connects to a session and checks that the user has authenticated 
// and that the remote IP address matches the address used to create
// the session
function sessionAuthenticate() 
{
 // Check if the user hasn't logged in
 if (!isset($_SESSION["loginUsername"]))
 {
   // The resquest does not identify a session
   $message = "You are not authorized to access";
   header("Location: login_form.php?msg=$message");
   exit;
 }

// Check if the request is from a different IP address to previously
 if (!isset($_SESSION["loginIP"]) ||
      ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"]))
  {
   // The request did not originate from the machine
  // that was used to create the session.
  // THIS IS PROBABLY A SESSION HIJACK ATTEMPT
  $message = "You are not authorized to acces the URL";
  header("Location: login_form.php?msg=$message");
  exit;
  }
}

// Stolen from PHP and MySQL by Hugh E. Williams and David Lane
function mysqlclean($array, $index, $maxlength, $connection) 
{
  if (isset($array["{$index}"]))
  {
    $input = substr($array["{$index}"], 0, $maxlength);
    $input = mysql_real_escape_string($input, $connection);
    return ($input);
  }
  return NULL;
}

?>
