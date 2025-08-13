<?
   session_name("PHPAUCTIONADMIN");
   session_start();


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




	if($HTTP_POST_VARS[action] == "insert") 
		{ 
	$md5_pass=md5($MD5_PREFIX.$password);
	$query = "insert into PHPAUCTION_adminusers values (10,'$username', '$md5_pass', '20011224', '20020110093458', 1)";
	$result = @mysql_query($query);
   				#// Redirect
   				Header("Location: admin.php");
   				exit;	
		}
			$query = "select MAX(id) from PHPAUCTION_adminusers";
   			$result = @mysql_query($query);
			while($row = mysql_fetch_row($result)) {
			$id = $row[0] + 1;
			}
   			if($id==1) 
			{ 
				$id=0;

				require("./header.php"); ?>

				<TABLE BORDER=0 WIDTH=650 CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF" ALIGN="CENTER">
				<TR>
				<TD><CENTER><FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
				<BR>
				<FORM NAME=login ACTION=login.php METHOD=POST>
					<TABLE WIDTH="400" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699">
						<TR> 
							<TD> 
								<TABLE WIDTH=100% CELLPADDING=3 ALIGN="CENTER" CELLSPACING="0" BORDER="0" BGCOLOR="#FFFFFF">
									<TR BGCOLOR="#336699"> 
										<TD COLSPAN="2" ALIGN=CENTER><FONT FACE="Tahoma, Verdana" SIZE="2" COLOR="#FFFFFF"><B>
										:: Please create your username and password ::</B></FONT></TD>
									</TR>
									<TR> 
										<TD></TD>
										<TD> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR=red> 
											<? print $ERR; ?>
											</FONT> </TD>
									</TR>
									<TR> 
										<TD ALIGN=right> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_003; ?>
											</FONT> </TD>
										<TD> 
											<INPUT TYPE=TEXT NAME=username SIZE=20 >
										</TD>
									</TR>
									<TR> 
										<TD ALIGN=right> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_004; ?>
											</FONT> </TD>
										<TD> 
											<INPUT TYPE=password NAME=password SIZE=20 >
										</TD>
									</TR>
									<TR> 
										<TD></TD>
										<TD> 
											<INPUT TYPE=submit NAME=action VALUE="insert">
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
				</FORM>
				</font> 
			</CENTER>
		</TD>
</TR>
</TABLE>

<? require("./footer.php");

 				} 
			else { $id=1;

   #//
   if($HTTP_POST_VARS[action] == "login")
   {
   		if(strlen($HTTP_POST_VARS[username]) == 0 ||
   		   strlen($HTTP_POST_VARS[password]) == 0
   		  )
   		{
   			$ERR = $ERR_047;
   		}
   		else
   		{
   			$query = "select * from PHPAUCTION_adminusers where username='$HTTP_POST_VARS[username]' and password='".md5($MD5_PREFIX.$HTTP_POST_VARS[password])."'";
   			$res = @mysql_query($query);
   			if(!$res)
   			{
   				print "Error: $query<BR>".mysql_error();
   				exit;
   			}
   			if(mysql_num_rows($res) == 0)
   			{
   				$ERR = $ERR_048;
   			}
   			else
   			{
   				$admin = mysql_fetch_array($res);
   				
   				#// Set sessions vars
   				$PHPAUCTION_ADMIN_LOGIN = $admin[id];
   				$PHPAUCTION_ADMIN_USER = $admin[username];
   				session_name("PHPAUCTIONADMIN");
   				session_register("PHPAUCTION_ADMIN_LOGIN","PHPAUCTION_ADMIN_USER");
   				
   				#// Update last login information for this user
   				$query = "update PHPAUCTION_adminusers set lastlogin='".date("YmdHis")."' where username='$admin[username]'";
   				$rr = mysql_query($query);
   				if(!$rr)
   				{
   					print "Error: $query<BR>".mysql_error();
   					exit;
   				}
   				
   				#// Redirect
   				Header("Location: admin.php");
   				exit;
   			}
   		}
   	}

	require("./header.php"); 

?>

<TABLE BORDER=0 WIDTH=650 CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF" ALIGN="CENTER">
	<TR>
<TD>
	<CENTER>
				<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
				<BR>
				<? if(!$action || ($action && $ERR)) : ?>
				<FORM NAME=login ACTION=login.php METHOD=POST>
					<TABLE WIDTH="400" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699">
						<TR> 
							<TD> 
								<TABLE WIDTH=100% CELLPADDING=3 ALIGN="CENTER" CELLSPACING="0" BORDER="0" BGCOLOR="#FFFFFF">
									<TR BGCOLOR="#336699"> 
										<TD COLSPAN="2" ALIGN=CENTER><FONT FACE="Tahoma, Verdana" SIZE="2" COLOR="#FFFFFF"><B>:: 
							Please log in using the username and password you created  ::</B></FONT></TD>
									</TR>
									<TR> 
										<TD></TD>
										<TD> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2" COLOR=red> 
											<? print $ERR; ?>
											</FONT> </TD>
									</TR>
									<TR> 
										<TD ALIGN=right> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_003; ?>
											</FONT> </TD>
										<TD> 
											<INPUT TYPE=TEXT NAME=username SIZE=20 >
										</TD>
									</TR>
									<TR> 
										<TD ALIGN=right> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
											<? print $MSG_004; ?>
											</FONT> </TD>
										<TD> 
											<INPUT TYPE=password NAME=password SIZE=20 >
										</TD>
									</TR>
									<TR> 
										<TD></TD>
										<TD> 
											<INPUT TYPE=submit NAME=action VALUE="login">
										</TD>
									</TR>
								</TABLE>
							</TD>
						</TR>
					</TABLE>
				</FORM>
				<?  endif;  ?>
				</font> 
			</CENTER>
		</TD>
</TR>
</TABLE>

<?  } require("./footer.php");  ?>


