<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<?

if ( !session_is_registered("uid") ) {
	print "You must be logged in to add a chapter to a story";

} else {
	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	$cl = new TheCleaner();

	$table = $dl->select("*","sl18_stories",array('sid'=>$_GET["no"],'sadd'=>0));

	if ( count ($table) < 1 ) {
		print "Either the story does not exist, or the author does not allow additions";
			
	} else { 
		print "Add chapter to <a href='story.php?no=" . $table[0]["sid"] . "'>" .$table[0]["sname"] . "</a><p>";

		if ($_POST["addtopreview"]) {
			$_POST["cname"] = $cl->preva($_POST["cname"]);
			$_POST["cbody"] = $cl->prevb($_POST["cbody"]);

			if (!$_POST["cname"] || !$_POST["cbody"]) {
				print "Please press back and fill out both fields";
			} else {
?>
				<form method='post' action='addto.php?no=<?=$_GET["no"]?>'>
				<table border='0' with='100%'>
					<tr>
						<td width='20%'>Chapter Name</td><td><?=$_POST["cname"]?></td>
					</tr><tr>
						<td width='20%' valign='top'>Text</td><td><?=$_POST["cbody"]?></td>
					</tr><tr>
						<td colspan='2'><input type='submit' name='addtosubmit' value='add chapter'></td>
					</tr>
				</table>
				<input type='hidden' name='cname' value='<?=$cl->trans($_POST["cname"])?>'>
				<input type='hidden' name='cbody' value='<?=$cl->trans($_POST["cbody"])?>'>
				</form>
<?

			}



		} elseif ( $_POST["addtosubmit"] ) {

			$chapter["cname"] = $cl->subm($_POST["cname"]);
			$chapter["cbody"] = $cl->subm($_POST["cbody"]);
			$chapter["cuid"] = $_SESSION["uid"];
			$chapter["cdate"] = date("Y-m-d");
			$chapter["csid"] = $_GET["no"];

			$dl->insert("sl18_chapters",$chapter) or die($dl->getError());
			$dl->update("sl18_stories",array('stime'=>date("YmdHis"),'scdate'=>date("Y-m-d")),array('sid'=>$_GET["no"])) or die($dl->getError());

			print "Your chapter has been added to the story";

		} else {
?>
			Warning: Once you have added a chapter, only the author of the story is authorized to edit it!
			<p>
			<form method='post' action='addto.php?no=<?=$_GET["no"]?>'>
			<table border='0' width='100%'>
				<tr>
					<td width='20%'>Chapter Title</td>
					<td><input type='text' name='cname' maxlength='50'></td>
				</tr><tr>
				 	<td width='20%' valign='top'>Chapter Text</td>
					<td><textarea name='cbody' style='width:100%; height:300'></textarea></td>
				</tr><tr>
					<td colspan='2'>
						<input type='submit' value='preview' name='addtopreview'>
					</td>
				</tr>
			</table>
			</form>

<?
		}
	}
}
?>
		</td>
	</tr>
</table>