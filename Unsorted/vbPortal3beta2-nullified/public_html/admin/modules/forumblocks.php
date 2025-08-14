<?php
/*****************************************************************************
 * Center Blocks System
 *
 * Copyright (c) 2001 wajones (webmaster@phpportals.com)
 * http://phpportals.com/
  
 * Based on Advanced Blocks
 * Copyright (c) 2001 Patrick Kellum (webmaster@quahog-library.com)
 * http://quahog-library.com/
 * Which was based in part of the blocks system in PHP-Nuke
 * Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
 * http://phpnuke.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *****************************************************************************/
if(!eregi('admin.php', $PHP_SELF)) {
	die('Access Denied');
}
$hlpfile = 'manual/advblocks.php';
$result=mysql_query("SELECT usergroupid FROM user WHERE username = '$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper!=6) {
	print 'Access Denied';
	exit;
}

// language
include "admin/language/lang-$language-advblocks.php";
/*********************************************************/
/* Blocks Functions                                      */
/*********************************************************/

function BlocksAdmin() {
	global
		$forumblocks_modules,
		$nuketable,
		$hlpfile,
		$bgcolor1,
		$bgcolor2,
		$bgcolor3,
		$bgcolor4,
		$textcolor1,
		$textcolor2,
		$show_inactive
	;
	include 'header.php';
	GraphicAdmin($hlpfile);
	OpenTable();
	print '<center><font size="4"><b>'._AB_BLOCKSADMIN.'</b></font></center>';
	CloseTable();
	print '<br>';
	/* Blocks */
	$filter_inactive = '';
	if(!$show_inactive) {
		$filter_inactive = 'WHERE active=1';
	}
	OpenTable();
	print '<center><font size="4"><b>Forum Left Column Blocks</b></font>';
	if($show_inactive) {
		print ' <font size="1">[ <a href="admin.php?op=ForumBlocksAdmin">hide inactive</a> ]</font>';
	}
	else {
		print ' <font size="1">[ <a href="admin.php?op=ForumBlocksAdmin&amp;show_inactive=1">show inactive</a> ]</font>';
	}
	print '</center><br>'
		.'<table border="1" width="100%"><tr>'
		."<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._TITLE.'</b></font></td>'
		."<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._POSITION.'</b></font></td>'
		."<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._WEIGHT.'</b></font></td>'
		."<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._TYPE.'</b></font></td>'
	;
	if($show_inactive) {
		print "<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._STATUS.'</b></font></td>';
	}
	print "<td align=\"center\" bgcolor=\"$bgcolor2\"><font size=\"1\" color=\"$textcolor1\" style=\"color:$textcolor1\"><b>"._FUNCTIONS.'</b></font></tr>';
	//
	// block position images
	//
	$position[l] = 'LeftColumn';
	$position[r] = '<img src="images/common/right.gif" alt="'._RIGHTBLOCK.'" hspace="5">';
	//
	// highest weight for each position (better to do it here then to keep doing it in the loop)
	//
	$filter_weight = '';
	if(!$show_inactive) {
		$filter_weight = "AND active=1";
	}
	$high[l] = mysql_fetch_array(mysql_query("SELECT weight FROM nuke_forumblocks WHERE position='l' $filter_weight ORDER BY weight DESC"));
	$high[r] = mysql_fetch_array(mysql_query("SELECT weight FROM nuke_forumblocks WHERE position='r' $filter_weight ORDER BY weight DESC"));
	$result = mysql_query("SELECT bid, bkey, title, url, position, weight, active FROM nuke_forumblocks $filter_inactive ORDER BY position, weight");
	$total_rows = mysql_affected_rows();
	while($row = mysql_fetch_array($result)) {
		if($row[active]) {
			$active = _ACTIVE;
			$active_img = 'images/green_dot.gif';
			$change = _DEACTIVATE;
			$change_img = 'images/red_dot.gif';
		}
		else {
			$active = _INACTIVE;
			$active_img = 'images/red_dot.gif';
			$change = _ACTIVATE;
			$change_img = 'images/green_dot.gif';
		}
		$type = $forumblocks_modules[$row[bkey]][text_type];
		//
		// weight
		//
		$move_up = '';
		$move_down = '';
		$move_space = '';
		if($row[position] == $prev_pos) {
			$move_up = "<a href=\"admin.php?op=ForumBlocksOrder&amp;bid=$row[bid]&amp;position=$row[position]&amp;new_weight=" . ($row[weight] - 1.5) . "\"><img src=\"images/common/up_thin.gif\" border=\"0\" alt=\"" . _BLOCKUP . "\"></a>";
		}
		if($row[weight] != $high[$row[position]][weight]) {
			$move_down = "<a href=\"admin.php?op=ForumBlocksOrder&amp;bid=$row[bid]&amp;position=$row[position]&amp;new_weight=" . ($row[weight] + 1.5) . "\"><img src=\"images/common/down_thin.gif\" border=\"0\" alt=\"" . _BLOCKDOWN . "\"></a>";
		}
		if($row[position] == $prev_pos && $row[weight] != $high[$row[position]][weight]) {
			$move_space = '&nbsp;';
		}
		//
		// start table row
		//
		print "<tr>"
			."<td align=\"left\"><font size=\"2\">$row[title]</font></td>"
			."<td align=\"center\"><font size=\"2\">" . $position[$row[position]] . "</font></td>"
			."<td align=\"center\"><font size=\"2\">$move_up$move_space$move_down</font></td>"
			."<td align=\"center\"><font size=\"2\">$type</font></td>"
		;
		if($show_inactive) {
			print "<td align=\"center\"><img src=\"$active_img\" border=\"0\" alt=\"$active\"></td>";
		}
		//
		// functions
		//
		print "<td align=\"left\"><a href=\"admin.php?op=ForumBlocksEdit&amp;bid=$row[bid]\"><img src=\"images/common/edit.gif\" border=\"0\" alt=\"Edit Block\"></a> <a href=\"admin.php?op=ForumBlocksChangeStatus&amp;bid=$row[bid]\"><img src=\"$change_img\" border=\"0\" alt=\"$change\"></a>";
		if($forumblocks_modules[$row[bkey]][allow_delete]) {
			print " <a href=\"admin.php?op=ForumBlocksDelete&amp;bid=$row[bid]\"><img src=\"images/common/delete.gif\" border=\"0\" alt=\"Delete Block\"></a>";
		}
		print '</td></tr>';
		$prev_pos = $row[position];
	}
	print '</table>';
	CloseTable();
	print '<br>';
	/* Add New block */
	OpenTable();
	print '<center><font size="3"><b>' . _ADDNEWBLOCK . '</b></font></center><br>'
		.'<font size="2">'
		.'<form action="admin.php" method="post">'
		._TITLE . '&nbsp;&nbsp;<input class="textbox" type="text" name="title" size="20" maxlength="75">&nbsp;&nbsp;'
		.'<select name="bkey">'
	;
	foreach($forumblocks_modules as $k=>$v) {
		if($v[allow_create]) {
			print "<option name=\"bkey\" value=\"$k\">$v[text_type_long]</option>\n";
		}
	}
	print '</select>&nbsp;&nbsp;'
		.'<input type="submit" value="' . _CREATEBLOCK . '">'
		.'<input type="hidden" name="op" value="ForumBlocksAdd"></form>'
		.'</font>'
	;
	CloseTable();
	include 'footer.php';
}

