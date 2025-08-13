<?php
session_start();
ob_start();
include("../includes/validate_admin.php");
include("../includes/config.php");
include("../includes/admin_header.php");
include("../includes/messages.php");
if($Submit == "Submit")
{
	$st = "select * from StatPayPalCharges";
	$rs = mysql_query($st) or die(mysql_error());
	if(mysql_num_rows($rs) > 0)
	{
		$st = "update StatPayPalCharges set planA = $plana, planB = $planb";
		$rs = mysql_query($st) or die(mysql_error());
		$msg = $M_PaypalChargesUpdated;
	}
	else
	{
		$st = "Insert into StatPayPalCharges values($plana,$planb,now())";
		$rs = mysql_query($st) or die(mysql_error());
		$msg = $M_PaypalChargesInserted;
	}
}
$st = "select * from StatPayPalCharges";
$rs = mysql_query($st) or die(mysql_error());
$row = mysql_fetch_array($rs);
$plana = $row['planA'];
$planb = $row['planB'];
?>
<script language="javascript">
function validate()
{
	with(document.frm)
	{
		if(plana.value == "")
		{
			alert("Please enter Plan A charges");
			plana.focus();
			return false;
		}
		if(isNaN(plana.value) == true)
		{
			alert("Please enter numeric value");
			plana.focus();
			return false;
		}
		if(planb.value == "")
		{
			alert("Please enter Plan B charges");
			planb.focus();
			return false;
		}
		if(isNaN(planb.value) == true)
		{
			alert("Please enter numeric value");
			planb.focus();
			return false;
		}
	}
}
</script>
<form name=frm method="post" action="paypal.php" onSubmit="return validate();">
<table width="300" border="0"  cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td colspan="2">
	  <div align="center"><b>Paypal Subscription Fee</b></div>
	</td>
  </tr>
  <?php
  if($msg != "")
  {
	print "<tr><td colspan='2' align=center><span class=error>$msg</span></td></tr>";
  }
  else
  {
  ?>
  <tr>
	<td colspan="2">
	  &nbsp;
	</td>
  </tr>
  <?php
  }
  ?>
  <tr>
	<td width="120">
	  <div align="right">Plan A</div>
	</td>
	<td width="180">
	  <input type="text" size=5 name="plana" value="<?php print $plana; ?>"> Per Month
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div align="right">Plan B</div>
	</td>
	<td width="180">
	  <input type="text" size=5 name="planb" value="<?php print $planb; ?>"> Per Month
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
