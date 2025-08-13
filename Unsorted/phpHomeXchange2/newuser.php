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
if (IsSet($action)) {
$BANIP = array();
$BANIP = file("bannedemail.txt");
for ($i=0; $i<count($BANIP); $i++) {
   if ($email == $BANIP[$i]) {
   	echo "<FONT COLOR=RED><B>That email address is being banned from using our services.</B></FONT>";
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
	$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
	mysql_select_db($dbname, $global_dbh);
	$query = "SELECT * FROM $dbtable WHERE username = '$username'";
	$result = @mysql_query($query, $global_dbh);
	$blaher = mysql_num_rows($result);
	if ($blaher > 0) {
			print("<FONT COLOR=RED><B>Username Taken</B></FONT>");		
			include "footer.inc";
			exit();
	}
	else {
		// seed random generator thingamaboby
		mt_srand((double)microtime()*1000000);
		// get random number from generator thingymabob
		for ($z = 0; $z < 7; $z++) {
			$pass = $pass . mt_rand(0, 9);
		}
		$date = time();
		$queryy = "INSERT INTO $dbtable(username, password, email, lastip, urls, creditsearned, creditsused, referer, memberlevel, checkcode, refercredits, ispaused, lasttime) VALUES('$username', '$pass', '$email', '$REMOTE_ADDR', '$url1|$url2|$url3|$url4|$url5|$url6|$url7|$url8|$url9|$url10', '$signupbonus','0','$referer', '0', '0', '0', '1', '$date')";
		$result = @mysql_query($queryy);
		if($result == 0) {
			print("Error Adding User");
			include "footer.inc";
			exit();
		}
		else {
			$mailsend = mail("$email", "Your Password", "Hey! Thank You For Joining! \r\nYou MUST activate your account. To do so, login the members area and unpause your account.\r\n\r\n Your Username is $username\r\n Your password is $pass\r\n Login at $scriptsurl/member.php \r\n\r\n PHPHomeXchange 2.0", "From: $adminemail");
			?>
			<H1>New User</H1>
			Before you can recieve credits, you need to activate your account by using the url
			sent to your email address <P>
			<B>You have 10 days to activate your account</B><P>
			<!--[if IE 5]>
			<B>You have IE 5, click
			<A HREF="#"
			onClick="this.style.behavior='url(#default#homepage)';
			this.setHomePage('<?php print("$scriptsurl"); ?>/start.cgi?<?php print("$username"); ?>');">
			here
			</A> to automaticaly set your start page as your start url.<B>
			<![endif]-->
		<?php
			include "footer.inc";
			exit();
		}
	}
}
else {
?>
	<FORM METHOD="POST" ACTION="newuser.php">
<H1>New User</H1>
To become a member, fill out this form.<P>
<TABLE>
<TR><TD>Username</TD>
    <TD><INPUT TYPE=TEXT NAME="username"></TD></TR>
<TR><TD>Password</TD>
    <TD>Will Be Emailed To You</TD></TR>
<TR><TD>Email</TD>
    <TD><INPUT TYPE=TEXT NAME="email"></TD></TR>
<TR><TD>URL 1</TD><TD><INPUT TYPE=TEXT NAME="url1"></TD></TR>
<TR><TD>URL 2</TD><TD><INPUT TYPE=TEXT NAME="url2"></TD></TR>
<TR><TD>URL 3</TD><TD><INPUT TYPE=TEXT NAME="url3"></TD></TR>
<TR><TD>URL 4</TD><TD><INPUT TYPE=TEXT NAME="url4"></TD></TR>
<TR><TD>URL 5</TD><TD><INPUT TYPE=TEXT NAME="url5"></TD></TR>
<TR><TD>URL 6</TD><TD><INPUT TYPE=TEXT NAME="url6"></TD></TR>
<TR><TD>URL 7</TD><TD><INPUT TYPE=TEXT NAME="url7"></TD></TR>
<TR><TD>URL 8</TD><TD><INPUT TYPE=TEXT NAME="url8"></TD></TR>
<TR><TD>URL 9</TD><TD><INPUT TYPE=TEXT NAME="url9"></TD></TR>
<TR><TD>URL 10</TD><TD><INPUT TYPE=TEXT NAME="url10"></TD></TR>
<TR><TD><INPUT TYPE=HIDDEN NAME="referer" VALUE="<?php print("$referer"); ?>">
        <INPUT TYPE=HIDDEN NAME="action" VALUE="join"></TD>
    <TD><INPUT TYPE="Submit" NAME="Submit" VALUE="Sign Up"></TD></TR>
</TABLE>
<H4>By Using StartXchange You Agree To The <A HREF="tos.php">TOS</A></H4>
<?php
}
include "footer.inc";
?>



