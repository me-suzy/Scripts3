<br>
<table align="center" width="300" border="0" cellspacing="0" cellpadding="2" class="main" style="border: 1 solid #000000">
	<tr>
		<td width="300" class="main_bold">
<form method="post" action="<?php echo $PHP_SELF ?>?confirm=yes">
<input type="hidden" name="id" value="<?php echo $id ?>">
<center><span class="summary_message">- Edit Plan -</span></center>
<br>
Plan Name:
<input type="text" size="30" name="package" class="formfield" value="<?php echo $package ?>">
<br><br>
Monthly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="monthly" class="formfield" value="<?php echo $monthly ?>">
<br><br>
Quarterly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="quarterly" class="formfield" value="<?php echo $quarterly ?>">
<br><br>
Bi-Annual Price: <?php echo $currencytype ?>
<input type="text" size="5" name="biannually" class="formfield" value="<?php echo $biannually ?>">
<br><br>
Yearly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="yearly" class="formfield" value="<?php echo $yearly ?>">
<br><br>
<center><input type="submit" name="editplan" value="Edit" class="formfield">
&nbsp;
<button onclick="window.location='setup.php?editplan=yes&delete=yes&id=<?php echo $id ?>'" class="formfield">Delete</button>
&nbsp;
<button onclick="window.location='setup.php?plans=yes'" class="formfield">Back</button></center>
</form>
</td>
</tr>
</table>