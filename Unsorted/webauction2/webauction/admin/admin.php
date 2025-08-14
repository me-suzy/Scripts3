<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
   include("../includes/config.inc.php");
   include("../includes/messages.inc.php");

	//--Authentication check
	if(! $HTTP_COOKIE_VARS["authenticated"]){
		Header("Location: login.php?loginfail=1");
	}
?>

<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>

<?php include("../includes/styles.inc.php"); ?>

<BODY>

<?php require("./header.php"); ?>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<CENTER>
	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><BR>
	<B>
		<?php print $MSG_051; ?>
	</B>
	</FONT>
	<BR>
	<BR>
	<CENTER>
	<TABLE WIDTH=700 CELLPADDING=2>
	<TR>

<!-- Installation -->

	<TD WIDTH=33% VALIGN=top>
		<TABLE WIDTH=230 CELLPADDING=2>
		  <TR>
			<TD WIDTH=20></TD>
			<TD BGCOLOR="#EEEEEE">
			<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<B><?php print $MSG_061; ?></B>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<?php print $MSG_064; ?>
			    </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<?php print $MSG_065; ?>
			    </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<?php print $MSG_066; ?>
			    </FONT>
			</TD>
		  </TR>
		</TABLE>
	</TD>



<!-- Configuration -->

	<TD WIDTH=300%  VALIGN=top>
		<TABLE WIDTH=230 CELLPADDING=2>
		  <TR>
			<TD WIDTH=20></TD>
			<TD BGCOLOR="#EEEEEE">
			<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<B><?php print $MSG_063; ?></B>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./email.php" CLASS="links">
				<?php print $MSG_077; ?>
				</A>
			    </FONT>
			</TD>
		  </TR>
		  
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./pwd_change.php" CLASS="links">
				<?php print $MSG_460; ?>
				</A>
			    </FONT>
			</TD>
		  </TR>
		  
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./currency.php"  CLASS="links">
				<?php print $MSG_076; ?>
				</A>
			    </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./categories.php" CLASS="links">
				<?php print $MSG_078; ?>
				</A>
			    </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./countries.php" CLASS="links">
				<?php print $MSG_083; ?>
				</A>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./payments.php" CLASS="links">
				<?php print $MSG_075; ?>
				</A>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./durations.php" CLASS="links">
				<? print $MSG_069; ?>
				</A>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./increments.php" CLASS="links">
				<?php print $MSG_128; ?>
				</A>
				</FONT> </TD>
		  </TR>
		  <TR>
			<TD WIDTH=20></TD>
			<TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./listhelp.php" CLASS="links">
				<?php print $MSG_916; ?>
				</A>
				</FONT>
			</TD>
		  </TR>
		</TABLE>
	</TD>

<!-- Administration -->

	<TD WIDTH=33% VALIGN=top>
		<TABLE WIDTH=230 CELLPADDING=2>
		  <TR>
			<TD WIDTH=20></TD>
			<TD BGCOLOR="#EEEEEE">
			<FONT FACE="Verdana, Verdana, Arial, Helvetica,sans-serif" SIZE="2">
				<B><?php print $MSG_062; ?></B>
			 </TD>
		  </TR>
		  <TR>
			 <TD WIDTH=20></TD>
			 <TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./listusers.php" CLASS="links">
				<?php print $MSG_045; ?>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			 <TD WIDTH=20></TD>
			 <TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./listauctions.php" CLASS="links">
				<?php print $MSG_067; ?>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			 <TD WIDTH=20></TD>
			 <TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./newsletter.php" CLASS="links">
				<?php print "Newsletter"; ?>
			     </FONT>
			</TD>
		  </TR>
		  <TR>
			 <TD WIDTH=20></TD>
			 <TD><FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
				<IMG SRC="../images/ball.gif">
				<A HREF="./listnews.php" CLASS="links">
				<?php print "News"; ?>
			     </FONT>
			</TD>
		  </TR>
		  

		  
		  
		</TABLE>
	</TD>
	</TR>
	</TABLE>

<BR>
<BR>

</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<?php print $MSG_051; ?>

</CENTER></CENTER></BODY></HTML>
