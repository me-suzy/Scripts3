<?php require("../util.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
   <TITLE>Payment Instructions - Buy</TITLE>

	<link rel=stylesheet type="text/css" href="buy.css">

	<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
    <META NAME="keywords" CONTENT="php, php3, php4, software, freeware, download, code, source, freeware, downlaod, yellow page, directory, php, lookup, whois">
    <META NAME="description" CONTENT="">

</HEAD>

<body>
<div align="center">
<p><br></p>



<table width=450 align="center" class="form">
<tr><!-- Rounded bottom row -->
	<th class="highlight" valign="middle">
		<img src="buy.png" width="64" height="41" border="0" alt="Buy with Confidence - Secure Server" align="absmiddle">
		Payment Instructions
	</th>
</tr>





<tr>
<td bgcolor="White">
<!-- START of Main Content -->

<h2>Payment Instructions</h2>


<?php
echo"Thank you for your order. ";
echo"To deliver your order we need you to take that all important next step and <b>make the payment !</b> ";
echo"Your wish is to pay by &quot;$method&quot;";
echo"<h3>$method</h3>";
switch($method) {
	case"Credit Card by Internet": // and also Check by Internet is the same
	case"Check by Internet":	
		echo"$method payment can be made by clicking &quot;Make the Payment&quot; below.";
		include("orderViaAuthorizeNet.php");
		break;
	case"Credit Card by Phone":
		echo"$method payment can be made by phoning: " . PHONE;
		break;
	case"Credit Card by Fax":
	case"Check by Fax":
		echo"$method payment can be made by faxing: " . FAX;
		break;
	case"Check by Mail":	
	case"Money Order by Mail":
	case"US Currency":
	case"Travelers Checks":
	case"Credit Card by Mail":	
		echo"$method payment can be made to:<br><br><b style=\"color:Teal;\">" . LEGALNAME . "<br>" . ADDRESS . "</b>";
		break;
	case"Wire":
		echo"$method payment can be made by contacting the bank:<br> ";
		include("paymentByWireInfo.php");
		break;
	case"Email Invoice":		
		echo"An email invoice will be sent to $receiptEmail.<p></p>";
		break;			
	default:
		echo"No method defined.";
		exit;
	}
?>	

<h3>Transaction Details</h3>
Product: <?php echo $product1;?><br>
Unique Listing Data: Contact Key#<?php echo $yps;?> Category Key#<?php echo $ckey;?><br>
Duration of Position: <?php echo $expirationLength;?><br>
Price of Goods before Taxes: $<?php echo $chargeTotal;?><br>
Payable to: <?php echo LEGALNAME;?><br>
Payment method: <?php echo $method;?><br>
Send Receipt Email to: <?php echo $receiptEmail;?><br>

<p ALIGN="CENTER" STYLE="color:#006600;"><b>PRINT OUT AS A REMINDER?</b></p>

<p><a href="<?php echo INSTALLPATH;?>">Back to <?php echo COMPANY;?></a></p>



<!-- END of Main Content -->
</td>	
</tr>



<tr><!-- bottom row -->
	<td class="highlight"><!-- set to desired page width-->
	<table><tr>
	<td align="center"><b STYLE="COLOR:white;" align="center">Make the Upgrade - Enjoy Optimum Results!</b>
	<input class="back" type="button" name="goBack" value=" &lt;-- Back " onClick="history.back(1)"></td>
	</tr></table>
	</td>
</tr>
</table>


</div>
</body>
</html>