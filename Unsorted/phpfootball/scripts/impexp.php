<?php 
/*
***************************************************************************
Parameters :

action import,export
dbfield
***************************************************************************
*/
?>

<?php include("inc.functions.php"); ?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>

<?php
//global variables
$fname = "backup.sql";
$uri = $_SERVER["PHP_SELF"];
@set_time_limit(1200);
?>

<?php 
//make sure file is writable if not chmod it
do_chmod($fname);
//empty previous backup/restore
$file_pointer = fopen("$fname", "w");
fclose($file_pointer);
?>

<p>&nbsp;</p>
<table width="75%" class=tddd align="center">
  <tr> 
    <td colspan="4">Data Management</td>
  </tr>
<tr><td class=tdark align=center>&nbsp;</td></tr>
  <tr> 
    <td class=form colspan="4" align=center>
<?php
//
//data restore section
//
//data restore notice
if($action == "import" && !$proceed){
	echo "You are about to replace all user introduced data for $dbfield with a backup<br>All the user introduced data curently available for $dbfield will be replaced";
	echo "<form enctype=multipart/form-data action=$uri method=post><input type=hidden name=MAX_FILE_SIZE value=30000><input class=input type=file name=imp_file>&nbsp;<input type=hidden name=action value=$action><input type=hidden name=dbfield value=$dbfield><input type=submit class=button name=proceed value=Proceed></form>";
}
//data restore code
if($action == "import" && $proceed == "Proceed"){
	if ($imp_file != ""){
		$imp_file_tmpname = $_FILES['imp_file']['tmp_name'];
		if($imp_file_tmpname != ""){
				$size = get_filesize($imp_file);
				move_uploaded_file($imp_file_tmpname,$fname);
				do_import($fname);
				echo "Data restore sucesfully completed from $imp_file_name $size";
		}else{echo "Error uploading backup file";}
	}else{echo "You must select a backup file";}
}
//
//data backup section
//
//data backup notice
if($action == "export" && !$proceed){
	echo "You are about to backup all user introduced data for $dbfield<br>It is advised to do this before updating to a newer PHPFootball version";
	echo "<form action=$uri method=post><input type=hidden name=action value=$action><input type=hidden name=dbfield value=$dbfield><input type=submit class=button name=proceed value=Proceed></form>";
}
//data backup code
if($action == "export" && $proceed == "Proceed"){
//make news backup
	if($dbfield == "news"){
	$dbtable = "News";
	do_backup($dbtable,$fname);
	}
//make league backup
	if($dbfield == "league"){
	$leagues = array("Agegroups","Divisions","Fixtures","Games","Leagues","Teams","Venues");
		foreach ($leagues as $league){
			$dbtable = "$league";
			do_backup($dbtable,$fname);
		}
	}
	echo "$dbfield data backup temporary saved as <a href=$fname target=_blank>$fname</a><br>Save Target As and store the data backup for later usage";
}

?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
