<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

//--Authentication check
	if(! $HTTP_COOKIE_VARS["authenticated"]){
		Header("Location: login.php?loginfail=1");
	}
	
	//-- Set offset and limit for pagination
	
	$limit = 20;
	if(!$offset) $offset = 0;
	
	
	include "./header.php";
?>

<html>
<HEAD>

<TITLE></TITLE>

<?    require('../includes/styles.inc.php'); ?>

</HEAD>

  
<BODY bgcolor="#FFFFFF">
<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
<center>
<h2>Newsletter schreiben</h2>
<form action="sendnews.php" method="post">
<b>Nur Text:</b><br>
<textarea name="txt"  cols=80 rows=10>
Hallo {nick} oder {1},
dies ist der erste!! Newsletter.
</textarea>
<P>
<b>HTML:</b><br>
<textarea name="htmltxt" cols=80 rows=10>
Hallo {nick} oder {1},<br>
dies ist der erste!! Newsletter.<br>
</textarea>
<br>
<br>Betreff:<input type="text" name="subject">
<br>Von: <input type="text" name="von">
<br><input type="submit">
</form>
</center>
	  <CENTER>
		<A HREF="admin.php" CLASS="links">Admin Home</A>
		</CENTER>
	  <BR>
</TD>
</TR>
</TABLE>
  
  <!-- Closing external table (header.php) -->
  </TD>
  </TR>
</TABLE>
  
  
  <? include "./footer.php"; ?>