<?
##########################################################################
## BACKUP.PHP
##########################################################################

require("admheader.php");
?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 &nbsp; Backup 
</td>
</tr>

<tr bgcolor="white">
<td width="100%">
 
Here you can make a backup. Sql dump will only work with correct set-up mysqldump client. 
It will run a mysqldump. The backup will be done to the 'Backup/Todays date' directory, 
REMEMBER to make the backup dir world writeable in order to use this tool
<p>
<b>Note:</b> Do not consider this as bulletproof, 100% secure backup, just use this as an additional help to recreate your data. You SHOULD also use other, more secure methods. <p>

<?
$backupdir_perm = fileperms("backup/");
if ($backupdir_perm == 16895)
{
        print("Permissions are set correct: <a href='backup.php?backup=1'><b>Start backup</b></a>");
}
else
{
        print(" <b>Error</b><br />Backupdir is not set to chmod 777 (writeable) ");
}
?>
<?

//------   Backup of config files ---------
if ($backup == 1)
{
 $date = date(d.m.Y);
 if (!file_exists("backup/$date"))
 {
        mkdir ("backup/$date", 0777);
 }

 if ( copy ("$full_path_to_public_program/admin/config/general.inc.php", "$full_path_to_public_program/admin/backup/$date/config.inc.php") )
{
        chmod ("backup/$date/config.inc.php", 0777);
        print "<br />Config.inc.php is copied !<br />";
}
else
{
        print " Config.inc.php NOT copied! <br />";
}

if ( copy ("$full_path_to_public_program/admin/config/header.inc.php", "$full_path_to_public_program/admin/backup/$date/header.inc.php") )
{
  		chmod ("backup/$date/header.inc.php", 0777);
        print "Header.inc.php is copied!<br />";
}
else
{
        print " Header.inc.php NOT copied! <br />";
}

if ( copy ("$full_path_to_public_program/admin/config/footer.inc.php", "$full_path_to_public_program/admin/backup//$date/footer.inc.php") )
{
        chmod ("backup/$date/footer.inc.php", 0777);
        print "Footer.inc.php is copied!<br />";
}
else
{
        print " Footer.inc.php NOT copied! <br />";
}

if ( copy ("$full_path_to_public_program/admin/db.php", "$full_path_to_public_program/admin/backup/$date/db.php") )
{
        chmod ("backup/$date/db.php", 0777);
        print "Db.php is copied!<br />";
}
else
{
        print " Db.php NOT copied! <br />";
}




 $backup_cat = "mysqldump --opt -c  -full -h $dbhost  -u $dbusr -p$dbpass $datab $cat_tbl >  backup/$date/$cat_tbl.sql";
 //print "<p>" . $backup_cat . "<p>";
 $exec=passthru($backup_cat, $ret_cat);

 $backup_ads = "mysqldump --opt -c  -full -h $dbhost  -u $dbusr -p$dbpass $datab $ads_tbl >  backup/$date/$ads_tbl.sql";
 //print "<p>" . $backup_ads . "<p>";
 $exec=passthru($backup_ads, $ret_ads);

 $backup_usr = "mysqldump --opt -c  -full -h $dbhost  -u $dbusr -p$dbpass $datab $usr_tbl >  backup/$date/$usr_tbl.sql";
 //print "<p>" . $backup_usr . "<p>";
 $exec=passthru($backup_usr, $ret_usr);


 $backup_pic = "mysqldump --opt -c  -full -h $dbhost  -u $dbusr -p$dbpass $datab $pic_tbl >  backup/$date/$pic_tbl.sql";
 //print "<p>" . $backup_pic . "<p>";
 $exec=passthru($backup_pic, $ret_pic);

 print "<p>";
 chmod ("backup/$date/$cat_tbl.sql", 0777);
 chmod ("backup/$date/$ads_tbl.sql", 0777);
 chmod ("backup/$date/$usr_tbl.sql", 0777);
 chmod ("backup/$date/$pic_tbl.sql", 0777);


 //------   Backup of SQL ---------
 if ($ret_cat==0)
 { print("<a href='backup/$date/$cat_tbl.sql'>Category table backed up</a><br />"); }
 else { print " Error: Category table <br />"; }

 if ($ret_ads==0)
 { print("<a href='backup/$date/$ads_tbl.sql'>Ad table backed up</a><br />"); }
 else { print " Error: Ad table <br />"; }

 if ($ret_usr==0)
 { print("<a href='backup/$date/$usr_tbl.sql'>User table backed up</a><br />"); }
 else { print " Error: User table <br />"; }


 if ($ret_pic==0)
 { print("<a href='backup/$date/$pic_tbl.sql'>Picture table backed up</a><br />"); }
 else { print " Error: Picture table <br />"; }

// End of backup
}

print "<p><p><b>Delete backup:</b><br />";
if ($delete AND $delcatname)
{
		$c_del = unlink("backup/$delcatname/$cat_tbl.sql");
        $a_del = unlink("backup/$delcatname/$ads_tbl.sql");
        $u_del = unlink("backup/$delcatname/$usr_tbl.sql");
        $p_del = unlink("backup/$delcatname/$pic_tbl.sql");
        $c_del = unlink("backup/$delcatname/db.php");
        $u_del = unlink("backup/$delcatname/header.inc.php");
        $c_del = unlink("backup/$delcatname/footer.inc.php");



         $cat_del = rmdir("backup/$delcatname");

         if ($cat_del == 1)
         {
                         print "$delcatname has been deleted !";
         }
         else
         {
                         print "An error occured, could not delete !";
         }


}


print "<form method='post' action='backup.php'>";
print "<select name='delcatname'>";
$dir = opendir("backup/");
while ($file = readdir($dir))
{
        if ($file <> "." AND $file <> "..")
        echo "<option>$file</option>";
}
print "</select>  <input type='submit' name='delete' value='Delete backup'></form>";
closedir($dir);
?>
 <p>
</td>
</tr>
</table>
<!-- END Table menu -->
<?
include("admfooter.php");
?>
