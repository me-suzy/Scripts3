<?php
session_start();
ob_start();
include("../includes/validate_admin.php");
include("../includes/config.php");
include("../includes/admin_header.php");
include("../includes/messages.php");
if($Submit == "Submit")
{
	$subject = $subject;
	$body = $body;
	$st = "select * from StatAdmin";
	$rs = mysql_query($st) or die(mysql_error());
	$row = mysql_fetch_array($rs);
	$adminEmail = $row['email'];
	$from = $adminEmail;
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: <$sitename>\r\n";
	$message = $body;
	$st = "select * from StatMember";
	$rs = mysql_query($st) or die(mysql_error());
	while($row = mysql_fetch_array($rs))
	{
		$to = $row['email'];
		if(!mail($to, $subject, $message, $headers))
		{
			$msg = "Email failed to deliver";
		}
	}
}
?>
<script language="javascript">
function validate()
{
	with(document.frm)
	{
		if(subject.value == "")
		{
			alert("Please enter subject");
			subject.focus();
			return false;
		}
		if(body.value == "")
		{
			alert("Please enter Message");
			body.focus();
			return false;
		}
	}
}
</script>
<form name=frm method="post" action="broadcast.php" onSubmit="return validate();">
<table width="300" border="0"  cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td colspan="2">
	  <div align="center"><b>Broadcast Letter</b></div>
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
	  <div class="text" align="right">Subject:</div>
	</td>
	<td width="180">
	  <input type="text" size="30" name="subject" value="<?php print $subject; ?>">
	</td>
  </tr>
  <tr>
	<td width="120">
	  <div class="text" align="right">Message:</div>
	</td>
	<td width="180">
	  <textarea name="body" cols="30" rows="7"><?php print $body;?></textarea>
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