function BlocksAdd() {
	global
		$forumblocks_modules,
		$nuketable,
		$HTTP_POST_VARS
	;
	$vars = $HTTP_POST_VARS;
	$high = mysql_fetch_array(mysql_query("SELECT weight FROM nuke_forumblocks WHERE position='l' ORDER BY weight DESC"));
	$vars[title] = stripslashes(FixQuotes($vars[title]));
	$vars[content] = stripslashes(FixQuotes($vars[content]));
	$vars[active] = 1;
    $vars[templates] = 1;
	$vars[position] = 'l';
	$vars[weight] = $high[weight] + 1;
	$vars[refreash] = 3600;
	// let the module do any block-specific changes before adding
	if($forumblocks_modules[$vars[bkey]][func_add]) {
		$vars = $forumblocks_modules[$vars[bkey]][func_add]($vars);
	}
	mysql_query("INSERT INTO nuke_forumblocks (bid, bkey, title, content, url, position, weight, active, templates, refresh, last_update) VALUES (NULL, '$vars[bkey]', '$vars[title]', '$vars[content]', '$vars[url]', 'l', '$vars[weight]', '$vars[active]', '$vars[templates]', '$vars[refresh]', 0)");
	$bid = mysql_insert_id();
	header("Location: admin.php?op=ForumBlocksEdit&bid=$bid");
}

