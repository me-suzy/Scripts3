<?php
###############################
# Last modified 24/12/2003
################################
$myerror = array();



function DBConnect($host,$user,$pass,$name){
   $res = mysql_connect($host,$user,$pass);
   if(!$res)addError("Connection error.","Error database connecting");
   $res = mysql_selectdb($name);
   if(!$res)addError("Database not found.","Please, check database name");
}

function addError($val){ global $myerror; $myerror[sizeof($myerror)] = $val; }

function outError(){ global $myerror; $rv = '<br><table border=0 cellspacing=1 cellpadding=1><tr><td>';
         foreach ( $myerror as $key => $val ){
         $rv .= "<img src=\"images/bullet.gif\" width=16 height=10 border=0 align=absmiddle><span class=title><font color=#A70000>$val</font></span><br>";}
         return $rv."</td></tr></table>";
}
function isError(){ global $myerror; if(sizeof($myerror)>0)return true; return false; }


function isEmpty($val){ if(strlen($val) == 0)return true; return false; }

function WriteFile(&$rv){
    $ls = fopen("defines.php","w");
    fputs($ls,$rv);
    fclose($ls);
    Header("Location: index.php");
    exit(1);
}

$sql = array();

$sql[1] = "CREATE TABLE `ROIactions` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `campaign_id` int(8) unsigned NOT NULL default '0',
  `action_name` varchar(255) NOT NULL default '',
  `action_link` varchar(255) NOT NULL default '',
  `action_code` varchar(15) NOT NULL default '',
  `action_type` int(1) NOT NULL default '1',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_point` int(1) NOT NULL default '0',
  `action_price` float NOT NULL default '0',
  `user_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

$sql[2] = "CREATE TABLE `ROIcampaigns` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `campaign_name` varchar(255) NOT NULL default '',
  `campaign_link` varchar(255) NOT NULL default '',
  `campaign_code` varchar(15) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `price_type` int(1) NOT NULL default '1',
  `per_click` float NOT NULL default '0',
  `campaign_begin` date NOT NULL default '0000-00-00',
  `campaign_end` date NOT NULL default '0000-00-00',
  `price` float NOT NULL default '0',
  `pertype` int(1) NOT NULL default '0',
  `user_id` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

$sql[3] = "CREATE TABLE `ROIclicks` (
  `campaign_id` int(8) unsigned NOT NULL default '0',
  `ip` varchar(16) NOT NULL default '0.0.0.0',
  `host` varchar(255) NOT NULL default '',
  `tm` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_id` int(8) NOT NULL default '0',
  `keyword_id` int(8) default '-1'
) TYPE=MyISAM";

$sql[4] = "CREATE TABLE `ROIkeywords` (
  `id` int(8) NOT NULL auto_increment,
  `campaign_id` int(8) default NULL,
  `keyword_name` varchar(255) default NULL,
  `keyword_price` float default NULL,
  `listing_id` int(8) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM";

$sql[5] = "CREATE TABLE `ROIlistings` (
  `id` int(8) NOT NULL auto_increment,
  `listing_name` varchar(255) default '0',
  `campaign_id` int(8) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

$sql[6] = "CREATE TABLE `ROIlogs` (
  `action_id` int(8) unsigned NOT NULL default '0',
  `ip` varchar(16) NOT NULL default '0.0.0.0',
  `tm` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_id` int(8) NOT NULL default '0',
  `keyword_id` int(8) default '0'
) TYPE=MyISAM";

$sql[7] = "CREATE TABLE `ROIusers` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `pw` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM";

  foreach($HTTP_POST_VARS as $key => $value){
    $$key = $value;
  }
  $vals = array('usr','pwd','dblogin','dbhost','dbname','dbpass');
  for($i = 0; $i < count($vals); $i++ ){
    if(!isset($$vals[$i]))$$vals[$i] = '';
  }


$login_page = <<<EOT
<html><head><title></title>
<link href="css/all.css" rel="stylesheet" type="text/css">
<link rel="SHORTCUT ICON" href="favicon.ico">
</head>
<body bgcolor=#ffffff>
<center><img src="images/roi_logo.jpg">
<br>
Please enter your user name and password to access the ROI Tracking Pro control panel.
<br>
</center>

<center>

%error%

<form action="install.php?install" method=post>
<table border=0 cellspacing=0 cellpadding=4>


<tr>
<td><b>Login</b>:</td>
<td><input type=text name=usr class=txt value="$usr" style="width: 200px"></td>
</tr>


<tr>
<td><b>Password</b>:</td>
<td><input type=password name=pwd class=txt value="$pwd" style="width: 200px"></td>
</tr>

<tr>
<td colspan=2><hr size=1 color=#cccccc width=100%></td>
</tr>


<tr>
<td><b>Database host:</b></td>
<td><input type=text name=dbhost class=txt value="$dbhost" style="width: 200px"></td>
</tr>


<tr>
<td><b>Database name:</b></td>
<td><input type=text name=dbname class=txt value="$dbname" style="width: 200px"></td>
</tr>


<tr>
<td><b>Database user:</b></td>
<td><input type=text name=dblogin class=txt value="$dblogin" style="width: 200px"></td>
</tr>

<tr>
<td><b>Database password:</b></td>
<td><input type=password name=dbpass class=txt avlue="$dbpass" style="width: 200px"></td>
</tr>

<tr>
<td colspan=2 align=right><input type=image src="images/button-send.gif">
</td>
</table>
</form>
</center>
</body></html>
EOT;

#for($i = 1; $i <= 6; $i++){
# $res = mysql_query($sql[$i]);
# if(!$res)echo "#$i fieled<br>";
# }

if($HTTP_SERVER_VARS['QUERY_STRING'] == 'install'){

 // if(!chmod("defines.php", 0666))addError("Error setting permissions.");
  //if(!chmod("temp",0777))addError("Error setting permissions for directory.");

  if(isEmpty($usr))addError("Invalid login name");
  if(isEmpty($pwd))addError("Invalid password");
  if(isEmpty($dbhost))addError("Invalid database host");
  if(isEmpty($dbname))addError("Invalid database name");
  if(isEmpty($dblogin))addError("Invalid database user");

  if(!isError())DBConnect($dbhost,$dblogin,$dbpass,$dbname);
  if(!isError()){
      for($i = 1; $i <= 7; $i++){
             $res = mysql_query(str_replace("\n","",$sql[$i]));
             if(!$res)addError("Table #$i not created");
      }
      $res = mysql_query("SHOW TABLES");
//      while($row = mysql_fetch_row($res))echo "Table $row[0] <br>";
  }
  if(!isError()){
 $rv.='<?php
    define("PASSWORD","'.$pwd.'");
    define("USER","'.$usr.'");
    define("TIMEOUT",1800);
    define("DBHOST","'.$dbhost.'");
    define("DBUSER","'.$dblogin.'");
    define("DBPASS","'.$dbpass.'");
    define("DBNAME","'.$dbname.'");
    ?>';
  WriteFile($rv);
  echo str_replace('%error%',"<b><font size=5 color=#007534>Installation complete. Please, remove this file from your server.</font></b>",$login_page);
  exit(1);
  }
}

 echo str_replace('%error%',outError(),$login_page);

?>