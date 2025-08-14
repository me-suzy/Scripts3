<html>
<head>
<title><?php require 'scriptinfo.php'; echo $fullscripttitle; ?> Setup</title>
<link rel="stylesheet" href="templates/styles/default.css" type="text/css">
</head>
<body>
<?php
// make compatable when register_globals is off
while(list($key, $value) = each($HTTP_GET_VARS)) 
{
 $$key = $value;
} 
while(list($key, $value) = each($HTTP_POST_VARS)) 
{
 $$key = $value;
} 

if ($admindir == '') $admindir = 'admin';

require 'classes/database.php';
require $admindir .'/adminfunctions.php';
require $admindir .'/admincommonfuncs.php';
require 'functions.php';
require 'commonfuncs.php';

if ($agree=='yes')
{
 if ($action=='create')
 {
   $connection = mysql_connect($thedbserver, $thedbuser, $thedbpassword);
$connectionerror = mysql_error();
echo $connectionerror;
   mysql_select_db($thedbname);
if ($connectionerror == '') { $connectionerror = mysql_error(); echo $connectionerror; }
   $userpassword = md5($userpassword);

  $db = new database($connection);
  
  $sql = fileread('setup.sql');
  $sql = str_replace('{PASSWORD}', $userpassword, $sql);
  $sql = str_replace('{USERNAME}', $username, $sql);
  $sql = str_replace('{PREFIX}', $prefix, $sql);
  $sql = str_replace('{TIME}', time(), $sql);
  $sql = str_replace('{IP}', $HTTP_SERVER_VARS['REMOTE_ADDR'], $sql);
  if ($admindir == '') $admindir = 'admin';
  $sql = str_replace('{OURADMINDIR}', $admindir, $sql);

  if ($multilingual == 'multi')
  { 
   if ($defaultlang == '') $defaultlang = 'English';
   if (is_array($languages)) $languages = implode(',', $languages);
   $languages = str_replace('selected', '', $languages);	
   if (!strstr($languages, $defaultlang)) $languages .= ','. $defaultlang;
   $sql = str_replace('{OURLANGUAGES}', $languages, $sql);  
   $sql = str_replace('{OURDEFAULTLANG}', $defaultlang, $sql);  
   $sql = str_replace('{OURTEMPLATESDIR}', 'templates/multilingual', $sql);    
   $sql = str_replace('{OURTEMPLATES}', 'templates/multilingual', $sql);    
  }
  else
  {
   $sql = str_replace('{OURLANGUAGES}', 'default', $sql);  
   $sql = str_replace('{OURDEFAULTLANG}', 'default', $sql);     
   $sql = str_replace('{OURTEMPLATESDIR}', 'templates/default', $sql);  
   $sql = str_replace('{OURTEMPLATES}', 'templates/default', $sql);      
  }

  $docreation = processsql($sql);

  $languagetable = $prefix. 'language';  
  if ($multilingual == 'multi')
  {
   if (strstr($languages, 'English')) languageupload("languages/setup/fullenglish.lng", 'English');
   if (strstr($languages, 'Deutsch')) languageupload("languages/setup/deutsch.lng", 'Deutsch');
   if (strstr($languages, 'Portugues')) languageupload("languages/setup/portugues.lng", 'Portugues');
   if (strstr($languages, 'Nederlands')) languageupload("languages/setup/nederlands.lng", 'Nederlands');   
   if (strstr($languages, 'Russian')) languageupload("languages/setup/russian.lng", 'Russian');       
   if (strstr($languages, 'Polish')) languageupload("languages/setup/polish.lng", 'Polish');       
   if (strstr($languages, 'French')) languageupload("languages/setup/french.lng", 'French');       
  }
  else
  {
   if ($scriptname == 'wsnmanual') languageupload("languages/setup/default.lng", 'default');
   else languageupload("languages/setup/englishonly.lng", 'default');
  }  
  
   if ($connectionerror == '')
   {
    if (!file_exists("config.php")) rename("config.php.txt", "config.php");
    @chmod("config.php", 0666);
    @chmod("attachments", 0777);

   $configdata = "<";
   $configdata .= "?php
   $";
   $configdata .= "categoriestable = '$prefix"."categories';
   $";
   $configdata .= "linkstable = '$prefix"."links'; 
   $";
   $configdata .= "metatable = '$prefix"."settings';
   $";
   $configdata .= "commentstable = '$prefix"."comments';
   $";   
   $configdata .= "languagetable = '$prefix"."language';
   $";   
   $configdata .= "memberstable = '$prefix"."members';
   $";   
   $configdata .= "membergroupstable = '$prefix"."membergroups';
   $";
   $configdata .= "databasename = '$thedbname';
   $";
   $configdata .= "prefix = '$prefix';
   $";     
   $configdata .= "connection = mysql_connect('$thedbserver', '$thedbuser', '$thedbpassword');
   mysql_select_db('$thedbname'); ?";
   $configdata .=">";

   // write config file
   $filename = 'config.php';
   $fd = fopen ($filename, 'w'); 
   $testit = fwrite ($fd, "$configdata");
   fclose ($fd);
   if ($testit)
   {
    // test chmod
    $testchmod = @chmod("readme.txt", 0666);
    if ($testchmod)
     echo "<p>Now CHMODing your templates for you.</p>";
    else
     echo "<p>Unable to CHMOD your templates for you... if you wish to use the online template editor, you will have to CHMOD each .tpl template file to 666 manually. If you wish to use file attachments or avatars, you will have to CHMOD your attachments directory to 777.</p>";      
    chmodtemplates();
    echo "<p>Sucessfully installed! You may now <a href=index.php>view $fullscripttitle</a> or <a href=adminlogin.php>login to the administration panel</a>. You should be sure to visit the 'manage settings' page in the admin panel to configure $fullscripttitle to your preferences. Instructions are in <a href=readme.txt>readme.txt</a>, and if you need further assistance with anything please visit the <a href=http://forums.webmastersite.net/>$fullscripttitle forum</a>.</p>";
   }
  else
  {
   die("Your config.php file is not writeable. Please chmod 666 or 777 the file config.php and try again.");
  }
 }
 else
 {
   echo "<p>Failed! Please check the database info you supplied and try again.</p>";
 }
   // close the database connection
   $db->closedb($connection);
  }

 else
 {
  $thisurl = 'http://' . $HTTP_SERVER_VARS['SERVER_NAME'] . $HTTP_SERVER_VARS['PHP_SELF'];
  $setupform = "
<p>Please fill in the requested database info and options below, so that setup can be completed:</p>
  <form name=setup action=setup.php?agree=yes&action=create method=POST>
  <table>
  <tr><td width=20%><b>Database server:</b><br>Usually localhost. </td><td width=80%><input type=text name=thedbserver size=20 value='localhost'></td></tr>
  <tr><td width=20%><b>Database name:</b><br>If you don't have one set up,<br>do so with your host's tools.</td><td width=80%><input type=text name=thedbname size=20></td></tr>
  <tr><td width=20%><b>Database username:</b><br>Must be user with access to above DB. </td><td width=80%><input type=text name=thedbuser size=20></td></tr>
  <tr><td width=20%><b>Database Password:</b><br>For the above DB username.</td><td width=80%><input type=text name=thedbpassword size=20></td></tr>
  <tr><td width=20%>&nbsp;</td><td width=80%>&nbsp;</td></tr>
  <tr><td width=20%><b>$fullscripttitle Username:</b> <br>Create your administrative login name for $fullscripttitle.</b> </td><td width=80%><input type=text name=username size=20></td></tr>
  <tr><td width=20%><b>$fullscripttitle Password:</b><br>Password will go with the name above. </td><td width=80%><input type=text name=userpassword size=20></td></tr>
  <tr><td width=20%>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><td width=20%><b>Choose database tables prefix:</b> </td><td width=80%><input type=text name=prefix size=20 value='". $scriptname ."_'></td></tr>
  <tr><td colspan=2>Leave as is if you only have one copy of $fullscripttitle in this database. <br>If you install multiple copies in one database, use unique prefixes.</td></tr>  
  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><td width=20%><b>Admin directory name:</b> </td><td><input type=text name=admindir size=20 value='admin'></td></tr>
  <tr><td colspan=2>Leave this as is unless you have a conflict with your website control panel. If you change it, you must also of course rename the actual /admin/ directory which you have uploaded.</td></tr>";
if ($scriptname != 'wsnlinks') $setupform .= "<!-- ";
$setupform .= "
  <tr><td width=20% valign=top><br><b>Multilingual or English-only:</b><br>The two right columns only apply if multilingual is selected. </td><td>
 <table width=100%><tr><td width=33% valign=top>
 <input type=radio name=multilingual value=english checked>English-only installation <br>
<input type=radio name=multilingual value=multi>Multilingual installation<br>
</td>
<td width=33% valign=top>
Choose languages to install: 
<br>(Press ctrl to select multiple.) <br>
<select multiple name='languages[]'>
 <option value='English'>English</option>
 <option value='Deutsch'>German (informal)</option>
 <option value='Portugues'>Portugese (Brazilian)</option>
 <option value='Nederlands'>Dutch</option>
 <option value='Russian'>Russian</option>
 <option value='Polish'>Polish</option>
 <option value='French'>French</option>
</select>
</td>
<td width=33% valign=top>
Choose a default language:<br>
<select name=defaultlang>
 <option value='English'>English</option>
 <option value='Deutsch'>German (informal)</option>
 <option value='Portugues'>Portugese (Brazilian)</option>
 <option value='Nederlands'>Dutch</option>
 <option value='Russian'>Russian</option>
 <option value='Polish'>Polish</option>
 <option value='French'>French</option>
</select>
</td>
</tr></table>
 
</td></tr>
  <tr><td colspan=2>(If you choose English-only the templates will be more easily readable, but not so easily translatable, and the script will run slightly faster. You must choose a multilingual install in order to be able to create a new language pack if yours isn't available, or to be able to install one that is available.)</td></tr>  
  <tr><td width=20%>&nbsp;</td><td width=80%>&nbsp;</td></tr>
";
if ($scriptname != 'wsnlinks') $setupform .= "--> ";
$setupform .= "
  <tr><td colspan=2><input type=submit value='Install ". $fullscripttitle ."'></td></td>
  </table>
  </form>";
  echo $setupform;
 }
}
else
{

$agreement = '<b>Warning:</b> This setup process will overwrite any previous '. $fullscripttitle .' data. Only use this for a new install. See the <a href=readme.txt>readme file</a> for upgrade instructions if you need to upgrade, only continue here if you wish to create a new installation.
<br><br><p align=center>
<textarea readonly rows=20 cols=75>';
$agreement .= fileread("license.txt");
$agreement .= '</textarea></p>
<p align=center>
<form name=agreeform action=setup.php?agree=yes method=POST>
<center><input type=submit value="I Agree"></center>
</form></p>
';
echo $agreement;
}

echo mysql_error();

?>
</body>
</html>