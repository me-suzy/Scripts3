<?php
$forumblocks_modules[text] = array(
	'title' => 'Plain Text',
	'func_display' => 'forumblocks_text_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'Text',
	'text_type_long' => 'Plain Text',
	'text_content' => 'Plain Text',
	'support_nukecode' => true,
	'allow_create' => true,
	'allow_delete' => true,
	'form_url' => false,
	'form_content' => true,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function forumblocks_text_block($row) {
    global $themesidebox,$nuke_title,$block_content;
	$block_title = $row[title];
    $block_content = $row[content];
	eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
}
?>