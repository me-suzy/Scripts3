<?php
// ++=========================================================================++
// || vBadvanced CMPS 1.0.0                                                   ||
// || © 2003-2004 vBadvanced.com & PlurPlanet, LLC - All Rights Reserved      ||
// || This file may not be redistributed in whole or significant part.        ||
// || http://vbadvanced.com                                                   ||
// || Nullified by GTT                                                        ||
// ++ ========================================================================++

error_reporting(E_ALL & ~E_NOTICE);
define('NO_REGISTER_GLOBALS', 1);

require_once('./global.php');
require_once('./includes/adminfunctions_template.php');
require_once('./includes/adminfunctions_language.php');

$scriptname = 'CMPS';
$version = '1.0.0';
$scriptfile = 'vbacmps_install.php';

print_cp_header('vBadvanced ' . $scriptname . ' Install', '', '<style type="text/css">
<!--
.redalert
{
	background: #FF0000;
	color: #000000;
}
-->
</style>');

if (empty($_REQUEST['do']))
{
  $_REQUEST['do'] = 'home';
}

// ##################### Make Template Function #####################
function insert_template($name, $content)
{
  global $DB_site;

	$template = compile_template($content);

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "template WHERE title = '$name' AND styleid = '-1'");

  $DB_site->query("INSERT INTO " . TABLE_PREFIX . "template (templateid, title, template, template_un, styleid, templatetype, dateline, username, version) VALUES (NULL, '$name', '" . addslashes($template) . "', '" . addslashes($content) ."', '-1', 'template', '" . time() . "', 'vBadvanced', '3.0.1')");

	echo '<span class="smallfont">Created Template - ' . $name . '</span><br />';
}

// ##################### Update Template Function #####################
function update_template($name, $content)
{
  global $DB_site;

	$template = compile_template($content);

  $DB_site->query("UPDATE " . TABLE_PREFIX . "template SET title = '$name', template = '" . addslashes($template) ."', template_un = '" . addslashes($content) . "' WHERE title = '$name'");

	echo '<span class="smallfont">Updated Template - ' . $name . '</font><br />';
}

// ##################### Delete Template Function #####################
function kill_template($title)
{
	global $DB_site;
	
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "template WHERE title = '$title'");
	echo '<span class="smallfont">Removed template <b>' . $title . '</b></span><br />';
}

// ##################### Insert Settings Function #####################
function insert_setting($title, $description, $varname, $value, $defaultvalue, $optioncode, $displayorder, $grouptitle, $phraseonly = 0)
{
	global $DB_site;

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (languageid, varname, text, phrasetypeid) VALUES (0, 'setting_" . $varname . "_title', '" . addslashes($title) ."', 5000)");
	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (languageid, varname, text, phrasetypeid) VALUES (0,  'setting_" . $varname . "_desc', '" . addslashes($description) . "', 5000)");

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_setting (varname, grouptitle, value, defaultvalue, optioncode, displayorder) VALUES ('$varname', '$grouptitle', '$value', '$defaultvalue', '" . addslashes($optioncode) . "', '$displayorder')");

	echo '<span class="smallfont">Added Setting - <b>' . $title . '</b></span><br />';
}

// ##################### Update Settings Function #####################
function update_setting($varname, $title = '', $description = '', $value = '', $defaultvalue = '', $optioncode = '', $displayorder = '')
{
  global $DB_site;

	if ($title)
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '" . addslashes($title) . "' WHERE varname = 'setting_" . $varname . "_title'");
	}
	if ($description)
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '" . addslashes($description) . "' WHERE varname = 'setting_" . $varname . "_desc'");
	}

	if ($value OR $defaultvalue OR $optioncode OR $displayorder)
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_setting SET " . 
				iif($value, "value = '" . $value . "'") . 
				iif($defaultvalue, "defaultvalue = '" . $defaultvalue . "'") . 
				iif($optioncode, "optioncode = '" . $optioncode . "'") . 
				iif($displayorder, "displayorder = '" . $displayorder . "'") . 
				" WHERE varname = '$varname'");
	}

	echo '<span class="smallfont">Updated Setting - ' . $varname . iif(isset($value), ' set value = <b>' . $value . '</b>') . '</span><br />';
}

// ##################### Delete Setting Function #####################
function kill_setting($title)
{
  global $DB_site;

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = 'setting_" . $title . "_title'");
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = 'setting_" . $title . "_desc'");
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_setting WHERE varname = '$title'");
	echo '<span class="smallfont">Removed Setting - <b>' . $title . '</b></span><br />';
}

// ##################### Insert Phrase Function #####################
function insert_phrase($varname, $text, $type)
{
	global $DB_site;

	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = '$varname' AND phrasetypeid = '$type' AND languageid = '0'");

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrase (phraseid, languageid, varname, text, phrasetypeid) VALUES (NULL, '0', '" . $varname . "', '" . addslashes($text) ."', '$type')");
	echo '<span class="smallfont">Added Phrase - <b>' . $varname . '</b></span><br />';
}

// ##################### Update Phrase Function #####################
function update_phrase($varname, $text)
{
	global $DB_site;
	
	$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET text = '" . addslashes($text) ."' WHERE varname = '$varname'");
	echo '<span class="smallfont">Updated Phrase - ' . $varname . '</span><br />';
}

// ##################### Delete Phrase Function #####################
function kill_phrase($varname, $type)
{
	global $DB_site;
	$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE varname = '$varname' AND phrasetypeid = '$type'");
	echo '<font size="1">Removed phrase <b>' . $varname . '</b></font><br />';
}

// ####################### Templates ################################
$maintemplates = array(
'adv_portal' => 
'$stylevar[htmldoctype]
<html dir="$stylevar[textdirection]" lang="$stylevar[languagecode]">
<head>
<title>$vboptions[hometitle]</title>

$headinclude

</head>
<body>

$header

$navbar

<table align="center" class="page" cellspacing="0" cellpadding="0"width="100%">
<tr valign="top">

<if condition="$show[\'left_column\']">

<td width="$vba_options[portal_leftcolwidth]">

$home[leftblocks]
	
</td>

<!-- Spacer Cell -->
<td width="$vba_options[portal_colspacing]"><img alt="" src="$vboptions[bburl]/$vboptions[cleargifurl]" width="$vba_options[portal_colspacing]" /></td>
<!-- / Spacer Cell -->

</if>


<if condition="$show[\'center_column\']">
<td valign="top">

$home[centerblocks]	

</td>
</if>


<if condition="$show[\'right_column\']">	

<!-- Spacer Cell -->
<td width="$vba_options[portal_colspacing]"><img alt="" src="$vboptions[bburl]/$vboptions[cleargifurl]" width="$vba_options[portal_colspacing]" /></td>
<!-- / Spacer Cell -->

<td valign="top" width="$vba_options[portal_rightcolwidth]">

$home[rightblocks]

</td>
</if>

</tr>
</table>

$footer

</body>
</html>',

'adv_portal_buddylist' => 
'<!-- Buddy List -->

	<if condition="$bbuserinfo[\'userid\'] AND $bbuserinfo[\'buddylist\']">
		<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
		<thead>
		<tr>
		<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] <a href="$vboptions[bburl]/profile.php?$session[sessionurl]do=editlist">$vbphrase[online_buddies]</a></strong></span>
		</td>
		</tr>
		</thead>
		<tr>
		<td class="$getbgrow" width="100%">
		$onlineusers
		</td>
		</tr>
		</table>
		<br />
	</if>
	
<!-- End Buddy List -->',

'adv_portal_buddylistbits' => 
'<div class="smallfont" style="margin-bottom:2px">
<span style="float:$stylevar[right]">
<a href="$vboptions[bburl]/private.php?$session[sessionurl]do=newpm&amp;u=$loggedin[userid]" title="<phrase 1="$loggedin[username]">$vbphrase[send_private_message_to_x]</phrase>">$vbphrase[pm]</a>
<a href="$vboptions[bburl]/profile.php?$session[sessionurl]do=removelist&amp;userlist=buddy&amp;u=$loggedin[userid]" title="$vbphrase[remove_from_buddy_list]">X</a>
</span>
<a href="$vboptions[bburl]/member.php?$session[sessionurl]u=$loggedin[userid]">$loggedin[username]</a> $loggedin[invisiblemark]
</div>',

'adv_portal_calendar' => 
'<!-- Mini Calendar -->
	
<table align="center" border="0" cellpadding="4" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
			
	$calendarbits

</table>
<br />
	
<!-- End Mini Calendar -->',

'adv_portal_footer' => 
'<!-- Do NOT remove this copyright notice. Doing so is a violation of your user agreement! -->

<div align="center" class="smallfont"><phrase 1="$vba_options[portal_version]">$vbphrase[powered_by_vbadvanced_cmps]</phrase></div>',

