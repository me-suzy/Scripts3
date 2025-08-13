<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<?

$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

$gen = $dl->select("*","sl18_stories",array('sid'=>$_GET["no"]));

if( $_GET["set"] == "read" ) {
print "Reviews for <a href='story.php?no=" . $gen[0]["sid"] . "'>" . $cl->preva($gen[0]["sname"]) . "</a>\n";

	$table = $dl->select("r.*,u.*",
		"sl18_review r LEFT JOIN sl18_users u ON u.uid=r.ruid",
		array('rsid'=>$_GET["no"]),"GROUP BY r.rid");

	foreach($table as $row) {
?>
			<table border='0' width='100%'>
				<tr class='heavydis'>

		<? if( !empty( $row["ruid"] ) ) { ?>
					<td colspan='2'><a href='authors.php?no=<?=$row["ruid"]?>'><?=$row["ruser"]?></a></td>
		<? } else { ?>
					<td width='30%'><?=$cl->preva($row["ruser"])?></td>
					<td width='30%'><?=$cl->preva($row["remail"])?></td>
		<? } ?>

					<td width='30%'><?=$row["rdate"]?></td>
					<td width='10%'>id # <?=$row["rid"]?></td>
				</tr><tr>
					<td colspan='4'><?=$cl->prevb($row["rbody"])?></td>
				</tr>
			 </table>
		 	<p>
<?
	}

} else {
print "Review: <a href='story.php?no=" . $gen[0]["sid"] . "'>" . $cl->preva($gen[0]["sname"]) . "</a>\n";
	$table = $dl->select("COUNT(*) AS exist","sl18_stories",array('sid'=>$_GET["no"]));
	if ( $table[0]["exist"] == 1 ) {

		if (session_is_registered("uid")) {
			$table = $dl->select("*","sl18_users",array('uid'=>$_SESSION["uid"]));
		}
?>
			<form method='post' action='base/trans.php?addreview'>
			<table border='0' width='100%'>
				<tr>
					<td width='20%'>Name</td>

		<? if ($table[0]["upenname"]) { ?>
					<td><?=$table[0]["upenname"]?></td>
		<? } else { ?>
					<td><input type='text' name='ruser'></td>
		<? } ?>
				</tr><tr>	
					<td width='20%'>Email</td>

		<? if ($table[0]["uemail"]) { ?>
					<td><?=$table[0]["uemail"]?></td>
		<? } else { ?>
					<td><input type='text' name='remail'></td>
		<? } ?>
	
				</tr><tr>
					<td colspan='2'><textarea name='rbody' style='height:150; width:100%'></textarea>
				</tr><tr>
					<td colspan='2'>
		<? if ($table[0]["uid"]) ?>
					<input type='hidden' name='ruid' value='<?=$table[0]["uid"]?>'>
					<input type='hidden' name='rsid' value='<?=$_GET["no"]?>'>
					<input type='submit' name='submitreview' value='review'></td>
				</tr>
			</table>
<?
	} else {
		print "There is no story to review";
	}
}

?>

		</td>
	</tr>
</table>