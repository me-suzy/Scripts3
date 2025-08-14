<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

	include "includes/server.inc.php";
	
	$sql="SELECT email FROM ".$dbfix."_users WHERE id=$id";
	$res=mysql_query ($sql);
	$arr=mysql_fetch_array ($res);

	include "header.php";
	include "templates/registered_php3.html";
	include "footer.php";
?>
