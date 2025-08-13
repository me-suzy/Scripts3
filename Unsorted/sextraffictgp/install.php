<?php
if($submit){

if ((!$db_host) || (!$db_user) || (!$db_pass) || (!$db_name))
{
header("Location: install.php");
exit;
}############################################


$time = date("Y-m-d");

$sql = array("CREATE TABLE st_admin (
  id int(11) NOT NULL auto_increment,
  admin_username varchar(16) NOT NULL default '',
  admin_password varchar(25) NOT NULL default '',
  admin_email varchar(255) NOT NULL default '',
  admin_groupid smallint(6) NOT NULL default '3',
  PRIMARY KEY  (id)
);",

"INSERT INTO st_admin VALUES (1, 'admin', 'admin', 'admin@yourdomain.com', 1);",

"CREATE TABLE st_admin_group (
  usergroupid smallint(5) unsigned NOT NULL auto_increment,
  usertitle char(100) NOT NULL default '',
  cansettings char(1) NOT NULL default '',
  canbanwords char(1) NOT NULL default '',
  cancategory char(1) NOT NULL default '',
  cantemplates char(1) NOT NULL default '',
  cangalleries char(1) NOT NULL default '',
  canstats char(1) NOT NULL default '',
  canadmin char(1) NOT NULL default '',
  PRIMARY KEY  (usergroupid)
);",


"INSERT INTO st_admin_group VALUES (1, 'Administrator', '1', '1', '1', '1', '1', '1', '1');",
"INSERT INTO st_admin_group VALUES (2, 'Gallery Helper', '', '', '', '', '1', '', '');",



"CREATE TABLE st_banned (
  bid int(11) NOT NULL auto_increment,
  banned_url varchar(255) NOT NULL default '',
  PRIMARY KEY  (bid)
);",



"CREATE TABLE st_categories (
  cid int(11) NOT NULL auto_increment,
  catname varchar(255) NOT NULL default '',
  visable char(1) NOT NULL default '',
  advert text NOT NULL,
  catimage varchar(255) NOT NULL default '',
  catorder int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (cid)
);",



"CREATE TABLE st_counter (
  count int(10) unsigned NOT NULL default '1'
);",


"CREATE TABLE st_counter2 (
  count int(10) unsigned NOT NULL default '0',
  date date NOT NULL default '0000-00-00'
);",



"CREATE TABLE st_links (
  linkid int(11) NOT NULL auto_increment,
  url varchar(255) NOT NULL default '',
  catid varchar(10) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  des varchar(255) NOT NULL default '',
  numpics varchar(4) NOT NULL default '',
  clicks mediumint(9) NOT NULL default '0',
  approved char(1) NOT NULL default '',
  date datetime default NULL,
  cf varchar(25) NOT NULL default '',
  confirm char(1) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  ip varchar(255) NOT NULL default '',
  rec varchar(255) NOT NULL default '',
  PRIMARY KEY  (linkid)
);",



"CREATE TABLE st_options (
  op smallint(5) unsigned NOT NULL auto_increment,
  tablewidth varchar(10) NOT NULL default '',
  sitename varchar(255) NOT NULL default '',
  header text NOT NULL,
  footer text NOT NULL,
  limitlinks varchar(25) NOT NULL default '',
  displaydate varchar(255) NOT NULL default '',
  keywords text NOT NULL,
  content text NOT NULL,
  sitetitle varchar(255) NOT NULL default '',
  background varchar(7) NOT NULL default '',
  text varchar(7) NOT NULL default '',
  linkcolor varchar(7) NOT NULL default '',
  linkcolor2 varchar(7) NOT NULL default '',
  archivelimit varchar(255) NOT NULL default '',
  datecolor varchar(7) NOT NULL default '',
  adminemail varchar(255) NOT NULL default '',
  siteurl varchar(255) NOT NULL default '',
  recip varchar(255) NOT NULL default '',
  submityn char(1) NOT NULL default '',
  submitynreason text NOT NULL,
  recipyn char(1) NOT NULL default '',
  stversion varchar(40) NOT NULL default '',
  turncatliston char(1) NOT NULL default '',
  installdate date NOT NULL default '0000-00-00',
  orderedby varchar(11) NOT NULL default '0',
  wayorder varchar(11) NOT NULL default '',
  PRIMARY KEY  (op),
  UNIQUE KEY ID (op)
);",

