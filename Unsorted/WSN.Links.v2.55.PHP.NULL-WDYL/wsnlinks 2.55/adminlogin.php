<?php
require 'start.php';
if (($filled) && ($thismember->login()))
{ 
 if ($settings->dirurl != '') $path = $settings->dirurl . '/'. $admindir .'/index.php';
 else $path = "http://". $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .'/'. $admindir .'/index.php';
 header("Location: $path"); 
 die('<meta http-equiv="refresh" content="0;url='. $path .'">'); 
}
else
{
 if (!$template) $template = new template("$templatesdir/admin/password.tpl");
}
require 'end.php'
?>