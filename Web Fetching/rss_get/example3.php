
<HTML>
<head> <title> My RSS Feeds </title> </head>
<body>

<font face="Verdana" size="2">
<?

$url = "http://games.slashdot.org/games.rss"; //URL for Slashdot Games
$displayname = "Slashdot | Games"; 
$number = 3;
include 'rss_get.php';

?>
</font>
<br><br>
<font face="Arial" size="2">
<?

$url = "http://www.wilwheaton.net/mt/index.xml"; //URL for WilWheaton.Net's XML feed
$displayname = "Slashdot | Games"; 
$number = 3;
include 'rss_get.php';

?>
</font>

</body>
</html>