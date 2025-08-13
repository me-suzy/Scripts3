<?php echo"\n\n\n\n";?>
<!--START searchForm.php -->
<?php //if( USECLICKBYCATEGORY == "yes" ) {include("clickByCategoryForm.php");}?>
<p>
<form name='searchForm' action='yellowresult.php' method='post'>
<input type="hidden" name="goal" value="Search">
<table class='form'>
	<tr>
		<th>Search</th>
	</tr>

	<tr>
		<td>
			Search for <?php include("categories.php");?> <input class="input" type="submit" name="submit" value="Find"><br>
			<br><font size=1><a href="http://www.dreamriver.com/phpYellow/index.php">Try the Premium Edition Advanced Search | </a><a href="http://www.dreamriver.com/fatpipe/startBuy.php">Buy Premium</a></font>
		</td>
	</tr>
</table>
</form>
</p>
<!-- END of searchForm.php -->