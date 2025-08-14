<?php
error_reporting(7);

require("includes/getglobals.php");

$version = "1.0.3 Pro";

if (function_exists("set_time_limit") == 1) {
  @set_time_limit(1200);
}

if (($step != "writeconfig") & ($step != "confirmempty") & ($step != "empty")) {
  settype($step,"integer");
}

if (!$step) {
  $step = 1;
}

function undoescapetext($string="") {
  $string = str_replace("||| ||","|||||",$string);
  $string = substr($string,1,-1);

  return $string;
}

?>
<html>
<head>
<title>Install VirtuaNews <?php echo $version?> Nullified by [WTN]&[WDYL]</title>

<script language="javascript">
<!--
function confirmempty() {
  if (confirm("You are about to clear ALL the contents from your database,\nthis is NOT reversable and you will loose EVERYTHING including any forum tables\n\nAre you sure?")) {
    if (confirm("You have chosen to clear your ENTIRE MySQL database.\nVirtuaNews can hold no responsibility\nfor any loss of data incurred as a result of performing this action.\n\nDo you agree to these terms?")) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
-->
</script>

<link rel="StyleSheet" href="admin/style.css" type="text/css">
</head>
<body>
<table cellspacing="0" cellpadding="2" class="header">
  <tr>
    <td class="header">Welcome to the VirtuaNews <?php echo $version; ?> install process<br>Nullified by [WTN]&[WDYL] `2004</td>
  </tr>
</table>
<table cellpadding="2" cellspacing="0" class="main">
<?

if ($step == "1") {

  echo "  <tr>\n    <td>Running this install script will do a clean install of VirtuaNews on your server</td>\n  </tr>\n";

  $perm_check_dir[] = "static";
  $perm_check_dir[] = "static/sub_pages";
  $perm_check_dir[] = "static/comment";
  $perm_check_dir[] = "static/index";
  $perm_check_dir[] = "static/polls";
  $perm_check_dir[] = "pages/default";
  $perm_check_dir[] = "pages/default/mod";
  $perm_check_dir[] = "pages/user";
  $perm_check_dir[] = "pages/user/mod";

  foreach ($perm_check_dir AS $dir) {

    $canwrite = @is_writeable($dir);

    if (!$canwrite) {
      if (!@chmod($dir,0773)) {
        echo "  <tr>\n    <td>Error cannot write to $dir directory and cannot change the permissions of it.  You must set the file permissions to allow writing, usually chmod(0777).  Please do this then refresh the page</td>\n  </tr>\n";
        $error = 1;
      }
    }
  }

  if (!$error) {
    echo "  <tr>\n    <td>Click <a href=\"install.php?step=2\">here</a> to continue</td>\n  </tr>\n";
  }

}

if ($step == "2") {

  $fileexists = @file_exists("admin/config.php");

  $canwrite = @fopen("admin/config.php","a");
  @fclose($canwrite);

  if (!$fileexists & !$canwrite) {
?>
  <tr>
    <td>Cannot find config.php file and cannot create one</td>
  </tr>
  <tr>
    <td>You must ensure you have uploaded this file to the admin directory and it looks something like this:</td>
  </tr>
  <tr>
    <td>
      <textarea cols="80" rows="15" class="form" readonly="readonly">&lt;?php
// type of database running
// (only mysql is supported at the moment)
$dbservertype = "mysql";

// hostname or ip of server
$servername = "localhost";

// username and password to log onto db server
$dbusername = "root";
$dbpassword = "";

// name of database
$dbname = "virtuanews";

// technical email address, to specify multiple emails seperate each one with a space
$technicalemail = "webmaster@yourhost.com";

// set the staff ids for the users which can prune the admin log
// enter a single id, or a string of ids seperated by a , eg. "1,5,7"
$canprunelog = "1";

// 0 shows no debug info
// 1 allows creation times to be viewed by adding showqueries=1 onto the query string,
// also displays the time in the admin panel
// 2 allows sql queries to be viewed also by adding showqueries=1 onto the query string
$debug = 1;

// If you have a problem having the directory /admin/ on your server then change this variable below
// Please ensure you do not have a / as the first or that last character
// You must also edit the file admin/toggle.js to replace admin/ with whatever you want
// Also, you MUST edit global.php and admin.php and edit the line saying require("admin/config.php");
// to point it to this file
$admindirectory = "admin";

?&gt;
      </textarea>
    </td>
  </tr>
<?php
  }

  if (!$fileexists & ($canwrite != 0)) {
  ?>
  <tr>
    <td>Cannot find config.php file, going to create one now</td>
  </tr>
  <tr>
    <td>Please confirm the details below</td>
  </tr>
  <tr>
    <td>
      <form action="install.php" method="post" name="form">
      <input type="hidden" name="step" value="writeconfig">
      <table width="50%" cellpadding="5" cellspacing="0">
        <tr>
          <td>Database Server Type:</td>
          <td><input type="text" name="dbservertype" value="mysql" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Hostname Or IP:</td>
          <td><input type="text" name="dbservername" value="localhost" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Username:</td>
          <td><input type="text" name="dbusername" value="root" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Password:</td>
          <td><input type="text" name="dbpassword" value="" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Name:</td>
          <td><input type="text" name="dbname" value="VirtuaNews" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Technical Email:<br>Seperate multiple emails with a space</td>
          <td><input type="text" name="technicalemail" value="webmaster@<?php echo $SERVER_NAME?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="Submit" class="form"> <input type="reset" name="reset" value="Reset" class="form"></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
  }

  if ($fileexists & !$canwrite) {
    include("admin/config.php");
?>
  <tr>
    <td>Please confirm the details below, only continue if they are correct</td>
  </tr>
  <tr>
    <td>
      <table width="50%" cellpadding="5" cellspacing="0">
        <tr>
          <td>Database Server Type:</td>
          <td><?php echo $dbservertype?></td>
        </tr>
        <tr>
          <td>Database Hostname Or IP:</td>
          <td><?php echo $dbservername?></td>
        </tr>
        <tr>
          <td>Database Username:</td>
          <td><?php echo $dbusername?></td>
        </tr>
        <tr>
          <td>Database Password:</td>
          <td><?php echo $dbpassword?></td>
        </tr>
        <tr>
          <td>Database Name:</td>
          <td><?php echo $dbname?></td>
        </tr>
        <tr>
          <td>Technical Email:</td>
          <td><?php echo $technicalemail?></td>
        </tr>
        <tr>
          <td colspan="2">Click <a href="install.php?step=3">here</a> to continue</td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
  }

  if ($fileexists & ($canwrite != 0)) {
    include("admin/config.php");
?>
  <tr>
    <td>Please confirm the details below</td>
  </tr>
  <tr>
    <td>
      <form action="install.php" method="post" name="form">
      <input type="hidden" name="step" value="writeconfig">
      <table width="50%" cellpadding="5" cellspacing="0">
        <tr>
          <td>Database Server Type:</td>
          <td><input type="text" name="dbservertype" value="<?php echo $dbservertype?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Hostname Or IP:</td>
          <td><input type="text" name="dbservername" value="<?php echo $dbservername?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Username:</td>
          <td><input type="text" name="dbusername" value="<?php echo $dbusername?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Password:</td>
          <td><input type="text" name="dbpassword" value="<?php echo $dbpassword?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Database Name:</td>
          <td><input type="text" name="dbname" value="<?php echo $dbname?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Technical Email:<br>Seperate multiple emails with a space</td>
          <td><input type="text" name="technicalemail" value="<?php echo $technicalemail?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="Submit" class="form"> <input type="reset" name="reset" value="Reset" class="form"></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
  }

}

if ($step == "writeconfig") {

  $dbservertype = strtolower($dbservertype);

  $configfile = "<"."?php
// type of database running
// (only mysql is supported at the moment)
\$dbservertype = \"$dbservertype\";

// hostname or ip of server
\$dbservername = \"$dbservername\";

// username and password to log onto db server
\$dbusername = \"$dbusername\";
\$dbpassword = \"$dbpassword\";

// name of database
\$dbname = \"$dbname\";

// technical email address, to specify multiple emails seperate each one with a space
\$technicalemail = \"$technicalemail\";

// set the staff ids for the users which can prune the admin log
// enter a single id, or a string of ids seperated by a , eg. \"1,5,7\"
\$canprunelog = \"1\";

// 0 shows no debug info
// 1 allows creation times to be viewed by adding showqueries=1 onto the query string,
// also displays the time in the admin panel
// 2 allows sql queries to be viewed also by adding showqueries=1 onto the query string
\$debug = 1;

// If you have a problem having the directory /admin/ on your server then change this variable below
// Please ensure you do not have a / as the first or that last character
// You must also edit the file admin/toggle.js to replace admin/ with whatever you want
// Also, you MUST edit global.php and admin.php and edit the line saying require(\"admin/config.php\");
// to point it to this file
\$admindirectory = \"admin\";

?>";

  $file = fopen("admin/config.php","w");
  fwrite($file,$configfile);
  $file = fclose($file);

  $step = 3;

}

if ($step == 3) {

  include("admin/config.php");
  include("includes/db_$dbservertype.php");

  $db = vn_connect(0);

  if (geterrno() == 1049) {

    echo "  <tr>\n    <td>Database Not Real, trying to create one now..</td>\n  </tr>\n";
    query("CREATE DATABASE $dbname",0);
    if (geterrno() != 0) {
      echo "  <tr>\n    <td>Unable to create database:<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
      echo "  <tr>\n    <td>Please create it manually or contact your system administrator for help</td>\n  </tr>\n";
    } else {
      echo "  <tr>\n    <td>Database now created</td>\n  </tr>\n";
      echo "  <tr>\n    <td>Click <a href=\"install.php?step=4\">here</a> to continue</td>\n  </tr>\n";
    }

  } elseif (geterrno() == 0) {

      echo "  <tr>\n    <td>Connect succeeded, database already exists, checking for database contents....</td>\n  </tr>\n";

      $gettables = query("SHOW TABLES");

      if (countrows($gettables)) {
        echo "  <tr>\n    <td>The database exists, however it is NOT empty</td>\n  </tr>\n";
        echo "  <tr>\n    <td>If you would like to empty the database please click <a href=\"install.php?step=confirmempty\" onclick=\"return confirmempty()\">here</a>.  <font class=\"red\">THIS WILL UNCONDITIONALLY ERASE ALL THE DATE FROM YOUR DATABASE.   DO NOT DO THIS IF YOU PLAN TO USE A FORUM FOR YOUR USER TABLES.</font></td>\n  </tr>\n";
        echo "  <tr>\n    <td>Click <a href=\"install.php?step=4\">here</a> to continue to the next step if you wish to leave your database intact</td>\n  </tr>\n";
      } else {
        echo "  <tr>\n    <td>The database exists and is empty</td>\n  </tr>\n";
        echo "  <tr>\n    <td>Click <a href=\"install.php?step=4\">here</a> to continue</td>\n  </tr>\n";
      }

  } else {
    echo "  <tr>\n    <td>Unable to conntect to server:<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
    echo "  <tr>\n    <td>Please go back and check the details in config.php and try again.</td>\n  </tr>\n";
  }

}

if ($step == "confirmempty") {
  echo "  <tr>\n    <td><font class=\"red\">YOU ARE ABOUT TO DELETE ALL THE DATA FROM YOUR DATABASE.  VIRTUANEWS CANNOT BE HELD RESPONSABLE FOR ANY LOSS OF DATA CAUSED BY THIS ACTION.  DO NOT DO THIS IF YOU PLAN TO USE YOUR FORUM FOR YOUR USER TABLES.</font></td>\n  </tr>\n";
  echo "  <tr>\n    <td>If you agree to these terms then please click <a href=\"install.php?step=empty&agree=1\" onclick=\"return confirmempty()\">here</a> otherwise click <a href=\"install.php?step=4\">here</a> to continue to the next step without erasing your data.</td>\n  </tr>\n";
}

if (($step == "empty") & ($agree == 1)) {
  include("admin/config.php");
  include("includes/db_$dbservertype.php");
  $db = vn_connect();
  $gettables = query("SHOW TABLES");

  while ($table_arr = fetch_array($gettables)) {
    $query[$table_arr[0]] = "DROP TABLE $table_arr[0]";
  }

  echo "  <tr>\n    <td>VirtuaNews is wiping your database:</tr>\n";

  foreach ($query AS $table_name => $query_text) {
    query($query_text,0);
    if (geterrno() == 0) {
      echo "  <tr>\n    <td>Deleting Table: $table_name</td>\n  </tr>\n";
    } else {
      echo "  <tr>\n    <td>Error Deleting Table: $table_name<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
      $error = 1;
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error deleting one or more of the tables.  Please examine this error and only continue if you are sure it will not effect the install process.  Click <a href=\"install.php?step=4\"> HERE</a> to continue\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All tables are deleted, please click <a href=\"install.php?step=4\">here</a> to continue</tr>\n";
  }
}

if ($step > 3) {
  include("admin/config.php");
  include("includes/db_$dbservertype.php");
  $db = vn_connect();
}

if ($step == 4) {

  $query[news_activation] = "CREATE TABLE news_activation (
    id INT(10) NOT NULL auto_increment,
    userid INT(10) DEFAULT '0' NOT NULL,
    date INT(10) DEFAULT '' NOT NULL,
    activateid INT(10) UNSIGNED DEFAULT '0' NOT NULL,
    type TINYINT(1) DEFAULT '1' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_adminlog] = "CREATE TABLE news_adminlog (
    id INT(10) UNSIGNED NOT NULL auto_increment,
    staffid SMALLINT(5) DEFAULT '0' NOT NULL,
    time INT(10) DEFAULT '' NOT NULL,
    script VARCHAR(20) DEFAULT '' NOT NULL,
    action VARCHAR(20) DEFAULT '' NOT NULL,
    extrainfo VARCHAR(200) DEFAULT '' NOT NULL,
    ipaddress VARCHAR(15) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_announcement] = "CREATE TABLE news_announcement (
    catid SMALLINT(5) DEFAULT '0' NOT NULL,
    content MEDIUMTEXT DEFAULT '' NOT NULL,
    image VARCHAR(255) DEFAULT '' NOT NULL,
    link VARCHAR(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (catid),
    UNIQUE KEY id (catid))";

  $query[news_article] = "CREATE TABLE news_article (
    id SMALLINT(5) NOT NULL auto_increment,
    date INT(10) DEFAULT '' NOT NULL,
    title VARCHAR(255) DEFAULT '' NOT NULL,
    staffid SMALLINT(5) DEFAULT '0' NOT NULL,
    authorname VARCHAR(255) DEFAULT '' NOT NULL,
    numpages SMALLINT(2) DEFAULT '' NOT NULL,
    views INT(10) DEFAULT '' NOT NULL,
    rating FLOAT DEFAULT '' NOT NULL,
    page1 MEDIUMTEXT DEFAULT '' NOT NULL,
    page2 MEDIUMTEXT DEFAULT '' NOT NULL,
    page3 MEDIUMTEXT DEFAULT '' NOT NULL,
    page4 MEDIUMTEXT DEFAULT '' NOT NULL,
    page5 MEDIUMTEXT DEFAULT '' NOT NULL,
    page6 MEDIUMTEXT DEFAULT '' NOT NULL,
    page7 MEDIUMTEXT DEFAULT '' NOT NULL,
    page8 MEDIUMTEXT DEFAULT '' NOT NULL,
    page9 MEDIUMTEXT DEFAULT '' NOT NULL,
    page10 MEDIUMTEXT DEFAULT '' NOT NULL,
    catid SMALLINT(5) DEFAULT '0' NOT NULL,
    parsenewline TINYINT(1) DEFAULT '1' NOT NULL,
    display SMALLINT(1) DEFAULT '1' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_articlecat] = "CREATE TABLE news_articlecat (
    id SMALLINT(5) NOT NULL auto_increment,
    title VARCHAR(255) DEFAULT '' NOT NULL,
    description MEDIUMTEXT DEFAULT '' NOT NULL,
    parentid SMALLINT(5) DEFAULT '0' NOT NULL,
    children SMALLINT(5) DEFAULT '0' NOT NULL,
    numarticles SMALLINT(5) DEFAULT '0' NOT NULL,
    displayorder SMALLINT(4) DEFAULT '0' NOT NULL,
    display SMALLINT(1) DEFAULT '1' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_articlevote] = "CREATE TABLE news_articlevote (
    id INT(10) UNSIGNED NOT NULL auto_increment,
    articleid INT(10) DEFAULT '0' NOT NULL,
    userid INT(10) DEFAULT '0' NOT NULL,
    votedate INT(10) DEFAULT '' NOT NULL,
    voteoption INT(10) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_category] = "CREATE TABLE news_category (
    id SMALLINT(5) NOT NULL auto_increment,
    parentid SMALLINT(5) DEFAULT '' NOT NULL,
    children SMALLINT(5) DEFAULT '' NOT NULL,
    name VARCHAR(255) NOT NULL,
    description MEDIUMTEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    posts SMALLINT(5) DEFAULT '' NOT NULL,
    allowcomments TINYINT(1) DEFAULT '' NOT NULL,
    allowview TINYINT(1) DEFAULT '' NOT NULL,
    showsitestats TINYINT(1) DEFAULT '' NOT NULL,
    showforumstats TINYINT(1) DEFAULT '' NOT NULL,
    showforumoptions TINYINT(1) DEFAULT '' NOT NULL,
    showpoll TINYINT(1) DEFAULT '' NOT NULL,
    showannouncement TINYINT(1) DEFAULT '' NOT NULL,
    showsubcats TINYINT(1) DEFAULT '' NOT NULL,
    defaulttheme SMALLINT(5) DEFAULT '' NOT NULL,
    forcetheme TINYINT(1) DEFAULT '' NOT NULL,
    recentpost VARCHAR(255) NOT NULL,
    display TINYINT(1) DEFAULT '' NOT NULL,
    displayorder SMALLINT(5) DEFAULT '' NOT NULL,
    displaymain TINYINT(1) DEFAULT '' NOT NULL,
    pollid SMALLINT(5) NOT NULL,
    PRIMARY KEY (id),
    KEY displayorder (displayorder))";

  $query[news_comment] = "CREATE TABLE news_comment (
    id INT(10) UNSIGNED NOT NULL auto_increment,
    newsid SMALLINT(5) DEFAULT '' NOT NULL,
    username VARCHAR(255) NOT NULL,
    userid SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    useremail VARCHAR(255),
    ip VARCHAR(15),
    comment MEDIUMTEXT NOT NULL,
    showsig TINYINT(1) DEFAULT '' NOT NULL,
    time INT(10) DEFAULT '' NOT NULL,
    parentid INT(10) DEFAULT '' NOT NULL,
    editlock TINYINT(1) DEFAULT '' NOT NULL,
    edituserid SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    editdate INT(10) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY userid (userid),
    KEY newsid (newsid,time))";

  $query[news_hit] = "CREATE TABLE news_hit (
    time INT(10) DEFAULT '' NOT NULL,
    ip VARCHAR(20) NOT NULL,
    browser VARCHAR(15) NOT NULL,
    os VARCHAR(15) NOT NULL,
    location VARCHAR(255) NOT NULL,
    PRIMARY KEY (time,ip)
    ) TYPE=HEAP";

  $query[news_module] = "CREATE TABLE news_module (
    id MEDIUMINT(5) NOT NULL auto_increment,
    name VARCHAR(255) NOT NULL,
    text VARCHAR(255) NOT NULL,
    description MEDIUMTEXT,
    version VARCHAR(255),
    templates MEDIUMTEXT,
    table_list MEDIUMTEXT,
    display TINYINT(1) DEFAULT '' NOT NULL,
    enable TINYINT(1) DEFAULT '' NOT NULL,
    options MEDIUMTEXT,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_news] = "CREATE TABLE news_news (
    id INT(10) UNSIGNED NOT NULL auto_increment,
    catid SMALLINT(5) DEFAULT '' NOT NULL,
    title VARCHAR(255) NOT NULL,
    mainnews MEDIUMTEXT NOT NULL,
    extendednews MEDIUMTEXT NOT NULL,
    logoimage VARCHAR(50),
    logoimageborder SMALLINT(1) DEFAULT '' NOT NULL,
    commentcount SMALLINT(5) DEFAULT '' NOT NULL,
    lastcommentuser VARCHAR(255),
    time INT(11) DEFAULT '' NOT NULL,
    staffid SMALLINT(5) DEFAULT '' NOT NULL,
    stickypost TINYINT(1) DEFAULT '' NOT NULL,
    parsenewline TINYINT(1) DEFAULT '' NOT NULL,
    program SMALLINT(1) DEFAULT '' NOT NULL,
    display SMALLINT(1) DEFAULT '' NOT NULL,
    allowcomments SMALLINT(1) DEFAULT '' NOT NULL,
    editstaffid SMALLINT(5) DEFAULT '' NOT NULL,
    editdate INT(10) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY catid (catid),
    KEY program (program))";

  $query[news_option] = "CREATE TABLE news_option (
    id SMALLINT(5) NOT NULL auto_increment,
    optiongroup VARCHAR(100),
    varname VARCHAR(100) NOT NULL,
    value TEXT NOT NULL,
    title VARCHAR(100),
    description MEDIUMTEXT,
    code MEDIUMTEXT,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_page] = "CREATE TABLE news_page (
    id SMALLINT(5) NOT NULL auto_increment,
    pagesetid SMALLINT(4) DEFAULT '' NOT NULL,
    title VARCHAR(255) NOT NULL,
    description MEDIUMTEXT,
    onserver TINYINT(1) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_pageset] = "CREATE TABLE news_pageset (
    id SMALLINT(5) NOT NULL auto_increment,
    title VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_pm] = "CREATE TABLE news_pm (
    id SMALLINT(5) UNSIGNED NOT NULL auto_increment,
    fromuserid SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    touserid SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    folder TINYINT(1) DEFAULT '' NOT NULL,
    senddate INT(10) DEFAULT '' NOT NULL,
    readdate INT(10) DEFAULT '' NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message MEDIUMTEXT NOT NULL,
    showsig TINYINT(1) DEFAULT '' NOT NULL,
    copyid SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_poll] = "CREATE TABLE news_poll (
    id SMALLINT(5) NOT NULL auto_increment,
    question VARCHAR(255) NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255),
    option4 VARCHAR(255),
    option5 VARCHAR(255),
    option6 VARCHAR(255),
    option7 VARCHAR(255),
    option8 VARCHAR(255),
    option9 VARCHAR(255),
    option10 VARCHAR(255),
    totalvotes SMALLINT(5) DEFAULT '' NOT NULL,
    votes VARCHAR(255) NOT NULL,
    display VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_pollvote] = "CREATE TABLE news_pollvote (
    id INT(10) UNSIGNED NOT NULL auto_increment,
    pollid INT(10) DEFAULT '' NOT NULL,
    userid INT(10) DEFAULT '' NOT NULL,
    votedate INT(10) DEFAULT '' NOT NULL,
    voteoption INT(10) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY pollid (pollid,userid))";

  $query[news_profilefield] = "CREATE TABLE news_profilefield (
    id SMALLINT(5) NOT NULL auto_increment,
    title VARCHAR(255) NOT NULL,
    description MEDIUMTEXT NOT NULL,
    required TINYINT(1) DEFAULT '' NOT NULL,
    hidden TINYINT(1) DEFAULT '' NOT NULL,
    editable TINYINT(1) DEFAULT '' NOT NULL,
    displayorder SMALLINT(5) DEFAULT '' NOT NULL,
    maxlength SMALLINT(5) DEFAULT '' NOT NULL,
    size SMALLINT(5) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_referer] = "CREATE TABLE news_referer (
    name varchar(255) NOT NULL default '',
    value int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (name))";

  $query[news_smilie] = "CREATE TABLE news_smilie (
    id SMALLINT(5) UNSIGNED NOT NULL auto_increment,
    title CHAR(100) NOT NULL,
    smilietext CHAR(100) NOT NULL,
    smiliepath CHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_staff] = "CREATE TABLE news_staff (
    id SMALLINT(5) NOT NULL auto_increment,
    userid SMALLINT(5) DEFAULT '' NOT NULL,
    job VARCHAR(100) NOT NULL,
    newsposts SMALLINT(5) DEFAULT '' NOT NULL,
    caneditallnews SMALLINT(1) DEFAULT '' NOT NULL,
    caneditallcomments SMALLINT(1) DEFAULT '' NOT NULL,
    canmakesticky TINYINT(1) DEFAULT '' NOT NULL,
    caneditusers TINYINT(1) DEFAULT '' NOT NULL,
    caneditprofilefields TINYINT(1) DEFAULT '' NOT NULL,
    caneditstaff SMALLINT(1) DEFAULT '' NOT NULL,
    caneditcategories SMALLINT(1) DEFAULT '' NOT NULL,
    caneditarticles TINYINT(1) DEFAULT '' NOT NULL,
    caneditsmilies TINYINT(1) DEFAULT '' NOT NULL,
    caneditpolls SMALLINT(1) DEFAULT '' NOT NULL,
    candeletelogos TINYINT(1) DEFAULT '' NOT NULL,
    caneditmodules TINYINT(1) DEFAULT '' NOT NULL,
    caneditthemes SMALLINT(1) DEFAULT '' NOT NULL,
    caneditoptions TINYINT(1) DEFAULT '' NOT NULL,
    canmaintaindb TINYINT(1) DEFAULT '' NOT NULL,
    canviewlog SMALLINT(1) DEFAULT '' NOT NULL,
    canpost_1 SMALLINT(1) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id),
    KEY userid (userid))";

  $query[news_stats] = "CREATE TABLE news_stats (
    lastupdate int(10) unsigned NOT NULL default '0',
    uniquestotal int(10) unsigned NOT NULL default '0',
    uniquestoday int(10) unsigned NOT NULL default '0',
    maxusersonline int(10) unsigned NOT NULL default '0')";

  $query[news_style] = "CREATE TABLE news_style (
    id SMALLINT(5) NOT NULL auto_increment,
    stylesetid SMALLINT(5) DEFAULT '0' NOT NULL,
    varname TEXT DEFAULT '' NOT NULL,
    value TEXT DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY stylesetid (stylesetid))";

  $query[news_styleset] = "CREATE TABLE news_styleset (
    id SMALLINT(5) NOT NULL auto_increment,
    title VARCHAR(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_subscribe] = "CREATE TABLE news_subscribe (
    id MEDIUMINT(10) NOT NULL auto_increment,
    newsid MEDIUMINT(10) DEFAULT '' NOT NULL,
    userid MEDIUMINT(10) DEFAULT '' NOT NULL,
    lastview INT(10) DEFAULT '' NOT NULL,
    emailupdate TINYINT(1) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY newsid (newsid,userid))";

  $query[news_theme] = "CREATE TABLE news_theme (
    id SMALLINT(5) NOT NULL auto_increment,
    title VARCHAR(255) DEFAULT '' NOT NULL,
    pagesetid SMALLINT(5) DEFAULT '0' NOT NULL,
    stylesetid SMALLINT(5) DEFAULT '0' NOT NULL,
    allowselect TINYINT(1) DEFAULT '1' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY id (id))";

  $query[news_user] = "CREATE TABLE news_user (
    userid INT(10) UNSIGNED NOT NULL auto_increment,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    showemail TINYINT(1) DEFAULT '' NOT NULL,
    homepage VARCHAR(100) NOT NULL,
    icq VARCHAR(20) NOT NULL,
    aim VARCHAR(20) NOT NULL,
    yahoo VARCHAR(20) NOT NULL,
    signature MEDIUMTEXT NOT NULL,
    activated TINYINT(1) DEFAULT '' NOT NULL,
    moderated TINYINT(1) DEFAULT '' NOT NULL,
    joindate INT(10) DEFAULT '' NOT NULL,
    posts SMALLINT(5) UNSIGNED DEFAULT '' NOT NULL,
    emailnotification TINYINT(1) DEFAULT '' NOT NULL,
    commentdefault TINYINT(1) DEFAULT '' NOT NULL,
    viewsigs TINYINT(1) DEFAULT '' NOT NULL,
    allowpm TINYINT(1) DEFAULT '' NOT NULL,
    emailpm TINYINT(1) DEFAULT '' NOT NULL,
    isbanned TINYINT(1) DEFAULT '' NOT NULL,
    PRIMARY KEY (userid),
    UNIQUE KEY id (userid))";

  $query[news_useragent] = "CREATE TABLE news_useragent (
    name VARCHAR(255) DEFAULT '' NOT NULL,
    type VARCHAR(15) DEFAULT '' NOT NULL,
    value INT(10) DEFAULT '' NOT NULL,
    PRIMARY KEY (name,type))";

  $query[news_userfield] = "CREATE TABLE news_userfield (
    userid INT(10) DEFAULT '0' NOT NULL,
    field1 VARCHAR(250) DEFAULT ''NOT NULL,
    field2 VARCHAR(250) DEFAULT ''NOT NULL,
    field3 VARCHAR(255) DEFAULT ''NOT NULL,
    field4 VARCHAR(255) DEFAULT ''NOT NULL,
    PRIMARY KEY (userid),
    UNIQUE KEY id (userid))";

  echo "  <tr>\n    <td>Installing the VirtuaNews tables:</tr>\n";

  while (list($table_name,$query_text) = each($query)) {
    query($query_text,0);
    if (geterrno() == 0) {
      echo "  <tr>\n    <td>Creating table: $table_name</td>\n  </tr>\n";
    } else {
      echo "  <tr>\n    <td>Error Creating table: $table_name<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
      $error = 1;
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error creating one or more of the tables.  Please examine this error and only continue if you are sure it will not effect the install process.  Click <a href=\"install.php?step=5\"> HERE</a> to continue </td>\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All tables created correctly, please click <a href=\"install.php?step=5\">here</a> to continue</tr>\n";
  }

}

if ($step == 5) {

  $query[news_announcement][] = "INSERT INTO news_announcement VALUES ('1','','','')";

  $query[news_category][] = "INSERT INTO news_category VALUES ('1','0','0','Main','View the latest news from our site here.','','1','3','3','1','1','1','1','1','0','1','0','1','1','1','1','1');";

  $query[news_news][] = "INSERT INTO news_news VALUES (NULL,'1','New Site','Welcome to our new website, powered by VirtuaNews version $version we hope to be able to bring you the latest and best information available on the web.\n\n\n\nYour admin team','','virtuanews_logo.gif','0','0','','".time()."','1','0','1','0','1','1','0','0')";

  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','aboutus_main','This template displays information about news posters and the admin of the site, along with any other info you wish to display on the page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','aboutus_record','This template displays the indervidual records for the user on the aboutus page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','archive_header','This template creates a table that will contain the posts over that day on the archive page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','archive_next_days_link','This template will provide a link to the next set of days on the archive page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','archive_page','This template is the main archive page which will output the list of posts over the number of days set int he options.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','archive_post','This template is used to display an indervidual news post on the archive page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','archive_previous_days_link','This template will provide a link to the previous set of days on the archive page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_cats_page','This template will display the articles within the category specified by the user.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_cats_record','This template will display indervidual links to the articles within the category specified.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_cats_sub_cat','This template will display links to the sub categories to the category specified.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_display_page','This template will display each article to the user.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_display_rating','This template will display an option for the user to rate the article, it will not be shown if a user has already selected a rating for the article.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_index_cat','This template displays the top level article categories.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_index_page','This template will display links to the first 2 levels of categories of articles on the main article page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','articles_index_sub_cat','This template displays the sub categories on the article index page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_autoparse_check','This template will display a check box allowing users to select if they wish to automatically parse urls or not.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_closed','This template is displayed in place of the table to add comments when comments are not allowed to be posted.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_disabled','This template is displayed instead of the table to add a comment when a user does not have permission to post comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_new','This template displays the table to add a comment to a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_notify_check','This template will display a check box allowing registered users to select if they wish to subscribe to a comment or not.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_qhtml_disabled','This template is shown inplace of the qhtml buttons when qhtml for the user is disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_qhtml_links','This template provides the buttons to insert qhtml into the comment, when it is enabled for the user on the comments page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_reply','This template displays the table to reply to a comment from a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_add_signature_check','This template will display a check box allowing registered users to select if they wish to use their signature or not.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_admin_del_link','This template is used to display a link to posters (thoses who have permissions) to delete users comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_admin_edit_link','This template is used to display a link to posters (thoses who have permissions) to edit users comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_close_link','This template is used to display a link to close all comment replies for a specific comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_data','This template is used to display the comments that dont have any replies to them for the news posts.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_data_child','This template is used to display the comments which are replies to other comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_data_parent','This template is used to display the comments that have replies to them for the news posts.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_editedby','This template is used to display details about who has edited a comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_edit_link','This template is used to display a link to reply to the comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_open_link','This template is used to display a link to open all comment replies for a specific comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_outer_table','This template is used as a parent to the comments for a news post, this will contain all comment info which can then be outputted to the main comment page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_reply_last','This template will display a link on the last child comment displaying a link to reply to the parent.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_reply_link','This template is used to display a link to the user allowing them to reply to a comment.  It is not displayed with the child comments, apart from the last one for each parent.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_report_link','This page bit is displayed along with comments providing a link to report them when the user is allowed to report comments.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_signature','This template is used to display a users signature along with their comment post if they have one.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_comment_user_post_count','This template is used to display a users post count on the forum and comments along with their comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_emailnotify_msg','This template is the main body of the emails that are sent users to tell them when there is a new comment to a news post they have subscribed to.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_emailnotify_subject','This template is the subject of the emails that are sent users to tell them when there is a new comment to a news post they have subscribed to.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_logged_in','This template is displayed in the comment add form to show the username that is logged in.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_logged_out','This template is displayed in the comment add form displaying text boxes to allow an unregistered user to enter a name to comment under.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_admin_close','This template is displayed when a user views a news post that can open and close posts, it will provide it will provide a link to close the news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_admin_open','This template is displayed when a user views a news post that can open and close posts, it will provide it will provide a link to open the news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_data','This template displays the news post and extended news on the comments page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_editedby','This template is used to display details about who has edited a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_logo','This template is for the logo that is optionally displayed with news posts on the comments page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_logo_border','This template is for the logo border that is optionally displayed around the logo with news posts on the comments page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_news_oc_link','This template is displayed when the comment reply system is enabled, allowing the user to open or close all comments to a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_page','This template is used as the comments page displaying the comment and news data within.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_report','This page will display a form allowing users to report a comment to the staff.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_report_email','This page bit contains the body of the email that will be sent to staff when a comment is reported.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_smilies_disabled','This template is displayed in place of the clickable smilies table when smilies are not allowed to be used.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_smilies_row','This page bit will display a row containing the clickable smilies.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_smilies_smiliebit','This template displays the actual clickable smilies in the table.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comments_smilies_table','This template is used to provide a table of clickable smilies to insert into the comment text area on the comment page, it is displayed only when the smilies are enabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','comment_news_subscribe_link','This page bit is displayed allowing a registered user to subscribe to a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','edit_page','This template is displayed when a user is editing a comment, it contains the form they will use to do so.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_account_notmoderated','This template is displayed when a user tries to access the user panel, but their account has not yet been moderated.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_activation_expired','This template is displayed when a user tries to activate their account using an activation id which has expired.  It will display a form allowing them to email a new activation id to their email address.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_already_activated','This template is displayed when a user requests a new activation id and has already activated their account.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_already_logged_in','This template is displayed when a user is trying to log in and is already logged in.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_already_registered','This template will be displayed when a user tries to register that is already registered.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_already_voted','This template is displayed when a user tries to vote more than once for a poll or an article.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_blank_field','This template is displayed when a required field is left blank.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_cannot_edit','This template is displayed whena user tries to edit a comment and comment editing is not allowed.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_category_hidden','This page bit is displayed when a user tries to view a category which is hidden from view to them.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_comments_closed','This template is displayed when a user tries to post a comment to a news post that they are not permitted to do.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_comment_edit_locked','this template is displayed when a user tries to edit a comment that has already been edited by a poster and locked for editing.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_comment_long','This template is displayed when a user enters a comment which is over the character limit.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_comment_user_taken','This template is displayed when a user tries to post a comment with a username which is already in use by a member.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_comment_wrong_user','This template is displayed when a user tries to post a comment with a different user name to what they are logged in as.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_edit_wrong_user','This template is displayed when a user tries to edit a comment that is not their own.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_failed','This template is displayed if an email fails to send at any time.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_hidden','This template is displayed when a user tries to email another user that has specified that they have their email address hidden.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_long','This template is displayed when a user enters an email address which is over the character limit.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_mismatch','This template is displayed when a user is registering and enters a different email address in the email confirm box to that which they entered first.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_notactivated','This template is displayed when a user tries to access the user panel, and has not yet activated their email address.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_email_taken','This template is displayed when a user tries to register with an email address that another member already has.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_field_long','This template is displayed when a user is registering or editing their profile and enters data greater than the max length of a custom field.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_invalid_cat','This template is displayed when a user specifies an invalid category to post a comment in.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_invalid_email','This template is displayed when a user enters an email address which is not valid.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_invalid_id','This template is displayed when the user specifies an invlaid id.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_invalid_link','this template is displayed when the user specifies an invalid link.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_invalid_rating','This template is displayed when a user tries to vote for something and specifies an invalid option.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_ip_banned','This template is displayed when a user who\'s IP has been banned tries to view the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_memberlist_disabled','This template is displayed when a user tries to access the member list and it is disabled in the site options.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_module_disabled','This template is displayed when a user tries to access a module which has been disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_no_anon_voting','This template is displayed when a user tries to vote and is not logged in when anonymous voting is not allowed.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_no_next_post','This page bit is displayed when a user tries to view the next post to the one they are viewing but there is no newer post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_no_perms','This template is displayed when a user tries to do an action they are not permitted to do.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_no_previous_post','This page bit is displayed when a user tries to view the previous post to the one they are viewing but there is no older post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_page','This template is used when there is an error on the site, it will output the error to the user.  This template is the basic outline of the error page, containing the error message within.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_page_hidden','This page bit will be displayed when a user tries to view a page they do not have the permission to view.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_password_mismatch','This template is displayed when a user is registering and enters a different password in the password confirm box to that which they entered first.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_password_request_expired','This template is displayed when a user tries to reset their password using an activation id that has expired.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_pm_bounce','This template is displayed when a user tries to send a message to another user who cannot recieve them.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_pm_disabled','This is displayed when a user tries to access their private messages but has them turned off in their profile.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_pm_full','This template is displayed when a user tries to send a new private message and their pm in/outbox is full.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_pm_invalid_user','This template is displayed when a user tries to view a private message that is not theirs.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_pm_no_perms','This template is displayed when a user tries to access their private messages but do not have the permissions to do so.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_profile_disabled','This template is shown when a user tries to view a users profile but viewing of profiles has been disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_search_disabled','This template is displayed when a user tries to search the site but searching has been disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_server_busy','This template is displayed when there are too many users on the site causing it too slow.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_signup_disabled','This template is displayed when a user tries to register and registration is disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_site_closed','This template is displayed when the site is closed.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_stats_disabled','This template is displayed when a user tries to view the stats.php page and viewing of stats has been disabled.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_too_many_images','This template is displayed when a user includes too many images in their post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_username_taken','This template is displayed when a user tries to register with a username which is already taken.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_user_banned','This template is displayed when a banned user tries to access the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_user_long','This template is displayed when a user enters a username which is over the character limit.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_wrong_password','This template is displayed when a user is logging in and enters an invlaid password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','error_wrong_username','This template is displayed when a user is logging in and enters an invlaid username.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','help_faq','This will display a page showing answers to frequently asked questions to help the users.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','help_qhtml','This will display a page showing information about the quick HTML code help the users.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','help_smilie','This will display a page showing a list of all smilies to help the users.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','help_smilie_bit','This page bit will the indervidual smilies on the smilie list.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_empty','This page bit is displayed when there are no sub categories to list.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_image','This template is used to display the image along with each category on the category list page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_name_link','This page bit displays the category names on the listing page, it is used to give a link to the main page showing the news posts.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_name_nolink','This page bit displays the category names on the listing page, it is used when showing details about a category which is set not to display on the main page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_page','This template is used to display a page listing all of the news categories and their info.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_record','This template is used to display each category on the category list.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_catlist_sub_record','This page bit will display details about the sub categories on the category listing page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_custom_page','This page is displayed as a custom start page when it is set to display first (in site options).','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_home_page','This template is used on the front page of the site, displaying the news posts.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post','This template is used to display the news posts on the front page of the site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_comments_image','This template is the name of the image with the link to the comments.  This will display when the comments are allowed.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_comments_image_lock','This template is the name of the image with the link to the comments.  This will display when the comments are closed.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_logo','This template is used when a news post is displayed with a logo.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_logo_border','This template is used when a news post is displayed with a logo which has a border round it selected.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_no_comments','This template is used to show that there are no comments to a news post, it will be replaced by the last comment username when there is.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_post_read_more','This template is used when extended news is entered for a news post, providing a link to read more.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','main_news_sticky','This page bit is used to display sticky news posts on the main news page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_email_content','This template is the content of them email which will be used to email users containing the message from the sender.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_email_disabled','This template is displayed when a user tries to use the email form but dont have permission to do so, instead it will just display the email address as a link.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_email_form','This template is displayed so that a user can email another user.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_email_subject','This template is the subject of the email which will be sent to users by people using the email form.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_page','This template will display a list of all the members on your site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_record','This template is used to display the indervidual details about each member on the member list page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_record_email','This template will display a link to email a user on the member list page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_record_homepage','This template will display a link to a users homepage on the member list page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_search','This template will display a search form allowing the user to search the members table.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_list_search_blank','This template is displayed if a user conducts a member search and there are no results.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_profile_custom','This page bit will display details of a members custom fields on their profile page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_profile_email','This template is used to display a link to email a user on the profile page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_profile_homepage','This template is used to display a link to a users homepage on the profile pahe.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_profile_page','This template is used to display the profile about a certain member.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','member_profile_pm','This template will display a link to send the user a private message for users who are able to do so.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','menu_1','This page is the menu for the category called <b>Main</b>.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_announcement','This template will output the announcement for the news category the user is browsing.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_announcement_image','This template will output an image without a link next to the announcement, if there is an image entered.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_announcement_image_link','This template will output an image with a link next to the announcement, if there is an image link entered.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_cat_nav_link','This template displays the links to the different news sections on each page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_cat_nav_off','This page bit displays the name of the category which is being viewed on the category nav.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_footer','This template is the footer, displayed at the bottom of every page.<br>\n<br>\nNOTE DO NOT REMOVE THE COPYRIGHT NOTICE','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_forum_options','This template outputs options for the forums to be displayed on every page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_header','This template is the header, displayed at the top of every page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_module_link','Template is used to provide links to the modules that are on the site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_nav_bar','This template will display the nav bar at the top of the page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_nav_joiner','This template will join the links in the nav bar at the top of the page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_nav_link_off','This template is displayed for a link on the nav bar at the top of the page, when only the title is displayed, no link.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_nav_link_on','This template is displayed for a link on the nav bar at the top of the page, where an actual link is required.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_curr_page','This template displays the current page number.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_first_link','This template displays a link to the very first page of the set,.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_last_link','This template displays a link to the very last page of the set,.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_main','This template displays the page navigation, shown where there are multiple pages for search results etc. This contains the seperate page links as variables.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_next_link','This template displays a link to the next page to the current one the user is on.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_page_nav_prev_link','This template displays a link to the previous page to the current one the user is on.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_recent_post_header','This template is the header displayed at the top of the recent posts.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_recent_post_link','This template displays the links to the comment pages for the recent post display.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_sitejump_cat','This page bit will display the indervidual categories in the site jump.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_sitejump_main','This page bit will display the outline of the site jump.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_sitejump_news','This page bit will display the news posts in the site jump.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_stats_forum','This template is displayed on every page (when forum stats are enabled in the category being browsed) containing stats about the forum.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_stats_site','This template is displayed on every page (when stats are enable in the category being browsed) containing the site counter and users online.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_theme_selector','This template provides the selector for the site styles, it is the parent drop down box which will be filled by the options.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_theme_selector_option','This template provides the indervidual options for the style selector allowing the user to choose which style they wish to use.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_username_email','This template will be used to display a persons name whent they post comments or news etc and provide a link to their email.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_username_noemail','This template will be used to display a persons name whent they post comments or news etc, it is used when no email address is given.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_username_profile','This template will be used to display a persons name whent they post comments or news etc and provide a link to their profile.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_welcometext_logged_in','This template is the welcome text displayed for users who are logged in.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','misc_welcometext_logged_out','This template is the welcome text displayed for users who are either not registered or not logged in to the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_options_option','This template is used to display the poll options on every page so a user can vote for the poll from any page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_options_table','This template is the table in which the poll will be displayed on all pages when the user has not already voted.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_results_option','This template is used to display the poll results for each option on every page.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_results_table','This template is the table in which the poll will be displayed on all pages when the user has already voted.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_view_results','This template displays all the polls that have been on the site from the past, this is the main page for this and contiains all the data about the polls.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_view_results_option','This template is used to display the poll results for each option on the page showing all the polls from the past on the site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','poll_view_results_table','This template is used to display the polls on the page showing all of the polls.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','print_news','This template is displayed so that a user can print out a news post, ie there are only a few images and simple text.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','print_news_comment','This template is used to display the comments on the print post page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','print_news_comment_child','This template is used to display the comments which are replies to other comments on the print post page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','print_news_comment_parent','This template is used to display the comments which have replies on the print post page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','print_news_post','This template is used to display the news post on the print post page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_acitvation_resent','This template is displayed when the user is being redirected after a new activation id has been sent to a new user.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_activation_complete','This template is displayed when the user is being redirected after email activation is complete.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_comment_edit','This template is displayed when the user is being redirected after editing a comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_comment_new','This template is displayed when the user is being redirected after posting a new comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_comment_report','This template is displayed when the user is being redirected after they have reported a comment.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_comment_sub','This template is displayed when the user is being redirected after they have subscribed to a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_comment_usub','This template is displayed when the user is being redirected after they have unsubscribed from a news post.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_login','This template is displayed when the user is being redirected after Logging in.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_logout','This template is displayed when the user is being redirected after logging out.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_member_email','This template is displayed when the user is being redirected after emailing a member.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_page','This template is displayed after a user has submitted something tot he site, it will redirect them to the correct page.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_pm_delete','This template is displayed when the user is being redirected after delete a private message.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_pm_sent','This template is displayed when the user is being redirected after they have sent a private message.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_profile_updated','This template is displayed when the user is being redirected after they have updated their profile.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_pwd_forgot','This template is displayed when the user is being redirected after an email has been sent to auser containing details how to reset their password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_pwd_reset','This template is displayed when the user is being redirected after they have reset their password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_pwd_updated','This template is displayed when the user is being redirected after they have updated their password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_registration_done','This template is displayed when the user is being redirected after registering with the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','redirect_voted','This template is displayed when the user is being redirected after voting for an article or poll.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_activation_msg','This template is emailed in the main body of the email, to a new user with details of how to activate their account.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_activation_subject','This template is the subject of the email which is sent to new users to give them details about activating their account.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_details_msg','This template is the body of the email which is sent to a user, after their account has been activated, confirming their login details.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_details_subject','This template is the subject of the email which is sent to a user, after their account has been activated, confirming their login details.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_moderated','This page is sent in the body of an email to users who have been moderated by staff.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_mod_msg','This page is sent in the body of an email to users after they have activated their email if their account still requires admin moderation.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_mod_subject','This page is sent as the subject of an email to users after they have activated their email if their account still requires admin moderation.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_newuser_msg','This page is sent in the body of an email to staff after a new user registeres requiring moderation.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_email_newuser_subject','This page is sent as the subject of an email to staff after a new user registeres requiring moderation.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_form','This template displays the form allowing users to register with your site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_form_custom_field','This template will display a text box for the custom profile fields you use, allowing the user to enter data into them.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','register_lost_activation','This page will display a form allowing users to request new activation details be sent to them.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_page','This template is the search form the user will use to search the site.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_page_cat_option','This template displays the options on the search page allowing the user to search specific news categories.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_results_article','This template will contain all the articles found by the search.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_results_article_record','This template displays the indervidual articles found by the search.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_results_news','This template will contain all the news posts found by the search.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_results_news_record','This template displays the indervidual news posts found by the search.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','search_results_page','This template is used as the main page for the search results, on it will be a list of the posts found by the users search.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','stats_browser_row','This template will display the info about the browsers for the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','stats_main_page','This template is displayed when a user views the site stats page. This template is the main page, in which the details will be displayed.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','stats_os_row','This template will display the info about the operating systems for the site.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_forget_request_emailmsg','This template will be emailed to users containing information on how to reset their password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_forget_request_emailsub','This template is the subject of the email which will be sent containing details on how to reset a users password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_forget_request_form','This template is displayed allowing a user to request that their password be sent to them when they forget it.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_forget_reset_emailmsg','This template will be emailed to a user contianing their new password after it has been reset.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_forget_reset_emailsub','This template is the subject of the email that will be sent to users containing their new password.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_first_unread_link','This template is displayed giving a link to the first unread post for the user to a news post which they have subscribed to.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_page','This template is displayed on the front page of the user control panel.  It contains links to each part of the control panel and details about posts users have subscribed to.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_pm','This template is used to display a list of new private messages for users on the front page of the user panel.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_pm_empty','This template is displayed on the front page of the user panel when a user has no new private messages.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_post','This template is used to display the indervidual post details about posts which users have subcribed to.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_index_post_empty','This template is displayed on the front page of the user panel for users that are not subscribed to any posts.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_login_form','This template is displayed when a user logs in, it contains the form they will use to do so.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_email_msg','This template is sent as an email in the body to a user when they recieve a new private message.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_email_subject','This template is sent as an email in the subject to a user when they recieve a new private message.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_empty','This template is displayed when a user views their pm inbox or outbox and it is empty.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_form','This template will display a form allowing users to send a new pm to someone.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_inbox','This template will display a page allowing users to view their pm inbox.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_inbox_record','This template is used to display the indervidual messages in a users inbox.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_outbox','This template will display a page listing all messages sent by the user and the times they were read plus a list of messages that havent been read so far.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_outbox_record','This template is used to display the indervidual messages in a users outbox.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_show','This template is used to display indervidual private messages to the users.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pm_unread','This template will be displayed instead of the read date for private messages which have yet to be read by users.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_profile_edit','This template will allow users to update their profile.','1')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_profile_email_msg','This page is sent in the body of an email to users after they change their email address allowing them to validate it again.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_profile_email_subject','This page is sent as the subject of an email to users after they change their email address.','0')";
  $query[news_page][] = "INSERT INTO news_page VALUES (NULL,'-1','user_pwd_edit','This template will display a form allowing users to alter their password.','1')";

  $query[news_pageset][] = "INSERT INTO news_pageset VALUES (1,'Default')";

  $query[news_poll][] = "INSERT INTO news_poll VALUES (NULL,'Do You Like VirtuaNews?','I love it','Its Good','Seen Better','Whats VirtuaNews?','','','','','','','0','0,0,0,0','1')";

  $query[news_profilefield][] = "INSERT INTO news_profilefield VALUES (NULL,'Biography','What are you like?','0','0','1','1','250','40')";
  $query[news_profilefield][] = "INSERT INTO news_profilefield VALUES (NULL,'Location','Where do you live?','0','0','1','2','250','40')";
  $query[news_profilefield][] = "INSERT INTO news_profilefield VALUES (NULL,'Interests','What are your hobbies?','0','0','1','3','250','40')";
  $query[news_profilefield][] = "INSERT INTO news_profilefield VALUES (NULL,'Occupation','What is your job?','0','0','1','4','250','40')";

  $query[news_referer][] = "INSERT INTO news_referer VALUES ('Home','0')";
  $query[news_referer][] = "INSERT INTO news_referer VALUES ('None','0')";

  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'smile',':)','images/smilies/smile.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'frown',':(','images/smilies/frown.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'embarrasment',':o','images/smilies/redface.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'big grin',':D','images/smilies/biggrin.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'wink',';)','images/smilies/wink.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'stick out tongue',':p','images/smilies/tongue.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'cool',':cool:','images/smilies/cool.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'roll eyes (sarcastic)',':rolleyes:','images/smilies/rolleyes.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'mad',':mad:','images/smilies/mad.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'eek!',':eek:','images/smilies/eek.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'confused',':confused:','images/smilies/confused.gif')";
  $query[news_smilie][] = "INSERT INTO news_smilie VALUES (NULL,'knockout',':knockout:','images/smilies/knockout.gif')";

  $query[news_stats][] = "INSERT INTO news_stats VALUES ('" . time() . "','0','0','0')";

  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','body','body style=\"background-color:#476297;margin:8px\"')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','bordercolor','#555555')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','charset','utf-8')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','csspath','images/blue/style.css')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','headerbgcolor','#FFFBF7')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','headertablewidth','100%')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','htmldoctype','<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','imagefolder','images/blue')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','logopath','images/blue/virtuanews_logo.gif')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','mainbgcolor','#EFF1F6')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','maincellpadding','3')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','maincellspacing','0')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','maintablewidth','95%')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','menubgcolor','#FFFBF7')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'-1','submitbutton','<input type=\"image\" src=\"images/blue/submit.gif\" />')";

  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','body','body style=\"background-color:#666666;margin:8px\"')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','csspath','images/grey/style.css')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','headerbgcolor','#E2E2E2')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','imagefolder','images/grey')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','logopath','images/grey/virtuanews_logo.gif')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','mainbgcolor','#FFFFFF')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','menubgcolor','#E2E2E2')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'2','submitbutton','<input type=\"image\" src=\"images/grey/submit.gif\" />')";

  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','body','body style=\"background-color:#666699;margin:8px\"')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','csspath','images/purple/style.css')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','headerbgcolor','#EAEDF7')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','imagefolder','images/purple')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','logopath','images/purple/virtuanews_logo.gif')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','mainbgcolor','#ffffff')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','menubgcolor','#EAEDF7')";
  $query[news_style][] = "INSERT INTO news_style VALUES(NULL,'3','submitbutton','<input type=\"image\" src=\"images/purple/submit.gif\" />')";

  $query[news_styleset][] = "INSERT INTO news_styleset VALUES('1','Blue')";
  $query[news_styleset][] = "INSERT INTO news_styleset VALUES('2','Grey')";
  $query[news_styleset][] = "INSERT INTO news_styleset VALUES('3','Purple')";

  $query[news_theme][] = "INSERT INTO news_theme VALUES ('1','Blue','1','1','1')";
  $query[news_theme][] = "INSERT INTO news_theme VALUES ('2','Grey','1','2','1')";
  $query[news_theme][] = "INSERT INTO news_theme VALUES ('3','Purple','1','3','1')";

  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Netscape','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Opera','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('MSIE','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Lynx','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('WebTV','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Konqueror','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Bot','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Other','browser','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Windows','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Mac','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Linux','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('SunOS','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('IRIX','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('BeOS','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('OS/2','os','0')";
  $query[news_useragent][] = "INSERT INTO news_useragent VALUES ('Other','os','0')";

  echo "  <tr>\n    <td>Inserting the default data into the VirtuaNews tables:</tr>\n";

  while (list($table_name,$query_arr) = each($query)) {
    echo "  <tr>\n    <td>Adding $table_name Data</td>\n  </tr>\n";
    foreach ($query_arr AS $query_text) {
      query($query_text,0);
      if (geterrno() != 0) {
        echo "  <tr>\n    <td>Error Adding $table_name Data<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
        $error = 1;
      }
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error inserting one or more of the records.  Please examine this error and only continue if you are sure it will not effect the install process.  Click <a href=\"install.php?step=6\"> HERE</a> to continue</td>\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All tables created correctly, please click <a href=\"install.php?step=6\">here</a> to continue</tr>\n";
  }

}

if ($step == 6) {

  echo "  <tr>\n    <td>Inserting the default templates:</td>\n  </tr>\n";

  if (!file_exists("admin/virtuanews.set")) {
    echo "  <tr>\n    <td>Cannot find the template file.  Please ensure it is uploaded to admin/virtuanews.set  When you are sure it is uploaded correctly please refresh this page and continue</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $filesize = @filesize("admin/virtuanews.set");

  $fp = @fopen("admin/virtuanews.set","r");
  $filecontent = @fread($fp,$filesize);
  @fclose($fp);

  if (trim($filecontent) == "") {
    echo "  <tr>\n    <td>The template file is not the virtuanews master file, you must upload the master file and then refresh this page to continue.</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $data_arr = explode("|||||",$filecontent);

  unset($filecontent);

  foreach ($data_arr AS $data) {
    $count ++;
    if ($count%2 == 1) {
      $info[$data] = "";
      $last_info = $data;
    } else {
      $info[$last_info] = $data;
    }
  }
  unset($data_arr);

  if (($info[' theme title'] == "!!MASTER!!") & ($info[' page set'] == "!!MASTER!!") & ($info[' style set'] == "!!MASTER!!")) {
    $is_master = 1;
  } else {
    echo "  <tr>\n    <td>The template file is not the virtuanews master file, you must upload the master file and then refresh this page to continue.</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $pagesetid = "-1";
  $stylesetid = "-1";

  unset($info[' version']);
  unset($info[' theme title']);
  unset($info[' style set']);
  unset($info[' page set']);

  foreach($info AS $title => $data) {

    $data = undoescapetext($data);

    if (substr($title,0,9) == " page !!!") {
      $name = substr($title,9);
      if ($name != "") {
        if ($file = @fopen("pages/default/".$name.".vnp","w")) {
          @fwrite($file,$data);
          @$file = fclose($file);
        } else {
          $error = 1;
        }
      }
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error inserting one or more of the records.  Please examine this error and only continue if you are sure it will not effect the install process.  Click <a href=\"install.php?step=7\"> HERE</a> to continue</td>\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All templates inserted correctly, please click <a href=\"install.php?step=7\">here</a> to continue</tr>\n";
  }

}

if ($step == 7) {
?>
  <tr>
    <td>Please Enter the following details about your site to set up the options</td>
  </tr>
  <tr>
    <td>If you plan to use a forum on your site it MUST be installed already, also please note there is no easy way of converting between forums so please ensure you set it correct now.</td>
  </tr>
  <tr>
    <td>
      <form action="install.php" method="post" name="form">
      <input type="hidden" name="step" value="8">
      <table width="95%" cellpadding="5" cellspacing="0">
        <tr>
          <td>Site Name:</td>
          <td><input type="text" name="sitename" value="" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Home URL:<br><font class="red">Without trailing slash</font></td>
          <td><input type="text" name="homeurl" value="http://<?php echo $SERVER_NAME?>" class="form" size="40"></td>
        </tr>
        <tr>
          <td>Webmaster Email:</td>
          <td><input type="text" name="webmasteremail" value="webmaster@<?php echo $SERVER_NAME?>" class="form" size="40"></td>
        </tr>
          <td width="65%"><b>Forum To Use:</b><br>Please select the forums that you have installed on your server so that VirtuaNews can use the member tables from that to save your users having to log in twice.  For this to work VirtuaNews must be installed in the same database as your forums and the cookie path must be set so that VirtuaNews can also read and write to users cookies.</td>
          <td width="35%">
            <input type="radio" name="use_forum" value="" checked="checked"> None<br>
            <input type="radio" name="use_forum" value="vb_30"> VBulletin 3.0.x (Note, you must first edit the file includes/forum_vb_30.php for this to work, simply open up the file in a text editor and edit the top section as it instructs<br>
            <input type="radio" name="use_forum" value="vb_22"> VBulletin 2.2.x<br>
            <input type="radio" name="use_forum" value="vb_20"> VBulletin 2.0.x<br>
            <input type="radio" name="use_forum" value="ib_10"> IBforums 1.x.x<br>
            <input type="radio" name="use_forum" value="phpbb_20"> phpBB 2.0.x
          </td>
        </tr>
        <tr>
          <td>Forum Path:<br><font class="red">Relative to the VirtuaNews index.php file, without trailing slash</font><br>Only needed if you are using a forum on your site.</td>
          <td><input type="text" name="forumpath" value="" class="form" size="40"></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" name="submit" value="Submit" class="form"> <input type="reset" name="reset" value="Reset" class="form"></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php

}

if ($step == 8) {

  if (($sitename == "") | ($homeurl == "") | ($webmasteremail == "")) {
    echo "  <tr>\n    <td>Please enter new values in all the fields on the previous page. Press the back button and correct the error and then try again</tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  if (($use_forum == "vn_20") | ($use_forum == "vb_22") | ($use_forum == "ib_10") | ($use_forum == "phpbb_20") | ($use_forum == "vb_30")) {
    if ($forumpath == "") {
      echo "  <tr>\n    <td>You have sepcified to use the VBulletin user tables, however, you have not set a forum path, you must go back and do so.</tr>\n";
      echo "</body>\n</html>";
      exit;
    }
  } elseif (!empty($use_forum)) {
    echo "  <tr>\n    <td>The forum you have specified to use is not supported currently by VirtuaNews.</tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $query[] = "INSERT INTO news_option VALUES (NULL,'','use_forum','$use_forum','Forum To Use','DO NOT ALTER - It can lead to a major security risk','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'','version','$version','VirtuaNews Version','DO NOT ALTER','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'article','articleanonvote','1','Allow Anonymous Votes','Allow anonymous votes for articles.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'article','articlerateall','1','Show Rating Options On All Pages','Show the article rating options on all pages, if set to no it will only be displayed on the last page.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'article','articlesperpage','20','Articles per page','The number of articles listed per page by default','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','allowreport','2','Allow Users To Report Comments','Set who can report comments, when a comment is reported an email will be sent to all staff who have permission to edit that comment informing them about it.','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==0,\"checked\",\"\").\" value=\\\"0\\\"> No one<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> Staff Only<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> Registered Users Only<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==3,\"checked\",\"\").\" value=\\\"3\\\"> All Users")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentchrlimit','5000','Comment text length limit','Set a limit to the length of comments on the site to save flooding the page, set to 0 to disable','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentemaillimit','40','Comment email length limit','Set a limit to the length of users email addresses that are entered on the comments page to save flooding the page, set to 0 to disable','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentreplydefault','1','Comment Reply Default Layout','Set the default layout for the comment reply system, either all replies shown, or all hidden as default.','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> All Replies Hidden<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> All Replies Shown")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentreplytext_p','Replies','Reply Text - Plural','Text that will be available to be displayed when there are multiple replies to a comment.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentreplytext_s','Reply','Reply Text - Single','Text that will be available to be displayed when there is a single reply to a comment.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commenttext1_p','are','Comment Text 1 - Plural','Text that will be available to be displayed when there are multiple comments.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commenttext1_s','is','Comment Text 1 - Single','Text that will be available to be displayed when there is a single comment.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commenttext2_p','comments','Comment Text 2 - Plural','Text that will be available to be displayed when there are multiple comments.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commenttext2_s','comment','Comment Text 2 - Single','Text that will be available to be displayed when there is a single comment.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','commentuserlimit','40','Comment username length limit','Set a limit to the length of usernames that are entered on the comments page to save flooding the page, set to 0 to disable','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','displayips','1','Display IPs','You may wish to display users IP address after they have posted a comment.  You can select who to display these ips to here','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==0,\"checked\",\"\").\" value=\\\"0\\\"> Do Not Display IPs<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> Only Display To Staff<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> Do Not Display To Non-Registered Users<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==3,\"checked\",\"\").\" value=\\\"3\\\"> Display To All Users")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','enablecommentreply','1','Enable Comment Reply System','Set here whether to enable the comment reply system or not for news posts.  Please note, if users have already posted replies to comments they will remain replies unless deleted.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'comment','iphiddentext','Hidden','Text to hide users ip','Text that will be displayed when a users ip is hidden on the comments page','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'commentedit','allowediting','1','Allow Comment Editing','Allow Editing of comments for registered users, note unregistered users cannot edit comments','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentedit','commenteditlock','1','Lock Users Comments After Staff Editing','You can set whether to lock users comments to prevent further editing after a member of staff has edited them.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentedit','commentedittext','1','Show Edited by text','Show the \"Edit by \" text when a user edits a comment','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentedit','commentedittextadmin','1','Show Edited By Text For Admin','Show the \"Edit by \" text when an admin edits a comment','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentedit','commentedittexttime','180','Time delay for &quot;Edited By xxx&quot; text','Time limit (in seconds) to wait before the &quot;Edited By xxx&quot; text is displayed at the bottom of comments after they have been edited.','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'commentpost','emailreplydefault','1','Enable Email Reply By Default','Have the email notification checked by default on the comments page.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentpost','enableemail','1','Enable Email Notification','Enable the ability for registered users to have emails sent to them when a new comment has been posted to a news post they subscribed to','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentpost','logips','3','Log IPs','You may wish to log users IP address when they post a comment.  You can log everyones IP, everyones but news posters, only non-registered users, or none at all','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==0,\"checked\",\"\").\" value=\\\"0\\\"> Do Not Log IPs<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> Only Log Non-Registered Users<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> Log All But Staff<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==3,\"checked\",\"\").\" value=\\\"3\\\"> Log All IPs")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentpost','msietextarea','80','MSIE Text Area Width','Due To differences in the way Internet Explorer and Netscape display the &lt;textarea&gt; element, you can set the width of the text areas to be displayed for the comment page for the two main browsers here','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'commentpost','nettextarea','50','Netscape Text Area Width','Due To differences in the way Internet Explorer and Netscape display the &lt;textarea&gt; element, you can set the width of the text areas to be displayed for the comment page for the two main browsers here','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','aboutusdate','d F Y','About Us Date Format','Date format for the about us page, showing the date posters joined, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','archivedate','H:i','Archive Date Format','Date format for the archive, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','archiveheaddate','l, d M Y','Archive Header Date Format','This is the time format displayed on the archive page at the top of each days posts, it show the server adjusted time/date<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','articlesdate','d F Y','Articles date format','Date format for the articles, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','commentdate',' d M Y - H:i','Comment date format','Date format for the commets, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','joindateformat','d F Y','Member List Join Date Format','Date format for the memberlist showing join date, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','newsdate',' d M Y - H:i','News date format','Date format for the news posts, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'datetime','pmdate','d M Y - H:i','Private Message Date','Date format for the private messages, shows server adjusted time<br>Help: <a href=\"http://www.php.net/manual/function.date.php\" target=\"_blank\">PHP Manual</a>','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'format','censorwords','','Censor Words','Enter any words you wish to be censored in any posts (news,comments,articles etc) that are made to your site.  Seperate each word by a space, for instance if you wish the sentance &quot;The cat chased the dog&quot; to be replaced with &quot;The *** ***sed the dog&quot; you would enter &quot;cat cha&quot; into the text area.  Please note, this is case insensative.','textarea')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','loggedout_allowhtml','0','Allow HTML - Unregistered Users','Users the use of HTML by unregistered users in their comments.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','loggedout_allowimg','0','Allow [img] - Unregistered Users','Allow the tag [img] to be used by unregistered users in their comments to create images.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','loggedout_allowqhtml','1','Allow Quick HTML - Unregistered Users','Allow quick html to be used by unregistered users in their comments.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','loggedout_allowsmilies','1','Allow Smilies - Unregistered Users','Allow smilies to be used by unregistered users in their comments.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','maximages','10','Maximum Images In Posts','You can set a maximum number of images your users may post in their comments and messages.  Set this to 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','staff_allowhtml','1','Allow HTML - Staff','Allow the use of HTML by staff in their comments and news posts etc.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','staff_allowimg','1','Allow [img] - Staff','Allow the tag [img] to be used by staff in their comments and news posts etc to create images.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','staff_allowqhtml','1','Allow Quick HTML - Staff','Allow quick html to be used by staff in their comments and news posts etc.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','staff_allowsmilies','1','Allow Smilies - Staff','Allow smilies to be used by staff in their comments and news posts etc.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','user_allowhtml','0','Allow HTML - Users','Users the use of HTML by users in their comments.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','user_allowimg','1','Allow [img] - Users','Allow the tag [img] to be used by users in their comments to create images.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','user_allowqhtml','1','Allow Quick HTML - Users','Allow quick html to be used by users in their comments.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'format','user_allowsmilies','1','Allow Smilies - Users','Allow smilies to be used by users in their comments.','yesno')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'general','defaultcat_loggedin','1','Default Category For Registered Users','The default category for the registered user on the site, this is the category they will see if they dont specify a category.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','defaultcat_loggedout','1','Default Category For Unregistered Users','The default category for the unregistered user on the site,  this is the category they will see if they dont specify a category.  Also, the settings for this category will be used on all sub pages, eg the archive and search pages.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','defaultcat_staff','1','Default Category For Staff','The default category for the staff, this is the category they will see if they dont specify a category.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','forumpath','$forumpath','Bulletin Board Path','Path to the bulletin board on the site.  Note the path is relative to the index.php page of VirtuaNews.  Please do not include the trailing slash after the path.  You may leave this blank if you are using the member tables from VirtuaNews.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','homeurl','$homeurl','Home Page URL','The url of your home page, without the trailing /','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','loadlimit','0','Load Limit','On *nix servers you may set a load limit to prevent your server becoming overloaded.  A typical setting for a high load would be 5.00.  Once the load reaches this point users will no longer be allowed to view the site.  Set to 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','sessionlimit','0','Session Limit','Limit the number of users you may have at any one time on your site.  Set to 0 to disable.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','sitename','$sitename','Site title','The title of your site','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','siteopen','1','Site active','From time to time you may wish to shut your site, use this option to decide if your site is open.  Please note, your site will always remain open to staff.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','startpage','news','Start Page','The option will set what the user first sees when they go to your site on index.php.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','timeoffset','0','Time Zone Offset','Set the time zone that your server is located is so that all times can be adjusted to display GMT.','".addslashes("<select name=\\\"option_\$options[varname]\\\" class=\\\"form\\\">
<option value=\\\"-12\\\" \".iif(\$options[value]==-12,\"selected\",\"\").\">(GMT -12:00 hours) Eniwetok, Kwajalein</option>
<option value=\\\"-11\\\" \".iif(\$options[value]==-11,\"selected\",\"\").\">(GMT -11:00 hours) Midway Island, Samoa</option>
<option value=\\\"-10\\\" \".iif(\$options[value]==-10,\"selected\",\"\").\">(GMT -10:00 hours) Hawaii</option>
<option value=\\\"-9\\\" \".iif(\$options[value]==-9,\"selected\",\"\").\">(GMT -9:00 hours) Alaska</option>
<option value=\\\"-8\\\" \".iif(\$options[value]==-8,\"selected\",\"\").\">(GMT -8:00 hours) Pacific Time (US & Canada)</option>
<option value=\\\"-7\\\" \".iif(\$options[value]==-7,\"selected\",\"\").\">(GMT -7:00 hours) Mountain Time (US & Canada)</option>
<option value=\\\"-6\\\" \".iif(\$options[value]==-6,\"selected\",\"\").\">(GMT -6:00 hours) Central Time (US & Canada)</option>
<option value=\\\"-5\\\" \".iif(\$options[value]==-5,\"selected\",\"\").\">(GMT -5:00 hours) Eastern Time (US & Canada)</option>
<option value=\\\"-4\\\" \".iif(\$options[value]==-4,\"selected\",\"\").\">(GMT -4:00 hours) Atlantic Time (Canada)</option>
<option value=\\\"-3.5\\\" \".iif(\$options[value]==-3.5,\"selected\",\"\").\">(GMT -3:30 hours) Newfoundland</option>
<option value=\\\"-3\\\" \".iif(\$options[value]==-3,\"selected\",\"\").\">(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown</option>
<option value=\\\"-2\\\" \".iif(\$options[value]==-2,\"selected\",\"\").\">(GMT -2:00 hours) Mid-Atlantic</option>
<option value=\\\"-1\\\" \".iif(\$options[value]==-1,\"selected\",\"\").\">(GMT -1:00 hours) Azores, Cape Verde Islands</option>
<option value=\\\"0\\\" \".iif(\$options[value]==0,\"selected\",\"\").\">(GMT) Western Europe Time, London, Lisbon</option>
<option value=\\\"+1\\\" \".iif(\$options[value]==+1,\"selected\",\"\").\">(GMT +1:00 hours) CET(Central Europe Time)</option>
<option value=\\\"+2\\\" \".iif(\$options[value]==+2,\"selected\",\"\").\">(GMT +2:00 hours) EET(Eastern Europe Time)</option>
<option value=\\\"+3\\\" \".iif(\$options[value]==+3,\"selected\",\"\").\">(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow</option>
<option value=\\\"+3.5\\\" \".iif(\$options[value]==+3.5,\"selected\",\"\").\">(GMT +3:30 hours) Tehran</option>
<option value=\\\"+4\\\" \".iif(\$options[value]==+4,\"selected\",\"\").\">(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value=\\\"+4.5\\\" \".iif(\$options[value]==+4.5,\"selected\",\"\").\">(GMT +4:30 hours) Kabul</option>
<option value=\\\"+5\\\" \".iif(\$options[value]==+5,\"selected\",\"\").\">(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi</option>
<option value=\\\"+5.5\\\" \".iif(\$options[value]==+5.5,\"selected\",\"\").\">(GMT +5:30 hours) Bombay, Calcutta, Madras</option>
<option value=\\\"+6\\\" \".iif(\$options[value]==+6,\"selected\",\"\").\">(GMT +6:00 hours) Almaty, Dhaka, Colombo</option>
<option value=\\\"+7\\\" \".iif(\$options[value]==+7,\"selected\",\"\").\">(GMT +7:00 hours) Bangkok, Hanoi, Jakarta</option>
<option value=\\\"+8\\\" \".iif(\$options[value]==+8,\"selected\",\"\").\">(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong</option>
<option value=\\\"+9\\\" \".iif(\$options[value]==+9,\"selected\",\"\").\">(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo</option>
<option value=\\\"+9.5\\\" \".iif(\$options[value]==+9.5,\"selected\",\"\").\">(GMT +9:30 hours) Adelaide, Darwin</option>
<option value=\\\"+10\\\" \".iif(\$options[value]==+10,\"selected\",\"\").\">(GMT +10:00 hours) EAST(East Australian Standard)</option>
<option value=\\\"+11\\\" \".iif(\$options[value]==+11,\"selected\",\"\").\">(GMT +11:00 hours) Magadan, Solomon Islands</option>
<option value=\\\"+12\\\" \".iif(\$options[value]==+12,\"selected\",\"\").\">(GMT +12:00 hours) Auckland, Wellington, Fiji</option>
</select>")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'general','webmasteremail','$webmasteremail','Web Master Email','Contact email for the web master of the site','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'ipbanning','banhost','','Hostnames To Ban','You may wish to ban certain host names from your site. Enter them here and they will be matched against the users hostname, seperate each name with a space.  Using this option can often result in a delay when loading pages due to PHP being slow at resolving hostnames.  We advise that you either keep this as short as possable or enter specific IP\'s to ban.','textarea')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'ipbanning','banip','','IP Addresses To Ban','You may wish to ban certain ip address from your site. Enter them here and they will be matched against the users IP address, seperate each address with a space.  You may enter a specific IP range by using * eg 125.213.213.* will ban any user with that range.','textarea')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'news','archiveperpage','7','Days Per Page On Archive','Number of days to show per page on the archive','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newsallowlogoup','1','Allow Upload Of News Logos','Allow posters to upload news logos to post along with their news posts.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newsautolock','0','Automatically Lock News','You may set your news posts to automatically lock to prevent further commenting on old news posts.  Please enter the number of days old a post must be to be automatically locked, enter 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newsedittext','0','Show Edited by text','Show the \"Edited by \" text when a user edits a news post','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newsedittexttime','180','Time delay for &quot;Edited By xxx&quot; text','Time limit (in seconds) to wait before the &quot;Edited By xxx&quot; text is displayed at the bottom of news posts after they have been edited.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newslogooverwrite','0','Allow Logos To be Overwritten','Set whether to allow staff to overwrite news logos when they upload new ones.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newslogoupsize','102400','Maximum Size Of Logo Uploads','Set the maximum size in bytes that a member of staff may upload for use in news posts.  Enter 0 to enable any sized uploads, (1 KB = 1024 bytes and 1 MB = 1048576 bytes).','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newslogouptype','gif jpg png bmp jpeg','Valid Extentions Of Logo Uploads','Please specify the types of files that staff may upload to display as news logos.  Seperate each entry with a space, eg &quot;gif jpg&quot; would only allow .gif and .jpg files to be uploaded.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','newsperpage','15','News Posts Per Page','Set the number of news posts to display on the front page','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'news','showsubcatlist','0','Show Sub Categories On Cat List','You can display the sub categories to the category being viewed on the category listing, use this options to set whether to allow it or not.','yesno')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'output','cookiedomain','','Cookie Domain','Set the domain to save cookies under.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'output','cookiepath','/','Cookie Path','Path to save cookies.  Please note, if you are using VBulletin user tables then this option MUST be the same as your VBulletin setting, and set to a path that both VirtuaNews and VBulletin can access (eg. / NOT /vbulletin/).','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'output','gziplevel','1','GZip Level','Set the level of compression (between 1 and 9) for gzipping your pages, higher values will increase server load drimatically.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'output','gzipoutput','0','GZip output','If enabled then all pages will be compressed using the gzip funtion, this will save bandwidth on your site, however, it will increase server load.  Please note your server must support gzip for this function to work (no error will be generated if you enable this and your server does not support gzip).','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'output','nocacheheaders','0','Force Refreshing of Pages Automatically','If enabled then VirtuaNews will send headers to the users browsers preventing them from caching any pages on your site, effectively forcing the user to refresh the page every time they visit.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'output','useredirects','1','Use Redirect Page','After a user submits something to the site they are redirected to a different place, by default a page saying &quot;Thank You, you are being redirected to...&quot; is displayed, you can turn this off and users will be redirected immediatly without seeing anything.  You can use this to save on time or bandwidth.','yesno')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','allowpms','2','Allow Private Messaging','Set who can use the private message system.','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==0,\"checked\",\"\").\" value=\\\"0\\\"> No One<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> Staff Only<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> All Registered Users Only")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','getpmmsg','1','Display Private Messages','Get the users private message details (logged in users only) eg. how many messages in their inbox, to be displayed on the site','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','pmcharlimit','5000','Private Message Character Limit','Set the how long private messages may be.  Set to 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','pmcountsent','1','Count Sent Messages Towards Limit','If set to yes then messages users have stored that they have sent will be counted towards theit PM limit.  If set to no then users can have as many sent messages stored as they wish.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','pmlimitstaff','100','PM Limit For Staff','Limit the number of pm\'s staff may have stored. Set to 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','pmlimituser','50','PM Limit For Users','Set the number of PM\'s ordinary users may have stored.  Set to 0 to disable it.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'pm','pmperpage','20','Private Messages Displayed Per Page','Set the number of pm\'s to be displayed per page for users.','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'polls','pollanonvote','1','Allow Anonymous Votes','Allow anonymous votes for polls.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'polls','pollsperpage','10','Polls shown per page','The number of polls shown on the summary page for all the polls','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'recentpost','recentpostchr','35','Recent Post Characters','You can limit the number of characters there will be in the titles shown on the recent post, to prevent long titles from making the layout look incorrect, set this number of characters here','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'recentpost','recentpostcount','5','Number Of Recent Posts','Number of recent posts to show in the table','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'search','allowsearch','1','Allow Searches','Allow searches through the news on the site','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'search','highlightsearch','1','Highlight Search Phrase','If set this will highlight the search phrase if found in the title of the results red','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'search','searchperpage','40','Search results shown per page','The number of search results shown per page by default','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'sitejump','showsitejump','0','Show Site Jump','You cant set whether or not to show the site jump on your pages, this is used to allow your users to quickly jump to places on your site.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'sitejump','sitejump_newsposts','15','News Posts To List','Set the number of recent news posts to list in the site jump.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'sitejump','sitejump_shownews','0','Show News In Site Jump','If set to yes then the site jump will show a list of recent news in the current category to jump to.','yesno')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'stats','allowstats','1','Allow Stats To Be Viewed','Allow the stats page to be viewed by users, the stats page contains the information on browsers and operating systems of the visitors to the site.  Note, this feature is always available in the admin section for posters','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'stats','dohttpreferer','1','Count HTTP Referers','Use the built in function to count HTTP referes to your site, see who links to your site','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'stats','dositestats','1','Use site counter','Use the built in hit counter, it this is not enabled, then no stats features will work at all','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'stats','sessiontimeout','900','User session time out','The length of time before a users session times out (in seconds), used to detirmine how many users are online at any one time','')";

  $query[] = "INSERT INTO news_option VALUES (NULL,'user','allowmultiplesignups','0','Allow Multiple Sign Ups','If enabled then your users will be able to sign up to your site multiple times, if not they can only register once.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','allowsignup','1','Allow New Users','Allow new users to sign up to your site.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','allowuserprofile','1','Allow Viewing Of User Profiles','Set whether you wish to allow users to view each others profiles or not.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','allowusersigs','1','Allow User Signatures','Set whether to allow user signatures or not with posts.  Only available for registered users, note, will work for VBulletin users as well.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','enablememberlist','1','Enable Member List','If set to yes then users will be able to view the member list showing details of all the members on your site.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','memberlistperpage','30','Members Shown Per Page','Set the number of members shown per page on the member list.','')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','newuseremail','0','Email Staff About New Users','If enabled then staff who have permission to edit the users of your site will be emailed when a new user registeres.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','requireuniqueemail','0','Require Unique Email Address','If enabled then new users signing up will be forced to enter a unique email address.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','requirevalidemail','1','Require Valid Email Address','If enabled then new users will be required to activate their email address before they are a valid member in order to confirm their address.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','usememberemail','2','Use Member Email Form','Set whether users can use the email form allowing them to email other users (is not possable if user has specified to hide their email).  If this is disabled then users will not be able to contact other users by email.','".addslashes("<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\"  \".iif(\$options[value]==0,\"checked\",\"\").\" value=\\\"0\\\"> None<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==1,\"checked\",\"\").\" value=\\\"1\\\"> Staff Only<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==2,\"checked\",\"\").\" value=\\\"2\\\"> Registered Users Only<br>
<input type=\\\"radio\\\" name=\\\"option_\$options[varname]\\\" \".iif(\$options[value]==3,\"checked\",\"\").\" value=\\\"3\\\"> All Users")."')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','usermoderation','0','Require User Moderation','If enabled then when a new user signs up to your site they will not be a full member till their account is moderated by a poster.','yesno')";
  $query[] = "INSERT INTO news_option VALUES (NULL,'user','usersubperpage','30','User Subscribe Posts Per Page','Set the number of posts to display on the user panel showing the posts users have subscribed to.','')";

  echo "  <tr>\n    <td>Inserting the option data into the VirtuaNews tables</tr>\n";

  while (list($key,$query_text) = each($query)) {
    query($query_text,0);
    if (geterrno() != 0) {
      echo "  <tr>\n    <td>Error Adding Option Data<br>Error Number: ".geterrno()."<br>Error Description: ".geterrordesc()."</td>\n  </tr>\n";
      $error = 1;
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error inserting one or more of the records.  Please examine this error and only continue if you are sure it will not effect the install process.  Click <a href=\"install.php?step=9\"> HERE</a> to continue </td>\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All options inserted correctly, please click <a href=\"install.php?step=9\">here</a> to continue</tr>\n";
  }

}

if ($step == 9) {

  $get_options = query("SELECT varname,value FROM news_option WHERE varname IN ('use_forum','forumpath')");
  while ($option_arr = fetch_array($get_options)) {
    ${$option_arr[varname]} = $option_arr[value];
  }

  include("includes/functions.php");

  if (!empty($use_forum)) {
    include("includes/forum_".strtolower(trim($use_forum)).".php");

    if ($userinfo = validateuser($userid,$userpassword)) {
?>
  <tr>
    <td>
      <form action="install.php" method="post" name="form">
      <input type="hidden" name="step" value="10">
      <table width="95%" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2">Please confirm the following details, they have been taken from your cookies which you are logged into your forums with.  If they are not correct, please login to your forums with the correct details then refresh this page.</td>
        </tr>
        <tr>
          <td>Username:</td>
          <td><?php echo $userinfo[username];?></td>
        </tr>
        <tr>
          <td>Email Address:</td>
          <td><?php echo $userinfo[email];?></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="Submit" class="form"></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
    } else {
      echo "  <tr>\n    <td>You are not logged in correctly to your forums and you have selected to use user tables from your forums, you must first be logged in correctly as a registered user on the forums before you can be added as the administrator to VirtuaNews.  Please log in then refresh this page</td>\n  </tr>\n</table>\n</body>\n</html>";
      exit;
    }

  } else {
?>
  <tr>
    <td>
      <form action="install.php" method="post" name="form">
      <input type="hidden" name="step" value="10">
      <table width="95%" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2">Please enter the following details to set you up as a staff member.</td>
        </tr>
        <tr>
          <td>Username:</td>
          <td><input type="text" name="username" size="40" class="form"></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="text" name="password" size="40" class="form"></td>
        </tr>
        <tr>
          <td>Email Address:</td>
          <td><input type="text" name="email" size="40" class="form"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="Submit" class="form"></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
  }
}

if ($step == 10) {

  $get_options = query("SELECT varname,value FROM news_option WHERE varname IN ('use_forum','forumpath')");
  while ($option_arr = fetch_array($get_options)) {
    ${$option_arr[varname]} = $option_arr[value];
  }

  include("includes/functions.php");

  if (!empty($use_forum)) {
    include("includes/forum_".strtolower(trim($use_forum)).".php");

    if ($userinfo = validateuser($userid,$userpassword)) {
      $username = $userinfo[username];
      $password = $userpassword;
      $email = $userinfo[email];
    } else {
      echo "  <tr>\n    <td>You are not logged in correctly to your forums and you have selected to use user tables from your forums, you must first be logged in correctly as a registered user on the forums before you can be added as the administrator to VirtuaNews.  Please log in then refresh this page</td>\n  </tr>\n</table>\n</body>\n</html>";
      exit;
    }
  } else {
    include("includes/forum_vn.php");
    $userid = 1;
    $password = md5($password);
  }

  query("INSERT INTO news_user VALUES ('1','$username','$password','$email','1','','','','','','1','1','".time()."','0','1','1','1','1','1','0')");
  query("INSERT INTO news_userfield VALUES (1,'','','','')");
  query("INSERT INTO news_staff VALUES ('1','$userid','Admin','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1')");

  include("includes/adminfunctions.php");
  include("includes/writefunctions.php");

  saveoptions();
  $cat_arr = getcat_arr();
  $theme_arr = getthemearr();
  $timeoffset = $timeoffset * 3600;
  $defaultcategory = 1;

  $themeid = 1;
  $pagesetid = 1;

  writeallpages();

  echo "  <tr>\n    <td>You have now completed the install of VirtuaNews, the control pannel can be found <a href=\"admin.php\">here</a>, available once you have deleted this install file.</td>\n  </tr>\n";

}
?>
</table>
</body>
</html>