<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
require("config.php");
/*
Check the login information based on what is pulled from the database:
use sessions to store the login information, pass the session from area
to area within the support center
*/

if(!isset($system)&&!isset($inlog)){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php print $title; ?></title>
<style>
input{color: #333333;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 11px;font-weight: normal;border-color: #333333;text-indent: 2px; 
<?php
if(substr($HTTP_USER_AGENT,"IE")){
?>border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; 
<?php
}
?>}
input.button {background-color: #F8F8F8;color: #333333;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 11px;font-weight : bold;
<?php
if(substr($HTTP_USER_AGENT,"IE")){
?>border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; 
<?php
}
?>}
</style>
</head>
<body>
<P align=center><FONT face=Verdana><STRONG><U><?php print $title; ?></U></STRONG></FONT></P>
<center>
<?php
	  //logout message
	  if($message=="logout"){
?>
	  <font class="update" color="#0033cc"><b><em>You have been logged out.</em><br>Please log in...</b><br></font>
<?php
	  }
	  //error message
	  elseif($message=="error"){
?>
		  <font class="update" color="#cc0000"><b><em>The user name and/or password you entered is invalid.</em></b></font>	
		  <font class="update" color="#0033cc"><br><b>Please log in again...</b></font><br>  
<?php
	  }
	  //no message
	  else{
?>
	  <font class="update" color="#0033cc"><b>Please log in...</b><br></font>
<?php
	  }
?>
</center><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="48%" valign="top">
<div align="center">
<form action="<?php print $PHP_SELF; ?>" method="post">
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#455D79">
 <tr> 
  <td bgcolor="#234D76"><b><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="2" color="#FFFFFF">Login</font></b></td>
 </tr>
 <tr> 
  <td bgcolor="#e1e1e1"> 
   <table width="100%" border="0" cellspacing="2">
    <tr> 
     <td width="50%" height="18"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="2"><b>Username:</b></font></td>
     <td width="50%" height="18"><input type="text" name="username1" size="20" maxlength="50"></td>
    </tr>
    <tr> 
     <td width="50%"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="2"><b>Password:</b></font></td>
     <td width="50%"><input type="password" name="password2" size="20" maxlength="50"></td>
    </tr>
   </table>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
     <td width="50%"><input class="button" type="submit" name="Enter" value="Login"></td>
     <?php
		 //FORGOT PASSWORD LINK
	 	if($reg_system=="1"){
		?>
     		<td width="50%" valign="bottom" align="left"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="1" color="#252525">&raquo;<a href="<?php print $openbb_webpath; ?>member.php?action=forgotpasswd">Forgot my password...</a></font></td>
		<?php
		}
		else{
		?>
			<td width="50%" valign="bottom" align="right"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="1" color="#252525">&nbsp;</font></td>
		<?php
		}//end else
		
		//REGISTER LINK
	 	if($reg_system=="1"){
		?>
     		<td width="50%" valign="bottom" align="right"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="1" color="#252525">&raquo;<a href="<?php print $openbb_webpath; ?>member.php?action=register">Register</a></font></td>
		<?php
		}
		else{
		?>
			<td width="50%" valign="bottom" align="right"><font face="Verdana,Geneva,Arial,Helvetica,sans-serif" size="1" color="#252525">&raquo;<a href="register.php">Register</a></font></td>
		<?php
		}//end else
	?>
    </tr>
   </table>
  </td>
 </tr>
</table>
<input type="hidden" name="inlog" value="true">
<input type="hidden" name="system" value="enter">
</form></div></td>
</tr>
</table></div><br>
    </td>
  </tr>
</table>
<br>
<?php
if($sys_req=="on"){
?>
<table align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td>
      <P><b>Please make sure that your browser meets the client side system 
      requirements:</b></P>
      <LI>JavaScript Support</LI>
      <LI>Cookie Support</LI>
      <LI>Internet Explorer 4 / Netscape 4 or above</LI></td>
</tr>
</table>
<?php
}
else{
?>
<div align="center"><a href="<?php print $PHP_SELF; ?>?sys_req=on">Client Side System Requirements</a></div>
<?php
}
?>
<div align="center"><a href="<?php print $admin_web_path; ?>">Administrators</a></div>
</body>
</html>
<?php
}
else{
	if($system=="enter"){
/*
The next seection of code authenticates the user against the login 
information pulled from the database and then, if the user has provided 
the correct login information, the information is stored to a session 
to faciliate its transfer through the application.
*/
//run check
	$sql="SELECT * FROM $user_table WHERE username='$username1';";
	$result=mysql_query($sql);
	$num_rows=mysql_num_rows($result);
	if($num_rows==0){
		header("location: index.php?message=error&pos=a");
		die();
	}
	elseif($num_rows>1){
		header("location: index.php?message=error&pos=b");
		die();
	}
	else{
	//login found
		while($value=mysql_fetch_array($result)){
			$setting["username"]=$value["username"];
			$setting["password"]=$value["password"];
		}
		$password2=md5($password2);
	//run check
	if($username1==$setting["username"]&&$password2==$setting["password"]){
		//set the session path
		session_save_path($setting["session_path"]);
		//start the session
		session_start();
		//encrypt the login information
		$user_user=$username1;
		$pass_user=$password2;
		//store the information
		session_register("user_user");
		session_register("pass_user");
		//now display the wait page:
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php print $title; ?> | Logging In...</title>
	<meta http-equiv="REFRESH" content="1; url=system.php">
</head>
<body>
<P align=center><STRONG>Logging In...</STRONG></P>
<P align=center><a href="system.php">Click here if your browser does not automatically forward 
you.</a></P>
</body>
</html>
<?php
		}//end authentication check
		else{
			header("location: index.php?message=error&pos=c");
			die();
		}
	}//end username/password correct
}//end system=enter
	elseif($system=="load"){
		//run the main administration application
		header("location: system.php");
	}//end system=load
	elseif($system=="logout"){
		//load session
		session_save_path($setting["session_path"]);
		session_start();
		//clear variables from the session
		session_unset();
		//kill the session
		session_destroy();
		?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php print $title; ?> | Logging Out...</title>
	<meta http-equiv="REFRESH" content="1; url=index.php?message=logout">
</head>
<body>
<P align=center><STRONG>Logging Out...</STRONG></P>
<P align=center><a href="index.php?message=logout">Click here if your browser does not automatically forward 
you.</a></P>
</body>
</html>	
		<?php
	}//end system=logout
}//end else
?>
