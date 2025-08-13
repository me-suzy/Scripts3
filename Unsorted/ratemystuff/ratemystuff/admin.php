<?php
#
# FILE: admin.php
# DATE: 01/10/03
# AUTHOR: ShaunC "Bulworth"
# PROJECT: RateMyStuff
# COPYRIGHT: PHPLabs.Com
# ----
# Description: Displays the administrative control menu. Handles
# deletion of pictures by tag. Handles disposition of user reports
# of offensive images.
#

require_once("config.php");

if(!$_REQUEST[action]){
  #Display the main administrative page
  
  $template = implode("", file("templates/admin.html"));

  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1", $db);
  $myrow = mysql_fetch_array($result);
  $numpics = $myrow[0];

  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1 AND approved=0", $db);
  $myrow = mysql_fetch_array($result);
  $approvenum = $myrow[0];

  $dayago = time() - 86400;
  $result = mysql_query("SELECT COUNT(id) FROM pictures WHERE active=1 AND adddate > $dayago", $db);
  $myrow = mysql_fetch_array($result);
  $newpics = $myrow[0];

  $result = mysql_query("SELECT SUM(numvotes) FROM pictures WHERE active=1", $db);
  $myrow = mysql_fetch_array($result);
  $numvotes = $myrow[0];

  $result = mysql_query("SELECT COUNT(username) FROM users", $db);
  $myrow = mysql_fetch_array($result);
  $numusers = $myrow[0];

  $result = mysql_query("SELECT COUNT(DISTINCT(textid)) FROM reports WHERE handled=0", $db);
  $myrow = mysql_fetch_array($result);
  $reportednum = $myrow[0];

  $result = mysql_query("SELECT DISTINCT(textid) FROM reports WHERE handled=0", $db);
  if(mysql_num_rows($result) > 0){
    #Build the reports review list
    $reviewlist .= <<<EOT
<br><br><table width="90%" border="0" cellspacing="2" cellpadding="0">
<tr align="center" valign="middle"> 
<td><b><font face="Verdana, Arial" size="1">Image Preview <br>
</font></b><font face="Verdana, Arial" size="1">(click to see full image in new 
window)</font></td>
<td><b><font face="Verdana, Arial" size="1">Disposition for This Image</b><br>(Click option to perform action)</font></td>
</tr>
EOT;
    while($myrow = mysql_fetch_array($result)){
      $reviewlist .= <<<EOT
<tr align="center" valign="middle"> 
<td><a href="getimage.php?tag=$myrow[textid]" target="_blank"><img src="$imageurl/img$myrow[textid].jpg" border=0 alt="View Reported Image" width="150" height="150"></a></td>
<td> 
<p><font face="Verdana, Arial" size="1"><a href="javascript:;" onClick="window.open('$_SERVER[PHP_SELF]?action=delete&tag=$myrow[textid]','Report','width=150,height=50,,location=no,toolbar=no,menubar=no,scrollbars=auto,resizable=no')">Delete 
Offensive Image</a></font></p>
<p><font face="Verdana, Arial" size="1"><a href="javascript:;" onClick="window.open('$_SERVER[PHP_SELF]?action=accept&tag=$myrow[textid]','Report','width=150,height=50,,location=no,toolbar=no,menubar=no,scrollbars=auto,resizable=no')">This 
Image is Acceptable</a></font></p>
</td>
</tr>
EOT;
    }
  }
  else{
    $reviewlist = "(When there are reports, the associated images will appear below with delete/approve links.)";
  }

  $template = str_replace("%numpics%", $numpics, $template);
  $template = str_replace("%newpics%", $newpics, $template);
  $template = str_replace("%numvotes%", $numvotes, $template);
  $template = str_replace("%numusers%", $numusers, $template);
  $template = str_replace("%approvenum%", $approvenum, $template);
  $template = str_replace("%reportednum%", $reportednum, $template);
  $template = str_replace("%reviewlist%", $reviewlist, $template);
  $template = str_replace("%phpself%", $_SERVER[PHP_SELF], $template);

  echo $template;
  exit;
}

if($_GET[action] == "delete"){
  #Delete the record associated with this tag
  $request = mysql_query("DELETE FROM pictures WHERE textid='$_GET[tag]'", $db);
  $request = mysql_query("DELETE FROM reports WHERE textid='$_GET[tag]'", $db);
  #Delete the image associated with this tag
  unlink("$imagedir/img$_GET[tag].jpg");
  echo "<font face='Verdana, Arial' size='2'><b>IMAGE DELETED</b> - This image has been removed.</font>";
}

if($_GET[action] == "accept"){
  #Ignore the reports about this image
  $request = mysql_query("DELETE FROM reports WHERE textid='$_GET[tag]'", $db);
  echo "<font face='Verdana, Arial' size='2'><b>IMAGE ACCEPTED</b> - Reports for this image have been ignored.</font>";
}

if($_POST[action] == "lookup"){
  #Figure out the owner information for this image
  $result = mysql_query("SELECT owner FROM pictures WHERE textid='$_POST[tag]'", $db);
  $myrow = mysql_fetch_array($result);
  $result = mysql_query("SELECT * FROM users WHERE username='$myrow[owner]'", $db);
  $myrow = mysql_fetch_array($result);
  echo <<<EOT
<p><font face="Verdana, Arial" size="2">Picture ID: $_POST[tag]<br>
Uploaded By: $myrow[username]<br>
Email Address: $myrow[email]<br>
IP Address: $myrow[lastip]</font></p>
EOT;
}

if($_POST[action] == "deletepic"){
  #Is this a valid image?
  $result = mysql_query("SELECT * FROM pictures WHERE textid='$_POST[tag]'", $db);
  if(mysql_num_rows($result) < 1){
    echo "<font face='Verdana, Arial' size='2'><b>INVALID IMAGE</b> - There was no image found with ID $_POST[tag].</font>";
    exit;
  }
  #Delete the record associated with this tag
  $request = mysql_query("DELETE FROM pictures WHERE textid='$_POST[tag]'", $db);
  $request = mysql_query("DELETE FROM reports WHERE textid='$_POST[tag]'", $db);
  #Delete the image associated with this tag
  unlink("$imagedir/img$_POST[tag].jpg");
  echo "<font face='Verdana, Arial' size='2'><b>IMAGE DELETED</b> - This image has been removed.</font>";
}

if($_POST[action] == "banuser"){
  if($_POST[type] == "email"){
    #Add this to the bannedemails table
    $result = mysql_query("INSERT INTO bannedemails SET email='$_POST[tag]'", $db);
    #Disable any account using this address
    $result = mysql_query("UPDATE users SET active=0 WHERE email='$_POST[tag]'", $db);
    $affected = mysql_affected_rows($db);
    echo "<font face='Verdana, Arial' size='2'><b>EMAIL BANNED</b> - The email address $_POST[tag] has been banned from signing up. $affected account(s) associated with this email address were disabled and can no longer login.</font>";
  }
  if($_POST[type] == "ip"){
    $result = mysql_query("INSERT INTO bannedips SET ipaddr='$_POST[tag]'", $db);
    echo "<font face='Verdana, Arial' size='2'><b>IP BANNED</b> - The IP address $_POST[tag] has been banned from signing up or uploading.</font>";
  }
}

if($_GET[action] == "emaillist"){
  $result = mysql_query("SELECT DISTINCT(email) FROM users", $db);
  header("Content-type: text/plain");
  while($myrow = mysql_fetch_array($result)){
    echo "$myrow[email]\n";
  }
}

?>