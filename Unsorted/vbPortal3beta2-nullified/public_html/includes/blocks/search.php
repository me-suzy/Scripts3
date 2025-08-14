<?php
$blocks_modules[search] = array(
	'title' => 'Search Box',
	'func_display' => 'blocks_search_block',
	'text_type' => 'Search',
	'text_type_long' => 'Search Box',
	'text_content' => 'Search Box',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_search_block($row) {
	global $bburl,$block_sidetemplate;
	$block_content = "<table border=\"0\"><tr><td><smallfont>
	<form style=\"margin-bottom:-2;\" action=\"$bburl/search.php\" method=\"post\">
	<input type=\"hidden\" name=\"s\" value=\"$session[sessionhash]\">
	<input type=\"hidden\" name=\"forumchoice\" value=\"- 1\">
	<input type=\"hidden\" name=\"searchin\" value=\"subject\">
	<input type=\"hidden\" name=\"searchdate\" value=\"-1\">
	<input type=\"hidden\" name=\"action\" value=\"simplesearch\">
	<input type=\"hidden\" name=\"booleanand\" value=\"yes\">
	<input class=\"search\" onFocus=\"this.value='';\" type=\"text\" name=\"query\" value=\"For...\" size=\"10\" maxlength=\"150\" >
	<input type=\"hidden\" name=\"action\" value=\"simplesearch\">
	<input type=\"image\" src=\"{imagesfolder}/go.gif\" name=\"Submit\" border=\"0\" align=\"absbottom\">
    </form><b><a href=\"$bburl/search.php?s=$session[sessionhash]\">Advanced Search</a></b>
    </smallfont></td></tr></table>";
	$block_title = $row[title];
	eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
	
}
?>