"INSERT INTO st_options VALUES (1, '750', 'SexTraffic TGP', '<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\r\n  <tr> \r\n    <td background=\"/img/bg.gif\">\r\n      <div align=\"center\"><font size=\"4\"><img src=\"/img/logo.gif\" width=\"299\" height=\"100\"></font></div>\r\n    </td>\r\n  </tr>\r\n</table>', '<table width=\"750\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\r\n  <tr>\r\n    <td><center><font face=\"arial\" size=\"2\"><b> - <a href=\"submit.php\">Webmasters</a> - </b></font></center></td>\r\n  </tr>\r\n</table>', '20', '%Y/%d/%m', 'sex, traffic, porn, adult, network, tgp, party goat', 'SexTraffic.net', 'SexTraffic TGP', 'FFFFFF', '000000', '#003399', '#33CC33', '500', '#003399', 'support@sextraffic.net', 'http://www.sextraffic.net', 'http://www.sextraffic.net', 'N', 'Sorry, <br><br>\r\nWe are currently have an overflow of submissions, come back later.br><br>\r\nThank you<br><br>\r\nSexTraffic TGP', 'N', 'Version 1.0', 'Y', '$time', 'catname', 'ASC');",



"CREATE TABLE st_ref (
  counter int(10) unsigned NOT NULL default '0',
  site text NOT NULL
);",



"CREATE TABLE st_template (
  templateid smallint(5) unsigned NOT NULL auto_increment,
  tempsetid smallint(6) NOT NULL default '1',
  title varchar(100) NOT NULL default '',
  tempcontent mediumtext NOT NULL,
  tempinfo text NOT NULL,
  catset varchar(10) NOT NULL default '',
  PRIMARY KEY  (templateid)
);",

