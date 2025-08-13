<?
$cl = new TheCleaner();

$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

// ##### How many pages are there anyway? This is set to 15 per page. No free will for you! Or, you know, just alter the instances of 15 to whatever ##### //

?>

<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<?

// ##### Thar be stories? ##### //

$table = $dl->select("*","sl18_stories",array('ssubid'=>$_GET["list"]));

if ( count ( $table ) == 0 ) {

	print "There are no stories contained in this sub-category";
	print "</td></tr>";

} else {

	$total = count( $table );
	$total = ceil($total / 15);

// ##### For identifying where we are in the page - page navigation ##### //

	if ( !isset ( $_GET["page"] ) || $_GET["page"] == 1 ) {				
		$page = 1;
		$getpage = 0;

	} else {
		$page = $_GET["page"];
		$getpage = $_GET["page"] * 15 - 15;
	}

// ##### Selecting all the info needed ##### //

	$table = $dl->select(
		"s.*, u.*, a.*, COUNT(DISTINCT c.cid) AS chap, COUNT(DISTINCT r.rid) AS rev",
		"sl18_stories s 
			LEFT JOIN sl18_chapters c ON c.csid=s.sid 
			LEFT JOIN sl18_users u ON u.uid=s.suid 
			LEFT JOIN sl18_rate a ON a.ratsid=s.sid 
			LEFT JOIN sl18_review r ON r.rsid=s.sid",
		array('s.ssubid'=>$_GET["list"]),
		"GROUP BY s.sid ORDER BY s.stime DESC LIMIT $getpage,15"
	) or die(
		$dl->getError()
	);

// ##### And printing it out ##### //

?>

<table border='0' width='100%'>

<? foreach ($table as $row) { ?>
	<tr>
	<? if ($row["srating"] == 5 ) { ?>
	<td class='catdis'><a href='story.php?no=<?=$row["sid"]?>' onClick='return confirm("I am old enough to read this")'><?=$cl->preva($row["sname"])?></a></td>
	<? } else { ?>
	<td class='catdis'><a href='story.php?no=<?=$row["sid"]?>'><?=$cl->preva($row["sname"])?></a></td>
	<? } ?>
	</tr><tr>
		<td><?=$cl->preva($row["sdescrip"])?></td>
	</tr><tr>
		<td class='small'>
			<img src='<?=SL_ROOT_URL?>/base/html/Default/images/<?=$row["sadd"]?>.gif' alt='add to story'>
			<a href='authors.php?no=<?=$row["uid"]?>'><?=$cl->preva($row["upenname"])?></a> -:- 
			<?=rating($row[srating])?> -:- 
			Chapters [<?=$row["chap"]?>] -:- 
			Published [<?=$row["sdate"]?>] -:- 
			Updated [<?=$row["scdate"]?>] -:- 
			Reviews [<a href='review.php?set=read&no=<?=$row["sid"]?>'><?=$row["rev"]?></a>] -:- 
			Average Vote [<? showrate(@ceil($row["rattotvote"] / $row["ratnovote"])) ?>]
		</td>
	</tr>
<? } ?>

</table>
		</td>
	</tr>

<?

// ##### Printing out the page nav, nice and easy ##### //

	if ($total > 0) {

?>
	<tr>
		<td class='cleardis'><center>
			<table border='0' width='100%'>
				<tr>
					<td width='33%'>
<?
		if ( $_GET["page"] > 1 ) {
			$pagen = $page - 1;
?>
						<center><a href='main.php?list=<?=$_GET["list"]?>'>
						<img src='<?=SL_ROOT_URL?>/base/html/Default/images/arrowbb.gif' border='0' alt='start'> </a>
						<a href='main.php?list=<?=$_GET["list"]?>&page=<?=$pagen?>'>
						<img src='<?=SL_ROOT_URL?>/base/html/Default/images/arrowb.gif' border='0' alt='back'> 
						</a></center>
<?
		}
?>
					</td>
					<td width='34%'>
						<center>Page: <?=$page?> of  <?=$total?></center>
					</td>
					<td with='33%'>
<?
		if ($total > 1 && $page != $total) {
			$pagen = $page + 1;
?>
						<center><a href='main.php?list=<?=$_GET["list"]?>&page=<?=$pagen?>'> 
						<img src='<?=SL_ROOT_URL?>/base/html/Default/images/arrowf.gif' border='0' alt='next'> 
						 </a> 
						<a href='main.php?list=<?=$_GET["list"]?>&page=<?=$total?>'> 
						<img src='<?=SL_ROOT_URL?>/base/html/Default/images/arrowff.gif' border='0' alt='end'> 
						 </a></center>
<?
		}
?>
					</td>
				</tr>
			</table>
			</center>
		</td>
	</tr>
<?
	}
}

?>

</table>