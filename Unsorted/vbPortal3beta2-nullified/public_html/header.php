<?php

/************************************************************************/
/* vbPortal Lite: CMS mod for vBulletin                                 */
/* vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     */
/* ===========================                                          */
/* vbPortal by wajones                                                  */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/* ===========================                                          */
/* Based partially on PHP-NUKE: Web Portal System                       */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
function head(){
include("config.php");
global $bbtitle,$index,$pollsactive,$banners,$imageurl,$Pmenu,$index,$headinclude,$user,$sitename,$slogan,$prefix,$topic,$hlpfile;global $datetimestr,$breadcrumb,$userimg,$bburl,$nukeurl,$Version_Num,$bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$textcolor1,$textcolor2,$menu,$banners,$bid,$imageurl,$imagesfolder,$headinclude,$bannercontent,$pagebgcolor;
// Thanks to hideoutguy for pointing out an error here...
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
$helpfile=$hlpfile;
if ($banners) {
     include("banners.php");
	 $showbanners = "<a href=\"$nukeurl/banners.php?op=click&bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"1\" alt=\"\"></a>";
}else{
     $showbanners = "";
}

//////////////////////////////
// The $Pmenu evals the proper menu template that has beeb declared in each module.
// The $Breadcrumb is the well the breadcrumb menu on each page you see.
// This example from the Links Script  
// global  $Pmenu,$breadcrumb;
// $Pmenu="P_thememenu_weblinks";
// $breadcrumb="WebLinks";
//////////////////////////////
// If no top menu is declared then the standard undefined menu is used
if ($Pmenu==""){
	$Pmenu = "P_thememenu_undefined";
}

// The date string you see in the menu bar.
$datetimestr = strftime('%A, %B %e, %Y %I:%M %p %Z');

eval("\$thememenu = \"".gettemplate($Pmenu)."\";");

eval("\$themeheader = \"".gettemplate("P_themeheader")."\";");
eval("dooutput(\"".gettemplate('P_home')."\");");

// Initialize the lefts blocks
advblocks(left);

// Center column top for all except forum
echo "</td><td valign=\"top\" align=\"center\" width=\"100%\">";

// Have we set a breadcrumb menu
if ($breadcrumb!=""){
   eval("dooutput(\"".gettemplate("P_breadcrumb")."\");");
}

}

head();

// This is for the stats
include("counter.php");
?>