function BlocksEdit($bid) {
	global
		$forumblocks_modules,
		$nuketable,
		$hlpfile,
		$bgcolor2,
		$bgcolor4
	;
	include 'header.php';
	GraphicAdmin($hlpfile);
	OpenTable();
	print '<center><font size="4"><b>' . _EDITBLOCK . '</b></font></center>';
	CloseTable();
	print '<br>';
	// $text_pos[l] = _LEFT;
	// $text_pos[r] = _RIGHT;
	$result = mysql_query("SELECT bid, bkey, title, content, url, position, weight, active, refresh, templates, last_update from nuke_forumblocks WHERE bid='$bid'");
	$row = mysql_fetch_array($result);
	$position[$row[position]] = ' selected';
	$active[$row[active]] = ' checked';
    $templates[$row[templates]] = ' checked';
	$refresh[$row[refresh]] = ' selected';
	OpenTable();
	print '<center><font size="3"><b>' . _BLOCK . ": $row[title] (" . $forumblocks_modules[$row[bkey]][text_content] . ")</b></font></center><br><br>"
		.'<form action="admin.php" method="post">'
		.'<table border="0" width="100%">'
		.'<tr><td>' . _TITLE . ':</td><td><input type="text" name="title" size="30" maxlength="60" value="' . htmlentities($row[title]) . '">&nbsp;&nbsp;' . $type . '</td></tr>'
	;
	if($forumblocks_modules[$row[bkey]][form_url]) {
		if($forumblocks_modules[$row[bkey]][url_text]) {
			$url_text = $forumblocks_modules[$row[bkey]][url_text];
		}
		else {
			$url_text = _AB_LABEL_URL;
		}
		if($forumblocks_modules[$row[bkey]][url_size]) {
			$url_size = $forumblocks_modules[$row[bkey]][url_size];
		}
		else {
			$url_size = 30;
		}
		if($forumblocks_modules[$row[bkey]][url_maxlength]) {
			$url_maxlength = $forumblocks_modules[$row[bkey]][url_maxlength];
		}
		else {
			$url_maxlength = 255;
		}
		switch($forumblocks_modules[$row[bkey]][url_type]) {
			default:
			case 'text':
				$row[url] = htmlentities($row[url]);
				print "<tr><td>$url_text:</td><td><input type=\"text\" name=\"url\" size=\"$url_size\" maxlength=\"$url_maxlength\" value=\"$row[url]\">";
				break;
			case 'checkbox':
				$check_url[$row[url]] = ' checked';
				print "<tr><td>$url_text:</td><td><input type=\"checkbox\" name=\"url\" value=\"1\"$check_url[1]>";
				break;
           	case 'select':
				$select_url[$row[url]] = ' selected';
				print "<tr><td>$url_text:</td><td><select name=\"url\" size=\"1\">";
				foreach($forumblocks_modules[$row[bkey]][url_values] as $k=>$v) {
					$sel = $select_row[$k];
					print "<option value=\"$k\"$sel>$v</option>\n";
				}
				print '</select>';
				break;
           
		}
		// rss-specific code, will be moved to the block type file eventually
		if($row[bkey] == 'rss') {
			$res2 = mysql_query("SELECT * FROM $nuketable[advheadlines] ORDER BY sitename,id");
			print '&nbsp;&nbsp;<select name="rssurl" size="1">';
			if($row[url]) {
				print "<option value=\"\" selected><--- Current</option>";
			}
			else {
				print "<option value=\"\" selected>"._CUSTOM.'</option>';
			}
			while($row2 = mysql_fetch_array($res2)) {
				print "<option value=\"$row2[rssurl]\">$row2[sitename]</option>\n";
			}
			print '</select> [ <a href="admin.php?op=AdvHeadlinesAdmin">Setup</a> ]';
		}
		print '</td></tr>';
	}
	if($forumblocks_modules[$row[bkey]][form_content]) {
		print '<tr><td>' . _CONTENT . ':</td><td><textarea name="content" cols="50" rows="10" wrap="soft">' . htmlentities($row[content]) . '</textarea><br><font size="1">' . _IFRSSWARNING . '</font></td></tr>';
	}
	// print '<tr><td>' . _POSITION . ':</td><td><select name="position">'
	//	."<option name=\"position\" value=\"l\"$position[l]>$text_pos[l]</option>"
	//	."<option name=\"position\" value=\"r\"$position[r]>$text_pos[r]</option>"
	//	.'</select></td></tr>'
	// ;
	print '<tr><td>' . _WEIGHT . ':</td><td><select name="weight" size="1">';
	print "<option value=\"$row[weight]\" selected>Current</option>\n";
	print "<option value=\"0.5\">Top</option>\n";
	$result = mysql_query("SELECT title, weight, position FROM nuke_forumblocks WHERE bid!=$bid ORDER BY position, weight");
	while($weight_row = mysql_fetch_array($result)) {
		print "<option value=\"" . ($weight_row[weight] + 0.5) . "\">After: $weight_row[title] (" . $text_pos[$weight_row[position]] . ")</option>\n";
	}
	print '</td></tr>';
	print '<tr><td>' . _ACTIVATE2 . '</td><td><input type="checkbox" name="active" value="1"' . $active[1] . '></td></tr>';
    if($forumblocks_modules[$row[bkey]][has_templates]) {
		print '<tr><td>' . _TEMPLATES . '</td><td><input type="checkbox" name="templates" value="1"' . $templates[1] . '></td></tr>';
	}
	if($forumblocks_modules[$row[bkey]][form_refresh]) {
		print '<tr><td>' . _REFRESHTIME . ':</td><td><select name="refresh">'
			.'<option name="refresh" value="1800"' . $refresh[1800] . '>1/2 ' . _HOUR . '</option>'
			.'<option name="refresh" value="3600"' . $refresh[3600] . '>1 ' . _HOUR . '</option>'
			.'<option name="refresh" value="18000"' . $refresh[18000] . '>5 ' . _HOURS . '</option>'
			.'<option name="refresh" value="36000"' . $refresh[36000] . '>10 ' . _HOURS . '</option>'
			.'<option name="refresh" value="86400"' . $refresh[86400] . '>24 ' . _HOURS . '</option>'
			.'</select>'
			.'</td></tr>'
		;
	}
	print '</table>'
		.'<input type="hidden" name="bid" value="' . $row[bid] . '">'
		.'<input type="hidden" name="bkey" value="' . $row[bkey] . '">'
		.'<input type="hidden" name="op" value="ForumBlocksEditSave">'
		.'<input type="submit" value="' . _SAVEBLOCK . '"></form>'
	;
	CloseTable();
	include 'footer.php';
}

