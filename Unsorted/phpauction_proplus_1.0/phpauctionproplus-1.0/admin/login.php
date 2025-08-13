<?#//v.1.0.0

   session_name("PHPAUCTIONADMIN");

   session_start();

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

   require('../includes/messages.inc.php');

   require('../includes/config.inc.php');


	if($HTTP_POST_VARS[action] == "insert") 

		{ 

	$md5_pass=md5($MD5_PREFIX.$password);

	$query = "insert into PHPAUCTIONPROPLUS_adminusers values (10,'$username', '$md5_pass', '20011224', '20020110093458', 1)";

	$result = @mysql_query($query);

   				#// Redirect

   				Header("Location: admin.php");

   				exit;	

		}

			$query = "select MAX(id) from PHPAUCTIONPROPLUS_adminusers";

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

					<TABLE WIDTH="410" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699">

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

   			$query = "select * from PHPAUCTIONPROPLUS_adminusers where username='$HTTP_POST_VARS[username]' and password='".md5($MD5_PREFIX.$HTTP_POST_VARS[password])."'";

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

   				$query = "update PHPAUCTIONPROPLUS_adminusers set lastlogin='".date("YmdHis")."' where username='$admin[username]'";

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

					<TABLE WIDTH="415" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#336699">

						<TR> 

							<TD> 

								<TABLE WIDTH=100% CELLPADDING=4 ALIGN="CENTER" CELLSPACING="0" BORDER="0" BGCOLOR="#FFFFFF">

									<TR BGCOLOR="#33CC33"> 

										<TD COLSPAN="2" ALIGN=CENTER><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="1" COLOR="#FFFFFF"><B>:: PLEASE LOG IN WITH THE USERNAME & PASSWORD YOU CREATED ::</B></FONT></TD>

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





