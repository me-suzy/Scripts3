<html>

<head></head>
<body>

<h3>Upgrade database of PHP Classifieds 6.04 and newer to 6.08</h3>
<a href="up608.php?start=1">Start upgrade</a><p>
<a href="up608.php?start=1&photo">Spesical upgrade</a> (will insert reference of file images into the database,
needed it you upgrade between 6.04 and 6.06 and used flat/file images. Do not use this if you have run up607 before!)
<?
if ($start)
{
	include_once("admin/inc.php");
	
	$my1 = mysql_query("ALTER TABLE $usr_tbl ADD `verify` INT"); 
	$my2 = mysql_query("ALTER TABLE $ads_tbl ADD `notify` INT"); 
	
	$my3 = mysql_query("ALTER TABLE $usr_tbl CHANGE `registered` `registered` VARCHAR(8) DEFAULT '0'"); 	
	$my4 = mysql_query("ALTER TABLE $usr_tbl add last_login varchar(12) default '0'");
	$my5 = mysql_query("ALTER TABLE $usr_tbl add num_logged int(11) default '0'");
	$my6 = mysql_query("ALTER TABLE $usr_tbl CHANGE verify verify VARCHAR(30) DEFAULT '0'");
	$my7 = mysql_query("ALTER TABLE $usr_tbl add months INT DEFAULT '0'");
	$my8 = mysql_query("ALTER TABLE $usr_tbl add approve INT DEFAULT '0'"); 
	$my9 = mysql_query("ALTER TABLE $usr_tbl add approve_from VARCHAR(8) DEFAULT '0'");
	$my10 = mysql_query("ALTER TABLE $usr_tbl add request_credits INT DEFAULT '0'");
	
	print "<p>Debub 
	My1: $my1, My2: $my2, My3: $my3,My4: $my4,My5: $my5,My6: $my6,My7: $my7,My8: $my8,My9: $my9,My10: $my10
		
	<p>";
	print "<p>Done !<p>";
}

if ($photo)
{

	$string = "select * from $ads_tbl where img_stored <> ''";
	$result = mysql_query($string);
	
	while ($myrow = mysql_fetch_array($result))
	{
	
	 $siteid = $myrow["siteid"];			
	 $img_stored = $myrow["img_stored"];
	
	 $sql = "insert into $pic_tbl (pictures_siteid, filename, imagew,imageh) values ('$siteid','$img_stored',150,150)";
	 print $sql . "<br />";
	 $res1 = mysql_query($sql);
	 
	 $sql = "update $ads_tbl set picture = picture + 1 where siteid='$siteid'";
	 print $sql . "<br />";
	 $res2 = mysql_query($sql);
	 print "<p>Done !<p>"; 
	}
}
?>


</body></html>
