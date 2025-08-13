<?



include "../includes/config.inc.php";

include "../includes/messages.inc.php";

include "../includes/countries.inc.php";





if($action)

{

//-- Data check

$query = "select email from PHPAUCTION_users where nletter='1'";

$result = mysql_query($query);

while($row = mysql_fetch_array($result))

{

mail($row[email],$subject,$content,"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]"); 

}

if(!$result)

{

$ERR = "ERR_001";

}

else

{

Header("Location: admin.php");

exit;

}

}





?>





<HEAD> <? require('../includes/styles.inc.php'); ?>



<TITLE>Newsletter Admin</TITLE>



</HEAD>



<? include "./header.php"; ?>



<BODY bgcolor="#FFFFFF">

<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">

<TR>

<TD>

<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">

<TR>

<TD ALIGN=CENTER COLSPAN=5>

<BR>

<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
<B><? print $MSG_607; ?></B>
</FONT>
<BR>

</TD>

</TR> 



<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">



<FORM NAME=newsletter ACTION="<? print basename($PHP_SELF); ?>" METHOD="POST">

<TR>

<TD WIDTH="204" VALIGN="top" ALIGN="right">

<?print $std_font; ?>

<? print "$MSG_606 *"; ?>

</FONT>

</TD>

<TD WIDTH="486">

<INPUT TYPE=text NAME=subject SIZE=40 MAXLENGTH=255 VALUE="<? print $subject; ?>">

</TD>

</TR>



<TR>

<TD WIDTH="204" VALIGN="top" ALIGN="right">

<? print $std_font; ?>

<? print "$MSG_605 *"; ?>

</FONT>

</TD>

<TD WIDTH="486">

<TEXTAREA NAME=content COLS=45 ROWS=20><? print $content; ?></TEXTAREA>

</TD>

</TR> 

<TR>

<TD WIDTH="204" VALIGN="top" ALIGN="right">

 

</TD>

<TD WIDTH="486">

<INPUT TYPE=submit>

</TD>

</TR>

</TABLE>

<INPUT type="hidden" NAME="action" VALUE="newsletter">

</FORM>

</center>

<BR>

<BR>

<CENTER>

<FONT face="Tahoma, Arial" size="2"><A HREF="admin.php" CLASS="navigation">Admin home</A>

</CENTER>

</TD>

</TR>

</TABLE>





</TD>

</TR>

</TABLE>



<!-- Closing external table (header.php) -->

</TD>

</TR>

</TABLE>





<? include "./footer.php"; ?>





</BODY>

