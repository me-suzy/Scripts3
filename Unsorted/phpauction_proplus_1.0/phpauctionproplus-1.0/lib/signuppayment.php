<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	#//include "lib/check-key.php";

	#// Save user id in a session var
	$USER_ID = $TPL_id_hidden;
	session_name($SESSION_NAME);
	session_register("USER_ID");

?>
<TABLE WIDTH="765" BGCOLOR="#FFFFFF" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR> 
		<TD> 
			<CENTER>
				<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
				<? print $tlt_font.$MSG_001; ?></FONT>
				<BR>
				<BR>
				<?=$std_font?>
				<?=$MSG_420?><?=$SETTINGS[sitename]?><BR>
				<?=$MSG_421?><?=print_money($SETTINGS[signupvalue])?>
				<BR>
				<?=$MSG_422?>
				<BR><BR><BR>
				<form action="buy_credits.php" method="post" NAME="buy">
					<INPUT TYPE="submit" NAME="submit" VALUE="<?=$MSG_447?>">
				</FORM>	
			</CENTER>
		</TD>
	</TR>
</TABLE>

	
