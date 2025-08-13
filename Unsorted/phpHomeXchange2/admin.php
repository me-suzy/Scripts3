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
$global_dbh = mysql_connect($dbhost, $dbusername, $dbpassword);
mysql_select_db($dbname, $global_dbh);

if ($password == $adminpass) {
    if(IsSet($action)) {
        if ($action == "editban") {
        ?>
<CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Banned List</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <B>Banned Ips:</B><BR>
       <TEXTAREA NAME="ip" COLS="47" ROWS="10"><?php
$BANIP = array();
$BANIP = file("bannedip.txt");
echo implode("", $BANIP);
       ?></TEXTAREA><P>
       <B>Banned Emails:</B><BR>
       <TEXTAREA NAME="email" COLS="47" ROWS="10"><?php
$BANMAIL = array();
$BANMAIL = file("bannedemail.txt");
echo implode("", $BANMAIL);
       ?></TEXTAREA><P>
       <B>Banned Urls:</B><BR>
       <TEXTAREA NAME="url" COLS="47" ROWS="10"><?php
$BANURL = array();
$BANURL = file("bannedurl.txt");
echo implode("", $BANURL);
       ?></TEXTAREA><P>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="action" VALUE="SaveBanned"><INPUT TYPE=SUBMIT NAME="no" VALUE="Close">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
  <?php
        }
        if ($action == "SaveBanned") {
        $fd = fopen("bannedurl.txt", "w+");
        $fout = fwrite($fd, $url);
        fclose($fd);
        
        $fd = fopen("bannedip.txt", "w+");
        $fout = fwrite($fd, $ip);
        fclose($fd);
        
        $fd = fopen("bannedemail.txt", "w+");
        $fout = fwrite($fd, $email);
        fclose($fd);
        
?>

         <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Banned List</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      The banned list was edited.</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="no" VALUE="OK!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
<?php
        exit();
        
        }
        if ($action == "stats") {
        $query = "SELECT creditsearned, creditsused FROM $dbtable WHERE ispaused<2";
	$result = mysql_query($query, $global_dbh);

	for ($i=0; $i<mysql_num_rows($result); $i++) {
	   $row = mysql_fetch_array($result);
	   $totcreditsearned = $totcreditsearned + $row[0];
	   $totcreditsused = $totcreditsused + $row[1];
	   
	}
           $totcreditsleft = $totcreditsearned - $totcreditsused;
	   $totcreditscount = $totcreditsearned + $totcreditsused;
        
        ?>
        <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Stats</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      <TABLE>
      <TR><TD>Users:</TD><TD><?php echo mysql_num_rows($result); ?></TD></TR>
      <TR><TD>Credits Earned:</TD><TD><?php echo $totcreditsearned; ?></TD></TR>
      <TR><TD>Credits Used:</TD><TD><?php echo $totcreditsused; ?></TD></TR>
      <TR><TD>Credits Left:</TD><TD><?php echo $totcreditsleft; ?></TD></TR>
      <TR><TD>Credits Counted:</TD><TD><?php echo $totcreditscount; ?></TD></TR>
      </TABLE>
      </P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="no" VALUE="OK!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
        
  <?php      exit();
        }
        if ($action == "Send") {
        

$query = "SELECT email FROM $dbtable";
$result = mysql_query($query, $global_dbh);

for ($i=0; $i<mysql_num_rows($result); $i++) {
   $row = mysql_fetch_array($result);
   $mailsend = mail($row[0], $subject, $message, "From: $adminemail");
   echo "Email Sent To <B>" . $row[0] . "</B><BR>";
}
?>
         <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Email Users</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      Your Message was sent to <?php echo mysql_num_rows($result); ?> users!</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="no" VALUE="OK!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
        
  <?php      exit();
        }
        if ($action == "mail") {
        ?>
        <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Email Users</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      <FORM ACTION="admin.php" METHOD="POST">
        <TABLE CELLPADDING="2" CELLSPACING="0" BORDER="0">
      <TR>
       <TD VALIGN=TOP>
        <P>
         Subject</TD>
       <TD VALIGN=TOP>
        <P>
         <INPUT TYPE=TEXT NAME="subject" SIZE="20" MAXLENGTH="256"></TD>
      </TR>
      <TR>
       <TD VALIGN=TOP>
        <P>
         Message</TD>
       <TD VALIGN=TOP>
        <P>
         <TEXTAREA NAME="message" COLS="50" ROWS="8"></TEXTAREA></TD>
      </TR>
      <TR>
       <TD VALIGN=TOP></TD>
       <TD VALIGN=TOP>
        <P>
         </TD>
      </TR>
     </TABLE>
       <CENTER>
       <P ALIGN=CENTER>
        <INPUT TYPE=SUBMIT NAME="action" VALUE="Send"><INPUT TYPE=SUBMIT NAME="no" VALUE="Cancel">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
        <?php
        exit();
        }
        if ($action == "Edit") {
        $query = "SELECT * FROM $dbtable WHERE username = '$selected'";
$result = mysql_query($query, $global_dbh);
$row = mysql_fetch_array($result);
  ?>
        <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Edit</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      <FORM ACTION="admin.php" METHOD="POST">
      <TABLE>
      <TR><TD>Username:</TD><TD><INPUT TYPE=hidden NAME="selected" VALUE="<?php echo $selected; ?>"><?php echo $selected; ?></TD></TR>
      <TR><TD>Password:</TD><TD><INPUT TYPE=TEXT NAME="pword" VALUE="<?php echo $row["password"];?>"></TD></TR>
      <TR><TD>Email:</TD><TD><INPUT TYPE=TEXT NAME="email" VALUE="<?php echo $row["email"];?>"></TD></TR>
      <TR><TD>LastIp:</TD><TD><INPUT TYPE=TEXT NAME="lastip" VALUE="<?php echo $row["lastip"];?>"></TD></TR>
      <TR><TD>URLs:</TD><TD><INPUT TYPE=TEXT NAME="urls" VALUE="<?php echo $row["urls"];?>"></TD></TR>
      <TR><TD>Credits Earned:</TD><TD><INPUT TYPE=TEXT NAME="creditsearned" VALUE="<?php echo $row["creditsearned"];?>"></TD></TR>
      <TR><TD>Credits Used:</TD><TD><INPUT TYPE=TEXT NAME="creditsused" VALUE="<?php echo $row["creditsused"];?>"></TD></TR>
      <TR><TD>Referer:</TD><TD><INPUT TYPE=TEXT NAME="referer" VALUE="<?php echo $row["referer"];?>"></TD></TR>
      <TR><TD>Member Level:</TD><TD><INPUT TYPE=TEXT NAME="memberlevel" VALUE="<?php echo $row["memberlevel"];?>"></TD></TR>
      <TR><TD>CheckCode:</TD><TD><INPUT TYPE=TEXT NAME="checkcode" VALUE="<?php echo $row["checkcode"];?>"></TD></TR>
      <TR><TD>Refer Credits:</TD><TD><INPUT TYPE=TEXT NAME="refercredits" VALUE="<?php echo $row["refercredits"];?>"></TD></TR>
      <TR><TD>IsPaused:</TD><TD><INPUT TYPE=TEXT NAME="ispaused" VALUE="<?php echo $row["ispaused"];?>"></TD></TR>
      <TR><TD>LastTime:</TD><TD><INPUT TYPE=TEXT NAME="lasttime" VALUE="<?php echo $row["lasttime"];?>"></TD></TR>
       </TABLE><CENTER>
       <P ALIGN=CENTER>
        <INPUT TYPE=SUBMIT NAME="action" VALUE="Save"><INPUT TYPE=SUBMIT NAME="no" VALUE="Close">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
        <?php
        exit();
        }
if ($action == "Save") {
        $query = "UPDATE $dbtable SET lastip='$lastip', referer='$referer', refercredits='$refercredits', lasttime='$lasttime', creditsearned='$creditsearned', creditsused='$creditsused', checkcode='$checkcode', memberlevel='$memberlevel', ispaused='$ispaused', password='$pword', email='$email', urls='$urls' WHERE username = '$selected'";
        $result = mysql_query($query, $global_dbh);
	echo mysql_error($global_dbh);
        ?>
         <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Edit</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      <?php echo $selected; ?> was edited.</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="no" VALUE="OK!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
   <?php
   exit();
        }
        if ($action == "Delete!") {
        $query = "DELETE FROM $dbtable WHERE username = '$selected'";
        $result = mysql_query($query, $global_dbh);
	echo mysql_error($global_dbh);
        ?>
         <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Delete</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
      <?php echo $selected; ?> was deleted.</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
       <INPUT TYPE=SUBMIT NAME="no" VALUE="OK!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
   <?php
   exit();
        }
    	if ($action == "Delete") {
    	?>
    	 <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - Delete</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       Are you sure you want to delete <?php echo $selected; ?>?</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER><INPUT TYPE="HIDDEN" NAME="selected" VALUE="<?php echo $selected; ?>">
        <INPUT TYPE=SUBMIT NAME="action" VALUE="Delete!"><INPUT TYPE=SUBMIT NAME="no" VALUE="Cancel">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
  <?php
  exit();
  }
	if ($action == "Search!") {
?>

	<CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange2 - Search Users</FONT></B></TD>
    </TR>
    <TR>
     <TD VALIGN=TOP>
      <P>
       <!-- $MVD$:spaceretainer() --> </P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
        <TABLE CELLPADDING="2" CELLSPACING="0" BORDER="0">
         <TR>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Select</TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Username</TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Credits</TD>
         </TR>
<?php
if ($option2 == "creditsleft") {
  $order = "(creditsearned - creditsused)";
}
else {
   $order = $option2;
}


$query = "SELECT username, creditsearned, creditsused FROM $dbtable WHERE 1 and $option like '%$search%'";

$result = mysql_query($query, $global_dbh);
echo mysql_error($global_dbh);

for ($i=0; $i<mysql_num_rows($result); $i++) {
   $row = mysql_fetch_array($result);
   ?>

<TR><TD VALIGN=TOP><CENTER>
<P ALIGN=CENTER>
<INPUT TYPE=RADIO NAME="selected" VALUE="<?php echo $row[0]; ?>"></TD>
<TD VALIGN=TOP>
          <P ALIGN=CENTER>
            <?php echo $row[0]; ?></TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            <?php echo ($row[1]-$row[2]); ?></TD>
         </TR>
   <?php
}
?>
</TABLE></P>
       </CENTER><CENTER>
       <P ALIGN=CENTER>
        <INPUT TYPE=SUBMIT NAME="action" VALUE="Edit"><INPUT TYPE=SUBMIT NAME="action" VALUE="Delete"><INPUT TYPE=SUBMIT NAME="ahhah" VALUE="Cancel">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
<?php
         exit();

	}
    	if ($action == "search") {
    	?>
            <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange2 - Search Users</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       What are you looking for?<P>
      <FORM ACTION="admin.php" METHOD="POST">
       <P ALIGN=LEFT>
        <INPUT TYPE=RADIO NAME="option" VALUE="username" CHECKED="true">Username<BR>
	<INPUT TYPE=RADIO NAME="option" VALUE="email">Email<BR>
	<INPUT TYPE=RADIO NAME="option" VALUE="urls">URLs<BR>
	<INPUT TYPE=RADIO NAME="option" VALUE="lastip">IP<BR>
        <INPUT TYPE=RADIO NAME="option" VALUE="referer">Referer</P>

       </DIV><CENTER>
       <P ALIGN=CENTER>
<INPUT TYPE=TEXT NAME="search">
<BR>
       <INPUT TYPE=SUBMIT NAME="action" VALUE="Search!">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
            <?php
            exit();
    	
    	}
    
    
          if (($action == "list-all") || ($action == "list-limit")) {
?>

	<CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange2 - List Users</FONT></B></TD>
    </TR>
    <TR>
     <TD VALIGN=TOP>
      <P>
       <!-- $MVD$:spaceretainer() --> </P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER>
        <TABLE CELLPADDING="2" CELLSPACING="0" BORDER="0">
         <TR>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Select</TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Username</TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            Credits</TD>
         </TR>
<?php
if ($option2 == "creditsleft") {
  $order = "(creditsearned - creditsused)";
}
else {
   $order = $option2;
}

if ($action == "list-all") {
$query = "SELECT username, creditsearned, creditsused FROM $dbtable order by $order $option";
}
else {
$z = $to - $from;
$query = "SELECT username, creditsearned, creditsused FROM $dbtable order by $order $option LIMIT $z, $from";
}
$result = mysql_query($query, $global_dbh);
echo mysql_error($global_dbh);

for ($i=0; $i<mysql_num_rows($result); $i++) {
   $row = mysql_fetch_array($result);
   ?>

<TR><TD VALIGN=TOP><CENTER>
<P ALIGN=CENTER>
<INPUT TYPE=RADIO NAME="selected" VALUE="<?php echo $row[0]; ?>"></TD>
<TD VALIGN=TOP>
          <P ALIGN=CENTER>
            <?php echo $row[0]; ?></TD>
          <TD VALIGN=TOP>
           <P ALIGN=CENTER>
            <?php if (($option2 == "creditsleft") || ($option2 == "username")) { echo ($row[1]-$row[2]); } else { echo $row["$option2"]; }?></TD>
         </TR>
   <?php
}
?>
</TABLE></P>
       </CENTER><CENTER>
       <P ALIGN=CENTER>
        <INPUT TYPE=SUBMIT NAME="action" VALUE="Edit"><INPUT TYPE=SUBMIT NAME="action" VALUE="Delete"><INPUT TYPE=SUBMIT NAME="ahhah" VALUE="Cancel">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
<?php
         exit();
         }
         if ($action == "list") {
            ?>
            <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - ADMIN</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       How would you like them listed?<P>
      <FORM ACTION="admin.php" METHOD="POST">
       <P ALIGN=LEFT>
        <INPUT TYPE=RADIO NAME="action" VALUE="list-all" CHECKED="true">List All<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="list-limit">List <INPUT TYPE=TEXT NAME="from" SIZE="3" MAXLENGTH="5">-<INPUT TYPE=TEXT NAME="to" SIZE="3" MAXLENGTH="5"></P>
       </DIV><DIV ALIGN=LEFT>
       <P ALIGN=LEFT>
        <INPUT TYPE=RADIO NAME="option" VALUE="ASC" CHECKED="true">Ascending<BR>
        <INPUT TYPE=RADIO NAME="option" VALUE="DESC">Descending</P>
       <P ALIGN=LEFT>
        <INPUT TYPE=RADIO NAME="option2" VALUE="username" CHECKED="true">Username<BR>
<INPUT TYPE=RADIO NAME="option2" VALUE="creditsused">Credits Used<BR>
<INPUT TYPE=RADIO NAME="option2" VALUE="creditsearned">Credits Earned<BR>
        <INPUT TYPE=RADIO NAME="option2" VALUE="creditsleft">Credits Left</P>

       </DIV><CENTER>
       <P ALIGN=CENTER>

       <INPUT TYPE=SUBMIT NAME="select" VALUE="List">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
            <?php
            exit();
         }
         if ($action == "configure") {
               ?>
<CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - ADMIN</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       For security purposes you must change settings manually.</P>
      <FORM ACTION="admin.php" METHOD="POST">
      <P ALIGN="center">
       <INPUT TYPE=SUBMIT NAME="select" VALUE="Go Back">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
               <?php
               exit();
         }
    }
    else {
    ?>
 <CENTER>
 <IMG SRC="http://www.theclixnetwork.com/scripts/check.cgi?version=php2">
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - ADMIN</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       What would you like to do?</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <P ALIGN=LEFT>
       	<INPUT TYPE=RADIO NAME="action" VALUE="mail">Mail Users<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="search">Search Users<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="list">List Users<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="editban">Banned Lists<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="stats">Xchange Stats</P>
       </DIV><DIV ALIGN=LEFT>
       <P ALIGN=LEFT>
        <INPUT TYPE=RADIO NAME="action" VALUE="configure">Configure<BR>
        <INPUT TYPE=RADIO NAME="action" VALUE="logout">Logout</P>
       </DIV><CENTER>
       <P ALIGN=CENTER>

       <INPUT TYPE=SUBMIT NAME="select" VALUE="Select">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>
    <? }
}
else {
?>

 <CENTER>
  <P ALIGN=CENTER>
   <TABLE WIDTH="250" CELLPADDING="0" CELLSPACING="0" BORDER="1" BORDERCOLOR="RED">
    <TR>
     <TD WIDTH="100%" BGCOLOR="RED" VALIGN=TOP>
      <P>
       <FONT COLOR="WHITE"><B>PHPHomeXchange 2.0 - ADMIN</B></FONT></TD>
    </TR>
    <TR>
     <TD WIDTH="100%" VALIGN=TOP>
      <P>
       Please Enter the administrator password:</P>
      <FORM ACTION="admin.php" METHOD="POST">
       <CENTER>
       <P ALIGN=CENTER><INPUT TYPE="HIDDEN" NAME="admin" VALUE="Enter">
        <INPUT TYPE=PASSWORD NAME="password" SIZE="20" MAXLENGTH="256"><INPUT TYPE=SUBMIT NAME="admin" VALUE="Enter">
       </FORM></TD>
    </TR>
   </TABLE></P>
  </CENTER>

<?php
}
?>