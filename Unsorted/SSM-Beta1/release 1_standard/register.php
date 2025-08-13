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
include("config.php");
//new user registration
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php print $title; ?> :: Register</title>
</head>
<body>
<?php
print '<FONT FACE="Verdana"><P ALIGN="CENTER"><b>';
if(!isset($add)){//check to see if add var is set
?>
<script language="JavaScript" type="text/javascript">
//check to make sure that email is valid
function validEmail(email){
	invalidChars=" /:,;";
	if(email==""){
		return false
	}
	for(i=0; i<invalidChars.length; i++){
		badChar = invalidChars.charAt(i);
		if(email.indexOf(badChar,0)>-1){
			return false
		}
	}//end for
	atPos=email.indexOf("@",1);
	if(atPos==-1){
		return false
	}
	if(email.indexOf("@",atPos+1)>-1){
		return false
	}
	periodPos=email.indexOf(".",atPos);
	if(periodPos==-1){
		return false
	}
	if(periodPos+3 > email.length){
		return false
	}
	return true
}//end validEmail

function submitIt(prefForm){
	if(!validEmail(prefForm.email.value)){
		alert("Invalid Email Address");
		prefForm.email.focus();
		prefForm.email.select();
		return false
	}
	else{
		if(prefForm.password.value!=prefForm.password2.value){
			alert("Entered Password Do Not Match!");
			prefForm.password.focus();
			prefForm.password.select();
			return false
		}
		else{
			LockButtons(prefForm);
			return true
		}
	}
}//end submitIt

function CancelConfirm(){
	var txt="Are you sure that you want to cancel this registration?\nClick OK to Reset the Form or CANCEL to continue with the registration process."
	if(!confirm(txt)){
		alert("Please continue to register...");
		return false;
	}
	else{
		return true;
	}
}//end function

//lock cancel button on submit
function LockButtons (whichform) {
	ua = new String(navigator.userAgent);
	if (ua.match(/IE/g)) {
		for (i=1; i<whichform.elements.length; i++) {
			if (whichform.elements[i].type == 'submit') {
				whichform.elements[i].disabled = true;
			}
			if (whichform.elements[i].type == 'reset') {
				whichform.elements[i].disabled = true;
			}
		}
	}
	whichform.submit();
}//end function
</script>
<style type="text/css">
<!--
<?php include("style.css"); ?>
.prefinput{
	color: #333333;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	border-color: #333333;
	text-indent: 2px; 
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px; 
	background: #f8f8f8;
}
-->
</style>
<font size="3">Register</u></font></b></p>
<form onSubmit="return submitIt(this)" action="<?php print $PHP_SELF; ?>" method="post" name="prefs">
<input type="hidden" name="add" value="true">
<input type="hidden" name="action" value="user">
<table width="70%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td colspan="2">Use the below form to create your profile.</td>
</tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7"><b>Your Profile</b></td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Login Information:&nbsp;</b></P></td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Username:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="username" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Password:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="password" name="password" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Confirm Password:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="password" name="password2" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Contact Information:</b>&nbsp;</P></td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Email Address:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="email" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Homepage:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="homepage" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Homepage Description:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="homepagedesc" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Instant Messaging:</b>&nbsp;</P></td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>ICQ:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="icq" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>AIM:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="aim" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Yahoo:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="yahoo" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>MSN:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="msn" size="25"></td>
</tr>
<tr>
    <td bgcolor="#efefef">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right><b>Other Information:</b>&nbsp;</P></td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Biography:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="note" size="20"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Occupation:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="occupation" size="20"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Location:&nbsp;</P></td>
    <td>&nbsp;<input class="prefinput" type="text" name="location" size="20"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Signature:&nbsp;</P></td>
    <td>&nbsp;<textarea cols="40" rows="7" name="sig" class="prefinput"></textarea></td>
</tr>
<tr>
    <td bgcolor="#efefef">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7">
      <P align=center><input type="submit" value="Register" class="but" name="submit.x">&nbsp;&nbsp;<input class="but" type="Reset" onClick="return CancelConfirm();" name="cancel.x"></P></td>
</tr>
</table></form>
<?php
}
elseif($add==true){
	//register user

	//validate input
	if(!isset($username)||!isset($password)){
		die("You must set a username & password!");
	}
	if(!isset($email)){
		die("You must include an email address!");
	}
	if($password!=$password2){
		die("Entered Password DO NOT match!");
	}
	$result=mysql_query("SELECT * FROM $user_table WHERE username = '$username';");
	$rows=mysql_numrows($result);
	if($rows!=0){
		die("Username is in use!");
	}
	
	//generate SQL
	$result="";
	if(!$result=mysql_query("INSERT INTO $user_table (`id`, `username`, `password`, `email`, `homepage`, `icq`, `aim`, `yahoo`, `location`, `note`, `showemail`, `usergroup`, `posts`, `joindate`, `timeoffset`, `timezone`, `avatar`, `custom`, `homepagedesc`, `occupation`, `msn`, `templategroup`, `vargroup`, `totalpm`, `lastpm`, `invisible`, `activated`, `banned`, `sig`, `showavatar`, `showsig`, `showhistory`, `addowntopics`, `autosubscribe`, `TICKETS`) VALUES ('', '".htmlspecialchars($username)."', '".md5($password2)."', '".strip_tags($email)."', '".strip_tags($homepage)."', '".strip_tags($icq)."', '".strip_tags($aim)."', '".htmlspecialchars($yahoo)."', '".strip_tags($location)."', '$note', '1', '1', '0', '".time()."', '0', '0', '', '', '".strip_tags($homepagedesc)."', '".strip_tags($occupation)."', '".strip_tags($msn)."', '0', '0', '0', '0', '1', '1', '1', '".strip_tags($sig)."', '1', '1', '1', '1', '1', '0');")){
		print "<p>Error in updating data!<br>";
		print mysql_error();
		print '<br><a href="';
		print $PHP_SELF;
		print '">Click Here to try again</a><br><br>';
		print "$sql</p>";
		$err=true;
	}//end error

	if($err!=true){
		?><br><br><div align="center">
		<STRONG><FONT face=Verdana size=2>You have been registered.</FONT></STRONG>
		<?php
		print '<br><br><a href="';
		print $web_path."index.php";
		print '">Click Here to Log In</a></div><br><br>';
	}//end no error
	else{
		print "Error in updating information.  Please try again.";
	}
}//end
?>
</body>
</html>
