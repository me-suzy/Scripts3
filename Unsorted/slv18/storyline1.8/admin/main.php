<? 
session_start(); 

include("../base/define.inc.php");
include("../base/main.inc.php");

$dl = new TheDB();	
$dl->connect() or die($dl->getError());
$dl->debug=false;

$cl = new TheCleaner();

if( !$_SESSION['sladmin'] ) {
	print "You do not have permission to access this area";
} else {
?>

<html>
<head>
<link rel='stylesheet' href='<?=SL_ROOT_URL?>/base/html/Default/style.css'>
</head>
<body>

<?
if ($_SERVER["QUERY_STRING"] == "config")
	include("set/config.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "language")
	include("set/language.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "mancat")
	include("set/mancat.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "mansub")
	include("set/mansub.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "manstory")
	include("set/manstory.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "style")
	include("set/style.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "layout")
	include("set/layout.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "file")
	include("set/file.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "newsadd")
	include("set/newsadd.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "newsedit")
	include("set/newsedit.inc.php");
elseif ($_SERVER["QUERY_STRING"] == "newsdelete")
	include("set/newsdelete.inc.php");
else 
	include("set/main.inc.php");

?>

</body>
</html>

<?
}
?>
