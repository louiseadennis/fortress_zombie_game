# Shamelessly stolen from PHP and MySQL by Hugh E. Williams and David Lane
<?php

function authenticateUser($connection, $username, $password)
{
 // Test the username and password parameter
 if (!isset($username) || !isset($password))
    return false;

 // Create a digest of the password  collected from the challenge
 // NB.  Need to add salt
 $password_digest = crypt(trim($password));

 //Formulate the SQL find the user
 $query = "SELECT password FROM users WHERE user_name = '{$username}' AND password = '{$password_digest}'";

 // Execute the query
 if (!$result = @mysql_query($query, $connection))
    showerror();

 // Exactly oe row? then we have found the user
 if (mysql_num_rows($result) != 1)
  return false;
 else
  return true;
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
   $_SESSION["message"] = "You are not authorized to access";
   header("Location: login.html");
   exit;
 }

// Check if the request is from a different IP address to previously
 if (!isset($_SESSION["loginIP"]) ||
      ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"]))
  {
   // The request did not originate from the machine
  // that was used to create the session.
  // THIS IS PROBABLY A SESSION HIJACK ATTEMPT
  $_SESSION["message"] = "You are not authorized to acces the URL";
  header("Location: login.html");
  exit;
  }
}

?>
