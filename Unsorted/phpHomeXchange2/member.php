<?php

#############################################################################
#############################################################################
##                                                                         ##
##                  ________   __         _     __      __                 ##
##                 / _______|  | |       |_|    \ \    / /                 ##
##                 | |         | |        _      \ \  / /                  ##
##                 | |         | |       | |      \ \/ /                   ##
##                 | |         | |       | |      / /\ \                   ##
##                 | |______   | |_____  | |     / /  \ \                  ##
##                 \________|  \_______| |_|    /_/    \_\                 ##
##                                                                         ##
##                             Script by CLiX                              ##
#############################################################################
#############################################################################
##  PHPHomeXchange                Version 2.0                              ##
##  Created 1/15/01               Created by CLiX                          ##
##  CopyRight © 2002 CLiX         clix@theclixnetwork.com                  ##
##  Get other scripts at:         theclixnetwork.com                       ##
#############################################################################
#############################################################################
##                                                                         ##
##  PHPHomeXchange Users are subject to the TOS at theclixnetwork.com. TOS ##
##  can change at any time. You MAY NOT redistribute or sell the script in ##
##  any way. If you intend on selling the site created from PHPHomeXchange ##
##  you must contact us for permission first!                              ##
##                                                                         ##
#############################################################################
#############################################################################

include "config.inc";
include "header.inc";
$BANIP = array();
$BANIP = file("bannedip.txt");
for ($i=0; $i<count($BANIP); $i++) {
   if ($REMOTE_ADDR == $BANIP[$i]) {
   	echo "<FONT COLOR=RED><B>You have been banned from using the services on this site.</B></FONT>";
   	include "footer.inc";
   	exit();
   }
}
if ($action == "activatederror") {
?>
<H1>Paused Account</H1>
<B><FONT COLOR=RED>Your account hasn't been activated yet. You may only receive credits if it is activated. To do so login with the password sent to your email address, and unpause your account.</FONT></B>
<?php
include "footer.inc";
exit();
}
$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
mysql_select_db($dbname, $global_dbh);
$query = "SELECT * FROM $dbtable WHERE username = '$username'";
$result = mysql_query($query, $global_dbh);
$row = mysql_fetch_array($result);
		if ($row["ispaused"] == "2") {
		?>
		 <H1>Account Locked</H1>
<B><FONT COLOR=RED>Your account has been locked by the administrator.</FONT></B>
<?php
include "footer.inc";
exit();
}
		if ($row["memberlevel"] == "0") {
			$tmp = $row["referer"];
			$queryer = "SELECT creditsearned, refercredits FROM $dbtable WHERE username = '$tmp'";
			$resulter = mysql_query($queryer, $global_dbh);
			print mysql_error($global_dbh);
			if (mysql_num_rows($resulter) > 0) {
				$zow = mysql_fetch_array($resulter);
				$tmp2 = $zow["creditsearned"] + $referbonus;
				$tmp3 = $zow["refercredits"] + $referbonus;
				$queryer = "UPDATE $dbtable SET refercredits='$tmp3', creditsearned='$tmp2' WHERE username = '$tmp'";
				$rsleut = mysql_query($queryer, $global_dbh);
				print mysql_error($global_dbh);
			}
		}