function BlocksEditSave() {
	global
		$forumblocks_modules,
		$nuketable,
		$HTTP_POST_VARS
	;
	$vars = $HTTP_POST_VARS;
	$result = mysql_query("SELECT weight FROM nuke_forumblocks WHERE bid='$vars[bid]'");
	$row = mysql_fetch_array($result);
	$vars[title] = stripslashes(FixQuotes($vars[title]));
	$vars[content] = stripslashes(FixQuotes($vars[content]));
	// let the module do any block-specific changes before updating
	if($forumblocks_modules[$vars[bkey]][func_update]) {
		$vars = $forumblocks_modules[$vars[bkey]][func_update]($vars);
	}
	mysql_query("UPDATE nuke_forumblocks SET title='$vars[title]', content='$vars[content]', url='$vars[url]', position='l', weight='$vars[weight]', active='$vars[active]', templates='$vars[templates]', refresh='$vars[refresh]', last_update=0 WHERE bid='$vars[bid]'");
	if($vars[weight] == $row[weight]) {
		header('Location: admin.php?op=ForumBlocksAdmin');
	}
	else {
		BlocksOrder($vars);
	}
}

function ChangeStatus($bid, $ok=0) {
	global
		$forumblocks_modules,
		$nuketable
	;
	$result = mysql_query("SELECT active from nuke_forumblocks WHERE bid='$bid'");
	$row = mysql_fetch_array($result);
	if($ok || $row[active]) {
		if($row[active] == 0) {
			$active = 1;
		}
		else {
			$active = 0;
		}
		mysql_query("UPDATE nuke_forumblocks SET active='$active' WHERE bid='$bid'");
		header('Location: admin.php?op=ForumBlocksAdmin');
		print '<html><body></body></html>';
		exit;
	}
	$result = mysql_query("SELECT * FROM nuke_forumblocks WHERE bid='$bid'");
	$row = mysql_fetch_array($result);
	include 'header.php';
	GraphicAdmin($hlpfile);
	print '<br>';
	OpenTable();
	print '<center><font size="3"><b>' . _BLOCKACTIVATION . '</b></font></center>';
	CloseTable();
	print '<br>';
	OpenTable();
	if($forumblocks_modules[$row[bkey]][show_preview]) {
		print '<center>' . _BLOCKPREVIEW . ' <i>' . $row[title] . '</i><br><br>';
		if(function_exists('nukecode')) {
			if($forumblocks_modules[$row[bkey]][support_nukecode]) {
				$row[content] = nukecode($row[content]);
			}
		}
		$forumblocks_modules[$row[bkey]][func_display]($row);
	}
	else {
		print '<center><i>' . $title . '</i><br><br>';
	}
	print '<br>' . _WANT2ACTIVATE . '<br><br>'
		.'[ <a href="admin.php?op=ForumBlocksAdmin">' . _NO . '</a> | <a href="admin.php?op=ForumBlocksChangeStatus&amp;bid=' . $bid . '&amp;ok=1">' . _YES . '</a> ]'
		.'</center>'
	;
	CloseTable();
	include 'footer.php';
}

