<?
	include "loggedin.inc.php";


	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	

	if($action == "Yes")
	{

		$query = "DELETE FROM PHPAUCTION_news WHERE id='$id'";
		$res = mysql_query($query);
		if(!$res)
		{
			$ERR = "ERR_001";
		}
		else
		{
			Header("Location: news.php");
			exit;
		}
	}
	elseif($action == "No")
	{
		Header("Location: news.php");
		exit;
	}
	
	if(!$action)
	{
		//--
		$query = "SELECT * FROM PHPAUCTION_news WHERE id='$id'";
		$res = mysql_query($query);
		if(!$res)
		{	
			print $ERR_001;
			exit;
		}
		else
		{
			$title 		= mysql_result($res,0,"title");
			$content 	= mysql_result($res,0,"content");
			$suspended 	= mysql_result($res,0,"suspended");
			$tmp_date = mysql_result($res,$i,"new_date");
			$day = substr($tmp_date,6,2);
			$month = substr($tmp_date,4,2);
			$year = substr($tmp_date,0,4);
			$new_date = "$day/$month/$year";
		}
	}
	include "./header.php";
	require('../includes/styles.inc.php'); 
?>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
<TR>
<TD>
	<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
	<TR>
	 <TD ALIGN=CENTER COLSPAN=5>
		<BR>
		<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4">
		<B><? print $MSG_338; ?></B>
		</FONT>
		<BR>
	 </TD>
	</TR>	

	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">

	<?
	if($ERR || $updated){
				print "<TR>
						<TD>
						</TD>
						<TD WIDTH=486>
						<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
						if($$ERR) print $$ERR;
						if($updated) print "Auction data updated";					
						print "</TD>
						</TR>";
	}
	?>



<FORM NAME=addnew ACTION="<? print basename($PHP_SELF); ?>" METHOD="POST">

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<?print $std_font; ?>
		<? print "$MSG_522 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<? print $std_font.Date("m/d/Y"); ?> (mm/dd/yyyy)
	  </TD>
	</TR>
	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<?print $std_font; ?>
		<? print "$MSG_519 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
		<? PRINT $STD_FONT.$title; ?>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_520 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
	  <? print $std_font.nl2br($content); ?>
	  </TD>
	</TR>	
	
	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		<? print $std_font; ?>
		<? print "$MSG_521 *"; ?>
		</FONT>
	  </TD>
	  <TD WIDTH="486">
	  <? print $std_font; ?>	<?
			if($suspended == 0) print " NO";
			if($suspended == 1) print " YES";
	  ?>
	  </TD>
	</TR>

	<TR>
	  <TD WIDTH="204" VALIGN="top" ALIGN="right">
		&nbsp;
	  </TD>
	  <TD WIDTH="486">
	  <BR><BR>
	  <? print $err_font.$MSG_339; ?>
	  <BR><BR>
		<INPUT TYPE=submit NAME=action VALUE=<? print $MSG_030; ?>>&nbsp;&nbsp;&nbsp;
		<INPUT TYPE=submit NAME=action VALUE=<? print $MSG_029; ?>>
	  </TD>
	</TR>


	</TABLE>
		<INPUT type="hidden" NAME="id" VALUE="<? echo $id; ?>">
		<INPUT type="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
	</FORM>
	</center>
		 <BR>
		  <BR>
		  <CENTER>
		  <FONT face="Tahoma, Arial" size="2"><A HREF="admin.php" CLASS="navigation">Admin home</A> | 
		  <FONT face="Tahoma, Arial" size="2"><A
		  HREF="news.php?offset=<? print $offset; ?>
		  "CLASS="navigation"><? print $MSG_340; ?></A></FONT>
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