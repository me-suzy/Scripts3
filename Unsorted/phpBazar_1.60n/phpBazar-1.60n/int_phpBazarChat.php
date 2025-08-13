<?

#################################################################################################
#
#  project              : phpBazar
#  filename             : int_phpBazarChat.php
#  last modified by     : Erich Fuchs
#  supplied by          : Reseting
#  nullified by		: CyKuH [WTN]
#  purpose              : phpBazarChat Interface Add Member
#
#################################################################################################



mysql_close();

mysql_connect ($chat_server,$chat_db_user,$chat_db_pass) or die(mysql_error());



$md5password=md5($password);

mysql_db_query("$chat_database", "insert into users (nick, pass) values ('$username', '$md5password')");



mysql_close();

mysql_connect($server, $db_user, $db_pass);

?>