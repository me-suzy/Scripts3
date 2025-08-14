<?php
/************************************************************************/
/* BCB Spider Tracker: Simple Search Engine Bot Tracking                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004 by www.bluecollarbrain.com                        */
/* http://bluecollarbrain.com                                           */
/*                                                                      */
/* This program is free software. You may use it as you wish.           */
/*   This File: spider_results.php                                      */ 
/*   This is the main results page you use to see what spiders have     */
/*   crawled your site.                                                 */
/*                                                                      */
/*                                                                      */
/************************************************************************/
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>BCB SpiderTracker Results</title>
<link href="includes/bcb_spider.css" rel="stylesheet" type="text/css">
</HEAD>
<?php
require 'config/spider_config.php';      // Change this path if you move spider_config.php
require 'includes/spider_functions.php'; // Change this path if you move spider_functions.php
?> 

<TABLE WIDTH="98%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#003366">
    <TR> 
        <th width="180" height="20" VALIGN=top class="thpad"> <a href="<?php echo $_SERVER['PHP_SELF']."?sort=date";?>">Date</a> 
            <input name="hiddenField" type="hidden" value="<?php echo $row['timestamp'];?>">
        </th>
        <th width="4" bgcolor="#003366">&nbsp;</th>
        <th VALIGN=top class="thpad">
			<a href="<?php echo $_SERVER['PHP_SELF']."?sort=agent";?>">Spider Agent</a>
		</th>
        <th width="4" bgcolor="#003366">&nbsp;</th>
        <th VALIGN=top class="thpad">
			<a href="<?php echo $_SERVER['PHP_SELF']."?sort=page";?>">URL SPIDERED</a>
		</th>
    </TR>
    
<?php
// Get 'since' value - the earliest timestamp in the table.
$min_time_sql = "SELECT MIN(timestamp) as timestamp FROM $tablename";
$min_time_qry = mysql_query($min_time_sql);
$min_res = mysql_fetch_array($min_time_qry);
$mintimestamp = FncChangeTimestamp($min_res['timestamp'], MM."/".DD."/".YYYY);

// Pagination stuff ========================================================
  $limit = 20;                               // Change this to change how many results per page are displayed.
  $query_count = "SELECT * FROM $tablename";
  $result_count = mysql_query($query_count);
  $totalrows = mysql_num_rows($result_count);
  mysql_free_result($result_count);
// $pag = current page of records showing
if(!$pag){
  $pag = 1;
}    
$limit_value = $pag * $limit - ($limit);
  
switch($sort){
	case "agent":     $sortsql="select * from $tablename  ORDER BY agent LIMIT $limit_value, $limit";break;
	case "date":      $sortsql="select * from $tablename  ORDER BY timestamp DESC LIMIT $limit_value, $limit";break;
	case "page":      $sortsql="select * from $tablename ORDER BY url LIMIT $limit_value, $limit";break;
	default:          $sortsql="select * from $tablename  ORDER BY timestamp DESC LIMIT $limit_value, $limit";
}
$result = mysql_query("$sortsql");

while ($row=mysql_fetch_assoc($result)) {
	// Alternate row background colors
	if($bgcolor == "#CCCCCC"){
		$bgcolor = "#EEEEEE";
	} else {
		$bgcolor = "#CCCCCC";
	}
	
	$agent	= $row['agent'];
	$url	= $row['url'];
	$timestamp = $row['timestamp'];
	$timestamp = FncChangeTimestamp($timestamp, MM."/".DD."/".YYYY." - ".hh.":".mm);

echo "
<TR>
<TD bgcolor='$bgcolor' align='center' class='pad'><font style='font-size: 10px' face='verdana, arial, helvetica'><B>$timestamp</B></TD>
<td width='4' bgcolor='#003366'>&nbsp;</td>
<TD bgcolor='$bgcolor' class='pad'><font style='font-size: 10px' face='verdana, arial, helvetica'><B>$agent</B></TD>
<td width='4' bgcolor='#003366'>&nbsp;</td>
<TD bgcolor='$bgcolor' class='pad'><font style='font-size: 10px' face='verdana, arial, helvetica'><B>$url</B></TD>
</TR>";

}
?>
    <tr bgcolor="#003366"> 
        <th height="20" colspan="5" class="pad"> This site has been crawled <?php echo $totalrows;?> 
            times since <?php echo $mintimestamp; ?>. </th>
    </tr>
</TABLE>

<div align="left" class="bodyText">
<?php
// Display the pagination links
// Thanks to www.phpfreaks.com for the awesome pagination tutorial!
// The PREV link
if ($pag != 1){
	$pageprev = $pag - 1;
	echo("Go to Page <a href=\"?sort=$sort&amp;pag=$pageprev\">PREV_".$limit."</a>&nbsp;&nbsp;");
	}
	else { // If $pag == 1
		echo("Go to Page PREV_".$limit."&nbsp;&nbsp;");
		}
// The page number links		
$numofpages = ceil($totalrows / $limit);
for ($i = 1; $i <= $numofpages; $i++) {
	if ($i == $pag) {
		echo ($i."&nbsp;");
	} else {
		echo ("<a href=\"".$_SERVER['PHP_SELF']."?sort=$sort&amp;pag=$i\">$i</a>&nbsp;");
	}
}
// The NEXT link
$pagenext = $pag+1;
	if($pag == $numofpages) {
		echo ("NEXT_".$limit);
	} else {
			echo("<a href=\"".$_SERVER['PHP_SELF']."?sort=$sort&amp;pag=$pagenext\">NEXT_".$limit."</a>");
		$pagenext = $pag++;
	}
// End of pagination stuff
?>
</div>
<div align="center">
	<img src="includes/bcb_spider.jpg" width="240" height="180"><BR>
</div>
<div align=center class="poweredby">
	powered by BCB SpiderTracker<br />&copy;2004 - <A HREF="http://bluecollarbrain.com">www.bluecollarbrain.com</A>
</div>
</BODY>
</HTML>