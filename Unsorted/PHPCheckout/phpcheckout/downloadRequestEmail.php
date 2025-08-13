<!-- START downloadRequestEmail.php -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<head>
		<?php require_once("configure.php");?>
		<script language="Javascript" src="phpcheckout.js"></script>
		<script language="Javascript">loadCSSFile();</script>
		<TITLE>Delivery Via <?php echo $via;?> -  <?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - Easily create complete internet programs with php, apache, mysql and windows</TITLE>
		</head> 

<body onload="window.document.requestEmailForm.recipient.focus()">
<!-- START of body -->
<?php if(!isset($recipient)){$recipient = NULL;}else{$recipient = $_POST['recipient'];}// get the recipient value?>





<?php include("header.php");?>

<?php include("youSelected.php");?>


<blockquote>

<h1>Delivery Via <?php echo $via;?></h1>

<?php echo $messageString;?>

<p>
The item you requested - <?php echo $productname;?> - is available via <?php echo $via;?>.<br>
Enter the email address to receive <?php echo $productname;?>:
</p>
<FORM name="requestEmailForm" method="post" action="runner.php">
	<!-- these are needed to deliver the product in the next step -->
	<input type="hidden" name="goal" value="Validate Email Before Sending Attachment">
	<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">	
	<input type="hidden" name="shortname" value="<?php echo $shortname;?>">	
	<input type="hidden" name="productname" value="<?php echo $productname;?>">
	<input type="hidden" name="availability" value="<?php echo $availability;?>">	
	<input type="hidden" name="via" value="<?php echo $via;?>">
<table>
<tr>
	<th colspan=2>Email to Recipient</td>
</tr>
<tr>
	<td>Recipient</td>
	<td>

		<input type="text" name="recipient" size=30 maxlength=120 value="<?php echo $recipient;?>">


	</td>
</tr>
<tr>
	<td colspan=2>
		<input class="submit" type="submit" name="submit" value="Email to Recipient">
	</td>
</tr>
</table>
</form>

</blockquote>

<p><br></p>



</body>
</html>
<!-- END downloadRequestEmail.php -->