"INSERT INTO st_template VALUES (1, 1, 'submit_before', '<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\r\n  <tr>\r\n    <td>\r\n      <div align=\"center\"><font size=\"4\"><b><font face=\"Arial\">Message before \r\n        submit</font></b></font></div>\r\n    </td>\r\n  </tr>\r\n</table>\r\n', 'HTML before the submit form.', '1');",
"INSERT INTO st_template VALUES (2, 1, 'submit_after', '<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\r\n  <tr>\r\n    <td>\r\n      <div align=\"center\"><font size=\"4\"><b><font face=\"Arial\">Message after submit</font></b></font></div>\r\n    </td>\r\n  </tr>\r\n</table>', 'HTML after the submit form.', '1');",
"INSERT INTO st_template VALUES (3, 1, 'submit_urlexists', '<br><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"15\" align=\"center\">\r\n<tr>\r\n<td>\r\n<center>\r\n<div align=\"left\"><b><font size=\"4\" face=\"Arial\">There is an error....</font><font size=\"3\" face=\"Arial\"><br>\r\n</font></b><font size=\"3\" face=\"Arial\">A link already exists with that <b>\"url\".</b> Please go <a href=\"javascript:history.back(-1)\"><b>back</b></a> and try again.<br><br>\r\n\$sitename<br>\r\n\$adminemail<br></font>\r\n</div>\r\n</center>\r\n</td>\r\n</tr>\r\n</table>', 'The URL of the submitted gallery already exists in the database.', '1');",
"INSERT INTO st_template VALUES (4, 1, 'submit_bannedemail', '<table width=\"90%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\" bordercolor=\"#3333FF\">\r\n  <tr> \r\n    <td bgcolor=\"#3333FF\"><font size=\"4\"><b><font face=\"Arial\" color=\"#FFFFFF\">ERROR:</font></b></font></td>\r\n  </tr>\r\n  <tr> \r\n    <td bgcolor=\"#EFEFEF\"><font face=\"Arial\" size=\"3\">Your email address is banned from posting galleries.</font></td>\r\n  </tr>\r\n</table>', 'Banned email of the submitted user.', '1');",
"INSERT INTO st_template VALUES (5, 1, 'submit_bannedurl', '<table width=\"90%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\" bordercolor=\"#3333FF\">\r\n  <tr> \r\n    <td bgcolor=\"#3333FF\"><font size=\"4\"><b><font face=\"Arial\" color=\"#FFFFFF\">ERROR:</font></b></font></td>\r\n  </tr>\r\n  <tr> \r\n    <td bgcolor=\"#EFEFEF\"><font face=\"Arial\" size=\"3\">Your domain (URL) is banned from posting galleries.</font></td>\r\n  </tr>\r\n</table>', 'Banned url of the submitted gallery', '1');",
"INSERT INTO st_template VALUES (6, 1, 'submit_cantfindurl', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\r\n<HTML>\r\n<HEAD>\r\n<TITLE>\$sitetitle</TITLE>\r\n<META NAME=\"Generator\" CONTENT=\"SexTraffic.net\">\r\n<META NAME=\"Keywords\" CONTENT=\"\$keywords\">\r\n<META NAME=\"Description\" CONTENT=\"\$content\">\r\n</HEAD>\r\n<body bgcolor=\"\$background\" text=\"\$text\" leftmargin=\"0\" bottomMargin=\"0\" rightMargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">\r\n<br>\r\n<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\r\n  <tr>\r\n    <td bgcolor=\"#7289FA\"><font size=\"4\"><b><font face=\"Arial\" color=\"#FFFFFF\">Error:</font></b></font></td>\r\n  </tr>\r\n  <tr>\r\n    <td><font size=\"2\"><font face=\"Arial\">Cant find URL (<b>\$url</b>), please go back and try \r\n      again.</font></font></td>\r\n  </tr>\r\n</table>\r\n\r\n</body>\r\n</html>\r\n', 'The submitted URL does not register on the internet.', '1');",
"INSERT INTO st_template VALUES (7, 1, 'submit_accepted', '<html>\r\n<head>\r\n<title>\$sitename - Submission accepted</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\r\n</head>\r\n\r\n<body bgcolor=\"\$bgcolor\" text=\"\$text\">\r\n<table cellpadding=\"10\" cellspacing=\"0\" border=\"0\" width=\"700\" align=\"center\">\r\n  <tr> \r\n    <td bgcolor=\"#333366\"><font size=\"4\"><b><font face=\"Arial\" color=\"#FFFFFF\">Submission accepted</font></b></font></td>\r\n  </tr>\r\n  <tr> \r\n    <td><font size=\"3\" face=\"Arial\">Thank you <b>\$contact_name</b> for your adding \r\n      your website.<br>\r\n      <br>\r\n      <b>Url:</b> \$url<br>\r\n      <b>Description:</b> \$des<br>\r\n      <b>Contact name:</b> \$name<br>\r\n      <b>Email:</b> \$email<br>\r\n      <br>\r\n      You will be sent an email to confirm your submission. You will need to click \r\n      the links inside this email for us to accept your gallery.<br>\r\n      <br>\r\n      If you do not hear from us, then your link was not approved.<br>\r\n      <br>\r\n      <b>Regards,<br>\r\n      \$sitename</b> </font></td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', 'Submission accepted into the database', '1');",
"INSERT INTO st_template VALUES (8, 1, 'submit_disabled', '<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\">\r\n  <tr>\r\n    <td bgcolor=\"#003399\">\r\n      <div align=\"center\"><font size=\"3\"><b><font face=\"Arial\" color=\"#FFFFFF\">Submissions have been disabled</font></b></font></div>\r\n    </td>\r\n  </tr>\r\n  <tr>\r\n    <td><font size=\"2\" face=\"Arial\" color=\"#000000\">\$submitynreason</font></td>\r\n  </tr>\r\n</table>', 'HTML to stop anymore submissions.', '1');",
"INSERT INTO st_template VALUES (9, 1, 'submit_norecip', 'Your website does not have a reciprical link, please double check and try again.', 'HTML to say your site requires a recipical link.', '1');",
"INSERT INTO st_template VALUES (10, 1, 'submit_fieldempty', 'Sorry, but you have not entered all the required feilds, please go back one and fill in the form.', 'A field has been left empty.', '1');",
"INSERT INTO st_template VALUES (11, 1, 'submit_confirmemail', 'Hello \$name,\r\n\r\nThanks for submitting your gallery to \$sitename.\r\nURL: \$url\r\nDescription: \$des\r\n\r\nTo confirm your submission please click on the link below:\r\n\$siteurl/confirm.php?cf=\$confirm\r\n\r\nP.S.: If you have no idea what this email is about, then please delete it because somebody probably used your email address to submit to \$sitename\r\n\r\nBest regards,\r\n\$sitename', 'Confirm email sent to gallery maker after submission.', '1');",
"INSERT INTO st_template VALUES (12, 1, 'submit_confirmemailsubject', 'Confirmation of gallery submission', 'Confirm email subject to gallery maker.', '1');",
"INSERT INTO st_template VALUES (13, 1, 'index_copyright', '<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\r\n  <tr> \r\n    <td> \r\n      <div align=\"center\"><font face=\"Verdana\" size=\"1\">Powered By <a href=\"http://www.sextraffic.net\"><b>SexTraffic TGP</b></a></font></div>\r\n    </td>\r\n    </tr>\r\n  </table>', '', '2');",
"INSERT INTO st_template VALUES (14, 1, 'confirm_message', '<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" bgcolor=\"\$color2\">\r\n  <tr>\r\n    <td><font size=\"4\"><b><font face=\"Arial\">Gallery confirmed...</font></b></font></td>\r\n  </tr>\r\n  <tr>\r\n    <td>\r\n      <br><font size=\"4\" face=\"Arial\"><b>Thanks.</b></font><br><br>\r\n	<font face=\"Arial\" size=\"3\">Your gallery has been placed in our que and will be reviewed shortly.</font<br>\r\n    </td>\r\n  </tr>\r\n</table>', 'HTML that displays when a gallery maker confirms his/her submission via email.', '3');",
"INSERT INTO st_template VALUES (15, 1, 'confirm_no_message', '<br><table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\r\n  <tr>\r\n    <td bgcolor=\"7289FA\"><font size=\"4\"><b><font face=\"Arial\" color=\"#FFFFFF\">Error:</font></b></font></td>\r\n  </tr>\r\n  <tr>\r\n    <td><font size=\"3\"><b><font face=\"Arial\">Sorry, gallery ID does not exist</font></b></font></td>\r\n  </tr>\r\n</table>', 'HTML to display if the gallery ID is wrong or does not exist.', '3');",
"INSERT INTO st_template VALUES (16, 1, 'index_links', '<b><font color=\"\$datecolor\">(-\$date-)</font><a href=\"visit.php?linkid=\$linkid\" target=\"_blank\"> \$numpics \$des</a> - \$catname</b><br>', 'Gallery url links<br>\r\n<b>Original tracking system:</b> visit.php?linkid=\$linkid', '2');",
"INSERT INTO st_template VALUES (17, 1, 'index_beforelinks', '<table width=\"\$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\r\n  <tr>\r\n    <td valign=\"top\"><font size=\"2\" face=\"arial\">', 'HTML before your gallery links', '2');",
"INSERT INTO st_template VALUES (18, 1, 'index_afterlinks', '</font></td>\r\n<td valign=\"top\" width=\"200\">\r\n\r\n<!--  START: PUT YOUR ADS BETWEEN HERE -->\r\n\r\n<!--  END :  PUT YOUR ADS BETWEEN HERE -->\r\n\r\n</td>\r\n  </tr>\r\n</table>', 'HTML after gallery links.', '2');",
"INSERT INTO st_template VALUES (19, 1, 'index_cats_before', '<table width=\"\$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\"> \r\n<tr> \r\n<td><center>\r\n<font size=\"1\" face=\"verdana\">Last updated: \$lastupdated</font><br><br>\r\n<font size=\"3\" face=\"arial\"><b>Quick Jump To Your Favorite Section</b></font><br>\r\n<font size=\"2\" face=\"arial\"><b>[ ', 'HTML at front of category listings', '2');",
"INSERT INTO st_template VALUES (20, 1, 'index_cats_link', '<a href=\"cat.php?cid=\$cid\">\$catname</a>', 'HTML that links the category pages', '2');",
"INSERT INTO st_template VALUES (21, 1, 'index_cats_between', ' | ', 'HTML between each category link', '2');",
"INSERT INTO st_template VALUES (22, 1, 'index_cats_after', ' ] </b></font></center></td>\r\n</tr>\r\n</table>', 'HTML table after the category listings', '2');",
"INSERT INTO st_template VALUES (23, 1, 'cats_advert', '<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\"><tr><td><div align=\"center\">\$advert</div></td></tr></table>', 'HTML for category advert at top of cats.php page.', '4');",
"INSERT INTO st_template VALUES (24, 1, 'cats_catname', '<table width=\"\$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\r\n<tr>\r\n<td><font size=\"6\" face=\"arial\"><b>\$catname</b></font></td>\r\n</tr>\r\n</table>', 'HTML displays category name.', '4');",
"INSERT INTO st_template VALUES (25, 1, 'cats_beforelinks', '<table width=\"\$tablewidth\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\r\n  <tr>\r\n    <td valign=\"top\">\r\n<font size=\"2\" face=\"arial\">', 'HTML Table before links in cats.php', '4');",
"INSERT INTO st_template VALUES (26, 1, 'cats_links', '<font color=\"\$datecolor\"><b>(-\$date-)</b></font><a href=\"\$url\" target=\"_blank\"> \$numpics \$des (\$catname)</a><br>', 'Gallery url links<br>\r\n<b>Original tracking system:</b> visit.php?linkid=\$linkid', '4');",
"INSERT INTO st_template VALUES (27, 1, 'cats_afterlinks', '</b></font></div></td>\r\n<td valign=\"top\" width=\"200\"> </tr>\r\n</table>', 'HTML table after links', '4');",



