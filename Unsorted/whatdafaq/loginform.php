<?php
include ( "header.php" );
	print "<form action=$PHP_SELF method=\"post\"><table align=center width=\"590\"><tr><td colspan=2><b>WhatDaFaq Administrators Only</b></td></tr>";
  	if ( $failed == "yes" ) {
	   echo ( "<tr><td colspan=2 align=center>Login Failed. Try again.</td></tr>" );
	} 	
	print "<tr><td class=\"small\"><b>Login Name:</b></td><td><input class=\"small\" type=text name=userid></td></tr>";
	print "<tr><td class=\"small\"><b>Password:</b></td><td><input class=\"small\" type=password name=password></td></tr><tr><td>&nbsp</td><td><input type=submit value=\"Log In\" class=\"small\"></td></tr></form></table>";
	print "<tr><td colspan=\"2\" class=\"small\" align=\"center\">Login with <b>userid:</b> admin <b>password:</b> whatdafaq</td></tr>";
include ( "footer.php" );	
?>	

