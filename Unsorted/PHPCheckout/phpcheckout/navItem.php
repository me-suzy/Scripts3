<!-- start of navItem.php -->
<?php $productnumber = !isset($productnumber)?NULL:$_POST['productnumber']; // initialize or capture ?>

<table class="favcolor" border=0 cellpadding=10>
	
	<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="&nbsp;Add&nbsp;">
			<input type="hidden" name="task" value="Add An Item">
		</td>
	</tr>
	</form>


	<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="&nbsp;Edit&nbsp;">
			<input type="hidden" name="task" value="Build an Item List">
		</td>
	</tr>
	</form>



<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Delete">
			<input type="hidden" name="task" value="Delete">
		</td>
	</tr>
	</form>




<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Show Hits">
			<input type="hidden" name="task" value="Show Hits">
		</td>
	</tr>
	</form>



<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Reset Hits">
			<input type="hidden" name="task" value="Reset Hits">
		</td>
	</tr>
	</form>



<form method="post" action="workWithItem.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Clear">
			<input type="hidden" name="task" value="Clear">
			<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">
		</td>
	</tr>
	</form>
</table>
<!-- end of navItem.php -->