function BlocksDelete($bid, $ok=0) {
	global
		$forumblocks_modules,
		$nuketable
	;
	$result = mysql_query("SELECT * FROM nuke_forumblocks WHERE bid='$bid'");
	$row = mysql_fetch_array($result);
	if(!$forumblocks_modules[$row[bkey]][allow_delete]) {
		header('Location: admin.php?op=ForumBlocksAdmin');
		print '<html><body></body></html>';
		exit;
	}
	if($ok) {
		mysql_query("DELETE from nuke_forumblocks WHERE bid='$bid'");
		header('Location: admin.php?op=ForumBlocksAdmin');
		print '<html><body></body></html>';
		exit;
	}
	include 'header.php';
	GraphicAdmin($hlpfile);
	OpenTable();
	print '<center><font size="4"><b>' . _AB_BLOCKSADMIN . '</b></font></center>';
	CloseTable();
	print '<br>';
	OpenTable();
	print '<center>' . _ARESUREDELBLOCK . ' <i>' . $row[title] . '</i>?<br><br>';
	if($forumblocks_modules[$row[bkey]][show_preview]) {
		if(function_exists('nukecode')) {
			if($forumblocks_modules[$row[bkey]][support_nukecode]) {
				$row[content] = nukecode($row[content]);
			}
		}
		$forumblocks_modules[$row[bkey]][func_display]($row);
	}
	print '<br>[ <a href="admin.php?op=ForumBlocksAdmin">' . _NO . '</a> | <a href="admin.php?op=ForumBlocksDelete&amp;bid=' . $bid . '&amp;ok=1">' . _YES . '</a> ]</center>';
	CloseTable();
	include 'footer.php';
}

