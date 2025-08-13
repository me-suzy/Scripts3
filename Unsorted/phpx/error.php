<?php
#$Id: error.php,v 1.4 2003/07/08 18:26:43 ryan Exp $
require("admin/includes/config.inc.php");
require("admin/includes/var.inc.php");
$error = $_SERVER['argv'][0];
$error = str_replace("error=", '', $error);
if ($error == ''){ $error = $_SERVER['REDIRECT_STATUS']; }
?>


<head>
<style>
.main {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color: #000000; background-color: #ffffff; }
.mainbold {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color: #000000; background-color: #ffffff; font-weight: bold;}
</style>
<?php print "<title>Error $error</title>"; ?>
</head>
<body>
<table border=0 cellpadding=2 cellspacing=2 width=90% align=center>
<?php print "$errorHeader[$error] $errorMessage[$error] $errorFooter"; ?>
</table>
</body>
</html>