'adv_portal_latesttopicbits' => 
'<if condition="$mods[\'modcol\'] == 1">

	<tr>
		<if condition="$vba_options[\'portal_threads_showicon\']">
			<td class="alt2"><if condition="$show[\'threadicon\']"><img alt="" border="0" src="$thread[threadiconpath]" title="$thread[threadicontitle]" /></if></td>
		</if>
		
		<td class="alt1">
			<if condition="$thread[\'subscribed\']">
				<span style="float:$stylevar[right]"><img alt="" class="inlineimg" src="$stylevar[imgdir_misc]/subscribed.gif" title="$vbphrase[you_are_subscribed_to_this_thread]" /></span>
			</if>
		<b><a href="$vboptions[bburl]/showthread.php?t=$thread[threadid]" title="$thread[preview]">$thread[title]</a></b>

			<if condition="$thread[\'rating\']">
				<span style="float:$stylevar[right]"><img alt="" src="$stylevar[imgdir_rating]/rating_$thread[rating].gif" title="<phrase 1="$thread[votenum]" 2="$thread[voteavg]">$vbphrase[thread_rating_x_votes_y_average]</phrase>" /></span>
			</if>
		
		<div class="smallfont"><span style="cursor:pointer" onclick="window.open(\'$vboptions[bburl]/member.php?$session[sessionurl]u=$thread[postuserid]\')">$thread[postusername]</span>

		</div>
		</td>
		<if condition="$show[\'lastpost\']">
			<td class="alt2">
				<div class="smallfont" style="text-align:right; white-space:nowrap">$thread[lastpostdate] <span class="time">$thread[lastposttime]</span><br /><phrase 1="$vboptions[bburl]/member.php?$session[sessionurl]find=lastposter&amp;t=$thread[threadid]" 2="$thread[lastposter]">$vbphrase[by_x]</phrase> <a href="$vboptions[bburl]/showthread.php?$session[sessionurl]goto=lastpost&amp;t=$thread[threadid]"><img alt="" border="0" src="$stylevar[imgdir_button]/lastpost.gif" title="$vbphrase[go_to_last_post]" /></a></div>
			</td>
		</if>
		<td align="center" class="<if condition="$show[\'lastpost\']">alt1<else />alt2</if>"><span class="smallfont">$thread[replycount]</span></td>

		<td align="center" class="<if condition="$show[\'lastpost\']">alt2<else />alt1</if>"><span class="smallfont">$thread[views]</span></td>
			<if condition="$vba_options[\'portal_threads_showforum\']">
				<td class="<if condition="$show[\'lastpost\']">alt1<else />alt2</if>"><span class="smallfont"><a href="$vboptions[bburl]/forumdisplay.php?$session[sessionurl]f=$thread[forumid]">$thread[forumtitle]</a></span></td>
			</if>		
	</tr>

<else />

	<tr>
		<td class="$getbgrow">
			<if condition="$show[\'threadicon\']">
				<img alt="" src="$thread[threadiconpath]" border="0" title="$thread[threadicontitle]" />
			</if>
			<if condition="$thread[\'subscribed\']">
				<img alt="" class="inlineimg" src="$stylevar[imgdir_misc]/subscribed.gif" title="$vbphrase[you_are_subscribed_to_this_thread]" />
			</if>
			<span class="smallfont"><strong><a href="$vboptions[bburl]/showthread.php?t=$thread[threadid]" title="<if condition="$thread[preview]">$thread[preview]

</if>$vbphrase[by] $thread[postusername] <if condition="$vba_options[\'portal_threads_showdate\']">$thread[postdate] $thread[posttime]</if>">$thread[title]</a></strong></span>

			<if condition="$thread[\'rating\']">
				<div style="margin-top:4px"><img alt="" src="$stylevar[imgdir_rating]/rating_$thread[rating].gif" title="<phrase 1="$thread[votenum]" 2="$thread[voteavg]">$vbphrase[thread_rating_x_votes_y_average]</phrase>" /></div>
			</if>
			<if condition="$show[\'lastpost\']">
				<div class="smallfont" style="margin-top:4px"><a href="$vboptions[bburl]/showthread.php?$session[sessionurl]goto=lastpost&amp;t=$thread[threadid]"><img alt="" border="0" src="$stylevar[imgdir_button]/lastpost.gif" title="$vbphrase[go_to_last_post]" /></a> $vbphrase[last_post_by] <a href="$vboptions[bburl]/member.php?find=lastposter&amp;t=$thread[threadid]">$thread[lastposter]</a></div>
			</if>
			<div class="smallfont">$thread[lastpostdate] <span class="time">$thread[lastposttime]</span></div>
			<if condition="$vba_options[\'portal_threads_showforum\']">
				<div class="smallfont"><a href="$vboptions[bburl]/forumdisplay.php?$session[sessionurl]f=$thread[forumid]">$thread[forumtitle]</a></div>
			</if>
		<div class="smallfont">$thread[replycount] $vbphrase[replies], $thread[views] $vbphrase[views]</div></td>
	</tr>
</if>',

'adv_portal_latesttopics' => 
'<!-- Latest Threads -->

<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder"width="100%">
	<tr>
		<td <if condition="$mods[\'modcol\'] == 1">colspan="6"</if> class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] $vbphrase[latest_forum_topics]</strong></span></td>
	</tr>
	<if condition="$mods[\'modcol\'] == 1">
	<tr>
		<if condition="$vba_options[\'portal_threads_showicon\']">
			<td class="thead" width="5">&nbsp;</td>
		</if>
		<td class="thead" width="100%"><span class="smallfont">$vbphrase[title_username_date]</span></td>
		<if condition="$show[\'lastpost\']">		
			<td align="center" class="thead" nowrap="nowrap" width="25%"><span class="smallfont">$vbphrase[last_post]</span></td>
		</if>
		<td class="thead" width="15"><span class="smallfont">$vbphrase[replies]</span></td>
		<td class="thead" width="15"><span class="smallfont">$vbphrase[views]</span></td>
		<if condition="$vba_options[\'portal_threads_showforum\']">
			<td align="center" class="thead" width="10%"><span class="smallfont">$vbphrase[forum]</span></td>
		</if>
	</tr>
</if>
$threadbits
</table>
<br />

<!-- End Latest Threads -->',

'adv_portal_moderation' => 
'<!-- Quick Moderation Block -->

<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
	<tr>
		<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] $vbphrase[quick_moderation]</strong></span></td>
	</tr>
	<tr>
		<td class="$getbgrow">
	
			<if condition="$show[\'threads\']">
				<div><phrase 1="$threads[count]" 2="$vboptions[bburl]/$modcpdir/moderate.php?do=posts">$vbphrase[x_threads]</phrase></div>
			</if>
			<if condition="$show[\'posts\']">
				<div><phrase 1="$posts[count]" 2="$vboptions[bburl]/$modcpdir/moderate.php?do=posts#postlist">$vbphrase[x_posts]</phrase></div>
			</if>
			<if condition="$show[\'events\']">
				<div><phrase 1="$events[count]" 2="$vboptions[bburl]/$modcpdir/moderate.php?do=events">$vbphrase[x_events]</phrase></div>
			</if>
			<if condition="$show[\'attachments\']">
				<div><phrase 1="$attachments[count]" 2="$vboptions[bburl]/$modcpdir/moderate.php?do=attachments">$vbphrase[x_attachment]</phrase></div>
			</if>
				<div><phrase 1="$users[count]" 2="$vboptions[bburl]/$admincpdir/user.php?do=moderate">$vbphrase[x_users]</phrase></div>
		
		</td>
	</tr>
</table>
<br />

<!-- End Quick Moderation Block -->',

'adv_portal_newsbits' => 
'<table align="center" border="0" class="tborder" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" width="100%">
	<tr>
		<td align="$stylevar[left]" class="tcat">
			<if condition="$news[\'subscribed\']">
				<span style="float:$stylevar[right]"><img alt="" class="inlineimg" src="$stylevar[imgdir_misc]/subscribed.gif" title="$vbphrase[you_are_subscribed_to_this_thread]" /> </span>
			</if>
			<if condition="$show[\'threadicon\']">
				<img alt="" border="0" src="$news[threadiconpath]" title="$news[threadicontitle]" />
			</if>
			<strong><a href="$vboptions[bburl]/showthread.php?t=$news[threadid]">$news[title]</a></strong>
			</td>
		</tr>

		<tr>
			<td class="alt2">
			<if condition="$vba_options[\'portal_news_showrating\'] AND $news[\'votenum\'] > 0">
				<span style="float:$stylevar[right]"><img alt="" src="$stylevar[imgdir_rating]/rating_$news[rating].gif" title="<phrase 1="$news[votenum]" 2="$news[voteavg]">$vbphrase[thread_rating_x_votes_y_average]</phrase>" /></span>
			</if>
			<span class="smallfont">- <phrase 1="$vboptions[bburl]/member.php?$session[sessionurl]u=$news[postuserid]" 2="$news[postusername]">$vbphrase[by_x]</phrase> $vbphrase[on] $dateposted</span>

			</td>
		</tr>
		<tr>
			<td align="$stylevar[left]" class="alt1" valign="top">
			<if condition="$vba_options[\'portal_news_showavatar\'] AND $newsavatarurl">
				<table align="$stylevar[left]" cellspacing="4"><tr><td><img alt=""  border="0" src="$newsavatarurl" title="$news[postusername]\'s $vbphrase[a_avatar]" /></td></tr></table>
			</if>
			$news[message]

			<if condition="$show[\'signature\']">
			<div>__________________<br />
			$news[signature]</div>
			</if>

			</td>
		</tr>

		<if condition="$news[\'attachmentid\']">
			<tr class="alt1">
				<td>
					<fieldset class="fieldset">
					<legend>Attached Files</legend>
					<table>
						$attachment
					</table>
					</fieldset>
				</td>
			</tr>
		</if>

		<tr class="alt2" valign="middle">
		<td valign="middle">
			<span style="float:right">
			<if condition="$bbuserinfo[\'usergroupid\'] == 6">
				<a href="$vboptions[bburl]/editpost.php?$session[sessionurl]do=editpost&amp;p=$news[postid]"><img alt="$vbphrase[edit_this_post]" border="0" src="$stylevar[imgdir_button]/edit.gif" /></a>
			</if>

			<if condition="$vba_options[\'portal_news_allowreplies\']">
				<a href="$vboptions[bburl]/newreply.php?$session[sessionurl]do=newreply&amp;t=$news[threadid]"><img alt="$vbphrase[reply_to_this_post]" border="0" src="$stylevar[imgdir_button]/reply_small.gif" /></a>
			</if>

			<if condition="$vba_options[\'portal_news_showsendfriend\']">
				<a href="$vboptions[bburl]/sendmessage.php?$session[sessionurl]do=sendtofriend&amp;t=$news[threadid]"><img alt="" border="0" src="$stylevar[imgdir_button]/sendtofriend.gif" title="$vbphrase[send_to_friend]" /></a>
			</if>

			<if condition="$vba_options[\'portal_news_showprintable\']">
				<a href="$vboptions[bburl]/printthread.php?$session[sessionurl]t=$news[threadid]"><img alt="" border="0" src="$stylevar[imgdir_button]/printer.gif" title="$vbphrase[show_printable_version]" /></a>
			</if>
			</span>

			<span class="smallfont">
			<if condition="$vba_options[\'portal_news_allowreplies\']">
				<a href="$vboptions[bburl]/showthread.php?$session[sessionurl]t=$news[threadid]">$news[replycount] <if condition="$news[\'replycount\'] == 1">$vbphrase[reply]<else />$vbphrase[replies]</if></a> |
			</if> $news[views] $vbphrase[views]</span>
		</td>
	</tr>

</table>
<br />',

'adv_portal_news_archive' => 
'<!-- News Archive -->

	<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
		<tr>
			<td class="tcat" <if condition="$mods[\'modcol\'] == 1"> colspan="5"</if>>$vba_options[portal_blockbullet] <span class="smallfont"><strong>$vbphrase[news_archive]</strong></span></td>
		</tr>
	<if condition="$mods[\'modcol\'] == 1">
		<tr>
			<if condition="$vba_options[\'portal_news_showicon\']">
				<td class="thead" width="15">&nbsp;</td>
			</if>
			<td class="thead" width="100%"><span class="smallfont">$vbphrase[title_username_date]</span></td><td class="thead" width="15"><span class="smallfont">$vbphrase[replies]</span></td><td class="thead" width="15"><span class="smallfont">$vbphrase[views]</span></td>
		</tr>
	</if>
		$newsarchivebits
	
	</table>
	<br />
	
<!-- End News Archive -->',

'adv_portal_news_archivebits' => 
'<if condition="$mods[\'modcol\'] == 1">
	<tr>
		<if condition="$vba_options[\'portal_news_showicon\']">
			<td class="alt2"><if condition="$show[\'threadicon\']"><img alt="" border="0" src="$news[threadiconpath]" title="$news[threadicontitle]" /></if>
			</td>
		</if>
		
		<td class="alt1" align="$stylevar[left]">
			<if condition="$news[\'subscribed\']">
				<span style="float:$stylevar[right]"><img alt="" class="inlineimg" src="$stylevar[imgdir_misc]/subscribed.gif" title="$vbphrase[you_are_subscribed_to_this_thread]" /></span>
			</if>
			<b><a href="$vboptions[bburl]/showthread.php?t=$news[threadid]" title="$news[preview]">$news[title]</a></b>
			
			<div class="smallfont"><phrase 1="$vboptions[bburl]/member.php?$session[sessionurl]u=$news[postuserid]" 2="$news[postusername]">$vbphrase[by_x]</phrase>
				<if condition="$news[\'rating\']">
					<span style="float:$stylevar[right]"><img alt="" src="$stylevar[imgdir_rating]/rating_$news[rating].gif" title="<phrase 1="$news[votenum]" 2="$news[voteavg]">$vbphrase[thread_rating_x_votes_y_average]</phrase>" /></span>
				</if>
			</div>
			
			<div class="smallfont">$news[postdate] <span class="time">$news[posttime]</span></div>

		</td>
		
		<td align="center" class="alt2"><span class="smallfont">$news[replycount]</span></td>
		<td align="center" class="alt1"><span class="smallfont">$news[views]</span></td>
	</tr>

<else />
	<tr>
		<td class="$getbgrow">
			<if condition="$vba_options[\'portal_news_showicon\']">
				<img alt="" src="$news[threadiconpath]" border="0" title="$news[threadicontitle]" />
			</if>
			<if condition="$news[\'subscribed\']">
				<img alt="" class="inlineimg" src="$stylevar[imgdir_misc]/subscribed.gif" title="$vbphrase[you_are_subscribed_to_this_thread]" />
			</if>
			<span class="smallfont"><b><a href="$vboptions[bburl]/showthread.php?t=$news[threadid]" title="<if condition="$news[preview]">$news[preview]

</if>$vbphrase[by] $news[postusername] $news[postdate] $news[posttime]">$news[title]</a></b></span>
			<div class="smallfont">$vbphrase[last_post_by] <a href="$vboptions[bburl]/member.php?find=lastposter&amp;t=$news[threadid]">$news[lastposter]</a></div>
			<div class="smallfont">$news[lastpostdate] <span class="time">$news[lastposttime]</span></div>
		<div class="smallfont">$news[replycount] $vbphrase[replies], $news[views] $vbphrase[views]</div></td>
	</tr>

</if>
',

'adv_portal_onlineusers' => 
'<!-- Online Users Block -->
	
<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder"width="100%">
	<tr>
		<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] <a href="$vboptions[bburl]/online.php?$session[sessionurl]">$vbphrase[active_users]</a>: $totalonline</strong></span>
		</td>
	</tr>
	<tr>
		<td class="alt2"><span class="smallfont"><phrase 1="$numberregistered" 2="$numberguest">$vbphrase[x_members_and_y_guests]</phrase></span></td>
	</tr>
	<tr>
		<td class="alt1"><span class="smallfont">$activeusers</span></td>
	</tr>
	
	<tr>
		<td class="alt2"><span class="smallfont"><phrase 1="$recordusers" 2="$recorddate" 3="$recordtime">$vbphrase[most_users_ever_online_was_x_y_at_z]</phrase></span></td>
	</tr>
