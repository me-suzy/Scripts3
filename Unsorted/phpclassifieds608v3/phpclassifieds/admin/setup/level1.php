<form method="POST" action="install.php">
<input type="hidden" name="level" value="1">
<b>DATABASE INFO</b><br />
<small>In order to use PHP CLASSIFIEDS,
you must have access to a MySql database, and you must
have created a database where the below user will have access.
This info must be correct.
<p>
The DB.PHP file will be installed in <b><?  print("$admindir"); ?></b>.</small><p>


<table>
<tr><td><small>Hostname (often localhost) :</small></td><td><input type="text" name="hostname" size="20"></td></tr>
<tr><td><small>DB Username : </small></td><td><input type="text" name="db_username" size="20"></td></tr>
<tr><td><small>DB Password : </small></td><td><input type="text" name="db_password" size="20"></td></tr>
<tr><td><small>Databasename: </small></td><td><input type="text" name="db_name" size="20"></td></tr></table>
<input type="submit" value="Create databasefile" name="submit">
</form>

<?
if ($submit)
{
     if (file_exists("$admindir/db.php"))
     {
         unlink ("$admindir/db.php");
     }

     $fd = fopen( "$admindir/db.php", "w+" );

     $str_gen = "<? mysql_connect (\"$hostname\",\"$db_username\",\"$db_password\");\nmysql_select_db (\"$db_name\");\n
     \$datab='$db_name';\n
     \$dbusr='$db_username';\n
     \$dbpass='$db_password';\n
     \$dbhost='$hostname';\n
     ?>";

     $len_gen = strlen( $str_gen );
     fwrite( $fd, $str_gen, $len_gen );
     fclose( $fd );
     print("Writed db.php to $admindir/db.php.<br />");
     
     print "If you recieve any errors below, you have given the wrong: HOSTNAME, DATABASENAME, USERNAME or PASSWORD
     for MySql user, and you must go back in your browser and try again!<p />";
     
     
     print("<a href='install.php?level=2'>Go on to level 2</a>");
}
?>