function BlocksOrder($vars) {
	global
		$forumblocks_modules,
		$nuketable
	;
	if($vars[new_weight]) {
		mysql_query("UPDATE nuke_forumblocks SET weight='$vars[new_weight]' WHERE bid=$vars[bid]");
	}
	$result = mysql_query("SELECT bid FROM nuke_forumblocks WHERE position='$vars[position]' ORDER BY weight");
	$c = 0;
	while($row = mysql_fetch_array($result)) {
		$c++;
		mysql_query("UPDATE nuke_forumblocks SET weight='$c' WHERE bid=$row[bid]");
	}
	header('Location: admin.php?op=ForumBlocksAdmin');
	print '<html><body></body></html>';
	exit;
}

function HeadlinesAdmin() {
	global
		$hlpfile,
		$nuketable,
		$bgcolor1,
		$bgcolor2,
		$textcolor1,
		$textcolor2
	;
	include 'header.php';
	GraphicAdmin($hlpfile);
	OpenTable();
	print '<center><font size="4"><b>'._HEADLINESADMIN.'</b></font></center>';
	CloseTable();
	print '<br>';
	OpenTable();
	print '<form action="admin.php" method="post">'
		.'<table border="1" width="100%" align="center"><tr>'
		."<td bgcolor=\"$bgcolor2\" align=\"center\"><font size=\"2\" color=\"$textcolor1\"><b>"._SITENAME.'</b></font></td>'
		."<td bgcolor=\"$bgcolor2\" align=\"center\"><font size=\"2\" color=\"$textcolor1\"><b>"._URL.'</b></font></td>'
		."<td bgcolor=\"$bgcolor2\" align=\"center\"><font size=\"2\" color=\"$textcolor1\"><b>"._FUNCTIONS.'</b></font></td><tr>'
	;
	$result = mysql_query("SELECT id,sitename,rssurl,siteurl FROM $nuketable[advheadlines] ORDER BY sitename,id");
	while($row = mysql_fetch_array($result)) {
		if($row[siteurl]) {
			print "<td bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$row[siteurl]\" target=\"_blank\"><font size=\"2\" color=\"$textcolor2\">$row[sitename]</font></a></td>";
		}
		else {
			print "<td bgcolor=\"$bgcolor1\" align=\"center\"><font size=\"2\" color=\"$textcolor2\">$row[sitename]</font></td>";
		}
		print "<td bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$row[rssurl]\" target=\"_blank\"><font size=\"2\" color=\"$textcolor2\">$row[rssurl]</font></a></td>"
			."<td bgcolor=\"$bgcolor1\" align=\"center\">[ <a href=\"admin.php?op=AdvHeadlinesEdit&amp;id=$row[id]\">"._EDIT."</a> | <a href=\"admin.php?op=AdvHeadlinesDel&amp;id=$row[id]&amp;ok=0\">"._DELETE.'</a> ]</td><tr>'
		;
	}
	print '</form></td></tr></table>';
	CloseTable();
	print '<br>';
	OpenTable();
	print '<font size=\"3\"><b>'._ADDHEADLINE.'</b></font><br><br>'
		.'<form action="admin.php" method="post">'
		.'<table border="0" width="100%"><tr><td>'
		._SITENAME.':</td><td><input type="text" name="sitename" size="31" maxlength="60"></td></tr><tr><td>'
		.'Site URL:</td><td><input type="text" name="siteurl" size="50" maxlength="255"></td></tr><tr><td>'
		._RSSFILE.':</td><td><input type="text" name="rssurl" size="50" maxlength="255"></td></tr><tr><td>'
		.'</td></tr></table>'
		.'<input type="hidden" name="op" value="AdvHeadlinesAdd">'
		.'<input type="submit" value="'._ADD.'">'
		.'</form>'
	;
	CloseTable();
	include 'footer.php';
}

