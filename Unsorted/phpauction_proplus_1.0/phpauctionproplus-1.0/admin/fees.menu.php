<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

?>
<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING="0" BORDER="0">
	<FORM NAME=changefeetype ACTION=<?=basename($PHP_SELF)?> METHOD=post>
	<?
		if($SETTINGS[feetype] == "pay")
		{
			$SW = "'prepay'";
		}
		else
		{
			$SW = "'pay'";
		}
	?>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"><FONT FACE="Tahoma, Verdana" SIZE="2"> 
			<? print $MSG_468; ?>
			<? print $MSG_469; ?>&nbsp;<B><SPAN CLASS="yellow"><?=$SETTINGS[feetype]?></SPAN></B>
			</FONT>&nbsp;
			<INPUT TYPE=hidden NAME=SWITCH VALUE=<?=$SW?>>
			<INPUT TYPE=submit NAME=switchfeetype VALUE="<?=$MSG_479?><?=$SW?>">
		</TD>
	</TR>
	</FORM>
	<TR> 
		<TD WIDTH="3%" colspan=2>&nbsp;</TD>
	</TR>
	<TR> 
		<TD WIDTH="3%"><B></B></TD>
		<TD WIDTH="97%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
			<? print $MSG_366; ?>
			</FONT></B></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="./images/ball.gif" WIDTH="12" HEIGHT="12"><A HREF="./signupfee.php" CLASS="links"> 
			<? print $MSG_417; ?>
			</A></FONT></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%"><B></B></TD>
		<TD WIDTH="97%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
			<? print $MSG_389; ?>
			</FONT></B></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="./images/ball.gif" WIDTH="12" HEIGHT="12"><A HREF="./buyersfinalvaluefee.php" CLASS="links"> 
			<? print $MSG_402; ?>
			</A></FONT></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%"><B></B></TD>
		<TD WIDTH="97%"><B><FONT FACE="Tahoma, Verdana" SIZE="2"> 
			<? print $MSG_385; ?>
			</FONT></B></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"> <FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="./images/ball.gif" WIDTH="12" HEIGHT="12"><A HREF="./sellersetupfee.php" CLASS="links"> 
			<? print $MSG_387;?>
			</A> </FONT></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="./images/ball.gif" WIDTH="12" HEIGHT="12"><A HREF="./sellerfinalvaluefee.php" CLASS="links"> 
			<? print $MSG_388;?>
			</A></FONT></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%"><FONT FACE="Tahoma, Verdana" SIZE="2"><IMG SRC="./images/ball.gif" WIDTH="12" HEIGHT="12"><A HREF="./picturesgalleryfee.php" CLASS="links"> 
			<? print $MSG_668;?>
			</A></FONT></TD>
	</TR>
	<TR> 
		<TD WIDTH="3%">&nbsp;</TD>
		<TD WIDTH="97%">&nbsp;</TD>
	</TR>
</TABLE>
