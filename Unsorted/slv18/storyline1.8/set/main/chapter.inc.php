<?

$cl = new TheCleaner();

$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

$table = $dl->select("s.*, u.*, c.*, cg.*, sg.*",
	"sl18_stories s
		LEFT JOIN sl18_chapters c ON c.csid=s.sid 
		LEFT JOIN sl18_users u ON u.uid=c.cuid 
		LEFT JOIN sl18_category cg ON cg.caid=s.scid 
		LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid",
	array('s.sid'=>$_GET["no"]),
	"ORDER BY cid ASC"
	);

$n = count($table);
$get = $_GET["chapter"] - 1;

?>

<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis' colspan='2'>

<?
if ( count( $table ) == 0 ) {

	print "This story does not exist";

} else {
?>
			<form name='form'>
			<table border='0' width='100%'>
				<tr class='catdis'>
					<td width='70%'>
						<a href='main.php?cat=<?=$table[0]["scid"]?>'><?=$cl->preva($table[0]["caname"])?></a> > 
						<a href='main.php?list=<?=$table[0]["ssubid"]?>'><?=$cl->preva($table[0]["subname"])?></a> > 
						<?=$cl->preva($table[0]["sname"])?> > 
						<select onChange='javascript:formHandler()' name='chapnav'>
<?
	for($i=0;$i<$n;$i++) {
		$o = $i+1;
		if ($o == 1)
			print "<option value='story.php?no=" . $table[0]["sid"] . "'>" .$o. ". " . $cl->preva($table[$i]["cname"]) . "</option> \n";
		elseif ($_GET["chapter"] == $o)
			print "<option value='story.php?no=" . $table[0]["sid"] . "&chapter=" . $o . "' selected>" .$o. ". " .$cl->preva($table[$i]["cname"]). "</option> \n";
		else		
			print "<option value='story.php?no=" . $table[0]["sid"] . "&chapter=" . $o . "'>" .$o. ". " . $cl->preva($table[$i]["cname"]) . "</option> \n";
	}
?>
						</select>
					</td>
					<td width='15%'>
						Author: <a href='authors.php?no=<?=$table[$_GET["chapter"]-1]["uid"]?>'>
						<?=$table[$_GET["chapter"]-1]["upenname"]?></a>
					</td>
					<td width='15%'>
						Hits: <?=$table[0]["sthits"]?>
					</td>
				</tr>
				<tr>
					<td>
						<?=$cl->prevb($table[$get]["cbody"])?>
					</td>
				</tr>
			</table>
			</form>

<? } ?>

		</td>
	</tr>
	<? if ( $n != 0 ) { ?>
	<tr>
		<td class='cleardis' width='50%'>
			<center><a href='review.php?set=add&no=<?=$table[0]["sid"]?>'>Review <?=$cl->preva($table[0]["sname"])?></a></center>
		</td>
		<td class='cleardis' width='50%'>
			<center><? addrating($cl->preva($table[0]["sname"]),$table[0]["sid"]) ?></center>
		</td>
	</tr>
	<? } ?>
</table>