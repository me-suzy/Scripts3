<?#//v.1.0.0
	session_name("PHPAUCTIONADMIN");		
	session_start();
	
	include "loggedin.inc.php";


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

   	require('../includes/messages.inc.php');
   	require('../includes/config.inc.php');
			
	require("./header.php");
	require('../includes/styles.inc.php'); 
	
	if(is_array($HTTP_POST_VARS[install]))
	{
		while(list($k,$v) = each($HTTP_POST_VARS[install]))
		{
			#// Create backup directory if does not exists
			if(!file_exists("backup"))
			{
				mkdir("backup",0755);
			}
			#//
			mysql_connect("phpauction.org","patches","cantachetipassa");
			mysql_select_db("phpauctionpropatches");
				
			$query = "select script from pluspatches where id=$k";
			$res = mysql_query($query);
            if(!$res)
			{
				print "ERROR: $query<BR>".mysql_error();
				exit;
			}
			$SCRIPT = mysql_result($res,0,"script");
			
			#// Process selected files
			if($v == "missing")
			{
				$FP = fopen("../".$PATCH_PATH[$k].$PATCH_FILE[$k],"w");
				fwrite($FP,$SCRIPT,strlen($SCRIPT));
				fclose($FP);
			}
			else
			{
				#// Backup current file
				$DATE = date("Ymd");
				copy("../".$PATCH_PATH[$k].$PATCH_FILE[$k],"backup/".$DATE."-".$PATCH_FILE[$k]);
				
				$FP = fopen("../".$PATCH_PATH[$k].$PATCH_FILE[$k],"w");
				fwrite($FP,$SCRIPT,strlen($SCRIPT));
				fclose($FP);				
			}

			
		}		
	}
    
    unset($PATCH_FILE);
    unset($PATCH_PATH);    

    session_name("PHPAUCTIONADMIN");
    session_unregister(PATCH_FILE);
    session_unregister(PATCH_PATH);    
	
	#// Connect to the patches database and check current installation
	if(!@mysql_connect("phpauction.org","patches","cantachetipassa"))
	{
		print "Cannot connect to patches server";
		exit;
	}
	elseif(!mysql_select_db("phpauctionpropatches"))
	{
		print "Cannot select pluspatches database";
		exit;
	}
	else
	{
		$query = "SELECT * from pluspatches";
		$res =  @mysql_query($query);
		if(!$res)
		{
			print "An error occured while retrieving pluspatches information";
			exit;
		}
		else
		{
			while($row = mysql_fetch_array($res))
			{
				$PATCH_FILE[$row[id]] = $row[filename];
				$PATCH_PATH[$row[id]] = $row[path];
				$PATCH_VERSION[$row[id]] = $row[version];
				$PATCH_DATE[$row[id]] = $row[filedate];
				$PATCH_DESCR[$row[id]] = $row[description];

				$TMP = explode(".",$row[version]);
				$PV = $TMP[2];
				 
				if(!file_exists("../".$row[path].$row[filename]))
				{
					$PATCH_STATUS[$row[id]]	= "missing";
				}
				else
				{
					$FP = file("../".$row[path].$row[filename]);
					$TMP = explode(".",$FP[0]);
                    $V = intval($TMP[3]);
                    /*
					if(!eregi("^\<\?#//v\.([0-9]+)\.([0-9]+)\.([0-9]+)$",$V,$r))
					{
						$V = 0;
					}
					else
					{
						#// File version
						$V = $r[3];
					}
                    */
					if($V < $PV)
					{
						$PATCH_STATUS[$row[id]]	= "old";
					}
				}
			}
		}
	}
		
	#//	
	session_name("PHPAUCTIONADMIN");
	session_register(PATCH_FILE);
	session_register(PATCH_PATH);
?>
  
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>
			<CENTER>
				<BR>
				<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST>
					<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
						<TR> 
							<TD ALIGN=CENTER><FONT color=#ffffff FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
								<? print $MSG_655; ?>
								</B></FONT><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4" COLOR=#FFFFFF><B> 
								</B></FONT></TD>
						</TR>
						<TR> 
							<TD>
								<TABLE WIDTH=100% CELLPADDING=2 ALIGN="CENTER" BGCOLOR="#FFFFFF">
									<?
										if(count($PATCH_STATUS) == 0)
										{
									?>
									<TR VALIGN="TOP"> 
										<TD COLSPAN=3>
										<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<?=$MSG_658?>
										</FONT></TD>
									</TR>
									<?
										}
										else
										{
									?>
									<TR VALIGN="TOP"> 
										<TD COLSPAN="3">
										<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
										<?
											print $MSG_659."<BR><BR>";
										?>
										<TABLE WIDTH=100% CELLPADDING=1 CALLSPACING=0 BORDER=0>
										<TR bgcolor=#BCEE68>
											<TD WIDTH=15>&nbsp;</TD>
											<TD BGCOLOR=WHITE>
												<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
												<?=$MSG_660?>
												</FONT>
											</TD>
										</TR>
										<TR bgcolor=#FFFF00>
											<TD WIDTH=15>&nbsp;</TD>
											<TD BGCOLOR=WHITE>
												<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
												<?=$MSG_661?>
												</FONT>
											</TD>
										</TR>
										</TABLE>
										<?
											asort($PATCH_STATUS);
											reset($PATCH_STATUS);
										?>
										<TABLE WIDTH=100% CELLPADDING=3 CALLSPACING=1 BODER=0>
										<TR bgcolor=#99CCFF>
										<TD>
											&nbsp;
										</TD>
										<TD>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<B>ACTION</B>
											</FONT>
										</TD>
										<TD>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<B>FILE</B>
											</FONT>
										</TD>
										<TD>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<B>VERSION</B>
											</FONT>
										</TD>
										<TD>
											<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
											<B>DESCRIPTION</B>
											</FONT>
										</TD>
										</TR>
										<?
											while(list($k,$v) = each($PATCH_STATUS))
											{
												if($v == "missing")
												{
													$BG = "#BCEE68";
												}
												elseif($v == "old")
												{
													$BG = "#FFFF00";
												}
										?>
											<TR BGCOLOR=<?=$BG?>>
												<TD>
													<INPUT TYPE=checkbox NAME=install[<?=$k?>] VALUE=<?=$v?>>
												</TD>
												<TD>
													<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
													<?
														if($v == "missing")
														{
															print "INSTALL";
														}
														elseif($v == "old")
														{
															print "UPDATE";
														}
													?>
													</FONT>
												</TD>
												<TD>
													<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
													<?=$PATCH_FILE[$k];?>
													</FONT>
												</TD>
												<TD>
													<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
													<?=$PATCH_VERSION[$k];?>
													</FONT>
												</TD>
												<TD>
													<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
													<?=$PATCH_DESCR[$k];?>
													</FONT>
												</TD>
											</TR>
										<?
											}
										?>
										</FONT>
										</TABLE>
									</TD>
								</TR>
								<?
									}
								?>
								<TR>
									<TD ALIGN=CENTER COLSPAN=3>
										<INPUT TYPE=SUBMIT VALUE="<?=$MSG_662?>">
									</TD>
								</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
					</FORM>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A HREF="admin.php" CLASS="links">Admin Home</A>
	</FONT>
	</CENTER>
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
