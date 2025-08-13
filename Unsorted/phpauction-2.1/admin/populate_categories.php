<?
   require "../includes/config.inc.php";
?>
<HTML>
<HEAD></HEAD>
<BODY>
<H2>PHPauction - Populate Categories Script</H2>
<BR>
<? 
$query = "delete from PHPAUCTION_categories";
$result = mysql_query($query);
if(!$result){
	print $ERR_00001;
	exit;
}
$buffer = file("./categories.txt");
$count_cat  = 0;
$counter    = 0;
$id		    = 0;
$actuals[0] = 0;
while(!ereg("^1@(.)*$",$buffer[$counter])){
  $counter++;
}

while($counter < count($buffer))
{print "$counter - ";
	$category    = explode("@", $buffer[$counter]);
	$category[1] = ereg_replace(10,"",$category[1]);
	$category[1] = ereg_replace(13,"",$category[1]);
	$id++;;
	if($category[0] != $actual){
		$actual 			= $category[0];
	}
	$actuals[$actual]	= $id;
	$father = $actuals[$actual - 1];
	print "F: $father - $category[1]<BR>";
	$query = "insert into PHPAUCTION_categories (cat_id, parent_id, cat_name, deleted, sub_counter, counter) values($id,$father,\"$category[1]\",0,0,0)";
	$result = mysql_query($query);
	if(!$result)
	{
			print $ERR_00001;
			print "<BR>$query - $actual";
			exit;
	}

	$counter++;
	$count_cat++;
}
?>

<BR>
<BR>
<BR>
<? 
print "<B>$count_cat</B>&nbsp;categories added<BR><BR>";
?>
<BR><BR>
You now need to run:
<UL>
<LI><A HREF="../util_cc1.php">util_cc1.php</A> (run this first)
<LI><A HREF="../util_cc2.php">util_cc2.php</A>
</UL>

</BODY>
</HTML>


