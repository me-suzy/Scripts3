<!-- start of aedControl.php -->
<?php $uniqueKey = $customerid;?>
<form name="taskSelectForm" method="post" action="workWithCustomer.php" target="_blank">
<input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
<input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">
<table bgcolor="silver" border=1>
<tr><th colspan=2><?php echo"Manage #$uniqueKey";?></th></tr>
<tr><td colspan=2>
	<input type="hidden" name="uniqueKey" value="<?php echo $uniqueKey;?>">
	<select name="task" size=3>
		<option value="Retrieve Customer Record" SELECTED>Retrieve Customer Record</option>
		<option value="Delete Single Record">Delete Single Record</option>
		<option value="Destroy This ID">Destroy This ID</option>
	</select>
</td></tr>	
<tr><td colspan=2>
	<input class="input" type="submit" name="Process" value="Process">
</td></tr></table>
</form>
<!-- end of aedControl.php -->