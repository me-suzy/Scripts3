<?php
$centerblocks_modules[text] = array(
	'title' => 'Plain Text',
	'func_display' => 'centerblocks_text_block',
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

function centerblocks_text_block($row) {
   $block_title = $row[title];
   $block_content = $row[content];
   eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
	
}
?>