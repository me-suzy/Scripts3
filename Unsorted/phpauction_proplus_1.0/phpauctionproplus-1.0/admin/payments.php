<?#//v.1.0.0

	include "loggedin.inc.php";

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

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
		$query = "delete from PHPAUCTIONPROPLUS_payments";
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

			$query = "insert into PHPAUCTIONPROPLUS_payments values($counter,\"$rebuilt_array[$i]\")";
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
				<BR>
			</center>
	<FORM NAME=conf ACTION=payments.php METHOD=POST>
				<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
					<TR> 
						<TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
							<? print $MSG_075; ?>
							</B></FONT></TD>
					</TR>
					<TR> 
						<TD>
							<TABLE WIDTH=100% CELLPADDING=2 BGCOLOR="#FFFFFF">
								<TR> 
									<TD WIDTH=50></TD>
									<TD> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
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
										</FONT></TD>
								</TR>
								<TR> 
									<TD WIDTH=3></TD>
									<TD BGCOLOR="#EEEEEE"> 
										<? print $std_font; ?>
										<B>
										<? print $MSG_087; ?>
										</B> </TD>
									<TD BGCOLOR="#EEEEEE"> 
										<? print $std_font; ?>
										<B>
										<? print $MSG_088; ?>
										</B> </TD>
								</TR>
								<?
	
		//--
		$query = "select * from PHPAUCTIONPROPLUS_payments order by description";
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
									<TD> </TD>
								</TR>
							</TABLE>
						</TD>
					</TR>
				</TABLE>
				</FORM>
			<CENTER>
				<BR>
				<BR>
				<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
				<A HREF="admin.php" CLASS="links">Admin Home</A> </FONT> <BR>
				<BR>
			</CENTER>
		</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
<? require("./footer.php"); ?>
