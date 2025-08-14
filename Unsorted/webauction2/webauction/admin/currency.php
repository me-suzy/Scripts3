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
	if(!$authenticated){
		Header("Location: login.php");
	}
	
	
	//--Check email
	
	if($act && !$currency){
		$ERR = "ERR_027";
	}
	
		
	if($act && !$$ERR){
			
		//-- Update adminmail.inc.php file
		
		$buffer = file("../includes/currency.inc.php");
		$fp = fopen("../includes/currency.inc.php", "w+");
		$i = 0;
		while($i < count($buffer)){
			
			if(strpos($buffer[$i],"currency")){
				$buffer[$i] = str_replace("\$currency = \"$currency\"","\$currency = \"$new_currency\"",$buffer[$i]);
			}
			
			fputs($fp,$buffer[$i]);	
			$i++;
		}
		fclose($fp);
		$MSG = "MSG_059";

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
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_076; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=currency.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_057; 
			if($$ERR){
				print "<FONT COLOR=red><BR>".$$ERR;
			}else{
				if($$MSG){
					print "<FONT COLOR=red><BR>".$$MSG;
				}else{
					print "<BR><BR>";
				}
			}
		?>
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<? 
			
			if($act){
				$value = $new_currency;
			}else{
				$value = $currency;
			}
		?>
		<INPUT TYPE=text NAME=new_currency VALUE="<? print $value; ?>" SIZE=6>
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_058; ?>">
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
