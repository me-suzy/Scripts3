<?
ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
include "phpAuth_config.php";
session_start();
session_destroy();
header('Location:'.$logout_redirect.'');
?>
</body>
</html>
<?
ob_end_flush();
?>