@names = ("Hartnell", "Hurndell", "Troughton", "Pertwee", "Baker", "Davison", "McCoy", "McGann", "Eccleston", "Tennant", "Smith", "Lambert", "Wiles", "Lloyd", "Bryant", "Sherwin", "Letts", "Hinchcliffe", "Williams", "Nathan-Turner", "Ware", "Collinson", "Liggat", "Simpson", "Wilson", "Bennett", "Schweitzer", "Wohlenberg", "Wilson", "Paul", "Beaton", "Segal", "Wright", "Davies", "Gardner", "Young", "Moffat", "Wenger", "Willis", "Skinner", "Foreman", "Ford", "Hill", "Chesterton", "Russell", "O'Brien", "Taylor", "Purves", "Kingdom", "Marsh", "Chaplet", "Lane", "Wills", "Jackson", "Craze", "McCrimmon", "Hines", "Waterfield", "Watling", "Heriot", "Padbury", "Lethbridge-Stewart", "Courtney", "Shaw", "John", "Grant", "Manning", "Sladen", "Benton", "Levene", "Yates", "Franklin", "Sullivan", "Marter", "Jameson", "Leeson", "Tamm", "Ward", "Waterhouse", "Sutton", "Fielding", "Strickson", "Turlough", "Flood", "Brown", "Bryant", "Bush", "Langford", "Aldred", "Holloway", "Ashbrook", "Tyler", "Piper", "Mitchell", "Langley", "Harkness", "Barrowman", "Clarke", "Noble", "Tate", "Jones", "Agyeman", "Peth", "Minogue", "Lake", "Morrissey", "Farisi", "Tshabalala", "da Souza", "Ryan", "Brooke", "Duncan", "Mott", "Cribbins", "Wildthyme", "Pond", "Gillan", "Darvill", "Song", "Kingston", "Owens", "Corden", "Whalen", "Turner", "Eugenides", "Attolia", "Sounis", "Eddis", "Hensall", "Cutter", "Hart", "Murray", "Wakeling", "Temple", "Lee-Potts", "Maitland", "Spearitt", "Haines", "Hodges", "Leek", "Rouass", "Page", "Becker", "Lester", "Miller", "Theobald", "Aubrey", "Mansfield", "Quinn", "Flemyng", "Parker", "Merchant", "Anderson", "Burton", "McMenamin", "Kearney", "Bradley", "Siddig", "Lewis", "Platt", "Magrs", "Stone", "Richards", "Mortimer", "Daly", "Martin", "Curry", "Wyatt", "Davis", "Hulke", "Pemberton", "Cotton", "Peel", "Black", "Johns", "Aaronovitch", "Saward", "Lydecker", "Hinchcliffe", "Ling", "Strutton", "Whittaker", "Orman", "Blum", "Hale", "Cornell");

$index = @names;
$name = $names[int(rand($index))];

print "Name: $name\n";

$type = int(rand(1000));
$building = 0;

if ($type < 25) {
    $building = 1;
} elsif ($type < 50) {
    $building = 2;
} elsif ($type < 75) {
    $building = 3;
} elsif ($type < 100) {
    $building = 5;
} elsif ($type < 125) {
    $building = 6;
} elsif ($type < 150) {
    $building = 7;
} elsif ($type < 175) {
    $building = 8;
} elsif ($type < 200) {
    $building = 9;
} elsif ($type < 225) {
    $building = 10;
} elsif ($type < 250) {
    $building = 11;
} elsif ($type < 275) {
    $building = 12;
} elsif ($type < 300) {
    $building = 13;
} elsif ($type < 325) {
    $building = 14;
} elsif ($type < 375) {
    $building = 15;
} elsif ($type < 400) {
    $building = 16;
} elsif ($type < 425) {
    $building = 17;
} elsif ($type < 450) {
    $building = 18;
} elsif ($type < 475) {
    $building = 19;
} elsif ($type < 500) {
    $building = 20;
} elsif ($type < 525) {
    $building = 21;
}


print "Type: $building \n";

$xcoord = int(rand(10));
$ycoord = int(rand(10));

print "Mall at: $xcoord, $ycoord\n";
