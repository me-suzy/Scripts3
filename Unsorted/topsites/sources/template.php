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

function do_template ($filename) {
  global $CONFIG;
  $file = $CONFIG['templates_path']."/".$filename.".html";
  $fh_template = fopen($file, "r");
  $template = fread($fh_template, filesize($file)); 
  fclose($fh_template);

  if ($filename == "template") {
    if (preg_match("/<\#poweredby>/", $template)) { $copythere = 1; }

    if ($copythere) { $return = $template; }
    else { $return = "You cannot delete the &lt;#poweredby&gt; tag from template.html."; }
  }
  elseif ($filename == "admin" || $filename == "ssi_top" || $filename == "ssi_members") {
    $return = $template;
  }
  else {
    $return = "<!-- Begin $filename.html -->\n" . $template . "\n<!-- End $filename.html -->\n\n";
  }
  return template_regex($return);
}

function template_regex ($template) {
  global $LNG, $TMPL;
  $template = preg_replace("/<#lng\{\'(.+?)\'\}>/ei", "\$LNG['\\1']", $template);
  $template = preg_replace("/<#(.+?)>/ei", "\$TMPL['\\1']", $template);
  return $template;
}

function build_template_stuff () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL, $starttime;

  // Build the multiple pages menu
  if ($TMPL['nummem'] > $CONFIG['numlist']) {
    $num = $TMPL['nummem'];
    $done = 0;
    $TMPL['rankingsform'] = "<select name=\"start\">\n";
    while ($num > 0) {
      $start = $done * $CONFIG['numlist'] + 1;
      $end = ($done + 1) * $CONFIG['numlist'];
      $FORM['start'] = $FORM['start'] ? $FORM['start'] : 1;

      if ($FORM['start'] == $start) {
        $TMPL['rankingsform'] .= "<option value=\"".$start."\" selected=\"selected\">".$start." - ".$end."\n";
      }
      else {
        $TMPL['rankingsform'] .= "<option value=\"".$start."\">".$start." - ".$end."\n";
      }

      $num = $num - $CONFIG['numlist'];
      $done++;
    }
    $TMPL['rankingsform'] .= "</select>";
  }

  // Build the ranking method menu
  $rankingmethod = $FORM['method'] ? $FORM['method'] : $CONFIG['rankingmethod'];
  $TMPL['meth'] = $LNG['g_'.$rankingmethod];
  $TMPL['methform'] = "<select name=\"method\">\n";
  if ($rankingmethod == "unq_pv") { $TMPL['methform'] .= "<option value=\"unq_pv\" selected=\"selected\">".$LNG['g_unq_pv']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"unq_pv\">".$LNG['g_unq_pv']."\n"; }
  if ($rankingmethod == "tot_pv") { $TMPL['methform'] .= "<option value=\"tot_pv\" selected=\"selected\">".$LNG['g_tot_pv']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"tot_pv\">".$LNG['g_tot_pv']."\n"; }
  if ($rankingmethod == "unq_in") { $TMPL['methform'] .= "<option value=\"unq_in\" selected=\"selected\">".$LNG['g_unq_in']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"unq_in\">".$LNG['g_unq_in']."\n"; }
  if ($rankingmethod == "tot_in") { $TMPL['methform'] .= "<option value=\"tot_in\" selected=\"selected\">".$LNG['g_tot_in']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"tot_in\">".$LNG['g_tot_in']."\n"; }
  if ($rankingmethod == "unq_out") { $TMPL['methform'] .= "<option value=\"unq_out\" selected=\"selected\">".$LNG['g_unq_out']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"unq_out\">".$LNG['g_unq_out']."\n"; }
  if ($rankingmethod == "tot_out") { $TMPL['methform'] .= "<option value=\"tot_out\" selected=\"selected\">".$LNG['g_tot_out']."\n"; }
  else { $TMPL['methform'] .= "<option value=\"tot_out\">".$LNG['g_tot_out']."\n"; }
  $TMPL['methform'] .= "</select>";

  // Build the categories menu
  $current_cat = $FORM['cat'] ? $FORM['cat'] : $LNG['main_all'];
  $TMPL['catform'] = "<select name=\"cat\">\n";
  if ($current_cat == $LNG['main_all']) { $TMPL['catform'] .= "<option value=\"\" selected=\"selected\">${LNG['main_all']}\n"; }
  else { $TMPL['catform'] .= "<option value=\"\">${LNG['main_all']}\n"; }
  foreach ($CONFIG['categories'] as $cat) {
    if ($current_cat == $cat) { $TMPL['catform'] .= "<option value=\"$cat\" selected=\"selected\">$cat\n"; }
    else { $TMPL['catform'] .= "<option value=\"$cat\">$cat\n"; }
  }
  $TMPL['catform'] .= "</select>";

  // Featured member
  if ($CONFIG['featured']) {
    if ($TMPL['nummem']) {
      $nummem = $TMPL['nummem'] - 1;
      $limit = rand(0, $nummem);
    }
    else { $limit = 0; }
    $result = $db->SelectLimit("SELECT id, url, title, description, urlbanner FROM ".$CONFIG['sql_prefix']."_members", 1, $limit);
    list($TMPL['id'], $TMPL['real_url'], $TMPL['title'], $TMPL['description'], $TMPL['urlbanner']) = $db->FetchArray($result);
    $TMPL['url'] = $CONFIG['list_url']."/out.php?id=".$TMPL['id'];
    $TMPL['featured'] = do_template("featured");
  }

  // Please do not remove the link to http://www.aardvarkind.com/.
  // This is a free script, all I ask for is a link back.
  // If you need to remove the link, see my website for details.
  $TMPL['poweredby'] = $LNG['main_powered']." <a href=\"http://www.aardvarkind.com/\" target=\"_blank\">Aardvark Topsites PHP</a> ".$TMPL['version'];

  // Stop the timer
  $endtime = microtime();
  $endtime = explode (' ', $endtime);
  $endtime = $endtime[1] + $endtime[0];
  $TMPL['executiontime'] = round(($endtime - $starttime), 5);
}
?>