<?php
$blocks_modules[randompic] = array(
	'title' => 'Random Pictures',
	'func_display' => 'blocks_randompic_block',
	'text_type' => 'Random Pictures',
	'text_type_long' => 'Random Pictures',
	'text_content' => 'Random Pictures',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_randompic_block($row) {
	RandomPic();
}
?>