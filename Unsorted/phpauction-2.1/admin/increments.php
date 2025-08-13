<?php
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


	if($act){	
		//-- Al fields must be numeric with

		$i = 0;
		while($i < count($increments) - 1){

			$lows[$i] = input_money($lows[$i]);
			$highs[$i] = input_money($highs[$i]);
			$increments[$i] = input_money($increments[$i]);

			if(!ereg("^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(\.[0-9]{1,2})$",$lows[$i])){
				$ERR = "ERR_030";
			}
			if(!ereg("^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(\.[0-9]{1,2})$",$highs[$i])){
				$ERR = "ERR_030";
			}
			if(!ereg("^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(\.[0-9]{1,2})$",$increments[$i])){
				$ERR = "ERR_030";
			}
			
			$i++;
		}
	}
	
	if($act && !$$ERR){

		//-- Build new increments array
		
		$rebuilt_increments = array();
		$rebuilt_lows = array();
		$rebuilt_highs = array();
		$i = 0;
		while($i < count($increments)){
		
			if(!ToBeDeleted($i) && strlen($increments[$i]) != 0){
				
				$rebuilt_increments[] 	= $increments[$i];
				$rebuilt_lows[] 			= $lows[$i];
				$rebuilt_highs[] 			= $highs[$i];
			}
			$i++;
		}
		
		$query = "delete from PHPAUCTION_increments";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error - abnormal termination".mysql_error();
			exit;
		}
		
		$i = 0;
		$counter = 1;
		while($i < count($rebuilt_increments)){
				
			$query = "insert into PHPAUCTION_increments values ('$counter', ".
			          str_replace(",","",$rebuilt_lows[$i]).", ".
			          str_replace(",","",$rebuilt_highs[$i]).", ".
			          str_replace(",","",$rebuilt_increments[$i]).")";
			$result = mysql_query($query);
			
			$i++;
			$counter++;
		}

		$MSG = "MSG_160";
		

	}		

	require("./header.php"); 
    require('../includes/styles.inc.php'); 
?>
<SCRIPT Language=Javascript>

function window_open(pagina,titulo,ancho,largo,x,y){

  var Ventana= 'toolbar=0,location=0,directories=0,scrollbars=1,screenX='+x+',screenY='+y+',status=0,menubar=0,resizable=0,width='+ancho+',height='+largo;
  open(pagina,titulo,Ventana);

}

</SCRIPT>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<? print $MSG_128; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<BR>
			<FORM NAME=conf ACTION=increments.php METHOD=POST>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
							<TD colspan=4> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
								<?php 
			print $MSG_135; 
            print $MSG_594;
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
								</FONT> </TD>
	</TR>

	 <TR>
	 <TD WIDTH=50></TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_240; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_241; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_242; ?> </B>
	 </TD>
	 <TD BGCOLOR="#EEEEEE">
	 <? print $std_font; ?>
	 <B><? print $MSG_088; ?> </B>
	 </TD>
	 </TR>
	
	<?php
		$query = "select * from PHPAUCTION_increments order by id";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: contact the site adminitrator".mysql_error();
			exit;
		}
		$num_increments = mysql_num_rows($result);
		$i = 0;
		while($i < $num_increments){
			
			$low = number_format(mysql_result($result,$i,"low"),2,'.',',');
			$high = number_format(mysql_result($result,$i,"high"),2,'.',',');
			$increment = number_format(mysql_result($result,$i,"increment"),2,'.',',');

			print "<TR>
					 <TD WIDTH=50></TD>
					 <TD><INPUT TYPE=text NAME=lows[] VALUE=\"".chop($low)."\" SIZE=10></TD>
					 <TD><INPUT TYPE=text NAME=highs[] VALUE=\"".chop($high)."\" SIZE=10></TD>
					 <TD><INPUT TYPE=text NAME=increments[] VALUE=\"".chop($increment)."\" SIZE=10></TD>
					 <TD><INPUT TYPE=checkbox NAME=delete[] VALUE=\"$i\"></TD>
					 </TR>";
			$i++;
		}
			print "<TR>
					 <TD WIDTH=50>
					 $std_font Add
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=lows[] SIZE=10>
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=highs[] SIZE=10>
					 </TD>
					 <TD>
					 <INPUT TYPE=text NAME=increments[] SIZE=10>
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
	</CENTER>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<?php require("./footer.php"); ?>