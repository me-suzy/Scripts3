<?php
##################################
# Last modified 24/12/2003
##################################
  include("defines.php");
  include("smalltemplater.php");


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

<form action="index.php?login" method=post>
<table border=0 cellspacing=0 cellpadding=4>

<tr>
<td><b>Login</b>:</td>
<td><input type=text name=login class=txt value=""></td>
</tr>


<tr>
<td><b>Password</b>:</td>
<td><input type=password name=pwd class=txt value=""></td>
</tr>
<tr>
<td colspan=2 align=right><input type=image border=0 src="images/button-send.gif"></td>
</table>
</form>
</center>
</body></html>
EOT;

if($HTTP_SERVER_VARS['QUERY_STRING'] == 'login'){
 if(isset($HTTP_POST_VARS['pwd']) && $HTTP_POST_VARS['pwd'] == PASSWORD && isset($HTTP_POST_VARS['login'])
            && $HTTP_POST_VARS['login'] == USER){
  $session_id = md5(PASSWORD.'root');
  session_start();
  $pass = PASSWORD;
  $_SESSION['pass'] = $pass;
  $_SESSION['uid'] = 0;
  header("Location: reports.php?pid=roireport");
  exit(1);
 }else{

$pwd = mysql_escape_string($HTTP_POST_VARS['pwd']);
$login = mysql_escape_string($HTTP_POST_VARS['login']);

   $res = @mysql_connect(DBHOST,DBUSER,DBPASS);
   $res = @mysql_selectdb(DBNAME);

   $res = @mysql_query("SELECT * FROM ROIusers WHERE name = '$login';");
   if ($res) { $a_r = mysql_fetch_array($res); }

   $true_pwd = $a_r['pw'];


   if ((!(empty($true_pwd))) && ($true_pwd == $pwd) )
    {
	  $session_id = md5(PASSWORD.$login);
	  session_start();
	  $pass = PASSWORD;
	  $_SESSION['pass'] = $pass;
	  $_SESSION['uid'] = $a_r['id'];
	  header("Location: reports.php?pid=roireport");
	  exit(1);
    }
   else
    {
   echo str_replace('%error%','<br><b><font color=#A80000>Wrong login or password</font></b>',$login_page);
    }
 }
}else if($HTTP_SERVER_VARS['QUERY_STRING'] == 'logout'){
   session_start();
   session_unset();
   $_SESSION = array();
   session_destroy();
   header("Location: index.php");
   exit(1);
}else  echo str_replace('%error%','',$login_page);

?>
