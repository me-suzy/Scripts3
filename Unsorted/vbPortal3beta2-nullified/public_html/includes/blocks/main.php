<?php
$blocks_modules[main] = array(
	'title' => 'Main',
	'func_display' => 'blocks_main_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'main',
	'text_type_long' => '',
	'text_content' => 'Main Menu',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => true,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_main_block($row){
global $block_sidetemplate;
	global  $DB_site,$nukeurl,$nukepath,$prefix,$block_sidetemplate;
    $result = $DB_site->query("select title as mtitle , content as mcontent from nuke_advblocks where bkey='main'");
    while(list($mtitle, $mcontent) = $DB_site->fetch_array($result)) {
	$block_content = "<smallfont>$mcontent";
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
			$block_content .= "<br><b>"._OTHEROPTIONS."</b><br>";
		    }
		    $block_content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$nukeurl/modules.php?op=modload&amp;name=$moduleslist[$i]&amp;file=$file\">$xname</a><br>";
		    $a = 1;
		    }
    		}
	    }
	}
	$block_content .= "</smallfont>";
	$block_title = $mtitle;
	eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
	
	}
	
}
?>