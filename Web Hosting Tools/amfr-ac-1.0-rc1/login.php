<?php
include('config.php');
session_start();
header("Cache-control: private");
if($_POST['user'] == $ausername && $_POST['pass'] == $apassword) {
$_SESSION['amfradmin'] = $_POST['user'];
$_SESSION['amfrpassw'] = $_POST['pass'];
header('Location: admin.php');
}
else {
?>
<HTML>
<HEAD>
<TITLE>Please login</TITLE>
</HEAD>
<BODY BGCOLOR="white">
<CENTER>
<FORM ACTION="login.php" METHOD="post">
<H2>Please login:</H2><BR>
Username: <INPUT TYPE="text" NAME="user"><BR>
Password: <INPUT TYPE="password" NAME="pass"><BR>
<INPUT TYPE="submit" VALUE="Login!">
</FORM>
</CENTER>
</BODY>
</HTML>
<?php
}
?>