"CREATE TABLE st_template_cats (
  tcid int(11) NOT NULL auto_increment,
  tempcatname varchar(255) NOT NULL default '',
  PRIMARY KEY  (tcid)
);",

"INSERT INTO st_template_cats VALUES (1, 'Submit.php Templates');",
"INSERT INTO st_template_cats VALUES (2, 'Index.php Templates');",
"INSERT INTO st_template_cats VALUES (3, 'Confirm.php Templates');",
"INSERT INTO st_template_cats VALUES (4, 'cat.php Templates');");

$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or die("Unable to connect to database server.");
mysql_select_db($db_name, $cnx)
		or die("Unable to select database.");

for ($i = 0; $i < count($sql); $i++)
{
	$result = (mysql_query($sql[$i]));
	if (!$result)
		die("The install failed for some reason.  Are you sure the database exists and your connection details are correct?");
}
    
############################################

?>
<html>
<head>
<title>SexTraffic TGP - Install completed</title>
</head>
<center>
  <table width="600" border="0" cellspacing="0" cellpadding="10">
    <tr>
      <td bgcolor="#333366"><b><font size="3" face="Arial"> </font></b> <b><font size="3" face="Arial" color="#FFFFFF">Installation 
        Complete!</font></b> </td>
    </tr><tr>
      <td> 
        <p><font size="2" face="Arial">The database tables have been created. 
          <b>SexTraffic TGP </b> is now ready to run. <br>
          <br>
          <b>Tables added</b><br>
          <br>
          - Admin accounts<br>
          - Admin groups<br>
          - Banned words<br>
          - Categories<br>
          - Counters and refferals<br>
          - Site options<br>
          - Database templates</font><font size="2" face="Arial"><br>
          <br>
          <br>
          Delete the <b>install.php</b> file from your server, for security reasons.<br>
          <br>
          Once you have loged in, change your login and password.Default login 
          &amp; password are:<br>
          <br>
          Login: <b>admin</b><br>
          Password: <b>admin</b><br>
          <br>
          <br>
          <a href="admin/index.php"><b>You can login here</b></a></font>
        </td>
    </tr></table>
  <br>
  <font face="Verdana" size="1">Powered by: <a href="http://www.sextraffic.net">SexTraffic 
  TGP</a> Version 1.0<br>
  Copyright &copy;2002 SexTraffic Network</font></center>
