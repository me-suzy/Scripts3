<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "loggedin.inc.php";

	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	include "../includes/countries.inc.php";
	
	$username = $name;


	//-- Data check
	if(!$id){
		header("Location: listusers.php");
		exit;
	}
	
	if($action){
	
		if($mode == "activate")
		{
			$sql="update PHPAUCTIONPROPLUS_users set suspended=0 WHERE id='$id'";
/* Update column users in table PHPAUCTIONPROPLUS_counters */
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET inactiveusers=(inactiveusers-1)");
		}
		else
		{
			$sql="update PHPAUCTIONPROPLUS_users set suspended=1 WHERE id='$id'";
/* Update column users in table PHPAUCTIONPROPLUS_counters */
			$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET inactiveusers=(inactiveusers+1)");			
		}
		$res=mysql_query ($sql);

		Header("Location: listusers.php");
		exit;
	}
	

	if(!$action || ($action && $updated)){

		$query = "select * from PHPAUCTIONPROPLUS_users where id=\"$id\"";
		$result = mysql_query($query);
		if(!$result){
			print "Database access error: abnormal termination".mysql_error();
			exit;
		}

		$username = mysql_result($result,0,"name");

		$nick = mysql_result($result,0,"nick");
		$password = mysql_result($result,0,"password");
		$email = mysql_result($result,0,"email");
		$address = mysql_result($result,0,"address");
		
		$country = mysql_result($result,0,"country");
		$country_list="";
		while (list ($code, $descr) = each ($countries))
		{
			$country_list .= "<option value=\"$code\"";
			if ($code == $country)
			{
				$country_list .= " selected";
			}
			$country_list .= ">$descr</option>\n";
		};
		
		$prov = mysql_result($result,0,"country");
		$zip = mysql_result($result,0,"zip");

		$birthdate = mysql_result($result,0,"birthdate");
		$birth_day = substr($birthdate,6,2);
		$birth_month = substr($birthdate,4,2);
		$birth_year = substr($birthdate,0,4);
		$birthdate = "$birth_day/$birth_month/$birth_year";


		$phone = mysql_result($result,0,"phone");
		$suspended = mysql_result($result,0,"suspended");

		$rate_num = mysql_result($result,0,"rate_num");
		$rate_sum = mysql_result($result,0,"rate_sum");
		if ($rate_num) 
		{
			$rate = round($rate_sum / $rate_num);
		}
		else 
		{
			$rate=0;
		}

	}


	require('./header.php');
	require('../includes/styles.inc.php'); 
?> <BR> 
<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
	<TR> 
		<TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
			<?
			if($suspended > 0)
			{
				print $MSG_306;
			}
			else
			{
				print $MSG_305;
			}
		?>
			</B></FONT></TD>
	</TR>
	<TR> 
		<TD BGCOLOR="#FFFFFF">
			<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5" BGCOLOR="#FFFFFF">
				<TR> 
					<TD> 
						<TABLE WIDTH=100% CELPADDING=0 CELLSPACING=0 BORDER=0 BGCOLOR="#FFFFFF">
							<TR> 
								<TD ALIGN=CENTER COLSPAN=5> <BR>
									<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"> 
									<B> </B> </FONT> <BR>
								</TD>
							</TR>
							<TABLE WIDTH="100%" BORDER="0" CELLPADDING="5">
								<?
if($updated){
			print "<TR>
  					<TD>
  					</TD>
  					<TD WIDTH=486>
					<FONT FACE=\"Verdana,Arial, Helvetica\" SIZE=2 COLOR=red>";
					if($updated) print "Users data updated";					
					print "</TD>
					</TR>";
}
?>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_302"; ?>
									</TD>
									<TD WIDTH="486"> 
										<?print $username; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_003"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $nick; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_004"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $password; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_303"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $email; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204"  VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_252"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $birthdate; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_009"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $address; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_014"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $countries[$country]; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_012"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $zip; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_013"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? print $phone; ?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_222"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? if(!$rate) $rate=0; ?>
										<IMG SRC="../images/estrella_<? echo $rate; ?>.gif"> 
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204" VALIGN="top" ALIGN="right"> 
										
										<? print "$MSG_300"; ?>
									</TD>
									<TD WIDTH="486"> 
										<? 
											if($suspended == 0)
												print "$MSG_029";
											else
												print "$MSG_030";

										?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<? print $err_font; ?>
										<?
											if($suspended > 0)
											{
												print $MSG_309;
												$mode = "activate";
											}
											else
											{
												print $MSG_308;
												$mode = "suspend";
											}
										?>
									</TD>
								</TR>
								<TR> 
									<TD WIDTH="204">&nbsp;</TD>
									<TD WIDTH="486"> 
										<FORM NAME=details ACTION="excludeuser.php" METHOD="POST">
											<INPUT TYPE="hidden" NAME="id" VALUE="<? echo $id; ?>">
											<INPUT TYPE="hidden" NAME="offset" VALUE="<? echo $offset; ?>">
											<INPUT TYPE="hidden" NAME="action" VALUE="Delete">
											<INPUT TYPE="hidden" NAME="mode" VALUE="<? print $mode; ?>">
											<INPUT TYPE=submit NAME=act VALUE="<? print $MSG_030; ?>">
										</FORM>
									</TD>
								</TR>
							</TABLE>
							<BR>
							<BR>
							<CENTER>
								<FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="admin.php">Admin 
								home</A> | <FONT FACE="Tahoma, Arial" SIZE="2"><A HREF="listusers.php?offset=<? print $offset; ?>">Users 
								list</A></FONT> </FONT>
							</CENTER>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
<!-- Closing external table (header.php) -->
<? include "footer.php"; ?>
