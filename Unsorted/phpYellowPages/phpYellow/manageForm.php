<?php echo"\n\n\n\n";?>
<!--START manageForm.php-->
<form name='manageForm' action='adminresult.php' method='post'>
<input type='hidden' name='formuser' value='<?php print($formuser);?>'>
<input type='hidden' name='formpassword' value='<?php print($formpassword);?>'>
<input type="hidden" name="goal" value="Manage Listings">
<table class='form'>
	<tr>
		<th colspan=2>Manage Listings</th>
	</tr>

	<tr>
		<td>Search for</td>
		<td><?php include("categories.php");?></td>
	</tr>


	<tr>
		<td>Find in City</td>
		<td><?php print("<input type='text' name='citytofind' value='' size=25 class='input'> Optional\n");?></td>
	</tr>

	<tr>
		<td>OR Find By State</td>
		<td><input type='text' name='ystateprov' value='' size=25 class='input'> Optional</td>
	</tr>

	<tr>
		<td>OR Find by Country</td> 
		<td><?php include("countries.php");?></td>
	</tr>

	<tr>
		<td colspan=2><input class='input' type='submit' name='submit' value='Manage Listings'>
</form>
		<p></p>
<form name="goToAdmin" method="post" action="<?php echo ADMINHOME;?>">
<input class="input" type="submit" name="submitgoadmin" value="To Admin">
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
</form>
		</td>
	</tr>
</table>
<!-- END of manageForm.php-->