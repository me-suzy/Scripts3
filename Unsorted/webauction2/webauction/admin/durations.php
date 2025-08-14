<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	Function ToBeDeleted($index){
		Global $delete;
		
		$i = 0;
		while($i < count($delete)){
			if($delete[$i] == $index) return true;
			
			$i++;
		}
		return false;
	}

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

	//--Authentication check
	if(!$authenticated){
		Header("Location: login.php");
	}
	
	
	
	//-- Data check: "days" field must be numeric 
	
	$i = 0;
	while($i < count($new_days)){
		
		if(!ereg("^[0-9]+$",$new_days[$i]) && strlen($new_days[$i]) != 0){
			
			$ERR = "ERR_035";

		}
		$i++;
	}
	
	
	if($act && !$$ERR){
			
		//-- Update DURATIONS table
		
		$rebuilt_durations = array();
		$rebuilt_days 		 = array();
		$i = 0;
		while($i < count($new_durations) && strlen($new_durations[$i]) != 0){

			if(!ToBeDeleted($new_days[$i]) && strlen($new_durations) != 0){
				
				$rebuilt_durations[] 	= $new_durations[$i];
				$rebuilt_days[] 			= $new_days[$i];
			}
			$i++;
		}
		
		//--
		
		$query = "delete from ".$dbfix."_durations";
		$result = mysql_query($query);
		if(!$result)
		{
			print $ERR_001." - ".mysql_error();
		}
		
		$i = 0;
		while($i < count($rebuilt_durations)){

			$query = "insert into ".$dbfix."_durations values($rebuilt_days[$i],\"$rebuilt_durations[$i]\")";
			$result = mysql_query($query);
			if(!$result)
			{
				print $ERR_001." - ".mysql_error();
			}
			
			$i++;
		}
		
		$MSG = "MSG_123";
		
	}		
		
	
	
?>
<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>

<?    require('../includes/styles.inc.php'); ?>
  
<BODY>

<? require("./header.php"); ?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD COLSPAN=3>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_069; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=durations.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD COLSPAN=2>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_122; 
			if($$ERR){
				print "<FONT COLOR=red><BR><BR>".$$ERR;
			}else{
				if($$MSG){
					print "<FONT COLOR=red><BR><BR>".$$MSG;
				}else{
					print "<BR><BR>";
				}
			}
		?>
	</TD>
	</TR>

	 <TR>
	 <TD WIDTH=50></TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_097; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_087; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_088; ?> </B>
	 </TD>
	 </TR>
	
	<?
		//--
		$query = "select * from ".$dbfix."_durations order by days";
		$result = mysql_query($query);
		if(!$result)
		{
			print $ERR_001." - ".mysql_error();
			exit;
		}
		$num = mysql_num_rows($result);
		$i = 0;
		
		while($i < $num){
			
			$days 		 = mysql_result($result,$i,"days");
			$description = mysql_result($result,$i,"description");
			
			print "<TR>
					 <TD WIDTH=50></TD>
					 <TD>
					 <INPUT TYPE=text NAME=new_days[] VALUE=\"$days\" SIZE=3>
					 </TD>					 
					 <TD>
					 <INPUT TYPE=text NAME=new_durations[] VALUE=\"$description\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=checkbox NAME=delete[] VALUE=\"$days\">
					 </TD>
					 </TR>";
			$i++;
		}
			print "<TR>
					 <TD WIDTH=50>
					 $std_font Add
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=new_days[] SIZE=3>
					 </TD>					 
					 <TD>
					 <INPUT TYPE=text NAME=new_durations[] SIZE=25>
					 </TD>
					 <TD>
					 </TD>
					 </TR>";
		
	?>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>

	</TD>
	</TR>
	</TABLE>
	</FORM>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	<BR><BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<? require("./footer.php"); ?>
</BODY>
</HTML>
