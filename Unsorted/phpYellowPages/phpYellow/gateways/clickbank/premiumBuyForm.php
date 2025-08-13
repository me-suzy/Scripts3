<!-- start of premiumBuyForm.php -->
<!-- variables expressly needed by clickbank are contained within the form action and urlencoded -->
<br>

<table width="45%"><tr><td>
<!-- working on 800etc >>>>>>>>>>>  form name="buyForm.php" method="get" action="http://www.clickbank.net/sell.cgi?link=800etc/13/Preferred_Internet-Products_Listing_for_1_months&c=$c&i=$i&m=$m&price=$x_amount"-->

<table class="form">
	<tr><th colspan=2>Proceed to Purchase?</th></tr>

	<tr><td>Item</td><td><b><?php echo $item;?> Listing</b></td></tr>
   <tr><td>Description</td><td><b><?php echo $productDescription;?></b></td></tr>
   <tr><td>Category</td><td><b><?php echo $category;?></b></td></tr>
   <tr><td>Price</td><td><b><?php echo $x_amount;?></b></td></tr>
   <tr><td>Unique ckey</td><td><?php echo $ckey;?></td>
   </tr>


   <tr>
   <td colspan=2>
	<br>
	<i>First you will be transported to our 
   	   secure server (run by <A ONMOUSEOVER="self.status='http://www.clickbank.com'; return true" HREF="http://zzz.clickbank.net/r/?800etc"><b>ClickBank</b></A>) where 
   	   you will be asked for your credit card 
   	   details. Visa, MasterCard, Discover,
   	   American Express, Eurocard, Novus, and 
   	   bank debit cards are accepted.
			
			<br><br>
   	   
			Once your card is approved you will be redirected back to our site, 
   	   where you may view your newly updated listing.
       The whole process takes under a minute!</i><br><br>
		 
		 <span style="font-size:18px;font-weight:bold;">
			<?php 
			// convert variables for temporary use in get method url
			// urlencode the product description for use in the email note receipt
			$encDesc = urlencode($category);

			$tempProductDescription = $item . "_" . $category . "_Listing_for_" . $monthsGoodFor . "_months";
			$encDesc = urlencode($tempProductDescription);
			$c = $ckey;
			$i = $item;
			$m = $monthsGoodFor;
			$nick = CLICKBANK_NICKNAME;
			?>
			<div align="center" style="font-size:24px;font-weight:bold;">
				<i><a href="http://www.clickbank.net/sell.cgi?link=<?php echo"$nick/$clickbanklinknumber/$productDescriptionForClickBank&c=$c&i=$i&m=$m&x_amount=$x_amount&encDesc=$encDesc";?>"> >>> Click Here to Purchase <<< </a></i>
			</div>
		 </span>
	</td></tr>

	<tr>
		<td colspan=2>
			&nbsp;
		</td>
	</tr>

			<tr>
   			<td  colspan=2 align="right">
					<a href="mailto:<?php echo EXECEMAIL;?>"><i><?php echo EXECEMAIL;?></i></a>&nbsp;&nbsp;&nbsp;
					<input class="back" type="button" name="goBack" value=" &lt;-- Back " onClick="history.back(1)">
				</td>
			</tr>	
		</table>
				</td>
			</tr>	
		</table>
</form>
<!-- end of premiumBuyForm.php -->
