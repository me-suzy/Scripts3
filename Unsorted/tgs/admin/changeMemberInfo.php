<?php
session_start();
ob_start();
include("../includes/validate_admin.php");
include("../includes/config.php");
include("../includes/admin_header.php");
include("../includes/messages.php");
if($Submit == "Submit")
{
	$st = "update StatMember set firstname = '$first_name', lastname = '$last_name', email = '$email', password = '$password' where userid = $member";
	$rs = mysql_query($st) or die(mysql_error());
	$msg = $M_InfoUpdated;
}
$st = "select * from StatMember where userid = $member";
$rs = mysql_query($st) or die(mysql_error());
$row = mysql_fetch_array($rs);
$first_name = $row['firstname'];
$last_name = $row['lastname'];
$email = $row['email'];
$username = $row['username'];

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
			alert("Invalid email address");
			email.focus();
			return false;
		}
		if(password.value == "")
		{
			alert("Please enter password");
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
function IsValidEmail( str ) {
	if (str+"" == "undefined" || str+"" == "null" || str+"" == "")
		return false;
	var isValid = true;	str += "";namestr = str.substring(0, str.indexOf("@"));
	domainstr = str.substring(str.indexOf("@")+1, str.length);
   	if (IsBlank(str) || (namestr.length == 0) ||(domainstr.indexOf(".") <= 0) ||(domainstr.indexOf("@") != -1) ||!IsAlpha(str.charAt(str.length-1)))
		{isValid = false;return isValid;}
}
</script>
<form name=frm method="post" action="changeMemberInfo.php" onSubmit="return validate();">
<input type="hidden" name="member" value="<?php print $member; ?>">
<table width="300" border="0" cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td colspan="2">
	  <div align="center"><b>Change Info</b></div>
	</td>
  </tr>
  <?php
  if($msg != "")
  {
	print "<tr><td colspan='2' align=center><span class=error>$msg</span></td></tr>";
  }
  ?>
  <tr>
	<td width="120">
	  <div align="right">First Name</div>
	</td>
	<td width="180">
	  <input type="text" name="first_name" value="<?php print $first_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right">Last Name</div>
	</td>
	<td width="180">
	  <input type="text" name="last_name" value="<?php print $last_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right">Email</div>
	</td>
	<td width="180">
	  <input type="text" name="email" value="<?php print $email; ?>">
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right">User Name</div>
	</td>
	<td width="180">
	  <input type="text" name="username" value="<?php print $username; ?>" onFocus="javascript:blur();">
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right">Password</div>
	</td>
	<td width="180">
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
	include("../includes/admin_footer.php");
?>