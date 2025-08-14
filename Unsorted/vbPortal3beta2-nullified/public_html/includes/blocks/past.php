<?php
$blocks_modules[past] = array(
	'title' => 'Past Articles',
	'func_display' => 'blocks_past_block',
	'text_type' => 'Past',
	'text_type_long' => 'Past Articles',
	'text_content' => 'Past Articles',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => true
    );

function blocks_past_block($row) {
global $DB_site,$bburl,$newsforum,$oldnewslimit,$newslimit,$block_sidetemplate;
$P_oldnewsbits ="";
$oldnewslimit=$newslimit+$oldnewslimit;
$oldthreads=$DB_site->query("SELECT * FROM thread WHERE forumid=$newsforum ORDER BY dateline DESC LIMIT $oldnewslimit");
$counter =0;
$newscount = 0;
while ($thread=$DB_site->fetch_array($oldthreads)) {
	if ($thread[visible]){
    	unset($thread[movedprefix]);
    	unset($thread[typeprefix]);
    	if ($thread[open]==10) {
      		// thread has been moved!
      		$thread[threadid]=$thread[pollid];
      		$thread[icon]="1";
      		$thread[movedprefix]=$movedthreadprefix;
			$thread[pollid]=0;
    	}
		$oldnewstitle=$thread[title];
		$oldnewsicon=$thread[iconid];
		$oldnewsthreadid=$thread["threadid"];
        if (($counter++ % 2) != 0) {
			$vbp_bc='{firstaltcolor}';
		} else {
			$vbp_bc='{secondaltcolor}';
		}
 	if ($newscount >= $newslimit){
		 if($row[templates]) {
			eval("\$P_newspastbit .= \"".gettemplate('P_newspastbit')."\";");
         }else{
			$P_newspastbit .= "<tr><td bgcolor='$vbp_bc'><img src=\"{imagesfolder}/icons/icon$oldnewsicon.gif\"><smallfont><a href=\"$bburl/showthread.php?threadid=$oldnewsthreadid\">  $oldnewstitle</a></smallfont><br></td></tr>\n";
         }
	}
	 $newscount++;
	}
}
 
 $block_title = $nuke_title = $row[title] ." (<a href='$bburl/forumdisplay.php?s=$session[sessionhash]&forumid=$newsforum'><smallfont color='{tableheadtextcolor}'>More</smallfont></a>)";
 $block_content = $P_newspastbit;
 eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");

}
?>