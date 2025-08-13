<?#//v.1.0.0
	session_start();
	
	if($HTTP_POST_VARS[prev] == "<< PREV")
	{
		Header("Location: upgrade21_pro10_2.php");
		exit;
	}
	elseif($HTTP_POST_VARS[next] == "UPGRADE")
	{
		Header("Location:upgrade21_pro10_4.php");
		exit;
	}
	
?>	
<html>
<head>
<title>Phpauction Upgrade Script</title>
</head>
<body BGCOLOR="brown1" TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<TABLE ALIGN=CENTER CELLPADDING=1 CELLSPACING=0 BORDER=0 BGCOLOR=white WIDTH=700>
<FORM ACTION="<?=basename($PHP_SELF)?>" METHOD=post>
<TR>
	<TD>
		<TABLE WIDTH=100% CELLPADDING=4 CELLSPACING=0 BORDER=0 BGCOLOR=white>
		<TR>
			<TD>
			<IMG SRC=images/logo.gif>
			&nbsp;&nbsp;
			<H1>Phpauction Pro Upgrade Script - STEP 3
			</H1>
			<BR>
			<BR>
			<FONT FACE=Helvetica SIZE=3>
			The upgrade script is ready for the last step. Please press the <FONT FACE=Courier>UPGRADE</FONT>
			button below to have Phpauction Pro 1.0 installed on your server.
			<BR>
			<BR>
			You can still make changes to your configuration by going back using the <FONT FACE=Courier><< PREV</FONT>
			button.
			<BR><BR>
			<CENTER>
			<INPUT TYPE=hidden NAME=action VALUE=process>
			<INPUT TYPE=submit NAME=prev VALUE="<< PREV">
			<INPUT TYPE=submit NAME=next VALUE="UPGRADE">
			<BR><BR><BR>
			Copyright &copy; 2002, Phpauction.org			
			</TD>
		</TR>
		</TABLE>
	</TD>
</TR>
</FORM>
</TABLE>
</body>
</html>