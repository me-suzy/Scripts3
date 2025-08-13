<?php
session_start();
ob_start();
include("../includes/admin_header.php");
include("../includes/validate_admin.php");
include("../includes/messages.php");
// Code for entering values into database
if($Submit == "Submit")
{
	$first_name = str_replace("'","''",$first_name);
	$first_name = str_replace("","\\",$first_name);
	$last_name = str_replace("'","''",$last_name);
	$last_name = str_replace("","\\",$last_name);
	$email = str_replace("'","''",$email);
	$email = str_replace("","\\",$email);
	$username = str_replace("'","''",$username);
	$username = str_replace("","\\",$username);
	$password = str_replace("'","''",$password);
	$password = str_replace("","\\",$password);

	$st = "select * from StatMember where username = '$username'";
	$rs = mysql_query($st) or die(mysql_error());
	$numrows = mysql_num_rows($rs);
	if($numrows > 0)
	{
		$msg = $M_UsernamePresent;
	}
	else
	{
			$st="insert into StatMember(sponsorid,parentid,firstname,lastname,email,username,password,companyname,createdate,package_type,account_status)";
			$st="$st values('0','0','$first_name','$last_name','$email','$username','$password','',NOW(),'PlanA','P')";
			$rs = mysql_query($st)or die(mysql_error());
			$msg = $M_RegisterSuccess;

	}
}
// Code for entering values into database
?>
<script language="javascript">
function validate()
{
	with(document.frm)
	{
		if(first_name.value == "")
		{
			alert("Please enter first name");
			first_name.focus();
			return false;
		}
		if(last_name.value == "")
		{
			alert("Please enter last name");
			last_name.focus();
			return false;
		}
		if(email.value == "")
		{
			alert("Please enter email address");
			email.focus();
			return false;
		}
		if(IsValidEmail(email.value) == false)
		{
			alert("Please enter valid email address");
			email.focus();
			return false;
		}
		if(username.value == "")
		{
			alert("Please enter username");
			username.focus();
			return false;
		}
		if(password.value == "")
		{
			alert("Please enter pasword");
			password.focus();
			return false;
		}
	}
}
function IsBlank( str ) {
	var isValid = false;
 	if ( IsNull(str) || IsUndef(str) || (str+"" == "") )
 		isValid = true;
	return isValid;
}
function IsUndef( val ) {
	var isValid = false;
 	if (val+"" == "undefined")
 		isValid = true;
	return isValid;
}
function IsNull( val ) {
	var isValid = false;
 	if (val+"" == "null")
 		isValid = true;
	return isValid;
}
function IsAlpha( str ) {
	if (str+"" == "undefined" || str+"" == "null" || str+"" == "")
		return false;
	var isValid = true;
		str += "";
  	for (i = 0; i < str.length; i++) {
		if ( !( ((str.charAt(i) >= "a") && (str.charAt(i) <= "z")) ||
      			((str.charAt(i) >= "A") && (str.charAt(i) <= "Z")) ) ) {
         				isValid = false;
         				break;
      			}
   }
	return isValid;
}
function IsValidEmail( str )
{
	if (str+"" == "undefined" || str+"" == "null" || str+"" == "")
		return false;
	var isValid = true;	str += "";namestr = str.substring(0, str.indexOf("@"));
	domainstr = str.substring(str.indexOf("@")+1, str.length);
   	if (IsBlank(str) || (namestr.length == 0) ||(domainstr.indexOf(".") <= 0) ||(domainstr.indexOf("@") != -1) ||!IsAlpha(str.charAt(str.length-1)))
		{isValid = false;return isValid;}
}
</script>
<?php
	if($registration_status == "")
	{
?>
<div class="text">
<center><b>Registration Form</b></center><br>
<?php
	if($msg != "")
	{
		print "<center><span class=error>$msg</span></center><br>";
	}
?>
<form name="frm" method="post" action="register.php" onSubmit="return validate();">
<table width="400" border="0" cellspacing="1" cellpadding="1" align="center" bordercolor="#660000">
  <tr>
	<td width="150">
	  <div align="right">First Name</div>
	</td>
	<td width="250">
	  <input type="text" name="first_name" value="<?php print $first_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right">Last Name</div>
	</td>
	<td width="250">
	  <input type="text" name="last_name" value="<?php print $last_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right">Email</div>
	</td>
	<td width="250">
	  <input type="text" name="email" value="<?php print $email; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right">Username</div>
	</td>
	<td width="250">
	  <input type="text" name="username" value="<?php print $username; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right">Password</div>
	</td>
	<td width="250">
	  <input type="password" name="password">
	</td>
  </tr>
  <tr>
	<td colspan="2">
	  <div align="center">
		<input type="submit" name="Submit" value="Submit" class=button>
		<input type="reset" name="Reset" value="Reset" class=button>
	  </div>
	</td>
  </tr>
</table>
</form>
<?php
	}
include("../includes/admin_footer.php");
?>