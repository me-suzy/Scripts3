<?php echo"\n\n\n\n";?>
<!-- start of categoryForm.php -->

<form name="categoryForm" method="post" action="yellowresult.php"  onSubmit="return checkCategoryForm(this)">

<table class="form">
<tr>
	<th colspan=2><?php echo $goal;?> <?php if ($goal != "Add Category" ){echo"Key#$ckey";}?></th>
</tr>


<tr>
	<td><b>Category</b></td>
	<?php if ($goal == "Add Category"){$category="";$description="";}// set category and description to nothing ?>
	<td>
	<?php if ($goal == "Update Category" ){echo $category; echo"<input type=\"hidden\" name=\"category\" value=\"$category\">"; }else{include("categories.php");} // since the listing may be firstpage we cannot allow them to change the category without verifying availability of the new category ?>
	</td>
</tr>



<?php if($goal=="Add Category"){$rank="0";}?>
<?php 
if(defined("PAIDLISTINGS") && ($goal != "Update Category")) {
	if((PAIDLISTINGS == "yes") || (PAIDLISTINGS == "all")) {
?>
<tr>
	<td><b>Position</b></td>
	<td>
		<select class="input" name="rank">
			<option value="<?php echo $rank;?>" SELECTED><?php echo $rank;?></option>
			<option value="2">First Page</option>
			<option value="1">Preferred</option>
			<option value="0">Basic</option>
		</select>
<?php
	}
}?>
	</td>
</tr>



<tr>
	<td><b>Description</b></td>
	<td><textarea class="input" name="description" rows=4 cols=60><?php echo $description;?></textarea><br>
		Enter a description up to 255 characters - about 4 lines.
	</td>
</tr>







<?php 
if ($goal == "Update Category" ):?>
<tr>
	<td>Status</td>
	<td><?php echo $status;?></td>
</tr>


<tr>
	<td>Expires</td>
	<td><?php echo $expires;?></td>
</tr>
<?php endif;?>


<?php if ( $goal != "Add Category" ):?>
<tr>
	<td>Last update</td>
	<td><?php echo $lastupdate;?></td>
</tr>


<tr>
	<td>Category Key#</td>
	<td><?php echo $ckey;?></td>
</tr>


<?php endif;?>




<tr>
	<td>Internal Use Only</td>
	<td>Contact Key#
	<input type="hidden" name="yps" value="<?php echo $yps;?>">
	<?php $ypsid = $yps;?>
	<input type="hidden" name="ypsid" value="<?php echo $ypsid;?>">	
	<?php echo $ypsid;?>
	<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
	</td>
</tr>


<tr>
<!-- used to pass current values back to the database when the listing is updated -->
<input type="hidden" name="paymentrequired" value="<?php echo $paymentrequired;?>">
<input type="hidden" name="status" value="<?php echo $status;?>">
<input type="hidden" name="expires" value="<?php echo $$expires;?>">
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="yemail" value="<?php echo $yemail;?>">
<input type="hidden" name="receiptEmail" value="<?php echo $yemail;?>">
<!-- these variables below are for use in premiumCheckout.php -->
<input type="hidden" value="<?php if(empty($x_first_name)){echo $yfirstname;}else{echo $x_first_name;}?>" name="x_first_name">
<input type="hidden" value="<?php if(empty($x_last_name)){echo $ylastname;}else{echo $x_last_name;}?>" name="x_last_name">
<input type="hidden" value="<?php if(empty($x_company)){echo $ycompany;}else{echo $x_company;}?>" name="x_company">
<input type="hidden" value="<?php if(empty($x_address)){echo $yaddress;}else{echo $x_address;}?>" name="x_address">
<input type="hidden" value="<?php if(empty($x_city)){echo $ycity;}else{echo $x_city;}?>" name="x_city">
<input type="hidden" value="<?php if(empty($x_state)){echo $ystateprov;}else{echo $x_state;}?>" name="x_state">
<input type="hidden" value="<?php if(empty($x_zip)){echo $ypostalcode;}else{echo $x_zip;}?>" name="x_zip">
<input type="hidden" value="<?php if(empty($x_country)){echo $ycountry;}else{echo $x_country;}?>" name="x_country">
<input type="hidden" value="<?php if(empty($x_phone)){echo $yphone;}else{echo $x_phone;}?>" name="x_phone">
<input type="hidden" value="<?php echo $yareacode;?>" name="area_code">
<input type="hidden" value="<?php if(empty($x_fax)){echo $yfax;}else{echo $x_fax;}?>" name="x_fax">
<input type="hidden" value="<?php if(empty($x_email)){echo $yemail;}else{echo $x_email;}?>" name="x_email">


<input type="hidden" name="yps" value="<?php echo $yps;?>">
<input type="hidden" name="x_cust_id" value="<?php if(empty($yfps)){echo $yps;}else{echo $yfps;}?>">
<!-- these variables above are for phpYellow Pages -->

	<td colspan=2><input class="input" type="submit" name="submit" value="<?php echo $goal;?>"></td>
</tr>
</table>
</form>


<p><br></p>


<?php 
if( defined("PAIDLISTINGS") && $goal != "Update Category") {
	if((PAIDLISTINGS == "yes") || (PAIDLISTINGS == "all")) {
		include("premiumPriceChart.php");
	}
}
?>
<!-- end of categoryForm.php -->