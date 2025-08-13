<?php
session_start();
ob_start();
include("includes/config.php");
include("includes/header.php");
include("includes/validate_member.php");
include("includes/messages.php");
if($Submit == "Submit")
{
	if($act == "edit")
	{
		$st = "update StatSite set sitename = '$site_name' where id = $site";
		$rs = mysql_query($st) or die(mysql_error());
		$msg = $M_InfoUpdated;

	}
	else
	{
		$st = "insert into StatSite values ('',$sessionSiteId,'$site_name')";
		$rs = mysql_query($st) or die(mysql_error());
		$msg = $M_InfoAdded;
	}
}
if($act == "edit")
{
	$st = "select * from StatSite where id = $site";
	$rs = mysql_query($st) or die(mysql_error());
	$row = mysql_fetch_array($rs);
	$site_name = $row['sitename'];
}
?>
<script language="javascript">
function validate()
{
	with(document.frm)
	{
		if(site_name.value == "")
		{
			alert("Please enter site name");
			site_name.focus();
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
<form name=frm method="post" action="changeSiteInfo.php" onSubmit="return validate();">
<input type="hidden" name="act" value="<?php print $act; ?>">
<input type="hidden" name="site" value="<?php print $site; ?>">
<table width="400" border="0" cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td colspan="2">
	  <div align="center"><b>Site</b></div>
	</td>
  </tr>
  <?php
  if($msg != "")
  {
	print "<tr><td colspan='2' align=center><span class=error>$msg</span></td></tr>";
  }
  ?>
  <tr>
	<td width="120" valign="top">
	  <div align="right">Site Name</div>
	</td>
	<td width="280">
	  <input type="text" size="25" name="site_name" value="<?php print $site_name; ?>">
	  <br>(eg. 'http://www.hotmail.com')
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
	include("includes/footer.php");
?>