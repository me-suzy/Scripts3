<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: auxiliary pages
	if (!isset($page)) $page = "aux1";
?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_AUX_INFO;?></font></u> :<br><br>

<table border=0 bgcolor=#CCCCCC cellspacing=1>
<tr align=center bgcolor=#EEEEEE>
<td><?=ADMIN_AUX_INFO;?> (<?=$page; ?>)</td>
</tr>
<tr align=center bgcolor=white>
<td>
<form action="admin.php" method=POST><br>
<p>&nbsp;&nbsp;&nbsp;&nbsp;<textarea name=auxpage cols=70 rows=15>
<?
	if (file_exists("cfg/$page")) $f = file("cfg/$page");
	else $f="";

	for ($i=0; $i<count($f); $i++) echo str_replace("<","&lt;",trim(stripslashes($f[$i]))."\n");
?>
</textarea>&nbsp;&nbsp;&nbsp;&nbsp;</p>
<input type=submit value="<?=SAVE_BUTTON;?>">
<input type=hidden name=aux_save value=1>
<input type=hidden name=page value="<?=$page;?>">
</form>
</td>
</tr>
</table>
