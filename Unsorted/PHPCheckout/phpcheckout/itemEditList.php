<?php echo"\n\n\n"; // this is the kickoff form for product updates ?>
<!-- start of itemEditList.php -->
<form name="itemEditListForm.php" method="post" action="workWithItem.php">
	<input type="hidden" name="task" value="Retrieve Item">
	<table class="form">
		<tr>
			<th>Edit Item</th>
		</tr>

		<tr>
			<td>Contains ALL items</td>
		</tr>

		<tr>
			<td>
				<?php $dataset="all";buildProductList($dataset);?>
			</td>
		</tr>

		<tr>
			<td>
				<input type="submit" name="submit" value="Retrieve Item" class="submit">
			</td>
		</tr>
	</table>
</form>
<!-- end of itemEditList.php -->