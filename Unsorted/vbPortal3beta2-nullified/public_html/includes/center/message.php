<?php
$centerblocks_modules[messagebox] = array(
	'title' => 'Languages',
	'func_display' => 'centerblocks_message_block',
	'text_type' => 'Message',
	'text_type_long' => 'Message',
	'text_content' => 'Message',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true,
    'has_templates' => false
    );
function centerblocks_message_block($row) {
    global $bgcolor1, $bgcolor2, $user, $admin, $cookie, $textcolor2, $prefix,$DB_site,$bbuserinfo;
    $result = $DB_site->query("select title, content, date, expire, view from $prefix"._message." where active='1'");
    if ($DB_site->num_rows($result) == 0) {
	return;
    } else {
	list($title, $content, $mdate, $expire, $view) = mysql_fetch_row($result);
	if ($title != "" && $content != "") {
       	if ($expire == 0) {
			$remain = _UNLIMITED;
	    } else {
			$etime = (($mdate+$expire)-time())/3600;
			$etime = (int)$etime;
			if ($etime < 1) {
				$remain = _EXPIRELESSHOUR;
			} else {
				$remain = ""._EXPIREIN." $etime "._HOURS."";
			}
	    }
		$block_title = $title;
        $block_content = $content;
       	$messedit = "Edit";
		$bmess="";	
		
	    if ($view == 4 AND is_admin($admin)) {
			$mview = _MVIEWADMIN;
			eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
		 } elseif ($view == 3 AND $bbuserinfo['userid']!=0 || is_admin($admin)) {
			if (is_admin($admin)) {
				 $mview = _MVIEWUSERS;
				 $block_content .= "<br><br><center><font size='1'>[ $mview - $remain - <a href='admin.php?op=messages'>$messedit</a>]</font></center>";
               
			}
			eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
		} elseif ($view == 2 AND $bbuserinfo['userid']==0 || is_admin($admin)) {
			if (is_admin($admin)) {
				 $mview = _MVIEWANON;
				 $block_content .= "<br><br><center><font size='1'>[ $mview - $remain - <a href='admin.php?op=messages'>$messedit</a>]</font></center>";
               
			}
			eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
        } elseif ($view == 1) {
       		if (is_admin($admin)) {
				 $mview = _MVIEWALL;
				 $block_content .= "<br><br><center><font size='1'>[ $mview - $remain - <a href='admin.php?op=messages'>$messedit</a>]</font></center>";
               
		 	}
			eval("dooutput(\"".gettemplate('P_themecenterbox')."\");");
       }
       	
        if ($expire != 0) {
	    	$past = time()-$expire;
		if ($mdate < $past) {
		    $result = mysql_query("update $prefix"._message." set active='0'");
		}
	    }
	}
    }
}
?>