function HeadlinesEdit($id) {
	global
		$hlpfile,
		$nuketable
	;
	include 'header.php';
	GraphicAdmin($hlpfile);
	OpenTable();
	print '<center><font size="4"><b>'._HEADLINESADMIN.'</b></font></center>';
	CloseTable();
	print '<br>';
	$row = mysql_fetch_array(mysql_query("SELECT * FROM $nuketable[advheadlines] WHERE id='$id'"));
	$row[sitename] = htmlentities($row[sitename]);
	OpenTable();
	print '<center><font size="3"><b>'._EDITHEADLINE.'</b></font></center>'
		.'<form action="admin.php" method="post">'
		."<input type=\"hidden\" name=\"id\" value=\"$id\">"
		.'<table border="0" width="100%"><tr><td>'
		._SITENAME.":</td><td><input type=\"text\" name=\"sitename\" size=\"31\" maxlength=\"60\" value=\"$row[sitename]\"></td></tr><tr><td>"
		."Site URL:</td><td><input type=\"text\" name=\"siteurl\" size=\"50\" maxlength=\"255\" value=\"$row[siteurl]\"></td></tr><tr><td>"
		._RSSFILE.":</td><td><input type=\"text\" name=\"rssurl\" size=\"50\" maxlength=\"255\" value=\"$row[rssurl]\"></td></tr><tr><td>"
		.'</select></td></tr></table>'
		.'<input type="hidden" name="op" value="AdvHeadlinesSave">'
		.'<input type="submit" value="'._SAVECHANGES.'">'
		.'</form>'
	;
	CloseTable();
	include 'footer.php';
}

function HeadlinesAdd($vars) {
	global
		$nuketable
	;
	$vars[sitename] = addslashes($vars[sitename]);
	mysql_query("INSERT INTO $nuketable[advheadlines] (id,sitename,rssurl,siteurl) VALUES (NULL,'$vars[sitename]','$vars[rssurl]','$vars[siteurl]')");
	header("Location: admin.php?op=AdvHeadlinesAdmin");
}

function HeadlinesSave() {
	global
		$nuketable
	;
	$vars = $GLOBALS[HTTP_POST_VARS];
	$vars[sitename] = addslashes($vars[sitename]);
	mysql_query("UPDATE $nuketable[advheadlines] SET sitename='$vars[sitename]',rssurl='$vars[rssurl]',siteurl='$vars[siteurl]' WHERE id='$vars[id]'");
	header("Location: admin.php?op=AdvHeadlinesAdmin");
}

function HeadlinesDel($id, $ok=false) {
	global
		$nuketable
	;
	if($ok) {
		mysql_query("DELETE FROM $nuketable[advheadlines] WHERE id=$id");
		header("Location: admin.php?op=AdvHeadlinesAdmin");
	}
	else {
		include 'header.php';
		GraphicAdmin($hlpfile);
		OpenTable();
		print '<center><br>'
			.'<font size="3">'
			.'<b>'._SURE2DELHEADLINE.'</b></font><br><br>'
			."[ <a href=\"admin.php?op=AdvHeadlinesDel&amp;id=$id&amp;ok=1\">"._YES.'</a> | <a href="admin.php?op=AdvHeadlinesAdmin">'._NO.'</a> ]<br><br>'
		;
		CloseTable();
		include 'footer.php';
	}
}

$vars = $HTTP_GET_VARS;
switch($op) {
	case 'ForumBlocksAdmin':
		BlocksAdmin();
		break;
	case 'ForumBlocksAdd':
		BlocksAdd();
		break;
	case 'ForumBlocksEdit':
		BlocksEdit($bid);
		break;
	case 'ForumBlocksEditSave':
		BlocksEditSave();
		break;
	case 'ForumBlocksChangeStatus':
		ChangeStatus($bid, $ok);
		break;
	case 'ForumBlocksDelete':
		BlocksDelete($bid, $ok);
		break;
	case 'ForumBlocksOrder':
		BlocksOrder($vars);
		break;
	case 'AdvHeadlinesAdmin':
		HeadlinesAdmin();
		break;
	case 'AdvHeadlinesEdit':
		HeadlinesEdit($vars[id]);
		break;
	case 'AdvHeadlinesAdd':
		HeadlinesAdd($HTTP_POST_VARS);
		break;
	case 'AdvHeadlinesSave':
		HeadlinesSave();
		break;
	case 'AdvHeadlinesDel':
		HeadlinesDel($vars[id],$vars[ok]);
		break;
}
?>
