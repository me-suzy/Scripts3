<?php echo"\n\n\n"; // this is the kickoff form for product updates ?>
<!-- start of itemEditList.php -->
<form name="itemDeleteListForm" method="post" action="workWithItem.php">
	<input type="hidden" name="task" value="Delete this Item">
	<table bgcolor="red">
		<tr>
			<td style="background-color:orange;color:white;"><b>Delete an Item</b></td>
		</tr>

		<tr>
			<td>WARNING! Permanent removal of 1 item will result</td>
		</tr>

		<tr>
			<td>
				<?php $dataset="all";buildProductList($dataset);?>
			</td>
		</tr>

		<tr>
			<td>
				<input type="submit" name="submit" value="Delete this Item" style="color:red;background-color:white;font-weight:bold;">
			</td>
		</tr>
	</table>
</form>
<!-- end of itemEditList.php -->