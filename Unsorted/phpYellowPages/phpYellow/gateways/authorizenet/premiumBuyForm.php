<?php echo"\n\n\n";?>
<!-- start of premiumBuyForm.php -->
<?php if ( BYPASSCARDPROCESSOR == "yes" ) {$myFormAction="premiumProcessPayment.php";}else{$myFormAction = POSTTRANSACTIONSURL;}?>
<form name="buyForm" method="post" action="<?php echo $myFormAction;?>">

<!-- start of premiumCheckout.php fields -->
<input type="hidden" name="customer_password" value="<?php echo $customer_password;?>">
<input type="hidden" name="x_email" value="<?php echo $x_email;?>">
<input type="hidden" name="x_company" value="<?php echo $x_company;?>">
<input type="hidden" name="x_first_name" value="<?php echo $x_first_name;?>">
<input type="hidden" name="x_last_name" value="<?php echo $x_last_name;?>">
<input type="hidden" name="x_address" value="<?php echo $x_address;?>">
<input type="hidden" name="x_city" value="<?php echo $x_city;?>"> 
<input type="hidden" name="x_state" value="<?php echo $x_state;?>"> 
<input type="hidden" name="x_country" value="<?php echo $x_country;?>">
<input type="hidden" name="x_zip" value="<?php echo $x_zip;?>">
<input type="hidden" name="x_phone" value="<?php echo $x_phone;?>">
<input type="hidden" name="x_fax" value="<?php echo $x_fax;?>">
<!-- end of premiumCheckout.php fields -->


<!-- start of other hidden variables -->
<input type="hidden" name="goal" value="Process Payment Response">
<input type="hidden" name="x_amount" value="<?php echo $x_amount;?>">
<!-- the Invoice number consists of todaysDate and a hyphen and the unique ckey of the category listing -->
<input type="hidden" name="x_invoice_num" value="<?php echo $x_invoice_num;?>">
<input type="hidden" name="productDescription" value="<?php echo $productDescription;?>">
<input type="hidden" name="customerid" value="<?php echo $yps;?>">
<!-- the $combined_name is the first and last name joined with a space in between -->
<?php $combined_name = $x_first_name . " " . $x_last_name;?> 
<input type="hidden" name="combined_name" value="<?php echo $combined_name;?>">
<input type="hidden" name="yps" value="<?php echo $yps;?>">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="item" value="<?php echo $item;?>">
<input type="hidden" name="expires" value="<?php echo $expires;?>">
<input type="hidden" name="monthsGoodFor" value="<?php echo $monthsGoodFor;?>">
<input type="hidden" name="category" value="<?php echo $category;?>">
<input type="hidden" name="tax_One_Amount" value="<?php echo $tax_One_Amount;?>">
<input type="hidden" name="tax_Two_Amount" value="<?php echo $tax_Two_Amount;?>">
<!-- end of other hidden variables -->
<!-- start of code used for authorizenet -->
<?php // authorizenet specific code
if(GATEWAYPROCESSOR == "authorizenet" ):
	$x_login = X_LOGIN;
	$transactionType = TRANSACTIONTYPE;
?>
<input type="hidden" name="x_login" value="<?php echo $x_login;?>">
<input type="hidden" name="transactionType" value="<?php echo $transactionType;?>">
<?php endif;?>
<!-- end of code used for authorizenet -->










<table class="form">
	<tr><th colspan=2>Proceed to Purchase?</th></tr>

	<tr><td>Item</td><td><?php echo $item . " Listing";?></td></tr>
   <tr><td>Description</td><td><?php echo $productDescription;?></td></tr>
   <tr><td>Category</td><td><?php echo $category;?></td></tr>
   <tr><td>Price</td><td><?php echo CURRENCYSYMBOL;?> <?php echo $x_amount;?></td></tr>
   <tr><td>Unique ckey</td><td><?php echo $ckey;?></td></tr>
   
   <tr><td colspan=2><i>Click Next Step - Improve your visibility!</i></td></tr>

	<tr>
		<td colspan=2>
			<input type="submit" name="submit" value=" Next Step " class="input">
		</td>
	</tr>
	
	<tr>
   	<td  colspan=2 align="right">
			<a href="mailto:<?php echo EXECEMAIL;?>"><i><?php echo EXECEMAIL;?></i></a>&nbsp;&nbsp;&nbsp;
			<input class="back" type="button" name="goBack" value=" &lt;-- Back " onClick="history.back(1)">
		</td>
	</tr>	
</table>



</form>

<!-- end of premiumBuyForm.php -->
<?php echo"\n\n\n\n\n";?>
				

<p><br></p>

<?php include("footer.php");?>