<?php
$blocks_modules[admin] = array(
	'title' => 'Admin Block',
	'func_display' => 'blocks_admin_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'Admin',
	'text_type_long' => '',
	'text_content' => 'Admin Menu',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => true,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_admin_block($row) {
global $bburl,$nukeurl,$DB_site,$admin, $prefix,$newsforum,$block_sidetemplate;
if (ismoderator($foruminfo[forumid])) {
$block_title = $row[title];
$block_content ="";
$result = $DB_site->query("select title, content as content from $prefix"._advblocks." where bkey='admin'");
	while(list($title, $content) = $DB_site->fetch_array($result)) {
      $block_content .= "<smallfont>$content</smallfont><br>";
	}
	$block_content .= "<smallfont><b>Waiting Moderation</b></smallfont>";
    $num = $DB_site->num_rows($DB_site->query("SELECT * FROM thread WHERE forumid=$newsforum AND visible=0 "));
	$block_content .= "<br><smallfont>";
	$block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$bburl/moderator.php?s=$session[sessionhash]&forumid=$forumid&action=modposts\">"._SUBMISSIONS."</a>: $num<br>";
	$result = $DB_site->query("select * from $prefix"._reviews."_add");
	$num = $DB_site->num_rows($result);
	$block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=reviews\">"._WREVIEWS."</a>: $num<br>";
	$result = $DB_site->query("select * from $prefix"._links_newlink."");
	$num = $DB_site->num_rows($result);
	$block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=links\">"._WLINKS."</a>: $num<br>";
    $result=$DB_site->query("SELECT * FROM user WHERE usergroupid=4");
    $waiting = $DB_site->num_rows($result);
    $block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$bburl/admin/user.php?s=$session[sessionhash]&action=moderate\">User Moderation</a>: $waiting<br>";

	// Uncomment the following to enable My_eGallery New Media display //
	/*
	$wait = mysql_fetch_array(mysql_query("SELECT COUNT(pid) AS total FROM $prefix"._gallery_pictures_newpicture.""));
	include ("admin/modules/gallery/config.php");
	global $language, $newlang;
	if (isset($newlang))
		$language = $newlang;
	if(file_exists("$languagepath/lang-$language.php")) {
		include("$languagepath/lang-$language.php");
	}
	else {
		include("$languagepath/lang-english.php");
	}
	$block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$adminurl&amp;do=validnew&amp;type=checknew\">"._GALPOSTEDMEDIA.'</a> :'.$wait[total].'<br>';
	*/
	// End of My_eGallery New media routine //
    $block_content .= "</smallfont>";
	eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
    }
}
?>