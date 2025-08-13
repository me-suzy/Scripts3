<?php echo"\n\n\n";?>
<!-- start of premiumBuyForm.php -->
<?php if ( BYPASSCARDPROCESSOR == "yes" ) {$myFormAction="premiumProcessPayment.php";}else{$myFormAction = POSTTRANSACTIONSURL;}?>
<form name="buyForm" method="post" action="<?php echo $myFormAction;?>">


<!-- start of paypal single item purchase fields -->
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="<?php echo MERCHANTACCOUNT;?>">
	<?php 
	// build a usable description for display on the paypal page
	$encodedCategory = urlencode($category);
	$descriptionWithSpaces = $item . "_" . $category . "_Listing_for_" . $monthsGoodFor . "_months";
	$descriptionWithoutSpaces = str_replace( " ", "_", $descriptionWithSpaces);
	// here we embed all variables and values we need to be passed back to premiumProcessPayment.php
	$encodedItem = urlencode($item);
	$goal = urlencode("Process Payment Response");
	$querystring = "?ckey=$ckey&monthsGoodFor=$monthsGoodFor&item=$encodedItem&goal=$goal";
	$return = INSTALLPATH . "premiumProcessPayment.php" . $querystring;?>
	<input type="hidden" name="return" value="<?php echo $return;?>">
		<!-- NOT USED: input type="hidden" name="undefined_quantity" value="1"-->
	<input type="hidden" name="monthsGoodFor" value="<?php echo $monthsGoodFor;?>">
	<input type="hidden" name="item_name" value="<?php echo $descriptionWithoutSpaces;?>">
	<input type="hidden" name="item_number" value="<?php echo $ckey;?>">
	<input type="hidden" name="amount" value="<?php echo $x_amount;?>">
	<input type="hidden" name="no_shipping" value="1">
		<?php //$image_url= INSTALLPATH . "appimage/phpYellowPages.gif";?>
		<!-- NOT USED: Business name used instead: input type="hidden" name="image_url" value="<?php echo $image_url;?>"-->
		<!-- NOT USED: paypal default page used instead: input type="hidden" name="cancel_return" value="http://www.yoursite.com/cancel.htm"-->
	<input type="hidden" name=" no_note" value="1">
<!-- end of of paypal single item purchase fields -->



<!-- start of other hidden variables -->
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
			<!-- NOT USED: input type="image" src="http://images.paypal.com/images/x-clickbut01.gif" name="submit" alt="Make payments with PayPal - it's fast,free and secure!"-->
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