<?
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;

$table = $dl->select(
	"COUNT(DISTINCT s.sid) AS stories, COUNT(DISTINCT r.rid) AS reviews",
	"sl18_users u 
		LEFT JOIN sl18_stories s ON s.suid=u.uid 
		LEFT JOIN sl18_review r ON r.rsid=s.sid",
	array('u.uid'=>$_SESSION["uid"]),
	"GROUP BY u.uid"
) or die(
	$dl->getError()
);
?>

You have a total of <?=$table[0]["stories"]?> stories published<br>
You have a total of <?=$table[0]["reviews"]?> reviews

