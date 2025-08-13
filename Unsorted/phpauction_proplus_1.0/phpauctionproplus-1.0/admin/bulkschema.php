<?#//v.1.0.0
		#///////////////////////////////////////////////////////
		#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
		#//  For Source code for the GPL version go to        //
		#//  http://phpauction.org and download               //
		#//  Supplied by CyKuH [WTN]                          //
		#///////////////////////////////////////////////////////

		include "includes/config.inc.php";
    include "includes/messages.inc.php";

?>
<HTML>
<HEAD>
  <TITLE><?=$SETTINGS[sitename]?></TITLE>
</HEAD>
<BODY BGCOLOR="white">
<TABLE WIDTH="100%" CELLSPACING="0" BORDER="0" CELLPADDING="4">

<?
if ($title=="") 
	{
?>
  <TR BGCOLOR="#eeeeee">
	  <TD WIDTH="100%"><?=$std_font?><B><?=$MSG_705?></B></TD>
  </TR>
  <TR>
	  <TD WIDTH="100%"><?=$std_font?><?=$MSG_706?></TD>
  </TR>
	<TR ALIGN="center">
		<TD WIDTH="100%">
		<TABLE WIDTH="100%" CELLSPACING="1" BORDER="0" CELLPADDING="2">
				<TR BGCOLOR="#dddddd">
					<TD WIDTH="30%"><B><?=$std_font.$MSG_707?></B></TD>
					<TD WIDTH="70%"><B><?=$std_font.$MSG_708?></B></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_017?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_709?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_710?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_711?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_713?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_712?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_738?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_739?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_716?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_717?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_718?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_719?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_722?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_723?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_724?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_725?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_726?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_727?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_728?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_729?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_730?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_731?></TD>
				</TR>
				<TR VALIGN="top">
					<TD WIDTH="30%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_732?></TD>
					<TD WIDTH="70%" BGCOLOR="#eeeeee"><?=$std_font.$MSG_733?></TD>
				</TR>
				<TR>
					<TD>  </TD>
					<TD>  </TD>
				</TR>
		</TABLE>
		</TD>
  </TR>
<?
	} else
	{
?>
  <TR BGCOLOR="#eeeeee">
	  <TD WIDTH="100%"><?=$std_font?><B><?=$MSG_713?></B></TD>
  </TR>
  <TR>
	  <TD WIDTH="100%"><?=$std_font?><?=$MSG_740?></TD>
  </TR>

<?	}  ?>
	<TR ALIGN="center">
		<TD WIDTH="100%"><BR><BR><?=$std_font?>
<? if($title=="cat")
	{
	echo "<A HREF=bulkschema.php>$MSG_270</a> | ";
	}
?>
<A HREF="Javascript:window.close()"><?=$MSG_678?></A></TD>
  </TR>
</TABLE>
</BODY>
</HTML>