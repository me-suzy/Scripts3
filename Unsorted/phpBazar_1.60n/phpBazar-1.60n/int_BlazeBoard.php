<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : int_BlazeBoard.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : BlazeBoard Interface Add Member
#
#################################################################################################



mysql_close();

mysql_connect ($forum_server,$forum_db_user,$forum_db_pass) or die(mysql_error());



    $avatar="images/blank.gif";

    mysql_db_query($forum_database, "insert into members (username, password, email, register, avatar)

	                values ('$username', '$password', '$email', '$timestamp', '$avatar')");



mysql_close();

mysql_connect($server, $db_user, $db_pass);

?>