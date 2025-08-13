<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : int_phpBB.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : phpBB V1.4.X Interface Add Member
#
#################################################################################################



$USERS_TABLE="users";



#################################################################################################



mysql_close();

mysql_connect ($forum_server,$forum_db_user,$forum_db_pass) or die(mysql_error());



// Get the next ID !!!!

    $query=mysql_db_query($forum_database, "SELECT user_id, username FROM $USERS_TABLE

			    WHERE user_id <> '-1' ORDER BY user_id DESC LIMIT 1") or die (mysql_error());

    $dbt=mysql_fetch_array($query);

    $id=$dbt[user_id]+1;



// Add new Member

    $md5password=md5($password);

    mysql_db_query($forum_database, "insert into $USERS_TABLE (user_id, username, user_password, user_email, user_regdate, user_theme)

	            	    values ('$id', '$username', '$md5password', '$email', '$timestamp','2') ") or die (mysql_error());



mysql_close();

mysql_connect($server, $db_user, $db_pass);

?>

