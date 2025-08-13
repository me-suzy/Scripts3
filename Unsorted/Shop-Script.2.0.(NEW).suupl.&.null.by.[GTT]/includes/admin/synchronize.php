<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/


	//ADMIN :: synchronization tools
?>

<a href="admin.php"><u><?=ADMIN_MAIN_MENU;?></u></a> : <u><font><?=ADMIN_SYNCHRONIZE_TOOLS;?></font></u> :<br><br>

<p>
<?=ADMIN_SYNCR_DESC; ?>

<br><br><br>
<form action="admin.php" enctype="multipart/form-data" method=post>

<?
	if (!isset($export_db))
		echo "<input type=submit name=export_db value=\"".ADMIN_EXPORT_DB_TO_FILE."\">";
	else
		echo "<p><u><b>".ADMIN_DB_EXPORTED_TO." <a href=\"database.sql\">database.sql</a> (".round(filesize("database.sql")/1024)." kb)</b></u>";

?>



<p>
<? if (!isset($import_db)) echo ADMIN_IMPORT_FROM_SQL;?>

<p>
<?
	if (isset($imp_err)) { echo $imp_err."<br><br>"; unset($import_db); }

		if (!isset($import_db)) {
?>
<input type=file name=db><br>
<input type=submit name="import_db" value="<?=OK_BUTTON;?>">

</form>
<?
		} else if (!isset($imp_err))

			echo "<p>".ADMIN_UPDATE_SUCCESSFUL;
?>