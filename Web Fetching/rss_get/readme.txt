RSS_GET 1.48 By Aaron Dunlap
www. aarondunlap .com

==============
INSTALLATION
==============

1. Extract this zip (with folder names enabled)
2. Open rss_get.php and modify the settings toward the top of the script, the variables you may 
   change are indicated in comments.
3. Put rss_get.php in whatever directory you want. It's recomended that you put it in your site's root dir.
4. Put the "rss/" folder in the same dir as you put rss_get.php
5. CHMOD the "rss/" dir to 770 (or whatever you wish, so long as it's writeable and readable by scripts)

You may upload the example#.php files if you would like to see some samples.

Make sure to read through the opening comments of rss_get.php to learn about all the options you can set while calling the script.

==============
UTILIZATION
==============

In the file you wish to insert the RSS headlines into, you will need to include(); the rss_get.php

Example 1:

<HTML>
<head> <title> My RSS Feed </title> </head>
<body>

<?

include 'rss_get.php';

?>

</body>
</html>

This will output your DEFAULT rss feed (established by you in rss_get.php). If you want to add a different 
RSS feed you need to set some variables before the include:

$url : The actual, full URL of the RSS feed
$diaplayname : the text to preceed the headlines, should be something like the name of that site
$number : How many headlines to show. If the number you have is more than the number of headlines on the feed, 
it will include them all.

Example 2:

<HTML>
<head> <title> My RSS Feed </title> </head>
<body>

<?

$url = "http://games.slashdot.org/games.rss"; //URL for Slashdot Games
$displayname = "Slashdot | Games"; 
$number = 3;
include 'rss_get.php';

?>

</body>
</html>


You can include the script as many times on one page as you wish, and you can change the font formatting like so.

Example 3:

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

