<?php
@error_reporting(7);
@set_time_limit(0);
@set_magic_quotes_runtime(0);
$phpversion=(int)(str_replace(".","",phpversion()));
require("lib/functions.php");
if($phpversion<410) {
 $_REQUEST=array();
 $_COOKIE=array();
 $_POST=array();
 $_GET=array();
 $_SERVER=array();
 $_FILES=array();
 get_vars_old();
}

if(isset($_REQUEST['step'])) $step=intval($_REQUEST['step']);
else $step=0;
if(isset($_REQUEST['ustep'])) $ustep=intval($_REQUEST['ustep']);
else $ustep=0;
if(isset($_REQUEST['mode'])) $mode=intval($_REQUEST['mode']);
else $mode=0;


if($step==0 && $ustep==0) {
@chmod("../",0777);	
@chmod("../../",0777);	
@chmod("../attachments",0777);	
@chmod("../images/avatars",0777);	
@chmod("lib",0777);	
@chmod("lib/config.inc.php",0777);	
@chmod("lib/options.inc.php",0777);	
?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td><b>Welcome to WoltLab Burning Board's Setup</b></td>
  </tr>
  <tr>
   <td>&nbsp;<br><i>System Requirements:</i>
   <table>
    <tr>
     <td><u>Aspect</u></td>
     <td><u>Requirement</u></td>
     <td><u>Your Specs</u></td>
    </tr>
    <tr>
     <td>PHP Version</td>
     <td>4.0.1</td>
     <td><font color="<?php if($phpversion<401) echo "red"; else echo "green"; ?>">
     <?php echo phpversion(); ?></font></td>
    </tr>
    <tr>
     <td>magic_quotes_sybase</td>
     <td>deactivated</td>
     <td><font color="<?php if(get_cfg_var("magic_quotes_sybase")) echo "red"; else echo "green"; ?>">
     <?php if(get_cfg_var("magic_quotes_sybase")) echo "activated";
     else echo "deactivated"; ?></font></td>
    </tr>
    <!--<tr>
     <td>safe_mode</td>
     <td>deactivated</td>
     <td><font color="<?php if(get_cfg_var("safe_mode")) echo "red"; else echo "green"; ?>">
     <?php if(get_cfg_var("safe_mode")) echo "activated";
     else echo "deactivated"; ?></font></td>
    </tr>-->
    <tr>
     <td>upload_max_filesize</td>
     <td>> 0</td>
     <td><font color="<?php if(!get_cfg_var("upload_max_filesize")) echo "red"; else echo "green"; ?>">
     <?php echo get_cfg_var("upload_max_filesize"); ?></font></td>
    </tr>
    <tr>
     <td>Writing rights to "acp/lib"</td>
     <td>Yes</td>
     <td><font color="<?php if(!is_writeable("lib")) echo "red"; else echo "green"; ?>">
     <?php if(is_writeable("lib")) echo "Yes";
     else echo "No"; ?></font></td>
    </tr>
    <tr>
     <td>Writing rights to "attachments"</td>
     <td>Yes</td>
     <td><font color="<?php if(!is_writeable("../attachments")) echo "red"; else echo "green"; ?>">
     <?php if(is_writeable("../attachments")) echo "Yes";
     else echo "No"; ?></font></td>
    </tr>
    <tr>
     <td>Writing rights to "images/avatars"</td>
     <td>Yes</td>
     <td><font color="<?php if(!is_writeable("../images/avatars")) echo "red"; else echo "green"; ?>">
     <?php if(is_writeable("../images/avatars")) echo "Yes";
     else echo "No"; ?></font></td>
    </tr>
   </table>
   <p><i>If one or several requirements are not met, the board might not function correctly.</i></p>
   <form method="post" action="setup.php"><select name="mode">
    <option value="0">Please choose the type of installation:</option>
    <option value="1">New installation</option>
    <option value="2">Update from wBB 1.0 Beta 4.0 or older</option>
    <option value="3">Update from wBB 2.0 RC 2 to 2.0 Final</option> 
   </select> <input type="submit" value="Continue">
   <input type="hidden" name="step" value="1">
   </form>
   </td>
  </tr>
 </table>
 </body></html>	
<?php	
}

