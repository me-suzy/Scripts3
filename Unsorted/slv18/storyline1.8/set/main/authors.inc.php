<?

$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

?>

<table border='0' width='100%' cellspacing='4'>
	<tr>
		<td class='cleardis'>

<? if ( !$_GET["no"] ) { ?>

			<div align='right'>
			<form action='authors.php' method='post'>
			<select name='letter'>
			<option value=''>-- Choose Author Range --</option>
			<option value='%%'>All</option>
<?
	foreach(range('A', 'Z') as $l) {
		if(substr($_POST["letter"],0,1) == $l)
			print "<option value='".$l."%' selected>".$l."</option>\n";
		else
			print "<option value='".$l."%'>".$l."</option>\n";
	}
?>

			<select>
			<input type='submit' name='go' value='go'>
			</form>
			</div>

<?
	if ( empty( $_POST["letter"] ) )
		$_POST["letter"] = "A%";

	$table = $dl->search("sl18_users.*","sl18_users",array('upenname'=>$_POST["letter"]),"GROUP BY uid ORDER BY upenname ASC");

	$limit = ceil(count($table) / 4);
	$count = 1;
?>

			<table width='100%'>
				<tr>
					<td width='25%' valign='top'>
<?	
	foreach($table as $row) {
		print "<a href='authors.php?no=" . $row["uid"] . "'>" . $row["upenname"] . "</a><br> \n";
		$count++;
		if ($count > $limit) { 
			print "</td>\n<td width='25%' valign='top'>";
			$count=1;
		}
	}
?>
					</td>
				</tr>
			</table>

<?

} else {


	$table = $dl->select(
		"u.*, s.*, a.*,cg.*,sg.*, COUNT(DISTINCT c.cid) AS chap, COUNT(DISTINCT r.rid) AS rev",
		"sl18_users u 
			LEFT JOIN sl18_stories s ON s.suid=u.uid 
			LEFT JOIN sl18_chapters c ON c.csid=s.sid 
			LEFT JOIN sl18_rate a ON a.ratsid=s.sid 
			LEFT JOIN sl18_category cg ON cg.caid=s.scid 
			LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid 
			LEFT JOIN sl18_review r ON r.rsid=s.sid",
		array('u.uid'=>$_GET["no"]),
		"GROUP BY s.sid ORDER BY s.stime DESC") or die($dl->getError());

	if ( count($table) == 0 ) 
		print "The author you have requested does not exist";
	else {

?>
		<? if ($table[0]["uava"]) { ?>
			 <center><img src='<?=SL_ROOT_URL?>/images/avatars/<?=$table[0]["uava"]?>'></center>
		<? } ?>
			<table border='0' width='100%'>
				<tr>
					<td width='20%' class='heavydis'><?=$table[0]["upenname"]?></td><td align='right'>Joined: <?=$table[0]["ustart"]?></td>
				</tr>
				<tr>
					<td width='20%' class='catdis'>Email</td><td width='80%'><?=$table[0]["uemail"]?></td>
				</tr>

		<? if ( $table[0]["uurl"] ) { ?>
				<tr>
					<td width='20%' class='catdis'>Homepage</td><td width='80%'><?=$table[0]["uurl"]?></td>
				</tr>
		<? } if ( $table[0]["umsn"] ) { ?>
				<tr>
					<td width='20%' class='catdis'>MSN</td><td width='80%'><?=$table[0]["umsn"]?></td>
				</tr>
		<? } if ( $table[0]["uicq"] ) { ?>
				<tr>
					<td width='20%' class='catdis'>ICQ</td><td width='80%'><?=$table[0]["uicq"]?></td>
				</tr>
		<? } if ( $table[0]["uaol"] ) { ?>
				<tr>
					<td width='20%' class='catdis'>AOL</td><td width='80%'><?=$table[0]["uaol"]?></td>
				</tr>
		<? } if ( $table[0]["ubio"] ) { ?>
				<tr>
					<td width='20%' valign='top' class='catdis'>About</td><td width='80%'><?=$cl->preva($table[0]["ubio"])?></td>
				</tr>
		<? } ?>
				<tr>
					<td colspan='2' class='catdis'>
						Stories Published <br>
<?
			foreach ($table as $row) {
				if ( !$row["sname"] ) {
					print "No stories currently published";
					break;
				}
?>
						<a href='story.php?no=<?=$row["sid"]?>'><?=$cl->preva($row["sname"])?></a><br>
						<?=$cl->preva($row["sdescrip"])?><br>
						<font class='small'>
						<?=$cl->preva($row["caname"])?> > <?=$cl->preva($row["subname"])?> -:- 
						<a href='authors.php?no=<?=$row["uid"]?>'><?=$row["upenname"]?></a> -:- 
						<?=rating($row[srating])?> -:- 
						Chapters [<?=$row["chap"]?>] -:- 
						Published [<?=$row["sdate"]?>] -:-
						Updated [<?=$row["scdate"]?>] -:- 
						Reviews [<a href='review.php?set=read&no=<?=$row["sid"]?>'><?=$row["rev"]?></a>] -:- 
						Average Vote [<? showrate(@ceil($row["rattotvote"] / $row["ratnovote"])) ?>]
						</font>
						<p>
			<? } ?>

			</tr><tr>
				<td colspan='2' class='catdis'>
					Recomended Reading <br>
<?
			if ( !$table[0]["urecs"] ) 
				print "No recommended stories";
			else {
				$recs = explode("|",$table[0]["urecs"]);
				foreach($recs as $is) {
					$table = $dl->select(
					"s.*, u.*, a.*,cg.*,sg.*, COUNT(DISTINCT c.cid) AS chap, COUNT(DISTINCT r.rid) AS rev",
					"sl18_stories s 
						LEFT JOIN sl18_chapters c ON c.csid=s.sid 
						LEFT JOIN sl18_users u ON u.uid=s.suid 
						LEFT JOIN sl18_rate a ON a.ratsid=s.sid 
						LEFT JOIN sl18_category cg ON cg.caid=s.scid 
						LEFT JOIN sl18_subcategory sg ON sg.subid=s.ssubid 
						LEFT JOIN sl18_review r ON r.rsid=s.sid",

					array('s.sid'=>$is),
					"GROUP BY s.sid ORDER BY s.stime DESC"
					) or die(
						$dl->getError()
					);
?>
					<a href='story.php?no=<?=$table[0]["sid"]?>'><?=$cl->preva($table[0]["sname"])?></a><br>
					<?=$cl->preva($table[0]["sdescrip"])?><br>
					<font class='small'>
					<?=$cl->preva($table[0]["caname"])?> > <?=$cl->preva($table[0]["subname"])?> -:- 
					<a href='authors.php?no="<?=$table[0]["uid"]?>'><?=$table[0]["upenname"]?></a> -:- 
					<?=rating($table[0][srating])?> -:- 
					Chapters [<?=$table[0]["chap"]?>] -:- 
					Published [<?=$table[0]["sdate"]?>] -:- 
					Updated [<?=$table[0]["scdate"]?>] -:- 
					Reviews [<a href='review.php?set=read&no=<?=$table[0]["sid"]?>'><?=$table[0]["rev"]?></a>] -:- 
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
<?
	}
}

?>

		</td>
	</tr>
</table>