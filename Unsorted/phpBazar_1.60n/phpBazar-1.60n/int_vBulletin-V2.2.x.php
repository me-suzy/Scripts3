<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : int_vBulletin-V2.2.x.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : vBulletin-V2.2.x Interface Add Member
#
#################################################################################################



mysql_close();

mysql_connect ($forum_server,$forum_db_user,$forum_db_pass) or die(mysql_error());



    // Add new Member

    $md5password=md5($password);

    mysql_db_query($forum_database, "insert into user (userid, usergroupid, username, password, email, joindate , styleid)

        	    values (NULL, '2', '$username', '$md5password', '$email', '$timestamp','1') ") or die(mysql_error());

    $userid=mysql_insert_id();

    // insert custom user fields

    mysql_db_query($forum_database, "INSERT INTO userfield (userid) VALUES ($userid)");



mysql_close();

mysql_connect($server, $db_user, $db_pass);



#################################################################################################

?>