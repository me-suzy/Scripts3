<?
	include "loggedin.inc.php";


/*

   Copyright (c), 1999, 2000, 2001 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');

	
	//--Check email
	
	if($act && !$adminEmail){
		$ERR = "ERR_007";
	}else{
		if($act && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$new_email)){
			$ERR = "ERR_008";
		}
	}
	
	if($act && !$$ERR){
			
		//-- Update adminmail.inc.php file
		
		$buffer = file("../includes/adminmail.inc.php");
		$fp = fopen("../includes/adminmail.inc.php", "w+");
		$i = 0;
		while($i < count($buffer)){
			
			if(strpos($buffer[$i],"\$adminEmail")){
				$buffer[$i] = str_replace($adminEmail,$new_email,$buffer[$i]);
			}
			
			fputs($fp,$buffer[$i]);	
			$i++;
		}
		fclose($fp);
		$ERR = "MSG_059";
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
		<? print $MSG_053; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=email.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_055; 
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
				$value = $new_email;
			}else{
				$value = $adminEmail;
			}
		?>		<INPUT TYPE=text NAME=new_email VALUE="<? print $value; ?>" SIZE=30>
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_054; ?>">
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
