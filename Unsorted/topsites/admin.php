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

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

// Settings
require_once 'config.php';

// Require functions and process GET and POST input
require_once $CONFIG['path'].'/sources/functions.php';
$FORM = parse_form();

// The language file
require_once $CONFIG['path'].'/languages/'.$CONFIG['deflanguage'].'.php';

// Template functions
require_once $CONFIG['path'] . '/sources/template.php';

// Connect to the database
require_once $CONFIG['path'].'/sources/drivers/'.$CONFIG['sql'].'.php';
$db = new SQL;
$db->Connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_pass'], $CONFIG['sql_database']);

$result = $db->Execute("SELECT admin_password FROM ".$CONFIG['sql_prefix']."_etc");
list($admin_password) = $db->FetchArray($result);

require_once $CONFIG['path'].'/sources/session.php';

$TMPL['sid'] = $FORM['sid'];

list($type, $data) = CheckSession($TMPL['sid']);

if ($type == 'admin') {
  if ($FORM['page'] == "approve") { approve(); }
  elseif ($FORM['page'] == "delete") { delete(); }
  elseif ($FORM['page'] == "edit") { edit(); }
  elseif ($FORM['page'] == "delete_review") { delete_review(); }
  elseif ($FORM['page'] == "email") { email(); }
  elseif ($FORM['page'] == "find") { find(); }
  elseif ($FORM['page'] == "logout") { logout(); }
  elseif ($FORM['page'] == "reset") { reset_stats(); }
  elseif ($FORM['page'] == "settings") { settings(); }
  elseif ($FORM['page'] == "version") { version(); }
  else { main(); }

  UpdateSession($TMPL['sid']);
}
elseif($TMPL['sid']) { $TMPL['content'] = $LNG['a_login_session']; }
else { login(); }

$db->Close;

echo do_template("admin");

function approve () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['id']) {
    $result = $db->Execute("SELECT id, url, title FROM ".$CONFIG['sql_prefix']."_members WHERE active = 0");
    if (mysql_num_rows($result)) {
      $TMPL['content'] = "<table border=\"1\">\n";
      $TMPL['content'] .= "<tr><td>".$LNG['g_id']."</td><td width=\"100%\">".$LNG['g_title']."</td><td>".$LNG['a_approve']."</td><td>".$LNG['a_approve_del']."</td></tr>\n";
      while (list($id, $url, $title) = $db->FetchArray($result)) {
        $TMPL['content'] .= "<tr><td>$id</td><td><a href=\"$url\" target=\"_blank\">$title</a></td><td><a href=\"admin.php?page=approve&id=$id&sid=${TMPL['sid']}\">".$LNG['a_approve']."</a></td><td><a href=\"admin.php?page=delete&id=$id&sid=${TMPL['sid']}\">".$LNG['a_approve_del']."</a></td></tr>\n";
      }
      $TMPL['content'] .= "</table>";
    }
    else { $TMPL['content'] = $LNG['a_approve_none']; }
  }
  else {
    $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_members SET active = 1 WHERE id = ".$FORM['id']);
    $TMPL['content'] = $LNG['a_approve_done'];
  }
}

function delete () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['id']) {
    $TMPL['content'] = <<<EndHTML
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="delete" />
<b>${LNG['a_del_mem']}</b><br />
${LNG['g_id']}: <input name="id" type="text" size="7" /><br />
<input type="submit" value="${LNG['g_form_submit_long']}" />
</form>
EndHTML;
  }
  else {
    $db->Execute("DELETE FROM ".$CONFIG['sql_prefix']."_members WHERE id = ".$FORM['id']);
    $db->Execute("DELETE FROM ".$CONFIG['sql_prefix']."_stats WHERE id2 = ".$FORM['id']);
    $db->Execute("DELETE FROM ".$CONFIG['sql_prefix']."_reviews WHERE id3 = ".$FORM['id']);
    $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_etc SET num_members = num_members - 1");

    $TMPL['content'] = $LNG['a_del_mems'];
  }
}

