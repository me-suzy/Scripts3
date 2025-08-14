<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
require('./lib.inc.php');

check_lvl_access($canviewstats);
dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'stats_restart')
{
	$result = $DB_site->query("DELETE FROM vcard_rating ");
	dohtml_result($result,"$msg_admin_stat_dbempty");
	$action = '';
}

// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if (empty($action))
{

$total_in_db 	= $DB_site->count_records("vcard_rating");

dohtml_table_header("summary","$msg_admin_stat_summary",2);
?>
<tr><td><?php echo "$msg_admin_stat_totalentries"; ?></td>	<td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_in_db; ?></b></td></tr>
<?php
dohtml_table_footer();
/*
   rating_id  int(10)  UNSIGNED No    auto_increment  Change  Drop  Primary  Index  Unique  Fulltext  
   rating_card  int(10)  UNSIGNED No  0    Change  Drop  Primary  Index  Unique  Fulltext  
   rating_value  tinyint(4)   No  0    Change  Drop  Primary  Index  Unique  Fulltext  
   rating_ip  varchar(50)   No      Change  Drop  Primary  Index  Unique  Fulltext  
   rating_date 
 
 */

dohtml_table_header("external","$msg_admin_top $admin_stats_toplist $msg_admin_rating",6);
	echo "<tr>";
	echo "<td valign=top><b>$msg_admin_card</b>&nbsp;</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> Average rating</b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> entries </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> Score</b></td>";
	echo "<td width=\"30%\"> .</td>";
	echo "</tr>";

$query = $DB_site->query("
SELECT COUNT(rating_card) AS score, rating_card,rating_value
FROM vcard_rating
GROUP BY rating_card
ORDER BY score DESC
LIMIT $admin_stats_toplist
");
	
while ($row  =  $DB_site->fetch_array($query))
{
	$card_rating = $DB_site->query_first("SELECT card_rating,card_caption FROM vcard_cards WHERE card_id='$row[rating_card]' ");
	echo "<tr>";
	echo "<td valign=top><font face=arial size=2><a HREF=\"$site_prog_url/create.php?card_id=$row[rating_card]\" target=\"_blank\"><b>$row[rating_card] - $card_rating[card_caption]</b>&nbsp;</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $card_rating[card_rating] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[score] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[score]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"30%\">". gethtml_barchart($row[score]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();


dohtml_table_header("restart","$msg_admin_stat_restart",1);
dohtml_form_infobox("<b>$msg_admin_warning</b><br> $msg_admin_stat4_note");
dohtml_form_header("stats4","stats_restart",0,0);
dohtml_form_footer("$msg_admin_stat_restart");
dohtml_table_footer();
dothml_pagefooter();
exit;
}
?>