<?php
//=================================================================\\
// Aardvark Topsites PHP 4.1.0                                     \\
//-----------------------------------------------------------------\\
// Copyright (C) 2003 Jeremy Scheff - http://www.aardvarkind.com/  \\
//-----------------------------------------------------------------\\
// This program is free software; you can redistribute it and/or   \\
// modify it under the terms of the GNU General Public License     \\
// as published by the Free Software Foundation; either version 2  \\
// of the License, or (at your option) any later version.          \\
//                                                                 \\
// This program is distributed in the hope that it will be useful, \\
// but WITHOUT ANY WARRANTY; without even the implied warranty of  \\
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   \\
// GNU General Public License for more details.                    \\
//=================================================================\\

$TMPL['header'] = $LNG['stats_header'];

if (!is_numeric($FORM['id'])) {
  error($LNG['stats_error_id'], 1, 0);
}
else { $FORM['id'] = intval($FORM['id']); }

$result = $db->Execute("SELECT id, url, title, description, category, urlbanner, total_ratings, num_ratings, unq_pv_today, unq_pv_1, unq_pv_2, unq_pv_3, (unq_pv_today + unq_pv_1 + unq_pv_2 + unq_pv_3) / 4, tot_pv_today, tot_pv_1, tot_pv_2, tot_pv_3, (tot_pv_today + tot_pv_1 + tot_pv_2 + tot_pv_3) / 4, unq_in_today, unq_in_1, unq_in_2, unq_in_3, (unq_in_today + unq_in_1 + unq_in_2 + unq_in_3) / 4, tot_in_today, tot_in_1, tot_in_2, tot_in_3, (tot_in_today + tot_in_1 + tot_in_2 + tot_in_3) / 4, unq_out_today, unq_out_1, unq_out_2, unq_out_3, (unq_out_today + unq_out_1 + unq_out_2 + unq_out_3) / 4, tot_out_today, tot_out_1, tot_out_2, tot_out_3, (tot_out_today + tot_out_1 + tot_out_2 + tot_out_3) / 4
                        FROM ".$CONFIG['sql_prefix']."_members, ".$CONFIG['sql_prefix']."_stats
                        WHERE id = id2 AND id = ".$FORM['id']);

list($TMPL['id'], $TMPL['real_url'], $TMPL['title'], $TMPL['description'], $TMPL['cat'], $TMPL['urlbanner'], $total_ratings, $TMPL['ratenum'], $TMPL['unq_pv_tod'], $TMPL['unq_pv_1'], $TMPL['unq_pv_2'], $TMPL['unq_pv_3'], $TMPL['unq_pv_avg'], $TMPL['tot_pv_tod'], $TMPL['tot_pv_1'], $TMPL['tot_pv_2'], $TMPL['tot_pv_3'], $TMPL['tot_pv_avg'], $TMPL['unq_in_tod'], $TMPL['unq_in_1'], $TMPL['unq_in_2'], $TMPL['unq_in_3'], $TMPL['unq_in_avg'], $TMPL['tot_in_tod'], $TMPL['tot_in_1'], $TMPL['tot_in_2'], $TMPL['tot_in_3'], $TMPL['tot_in_avg'], $TMPL['unq_out_tod'], $TMPL['unq_out_1'], $TMPL['unq_out_2'], $TMPL['unq_out_3'], $TMPL['unq_out_avg'], $TMPL['tot_out_tod'], $TMPL['tot_out_1'], $TMPL['tot_out_2'], $TMPL['tot_out_3'], $TMPL['tot_out_avg']) = $db->FetchArray($result);
$TMPL['url'] = $CONFIG['list_url']."/out.php?id=".$TMPL['id'];
$TMPL['avg_rate'] = $TMPL['ratenum'] ? round($total_ratings / $TMPL['ratenum'], 2) : 0;

$query = "SELECT review_id,  review_date, review FROM ".$CONFIG['sql_prefix']."_reviews WHERE id3 = ".$FORM['id'];
if ($FORM['allreviews']) { $result = $db->Execute($query); }
else { $result = $db->SelectLimit($query, 10, 0); }
while (list($review_id, $review_date, $review) = $db->FetchArray($result)) {
  $TMPL['reviews'] .= "<b>".$review_id." - ".$review_date."</b><br />\n".$review."<br /><br />\n";
}

$TMPL['content'] .= do_template("stats");
?>