</table>
<br />

<!-- End Online Users Block -->',

'adv_portal_poll' => 
'<!-- Poll Block -->
	
	<if condition="$vba_options[\'portal_poll_forumid\'] AND $pollinfo[\'pollid\']">
	<if condition="$showresults != 1 AND $uservoted != 1">
	
	<form action="$vboptions[bburl]/poll.php" method="post">
	<input name="s" type="hidden" value="$session[dbsessionhash]" />
	<input name="do" type="hidden" value="pollvote" />
	<input name="pollid" type="hidden" value="$pollinfo[pollid]" />
	
	</if>
		<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
		<tr>


			<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] $vbphrase[poll] <if condition="$showresults OR $uservoted">$vbphrase[results]</if></strong></span></td>
			</tr>
			<tr>
			<td class="alt1"><span class="smallfont">
			$pollinfo[question]</span></td></tr>
			<tr>
			<td class="alt2">
			
				<table width="100%">
					$pollbits
				</table>

			</td>
			</tr>
			<tr>
			<td align="$stylevar[left]" class="alt1">
			<if condition="$showresults OR $uservoted">
			<span class="smallfont"><b>$vbphrase[total_votes]: $pollinfo[nvotes]</b><br />$pollinfo[message].</span>
			
			<else />
			
			<input class="button" name="button" type="submit" value="$vbphrase[vote_now]" />
			</if>
			
			</td>
			</tr>
			
			<tr>
			<td class="alt2"><div class="smallfont">&raquo; <a href="$vboptions[bburl]/poll.php?$session[sessionurl]do=showresults&amp;pollid=$pollinfo[pollid]">$vbphrase[view_poll_results]</a></div>
			
			<if condition="$vba_options[\'portal_poll_allowreplies\']">
			<div class="smallfont">&raquo; <a href="$vboptions[bburl]/newreply.php?$session[sessionurl]t=$pollinfo[threadid]">$vbphrase[discuss_this_poll]</a></div>
			<div class="smallfont">&raquo; <a href="$vboptions[bburl]/showthread.php?$session[sessionurl]t=$pollinfo[threadid]">$vbphrase[this_poll_has] $pollinfo[replycount] <if condition="$pollinfo[\'replycount\'] == 1">$vbphrase[reply]<else />$vbphrase[replies]</if></a></div></if>
			
			<if condition="$bbuserinfo[\'usergroupid\'] == 6">
				<div class="smallfont">&raquo; <a href="$vboptions[bburl]/poll.php?$session[sessionurl]do=polledit&amp;pollid=$pollinfo[pollid]">$vbphrase[edit_poll]</a></div>
			</if></span></td>
			</tr>
			
			</table>
	<if condition="$showresults != 1 AND $uservoted != 1">
	
	</form>
	</if>
	<br />
	
	</if>
	
<!-- End Poll Block -->',

'adv_portal_polloption' => 
'<tr>
<td align="left" class="alt2"><label for="pollchoice_$option[number]"><input id="pollchoice_$option[number]" name="optionnumber" type="radio" value="$option[number]" /><span class="smallfont">$option[question]</span></label></td>
</tr>',

'adv_portal_polloption_multiple' => 
'<tr class="alt2">
<td width="10%"><input name="optionnumber[$option[number]]" type="checkbox" value="yes" /></td><td align="$stylevar[left]"><span class="smallfont">$option[question]</span></td>
</tr>',

'adv_portal_pollresult' => 
'<tr>
<td align="$stylevar[left]" class="alt2">
<span class="smallfont">$option[question] - $option[percent]%</span><br />
<img alt="" height="10" src="$stylevar[imgdir_poll]/bar$option[graphicnumber]-l.gif" width="3" /><img alt="$option[votes] <if condition="$option[\'votes\'] <> 1">$vbphrase[votes]<else />$vbphrase[vote]</if>" height="10" src="$stylevar[imgdir_poll]/bar$option[graphicnumber].gif" width="$option[barnumber]" /><img alt="" height="10" src="$stylevar[imgdir_poll]/bar$option[graphicnumber]-r.gif" width="3" /></td>
</tr>',

'adv_portal_search' => 
'<!-- Search Block -->
	
<form action="$vboptions[bburl]/search.php" method="post" name="search">
	<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder"width="100%">
		<tr>
	
			<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] $vbphrase[search_forums]</strong></span></td>
		</tr>
		<tr>
			<td class="$getbgrow">
			<input name="s" type="hidden" value="" />
			<input name="do" type="hidden" value="process" />
			<input name="sortby" type="hidden" value="lastpost" />
			<input name="forumchoice" type="hidden" value="0" />
			<input class="bginput" name="query" size="13" type="text" /> $gobutton<br />
			<span class="smallfont">&raquo; <a href="$vboptions[bburl]/search.php?">$vbphrase[advanced_search]</a></span>
			</td>
		</tr>
	</table>
</form>
<br />
	
<!-- End Search Block -->',

'adv_portal_stats' => 
'<!-- Stats Block -->
	
<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder"width="100%">
<tr>
<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] 

<phrase 1="$vboptions[hometitle]">$vbphrase[x_statistics]</phrase>

</strong></span></td>
</tr>
<tr>
<td class="$getbgrow"><span class="smallfont">

$vbphrase[members]: $numbermembers<br />
$vbphrase[threads]: $totalthreads<br />
$vbphrase[posts]: $totalposts<br />
$vbphrase[top_poster]: <a href="$vboptions[bburl]/member.php?$session[sessionurl]u=$topposter[userid]">$topposter[username]</a> ($topposter[posts])<br />
<br />
<phrase 1="$vboptions[bburl]/member.php?$session[sessionurl]u=$newuserid" 2="$newusername">$vbphrase[welcome_to_our_newest_member_x]</phrase>
</span>
</td>
</tr>

<if condition="$birthdays">
<tr>
<td class="alt1"><span class="smallfont">
<b>$vbphrase[todays_birthdays]:</b><br /> $birthdays</span></td>
</tr>
</if>

</table>
<br />
	
<!-- End Stats Block -->
',

'adv_portal_welcomeblock' => 
'<!-- Welcome / Login Block -->
	<if condition="!$bbuserinfo[\'userid\']">


	<script src="$vboptions[bburl]/clientscript/vbulletin_md5.js" type="text/javascript"></script>
	<form action="$vboptions[bburl]/login.php" method="post" onsubmit="md5hash(vb_login_password,vb_login_md5password)">
	<input name="vb_login_md5password" type="hidden" />
	<input name="s" type="hidden" value="" />
	<input name="do" type="hidden" value="login" />
	</if>

