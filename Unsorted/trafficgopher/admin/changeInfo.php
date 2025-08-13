<?php
session_start();
ob_start();
include("../includes/admin_header.php");
include("../includes/validate_admin.php");
include("../includes/messages.php");
if($Submit == "Submit")
{
	$st = "update StatAdmin set email = '$email', password = '$password'";
	$rs = mysql_query($st) or die(mysql_error());
	$msg = $M_InfoUpdated;
}
$st = "select * from StatAdmin";
$rs = mysql_query($st) or die(mysql_error());
$row = mysql_fetch_array($rs);
$email = $row['email'];

?>
<script language="javascript">
function validate()
{
	with(document.frm)
	{
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
<form name=frm method="post" action="changeInfo.php" onSubmit="return validate();">
<table width="300" border="0" cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td colspan="2">
	  <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="2">Change Admin Info</font></b></div>
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
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Email Address:</font></div>
	</td>
	<td width="180">
	  <input type="text" name="email" value="<?php print $email; ?>"><font face="Arial, Helvetica, sans-serif" size="1">
        <br>(this is also the paypal address)</font>
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Password:</font></div>
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