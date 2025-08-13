<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



	//ADMIN :: news module


	//current time
	$timestamp = time();

	$stamp = microtime();
	$stamp = explode(" ",$stamp);
	$stamp = $stamp[1];

	$s = strftime("%d", $timestamp).".".strftime("%m", $timestamp).".".strftime("%Y", $timestamp);

	if (isset($news_save))
	{
		db_set_identity(NEWS_TABLE);
		db_query("INSERT INTO ".NEWS_TABLE." (add_date, Body, add_stamp) VALUES ('$date','$body',$stamp)") or die (db_error());
		if (isset($send)) //send news to subscribers
		{
			include("./cfg/settings.inc");

			$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE Login='".ADMIN_LOGIN."'") or die (db_error());
			$em = db_fetch_row($q);
			$em = $em ? $em[0] : "";

			$q = db_query("SELECT Email FROM ".MAILING_LIST_TABLE."") or die (db_error());
			while ($row = db_fetch_row($q))
			  mail($row[0],EMAIL_NEWS_OF." $shopname",$body."\n\n".EMAIL_SINCERELY.", $shopname!\n$shopurl","From: \"$shopname\"<$em>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$em>");
			$q = db_query("SELECT Email FROM ".CUSTOMERS_TABLE." WHERE subscribed4news=1") or die (db_error());
			while ($row = db_fetch_row($q))
			  mail($row[0],EMAIL_NEWS_OF." $shopname",$body."\n\n".EMAIL_SINCERELY.", $shopname!\n$shopurl","From: \"$shopname\"<$em>;\n".EMAIL_MESSAGE_PARAMETERS."\nReturn-path: <$em>");
		}
	}

	if (isset($kill))
		db_query("DELETE FROM ".NEWS_TABLE." WHERE NID=$kill") or die (db_error());


?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_NEWS;?></font></u> :<br><br>

<?
	$q = db_query("SELECT NID, add_date, Body FROM ".NEWS_TABLE." ORDER BY add_stamp DESC") or die (db_error());
	echo "<table border=0 bgcolor=#CCCCCC cellspacing=1 width=50%><tr align=center bgcolor=#EEEEEE><td colspan=3>".ADMIN_NEWS."</td></tr>";
	while ($row = db_fetch_row($q))
	{
		echo "<tr bgcolor=white><td width=1%>$row[1]</td>";
		echo "<td width=100%>".nl2br(str_replace("<","&lt;",$row[2]))."</td>";
		echo "<td width=1%><a href=\"admin.php?path=news&kill=$row[0]\"><img border=0 src=\"images/remove.jpg\"></a></td></tr>";
	}

?>


<tr align=center bgcolor=white>
<td colspan=3>
<form action="admin.php?path=news" method=POST><br>

</center><b><?=ADMIN_NEW_NEWSARTICLE;?></b><center><br>
<table>
<tr>
<td><?=ADMIN_CURRENT_DATE;?>:</td><td><input type=text name=date value="<?=$s; ?>"></td>
</tr><tr>
<td align=right valign=top><?=DISCUSSION_BODY;?>:<br>(not HTML)</td><td>

<textarea name=body cols=40 rows=10>
</textarea>

</td>
</tr>
</table>

<p>
<input type=checkbox name=send> <?=ADMIN_SEND_NEWS_TO_SUBSCRIBERS;?><br>
</p>


<input type=submit value="<?=OK_BUTTON;?>">
<input type=hidden name=news_save value=1>
</form>
</td>
</tr>
</table>


