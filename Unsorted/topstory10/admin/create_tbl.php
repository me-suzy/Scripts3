<?

include("../inc/config.php");
include("login.php");
$ctQuery="CREATE TABLE `".$tst["tbl"]["articles"]."` (
`id` int(11) NOT NULL auto_increment,
`heading` varchar(255) NOT NULL default '',
`body` text NOT NULL,
`name` varchar(15) NOT NULL default '',
`surname` varchar(20) NOT NULL default '',
`country` varchar(50) NOT NULL default '',
`email` varchar(100) NOT NULL default '',
`website` varchar(100) NOT NULL default '',
`datePosted` bigint(20) NOT NULL default '0',
`userIp` varchar(30) NOT NULL default '',
`hits` bigint(20) NOT NULL default '0',
`status` tinyint(1) NOT NULL default '0',
PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;";

if(mysql_query($ctQuery)) {
	echo "<center>Creating table <i>".$tbl."</i> .... Done !";
	echo "<br>".$tst["lang"]["redirectingIndex"];
	echo '<meta http-equiv=refresh content=\'5; url=index.php\'>';
	echo '<br><br><a class="link1"  href="index.php">'.$tst["lang"]["click2redirectIndex"].'</center></a>';
}
else {
	echo "<br><br>Creating table <i>".$tbl."</i> .... Failed !";
}

?>