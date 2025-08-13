<!-- start of navItem.php -->
<?php $productnumber = !isset($productnumber)?NULL:$_POST['productnumber']; // initialize or capture ?>

<table class="favcolor" border=0 cellpadding=10>
	
	<form method="post" action="surveyTake.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Take">
		</td>
	</tr>
	</form>


	<form method="post" action="surveyResults.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="View Results">
		</td>
	</tr>
	</form>




	<form method="post" action="workWithSurvey.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Change">
			<input type="hidden" name="task" value="Change">
			
		</td>
	</tr>
	</form>



	<form method="post" action="workWithSurvey.php">
	<tr>
		<td>
			<input class="submit" type="submit" value="Clear">
			<input type="hidden" name="task" value="Clear">
		</td>
	</tr>
	</form>
</table>
<!-- end of navItem.php -->










