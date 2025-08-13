<font class='catdis'> > Add News </font><p>

<?
if( !$_POST["newsubmit"] ) {
?>
	<form method='post' action='main.php?newsadd'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'>Title</td><td><input type='text' name='nname'></td>
		</tr><tr>
			<td width='20%' valign='top'>Body</td><td><textarea name='nbody' style='width:100%; height:300px'></textarea></td>
		</tr><tr>
			<td colspan='2'><input type='submit' name='newsubmit' value='submit news'></td>
		</tr>
	</table>
	</form>
<?
} else {
	
	$dl->insert("sl18_news",array('ndate'=>date("Y-m-d"),'nname'=>$_POST["nname"],'nbody'=>$_POST["nbody"]));

	print "<u>" . stripslashes($_POST["nname"]) . "</u><br>";
	print nl2br(stripslashes($_POST["nbody"])) . "<p>";

	print "Post has been made";

}