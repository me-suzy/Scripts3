<?
if ($submit)
	{
	$dbport = "3306";	// default is 3306; change this if different
	$dbname = "silverice"; 	// name of the database
  $dbhandle = mysql_connect($hostname, $username, $password) or die("could not connect!");
  mysql_select_db($dbname,$dbhandle);

  echo "okie! got a connection";


  $dbhandle = mysql_connect($hostname, $username, $password);
   mysql_select_db($dbname,$dbhandle);

$definition="
CREATE TABLE accepted (
  id smallint(100) NOT NULL auto_increment,
  url varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  name varchar(100) NOT NULL default '',
  pics varchar(100) NOT NULL default '',
  category varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
)";
mysql_query($definition);

$definition="
CREATE TABLE auth (
  auth varchar(255) NOT NULL default '',
  PRIMARY KEY  (auth),
  UNIQUE KEY auth (auth),
  KEY auth_2 (auth)
)";
mysql_query($definition);
$definition="
CREATE TABLE blacklist (
  domain varchar(100) NOT NULL default '',
  UNIQUE KEY domain (domain),
  KEY domain_2 (domain)
)";
mysql_query($definition);
$definition="
CREATE TABLE published (
  id smallint(100) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  pics varchar(100) NOT NULL default '',
  category varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
)";
mysql_query($definition);
$definition="
CREATE TABLE submitted (
  id smallint(100) NOT NULL auto_increment,
  url varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  name varchar(100) NOT NULL default '',
  pics varchar(100) NOT NULL default '',
  category varchar(100) NOT NULL default '',
  date varchar(100) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY id_2 (id)
) ";
mysql_query($definition);

$ad2= "<br><a href=http://stats.adultrevenueservice.com/wmrefer.php?182619><img src=http://stats.adultrevenueservice.com/arsban.php?but8 border=0> <br><font face=\"verdana, helvetica\">Adult Revenue Service does. Signup now.</a>";

  $query = "SELECT password('$ad2') as password";

  $result = mysql_db_query ($dbname, $query);

 if ($result){

  $numOfRows = mysql_num_rows ($result);

  for ($i = 0; $i < $numOfRows; $i++){

    $password = mysql_result ($result, $i, "password");

}}

  $query = "insert into auth values ('$password')";
  $result = mysql_db_query ($dbname, $query);
  if ($result) {
  echo "<h1> Great! Everything is done!</h2>";  

  
  }
  else {
    echo mysql_errno().": ".mysql_error()."<BR>";
  }



}
else {
echo "This script attempts to create all the mySQL tables necessary for the SilveriCE TGP script.
	<br> Please enter your mySQL username and password and host. If you do not have these, you will
	have to speak to your host / admin";
echo "<B>THIS SCRIPT IS IF YOU CANNOT CREATE YOUR OWN DATABASE. YOU <i>MUST</i> CONTACT YOUR ADMINISTRATOR AND ASK THEM TO CREATE A DATABASE CALLED 'silverice'";
echo "
    <form method=\"post\" action=\"setup.php\">
    <input type=\"text\" name=\"hostname\" value=\"hostname\"><br>
    <input type=\"text\" name=\"username\" value=\"username\"><br>
    <input type=\"text\" name=\"password\" value=\"password\"><br>
    <input type=\"submit\" name=\"submit\" value=\"submit\"></form>";
}
?>
