<?php

# Will need to put some authentication here

# Connect to DB
$mysql = mysql_connect("localhost", "urbandead", "purplecat");
mysql_select_db("ud2");
?>
<html>
<head>
</head>
<body>
<?php

$res = mysql_query("select name from squares", $mysql);
while($row = mysql_fetch_array($res, MYSQL_NUM))
{
  foreach ($row as $attribute)
    print "{$attribute} ";
    print "<br>";
}
print('hello');

?>
</body>
</html>

