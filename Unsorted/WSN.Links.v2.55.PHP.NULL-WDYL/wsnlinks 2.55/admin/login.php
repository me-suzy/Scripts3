<?
while(list($key, $value) = each($HTTP_GET_VARS)) 
{
 $$key = $value;
} 
while(list($key, $value) = each($HTTP_POST_VARS)) 
{
 $$key = $value;
} 
require '../functions.php';
require '../config.php';
require '../classes/database.php';
require '../classes/settings.php';
require '../classes/category.php';
require '../classes/onelink.php';
require '../classes/template.php';
require '../classes/language.php';
require '../classes/member.php';
require 'adminfunctions.php';

// variables that we'll use globally \\
$db = new database;
$settings = new settingsdata;
$language = new language($settings->defaultlang);
$thismember = new member('dummy', 'dummy');

$templatesdir = $settings->templatesdir;
$linkfields = $settings->linkfields;
$categoryfields = $settings->categoryfields;

if (!$templatesdir) echo "<p><font size=6><b>There seems to be a problem with your database. Most likely, you've forgotten to run the <a href=setup.php>setup</a> or <a href=upgrade.php>upgrade</a> scripts.</b></font></p>";

$header = fileread("../$templatesdir/header.tpl");
$header = str_replace('templates/style.css', '../templates/style.css', $header);
if (!$template) $template = new template("../$templatesdir/blank.tpl");

if ($action == 'logout')
{
 setcookie ('wsnuser', "$username", (time() - 6000000));
 setcookie ('wsnpass', "$password", (time() - 6000000));
 $template->text = "You have been logged out. Return to <a href=../index.php>your directory</a>.";
}
if ($HTTP_POST_VARS[userpassword] != '')
{
 $password = md5($userpassword);
 $query = $db->select('id', 'memberstable', "name='$username'", '', '');
 $id = $db->rowitem($query);
 setcookie ('wsnuser', "$id", (time() + 6000000));
 setcookie ('wsnpass', "$password", (time() + 6000000));  
 $template->text = "<p>Password has been stored in cookie. You are now logged in. Please note: In order to log out, you'll need to return to this page instead of using the regular logout contained in the admin panel.</p><br><p>Please try your admin pages again now: <a href=index.php>Main admin panel</a>.<p>";
}

if (($HTTP_COOKIE_VARS['wsnuser'] != '') && (action != 'logout'))
{
 $template->text = "You're logged in already. If you wish to log out, click <a href=login.php?action=logout><b>here</b></a>.";
}
else
{
$template->text .= "<p>&nbsp;</p><p>This is an alternate login, for use only when regular login fails (which seems to happen in some server configurations). It will allow you access to your admin panel, although it won't allow browsing the directory logged in as the regular login does.</p></p>Type your password in the box below:<br><form action=login.php method=post>
<input type=text name=username value=username> <br>
<input type=text name=userpassword value=password>
<input type=submit value=Login>
</form></p>";
}
$speciallogin = true;
require 'adminend.php';
?>