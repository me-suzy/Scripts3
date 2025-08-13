<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

   session_name("PHPAUCTIONADMIN");
   session_start();

   include "../includes/dates.inc.php";
   
   
   #// Retrieve counter
   $query = "SELECT * FROM PHPAUCTIONPROPLUS_counters";
   $res = @mysql_query($query);
   if(!$res)
   {
   		print "Error: $query<BR>".mysql_error();
   		exit;
   	}
   	else
   	{
   		$COUNTERS = mysql_fetch_array($res);
   		
   		#// Format reset couters date
   		$RESET_DATE = FormatDate($COUNTERS[resetdate]);
   	}

?>
<TITLE>::PHPAUCTION ADMINISTRATION BACK-END::</TITLE>


<STYLE TYPE="TEXT/CSS">
<!--
td {  font-family: Tahoma, Verdana; font-size: x-small; color: #0030066}
.yellow {  font-family: Tahoma, Verdana; font-size: x-small; color: #0030066; background-color: #FFFF00}

-->
</STYLE>

</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="#08428C" VLINK="#08428C" ALINK="#08428C" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#EEEEEE" ALIGN="CENTER">
	<TR BGCOLOR="#66CC66"> 
		<TD WIDTH="43%" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><A HREF=admin.php><IMG SRC="images/logo.gif" WIDTH="255" HEIGHT="70" BORDER=0></A></TD>
		<TD WIDTH="57%" VALIGN="BOTTOM" ALIGN=center BGCOLOR="#FFFFFF"> 
			<DIV ALIGN="RIGHT"><FONT FACE="Verdana,Arial,Helvetica" SIZE=4 COLOR="#000066"> 
				<FONT COLOR="#3366CC" FACE="Tahoma, Verdana" SIZE="5"><B><FONT FACE="Arial, Helvetica, sans-serif" COLOR="#666666">ADMINISTRATION 
				BACK-END</FONT></B></FONT></FONT> </DIV>
		</TD>
	</TR>
</TABLE>
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#EEEEEE" ALIGN="CENTER">
	<TR BGCOLOR="#000000"> 
		<TD VALIGN=TOP HEIGHT="1" COLSPAN="2"><IMG SRC="images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
	</TR>
	<TR BGCOLOR="#99CCFF"> 
		<TD VALIGN=TOP HEIGHT="17" WIDTH="299"><FONT FACE=Verdana SIZE=2 COLOR=#000000> 
		<?
			if($HTTP_SESSION_VARS[PHPAUCTION_ADMIN_LOGIN])
			{
		?>
			<?=$MSG_592?>
			<B> 
			<?=$HTTP_SESSION_VARS[PHPAUCTION_ADMIN_USER]?>
			</B></FONT>
		<?
			}
			else
			{
				print "&nbsp;";
			}
		?>
			</TD>
		<TD VALIGN=TOP HEIGHT="17" ALIGN=right WIDTH="351">
		<?
			if($HTTP_SESSION_VARS[PHPAUCTION_ADMIN_LOGIN])
			{
		?>
			<A HREF="<?=$SETTINGS[siteurl]?>"> 
			</A> <A HREF="admin.php"><FONT FACE="Tahoma, Verdana" SIZE="2" COLOR="#333333">Admin 
			Home</FONT></A><FONT FACE="Tahoma, Verdana" SIZE="2"> <FONT COLOR="#000000">|</FONT> 
			<A HREF="logout.php"><FONT COLOR="#333333">Logout</FONT></A></FONT>
		<?
			}
			else
			{
				print "&nbsp;";
			}
		?>
		</TD>
	</TR>
	<TR BGCOLOR="#333333"> 
		<TD VALIGN=TOP HEIGHT="1" COLSPAN="2"><IMG SRC="images/transparent.gif" WIDTH="1" HEIGHT="1"></TD>
	</TR>
</TABLE>