if($step==1) {
 if(!$mode) {
  header("Location: setup.php?step=0");
  exit();
 }
 if($mode==3) {
  header("Location: setup.php?ustep=1");
  exit();
 }	
 if(isset($_POST['send'])) {
  $fp=fopen("lib/config.inc.php","w+");	
  fwrite($fp,"<?php
// Hostname or IP of your MySQL Server
\$sqlhost = \"".str_replace("\"","\\\\\"",$_POST['sqlhost'])."\";
// Username and Password to Login to your Database
\$sqluser = \"".str_replace("\"","\\\\\"",$_POST['sqluser'])."\";
\$sqlpassword = \"".str_replace("\"","\\\\\"",$_POST['sqlpassword'])."\";
// Name of your Database
\$sqldb = \"".str_replace("\"","\\\\\"",$_POST['sqldb'])."\";
// Number of this Forum
\$n = \"".intval($_POST['n'])."\";
// Email Address of the Administrator
\$adminmail = \"".str_replace("\"","\\\\\"",$_POST['adminmail'])."\";
?>");
  fclose($fp);
  header("Location: setup.php?step=2&mode=$mode");
  exit();
 }
 else {
  require("lib/config.inc.php");
  ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td><b>Enter the correct information for the database</b></td>
  </tr>
  <tr>
   <td>
   <table><form method="post" action="setup.php">
    <tr>
     <td>Database Address:</td>
     <td><input type="text" name="sqlhost" value="<?php echo $sqlhost; ?>"></td>
    </tr>
    <tr>
     <td>Database Username:</td>
     <td><input type="text" name="sqluser" value="<?php echo $sqluser; ?>"></td>
    </tr>
    <tr>
     <td>Database Password:</td>
     <td><input type="text" name="sqlpassword" value="<?php echo $sqlpassword; ?>"></td>
    </tr>
    <tr>
     <td>Database Name:</td>
     <td><input type="text" name="sqldb" value="<?php echo $sqldb; ?>"></td>
    </tr>
    <tr>
     <td>Number of this Forum:</td>
     <td><input type="text" name="n" value="<?php echo $n; ?>"></td>
    </tr>
    <tr>
     <td>Email Address of the Administrator:</td>
     <td><input type="text" name="adminmail" value="<?php echo $adminmail; ?>"></td>
    </tr>
   </table>
   <p align="center"><input type="submit" value="Submit"> <input type="reset" value="Reset"></p>
   <input type="hidden" name="step" value="1">
   <input type="hidden" name="send" value="send">
   <input type="hidden" name="mode" value="<?php echo $mode; ?>">
   </form><a href="setup.php?step=2&mode=<?php echo $mode; ?>">If you already have edited "config.inc.php" by hand, you can skip this step.</a>
   </td>
  </tr>
 </table>
 </body></html>	
<?php	
 }
}

if($step==2) {
 require("lib/config.inc.php");
 $error=0;
 $connid=@mysql_connect($sqlhost,$sqluser,$sqlpassword);	
 if(!$connid) $error=1;
 else {
  if(!@mysql_select_db($sqldb,$connid)) $error=1;
  else {
   header("Location: setup.php?step=3&mode=$mode");
   exit();	
  }	
 }
 
 if($error=1) { ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td>Could not connect to database, one or more of the information you gave in the last step are wrong.
   <br><br><a href="setup.php?step=1&mode=<?php echo $mode; ?>">Please go back to and correct the information.</a>
   </td>
  </tr>
 </table>
</body>
</html>	  	
<?php
 }
}

if($step==3) {
 require("lib/config.inc.php");
 require("lib/class_db_mysql.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);
	
 $tables=array(
 "bb".$n."_access",
 "bb".$n."_adminsessions",
 "bb".$n."_announcements",
 "bb".$n."_attachments",
 "bb".$n."_avatars",
 "bb".$n."_bbcodes",
 "bb".$n."_boards",
 "bb".$n."_events",
 "bb".$n."_folders",
 "bb".$n."_groups",
 "bb".$n."_icons",
 "bb".$n."_moderators",
 "bb".$n."_optiongroups",
 "bb".$n."_options",
 "bb".$n."_permissions",
 "bb".$n."_polloptions",
 "bb".$n."_polls",
 "bb".$n."_posts",
 "bb".$n."_privatemessage",
 "bb".$n."_profilefields",
 "bb".$n."_ranks",
 "bb".$n."_searchs",
 "bb".$n."_sessions",
 "bb".$n."_smilies",
 "bb".$n."_styles",
 "bb".$n."_subscribeboards",
 "bb".$n."_subscribethreads",
 "bb".$n."_subvariablepacks",
 "bb".$n."_subvariables",
 "bb".$n."_templatepacks",
 "bb".$n."_templates",
 "bb".$n."_threads",
 "bb".$n."_userfields",
 "bb".$n."_users",
 "bb".$n."_votes",
 "bb".$n."_wordlist",
 "bb".$n."_wordmatch");
 
 $c=0;
 $result = mysql_list_tables($sqldb); 
 for($i=0; $i<$db->num_rows($result); $i++) { 
  if(in_array(mysql_tablename($result,$i),$tables)) {
   $c=1;
   break;	
  } 
 }
 
 if($c==1) { ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td>There already are tables with the same name in your database, if you continue with the installation, they will be overwritten.
   <br><br><a href="setup.php?step=4&mode=<?php echo $mode; ?>">Continue</a>
   </td>
  </tr>
 </table>
</body>
</html>	 	
<?php	
 }
 else {
  header("Location: setup.php?step=4&mode=$mode");
  exit();	
 }
}

if($step==4) {
 require("lib/config.inc.php");
 require("lib/class_db_mysql.php");
 require("lib/class_query.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);
 
 $query=implode("",file("lib/structure.sql"));
 if($n!=1) $query=str_replace("bb1_","bb".$n."_",$query); 
 $sql_query = new query($query);
 $sql_query->doquery();
  
 header("Location: setup.php?step=5&mode=$mode");
 exit();
}

if($step==5) {
 require("lib/config.inc.php");
 require("lib/class_db_mysql.php");
 require("lib/class_query.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);

 $query=implode("",file("lib/inserts.sql"));
 if($n!=1) $query=str_replace("bb1_","bb".$n."_",$query); 
 $sql_query = new query($query);
 $sql_query->doquery();	

 list($version) = $db->query_first("SELECT VERSION()");
 if( preg_match("/^(3\.23)|(4\.)/", $version) ) $db->query("ALTER TABLE bb".$n."_sessions TYPE=HEAP");

 header("Location: setup.php?step=6&mode=$mode");
 exit();	
} 

if($step==6) {
 require("lib/config.inc.php");
 require("lib/class_db_mysql.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);

 $split="~~~";
 $stylefile="bb1.style";
 if(file_exists($stylefile)) {
  $file = file($stylefile);
  $firstrow = explode($split,array_shift($file));	
  	
  $file=explode($split.$split,implode("",$file));
  $db->query("INSERT INTO bb".$n."_subvariablepacks (subvariablepackid,subvariablepackname) VALUES (NULL,'".addslashes($firstrow[1])."')");
  $subvariablepackid=$db->insert_id();
  
  $subvariables=explode($split,$file[0]);
  for($i=0;$i<count($subvariables)/2;$i++) $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','".addslashes($subvariables[($i*2)])."','".addslashes($subvariables[($i*2+1)])."')",1);
 
  if($firstrow[2]==-1) $templatepackid=0;
  else {
   $db->query("INSERT INTO bb".$n."_templatepacks (templatepackid,templatepackname) VALUES (NULL,'".addslashes($firstrow[2])."')");
   $templatepackid=$db->insert_id();	
  }
  
  $templates=explode($split,$file[1]);
  for($i=0;$i<count($templates)/2;$i++) $db->unbuffered_query("REPLACE INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templates[($i*2)])."','".addslashes($templates[($i*2+1)])."')");
 
  $db->unbuffered_query("INSERT INTO bb".$n."_styles (styleid,stylename,templatepackid,subvariablepackid,default_style) VALUES (NULL,'".addslashes($firstrow[0])."','$templatepackid','$subvariablepackid','1') ",1);
 
  header("Location: setup.php?step=7&mode=$mode");
  exit();
 }	
 else { ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td>Error: Style file could not be found. Please make sure that the file "bb1.style" is in the folder "acp". 
   <br><br><a href="setup.php?step=6&mode=<?php echo $mode; ?>">Try Again</a>
   </td>
  </tr>
 </table>
</body>
</html>	 	 	
 <?php	
 }	
}

if($step==7) {
 if($mode==2) {
  header("Location: update.php");	
  exit();
 }	

 $username=trim($_REQUEST['username']);
 $email=trim($_REQUEST['email']);
 $password=trim($_REQUEST['password']);

 if(isset($_POST['send'])) {
  if($username && $email && $password) {
   require("lib/config.inc.php");
   require("lib/class_db_mysql.php");
   $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);

   $db->query("INSERT INTO bb".$n."_users (userid,username,password,email,groupid,rankid,regdate,lastvisit,lastactivity,activation,timezoneoffset) VALUES (NULL,'".addslashes(htmlspecialchars($username))."','".md5($password)."','".addslashes(htmlspecialchars($email))."','1','1','".time()."','".time()."','".time()."','1','0')");
   $userid=$db->insert_id();
   $db->query("INSERT INTO bb".$n."_userfields (userid) VALUES ('$userid')");
   
   header("Location: setup.php?step=8&mode=$mode");
   exit();	
  }	
 }
 ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td><b>Register the Administrator</b></td>
  </tr>
  <tr>
   <td>
   <table><form method="post" action="setup.php">
    <tr>
     <td>Username:</td>
     <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
    </tr>
    <tr>
     <td>Password:</td>
     <td><input type="text" name="password" value="<?php echo $password; ?>"></td>
    </tr>
    <tr>
     <td>Email Address:</td>
     <td><input type="text" name="email" value="<?php echo $email; ?>"></td>
    </tr>
   </table>
   <p align="center"><input type="submit" value="Submit"> <input type="reset" value="Reset"></p>
   <input type="hidden" name="step" value="7">
   <input type="hidden" name="send" value="send">
   <input type="hidden" name="mode" value="<?php echo $mode; ?>">
   </form>
   </td>
  </tr>
 </table>
</body>
</html>	
<?php	
}

if($step==8) {
 require("lib/config.inc.php");
 require("lib/class_db_mysql.php");
 require ("lib/class_options.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);
 $db->query("UPDATE bb".$n."_options SET value='".time()."' WHERE varname='installdate'");
 $option=new options("lib");
 $option->write(); ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td><b>Installation successful!</b> IMPORTANT: Now delete setup.php for your board's security.
   <br><br><a href="index.php">Click to access the Admin Control Panel!</a>
   </td>
  </tr>
 </table>
</body>
</html>
<?php	
}

if($ustep=="1") {
 require("./lib/config.inc.php");
 require("./lib/class_db_mysql.php");
 require("./lib/class_query.php");
 require("./lib/class_options.php");
 $db = new db($sqlhost,$sqluser,$sqlpassword,$sqldb,$phpversion);
 
 @mysql_query("ALTER TABLE bb".$n."_threads DROP INDEX visible");
 @mysql_query("ALTER TABLE bb".$n."_threads ADD INDEX (visible,lastposttime,closed)");
 
 function readtemplate($template) {
  $file=implode("",file("../templates/".$template.".tpl"));
  return $file;
 }
 
 $templates=array("codephptag",
"login",
"offline",
"register");
 for($i=0;$i<count($templates);$i++) {
  $template=readtemplate($templates[$i]);
  $db->query("REPLACE INTO bb".$n."_templates (templatepackid,templatename,template) VALUES (0,'$templates[$i]','".addslashes($template)."')");	
 }
 
 $db->query("UPDATE bb".$n."_options SET value='2.0' WHERE varname='boardversion'");
 
 $option=new options("lib");
 $option->write();
 
 ?>
<html>
<head>
<link rel="stylesheet" href="css/other.css">
</head>
<body>
 <table align="center" width="400">
  <tr>
   <td align="center"><img src="images/acp-logo.gif"></td>
  </tr>
  <tr>
   <td><b>Update to wBB 2.0 Final was successful.</b> IMPORTANT: Now delete setup.php for your board's security.
   <br><br><a href="index.php">Click to access the Admin Control Panel!</a>
   </td>
  </tr>
 </table>
</body>
</html>
<?php
}
?>