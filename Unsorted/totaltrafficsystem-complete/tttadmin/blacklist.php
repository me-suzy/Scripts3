<?php
//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
require("../ttt-mysqlvalues.inc.php");
require("../ttt-mysqlfunc.inc.php");
open_conn();
require("security.inc.php");

if ($_POST["dodelete"] != "") {
mysql_query("DELETE FROM ttt_blacklist WHERE sitedomain='$_POST[sitedomain]'") or print_error(mysql_error());
}
elseif ($_POST["edit"] != "" AND $_POST["sitedomain"] != "") {
$res = mysql_query("SELECT * FROM ttt_blacklist WHERE sitedomain='$_POST[sitedomain]'") or print_error(mysql_error());
$row = mysql_fetch_array($res);
extract($row);
$msg = "Edit $_POST[sitedomain]";
}
elseif ($_POST["update"] != "") {
	mysql_query("UPDATE ttt_blacklist SET email='$_POST[email]', icq='$_POST[icq]', reason='$_POST[reason]' WHERE sitedomain='$_POST[sitedomain]'") or print_error(mysql_error());
	$msg = "$_POST[sitedomain] Updated";
}
elseif ($_POST["add"] != "") { $msg = "Add New Site To Blacklist"; }
elseif ($_POST["addnew"] != "") {
	if ($_POST["sitedomain"] == "") { print_error("Please type in a site domain"); }
	$res = mysql_query("SELECT sitedomain FROM ttt_blacklist WHERE sitedomain='$_POST[sitedomain]'") or print_error(mysql_error());
	if (mysql_num_rows($res) > 0) { print_error("Site domain already in blacklist"); }
	mysql_query("INSERT INTO ttt_blacklist (sitedomain, email, icq, reason) VALUES ('$_POST[sitedomain]', '$_POST[email]', '$_POST[icq]', '$_POST[reason]')") or print_error(mysql_error());
	$msg = "$_POST[sitedomain] Added To Blacklist";
}
else {
$res = mysql_query("SELECT * FROM ttt_blacklist ORDER BY sitedomain") or print_error(mysql_error());
$msg = "Blacklist";
}
close_conn();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Blacklist</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../ttt-style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF">
<?php if ($_POST["delete"] != "" AND $_POST["sitedomain"] != "") { ?>
<form action="blacklist.php" method="POST">
<input type="hidden" name="sitedomain" value="<?php echo $_POST["sitedomain"]; ?>">
<div align="center"><b><font size="4">Delete <?php echo $_POST["sitedomain"]; ?> From Blacklist?<br>
  <input name="dodelete" type="submit" value="Yes, Delete It" class="buttons">&nbsp;&nbsp;
  <input name="goback" type="submit" value="No, Go Back" class="buttons">
  </font></b> </div>
</form>
<?php } elseif ($_POST["dodelete"] != "") { ?>
<div align="center"><b><font size="4"><?php echo $_POST["sitedomain"]; ?> Deleted From Blacklist<br></font></b>
<br><br><br><br><br><br><br><br>
</div>
<?php } elseif (($_POST["edit"] != "" AND $_POST["sitedomain"] != "") OR $_POST["update"] != "") { ?>
<form action="blacklist.php" method="POST">
<div align="center"><font size="4"><b><?php echo $msg; ?></b></font><br>
  <br>
</div>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr> 
          <td colspan="2" class="toprows">Edit Blacklist</td>
        </tr>
        <tr class="normalrow"> 
          <td width="100" align="left"><b>Email:</b></td>
          <td align="left"><input name="email" type="text" id="email" size="30" maxlength="150" value="<?php echo $email; ?>"></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left"><b>ICQ:</b></td>
          <td align="left"><input name="icq" type="text" id="icq" size="30" maxlength="20" value="<?php echo $icq; ?>"></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left"><b>Reason:</b></td>
          <td align="left"><textarea name="reason" cols="30" rows="5" id="reason"><?php echo $reason; ?></textarea></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">&nbsp;<input type="hidden" name="sitedomain" value="<?php echo $_POST["sitedomain"]; ?>"></td>
          <td align="left"><input name="update" type="submit" class="buttons" id="update" value="Update"></td>
        </tr>
      </table></td>
  </tr>
</table>
</form>
<?php } elseif ($_POST["add"] != "" OR $_POST["addnew"] != "") { ?>
<form action="blacklist.php" method="POST">
<div align="center"><font size="4"><b><?php echo $msg; ?></b></font><br>
  <br>
</div>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr> 
          <td colspan="2" class="toprows">Add New Site To Blacklist</td>
        </tr>
        <tr class="normalrow"> 
          <td width="100" align="left"><b>Site Domain:</b></td>
          <td align="left"><input name="sitedomain" type="text" size="30" maxlength="150"></td>
        </tr>
        <tr class="normalrow"> 
          <td width="100" align="left"><b>Email:</b></td>
          <td align="left"><input name="email" type="text" id="email" size="30" maxlength="150"></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left"><b>ICQ:</b></td>
          <td align="left"><input name="icq" type="text" id="icq" size="30" maxlength="20"></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left"><b>Reason:</b></td>
          <td align="left"><textarea name="reason" cols="30" rows="5" id="reason"></textarea></td>
        </tr>
        <tr class="normalrow"> 
          <td align="left">&nbsp;</td>
          <td align="left"><input name="addnew" type="submit" class="buttons" value=" Add "></td>
        </tr>
      </table></td>
  </tr>
</table>
</form>
<?php } else { ?>
<form action="blacklist.php" method="POST">
<div align="center"><b><font size="4">Blacklist</font></b><br>
  <br>
</div>
<table width="550" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr class="toprows"> 
          <td width="25%">Site Domain</td>
          <td width="25%">Email</td>
          <td width="15%">ICQ</td>
          <td width="35%">Reason</td>
          <td>&nbsp;</td>
        </tr>
<?php
if (mysql_num_rows($res) == 0) { ?>
        <tr class="normalrow"> 
          <td colspan="5"><font size="3"><b>NO SITE DOMAINS IN DATABASE</b></font></td>
        </tr>
<?php
}
while ($row = mysql_fetch_array($res)) {
extract($row);
if (strlen($reason) > 25) { $reason = substr($reason, 0, 25) . "..."; }
print <<<END
        <tr class="normalrow"> 
          <td align="left">$sitedomain</td>
          <td align="left">$email</td>
          <td align="left">$icq</td>
          <td align="left">$reason</td>
          <td><input type="radio" name="sitedomain" value="$sitedomain"></td>
        </tr>
END;
}
?>
      </table></td>
  </tr>
</table>
<div align="center"><br>
  <input name="add" type="submit" class="buttons" id="add" value=" Add ">
  &nbsp; 
  <input name="edit" type="submit" class="buttons" id="edit" value=" Edit ">
  &nbsp; 
  <input name="delete" type="submit" class="buttons" id="delete" value="Delete">
</div>
<?php } ?>
<p align="center"><a href="blacklist.php">Back To Blacklist</a><br><br><a href="javascript:window.close()">Close Window</a></p>
</body>
</form>
</html>
