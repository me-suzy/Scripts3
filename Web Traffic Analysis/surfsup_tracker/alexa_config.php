<?

// From AlexaSurf.com
// Edit these settings to match your DB

$dbhost = 'localhost'; //most common is localhost
$dbuser = 'your_username';
$dbpass = 'your_password';
$dbname = 'your_database_name';
$site = 'www.yoursite.com'; // no following slash or http://


// DONT EDIT BELOW HERE UNLESS YOU KNOW HAT YOUR DOING 


$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');

mysql_select_db($dbname);

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

mysql_select_db($mysql);

?>