function edit () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['do']) {
    $TMPL['content'] = <<<EndHTML
<form action="admin.php" method="get">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="edit" />
<b>${LNG['edit_header']}</b><br />
<input name="do" type="hidden" value="form" />
${LNG['g_id']}: <input name="id" type="text" size="7" /><br />
<input type="submit" value="${LNG['g_form_submit_long']}" />
</form>
EndHTML;
  }
  elseif ($FORM['do'] == "form") {
    $result = $db->Execute("SELECT id, password, url, title, description, category, urlbanner, email, active FROM ".$CONFIG['sql_prefix']."_members WHERE id = ".$FORM['id']);
    list($id, $password, $url, $title, $description, $category, $urlbanner, $email, $active) = $db->FetchArray($result);

    if ($id) {
      $catselect = "<select name=\"cat\">\n";
        foreach ($CONFIG['categories'] as $cat) {
        if ($cat == $category) { $catselect .= "<option value=\"$cat\" selected=\"selected\">$cat\n"; }
        else { $catselect .= "<option value=\"$cat\">$cat\n"; }
      }
      $catselect .= "</select>";

      if ($active) { $actives = "<option value=\"1\" selected=\"selected\">".$LNG['a_edit_active']."\n<option value=\"0\">".$LNG['a_edit_inactive']."\n"; }
      else { $actives = "<option value=\"1\">".$LNG['a_edit_active']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_edit_inactive']; }

      $TMPL['content'] .= <<<EndHTML
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="edit" />
<input name="do" type="hidden" value="submit" />
<input name="id" type="hidden" value="${id}" />
<table><tr><td>
${LNG['g_id']}
</td><td>
${id}
</td></tr><tr><td>
${LNG['g_url']}
</td><td>
<input name="url" type="text" size="50" value="${url}" />
</td></tr><tr><td>
${LNG['g_title']}
</td><td>
<input name="title" type="text" size="50" value="${title}" />
</td></tr><tr><td>
${LNG['g_description']}
</td><td>
<input name="description" type="text" size="50" value="${description}" />
</td></tr><tr><td>
${LNG['g_category']}
</td><td>
$catselect
</td></tr><tr><td>
${LNG['g_email']}
</td><td>
<input name="email" type="text" size="50" value="${email}" />
</td></tr><tr><td>
${LNG['g_bannerurl']}
</td><td>
<input name="urlbanner" type="text" size="50" value="${urlbanner}" />
</td></tr><tr><td>
${LNG['g_password']} - ${LNG['a_edit_password_blank']}
</td><td>
<input name="password" type="text" size="50" />
</td></tr><tr><td>
${LNG['a_edit_site_is']}
</td><td>
<select name="active">
$actives</select>
</td></tr><tr><td colspan="2" align="center">
<input type="submit" value="${LNG['g_form_submit_long']}" />
</td></tr></table>
</form>
EndHTML;
    }
    else { $TMPL['content'] .= $LNG['a_edit_error_id']; }
  }
  elseif ($FORM['do'] == "submit") {
    if (!preg_match("/http/", $FORM['url'])) { $error_url = 1; }
    if (!preg_match("/.+\@.+\.\w+/", $FORM['email'])) { $error_email = 1; }
    if (!$FORM['title']) { $error_title = 1; }
    if ($FORM['urlbanner'] == '' || $FORM['urlbanner'] == "http://") {
      $TMPL['urlbanner'] = $CONFIG['defbanner'];
    }
    elseif ($CONFIG['max_banner_width'] && $CONFIG['max_banner_height']) {
      $size = getimagesize($FORM['urlbanner']);
      if ($size[0] > $CONFIG['max_banner_width'] || $size[1] > $CONFIG['max_banner_height']) {
        $error_urlbanner = 1;
      }
      if (!$size[0] && !$size[1]) { $error_urlbanner = 1; }
    }

    if ($error_url || $error_email || $error_title || $error_urlbanner) {
      $TMPL['content'] .= $LNG['join_error']."<br /><br />\n";
      $TMPL['content'] .= $LNG['join_error_forgot']."<br />\n";
      if ($error_url) { $TMPL['content'] .= $LNG['join_error_url']."<br />"; }
      if ($error_email) { $TMPL['content'] .= $LNG['join_error_email']."<br />"; }
      if ($error_title) { $TMPL['content'] .= $LNG['join_error_title']."<br />"; }
      if ($error_password) { $TMPL['content'] .= $LNG['join_error_password']."<br />"; }
      if ($error_urlbanner) { $TMPL['content'] .= $LNG['join_error_urlbanner']." ".$CONFIG['max_banner_width']."x".$CONFIG['max_banner_height']."<br />"; }
      $TMPL['content'] .= "<br />".$LNG['edit_error_back'];
    }
    else {
      $TMPL['id'] = $FORM['id'];
      $TMPL['url'] = $FORM['url'];
      $TMPL['title'] = $FORM['title'];
      $TMPL['description'] = $FORM['description'];
      $TMPL['cat'] = $FORM['cat'];
      $TMPL['urlbanner'] = $FORM['urlbanner'];
      $TMPL['email'] = $FORM['email'];

      if ($FORM['password']) {
        $FORM['password'] = md5($FORM['password']);
        $password_sql = "password = '".$FORM['password']."', ";
      }

      $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_members SET ${password_sql}url = '".$TMPL['url']."', title = '".$TMPL['title']."', description = '".$TMPL['description']."', category = '".$TMPL['cat']."', urlbanner = '".$TMPL['urlbanner']."', email = '".$TMPL['email']."', active = ".$FORM['active']." WHERE id = ".$TMPL['id']);

      $TMPL['content'] = $LNG['edit_success']."<br /><br />\n".$LNG['edit_info_edited'];
    }
  }
}

