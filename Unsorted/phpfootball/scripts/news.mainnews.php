<?php 
/*
***************************************************************************
Parameters :
Id
all 0,1
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td colspan=2 class=tdark><center>Main news</center></td></tr>
<?
if ($Id != "" && !$all){
	$query = "SELECT * FROM News WHERE Id = $Id";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	$num_res = mysql_num_rows($result);
	for ($i=0; $i<$num_res; $i++){
              $myrow = mysql_fetch_array($result);
              $Headline = $myrow["Headline"];
              $Content = $myrow["Content"];
              $Poster = $myrow["Poster"];
              $Date = $myrow["Date"];
	}
echo "<tr><td class=tddd>$Headline&nbsp;</td></tr><tr><td height=200 class=td><center>$Content&nbsp;</center></td></tr><tr><td class=input><center>Posted by $Poster on $Date&nbsp;</center></td></tr>\n";
}
if (!$Id && !$all){
	$result = mysql_query("SELECT * FROM News");
	$num = mysql_num_rows($result);
	$number = $num--;
	$query = "SELECT * FROM News LIMIT $num,-1";
	echo $query;
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	$num_res = mysql_num_rows($result);
	for ($i=0; $i<$num_res; $i++){
              $myrow = mysql_fetch_array($result);
              $Headline = $myrow["Headline"];
              $Content = $myrow["Content"];
              $Poster = $myrow["Poster"];
              $Date = $myrow["Date"];
echo "<tr><td class=tddd>$Headline&nbsp;</td></tr><tr><td height=200 class=td><center>$Content&nbsp;</center></td></tr><tr><td class=input><center>Posted by $Poster on $Date&nbsp;</center></td></tr>\n";
	}
}
if ($all == "1"){
	$query = "SELECT * FROM News";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	$num_res = mysql_num_rows($result);
	for ($i=0; $i<$num_res; $i++){
              $myrow = mysql_fetch_array($result);
              $Headline = $myrow["Headline"];
              $Content = $myrow["Content"];
              $Poster = $myrow["Poster"];
              $Date = $myrow["Date"];
echo "<tr><td class=tddd>$Headline&nbsp;</td></tr><tr><td height=200 class=td><center>$Content&nbsp;</center></td></tr><tr><td class=input><center>Posted by $Poster on $Date&nbsp;</center></td></tr>\n";
	}
}
?>
</table>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>