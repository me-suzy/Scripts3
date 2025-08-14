<?php
$forumblocks_modules[ephem] = array(
	'title' => 'Ephemerids',
	'func_display' => 'forumblocks_ephem_block',
	'text_type' => 'Ephemerids',
	'text_type_long' => 'Ephemerids',
	'text_content' => 'Ephemerids',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function forumblocks_ephem_block($row) {
  global $DB_site,$prefix,$themesidebox,$block_content;
    $today = getdate();
    $eday = $today[mday];
    $emonth = $today[mon];
    $result = $DB_site->query("select yid, content from $prefix"._ephem." where did='$eday' AND mid='$emonth'");
    $block_title = $row[title];
    $block_content = "<b>"._ONEDAY."</b><br>";
    while(list($yid, $content) = $DB_site->fetch_array($result)) {
        if ($cnt==1) {
    	    $block_content .= "<br><br>";
	}
	$block_content .= "<b>$yid</b><br>";
    	$block_content .= "$content";
	$cnt = 1;
    }
    	
	eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
 
}
?>