<?php
include("../settings.php");
ereg ("(^.*/)[^/]+/[^/]+$", $PHP_SELF, $regs);
$base_uri = $regs[1];

$password=$pswd;
if ($login) $admin_login=$login;
if ($password) $admin_password="";

if ((($password==$website["password"])||($admin_password==$website["password"]))&&(!$logout))
{  									                    	     setcookie("admin_password",$website["password"],0,$base_uri);
setcookie("admin_login",$admin_login,0,$base_uri);
}else 
{
setcookie("admin_password","",0,$base_uri);
setcookie("admin_login","",0,$base_uri);
header("Location: admin_login.php");
exit;
};

?>