</body></html>
<?php 
exit;}
?>

<html>
<head>
<title>SexTraffic TGP - Install</title>
</head>
<body><form action="<?=$PHP_SELF?>" method="post">
  <table width="600" border="0" cellpadding="10" cellspacing="0" align="center">
      <td> 
        <div align="center"><b><font size="4" face="Arial">SexTraffic TGP<br>
          Installation Script</font></b> <font size="4" face="Arial"><b>Software 
          Licence</b></font><br><br>
          <font size="2" face="Arial">By using this software you agree <b>NOT</b> 
          to remove any copyright notices.<br>
          If you do not agree to these terms, please do not install SexTraffic 
          TGP. <br>
          This script will create the database tables needed by SexTraffic TGP<br>
          </font><br>
          <b><font size="3" face="Arial">MySQL Database Details:</font><br>
          </b><br>
		  </div>
        <table width="500" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr> 
            <td width="50%"><div align="right"><font size="2" face="Arial"><b>Database Host:</b></font></div></td>
            <td width="50%"><input type="text" name="db_host" size="20" value="localhost"></td>
          </tr>
          <tr> 
            <td width="50%"><div align="right"><font size="2" face="Arial"><b> Database Username:</b></font></div></td>
            <td width="50%"><input type="text" name="db_user" size="20" value="Username"></td>
          </tr>
          <tr> 
            <td width="50%"><div align="right"><font size="2" face="Arial"><b>Database Password:</b></font></div></td>
            <td width="50%"><input type="text" name="db_pass" size="20" value="Password"></td>
          </tr>
          <tr> 
            <td width="50%"><div align="right"><font size="2" face="Arial"><b>Database Name:</b></font></div></td>
            <td width="50%"><input type="text" name="db_name" size="20"></td>
          </tr>
          <tr> 
            <td width="50%">&nbsp;</td>
            <td width="50%"><input type="submit" name="submit" value="Install Now"></td>
          </tr>
        </table>
      </td>
	 </table>
	 </form>
	 </body>
	 </html>