<!-- start of navCustomer.php -->

<table>
<form method="post" action="workWithCustomer.php">
<tr>
	<td>
		<input class="submit" type="submit" value="Add">
		<input type="hidden" name="task" value="Add A Customer">
	</td>
</tr>
	</form>	


<form method="post" action="workWithCustomer.php">
<tr>
	<td>

<input class="submit" type="submit" value="&nbsp;Edit&nbsp;">
<input type="hidden" name="task" value="Edit A Customer">


	</td>
</tr>
</form>	

	<form method="post" action="workWithCustomer.php">
	<tr>
	<td>

<input class="submit" type="submit" value="Delete">
<input type="hidden" name="task" value="Delete A Customer">

	</td>
</tr>
</form>



<?php if(file_exists("proPurchaseForm.php")):?>
<form method="post" action="workWithCustomer.php">
<tr>
	<td>

<input class="submit" type="submit" value="+ Purchase">
<input type="hidden" name="task" value="+ Purchase">

	</td>
</tr>
</form>
<?php endif;?>


<form method="post" action="workWithCustomer.php">
<tr>
	<td>

<input name="go2findneedleForm" class="submit" type="button" value="Find" onclick='location.href="admin.php#find"'>
<input type="hidden" name="task" value="Find">

	</td>
</tr>
</form>



<form method="post" action="workWithCustomer.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Clear">
			<input type="hidden" name="task" value="Clear">
		</td>
	</tr>
	</form>
</table>
<!-- end of navCustomer.php -->