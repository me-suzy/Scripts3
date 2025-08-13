<br>
<table align="center" width="300" border="0" cellspacing="0" cellpadding="2" class="main" style="border: 1 solid #000000">
	<tr>
		<td width="300" class="main_bold">
<form method="post" action="<?php echo $PHP_SELF ?>">
<center><span class="summary_message">- Add Plan -</span></center>
<br>
Plan Name:
<input type="text" size="30" name="package" class="formfield">
<br><br>
Monthly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="monthly" class="formfield">
<br><br>
Quarterly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="quarterly" class="formfield">
<br><br>
Bi-Annual Price: <?php echo $currencytype ?>
<input type="text" size="5" name="biannually" class="formfield">
<br><br>
Yearly Price: <?php echo $currencytype ?>
<input type="text" size="5" name="yearly" class="formfield">
<br><br>
<center><input type="submit" name="addplans" value="Add" class="formfield"></center>
</form>
</td>
</tr>
</table>