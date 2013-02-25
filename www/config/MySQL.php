<?php

require_once('/Users/louiseadennis/Personal/urbandead/fortress_zombie_game/localfiles/config.inc.php');

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

function showerror() 
{
  die("Error " . mysql_errno() . " : " . mysql_error());
}

?>