function delete_review () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['id']) {
    $TMPL['content'] = <<<EndHTML
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="delete_review" />
<b>${LNG['a_del_rev']}</b><br />
${LNG['a_del_rev_id']}: <input name="id" type="text" size="7" /><br />
<input type="submit" value="${LNG['g_form_submit_long']}" />
</form>
EndHTML;
  }
  else {
    $db->Execute("DELETE FROM ".$CONFIG['sql_prefix']."_reviews WHERE review_id = ".$FORM['id']);

    $TMPL['content'] = $LNG['a_del_rev_done'];
  }
}

function email () {
  global $CONFIG, $db, $LNG, $TMPL;

  $TMPL['content'] .= $LNG['a_email_addresses']."<br /><br />\n";
  $result = $db->Execute("SELECT email FROM ".$CONFIG['sql_prefix']."_members");

  while (list($email) = $db->FetchArray($result)) {
    if (!$first_email) { $TMPL['content'] .= "$email"; $first_email = 1; }
    else { $TMPL['content'] .= ", $email"; }
  }
}

function find () {
  global $LNG, $TMPL;

  $TMPL['content'] .= $LNG['a_find_find'];
}

function login () {
  global $admin_password, $CONFIG, $FORM, $LNG, $TMPL;

  $FORM['pass'] = md5($FORM['pass']);

  if ($FORM['pass'] == $admin_password) {
    $session = new Session;
    $TMPL['sid'] = $session->GetID(24);
    $session->SetType('admin');
    $session->Create(1);

    $TMPL['content'] .= $LNG['a_login']."<br /><br />\n";
    $TMPL['content'] .= "<a href=\"admin.php?page=${FORM['nextpage']}&sid=${TMPL['sid']}\"><b>".$LNG['a_login_enter']."</b></a>";
  }
  elseif ($FORM['pass'] != 'd41d8cd98f00b204e9800998ecf8427e') { $TMPL['content'] = $LNG['a_login_invalidpw']; }
  else {
    $TMPL['content'] = <<<EndHTML
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="nextpage" type="hidden" value="${FORM['page']}" />
${LNG['g_password']}: <input name="pass" type="password" size="20" /><br />
<input type="submit" value="${LNG['g_form_submit_long']}" />
</form>
EndHTML;
  }
}

function logout () {
  global $LNG, $TMPL;

  KillSession($TMPL['sid']);

  $TMPL['content'] = $LNG['a_logout'];
}

