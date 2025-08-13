<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<?
$cl = new TheCleaner();

$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

$table = $dl->select(
	"s.*, u.*, a.*,cg.*,sg.*, COUNT(DISTINCT c.cid) AS chap, COUNT(DISTINCT r.rid) AS rev",
	"sl18_stories s 
		LEFT JOIN sl18_chapters c ON c.csid=s.sid 
		LEFT JOIN sl18_users u ON u.uid=s.suid 
		LEFT JOIN sl18_rate a ON a.ratsid=s.sid 
		LEFT JOIN sl18_category cg ON cg.caid=s.scid 
		LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid 
		LEFT JOIN sl18_review r ON r.rsid=s.sid",

	"",
	"GROUP BY s.sid ORDER BY s.stime DESC LIMIT 0,15"
	);

	if ( count( $table ) < 1 ) 
		print "There are currently no published stories";
	else {

		foreach($table as $row) {

?>
			<a href='story.php?no=<?=$row["sid"]?>'><?=$cl->preva($row["sname"])?></a><br>
			<?=$cl->preva($row["sdescrip"])?><br>
			<font class='small'>
			<?=$cl->preva($row["caname"])?> > <?=$cl->preva($row["subname"])?> -:- 
			<a href='authors.php?no=<?=$row["uid"]?>'><?=$cl->preva($row["upenname"])?></a> -:- 
			<?=rating($row[srating])?> -:- 
			Chapters [<?=$row["chap"]?>] -:- 
			Published [<?=$row["sdate"]?>] -:- 
			Updated [<?=$row["scdate"]?>] -:- 
			Reviews [<a href='review.php?set=read&no=<?=$row["sid"]?>'><?=$row["rev"]?></a>] -:- 
			Average Vote [<? showrate(@ceil($row["rattotvote"] / $row["ratnovote"])) ?>]
			</font>
			<p>

<? 
		} 
	}
?>

		</td>
	</tr>
</table>