<?php
$forumblocks_modules[php] = array(
	'title' => 'PHP Script',
	'func_display' => 'forumblocks_php_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'PHP',
	'text_type_long' => 'PHP Script',
	'text_content' => 'PHP Script',
	'support_nukecode' => false,
	'allow_create' => true,
	'allow_delete' => true,
	'form_url' => true,
	'form_content' => true,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function forumblocks_php_block($row) {
    global $themesidebox,$nuke_title,$block_content;
	ob_start();
	print eval($row[content]);
	$row[content] = ob_get_contents();
	ob_end_clean();
	$block_title = $row[title];
	$block_content = $row[content];
	eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
}
?>