<?php

# Will need to put some authentication here

# Connect to DB
$mysql = mysql_connect("localhost", "urbandead", "purplecat");
mysql_select_db("ud2");

# By default we arrive at 5,5 - will need to randomize this in future.
$cx = $_GET["cx"];
$cy = $_GET["cy"];
if ($cx == NULL) {
   $cx = 5;
   $cy = 5;
}
?>
<html>
<head>
<title>Urban Dead 2 - The City</title>

<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<div class=main>
<table><tr>
<?php

$res = mysql_query("select name, xcoord, ycoord from squares where xcoord between ($cx - 1) and  ($cx + 1) and ycoord between ($cy - 1) and ($cy + 1)", $mysql);
$counter = 0;
while($row = mysql_fetch_array($res))
{
   print "<td class=b><a href=main.php?cx={$row["xcoord"]}&cy={$row["ycoord"]}>{$row["name"]}</a></td>";
   $counter++;
   if ($counter == 3) {
      print "</tr></tr>";
      $counter = 0;
       }
}


?>
</table>
</div>
</body>
</html>

