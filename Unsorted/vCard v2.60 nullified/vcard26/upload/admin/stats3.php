<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
require('./lib.inc.php');

check_lvl_access($canviewstats);
dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'stats_restart')
{
	$result = $DB_site->query("DELETE FROM vcard_statsextfile ");
	dohtml_result($result,"$msg_admin_stat_dbempty");
	$action = '';
}

// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if (empty($action))
{

$total_in_db 	= $DB_site->count_records("vcard_statsextfile");

dohtml_table_header("summary","$msg_admin_stat_summary",2);
?>
<tr><td><?php echo "$msg_admin_stat_totalentries"; ?></td>	<td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_in_db; ?></b></td></tr>
<?php
dohtml_table_footer();

dohtml_table_header("external","$msg_admin_top $admin_stats_toplist $msg_admin_stat_external",6);
$query = $DB_site->query("
SELECT COUNT(extfile_file) AS score, extfile_file,extfile_date
FROM vcard_statsextfile
GROUP BY extfile_file
ORDER BY score DESC
LIMIT $admin_stats_toplist
");

while ($row  =  $DB_site->fetch_array($query))
{
	echo "<tr>";
	echo "<td valign=top><font face=arial size=2><a HREF=\"$site_prog_url/create.php?f=$row[extfile_file]\" target=\"_blank\"><b>$row[extfile_file]</b>&nbsp;</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[score] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[score]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"30%\">". gethtml_barchart($row[score]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();


dohtml_table_header("restart","$msg_admin_stat_restart",1);
dohtml_form_infobox("<b>$msg_admin_warning</b><br> $msg_admin_stat3_note");
dohtml_form_header("stats3","stats_restart",0,0);
dohtml_form_footer("$msg_admin_stat_restart");
dohtml_table_footer();
dothml_pagefooter();
exit;
}
?>