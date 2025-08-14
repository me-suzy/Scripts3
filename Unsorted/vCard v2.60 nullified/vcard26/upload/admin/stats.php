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
dothml_pageheader();

// ############################# DB ACTION #############################
if ($action == 'stats_restart')
{
	$result = $DB_site->query("DELETE FROM vcard_stats ");
	dohtml_result($result,"$msg_admin_stat_dbempty");
	$action ='';
}

// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if (empty($action))
{

$total_in_db 	= $DB_site->count_records("vcard_stats");
$total_in_site	= $DB_site->count_records("vcard_cards");
$total_regusers	= $DB_site->count_records("vcard_abook");
$total_ratings  = $DB_site->count_records("vcard_rating");
$total_uploadimages = $DB_site->count_records("vcard_attach");

dohtml_table_header("summary","$msg_admin_stat_summary",2);
?>
<tr><td><?php echo "$msg_admin_stat_totalsent"; ?></td>	<td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_in_db; ?></b></td></tr>
<tr><td><?php echo "$msg_admin_stat_totalcards"; ?></td><td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_in_site; ?></b></td></tr>
<tr><td><?php echo "$msg_admin_stat_regusers"; ?></td><td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_regusers; ?></b></td></tr>
<tr><td><?php echo "$msg_admin_stats_uploadimages"; ?></td><td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_uploadimages; ?></b></td></tr>
<tr><td><?php echo "$msg_admin_stat_ratingentries"; ?></td><td><font face="Arial" size="2" color="#CC0000"><b><?php echo $total_ratings; ?></b></td></tr>
<tr><td><?php echo "$msg_admin_stat_morestats"; ?></td><td><a href="stats2.php?&s=<?php echo $s; ?>">[ <?php echo $msg_admin_stat_detailstats; ?> ]</a> <?php echo "[<a href=\"stats2.php?period=month&s=$s\">$msg_admin_month</a>] [<a href=\"stats2.php?period=day&s=$s\">$msg_admin_day</a>] [<a href=\"stats2.php?period=week&s=$s\">$msg_admin_week</a>] "; ?></td>
<tr><td><?php echo "$msg_admin_stat_morestats"; ?></td><td><a href="stats3.php?&s=<?php echo $s; ?>">[ <?php echo $msg_admin_stat_external; ?> ]</a> </td>
<tr><td><?php echo "$msg_admin_stat_morestats"; ?></td><td><a href="stats4.php?&s=<?php echo $s; ?>">[ <?php echo $msg_admin_rating; ?> ]</a> </td>
<?php
dohtml_table_footer();

dohtml_table_header("cards","$msg_admin_top $admin_stats_toplist $msg_admin_postcard",6);
$query = $DB_site->query("
SELECT COUNT(s.card_id) AS score, cd.card_id, cd.card_caption, ct.cat_id, ct.cat_name
FROM vcard_stats AS s
   LEFT JOIN vcard_cards AS cd ON s.card_id=cd.card_id
   LEFT JOIN vcard_category AS ct ON ct.cat_id=cd.card_category
GROUP BY s.card_id
ORDER BY score DESC
LIMIT $admin_stats_toplist
");

while ($row  =  $DB_site->fetch_array($query))
{
	$row['cat_name'] 	= stripslashes(htmlspecialchars($row['cat_name']));
	$row['card_caption'] 	= stripslashes($row['card_caption']);
	echo "<tr>";
	echo "<td valign=top><font face=arial size=2>
	". cexpr($row[card_id] !="","<a HREF=\"$site_prog_url/create.php?card_id=$row[card_id]\" target=\"_blank\"><b>$row[card_id]</b>&nbsp;</td><td valign=top width=\"23%\">&nbsp;$row[card_caption]</a>
	</td><td valign=top width=\"25%\"><font face=arial size=2><b>&nbsp; <a HREF=\"$site_prog_url/gbrowse.php?cat_id=$row[cat_id]\" target=\"_blank\">$row[cat_name]</a>","$msg_admin_template")."";
	echo "</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[score] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[score]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"30%\">". gethtml_barchart($row[score]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();


dohtml_table_header("cards","$msg_admin_top $admin_stats_toplist $msg_admin_category",4);
$query = $DB_site->query("
SELECT COUNT(s.card_id) AS score, ct.cat_id, ct.cat_name
FROM vcard_stats AS s
 LEFT JOIN vcard_cards AS cd ON s.card_id=cd.card_id
 LEFT JOIN vcard_category AS ct ON ct.cat_id=cd.card_category 
GROUP BY cd.card_category
ORDER BY score DESC
LIMIT $admin_stats_toplist
");

while ($row  =  $DB_site->fetch_array($query))
{
	$row['cat_name'] = stripslashes(htmlspecialchars($row['cat_name']));
	echo "<tr>";
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp; <a HREF=\"$site_prog_url/gbrowse.php?cat_id=$row[cat_id]\" target=\"_blank\">$row[cat_name]</a>";
	echo "</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[score] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[score]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[score]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("template","$msg_admin_top $admin_stats_toplist $msg_admin_template",4);
$query = $DB_site->query("
	SELECT count(*) AS count,card_template
	FROM vcard_stats
	GROUP BY card_template
	ORDER BY count DESC LIMIT $admin_stats_toplist
	");

while ($row  =  $DB_site->fetch_array($query))
{
	echo "<tr>";
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp;<a HREF=\"$site_prog_url/templates/$row[card_template].ihtml\">$row[card_template]</a></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[count] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[count]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[count]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("poem","$msg_admin_top $admin_stats_toplist $msg_admin_poem",4);
$query = $DB_site->query("
	SELECT count(*) AS count,card_poem
	FROM vcard_stats
	GROUP BY card_poem
	ORDER BY count DESC LIMIT $admin_stats_toplist
	");

while ($row  =  $DB_site->fetch_array($query))
{
	echo "<tr>";
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp;";
	if($row['card_poem'] !="")
	{
		$poeminfo = $DB_site->query_first("SELECT poem_title FROM vcard_poem WHERE poem_id='$row[card_poem]'");
		$poem_title = stripslashes(htmlspecialchars($poeminfo['poem_title']));
	}
	echo "". cexpr($row[card_poem] !="","<a HREF=\"poem.php?action=view&poem_id=$row[card_poem]&s=$s\">$poem_title","$MsgNone") ."";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[count] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[count]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[count]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("stamp","$msg_admin_top $admin_stats_toplist $msg_admin_stamp",4);
$query = $DB_site->query("
	SELECT count(*) AS count, vcard_stats.card_stamp, vcard_stamp.stamp_file, vcard_stamp.stamp_name
	FROM vcard_stats
	   LEFT JOIN vcard_stamp ON  vcard_stamp.stamp_file = vcard_stats.card_stamp
	GROUP BY card_stamp
	ORDER BY count DESC LIMIT $admin_stats_toplist
	");

while ($row  =  $DB_site->fetch_array($query))
{
	$row['stamp_name'] = stripslashes(htmlspecialchars($row['stamp_name']));
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp;
	". cexpr($row[card_stamp] !="","<a HREF=\"$site_image_url/$row[card_stamp]\">$row[stamp_name]</a>","$MsgNone") ."";
	echo "</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[count] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[count]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[count]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("sound","$msg_admin_top $admin_stats_toplist $msg_admin_music",4);
$query = $DB_site->query("
	SELECT count(*) AS count, vcard_stats.card_sound, vcard_sound.sound_file, vcard_sound.sound_name
	FROM vcard_stats
	   LEFT JOIN vcard_sound ON vcard_stats.card_sound = vcard_sound.sound_file
	GROUP BY card_sound
	ORDER BY count DESC LIMIT $admin_stats_toplist
	");

while ($row  =  $DB_site->fetch_array($query))
{
	$row['sound_name'] = stripslashes(htmlspecialchars($row['sound_name']));
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp;
	". cexpr($row[card_sound] !="","<a HREF=\"$site_music_url/$row[card_sound]\">$row[sound_name]</a>","$MsgNone") ."";
	echo "</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[count] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[count]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[count]/$total_in_db*100) ."</td>";
	echo "</tr>"; 
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("pattern","$msg_admin_top $admin_stats_toplist $msg_admin_pattern",4);
$query = $DB_site->query("
	SELECT count(*) AS count, vcard_stats.card_background, vcard_pattern.pattern_file, vcard_pattern.pattern_name
	FROM vcard_stats
	   LEFT JOIN vcard_pattern ON vcard_pattern.pattern_file = vcard_stats.card_background
	GROUP BY card_background
	ORDER BY count DESC LIMIT $admin_stats_toplist
	");

while ($row  =  $DB_site->fetch_array($query))
{
	$row['pattern_name'] = stripslashes(htmlspecialchars($row['pattern_name']));
	echo "<td valign=top width=\"40%\"><font face=arial size=2><b>&nbsp;
	". cexpr($row[card_background] !="","<a HREF=\"$site_image_url/$row[card_background]\">$row[pattern_name]</a>","$MsgNone") ."";
	echo "</td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b> $row[count] </b></td>";
	echo "<td width=\"10%\" align=\"center\"><font color=cc0000 face=arial size=2><b>". get_percent($row[count]/$total_in_db*100) . "%</b></td>";
	echo "<td width=\"40%\">". gethtml_barchart($row[count]/$total_in_db*100) ."</td>";
	echo "</tr>";
}
$DB_site->free_result($query);
dohtml_table_footer();

dohtml_table_header("restart","$msg_admin_stat_restart",1);
dohtml_form_infobox("<font color=\"#FF0000\"><b>$msg_admin_warning</b></h2> $msg_admin_stat_note");
dohtml_form_header("stats","stats_restart",0,0);
dohtml_form_footer("$msg_admin_stat_restart");
dohtml_table_footer();
dothml_pagefooter();
exit;
}
?>