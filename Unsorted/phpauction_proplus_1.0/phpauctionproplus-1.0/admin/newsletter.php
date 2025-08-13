<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

include "loggedin.inc.php";

include "../includes/config.inc.php";
include "../includes/messages.inc.php";
include "../includes/countries.inc.php";


if($action)
{
	//-- Data check
	$query = "select email from PHPAUCTIONPROPLUS_users where nletter='1'";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
		mail($row[email],stripslashes($subject),stripslashes($content),"From:$SETTINGS[sitename] 										<$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]"); 	
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
<TR>
    <TD> 
		<TR>
    <TD ALIGN=CENTER COLSPAN=5>&nbsp;</TD>
</TR> 

<FORM NAME=newsletter ACTION="<? print basename($PHP_SELF); ?>" METHOD="POST">
			<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
				<TR> 
					<TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
						<? print $MSG_607; ?>
						</B></FONT></TD>
				</TR>
				<TR> 
					<TD> 
						<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
							<TR> 
								<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
									<?print $std_font; ?>
									<? print "$MSG_606 *"; ?>
								</TD>
								<TD WIDTH="486"> 
									<INPUT TYPE=text NAME=subject SIZE=40 MAXLENGTH=255 VALUE="<? print $subject; ?>">
								</TD>
							</TR>
							<TR> 
								<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
									<? print $std_font; ?>
									<? print "$MSG_605 *"; ?>
								</TD>
								<TD WIDTH="486"> 
									<TEXTAREA NAME=content COLS=45 ROWS=20><? print $content; ?></TEXTAREA>
								</TD>
							</TR>
							<TR> 
								<TD WIDTH="204" VALIGN="top" ALIGN="right"> </TD>
								<TD WIDTH="486"> 
									<INPUT TYPE=submit>
									<INPUT TYPE="hidden" NAME="action" VALUE="newsletter">
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
			</FORM>
<BR>
<BR>
<TABLE WIDTH=600 BORDER=0 CELLPADDING=4 CELLSPACING=0 ALIGN=CENTER>
<TR ALIGN=CENTER BGCOLOR=#FFFFFF>
				<TD COLSPAN=2><A HREF="admin.php" CLASS="navigation">Admin home</A> 
				</TD>
</TR>
</TABLE>


<? include "./footer.php"; ?>


</BODY>
</HTML>