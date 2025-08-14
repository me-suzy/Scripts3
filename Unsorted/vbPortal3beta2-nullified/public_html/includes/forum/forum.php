<?php
$forumblocks_modules[forum] = array(
	'title' => 'forum',
	'func_display' => 'forumblocks_forum_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'forum',
	'text_type_long' => '',
	'text_content' => 'Forum Menu',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
	'has_template' => false
);
function forumblocks_forum_block($row) {
    global $DB_site,$prefix,$nukepath,$nukeurl,$themesidebox,$block_content;
    $result = $DB_site->query("select title, content from nuke_advblocks where bkey='main'");
    while(list($title, $content) = $DB_site->fetch_array($result)) {
	$content = "<smallfont>$content";
	if(isset($name)) {
	} else {
	    $handle=opendir($nukepath .'/modules');
	    while ($file = readdir($handle)) {
		if ( (!ereg("[.]",$file)) ) {
		    $moduleslist .= "$file ";
		}
	    }
	    closedir($handle);
	    $moduleslist = explode(" ", $moduleslist);
	    sort($moduleslist);
	    for ($i=0; $i < sizeof($moduleslist); $i++) {
		if ($moduleslist[$i]!="") {
		    $dummy = ereg("NS-",$moduleslist[$i]);
		    if ($dummy == "") {
		    $xname = ereg_replace("_", " ", "$moduleslist[$i]");
    		    if ($file == "") {
			$file = "index";
		    }
		    if ($a == 0) {
			$content .= "<br><b>"._OTHEROPTIONS."</b><br>";
		    }
		    $content .= "<smallfont><strong><big>&middot;</big></strong>&nbsp;<a href=\"$nukeurl/modules.php?op=modload&amp;name=$moduleslist[$i]&amp;file=$file\">$xname</a></smallfont><br>";
		    $a = 1;
		    }
    		}
	    }
	}
	$content .= "</smallfont>";
	$block_title = $row[title];
	$block_content = $content;
	eval("\$themesidebox .= \"".gettemplate("P_themesidebox_left")."\";");
    }

}

?>