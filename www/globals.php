<?php
require_once('utilities.php');

function get_global($name, $connection) 
{
 $sql = "SELECT value FROM globals WHERE name = '{$name}'";

 if (!$result = mysql_query($sql, $connection)) {
   print $sql;
   showerror();
 }

 while ($row=mysql_fetch_array($result)) {
   $global = $row["value"];
   return $global;
 }
}

function get_max_chars($connection) 
{
  return get_global('max_chars', $connection);
}

function get_max_ap($connection)
{
  return get_global('max_ap', $connection);
}

function get_ap_refresh_rate($connection)
{
  return get_global('ap_refresh_rate', $connection);
}

?>