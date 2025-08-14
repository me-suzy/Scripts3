<?php
/************************************************************************/
/* vbPortal: CMS mod for vBulletin                                      */
/* vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     */
/* ===========================                                          */
/* vbPortal by wajones                                                  */
/* Copyright (c) 2001 by William A. Jones                               */
/* http://www.phpportals.com                                            */
/* ===========================                                          */
/* Based on PHP-NUKE: Web Portal System                                 */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

include("auth.inc.php");
if (!IsSet($mainfile)) { include ("mainfile.php"); }
$permissions=getpermissions();
if (!$permissions['cancontrolpanel']) {
	  echo standardredirect("Attempt to access Admin Failed","$nukeurl/index.php?s=$session[sessionhash]");  
}


if(!isset($op)) { $op = "adminMain"; }
$hlpfile = "manual/admin.html";
getvbpvars();
/*********************************************************/
/* Login Function                                        */
/*********************************************************/

function login() {

    include ("header.php");
    OpenTable();
	
    echo "<center><font size=\"4\"><b>"._ADMINLOGIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\">"
	."<tr><td>"._ADMINID."</td>"
	."<td><input type=\"text\" NAME=\"aid\" SIZE=\"20\" MAXLENGTH=\"20\"></td></tr>"
	."<tr><td>"._PASSWORD."</td>"
	."<td><input type=\"password\" NAME=\"pwd\" SIZE=\"20\" MAXLENGTH=\"18\"></td></tr>"
	."<tr><td>"
	."<input type=\"hidden\" NAME=\"op\" value=\"login\">"
	."<input type=\"submit\" VALUE=\""._LOGIN."\">"
	."</td></tr></table>"
	."</form>";
	
	CloseTable();
    include ("footer.php");

}

function deleteNotice($id, $table, $op_back) {
    mysql_query("delete from $table WHERE id = $id");
    Header("Location: admin.php?op=$op_back");
}

/*********************************************************/
/* Administration Menu Function                          */
/*********************************************************/

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic, $adminimg;
   	   if ($admingraphic == 1) {
			$img = "<img src=\"$adminimg$image\" border=\"0\" alt=\"\"></a><br>";
			$close = "";
		} else {
			$image = "";
			$close = "</a>";
		}
		echo "<td align=\"center\"><font size=\"2\"><a href=\"$url\">$img<b>$title</b>$close</font></td>";
		if ($counter == 5) {
			echo "</tr><tr>";
			$counter = 0;
		} else {
			$counter++;
		}
	}

function GraphicAdmin($hlpfile) {
    global $aid, $admingraphic, $adminimg, $language, $admin, $banners, $prefix;
	$permissions=getpermissions();
	if (!$permissions['cancontrolpanel']) {
		 echo standardredirect("Attempt to access Admin Failed","$nukeurl/index.php?s=$session[sessionhash]"); 
	}else{
		$radminsuper=1;
		OpenTable();	
		echo "<center><font size=\"4\"><b><a href=\"admin.php\">"._ADMINMENU."</a></b>";
		echo"&nbsp;&nbsp;&nbsp;<b><a href=\"admin.php?op=BannersAdmin\">"._BANNERSADMIN."</a></b>";
		if (!$hlpfile) {
		} else {
			echo "</font><br><br>[ <a href=\"javascript:openwindow()\">"._ONLINEMANUAL."</a> ]</center>";
		}
		echo "<br><br>";
		echo"<table border=\"0\" width=\"100%\" cellspacing=\"1\"><tr>";
		$linksdir = dir("admin/links");
		while($func=$linksdir->read()) {
			if(substr($func, 0, 6) == "links.") {
				$menulist .= "$func ";
		}
		}
		closedir($linksdir->handle);
		$menulist = explode(" ", $menulist);
		sort($menulist);
		for ($i=0; $i < sizeof($menulist); $i++) {
		if($menulist[$i]!="") {
			$counter = 0;
			include($linksdir->path."/$menulist[$i]");
		}
		}
		adminmenu("admin.php?op=logout", ""._ADMINLOGOUT."", "exit.gif");
		echo"</tr></table></center>";
		CloseTable();
		echo "<br>";
		
	}
}

/*********************************************************/
/* Administration Main Function                          */
/*********************************************************/

function adminMain() {
    global $language, $hlpfile, $admin, $admart, $aid, $prefix;
	$permissions=getpermissions();
	if (!$permissions['cancontrolpanel']) {
		 echo standardredirect("Attempt to access Admin Failed","$nukeurl/index.php?s=$session[sessionhash]"); 
    }else{

		$hlpfile = "manual/admin.html";
		include ("header.php");
		$dummy = 0;
		GraphicAdmin($hlpfile);
		echo "<br>";
		OpenTable();
		echo "Credits";
		CloseTable();
		include ("footer.php");
	}
}




if($admintest) {

    switch($op) {

	case "deleteNotice":
	deleteNotice($id, $table, $op_back);
	break;

	case "GraphicAdmin":
        GraphicAdmin($hlpfile);
        break;

	case "adminMain":
	adminMain();
	break;

	case "logout":
	setcookie("admin","$admin",time()-60);
	include("header.php");
	OpenTable();
	echo "<center><font size=\"4\"><b>"._YOUARELOGGEDOUT."</b></font></center>";
	CloseTable();
	include("footer.php");
	break;

	case "login";
	unset($op);

	default:
	$casedir = dir("admin/case");
	while($func=$casedir->read()) {
	    if(substr($func, 0, 5) == "case.") { 
		include($casedir->path."/$func");
	    }
	}
	closedir($casedir->handle);
	break;

	}

} else {
   
   login();

}

?>