<if condition="!$vba_options[\'portal_shownavbar\'] AND $bbuserinfo[\'userid\']">
<script type="text/javascript">
<!--
function log_out()
{
	ht = document.getElementsByTagName("html");
	ht[0].style.filter = "progid:DXImageTransform.Microsoft.BasicImage(grayscale=1)";
	if (confirm(\'$vbphrase[sure_you_want_to_log_out]\'))
	{
		return true;
	}
	else
	{
		ht[0].style.filter = "";
		return false;
	}
}
//-->
</script>
</if>

			
	<table align="center" border="0" cellpadding="$stylevar[cellpadding]" cellspacing="$stylevar[cellspacing]" class="tborder" width="100%">
	<tr>
	<td class="tcat"><span class="smallfont"><strong>$vba_options[portal_blockbullet] 
	<if condition="$bbuserinfo[\'userid\']">
	<a href="$vboptions[bburl]/usercp.php?$session[sessionurl]">$vbphrase[user_cp]</a><else />$vbphrase[log_in]
	</if></strong></span></td>
	</tr>
	<tr>
	<td class="$getbgrow">
	
	<if condition="$bbuserinfo[\'userid\']">
	<span class="smallfont"><phrase 1="$bbuserinfo[username]">$vbphrase[welcome_back_x]</phrase></span><br />
	
	<if condition="$vba_options[\'portal_welcome_avatar\']">
		<table cellpadding="3">
		<tr>
		<td><a href="$vboptions[bburl]/profile.php?do=editavatar"><img src="$avatarurl" border="0" alt="$vbphrase[edit_avatar]" /></a>
		</td>
		</tr>
		</table>
	</if>

	<span class="smallfont"><phrase 1="$lastvisitdate" 2="$lastvisittime">$vbphrase[last_visited_x_at_y]</phrase><br />
	<if condition="$vba_options[\'portal_welcome_newpms\']"><a href="$vboptions[bburl]/private.php?$session[sessionurl]">$vbphrase[new_pms]</a>: $bbuserinfo[pmunread]<br /></if>
	<if condition="$vba_options[\'portal_welcome_newposts\']"><a href="$vboptions[bburl]/search.php?$session[sessionurl]do=getnew">$vbphrase[new_posts]</a>: $newposts<br /></if>
	<a href="$vboptions[bburl]/login.php?$session[sessionurl]do=logout&amp;u=$bbuserinfo[userid]" onclick="return log_out()">$vbphrase[log_out]</a></span>
	
	<else />
		<table width="100%">
		<tr>
		<td>
		<span class="smallfont"><b>$vbphrase[user_name]:<br />
		<input class="bginput" name="vb_login_username" size="12" type="text" /><br />
		$vbphrase[password]:</b></span><br />
		<input class="bginput" name="vb_login_password" size="12" type="password" /><br />
		<input checked="checked" class="bginput" name="cookieuser" id="cb_cookieuser" type="checkbox" value="1" /><span class="smallfont">$vbphrase[remember_me]</span>
		</td>
		</tr>
		<tr>
		<td>
		<input class="button" type="submit" value="$vbphrase[log_in]" />
		</td>
		</tr>
		<tr>
		<td>
		<span class="smallfont"><phrase 1="$vboptions[bburl]">$vbphrase[not_a_member_yet_register_now]</phrase>
</span>
		</td>
		</tr>
		</table>
	</if>
	</td>
	</tr>
	</table>
	<br />

	<if condition="!$bbuserinfo[\'userid\']">
	</form>
	</if>
<!-- End Welcome / Login Block -->'

); 


// ########################### Phrases ################################
$mainphrases = array(
'1' => array('varname' => 'active_users', 'ptype' => '569', 'text' => 
'Active Users'),
'2' => array('varname' => 'buddies', 'ptype' => '569', 'text' => 
'buddies'),
'3' => array('varname' => 'by', 'ptype' => '569', 'text' => 
'By'),
'4' => array('varname' => 'discuss_this_poll', 'ptype' => '569', 'text' => 
'Discuss This Poll'),
'5' => array('varname' => 'edit_this_post', 'ptype' => '569', 'text' => 
'Edit This Post'),
'6' => array('varname' => 'last_post_by', 'ptype' => '569', 'text' => 
'Last post by'),
'7' => array('varname' => 'latest_forum_topics', 'ptype' => '569', 'text' => 
'Latest Forum Topics'),
'8' => array('varname' => 'new_pms', 'ptype' => '569', 'text' => 
'New PMs'),
'9' => array('varname' => 'news_archive', 'ptype' => '569', 'text' => 
'News Archive'),
'10' => array('varname' => 'no_x_online', 'ptype' => '569', 'text' => 
'<span class="smallfont">No {1} online</span>'),
'11' => array('varname' => 'not_a_member_yet_register_now', 'ptype' => '569', 'text' => 
'Not a member yet?<br />
<a href="{1}/register.php">Register Now!</a>'),
'12' => array('varname' => 'on', 'ptype' => '569', 'text' => 
'on'),
'13' => array('varname' => 'online_buddies', 'ptype' => '569', 'text' => 
'Online Buddies'),
'14' => array('varname' => 'pm', 'ptype' => '569', 'text' => 
'PM'),
'15' => array('varname' => 'poll_results', 'ptype' => '569', 'text' => 
'Poll Results'),
'16' => array('varname' => 'powered_by_vbadvanced_cmps', 'ptype' => '569', 'text' => 
'<a href="hXXp://" target="_blank">vBadvanced</a>'),
'17' => array('varname' => 'quick_moderation', 'ptype' => '569', 'text' => 
'Quick Moderation'),
'18' => array('varname' => 'read_more', 'ptype' => '569', 'text' => 
' <span class="smallfont">[<a href="{1}/showthread.php?{3}t={2}">Read More</a>]</span>'),
'19' => array('varname' => 'remove_from_buddy_list', 'ptype' => '569', 'text' => 
'Remove from buddy list'),
'20' => array('varname' => 'reply_to_this_post', 'ptype' => '569', 'text' => 
'Reply to This Post'),
'21' => array('varname' => 'send_to_friend', 'ptype' => '569', 'text' => 
'Send to Friend'),
'22' => array('varname' => 'show_printable_version', 'ptype' => '569', 'text' => 
'Show Printable Version'),
'24' => array('varname' => 'this_poll_has', 'ptype' => '569', 'text' => 
'This Poll Has'),
'25' => array('varname' => 'title_username_date', 'ptype' => '569', 'text' => 
'Title, Username, &amp; Date'),
'26' => array('varname' => 'top_poster', 'ptype' => '569', 'text' => 
'Top Poster'),
'27' => array('varname' => 'welcome_back_x', 'ptype' => '569', 'text' => 
'Welcome back <strong>{1}</strong>'),
'28' => array('varname' => 'x_attachment', 'ptype' => '569', 'text' => 
'<a href="{2}" target="_blank">Attachments</a> ({1})'),
'29' => array('varname' => 'x_events', 'ptype' => '569', 'text' => 
'<a href="{2}" target="_blank">Events</a> ({1})'),
'30' => array('varname' => 'x_posts', 'ptype' => '569', 'text' => 
'<a href="{2}" target="_blank">Posts</a> ({1})'),
'31' => array('varname' => 'x_threads', 'ptype' => '569', 'text' => 
'<a href="{2}" target="_blank">Threads</a> ({1})'),
'32' => array('varname' => 'x_users', 'ptype' => '569', 'text' => 
'<a href="{2}" target="_blank">Users</a> ({1})'),
'33' => array('varname' => 'active', 'ptype' => '570', 'text' => 
'Active'),
'34' => array('varname' => 'add_module', 'ptype' => '570', 'text' => 
'Add Module'),
'35' => array('varname' => 'add_page', 'ptype' => '570', 'text' => 
'Add Page'),
'36' => array('varname' => 'add_setting', 'ptype' => '570', 'text' => 
'Add Setting'),
'37' => array('varname' => 'advanced_options', 'ptype' => '570', 'text' => 
'Advanced Options'),
'38' => array('varname' => 'advanced_options_description', 'ptype' => '570', 'text' => 
'Here you may specify a different value for each default option that will apply to this page. If no option is specified here, this page will use the default. If you wish to restore an option to it\'s default, simply check the "Use Default" checkbox to the right of the option\'s title and then save the options.'),
'39' => array('varname' => 'are_you_sure_delete_module_called_x', 'ptype' => '570', 'text' => 
'Are you sure you want to delete the module called <b>{1}</b>?<br />
This action can <b>NOT</b> be undone.'),
'40' => array('varname' => 'are_you_sure_delete_page_called_x', 'ptype' => '570', 'text' => 
'Are you sure you want to delete the page called <strong>{1}</strong>?'),
'41' => array('varname' => 'center_column', 'ptype' => '570', 'text' => 
'Center Column'),
'42' => array('varname' => 'choose_a_file', 'ptype' => '570', 'text' => 
'Choose a File'),
'43' => array('varname' => 'column', 'ptype' => '570', 'text' => 
'Column'),
'44' => array('varname' => 'custom_style_for_this_page', 'ptype' => '570', 'text' => 
'Custom Style for this Page'),
'45' => array('varname' => 'edit_module', 'ptype' => '570', 'text' => 
'Edit Module'),
'46' => array('varname' => 'edit_modules', 'ptype' => '570', 'text' => 
'Edit Modules'),
'47' => array('varname' => 'edit_page', 'ptype' => '570', 'text' => 
'Edit Page'),
'48' => array('varname' => 'edit_pages', 'ptype' => '570', 'text' => 
'Edit Pages'),
'49' => array('varname' => 'edit_setting', 'ptype' => '570', 'text' => 
'Edit Setting'),
'50' => array('varname' => 'file_to_include', 'ptype' => '570', 'text' => 
'File to Include'),
'51' => array('varname' => 'here_specify_which_usergroups_access_page', 'ptype' => '570', 'text' => 
'Here you may specifiy which usergroups will have access to this page.'),
'52' => array('varname' => 'here_specify_which_usergroups_view_module', 'ptype' => '570', 'text' => 
'Here you may specifiy which usergroups will be able to view this module.'),
'53' => array('varname' => 'identifier', 'ptype' => '570', 'text' => 
'Identifier'),
'54' => array('varname' => 'inactive', 'ptype' => '570', 'text' => 
'Inactive'),
'55' => array('varname' => 'left_column', 'ptype' => '570', 'text' => 
'Left Column'),
'56' => array('varname' => 'module_identifier', 'ptype' => '570', 'text' => 
'Module Identifier'),
'57' => array('varname' => 'module_title', 'ptype' => '570', 'text' => 
'Module Title'),
'58' => array('varname' => 'modules_enabled', 'ptype' => '570', 'text' => 
'Modules Enabled'),
'59' => array('varname' => 'move_to', 'ptype' => '570', 'text' => 
'Move to'),
'60' => array('varname' => 'no_modules', 'ptype' => '570', 'text' => 
'No Modules'),
'61' => array('varname' => 'option_code', 'ptype' => '570', 'text' => 
'Option Code'),
'62' => array('varname' => 'or_template_to_include', 'ptype' => '570', 'text' => 
'<b>OR</b> Template to Include'),
'63' => array('varname' => 'page_identifier', 'ptype' => '570', 'text' => 
'Page Identifier'),
'64' => array('varname' => 'page_identifier_example', 'ptype' => '570', 'text' => 
'This is the variable that will be used in the URL to link to this page. For example, if this option is set to \'games\', then the link to this page would look like this: {1}/index.php?{2}=games</div>'),
'65' => array('varname' => 'page_template', 'ptype' => '570', 'text' => 
'Page Template'),
'66' => array('varname' => 'page_template_description', 'ptype' => '570', 'text' => 
'This is the template that will replace your "Custom Page Content" module. If you don\'t wish to have a custom template on this page, simply leave this field blank.'),
'67' => array('varname' => 'page_title', 'ptype' => '570', 'text' => 
'Page Title'),
'68' => array('varname' => 'remove_module', 'ptype' => '570', 'text' => 
'Remove Module'),
'69' => array('varname' => 'right_column', 'ptype' => '570', 'text' => 
'Right Column'),
'70' => array('varname' => 'setting', 'ptype' => '570', 'text' => 
'setting'),
'71' => array('varname' => 'template_note_prefixes', 'ptype' => '570', 'text' => 
'Please note that templates should have the prefix "adv_portal". It is not necessary to enter that prefix with the template name, so if the template you would like to include is named "adv_portal_search" then you would simply enter "search" for the template to include.'),
'72' => array('varname' => 'templates_used', 'ptype' => '570', 'text' => 
'Templates Used'),
'73' => array('varname' => 'templates_used_note', 'ptype' => '570', 'text' => 
'List all templates (seperated by comma\'s) that your module will use here in order to cache them and prevent unnecessary queries.<br />Note: This is only necessary when including files, but not when including templates.'),
'74' => array('varname' => 'this_what_used_identify_module', 'ptype' => '570', 'text' => 
' This is what will be used to identify this particular module. Unless you have a use for this it should be left blank.'),
'75' => array('varname' => 'to_remove_this_module', 'ptype' => '570', 'text' => 
'To remove this module click the \'Remove Module\' button below.'),
'76' => array('varname' => 'update_all_pages', 'ptype' => '570', 'text' => 
'Update All Pages?'),
'77' => array('varname' => 'update_all_pages_desc', 'ptype' => '570', 'text' => 
'Set this option to \'Yes\' if you would like to update all of your pages to include this module. Otherwise you will need to manually edit each page and add the module to the pages you wish for it to appear on.'),
'78' => array('varname' => 'use_default', 'ptype' => '570', 'text' => 
'Use Default'),
'79' => array('varname' => 'use_default_style', 'ptype' => '570', 'text' => 
'Use Default Style'),
'80' => array('varname' => 'vbadvanced_cmps', 'ptype' => '570', 'text' => 
'vBadvanced CMPS'),
'81' => array('varname' => 'vote', 'ptype' => '569', 'text' => 
'Vote'),

// CP Stop Messages
'112' => array('varname' => 'adv_portal_cant_remove_default', 'ptype' => '9000', 'text' => 
'You can\'t remove your default page!'),
'113' => array('varname' => 'adv_portal_must_choose_include', 'ptype' => '9000', 'text' => 
'You must choose either a file or a template to include!'),
'114' => array('varname' => 'adv_portal_must_enter_x_for_page', 'ptype' => '9000', 'text' => 'You must choose a {1} for your page!'),
'115' => array('varname' => 'adv_portal_must_choose_title_for_module', 'ptype' => '9000', 'text' => 'You must choose a title for your module!'),

// CP Home Page
'236' => array('varname' => 'vba_cmps', 'ptype' => '9', 'text' => 
'vBa CMPS'),
'252' => array('varname' => 'edit_modules', 'ptype' => '9', 'text' => 
'Edit Modules'),
'253' => array('varname' => 'edit_pages', 'ptype' => '9', 'text' => 
'Edit Pages'),
'255' => array('varname' => 'add_module', 'ptype' => '9', 'text' => 
'Add Module'),
'221' => array('varname' => 'add_page', 'ptype' => '9', 'text' => 
'Add Page'),
'251' => array('varname' => 'default_settings', 'ptype' => '9', 'text' => 
'Default Settings'),

// Setting Group
'935' => array('varname' => 'settinggroup_adv_portal_latestthreads', 'ptype' => '5000', 'text' => 
'Latest Threads Options'),
'937' => array('varname' => 'settinggroup_adv_portal_main', 'ptype' => '5000', 'text' => 
'Main Options'),
'938' => array('varname' => 'settinggroup_adv_portal_misc', 'ptype' => '5000', 'text' => 
'Miscellaneous Options'),
'939' => array('varname' => 'settinggroup_adv_portal_news', 'ptype' => '5000', 'text' => 
'News Options'),
'940' => array('varname' => 'settinggroup_adv_portal_poll', 'ptype' => '5000', 'text' => 
'Poll Options'),
'941' => array('varname' => 'settinggroup_adv_portal_welcomeblock', 'ptype' => '5000', 'text' => 
'Welcome Block Options')

);

// ########################## Settings ##############################
$mainsettings = array(
'portal_news_enablearchive' => array('title' => 'Number of Archived News Posts to Display', 'description' => 'Set this to the number of news posts you would like to displayin the News Archive. Set this to 0 to disable the News Archive.', 'value' => 
'2', 'optioncode' => '', 'displayorder' => '18', 'grouptitle' => 'adv_portal_news'),

'portal_news_archivepreview' => array('title' => 'Show Preview in Archive?', 'description' => 'This option will allow you to show a preview of the archived thread, the same as on the forumdisplay page.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '19', 'grouptitle' => 'adv_portal_news'),

'portal_threads_maxthreads' => array('title' => 'Latest Threads Maximum ', 'description' => 'The maximum number of latest threads to display on the page.', 'value' => 
'10', 'optioncode' => '', 'displayorder' => '1', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_maxchars' => array('title' => 'Latest Threads Maximum Characters', 'description' => 'The maximum number of characters of your latest threads title to display before it is replaced by \'...\'. Set this to 0 to disable it.', 'value' => 
'30', 'optioncode' => '', 'displayorder' => '3', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_showdate' => array('title' => 'Show Latest Threads Date & Time?', 'description' => 'Show the time and date the thread was created.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '4', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_showicon' => array('title' => 'Show Latest Threads Icon?', 'description' => 'Show threads icon in latest threads section.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '8', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_showsubscribed' => array('title' => 'Show Subscribed Icon?', 'description' => 'Set this option to \'Yes\' if you would like to display the subscribed icon next tothe thread title if a user is subscribed to that thread.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '9', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_showpreview' => array('title' => 'Show Preview of New Threads?', 'description' => 'This option will allow you to show a preview of the thread, the same as onthe forumdisplay page.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '7', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_showforum' => array('title' => 'Show Latest Threads Forum?', 'description' => 'This option will display the name of, and link to the forum that the post was made in.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '6', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_poll_forumid' => array('title' => 'Polls Forum ID', 'description' => 'The ID number of your polls forum. Enter 0 to disable homepage polls, or enter RAND to pull a random poll from any forum.', 'value' => 
'0', 'optioncode' => '', 'displayorder' => '1', 'grouptitle' => 'adv_portal_poll'),

'portal_poll_allowreplies' => array('title' => 'Allow Poll Discussion?', 'description' => 'Setting this option to \'Yes\' will show the \'Discuss this poll\' link on your poll giving users a link to the thread in which the poll was made.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '2', 'grouptitle' => 'adv_portal_poll'),

'portal_poll_allowsmilies' => array('title' => 'Show Poll Smilies?', 'description' => 'Setting this option to \'Yes\' will parse smilies in your poll.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '3', 'grouptitle' => 'adv_portal_poll'),

'portal_welcome_newpms' => array('title' => 'Show New PMs?', 'description' => 'Show a user\'s private message info in the welcome block?', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '2', 'grouptitle' => 'adv_portal_welcomeblock'),

'portal_threads_showrating' => array('title' => 'Show Thread Rating', 'description' => 'Show the rating of threads?', 'value' => 
'0', 'optioncode' => 'yesno', 'displayorder' => '10', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_threads_exclude' => array('title' => 'Excluded Forums', 'description' => 'Enter a list of forumids (seperated by commas) that you would like to exclude from your modules that use forum permissions. This will affect latest threads, Random polls, and any add-on modules that you may have which use the $iforumperms variable to determine forum permissions. Leave this blank if you do not wish to exclude any forums. Example setting: 1,4,9', 'value' => 
'', 'optioncode' => '', 'displayorder' => '2', 'grouptitle' => 'adv_portal_misc'),

'portal_threads_lastpost' => array('title' => 'Show Last Post Info', 'description' => 'Set this option to \'Yes\' if you would like to display the last poster and time at which the last post was made.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '5', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_calendarid' => array('title' => 'Calendar ID', 'description' => 'The calendar ID you would like to display. Enter 0 to disable the calendar, or -1 to display a simple calendar with no links to events.', 'value' => 
'0', 'optioncode' => '', 'displayorder' => '1', 'grouptitle' => 'adv_portal_misc'),

'portal_threads_forumids' => array('title' => 'Latest Threads Forums', 'description' => 'Here you may enter a list of forumids (seperated by commas) to pull latest threads from. If you wish to pull latest threads from all forums, leave this option blank.', 'value' => 
'', 'optioncode' => '', 'displayorder' => '2', 'grouptitle' => 'adv_portal_latestthreads'),

'portal_shownavbar' => array('title' => 'Enable Navbar?', 'description' => 'This option will enable the vBulletin navbar on the page.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '2', 'grouptitle' => 'adv_portal_main'),

'portal_colspacing' => array('title' => 'Spacing Between Columns', 'description' => 'This is the amount of space (in pixels) that is between the different columns.', 'value' => 
'15', 'optioncode' => '', 'displayorder' => '5', 'grouptitle' => 'adv_portal_main'),

'portal_news_showsignature' => array('title' => 'Show Signature in News Posts', 'description' => 'Set this option to \'Yes\' if you would like to enable signatures in news posts. Note: The option to show your signature must have been selected in the post as well in order for the signature to be shown.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '12', 'grouptitle' => 'adv_portal_news'),

'portal_rightcolwidth' => array('title' => 'Right Column Width', 'description' => 'The width (in pixels or percentage) of your right column.', 'value' => 
'175', 'optioncode' => '', 'displayorder' => '4', 'grouptitle' => 'adv_portal_main'),

'portal_leftcolwidth' => array('title' => 'Left Column Width', 'description' => 'The width (in pixels or percentage) of your left column.', 'value' => 
'175', 'optioncode' => '', 'displayorder' => '3', 'grouptitle' => 'adv_portal_main'),

'portal_blockbullet' => array('title' => 'Block Bullet', 'description' => 'An image or any other HTML code you would like to appear next to the title of each block.', 'value' => 
'&raquo;', 'optioncode' => '', 'displayorder' => '6', 'grouptitle' => 'adv_portal_main'),

'portal_pagevar' => array('title' => 'Page Variable', 'description' => 'This is the variable that will be used in the URL to link to your new pages. For example, if this option is set to \'page\', then a link to a new page would look like this: http://yoursite.com/index.php?page=yourpage', 'value' => 
'page', 'optioncode' => '', 'displayorder' => '1', 'grouptitle' => 'adv_portal_main'),

'portal_welcome_newposts' => array('title' => 'Show New Posts?', 'description' => 'Set this option to \'Yes\' if you would like to display the number of new posts since a user\'s last visit. Note: On larger forums, this option may be somewhat server intensive. If you experience problems with the page loading slowly, try turning this option off.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '3', 'grouptitle' => 'adv_portal_welcomeblock'),

'portal_welcome_avatar' => array('title' => 'Show User\'s Avatar?', 'description' => 'Set this option to \'Yes\' if you would like to display the user\'s avatar in the welcome block.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '1', 'grouptitle' => 'adv_portal_welcomeblock'),

'portal_news_maxposts' => array('title' => 'News Posts Maximum', 'description' => 'The maximum number of news posts to display. Set to 0 to show all posts.', 'value' => 
'2', 'optioncode' => '', 'displayorder' => '2', 'grouptitle' => 'adv_portal_news'),

'portal_news_maxchars' => array('title' => 'News Maximum Characters', 'description' => 'The maximum number of characters to display per news post before it is replacedby the \'[read more]\' link. Set this to 0 to disable it.', 'value' => 
'1000', 'optioncode' => '', 'displayorder' => '3', 'grouptitle' => 'adv_portal_news'),

'portal_news_forumid' => array('title' => 'News Forum ID', 'description' => 'The ID number of your default news forum. To display threads from more than one forum, enter a list of forumids seperated by commas. Example setting: 1,4,9', 'value' => 
'0', 'optioncode' => '', 'displayorder' => '1', 'grouptitle' => 'adv_portal_news'),

'portal_welcome_lastvisit_date' => array('title' => 'Last Visit Date Format', 'description' => 'The format that you would like the date of the user\'s last visit displayed in.', 'value' => 
'm-d-y', 'optioncode' => '', 'displayorder' => '4', 'grouptitle' => 'adv_portal_welcomeblock'),

'portal_welcome_lastvisit_time' => array('title' => 'Last Visit Time Format', 'description' => 'The format that you would like the time of the user\'s last visit displayed in.', 'value' => 
'g:i a', 'optioncode' => '', 'displayorder' => '5', 'grouptitle' => 'adv_portal_welcomeblock'),

'portal_news_allowreplies' => array('title' => 'Allow News Replies?', 'description' => 'Allow users to comment on news posts? Note: Your news forum may not have permissions set to make it a private forum if you wish to allow comments.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '4', 'grouptitle' => 'adv_portal_news'),

'portal_news_showavatar' => array('title' => 'Show News Avatar?', 'description' => 'Displays the poster\'s avatar in the news section.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '6', 'grouptitle' => 'adv_portal_news'),

'portal_news_showicon' => array('title' => 'Show News Icon?', 'description' => 'Show your news posts icon?', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '7', 'grouptitle' => 'adv_portal_news'),

'portal_news_showrating' => array('title' => 'Show News Ratings?', 'description' => 'Shows the rating of news posts?', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '8', 'grouptitle' => 'adv_portal_news'),

'portal_news_showattachments' => array('title' => 'Enable News Attachments?', 'description' => 'Set this to yes if you would display attachments with your news posts (Note: This will only display the first attachment associated with the post).', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '5', 'grouptitle' => 'adv_portal_news'),

'portal_news_showprintable' => array('title' => 'Enable Printable Version?', 'description' => 'Shows the printable version icon and allow users to quickly print news posts.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '10', 'grouptitle' => 'adv_portal_news'),

'portal_news_showsendfriend' => array('title' => 'Enable Send to Friend?', 'description' => 'Shows the send to friend icon and allow users to quickly send news posts to friends.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '11', 'grouptitle' => 'adv_portal_news'),

'portal_news_enablehtml' => array('title' => 'Enable HMTL for News?', 'description' => 'Enables HTML for the news posts.', 'value' => 
'0', 'optioncode' => 'yesno', 'displayorder' => '13', 'grouptitle' => 'adv_portal_news'),

'portal_news_enablevbcode' => array('title' => 'Enable vBcode for News?', 'description' => 'Enables vBcode for the news posts.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '14', 'grouptitle' => 'adv_portal_news'),

'portal_news_enablevbimage' => array('title' => 'Enable vBimage code for News?', 'description' => 'Enables vB image code for the news posts.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '15', 'grouptitle' => 'adv_portal_news'),

'portal_news_enablesmilies' => array('title' => 'Allow News Smilies?', 'description' => 'Allows smilies in newsposts.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '16', 'grouptitle' => 'adv_portal_news'),

'portal_news_dateformat' => array('title' => 'News Date & Time Format', 'description' => 'Format in which the time and date is presented in the news posts.', 'value' => 
'M d, Y - g:i A', 'optioncode' => '', 'displayorder' => '17', 'grouptitle' => 'adv_portal_news'),

'portal_news_showsubscribed' => array('title' => 'Show Subscribed Threads?', 'description' => 'Set this option to \'Yes\' if you would like to display the subscribed icon next to the thread title if a user is subscribed to that news thread.', 'value' => 
'1', 'optioncode' => 'yesno', 'displayorder' => '9', 'grouptitle' => 'adv_portal_news'),

'portal_version' => array('title' => 'vBadvanced CMPS version', 'description' => 'Leave this setting alone.', 'value' => 
'1.0.0', 'optioncode' => 'hidden', 'displayorder' => '0', 'grouptitle' => 'adv_portal_main')

); 


// ###### Setting Groups #########
$sgarray = array(
	'adv_portal_news' => 2,
	'adv_portal_main' => 1,
	'adv_portal_latestthreads' => 3,
	'adv_portal_poll' => 4,
	'adv_portal_welcomeblock' => 5,
	'adv_portal_misc' => 6
);



// #################### Main Welcome Page ########################
if ($_REQUEST['do'] == 'home')
{
	// Steps
	$steps = array('Checks your database for previous installations', 'Adds / Alters Database Tables', 'Creates new Templates', 'Adds Phrases', 'Adds Settings', 'Adds Modules', 'Updates Image Paths');

	echo '<p><b>Welcome to vBadvanced ' . $scriptname . ' version ' . $version . '.<br />Running this script will do a clean install of 	vBadvanced ' . $scriptname . ' onto your server.</b><br /><br />';

	foreach ($steps AS $number => $step)
	{
		echo 'Step ' . ($number + 1) . ': ' . $step . '<br />';
	}

	echo '<p><a href="' . $scriptfile . '?do=startinstall"><b>Click here to begin the installation process --&gt;</b></a></p><br />';

	echo '<a href="' . $scriptfile . '?do=upgrade"><b>Click here to upgrade --&gt;</b></a><br /><br /><br />';


	echo '<a href="' . $scriptfile . '?do=templates&amp;only=1"><b>Click here to install ONLY the templates <font size="1">(useful after upgrading vBulletin)</font> --&gt;</b></a><br /><br />';

	echo '<a href="' . $scriptfile . '?do=phrases&amp;only=1"><b>Click here to install ONLY the phrases --&gt</b></a><br /><br />';

	echo '<a href="' . $scriptfile . '?do=doimages"><b>Click here to update your image paths (Icons &amp; Smilies) --&gt;</b></a><br /><br /><br />';


	echo '<a href="' . $scriptfile . '?do=uninstall"><b>Click here to uninstall vBadvanced ' . $scriptname . ' --&gt;</b></a>';

}

// #################### Check for previous installations ############################
if ($_REQUEST['do'] == 'startinstall')
{
  $DB_site->reporterror = 0;
	$check = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "adv_setting WHERE varname LIKE 'portal_%'");
	if ($check['count'])
	{
		echo 'vBadvanced ' . $scriptname . ' is already installed!';
	}
	else
	{
		echo 'No previous installation detected.<br /><br />';
		echo '<a href="' . $scriptfile . '?do=createtables"><b>Add Database Tables --&gt;</b></a><br />';
	}
}

// #################### Create Tables ############################
if ($_REQUEST['do'] == 'createtables')
{
	$DB_site->query("
	CREATE TABLE IF NOT EXISTS " . TABLE_PREFIX . "adv_setting (
  varname varchar(100) NOT NULL default '',
  grouptitle varchar(50) NOT NULL default '',
  value mediumtext NOT NULL default '',
  defaultvalue varchar(250) NOT NULL default '',
  optioncode mediumtext NOT NULL,
  displayorder smallint(3) unsigned NOT NULL default '0'
  ) TYPE=MyISAM");
	
	echo 'Created table <b>adv_setting</b>.<br />';

	$DB_site->query("
		CREATE TABLE IF NOT EXISTS " . TABLE_PREFIX . "adv_settinggroup (
	  grouptitle varchar(50) NOT NULL default '',
	  displayorder smallint(3) unsigned NOT NULL default '0',
	  PRIMARY KEY  (grouptitle)
		) TYPE=MyISAM
	");

	echo 'Created table <b>adv_settinggroup</b>.<br />';

	$DB_site->query("
		CREATE TABLE " . TABLE_PREFIX . "adv_modules (
	  modid smallint(5) unsigned NOT NULL auto_increment,
	  title varchar(100) NOT NULL default '',
	  identifier varchar(100) NOT NULL default '',
	  filename varchar(100) NOT NULL default '',
	  inctype int(3) unsigned NOT NULL default '0',
	  modcol smallint(3) unsigned NOT NULL default '0',
	  displayorder smallint(5) unsigned NOT NULL default '0',
	  templatelist varchar(255) NOT NULL default '',
	  userperms varchar(255) NOT NULL default '',
	  active smallint(3) unsigned NOT NULL default '1',
	  PRIMARY KEY  (modid)
		) TYPE=MyISAM
	");

	echo 'Created table <b>adv_modules</b><br />';

	$DB_site->query("
		CREATE TABLE " . TABLE_PREFIX . "adv_pages (
	  pageid int(10) unsigned NOT NULL auto_increment,
	  title varchar(100) NOT NULL default '',
	  name varchar(100) NOT NULL default '',
	  template varchar(100) NOT NULL default '',
	  modules mediumtext NOT NULL,
	  advanced mediumtext NOT NULL,
	  userperms varchar(255) NOT NULL default '',
	  styleid smallint(5) unsigned NOT NULL default '0',
	  PRIMARY KEY  (pageid)
		) TYPE=MyISAM
	");

	echo 'Created table <b>adv_pages</b><br />';

	echo '<br />';

	$DB_site->query("ALTER TABLE " . TABLE_PREFIX . "language ADD phrasegroup_adv_portal MEDIUMTEXT NOT NULL");
	$DB_site->query("ALTER TABLE " . TABLE_PREFIX . "language ADD phrasegroup_adv_portal_cp MEDIUMTEXT NOT NULL");

	echo "Altered table <b>language</b> to add phrase groups.<br />";

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrasetype VALUES (569, 'adv_portal', 'vBadvanced CMPS', 3)");
	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "phrasetype VALUES (570, 'adv_portal_cp', 'vBadvanced CMPS CP', 3)");

	echo "Added phrasetypes.<br />";

	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "datastore VALUES ('adv_portal_opts', '')");
	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "datastore VALUES ('adv_modules', '')");

	echo "Inserted fields to the datastore table.<br />";


	$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_pages VALUES ('', 'Default / Homepage', 'home', '', '', '', '', 0)");

	echo '<p>Inserted default/home page</p>';

	foreach ($sgarray AS $grouptitle => $order)
	{
		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_settinggroup (grouptitle, displayorder) VALUES ('$grouptitle', '$order')");
	}
	echo 'Inserted advanced setting groups.<br />';

	echo '<br /><a href="' . $scriptfile . '?do=templates">Insert Templates --&gt;</a>';

}

// ##################### Insert Templates #########################
if ($_REQUEST['do'] == 'templates')
{

	foreach ($maintemplates AS $name => $content)
	{
		insert_template($name, $content);
	}

	build_all_styles(0, 0, iif($_REQUEST['only'], 'index.php', $scriptfile . '?do=phrases'));

}

// #################### Insert Phrases ############################
if ($_REQUEST['do'] == 'phrases')
{

	globalize($_REQUEST, array('only' => INT));

	foreach ($mainphrases AS $phrase)
	{
		insert_phrase($phrase['varname'], $phrase['text'], $phrase['ptype']);
	}
	
	build_language(-1);

	if ($only == 1)
	{
		echo '<br /><a href="' . $scriptfile . '?do=settings&amp;phrases=1">Update Setting Phrases --&gt;</a>';
	}
	else
	{
		echo '<br /><a href="' . $scriptfile . '?do=settings">Insert Settings --&gt;</a>';
	}
}

// ######################### Insert Settings #########################
if ($_REQUEST['do'] == 'settings')
{
	foreach ($mainsettings AS $varname => $setting)
	{
		if ($_REQUEST['phrases'] == 1)
		{
			update_setting($varname, $setting['title'], $setting['description']);
		}
		else
		{
			insert_setting($setting['title'], $setting['description'], $varname, $setting['value'], $setting['value'], $setting['optioncode'], $setting['displayorder'], $setting['grouptitle']);
		}
	}

	$settings = $DB_site->query("SELECT varname, value FROM " . TABLE_PREFIX . "adv_setting");
	while ($setting = $DB_site->fetch_array($settings))
	{
		$adv_options["$setting[varname]"] = $setting['value'];
	}
	$DB_site->free_result($settings);
	build_datastore('adv_portal_opts', serialize($adv_options));

	if ($_REQUEST['phrases'] == 1)
	{
		echo 'All Phrases Updated!<br />';
		echo '<br /><a href="index.php">Log into Admin CP --&gt;</a>';
		build_language(-1);
	}
	else
	{
		echo '<br /><a href="' . $scriptfile . '?do=vbahomepage">Insert Modules --&gt;</a>';
	}
}

// ####################### Check for vBa Homepage Settings #####################
if ($_REQUEST['do'] == 'vbahomepage')
{
	$checkopts = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "settinggroup WHERE grouptitle = 'vbadv_index'");

	if ($checkopts['grouptitle'])
	{
		echo 'It has been determined that you have vBadvanced Homepage installed. If you would like, this script can import your vBadvanced Homepage settings for use with vBadvanced CMPS.<br /><br />';

		echo '<a href="' . $scriptfile . '?do=importsettings">Import your vBadvanced Homepage settings. --&gt;</a><br /><br />';
		echo '<a href="' . $scriptfile . '?do=modules">Do NOT import your vBadvanced Homepage settings. --&gt;</a>';
	}
	else
	{
		$_REQUEST['do'] = 'modules';
	}	
}


// ######################### Import vBa Homepage Settings ####################
if ($_REQUEST['do'] == 'importsettings')
{

	update_setting('portal_main_blockbullet', '', '', $vboptions['blockbullet']);
	update_setting('portal_main_shownavbar', '', '', $vboptions['home_navbar']);

	update_setting('portal_news_forumid', '', '', $vboptions['newsforum']);
	update_setting('portal_news_maxposts', '', '', $vboptions['newslimit']);
	update_setting('portal_news_maxchars', '', '', $vboptions['maxnewschars']);
	update_setting('portal_news_allowreplies', '', '', $vboptions['showcomments']);
	update_setting('portal_news_showavatar', '', '', $vboptions['shownewsavatar']);
	update_setting('portal_news_showicon', '', '', $vboptions['shownewsicon']);
	update_setting('portal_news_showrating', '', '', $vboptions['shownewsrating']);
	update_setting('portal_news_showattachments', '', '', $vboptions['shownewsattach']);
	update_setting('portal_news_showprintable', '', '', $vboptions['showprintversion']);
	update_setting('portal_news_showsendfriend', '', '', $vboptions['showsendfriend']);
	update_setting('portal_news_enablehtml', '', '', $vboptions['newshtml']);
	update_setting('portal_news_enablevbcode', '', '', $vboptions['allownewsbbcode']);
	update_setting('portal_news_enablevbimage', '', '', $vboptions['newsimagecode']);
	update_setting('portal_news_enablesmilies', '', '', $vboptions['allownewssmilie']);
	update_setting('portal_news_dateformat', '', '', $vboptions['news_timeformat']);
	update_setting('portal_news_showsubscribed', '', '', $vboptions['news_subscribed']);
	update_setting('portal_news_enablearchive', '', '', $vboptions['newsarchive']);
	update_setting('portal_news_archivepreview', '', '', $vboptions['archive_preview']);

	update_setting('portal_threads_maxthreads', '', '', $vboptions['maxlatethreads']);
	update_setting('portal_threads_maxchars', '', '', $vboptions['maxthreadchars']);
	update_setting('portal_threads_showdate', '', '', $vboptions['showthreaddate']);
	update_setting('portal_threads_showicon', '', '', $vboptions['showthreadicon']);
	update_setting('portal_threads_showsubscribed', '', '', $vboptions['newthreads_subscribed']);
	update_setting('portal_threads_showpreview', '', '', $vboptions['newthreads_preview']);
	update_setting('portal_threads_showforum', '', '', $vboptions['latestthreads_forum']);

	update_setting('portal_poll_forumid', '', '', $vboptions['pollsforum']);
	update_setting('portal_poll_allowreplies', '', '', $vboptions['showpolldiscuss']);
	update_setting('portal_poll_allowsmilies', '', '', $vboptions['showpollsmilies']);

	update_setting('portal_welcome_avatar', '', '', $vboptions['showavatar']);
	update_setting('portal_welcome_newposts', '', '', $vboptions['shownewposts']);
	update_setting('portal_welcome_newpms', '', '', $vboptions['showpm']);

	$settings = $DB_site->query("SELECT varname, value FROM " . TABLE_PREFIX . "adv_setting");
	while ($setting = $DB_site->fetch_array($settings))
	{
		$adv_options["$setting[varname]"] = $setting['value'];
	}
	$DB_site->free_result($settings);
	build_datastore('adv_portal_opts', serialize($adv_options));


	echo '<br /><a href="' . $scriptfile . '?do=modules&amp;import=1">Insert Modules --&gt;</a>';
}

// ######################### Insert Modules ##############################
if ($_REQUEST['do'] == 'modules')
{

	globalize($_REQUEST, array('import' => INT));

	function insert_module($title, $identifier, $filename, $inctype, $modcol, $displayorder, $templatelist, $userperms, $active)
	{
		global $DB_site;

		$DB_site->query("INSERT INTO " . TABLE_PREFIX . "adv_modules (title, identifier, filename, inctype, modcol, displayorder, templatelist, userperms, active) VALUES ('" . addslashes($title) . "', '$identifier', '$filename', '$inctype', '$modcol', '$displayorder', '$templatelist', '$userperms', '$active')");

		echo '<span class="smallfont">Inserted Module - <b>' . $title . '</b></span><br />';
	}
	
	insert_module('Online Users', 'onlineusers', 'onlineusers.php', 0, 0, 4, 'adv_portal_onlineusers, forumhome_loggedinuser', '', iif($import, $vboptions['showonline'], 1));
	insert_module('Buddy List', 'buddylist', 'onlineusers.php', 0, 0, 3, 'adv_portal_buddylist, adv_portal_buddylistbit', '', iif($import, $vboptions['showbuddylist'], 1));
	insert_module('Mini Calendar', '', 'minicalendar.php', 0, 2, 1, 'adv_portal_calendar, calendar_smallmonth_day, calendar_smallmonth_day_other, calendar_smallmonth_header, calendar_smallmonth_week', '', iif($import AND !$vboptions['showcalendar'], 0, 1));
	insert_module('News', 'news', 'news.php', 0, 1, 2, 'adv_portal_news_archivebits, adv_portal_news_readmore, adv_portal_newsbits, bbcode_quote, bbcode_php, postbit_attachment, adv_portal_news_archive,', '', iif($import AND !$vboptions['newsforum'], 0, 1));
	insert_module('Latest Forum Topics', '', 'latesttopics.php', 0, 2, 3, 'adv_portal_latesttopics, adv_portal_latesttopicbits', '', iif($import AND !$vboptions['maxlatethreads'], 0, 1));
	insert_module('Stats', '', 'stats.php', 0, 2, 2, 'adv_portal_stats', '', iif($import, $vboptions['showstats'], 1));
	insert_module('Current Poll', '', 'currentpoll.php', 0, 0, 6, 'adv_portal_poll, adv_portal_pollresult, adv_portal_polloption, adv_portal_polloption_multiple', '', iif($import AND !$vboptions['pollsforum'], 0, 1));
	insert_module('Quick Moderation', '', 'moderate.php', 0, 0, 2, 'adv_portal_moderation', '5,6,7', 1);
	insert_module('Welcome Block', '', 'welcomeblock.php', 0, 0, 1, 'adv_portal_welcomeblock', '', iif($import, $vboptions['home_welcomeblock'], 1));
	insert_module('Search', '', 'search', 1, 0, 5, '', '4,6', iif($import, $vboptions['showsearch'], 1));
	insert_module('News Archive', 'newsarchive', 'news.php', 0, 1, 3, '', '', iif($import AND !$vboptions['newsarchive'], 0, 1));
	insert_module('Custom Page Content', 'custompage', '', 2, 1, 1, '', '', 1);


	$module = array();
	$mods = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules WHERE active = 1");
	while ($mod = $DB_site->fetch_array($mods))
	{
		$module[] = $mod;
	}
	build_datastore('adv_modules', serialize($module));


	foreach ($module AS $amod)
	{
		$modids[] = $amod['modid'];
	}
	$modids = implode(',', $modids);

	$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_pages SET modules = '$modids'");
	echo '<br />Added modules to all pages.<br />';

	echo '<br /><a href="' . $scriptfile . '?do=doimages">Update Image Paths --&gt;</a>';

}



// ######################### Update Image Paths #######################
if ($_REQUEST['do'] == 'doimages')
{

	function do_path_check($imageurl)
	{
		global $vboptions;

		if (@getimagesize($vboptions['bburl'] . '/' . $imageurl))
		{
			$validated = 2;
		}
		elseif (@getimagesize('../' . $imageurl))
		{
			$validated = 1;
		}
		elseif (@fopen($imageurl, r))
		{
			$validated = 1;
		}					
		else
		{
			$validated = 0;
		}
		return $validated;
	}
	
	print_form_header('vbacmps_install', 'updateimages');
	print_table_header('Image Paths', 5);
	print_description_row('For this part of the installation process, we will attempt to update all of your images (icons &amp; smilies) for use with vBadvanced. Please check each image here to make sure that it is displayed properly!<br /><br />
	In most cases, it should not be necessary to make any changes here. If the current image already has the correct path then the "Update" box beside it will not be checked since no changes are necessary. If the current image does not have the correct path, but this script is able to determine the correct path, then the "Update" box will be checked and the correct URL entered into the text box. If neither of the above applies, then you will see the entire box outlined in <font color="red">red</font> and it will be necessary to modify the path yourself.', 0, 5);

	print_table_header('Update Icon Paths', 5);
	print_cells_row(array('Update?', 'Image Title', 'New URL', 'Current Image', 'New Image'), 'thead');

	$icons = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "icon ORDER BY iconid");
	while ($icon = $DB_site->fetch_array($icons))
	{
		construct_hidden_code('title[' . $icon['iconid'] . ']', $icon['title']);
		$icontitle = stripslashes($icon['title']);

		$iconcheck = do_path_check($icon['iconpath']);

		switch($iconcheck)
		{
			case 1:
			$checked = '';
			$class = fetch_row_bgclass();
			$newiconpath = $icon['iconpath'];
			break;

			case 2:
			$checked = 'checked="checked"';
			$class = fetch_row_bgclass();
			$newiconpath = $vboptions['bburl'] . '/' . $icon['iconpath'];
			break;
			
			case 0:
			$class = 'redalert';
			$newiconpath = $icon['iconpath'];
			break;
		}

		echo '<tr class="' . $class . '"><td align="center"><input type="checkbox" name="updateicon[' . $icon['iconid'] . ']" ' . $checked . ' value="1" /></td><td>' . $icontitle . '</td><td align="center"><input name="iconpath[' . $icon['iconid'] . ']" type="text" value="' . $newiconpath . '" size="50" /></td><td align="center"><img src="' . $icon['iconpath'] . '" /></td><td align="center"><img src="' . $newiconpath . '" /></td></tr>';
		
	}
	print_table_break();

	print_table_header('Update Smilie Paths', 5);
  
	print_cells_row(array('Update?', 'Image Title', 'New URL', 'Current Image', 'New Image'), 'thead');
  
	$smilies = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "smilie ORDER BY displayorder");
	while ($smilie = $DB_site->fetch_array($smilies))
	{
  	construct_hidden_code('title[' . $smilie['smilieid'] . ']', $smilie['title']);

		$smiliecheck = do_path_check($smilie['smiliepath']);

		switch($smiliecheck)
		{
			case 1:
			$checked = '';
			$class = fetch_row_bgclass();
			$newsmiliepath = $smilie['smiliepath'];
			break;

			case 2:
			$checked = 'checked="checked"';
			$class = fetch_row_bgclass();
			$newsmiliepath = $vboptions['bburl'] . '/' . $smilie['smiliepath'];
			break;
			
			case 0:
			$class = 'redalert';
			$newsmiliepath = $smilie['smiliepath'];
			break;
		}

		echo '<tr class="' . $class . '"><td align="center"><input type="checkbox" name="updatesmilie[' . $smilie['smilieid'] . ']" ' . $checked . 'value="1" /></td><td>' . $smilie['title'] . '</td><td align="center"><input name="smiliepath[' . $smilie['smilieid'] . ']" type="text" value="' . $newsmiliepath . '" size="50" /></td><td align="center"><img src="' . $smilie['smiliepath'] . '" /></td><td align="center"><img src="' . $newsmiliepath . '" /></td></tr>';
		
	}

	print_table_break();


	if ($vboptions['cleargifurl'] != 'clear.gif')
	{
		print_table_header('Clear Gif URL', 5);
		print_yes_no_row('It has been determined that your "cleargifurl" option has been changed from the default to "' . $vboptions['cleargifurl'] . '". This is <i>most likely</i> from a previous installation of vBadvanced. Since then we have discovered a problem with using full paths to this image. If you would like to change this option back to it\'s original value of "clear.gif", simply make sure that \'Yes\' is checked on the box to the right. If you do not wish to update this image, simply check \'No\' on the box to the right.', 'updateclear', 1);
		print_table_break();
	}


	if ($vboptions['showdeficon'])
	{
		print_table_break();
	
		print_table_header('Default Post Icon', 5);
	  
		print_cells_row(array('Update?', 'Image Title', 'New URL', 'Current Image', 'New Image'), 'thead');
	
		$diconcheck = do_path_check($vboptions['showdeficon']);
	
		switch($diconcheck)
		{
			case 1:
			$checked = '';
			$class = fetch_row_bgclass();
			$newdiconpath = $vboptions['showdeficon'];
			break;
	
			case 2:
			$checked = 'checked="checked"';
			$class = fetch_row_bgclass();
			$newdiconpath = $vboptions['bburl'] . '/' . $vboptions['showdeficon'];
			break;
	
			case 0:
			$class = 'redalert';
			$newdiconpath = $vboptions['showdeficon'];
			break;
		}
	
		echo '<tr class="' . $class . '"><td align="center"><input type="checkbox" name="updatedicon" ' . $checked . ' value="1" /></td><td>deficon</td><td align="center"><input name="deficon" type="text" value="' . $newdiconpath . '" size="50" /></td><td align="center"><img src="' . $vboptions['showdeficon'] . '" /></td><td align="center"><img src="' . $newdiconpath . '" /></td></tr>';
	}

	print_submit_row('Save', 'Reset', 5);
}

// ########################## Do Update Image Paths ####################
if ($_POST['do'] == 'updateimages')
{

	globalize($_POST, array('updateicon', 'iconpath', 'updatesmilie', 'smiliepath'));

	echo '<b>Updating Images...</b><br /><br />';
	// Update Icons
	if (is_array($updateicon))
	{
		echo '<b>Updating Icons...</b><br />';
		foreach ($iconpath AS $key => $val)
		{
			if ($updateicon[$key])
			{
				$DB_site->query("UPDATE " . TABLE_PREFIX . "icon SET iconpath = '$val' WHERE iconid = '$key'");
				echo 'Updated Image: ' . $title[$key] . ' (' . $val . ')<br />';
			}
		}
		build_image_cache('icon');
	}

	// Update Smilies
	if (is_array($updatesmilie))
	{
		echo '<br /><br /><b>Updating Smilies...</b><br />';
		foreach ($smiliepath AS $key => $val)
		{
			if ($updatesmilie[$key])
			{
				$DB_site->query("UPDATE " . TABLE_PREFIX . "smilie SET smiliepath = '$val' WHERE smilieid = '$key'");
				echo 'Updated Image: ' . $title[$key] . ' (' . $val . ')<br />';
			}
		}
		build_image_cache('smilie');
	}

	if ($_POST['updateclear'])
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "setting SET value = 'clear.gif' WHERE varname = 'cleargifurl'");
		echo '<br />clear.gif updated.<br />';
	}

	if ($_POST['updatedicon'])
	{
		$DB_site->query("UPDATE " . TABLE_PREFIX . "setting SET value = '$deficon' WHERE varname = 'showdeficon'");
		echo '<br />showdeficon updated.<br />';
	}

	echo '<br /><b>Images Updated!</b><br /><br />';
	echo '<a href="' . $scriptfile . '?do=finished"><b>Click here to finish the installation --&gt;</b></a><br /><br />';
}


// ############################ Last Step #############################
if ($_REQUEST['do'] == 'finished')
{

	echo 'vBadvanced ' . $scriptname . ' installation complete!<br /><br />';

	echo '<font size="3" color="red"><b>You should now delete this file from your admincp directory.<br />Leaving this file here could be a security risk!</b></font><br /><br />';

	echo '<a href="index.php"><b>Click here to log into your Admin CP. --&gt;</b></a><br />';

}

// ########################### Upgrade ###############################
if ($_REQUEST['do'] == 'upgrade')
{

  $DB_site->reporterror = 0;
	if (!$check = $DB_site->query_first("SELECT COUNT(*) AS count FROM " . TABLE_PREFIX . "adv_setting WHERE varname LIKE 'portal_%'"))
	{
		echo 'vBadvanced ' . $scriptname . ' is not installed! Please choose the option to install from the main page.';
		exit;
	}
	$check2 = $DB_site->query_first("SELECT * FROM " . TABLE_PREFIX . "adv_setting WHERE varname = 'portal_version'");
	if ($check2['value'] == '1.0.0')
	{
		echo 'You are already running the current version of vBadvanced ' . $scriptname . '!';
		exit;
	}
	
	if (!$check2['value'])
	{
		$check2['value'] = 'RC1';
	}

	switch($check2['value'])
	{
		case 'RC1':

		$centercols = array();
		$leftcols = array();
	
		$modules = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules");
		while ($module = $DB_site->fetch_array($modules))
		{
			switch($module['modcol'])
			{
				case 0:
					$centercols[] = $module['modid'];
					break;
				case 1:
					$leftcols[] = $module['modid'];
					break;
			}
		}
	
		if (!empty($leftcols))
		{
			$leftcols = implode(',', $leftcols);
			$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_modules SET modcol = '0' WHERE modid IN ($leftcols)");
		}
	
		if (!empty($centercols))
		{
			$centercols = implode(',', $centercols);
			$DB_site->query("UPDATE " . TABLE_PREFIX . "adv_modules SET modcol = 1 WHERE modid IN ($centercols)");
		}
	
		$mods = $DB_site->query("SELECT * FROM " . TABLE_PREFIX . "adv_modules WHERE active = '1' ORDER BY modcol, displayorder");
		while ($mod = $DB_site->fetch_array($mods))
		{
			$module[] = $mod;
		}
		build_datastore('adv_modules', serialize($module));
	
		foreach ($mainphrases AS $phrase)
		{
			if (in_array($phrase['varname'], array('not_a_member_yet_register_now', 'vote', 'powered_by_vbadvanced_cmps', 'adv_portal_must_enter_x_for_page', 'adv_portal_must_choose_title_for_module')))
			{
				insert_phrase($phrase['varname'], $phrase['text'], $phrase['ptype']);
			}
		}
	
		// Remove extra printable version phrase if necessary
		$prphrase = array();
		$printer = $DB_site->query("SELECT phraseid FROM " . TABLE_PREFIX . "phrase WHERE varname = 'show_printable_version'");
		$pcount = 0;
		while ($print = $DB_site->fetch_array($printer))
		{
			$pcount++;
			if ($pcount > 1)
			{
				$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrase WHERE phraseid = '$print[phraseid]'");
			}
		}
	
		foreach ($mainsettings AS $varname => $setting)
		{
			if (in_array($varname, array('portal_version')))
			{
				insert_setting($setting['title'], $setting['description'], $varname, $setting['value'], $setting['value'], $setting['optioncode'], $setting['displayorder'], $setting['grouptitle']);
			}
		}

	case 'RC2':

		$DB_site->query("UPDATE " . TABLE_PREFIX . "phrase SET varname = 'show_printable_version' WHERE varname = 'show_printable_verison'");

		update_setting('portal_version', '', '', '1.0.0');

		$settings = $DB_site->query("SELECT varname, value FROM " . TABLE_PREFIX . "adv_setting");
		while ($setting = $DB_site->fetch_array($settings))
		{
			$adv_options["$setting[varname]"] = $setting['value'];
		}
		$DB_site->free_result($settings);
		build_datastore('adv_portal_opts', serialize($adv_options));

		foreach ($mainphrases AS $phrase)
		{
			if (in_array($phrase['varname'], array('powered_by_vbadvanced_cmps')))
			{
				update_phrase($phrase['varname'], $phrase['text']);
			}
		}

		foreach ($maintemplates AS $name => $content)
		{
			insert_template($name, $content);
		}

		build_language(-1);
	
		build_all_styles(0, 0, 'index.php');
	}
}



// ######################### Uninstall #########################
if ($_REQUEST['do'] == 'uninstall')
{
	globalize($_REQUEST, array('step' => STR));

	switch ($step)
	{
		case 'phrases':
			foreach ($mainphrases AS $phrase)
			{
				kill_phrase($phrase['varname'], $phrase['ptype']);
			}

			echo '<p><a href="' . $scriptfile . '?do=uninstall&amp;step=templates">Next Step --&gt;</b></a></p>';
			break;

		case 'templates':

			foreach ($maintemplates AS $name => $content)
			{
				kill_template($name);
			}
	
			echo '<p><a href="' . $scriptfile . '?do=uninstall&amp;step=settings">Next Step --&gt;</b></a></p>';
			break;

		case 'settings':

			foreach ($sgarray AS $grouptitle => $order)
			{
				$DB_site->query("DELETE FROM " . TABLE_PREFIX . "adv_settinggroup WHERE grouptitle = '$grouptitle'");
			}
			echo 'Removed advanced setting groups.<br /><br />';

			foreach ($mainsettings AS $varname => $setting)
			{
				kill_setting($varname);
			}
			echo '<p><a href="' . $scriptfile . '?do=uninstall&amp;step=tables">Next Step --&gt;</b></a></p>';

			break;

		case 'tables':

			$DB_site->reporterror = 0;

			$DB_site->query("DROP TABLE " . TABLE_PREFIX . "adv_modules");
			$DB_site->query("DROP TABLE " . TABLE_PREFIX . "adv_pages");


			$DB_site->query("ALTER TABLE " . TABLE_PREFIX . "language DROP phrasegroup_adv_portal");
			$DB_site->query("ALTER TABLE " . TABLE_PREFIX . "language DROP phrasegroup_adv_portal_cp");
		
			echo "Altered table <b>language</b> to drop phrase groups.<br />";
		
			$DB_site->query("DELETE FROM " . TABLE_PREFIX . "phrasetype WHERE phrasetypeid IN(569,570)");
		
			echo "Removed phrasetypes.<br />";
		
			$DB_site->query("DELETE FROM " . TABLE_PREFIX . "datastore WHERE title IN('adv_portal_opts', 'adv_modules')");
		
			echo "Removed fields from the datastore table.<br />";
		
	
			echo 'vBadvanced ' . $scriptname . ' Sucessfully Uninstalled.<br /> <br /><a href="index.php">Log Into Admin CP --&gt;</b></a>';

			echo '<br /><br /><a href="' . $scriptfile . '">Click here to re-install --&gt;</a>';

			break;

		default:
			echo '<p>We\'re sorry you didn\'t like vBadvanced ' . $scriptname . '. Running this script will remove all changes that were made for vBadvanced ' . $scriptname . '.</p>';
			echo '<p><a href="' . $scriptfile . '?do=uninstall&amp;step=phrases">Click here to uninstall --&gt;</b></a></p>';

	}
}

print_cp_footer();

?>