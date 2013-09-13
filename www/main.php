<?php 

require_once('./config/accesscontrol.php');

// Set up/check session and get database password etc.
require_once('./config/MySQL.php');
session_start();
sessionAuthenticate();

// Utility Functions
require_once('utilities.php');

// Check we have a character
characterSelected();

// Connect to Database
$mysql = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_database);


// Get session and post variables
$c_id = $_SESSION["c_id"];
if ( isset($_POST["cx"]) ) {
   $cx = $_POST["cx"];
   $cy = $_POST["cy"];
   $inside = $_POST["inside"];
}

// Get Location details from database.
$time_passed = deduct_ap($c_id, $mysql);
if ($cx != NULL & check_legal($c_id, $cx, $cy, $inside, $mysql) ) 
{
    // Character has taken an action

    $current_square = squareFromCoords($cx, $cy, $inside, $mysql);
    $square = $current_square['square_id'];

    // update the character database because the character has moved
    $char_update = "UPDATE characters SET square_id=$square, inside=$inside WHERE c_id = $c_id";

    if (!mysql_query($char_update)) {
        $message = "Database Error: " . mysql_errno() . " : " . mysql_error();
       	header("Location: main.php?msg=$message");
       	exit;
    } 
} else {
   $current_square = squareFromChar($c_id, $mysql);
   $square = $current_square['square_id'];
}

// Set up variable describing the current square
$cx = $current_square['xcoord'];
$cy = $current_square['ycoord'];
$inside = $current_square['inside'];
$square_type = $current_square['type'];
$square_name = $current_square['name'];

$character = get_character_details($c_id, $mysql);
$ap = $character['ap'];
?>

<html>
<head>
<title>Urban Dead 2 - The City</title>

<link rel="stylesheet" href="styles/default.css" type="text/css">
</head>
<body>
<div class=main>

<?php
# Display any error, warning or debugging messages passed with  the form.
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<p>' . $msg . '</p>';
} 

if ($ap > 0) {
    printMiniMap($current_square, $c_id, $mysql);

    printCharacterDetails($character, $mysql);

    printLocationDetails($current_square, $c_id, $mysql);
} else {
    print "<div class=warning><p>You have $ap action points.  You are exhausted and can go no further ... $time_passed</p></div>";
}
?>
</div>

<div class=character_manage><form method="POST" action="character_select.php">
Switch to character: <select name=c_id>
<?php
# Switching Characters
$uid = get_user_id($mysql);
$sql = "SELECT c_id, name from characters WHERE user_id = $uid";
if (!$result = @mysql_query($sql,$mysql))
     showerror();
while ($row = mysql_fetch_array($result)) {
     print ("<option value=");
     print $row["c_id"];
     print (">");
     print $row["name"];
     print ("</option>");
}
?>
</select>
<input type="submit" value="Select New Character">
</form>
Or <a href=character_gen_form.php>Create a new Character</a>.

<form method="POST" action="logout.php">
<input type="submit" value="Log Out">
</form>
</div>

</body>
</html>

<?php

function printMiniMap($current_square, $c_id, $mysql)
{

    $currentlocation='bgcolor="#C0C0C0"';
    $otherlocation='bgcolor="#F5F5F5"';

    $cx = $current_square['xcoord'];
    $cy = $current_square['ycoord'];
    $inside = $current_square['inside'];

    $res = mysql_query("select name, xcoord, ycoord, square_id from squares where xcoord between ($cx - 1) and  ($cx + 1) and ycoord between ($cy - 1) and ($cy + 1)", $mysql);

    print "<table><tr>";

    $previousx = 0;
    while($row = mysql_fetch_array($res))
    {
        $sq_name = $row["name"];
        $sq_x = $row["xcoord"];          
        $sq_y = $row["ycoord"];
        $sq_id = $row["square_id"];

        if ($previousx < $sq_x && $previousx) {
	    print "</tr></tr>";
        }

        $background=$cx==$sq_x&&$cy==$sq_y?$currentlocation:$otherlocation;

        // The move button
        print "<td class=b {$background}>";
        print "<form action='' method='post'>";
        print "<input type='hidden' name='cx' value='{$sq_x}'>";
        print "<input type='hidden' name='cy' value='{$sq_y}'>";
        print "<input type='hidden' name='inside' value=0>";
        print "<input type='submit' value='{$sq_name}'></input>";
        print "</form>";

        if ($cx==$sq_x&&$cy==$sq_y) {
	    printMiniMapCharList($sq_id, $inside, $c_id, $mysql);
        } else {
            if ($inside == 0) {
                printMiniMapCharList($sq_id, $inside, $c_id, $mysql);
            }
        }

        print"</td>";

        $previousx=$sq_x;
    }
    print "</table>";
}

function printMiniMapCharList($sq_id, $inside, $c_id, $connection) 
{
    $other_chars = otherCharactersAt($sq_id, $inside, $c_id, $connection);
    if (count($other_chars) > 0) {
        foreach($other_chars as $oc) {
            print "<div class=minimap_char>$oc</div>";
        }
    }
}

function printCharacterDetails($character, $connection)
{
  $name = $character['name'];
  $ap = $character['ap'];

  print "<div class=char><p>You are $name.  You have $ap action points.</p></div>";
}

function printLocationDetails($current_square, $c_id, $connection) 
{
    $cx = $current_square['xcoord'];
    $cy = $current_square['ycoord'];
    $inside = $current_square['inside'];
    $square_type = $current_square['type'];
    $square_name = $current_square['name'];
    $square = $current_square['square_id'];

    // State Location
    print "<div class=location><p>You are ";
    if (!$inside) {
        print "outside";
        $button_text = "Enter Building";
        $new_inside = 1;
    } else {
        print "inside";
        $button_text = "Exit Building";
        $new_inside = 0;
    }
    print " $square_name [$cx, $cy].</p>";

    //  List other characters
    $other_chars = otherCharactersAt($square, $inside, $c_id, $connection);

    $num_chars = count($other_chars);
    if ($num_chars > 0) {
        print "<p>Also here ";
        if ($num_chars == 1) {
          print "is ";
        } else {
          print "are ";
        }
        for ($i = 0; $i < $num_chars; $i++) {
            print "$other_chars[$i]";
            if ($i < $num_chars - 1) {
                print ", ";
            }
        }
      print "</p>";
    } else {
      print "<p>No other characters here.</p>";
    }

    // Enter/Exit Building button
    if ($square_type != "0") {
        print "<form action='' method='post'>";
        print "<input type='hidden' name='cx' value='$cx'>";
        print "<input type='hidden' name='cy' value='$cy'>";
        print "<input type='hidden' name='inside' value=$new_inside>";
        print "<input type='submit' value='$button_text'>";
        print "</form>";
     }

     print "</div>";
}

?>