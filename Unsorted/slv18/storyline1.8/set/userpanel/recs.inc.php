<?
$cl = new TheCleaner();
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;
?>

<form method='post' action='base/trans.php?recs'>

<?
$call = $dl->select("sl18_users.urecs AS recs","sl18_users",array('uid'=>$_SESSION["uid"]));

if( !empty($call[0]["recs"]) ) {

	$recs = explode("|",$call[0]["recs"]);

	foreach($recs as $is) {
		$callb = $dl->select("*","sl18_stories",array('sid'=>$is));
		print "Story N<sup><u>o</u></sup> <input type='text' name='recs[]' value='" . $is . "'>";
			if(count($callb) == 0)
				print " [This story no longer exists]<br>";
			else
				print " [<a href='story.php?no=".$callb[0]["sid"]."'>" . $cl->preva($callb[0]["sname"]) . "</a>]<br>";
	}
}

$n = 10 - count($recs);							// users want more rec slots? Change 10 to whatever number they want


for($i=0 ; $i<$n ; $i++) {
	print "Story N<sup><u>o</u></sup> <input type='text' name='recs[]'><br>";
}
?>
<br><input type='submit' value='update recs' name='submitrecs'>
</form>
