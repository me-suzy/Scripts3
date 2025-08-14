<?php
$forumblocks_modules[html] = array(
	'title' => 'HTML',
	'func_display' => 'forumblocks_html_block',
	'text_type' => 'HTML',
	'text_type_long' => 'HTML',
	'text_content' => 'HTML',
	'support_nukecode' => false,
	'allow_create' => true,
	'allow_delete' => true,
	'form_url' => false,
	'form_content' => true,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function forumblocks_html_block($row) {
	global $themesidebox,$nuke_title,$block_content;
	$block_title = $row[title];
	$block_content =$row[content];
	eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");

}
?>