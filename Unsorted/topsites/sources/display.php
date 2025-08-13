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

if ($FORM['cat']) {
  $TMPL['category'] = $FORM['cat'];
  $category_sql = "AND category = '${TMPL['category']}'";
}
else { $TMPL['category'] = $LNG['main_all']; }

$TMPL['header'] = $LNG['main_header']." - ".$TMPL['category'];

$start = $FORM['start'] ? $FORM['start'] - 1 : 0;
$rankingmethod = $FORM['method'] ? $FORM['method'] : $CONFIG['rankingmethod'];

if ($CONFIG['top'] && $FORM['start'] < 2) { $TMPL['content'] = do_template("tableheader_top"); }
else { $TMPL['content'] = do_template("tableheader"); }

$result = $db->SelectLimit("SELECT id, url, title, description, category, urlbanner, total_ratings, num_ratings, unq_pv_today, (unq_pv_today + unq_pv_1 + unq_pv_2 + unq_pv_3) / 4, tot_pv_today, (tot_pv_today + tot_pv_1 + tot_pv_2 + tot_pv_3) / 4, unq_in_today, (unq_in_today + unq_in_1 + unq_in_2 + unq_in_3) / 4, tot_in_today, (tot_in_today + tot_in_1 + tot_in_2 + tot_in_3) / 4, unq_out_today, (unq_out_today + unq_out_1 + unq_out_2 + unq_out_3) / 4, tot_out_today, (tot_out_today + tot_out_1 + tot_out_2 + tot_out_3) / 4, old_rank
                            FROM ".$CONFIG['sql_prefix']."_members, ".$CONFIG['sql_prefix']."_stats
                            WHERE id = id2 $category_sql AND active = 1
                            ORDER BY (".$rankingmethod."_today + ".$rankingmethod."_1 + ".$rankingmethod."_2 + ".$rankingmethod."_3) / 4 DESC
                            ", $CONFIG['numlist'], $start);

$TMPL['rank'] = ++$start;
$page_rank = 1;
$TMPL['alt'] = 'alt';
while (list($TMPL['id'], $TMPL['real_url'], $TMPL['title'], $TMPL['description'], $TMPL['cat'], $TMPL['urlbanner'], $total_ratings, $TMPL['ratenum'], $TMPL['unq_pv_tod'], $TMPL['unq_pv_avg'], $TMPL['tot_pv_tod'], $TMPL['tot_pv_avg'], $TMPL['unq_in_tod'], $TMPL['unq_in_avg'], $TMPL['tot_in_tod'], $TMPL['tot_in_avg'], $TMPL['unq_out_tod'], $TMPL['unq_out_avg'], $TMPL['tot_out_tod'], $TMPL['tot_out_avg'], $TMPL['old_rank']) = $db->FetchArray($result)) {
  if ($CONFIG['rankingmethod'] == $rankingmethod && $TMPL['category'] == $LNG['main_all']) {
    if (!$TMPL['old_rank']) {
      $TMPL['old_rank'] = $TMPL['rank'];
      $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_stats SET old_rank = ".$TMPL['old_rank']." WHERE id2 = ".$TMPL['id']);
    }
    if ($TMPL['old_rank'] > $TMPL['rank']) { $TMPL['up_down'] = "up"; }
    elseif ($TMPL['old_rank'] < $TMPL['rank']) { $TMPL['up_down'] = "down"; }
    else { $TMPL['up_down'] = "neutral"; }
  }
  else { $TMPL['up_down'] = "neutral"; }

  if ($TMPL['alt']) { $TMPL['alt'] = ''; }
  else { $TMPL['alt'] = 'alt'; }

  $TMPL['url'] = $CONFIG['list_url']."/out.php?id=".$TMPL['id'];
  $TMPL['avg_rate'] = $TMPL['ratenum'] ? round($total_ratings / $TMPL['ratenum'], 0) : 0;
  $TMPL['tod'] = $TMPL[$rankingmethod . "_tod"];
  $TMPL['avg'] = $TMPL[$rankingmethod . "_avg"];
  if ($page_rank <= $CONFIG['top'] && $FORM['start'] < 2) {
    $TMPL['content'] .= do_template("table_top");
    $is_top = 1;
  }
  else {
    // This sees if $do_tableheader had been set during the last loop.  If so,
    // a new tableheader is printed.  This keeps a tableheader form being the
    // last thing on the page when there is an ad break at the end.
    if ($do_tableheader) {
      $TMPL['content'] .= do_template("tableheader");
      $do_tableheader = 0;
    }
    $TMPL['content'] .= do_template("table");
    $topdone = 1;
  }
  if ($page_rank == $CONFIG['top'] && $FORM['start'] < 2) {
    $TMPL['content'] .= do_template("tablecloser_top");
    $do_tableheader = 1;
  }

  if ($CONFIG['adbreaks'][$page_rank]) {
    if ($is_top) {
      $TMPL['content'] .= do_template("tablecloser_top");
      $TMPL['content'] .= do_template("adbreak_top");
      $TMPL['content'] .= do_template("tableheader_top");
    }
    else {
      $TMPL['content'] .= do_template("tablecloser");
      $TMPL['content'] .= do_template("adbreak");
      $do_tableheader = 1;
    }
  }

  $is_top = 0;
  $TMPL['rank']++;
  $page_rank++;
}

if ($topdone) {
  $TMPL['content'] .= do_template("tablecloser");
}
elseif (!$do_tableheader) {
  $TMPL['content'] .= do_template("tablecloser_top");
}
?>