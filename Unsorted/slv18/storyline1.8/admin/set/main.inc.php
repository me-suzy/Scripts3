<table border='0' width='100%'>
	<tr class='heavydis'>
		<td><b>Welcome to the Admin Panel</b></td>
	</tr><tr>
		<td>
<?
			if( file_exists( SL_ROOT_PATH . "/installer.php" ) ) 
				print "<font color='red'>Warning! You have not removed installer.php!</font><p>";
?>
			General > Configuration => The details of your site.<br>
			General > Language => Change the words used [not yet functional]<p>
			Manage > Category => Create, edit and delete categories<br>
			Manage > SubCategory => Create, edit and delete sub-categories<br>
			Manage > Story => Edit location or delete stories<p>
			Cosmetics > Edit StyleSheet => Edit the presentation formatting<br>
			Cosmetics > Edit Layout => Edit the order of the blocks<br>
			Cosmetics > Edit File => Edit individual blocks<p>
			News > * => Add, edit and delete news items
		</td>
	</tr>
</table>