if ($action == "report") {
?>
  <FORM METHOD="POST" ACTION="member.php">
   Hello, <?php echo $u; ?>. You are about to report <?php echo $o; ?>. Now why is that?</P>
<INPUT TYPE=HIDDEN NAME="u" VALUE="<?php echo $u; ?>">
<INPUT TYPE=HIDDEN NAME="o" VALUE="<?php echo $o; ?>">
<INPUT TYPE=HIDDEN NAME="action" VALUE="doreport">
   <P>
    <TEXTAREA NAME="reason" COLS="44" ROWS="5"></TEXTAREA>
    <INPUT TYPE="Submit" NAME="Submit" VALUE="Report <?php echo $o; ?>">
    </FORM>
<?php
include "footer.inc";
exit();

}
if ($action == "doreport") {
$mailsend = mail("$adminemail", "USER REPORTED", "The user $o has been reported by $u because $reason");
?>
<H1>User Reported</H1>
We will check their site(s) for violations.
<?php
include "footer.inc";
exit();
}
if ($action == "sendpass") {
	$mailsend = mail($row["email"], "Your Password", "Hey! Thank You For Joining! \r\n\r\n Your Username is " . $row["username"] . " \r\n Your password is " . $row["password"] . " \r\n Login at $scriptsurl/member.php \r\n\r\n PHPHomeXchange 2.0", "From: $adminemail");
	?>
	<H1>Forgot Password</H1>
	Your password has been sent to <?php echo $row["email"]; echo "."; include "footer.inc"; exit();
}
$blaher = mysql_num_rows($result);
if ($password != NULL) {
if ($row["password"] == $password)  {
	if ($action == "forgot") {
		?>
		<H1>Forgot Password</H1>
		Your currently logged in correct, click <A HREF="member.php?action=logout">here</A> to logout or <A HREF="member.php">here</A> to view the members area.
		<?php
		include "footer.inc";
		exit();
	}
	if ($action == "transfer") {
		if (IsSet($touser)) {
			if ($row["creditsearned"] - $row["creditsused"] < $amount) {
				?>
				<H1>Transfer Credits</H1>
				You dont have enough credits to transfer!<BR>
				<?php
			}
			else {
				$query = "SELECT * FROM $dbtable WHERE username = '$touser'";
				$result = mysql_query($query, $global_dbh);
				$rowtwo = mysql_fetch_array($result);
				$blahetr = mysql_num_rows($result);
				if ($blahetr > 0) {
					$temp = $row["creditsused"] + $amount;
					$temptwo = $row["creditsearned"] + $amount;
					$query = "UPDATE $dbtable SET creditsused='$temp'  WHERE username = '$username'";
					$result = mysql_query($query, $global_dbh);		
					$query = "UPDATE $dbtable SET creditsearned='$temptwo' WHERE username = '$touser'";
					$result = mysql_query($query, $global_dbh);
					?>
					<H1>Transfer Credits</H1>
					Your credits have been transfered.
					<?php
				}
				else {
					?>
					<H1>Transfer Credits</H1>
					Unknown username.
					<?php
				}
			}
		}
		else {
		?>
		<H1>Transfer Credits</H1>
		To transfer credits, simply enter the username and amount below:<BR>
			<FORM METHOD="POST" ACTION="member.php">
			<TABLE>
			<TR><TD>Username</TD>
			    <TD><INPUT TYPE=TEXT NAME="touser"></TD></TR>
			<TR><TD>Amount</TD>
			    <TD><INPUT TYPE=TEXT NAME="amount"></TD></TR>
			<tr><TD></TD>
			    <TD><INPUT TYPE=HIDDEN NAME="action" VALUE="transfer"><INPUT TYPE="Submit" NAME="Submit" VALUE="Login"></TD></TR>
			</TABLE>
		<?php
		}
		include "footer.inc";
		exit();
	}
	if ($action == "save") {
		$BANIP = array();
$BANIP = file("bannedemail.txt");
for ($i=0; $i<count($BANIP); $i++) {
   if ($email == $BANIP[$i]) {
   	echo "<FONT COLOR=RED><B>" . $BANIP[$i] . " is being banned from using our services.</B></FONT>";
   	include "footer.inc";
   	exit();
   }
}
$urls = "$url1|$url2|$url3|$url4|$url5|$url6|$url7|$url8|$url9|$url10";
$BANIP = array();
$BANIP = file("bannedurl.txt");
for ($i=0; $i<count($BANIP); $i++) {
   if (strstr($urls, $BANIP[$i])) {
   	echo "<FONT COLOR=RED><B>One of your urls contains a banned domain, " . $BANIP[$i] . "</B></FONT>";
   	include "footer.inc";
   	exit();
   }
}

		$query = "UPDATE $dbtable SET memberlevel='1', ispaused='$ispaused', password='$pword', email='$email', urls='$url1|$url2|$url3|$url4|$url5|$url6|$url7|$url8|$url9|$url10' WHERE username = '$username'";
		$result = mysql_query($query, $global_dbh);
		print mysql_error($global_dbh);
		?>
			<H1>Information Saved</H1>
			You must now re-login.
			<P>
			<FORM METHOD="POST" ACTION="member.php">
			<TABLE>
			<TR><TD>Username</TD>
			    <TD><INPUT TYPE=TEXT NAME="username"></TD></TR>
			<TR><TD>Password</TD>
			    <TD><INPUT TYPE=PASSWORD NAME="password"></TD></TR>
			<tr><TD></TD>
			    <TD><INPUT TYPE=HIDDEN NAME="action" VALUE="login"><INPUT TYPE="Submit" NAME="Submit" VALUE="Login"></TD></TR>
			</TABLE>
		<?php
			include "footer.inc";
			exit();
	}
	elseif ($a == "referal") {
	$query = "SELECT username FROM $dbtable WHERE referer = '$username'";
	$result = mysql_query($query, $global_dbh);
	print "<H1>Downline Searcher</H1>";
        print "Send your downline credits, click <A HREF=\"member.php?action=transfer\">here</A> to transfer credits.<BR>";
	print "<B>$username</B>";
	if (mysql_num_rows($result) > 0) {
	print "<BLOCKQUOTE>";
	while($row_array = mysql_fetch_row($result))
		{
		print "<BR>" . $row_array[0];
		$query2 = "SELECT username FROM $dbtable WHERE referer = '$row_array[0]'";
		$result2 = mysql_query($query2, $global_dbh);
		if (mysql_num_rows($result2) > 0) {
		print "<BLOCKQUOTE>";
		while($row_array2 = mysql_fetch_row($result2))
			{
			print "<BR>" . $row_array2[0];
			$query3 = "SELECT username FROM $dbtable WHERE referer = '$row_array2[0]'";
			$result3 = mysql_query($query3, $global_dbh);
			if (mysql_num_rows($result3) > 0) {
			print "<BLOCKQUOTE>";
			while($row_array3 = mysql_fetch_row($result3))
				{
				print "<BR>" . $row_array3[0];
				$query4 = "SELECT username FROM $dbtable WHERE referer = '$row_array3[0]'";
				$result4 = mysql_query($query4, $global_dbh);
				if (mysql_num_rows($result4) > 0) {
				print "<BLOCKQUOTE>";
				while($row_array4 = mysql_fetch_row($result4))
					{
					print "<BR>" . $row_array4[0];
					}
			 	print "</BLOCKQUOTE><BR>";
				}
				}
			print "</BLOCKQUOTE><BR>";
			}
			}
		print "</BLOCKQUOTE><BR>";
		}
		}
	print "</BLOCKQUOTE>";
	include "footer.inc";
	exit();
	}
	}
	else {
		$left = $row["creditsearned"] - $row["creditsused"];
		
		?>
		
	<H1>Members Only</H1>
	<H3>User Statistics</H3>
	<TABLE>
	<TR><TD>Credits Earned</TD>
	    <TD><?php echo $row["creditsearned"]; ?></TD></tr>
	<TR><TD>Credits Used</TD>
	    <TD><?php echo $row["creditsused"]; ?></TD></tr><TR><TD>Credits Left</TD>
	    <TD><?php print("$left"); ?></TD></tr>
	<TR><TD><A HREF="member.php?a=referal">Referal</A>Credits</TD><TD><?php echo $row["refercredits"]; ?>
	</TABLE>
	<H3>Codes</H3>
	<TABLE>
	<TR><TD>Start Page:</TD>
	    <TD><A HREF="<?php echo $scriptsurl; ?>/start.php?username=<?php echo $username; ?>"><?php echo $scriptsurl; ?>/start.php?username=<?php echo $username; ?></A></TD></TR>
	<TR><TD>referal Page:</TD>
	    <TD><?php echo $scriptsurl; ?>/index.php?referer=<?php echo $username; ?></TD></TR>
	</TABLE>
<!-- The following is not required, but can be left in to make site have more ways to advertise -->
                    <H3>Safe List</H3>
                    <TABLE>
                    <TR><TD><A HREF="http://www.global-lists.com/mle/minisignup.php?list=StartXchangeSafeList&id=8529872953">Join</A></TD><TD><A HREF="http://www.global-lists.com/mle/login.php?list=StartXchangeSafeList&id=8529872953">Login</A></TD></TR>
                    </TABLE>
<!-- The above is not required, but can be left in to make site have more ways to advertise -->
	<H3>Edit User Info</H3>
	<FORM METHOD="POST" ACTION="member.php">
	<TABLE>
	<TR><TD>Username</TD>
	    <TD><?php echo $username; ?></TD></TR>
	<TR><TD>Password</TD>
	    <TD><INPUT TYPE=PASSWORD NAME="pword" VALUE="<?php echo $row["password"]; ?>"></TD></TR>
	<TR><TD>Email</TD>
	    <TD><INPUT TYPE=TEXT NAME="email" VALUE="<?php echo $row["email"]; ?>"></TD></TR>
	<tr><TD>Pause*</TD>
	    <TD><?php if ($row["ispaused"] == 1) { echo '<INPUT TYPE=RADIO NAME="ispaused" VALUE="1" CHECKED>yes<BR><INPUT TYPE=RADIO NAME="ispaused" VALUE="0">no';
		} else { echo '<INPUT TYPE=RADIO NAME="ispaused" VALUE="1">yes<BR><INPUT TYPE=RADIO NAME="ispaused" VALUE="0" CHECKED>no'; }
	         ?></TD></TR>
	         <?php $urls = explode("|", $row["urls"]); ?>
<TR><TD>URL 1</TD><TD><INPUT TYPE=TEXT NAME="url1" VALUE="<?php echo $urls[0]; ?>"></TD></TR>
<TR><TD>URL 2</TD><TD><INPUT TYPE=TEXT NAME="url2" VALUE="<?php echo $urls[1]; ?>"></TD></TR>
<TR><TD>URL 3</TD><TD><INPUT TYPE=TEXT NAME="url3" VALUE="<?php echo $urls[2]; ?>"></TD></TR>
<TR><TD>URL 4</TD><TD><INPUT TYPE=TEXT NAME="url4" VALUE="<?php echo $urls[3]; ?>"></TD></TR>
<TR><TD>URL 5</TD><TD><INPUT TYPE=TEXT NAME="url5" VALUE="<?php echo $urls[4]; ?>"></TD></TR>
<TR><TD>URL 6</TD><TD><INPUT TYPE=TEXT NAME="url6" VALUE="<?php echo $urls[5]; ?>"></TD></TR>
<TR><TD>URL 7</TD><TD><INPUT TYPE=TEXT NAME="url7" VALUE="<?php echo $urls[6]; ?>"></TD></TR>
<TR><TD>URL 8</TD><TD><INPUT TYPE=TEXT NAME="url8" VALUE="<?php echo $urls[7]; ?>"></TD></TR>
<TR><TD>URL 9</TD><TD><INPUT TYPE=TEXT NAME="url9" VALUE="<?php echo $urls[8]; ?>"></TD></TR>
<TR><TD>URL 10</TD><TD><INPUT TYPE=TEXT NAME="url10" VALUE="<?php echo $urls[9]; ?>"></TD></TR>
	<TR><TD><INPUT TYPE=HIDDEN NAME="action" VALUE="save"></TD>
	    <TD><INPUT TYPE="Submit" NAME="Submit" VALUE="Edit"> [<A HREF="member.php?action=logout">LogOut</A>]</TD></TR>
	</TABLE>
	*Pausing your account will prevent it from being shown in the exchange, use this
		if your page is temporarly unavailable. It does not stop you from earning or using
	credits in any other way. Priority Users [over 100 credits] cannot pause their account.
	</FORM>
		
		
		
		<?php		
		include "footer.inc";
		exit();
	}
}
}
if ($action == "forgot") {
	?>
	<H1>Forgot Password</H1>
	Forgot your password? No Problem... Just enter your username below:
	<FORM METHOD="POST" ACTION="member.php">
	<INPUT TYPE=TEXT NAME="username"> <INPUT TYPE=HIDDEN NAME="action" VALUE="sendpass"><INPUT TYPE="Submit" NAME="Submit" Value="Get It">
	</FORM>
	<?php
	include "footer.inc";
	exit();
}

	if ($blaher > 0) {
		print("<FONT COLOR=RED><B>Invalid Username or Password</B></FONT>");
	}
?>
<H1>Members Only</H1>
<FORM METHOD="POST" ACTION="member.php">
<TABLE>
<TR><TD>Username</TD>
    <TD><INPUT TYPE=TEXT NAME="username"></TD></TR>
<TR><TD>Password</TD>
    <TD><INPUT TYPE=PASSWORD NAME="password"></TD></TR>
<tr><TD></TD>
    <TD><INPUT TYPE=HIDDEN NAME="action" VALUE="login"><INPUT TYPE="Submit" NAME="Submit" VALUE="Login"></TD></TR>
</TABLE>
<H6>Forgot your password? Click <A HREF="member.php?action=forgot">Here</A></H6>
</FORM>
<?php

include "footer.inc";
?>



