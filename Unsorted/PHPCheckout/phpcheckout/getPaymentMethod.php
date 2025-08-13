<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
      <script language="Javascript" src="phpcheckout.js"></script>
      <script language="Javascript">loadCSSFile();</script>
		
		<?php require_once("configure.php");?>
		
<TITLE><?php echo IMPLEMENTATIONNAME;?> - <?php echo BENEFIT;?> -  <?php echo ORGANIZATION;?> - Powered by phpcheckoutTM from DreamRiver http://wwww.dreamriver.com - Software Powers the Net - a DreamRiver Internet Software Product - php software mysql database - download - web database - dreamriver - freeware - sql - file downloader - internet application development - hot scripts - good value - database software - custom code - php e-mail software - ultimate web apps software - phpYellow Pages - EasySQL - phpTellAFriend - mysql php download - Easily create complete internet programs with php, apache, mysql and windows</TITLE>

		<META NAME="keywords" CONTENT="Home, dreamriver internet training programming consulting services software products small medium size enterprises globally.">
		<META NAME="description" CONTENT="Home - dreamriver.com delivers internet training, programming and consulting services, and software products for small-to-medium-size enterprises (SMEs) globally.">
		<META NAME="Author" CONTENT="Richard Creech, Email: richardc@dreamriver.com Web: http://www.dreamriver.com">
	</HEAD> 

<body>

<!-- START of body -->

<?php include("header.php");?>

	<!-- start of primary content FOR PAGE -->
	<tr>
	<!-- start LEFT COLUMN -->
		<td valign="top" align="center" width="30%" bgcolor="#e9e9e9">
			<h4>Purchase</h4>
			<b>Enter ...</b> 
			<ul>
				<li>Email to Ship To
				<li>Click &quot;Next Step&quot;
			</ul>
			<img src="pplogo_small.png" width="94" height="50" border="0" alt="Planet Payment Secure Processing with Planet Payment" align="absmiddle">

			
		</td>



		<!-- start MAIN COLUMN -->
		<td colspan=2>
		<!-- PUT CONTENT RIGHT HERE !!! -->
		
<table width=450 align="center" class="form">
<tr><!-- Rounded bottom row -->
	<th class="highlight">
	<div align="center">
		<p>Email To Ship To</p>
<!-- start of flags of the world -->		
<img src="usa.png" width="32" height="17" border="0" alt="We Accept Your Currency with Secure PlanetPayment Services">		
<img src="uk.gif" width="25" height="16" border="0" alt="We Accept Your Currency with Secure PlanetPayment Services">
<img src="australia.png" width="25" height="16" border="0" alt="We Accept Your Currency with Secure PlanetPayment Services">
<img src="canada.png" width="32" height="16" border="0" alt="We Accept Your Currency with Secure PlanetPayment Services">
</div>
<!-- end of flags of the world -->
	</th>
</tr>




<tr>
<td bgcolor="White">
<!-- START of Main Content -->



<form name="buyForm" method="post" action="buy.php">
<table width="100%">
<tr>
	<td colspan=2><h2>Email To Ship To</h2></td>
</tr>


<!-- product -->
<tr bgcolor="AliceBlue">
	<td>
	Product:
	</td>
	<td bgcolor="DarkSlateGray">
	<b style="font-size:16px;color:gold;"><?php echo"$product";?></b>
	</td>
</tr>


<!-- license -->
<tr bgcolor="Ivory">
	<td>
	License:
	</td>
	<td>
	<?php echo"$license<br>";?>
	</td>
</tr>

	
<!-- method -->
<tr bgcolor="AliceBlue">
	<td>	
	Method:
	</td>
	<td>
	<?php echo $method;?>
	<?php if($method == "Email Invoice"):?>
		<br>
		Email: <input type="text" name="email" value="" size=40>
	<?php endif;?>
	</td>
</tr>	
	
	
<!-- amount -->
<tr bgcolor="Ivory">
	<td>	
	Price:
	</td>
	<td>
	<?php echo $x_amount;?>
	</td>
</tr>	


<!-- return policy -->
<tr bgcolor="AliceBlue">
	<td>	
	Return Policy:
	</td>
	<td>
	<b style="color:red;">No returns.</b> All Sales are Final. <a href="../company/shipping.php" target="_blank">See Policy</a>.
	</td>
</tr>	


<!-- shipping -->
<tr bgcolor="Ivory">
	<td>	
	Shipping:
	</td>
	<td>
	Product is shipped via email attachment one (1) business day after confirmation of payment.
	</td>
</tr>	


<!-- enter email address to ship to -->
<tr bgcolor="AliceBlue">
	<td>	
	Ship To:
	</td>
	<td>
	Valid Email <input type="text" name="emailToShipTo" value="" size=30 maxlength=160>
	</td>
</tr>	


<tr>
	<td colspan=2>
	<br>
	<input class="input" type="submit" name="submit" value="Next Step"> 
	
	</td>
</tr></table>

<!-- these variables below are for buy.php -->
<input type="hidden" name="goal" value="Payment Instructions">
<input type="hidden" name="wanted" value="<?php echo $wanted;?>">
<input type="hidden" name="shipto" value="<?php echo $shipto;?>">

</form>
<!-- these variables above are for buy.php -->
<!-- END of Main Content -->
</td>	
</tr>



<tr><!-- bottom row -->
	<td align="right" class="highlight"><!-- set to desired page width-->
	<a href="mailto:info@dreamriver.com?subject=Dreamriver.com - Question"><i>info@dreamriver.com</i></a>&nbsp;&nbsp;&nbsp;		<input class="back" type="button" name="goBack" value=" &lt;-- Back " onClick="history.back(1)">
	</td>
</tr>
</table>		
		
		<p style="color:silver;">End of Document</p>
		
		<!-- END OF CORE CONTENT !!! -->
		</td>
	</tr>


<?php include("footer.php");?>

	
