<?php
error_reporting(7);
/************************************************************************/
/* vbPortal: CMS mod for vBulletin                                      */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/*                                                                      */
/************************************************************************/
include("../config.php");
global $nukepath,$bbuserinfo,$nukeurl,$foot1,$foot2,$foot3,$foot4;
global $Allow_Forum_Leftcolumn, $Forum_Default_Leftcolumn,$newsforum,$pollsforum;
chdir($nukepath . "/");

if ($banners) {
     global $banners, $bid, $imageurl;
	include("banners.php");
	 $showbanners = "<a href=\"$nukeurl/banners.php?op=click&bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"\"></a>";
}else{
     $showbanners = "";
}
eval("\$themeheader = \"".gettemplate("P_themeheader")."\";");
eval("\$thememenu = \"".gettemplate('P_thememenu_forum')."\";");
if ($Allow_Forum_Leftcolumn==1) {
if (($bbuserinfo['userid']!=0 and $bbshowleftcolumn)or ($bbuserinfo['userid']==0 and  $Forum_Default_Leftcolumn==1)){
   	include($nukepath . "/language/lang-$language.php");
	require($nukepath . "/includes/forumblocks.php");
	forumblocks(left);
    if ($action==newreply or $action==newthread or $threadid !=0){
         $themeleftcolumn ="";
         $closeleftcolumn ="";
	}else{
        eval("\$themeleftcolumn  = \"".gettemplate('P_ForumLeftColumn')."\";");
        $closeleftcolumn = "<br></td></tr></table>";
    }
	$switchdisplay="| <a href=\"$nukeurl/user.php?s=$session[sessionhash]&action=switchdisplay\"><b>Hide Leftcolumn</b></a>";
}else{
    if ($bbuserinfo['userid']!=0){
      $switchdisplay="| <a href=\"$nukeurl/user.php?s=$session[sessionhash]&action=switchdisplay\"><b>Show Leftcolumn</b></a>";
	}else{
	  $switchdisplay="";
	}
}
}else{
	$switchdisplay="";
	$showleftcolumn =0;
}

chdir($vbpath . "/"); 

?>