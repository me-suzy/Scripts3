<?php
$blocks_modules[pminfo] = array(
	'title' => 'Private Messages',
	'func_display' => 'blocks_pminfo_block',
	'text_type' => 'pminfo',
	'text_type_long' => 'Private Messages',
	'text_content' => 'Private Messages',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_pminfo_block($row) {
global $bburl,$pminfo,$DB_site,$bbuserinfo,$permissions,$block_sidetemplate;
if ($permissions['canusepm'] and $bbuserinfo['receivepm']) {
  $allpm=$DB_site->query_first("SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid]");
  $newpm=$DB_site->query_first("SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid] AND dateline>$bbuserinfo[lastvisit] AND folderid=0");
  $unreadpm=$DB_site->query_first("SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid] AND messageread=0 AND folderid=0");
  $pminfo .="<tr id=\"cat\"><td bgcolor=\"{firstaltcolor}\"><smallfont><b>$bbuserinfo[username]</b> - You have $newpm[messages] new message(s) since your last visit.<br>\n";
  $pminfo .="(You have $unreadpm[messages] unread messages and $allpm[messages] total messages in all your folders.) <a href=\"$bburl/private.php?s=$session[sessionhash]\" title=\"Click Here to Open Your Private Messages $inboxname\"></smallfont><smallfont color=\"{pagetextcolor}\"> [Go...Read]</a></smallfont></td></tr>\n";

} else {
  $pminfo='';
}

 $block_title = $row[title];
 $block_content = $pminfo;
 eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
}
?>