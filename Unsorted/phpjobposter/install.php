<?php

include("config.php");

	$dbLink = @mysql_connect( $dbasehost,$dbaseuser, $dbasepassword);
	mysql_select_db($dbase);


	$query1 = "CREATE TABLE jobnum (
                   recordid int(11) NOT NULL auto_increment,
                   username text NOT NULL,
                   password text NOT NULL,
                   PRIMARY KEY  (recordid))
                   ";


        $query2 = "CREATE TABLE jobs (
                   recordid int(11) NOT NULL auto_increment,
                   jobid text NOT NULL,
                   title text NOT NULL,
                   company text NOT NULL,
                   location text NOT NULL,
                   description text NOT NULL,
                   contact text NOT NULL,
                   email text NOT NULL,
                   url text NOT NULL,
                   publishdate text NOT NULL,
                   PRIMARY KEY  (recordid))
                   ";



echo "The Table has been added to your database";



	$result=@mysql_query( $query1)  or die ("couldn't execute query1");
	$result=@mysql_query( $query2)  or die ("couldn't execute query2");
	mysql_close();

?>


