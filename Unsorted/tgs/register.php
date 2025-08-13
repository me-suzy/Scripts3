<?php
session_start();
ob_start();
include("includes/header.php");
include("includes/common.php");
include("includes/register_header.php");
include("includes/messages.php");
if(!session_is_registered("session_first_name"))
{
	session_register("session_first_name");
}
if(!session_is_registered("session_last_name"))
{
	session_register("session_last_name");
}
if(!session_is_registered("session_email"))
{
	session_register("session_email");
}
if(!session_is_registered("session_username"))
{
	session_register("session_username");
}
if(!session_is_registered("session_password"))
{
	session_register("session_password");
}
if(!session_is_registered("session_packageType"))
{
	session_register("session_packageType");
}
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

		$session_first_name = $first_name;
		$session_last_name = $last_name;
		$session_email = $email;
		$session_username = $username;
		$session_password = $password;
		$session_packageType = $package_type;
		$registration_status = "registered";
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
<center><b>Register for <?php print $sitename; ?> Services</b></center><br>
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
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">First Name:</div>
	</td>
	<td width="250">
	  <input type="text" name="first_name" value="<?php print $first_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Last Name:</div>
	</td>
	<td width="250">
	  <input type="text" name="last_name" value="<?php print $last_name; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Email Address:</div>
	</td>
	<td width="250">
	  <input type="text" name="email" value="<?php print $email; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Username:</div>
	</td>
	<td width="250">
	  <input type="text" name="username" value="<?php print $username; ?>">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Password:</div>
	</td>
	<td width="250">
	  <input type="password" name="password">
	</td>
  </tr>
  <tr>
	<td width="150">
	  <div align="right"><font face="Arial, Helvetica, sans-serif" size="2">Package Type:</div>
	</td>
	<td width="250">
	  <input type=radio name=package_type value="planA" checked><font face="Arial, Helvetica, sans-serif" size="2">
          Plan A &nbsp;&nbsp;<input type=radio name=package_type value="planB"><font face=arial size=2>Plan B
	</td>
  </tr>
  <tr>
	<td colspan="2">
	  <div align="center">
		<input type="submit" name="Submit" value="Submit" class=button>
		<input type="reset" name="Reset" value="Reset" class=button>
          </div><br>
      <center><b><font size="2">What is the Difference between Plan A and Plan B?</b></font></center>
      <font size="1"><div align="justify">
      Plan A is Priced Lower becuase a Link MUST be Placed somewhere on your websites home page
      while with Plan B a link is not required. The price for Plan A is $<? echo $planAcharge; ?> per Month, and 
      the Price for Plan B is $<? echo $planBcharge; ?> per month. We check all of our Plan A members websites 
      weekly to make sure they are linking back to us, if we find no link their account will
      be deleted without notice.</form>
      </font>
    </td>
  </tr>

<?php
	}
	else
	{
		$st = "select * from StatAdmin";
		$rs = mysql_query($st) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		$adminEmail = $row['email'];

		$st = "select * from StatAdmin";
		$rs = mysql_query($st) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		$adminEmail = $row['email'];
		$st = "select * from StatPayPalCharges";
		$rs = mysql_query($st) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		if($package_type == "planA")
		{
			$plan = $row['planA'];
		}
		else
		{
			$plan = $row['planB'];
		}

		print "<br><center>$M_RedirectPayPal<br><br>"
?>
	<form name=paypalfrm action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_xclick-subscriptions">
	<input type="hidden" name="business" value="<?php print $adminEmail; ?>">
	<input type="hidden" name="item_name" value="<?php print $sitename; ?> Statistics Subscription for: <?PHP echo $username?>">
	<input type="hidden" name="item_number" value="ECAM">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="return" value="<?php print $paypal_successurl; ?>">
	<input type="hidden" name="cancel_return" value="<?php print $paypal_cancelurl; ?>">
	<input type="hidden" name="no_note" value="1">
	<input type="hidden" name="a3" value="<?php print $plan; ?>">
	<input type="hidden" name="p3" value="1">
	<input type="hidden" name="t3" value="M">
	<input type="hidden" name="src" value="1">
	<input type="hidden" name="sra" value="1">
	</form>
	<script language="javascript">
	document.paypalfrm.submit();
	</script>
<?php
	}
include("includes/register_footer.php");
include("includes/footer.php");
?>