function main () {
  global $CONFIG, $db, $LNG, $TMPL;

  $result = $db->Execute("SELECT id FROM ".$CONFIG['sql_prefix']."_members WHERE active = 0");
  $num = mysql_num_rows($result);

  $TMPL['content'] = $LNG['a_main']."<br /><br />\n";

  if ($num) { $TMPL['content'] .= "<a href=\"admin.php?page=approve\">".sprintf($LNG['a_main_approve'], $num)."</a>"; }
}

function reset_stats () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['reset']) {
    $TMPL['content'] .= <<<EndHTML
${LNG['a_reset_confirm']}<br /><br />
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="reset" />
<input name="reset" type="hidden" value="1" />
<input type="submit" value="${LNG['a_reset_stats']}" />
</form>
EndHTML;
  }
  else {
    $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_stats SET unq_pv_today = 0, unq_pv_1 = 0, unq_pv_2 = 0, unq_pv_3 = 0,
                                                             tot_pv_today = 0, tot_pv_1 = 0, tot_pv_2 = 0, tot_pv_3 = 0,
                                                             unq_in_today = 0, unq_in_1 = 0, unq_in_2 = 0, unq_in_3 = 0,
                                                             tot_in_today = 0, tot_in_1 = 0, tot_in_2 = 0, tot_in_3 = 0,
                                                             unq_out_today = 0, unq_out_1 = 0, unq_out_2 = 0, unq_out_3 = 0,
                                                             tot_out_today = 0, tot_out_1 = 0, tot_out_2 = 0, tot_out_3 = 0");

    $TMPL['content'] = $LNG['a_reset_done'];
  }
}

