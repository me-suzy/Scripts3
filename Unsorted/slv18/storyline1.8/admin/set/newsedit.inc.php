<font class='catdis'> > Edit News </font><p>

<?
if ($_POST["editnewsubmit"]) {

	$dl->update("sl18_news",array('nname'=>$_POST["nname"],'nbody'=>$_POST["nbody"]),array('nid'=>$_POST["nid"]));

	print "<u>" . stripslashes($_POST["nname"]) . "</u><br>";
	print nl2br(stripslashes($_POST["nbody"])) . "<p>";

	print "News item has been edited";

} elseif ($_POST["editnewsmid"]) {

	$table = $dl->select("*","sl18_news",array('nid'=>$_POST["nid"]));

	if( count( $table ) < 1 ) 
		print "There is no news item with the id supplied";
	else {
?>
		<form method='post' action='main.php?newsedit'>
		<table border='0' width='100%'>
			<tr>
				<td width='20%'>Title</td><td><input type='text' name='nname' value='<?=$cl->preva($table[0]["nname"])?>'></td>
			</tr><tr>
				<td width='20%' valign='top'>Body</td>
				<td><textarea name='nbody' style='width:100%; height:300px'><?=stripslashes($table[0]["nbody"])?></textarea></td>
			</tr><tr>
				<td colspan='2'>
					<input type='hidden' name='nid' value='<?=$table[0]["nid"]?>'>
					<input type='submit' name='editnewsubmit' value='edit news'>
				</td>
			</tr>
<?
	}

} else {
?>
	<form method='post' action='main.php?newsedit'>
	News id# <input type='text' name='nid'><br>
	<input type='submit' name='editnewsmid' value='choose news item'>
	</form>
<?
}
?>