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
//   require('../includes/payments.inc.php');

	
	if($act){
			
		
		//-- Built new payments array
		
		$rebuilt_array = array();
		$i = 0;
		while($i < count($new_payments)){
		
			if(!ToBeDeleted($i) && strlen($new_payments[$i]) != 0){
				
				$rebuilt_array[] = $new_payments[$i];
			}
			$i++;
		}
		

		//--
		$query = "delete from PHPAUCTION_payments";
		$result = mysql_query($query);
		if(!$result)
		{
			print $ERR_001." - ".mysql_error();
			exit;
		}
		
		//--
		$i = 0;
		$counter = 1;
		while($i < count($rebuilt_array)){

			$query = "insert into PHPAUCTION_payments values($counter,\"$rebuilt_array[$i]\")";
			$result = mysql_query($query);
			if(!$result)
			{
				print $ERR_001." - ".mysql_error();
				exit;
			}
			$counter++;
			$i++;
		}
		
		$MSG = "MSG_093";
		
	}		
		
	

	require("./header.php"); 
	require('../includes/styles.inc.php'); 


?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_075; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
	<CENTER>
	<FORM NAME=conf ACTION=payments.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
		<? 
			print $MSG_092; 
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
	 <TD WIDTH=3></TD>
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
		$query = "select * from PHPAUCTION_payments order by description";
		$result = mysql_query($query);
		if(!$result)
		{
			print $ERR_001." - ".mysql_error();
			exit;
		}
		$num = mysql_num_rows($result);
		
		$i = 0;
		while($i < $num){
		
			$description 	= mysql_result($result,$i,"description");
			
			print "<TR>
					 <TD WIDTH=50></TD>
					 <TD>
					 <INPUT TYPE=text NAME=new_payments[] VALUE=\"$description\" SIZE=25>
					 </TD>
					 <TD>
					 <INPUT TYPE=checkbox NAME=delete[] VALUE=$i>
					 </TD>
					 </TR>";
			$i++;
		}
			print "<TR>
					 <TD WIDTH=50>
					 $std_font Add
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=new_payments[] SIZE=25>
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