function settings () {
  global $CONFIG, $db, $FORM, $LNG, $TMPL;

  if (!$FORM['deflanguage']) {
    $language_array = array();
    $handle = opendir($CONFIG['path']."/languages");
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        $file = str_replace(".php", "", $file);
        array_push($language_array, $file);
      }
    }
    foreach ($language_array as $value) {
      if ($value == $CONFIG['deflanguage']) { $languages .= "<option value=\"$value\" selected=\"selected\">$value\n"; }
      else { $languages .= "<option value=\"$value\">$value\n"; }
    }

    $sql_array = array();
    $handle = opendir($CONFIG['path']."/sources/drivers");
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        $file = str_replace(".php", "", $file);
        array_push($sql_array, $file);
      }
    }
    foreach ($sql_array as $value) {
      if ($value == $CONFIG['sql']) { $sqls .= "<option value=\"$value\" selected=\"selected\">$value\n"; }
      else { $sqls .= "<option value=\"$value\">$value\n"; }
    }

    $rankingmethoda = array('unq_pv', 'tot_pv', 'unq_in', 'tot_in', 'unq_out', 'tot_out');
    foreach ($rankingmethoda as $value) {
      if ($value == $CONFIG['rankingmethod']) { $rankingmethods .= "<option value=\"$value\" selected=\"selected\">".$LNG['g_'.$value]."\n"; }
      else { $rankingmethods .= "<option value=\"$value\">".$LNG['g_'.$value]."\n"; }
    }

    if ($CONFIG['gateway']) { $gateways = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_on']."\n<option value=\"0\">".$LNG['a_s_off']; }
    else { $gateways = "<option value=\"1\">".$LNG['a_s_on']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_off']; }
    if ($CONFIG['search']) { $searchs = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_on']."\n<option value=\"0\">".$LNG['a_s_off']; }
    else { $searchs = "<option value=\"1\">".$LNG['a_s_on']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_off']; }
    if ($CONFIG['daymonth']) { $daymonths = "<option value=\"0\">".$LNG['a_s_days']."\n<option value=\"1\" selected=\"selected\">".$LNG['a_s_months']."\n"; }
    else { $daymonths = "<option value=\"0\"\" selected=selected\">".$LNG['a_s_days']."\n<option value=\"1\">".$LNG['a_s_months']; }
    if ($CONFIG['featured']) { $featureds = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_yes']."\n<option value=\"0\">".$LNG['a_s_no']."\n"; }
    else { $featureds = "<option value=\"1\">".$LNG['a_s_yes']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_no']; }
    if ($CONFIG['active_default']) { $active_defaults = "<option value=\"0\">".$LNG['a_s_yes']."\n<option value=\"1\" selected=\"selected\">".$LNG['a_s_no']."\n"; }
    else { $active_defaults = "<option value=\"0\" selected=\"selected\">".$LNG['a_s_yes']."\n<option value=\"1\">".$LNG['a_s_no']; }
    if ($CONFIG['email_admin_on_join']) { $email_admin_on_joins = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_yes']."\n<option value=\"0\">".$LNG['a_s_no']."\n"; }
    else { $email_admin_on_joins = "<option value=\"1\">".$LNG['a_s_yes']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_no']; }
    if ($CONFIG['ranks_on_buttons']) { $ranks_on_buttonss = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_yes']."\n<option value=\"0\">".$LNG['a_s_no']."\n"; }
    else { $ranks_on_buttonss = "<option value=\"1\">".$LNG['a_s_yes']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_no']; }
    if ($CONFIG['gzip']) { $gzips = "<option value=\"1\" selected=\"selected\">".$LNG['a_s_yes']."\n<option value=\"0\">".$LNG['a_s_no']."\n"; }
    else { $gzips = "<option value=\"1\">".$LNG['a_s_yes']."\n<option value=\"0\" selected=\"selected\">".$LNG['a_s_no']; }
    foreach ($CONFIG['adbreaks'] as $key => $value) {
      if (!$adbreak) { $adbreak = "$key"; }
      else { $adbreak .= ",$key"; }
    }
    foreach ($CONFIG['categories'] as $value) {
      if (!$categories) { $categories = "$value"; }
      else { $categories .= ",$value"; }
    }

    $TMPL['content'] = <<<EndHTML
<table cellspacing="0" cellpadding="0"><tr><td>
<form action="admin.php" method="post">
<input name="sid" type="hidden" value="${TMPL['sid']}" />
<input name="page" type="hidden" value="settings" />
<b>${LNG['a_s_general']}</b><br /><br />
${LNG['a_s_list_name']}<br />
<input name="list_name" type="text" size="50" value="${TMPL['list_name']}" /><br /><br />
${LNG['a_s_deflanguage']}<br />
<select name="deflanguage">
$languages</select><br /><br />
${LNG['a_s_path']}<br />
<input name="path" type="text" size="50" value="${CONFIG['path']}" /><br /><br />
${LNG['a_s_list_url']}<br />
<input name="list_url" type="text" size="50" value="${CONFIG['list_url']}" /><br /><br />
${LNG['a_s_templates_path']}<br />
<input name="templates_path" type="text" size="50" value="${CONFIG['templates_path']}" /><br /><br />
${LNG['a_s_templates_url']}<br />
<input name="templates_url" type="text" size="50" value="${CONFIG['templates_url']}" /><br /><br />
${LNG['a_s_youremail']}<br />
<input name="youremail" type="text" size="50" value="${CONFIG['youremail']}" /><br /><br /><br /><br />

<b>${LNG['a_s_sql']}</b><br /><br />
${LNG['a_s_sql_type']}<br />
<select name="sql">
$sqls</select><br /><br />
${LNG['a_s_sql_host']}<br />
<input name="sql_host" type="text" size="50" value="${CONFIG['sql_host']}" /><br /><br />
${LNG['a_s_sql_database']}<br />
<input name="sql_database" type="text" size="50" value="${CONFIG['sql_database']}" /><br /><br />
${LNG['a_s_sql_user']}<br />
<input name="sql_user" type="text" size="50" value="${CONFIG['sql_user']}" /><br /><br />
${LNG['a_s_sql_pass']}<br />
<input name="sql_pass" type="text" size="50" value="${CONFIG['sql_pass']}" /><br /><br /><br /><br />

<b>${LNG['a_s_ranking']}</b><br /><br />
${LNG['a_s_categories']}<br />
<input name="categories" type="text" size="80" value="$categories" /><br /><br />
${LNG['a_s_numlist']}<br />
<input name="numlist" type="text" size="7" value="${CONFIG['numlist']}" /><br /><br />
${LNG['a_s_daymonth']}<br />
<select name="daymonth">
$daymonths</select><br /><br />
${LNG['a_s_rankingmethod']}<br />
<select name="rankingmethod">
$rankingmethods</select><br /><br />
${LNG['a_s_featured']}<br />
<select name="featured">
$featureds</select><br /><br />
${LNG['a_s_top']}<br />
<input name="top" type="text" size="7" value="${CONFIG['top']}" /><br /><br />
${LNG['a_s_adbreak']}<br />
<input name="adbreak" type="text" size="20" value="$adbreak" /><br /><br /><br /><br />

<b>${LNG['a_s_members']}</b><br /><br />
${LNG['a_s_active_default']}<br />
<select name="active_default">
$active_defaults</select><br /><br />
${LNG['a_s_delete_after']}<br />
<input name="delete_after" type="text" size="7" value="${CONFIG['delete_after']}" /><br /><br />
${LNG['a_s_email_admin_on_join']}<br />
<select name="email_admin_on_join">
$email_admin_on_joins</select><br /><br />
${LNG['a_s_max_banner_width']}<br />
<input name="max_banner_width" type="text" size="7" value="${CONFIG['max_banner_width']}" /><br /><br />
${LNG['a_s_max_banner_height']}<br />
<input name="max_banner_height" type="text" size="7" value="${CONFIG['max_banner_height']}" /><br /><br />
${LNG['a_s_defbanner']}<br />
<input name="defbanner" type="text" size="50" value="${CONFIG['defbanner']}" /><br /><br /><br /><br />

<b>${LNG['a_s_button']}</b><br /><br />
${LNG['a_s_ranks_on_buttons']}<br />
<select name="ranks_on_buttons">
$ranks_on_buttonss</select><br /><br />
${LNG['a_s_button_url']}<br />
<input name="button_url" type="text" size="50" value="${CONFIG['button_url']}" /><br /><br />
${LNG['a_s_button_dir']}<br />
<input name="button_dir" type="text" size="50" value="${CONFIG['button_dir']}" /><br /><br />
${LNG['a_s_button_ext']}<br />
<input name="button_ext" type="text" size="10" value="${CONFIG['button_ext']}" /><br /><br />
${LNG['a_s_button_num']}<br />
<input name="button_num" type="text" size="7" value="${CONFIG['button_num']}" /><br /><br /><br /><br />

<b>${LNG['a_s_searchs']}</b><br /><br />
${LNG['a_s_search_id']}<br />
<input name="search_id" type="text" size="7" value="${CONFIG['search_id']}" /><br /><br />
${LNG['a_s_search']}<br />
<select name="search">
$searchs</select><br /><br />
${LNG['a_s_searchresults']}<br />
<input name="searchresults" type="text" size="20" value="${CONFIG['searchresults']}" /><br /><br /><br /><br />

<b>${LNG['a_s_other']}</b><br /><br />
${LNG['a_s_gzip']}<br />
<select name="gzip">
$gzips</select><br /><br />
${LNG['a_s_timeoffset']}<br />
<input name="timeoffset" type="text" size="20" value="${CONFIG['timeoffset']}" /><br /><br />
${LNG['a_s_gateway']}<br />
<select name="gateway">
$gateways</select><br /><br /><br /><br />
<input type="submit" value="${LNG['g_form_submit_long']}" />
</form>
</td></tr></table>
EndHTML;
  }
  else {
    require_once $CONFIG['path'].'/languages/'.$FORM['deflanguage'].'.php';

    $adbreaks = explode(',', $FORM['adbreak']);
    foreach ($adbreaks as $value) {
      if ($value) {
        if (!$newadbreaks) { $newadbreaks .= "$value => 1"; }
        else { $newadbreaks .= ", $value => 1"; }
      }
    }

    $categories = explode(',', $FORM['categories']);
    foreach ($categories as $value) {
      if ($value) {
        if (!$newcategories) { $newcategories .= "'$value'"; }
        else { $newcategories .= ", '$value'"; }
      }
    }

    $newconfig = <<<EndHTML
<?php
\$TMPL['list_name'] = '${FORM['list_name']}';
\$CONFIG['deflanguage'] = '${FORM['deflanguage']}';
\$CONFIG['path'] = '${FORM['path']}';
\$CONFIG['list_url'] = '${FORM['list_url']}';
\$TMPL['list_url'] = \$CONFIG['list_url'];
\$CONFIG['templates_path'] = '${FORM['templates_path']}';
\$CONFIG['templates_url'] = '${FORM['templates_url']}';
\$TMPL['templates_url'] = \$CONFIG['templates_url'];
\$CONFIG['youremail'] = '${FORM['youremail']}';

\$CONFIG['sql'] = '${FORM['sql']}';
\$CONFIG['sql_host'] = '${FORM['sql_host']}';
\$CONFIG['sql_database'] = '${FORM['sql_database']}';
\$CONFIG['sql_user'] = '${FORM['sql_user']}';
\$CONFIG['sql_pass'] = '${FORM['sql_pass']}';
\$CONFIG['sql_prefix'] = '${CONFIG['sql_prefix']}';

\$CONFIG['categories'] = array($newcategories);
\$CONFIG['numlist'] = ${FORM['numlist']};
\$CONFIG['daymonth'] = ${FORM['daymonth']};
\$CONFIG['rankingmethod'] = '${FORM['rankingmethod']}';
\$CONFIG['featured'] = ${FORM['featured']};
\$CONFIG['top'] = ${FORM['top']};
\$CONFIG['adbreaks'] = array($newadbreaks);

\$CONFIG['active_default'] = ${FORM['active_default']};

\$CONFIG['delete_after'] = ${FORM['delete_after']};
\$CONFIG['email_admin_on_join'] = ${FORM['email_admin_on_join']};
\$CONFIG['max_banner_width'] = ${FORM['max_banner_width']};
\$TMPL['max_banner_width'] = \$CONFIG['max_banner_width'];
\$CONFIG['max_banner_height'] = ${FORM['max_banner_height']};
\$TMPL['max_banner_height'] = \$CONFIG['max_banner_height'];
\$CONFIG['defbanner'] = '${FORM['defbanner']}';

\$CONFIG['ranks_on_buttons'] = ${FORM['ranks_on_buttons']};
\$CONFIG['button_url'] = '${FORM['button_url']}';
\$CONFIG['button_dir'] = '${FORM['button_dir']}';
\$CONFIG['button_ext'] = '${FORM['button_ext']}';
\$CONFIG['button_num'] = ${FORM['button_num']};

\$CONFIG['search_id'] = ${FORM['search_id']};
\$CONFIG['search'] = ${FORM['search']};
\$CONFIG['searchresults'] = ${FORM['searchresults']};

\$CONFIG['gzip'] = ${FORM['gzip']};
\$CONFIG['timeoffset'] = ${FORM['timeoffset']};
\$CONFIG['gateway'] = ${FORM['gateway']};

\$CONFIG['version'] = '4.1.0 (2003-07-29)';
\$TMPL['version'] = \$CONFIG['version'];
?>
EndHTML;

    $file = $CONFIG['path']."/config.php";
    $fh_config = fopen($file, "w");
    fwrite($fh_config, $newconfig);
    fclose($fh_config);
    $TMPL['content'] .= $LNG['a_s_updated'];
  }
}
function version () {
  global $CONFIG, $LNG, $TMPL;

  $latest_version = file_get_contents("http://www.aardvarkind.com/topsitesphp/version.txt");
  $latest_version = rtrim($latest_version);

  if ($CONFIG['version'] == $latest_version) { $TMPL['content'] .= $LNG['a_version_using']; }
  else {
    $whats_new = file_get_contents("http://www.aardvarkind.com/topsitesphp/whatsnew-4.1.0.txt");
    $TMPL['content'] .= $LNG['a_version_your'].": ".$CONFIG['version']."<br />".$LNG['a_version_latest'].": ".$latest_version."<br /><br />\n".$LNG['a_version_new']."<br /><br />\n".$whats_new;
  }
}
?>