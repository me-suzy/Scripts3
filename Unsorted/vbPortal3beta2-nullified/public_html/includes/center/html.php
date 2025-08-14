<?php
$centerblocks_modules[html] = array(
	'title' => 'HTML',
	'func_display' => 'centerblocks_html_block',
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
    'show_preview' => true,
    'has_templates' => false
    );

function centerblocks_html_block($row) {
	$block_title = $row[title];
    $block_content = $row[content];
    eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
	  
}
?>