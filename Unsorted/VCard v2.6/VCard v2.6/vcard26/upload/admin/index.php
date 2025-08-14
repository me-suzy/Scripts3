<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
require('./lib.inc.php');
$debug = 0;
if ($action == 'frames')
{
?>
<html>
<head>
	<title>vCard - <?php echo "$msg_admin_controlpanel"; ?></title>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=<?php echo "$admin_charset"; ?>">
</head>
<frameset cols="175,*" rows="*" border="0" frameborder="0"> 
	<frame src="index.php?action=m<?php echo "&s=$s"; ?>" name="menu">
	<frameset rows="25,*" framespacing="0" frameborder="0"> 
		<frame src="index.php?action=h<?php echo "&s=$s"; ?>" name="header" NAME="top" SCROLLING="No" NORESIZE MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0">
		<frame src="main.php?<?php echo "s=$s"; ?>" name="main">
	</frameset>
</frameset>
<noframes>
<body bgcolor="#FFFFFF">

</body>
</noframes>
</html>
<?php
	exit;
}

if ($action == 'h')
{
	dothml_pageheader();
?>
<table width="100%" height="100%" border="0">
  <tbody>
  <tr valign="middle">
    <td width="100%" nowrap><font size="1"><a href="./" target="_blank" onMouseOver="window.status=' Control Panel ';return true" onMouseOut="window.status='';return true"><?php echo "$msg_admin_controlpanel ($vcardversion)"; ?></a></font></td>
    <td nowrap><font size="1"><?php echo "$timenow"; ?></font> - <A href="../" target="_blank" onMouseOver="window.status=' Acess your greeting card home page ';return true" onMouseOut="window.status='';return true"><b><font size="1">[<?php echo "$msg_admin_homepage"; ?>]</font></b></a> <a href="index.php?action=logout&s=<?PHP echo $s;?>" target="_top" onMouseOver="window.status=' Logo Out ';return true" onMouseOut="window.status='';return true"><b><font size="1">[<?php echo "$msg_admin_logout"; ?>]</font></b></a></td>
  </tr>
  </tbody>
</table>
</body>
</html>
<?php
	exit;
}

function dohtml_admin_menulink($file,$action,$s,$alt,$permission="0") {
	global $superuser;
	
	if($permission ==1 || $superuser==1)
	{
		echo "|<a href=\"$file.php?action=$action&s=$s\" target=\"main\" onMouseOver=\"window.status=' $alt ';return true\" onMouseOut=\"window.status='';return true\">$alt</a>| ";
	}
}
function dothml_local_titlelabel($title,$anchor="",$specialchars="1",$access="0") {
	global $site_font_face,$superuser;
	
	if($access != 0 || $superuser == 1)
	{
		echo "<tr><td colspan=\"2\" bgcolor=\"#595959\"><a name=\"$anchor\"><font face=\"$site_font_face\"size=\"2\" color=\"#FFCE63\"><b>".cexpr($specialchars,htmlspecialchars($title),$title)."</b></td></tr>\n";
	}
}

if($action=='m')
{
	dothml_pageheader();
?>
<div align="center"><a href="../" target="_blank" onMouseOver="window.status=' Powered by vCard ';return true" onMouseOut="window.status='';return true"><img src="../img/icon_powered.gif" width="102" height="47" alt="" border="0"></a></div>
<p>
<table width="100%" cellspacing="1" cellpadding="2">
<?php
dothml_local_titlelabel($msg_admin_controlpanel,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("main","",$s,$msg_admin_controlpanel,$vcuser['canviewcardcontrol']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_users,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("users","add",$s,$msg_admin_menu_add,$vcuser['superuser']) ?>
	<?php dohtml_admin_menulink("users","edit",$s,$msg_admin_menu_edit,$vcuser['superuser']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_options,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("options","",$s,$msg_admin_menu_options,$vcuser['canviewoptions']) ?>
	<?php if($debug) { echo "<br>"; dohtml_admin_menulink("setting","",$s,"edit setting",$vcuser['superuser']); } ?>
	<?php if($debug) { echo "<br>"; dohtml_admin_menulink("setting","group_add",$s,"add new group",$vcuser['superuser']); } ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_category,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("category","cat_add",$s,$msg_admin_menu_add,$vcuser['superuser']) ?>
	<?php dohtml_admin_menulink("category","",$s,$msg_admin_menu_edit,$vcuser['superuser']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_postcard,"","","$canaddcard$canaddcard$caneditcard");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("cards","add",$s,$msg_admin_menu_add,$vcuser['canaddcard']) ?>
	<?php dohtml_admin_menulink("cards","upload",$s,$msg_admin_menu_upload,$vcuser['canaddcard']) ?>
	<?php dohtml_admin_menulink("cards","edit",$s,$msg_admin_menu_edit,$vcuser['caneditcard']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_cardsgroup,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("cardsgroup","add",$s,$msg_admin_menu_add,$vcuser['superuser']) ?>
	<?php dohtml_admin_menulink("cardsgroup","",$s,$msg_admin_menu_edit,$vcuser['superuser']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_event,"","","$canaddevent$caneditevent");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("event","add",$s,$msg_admin_menu_add,$vcuser['canaddevent']) ?>
	<?php dohtml_admin_menulink("event","",$s,$msg_admin_menu_edit,$vcuser['caneditevent']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_poem,"","","$canaddpoem$caneditpoem");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("poem","add",$s,$msg_admin_menu_add,$vcuser['canaddpoem']) ?>
	<?php dohtml_admin_menulink("poem","",$s,$msg_admin_menu_edit,$vcuser['caneditpoem']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_music,"","","$canaddmusic$canaddmusic$caneditmusic");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("music","add",$s,$msg_admin_menu_add,$vcuser['canaddmusic']) ?>
	<?php dohtml_admin_menulink("music","upload",$s,$msg_admin_menu_upload,$vcuser['canaddmusic']) ?>
	<?php dohtml_admin_menulink("music","",$s,$msg_admin_menu_edit,$vcuser['caneditmusic']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_stamp,"","","$canaddstamp$canaddstamp$candeletestamp");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("stamp","add",$s,$msg_admin_menu_add,$vcuser['canaddstamp']) ?>
	<?php dohtml_admin_menulink("stamp","upload",$s,$msg_admin_menu_upload,$vcuser['canaddstamp']) ?>
	<?php dohtml_admin_menulink("stamp","",$s,$msg_admin_menu_edit,$vcuser['caneditstamp']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_pattern,"","","$canaddpattern$canaddpattern$candeletepattern");
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("pattern","add",$s,$msg_admin_menu_add,$vcuser['canaddpattern']) ?>
	<?php dohtml_admin_menulink("pattern","upload",$s,$msg_admin_menu_upload,$vcuser['canaddpattern']) ?>
	<?php dohtml_admin_menulink("pattern","",$s,$msg_admin_menu_edit,$vcuser['caneditpattern']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_replace,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("replace","add",$s,$msg_admin_menu_add,$vcuser['canaddreplace']) ?>
	<?php dohtml_admin_menulink("replace","",$s,$msg_admin_menu_edit,$vcuser['caneditreplace']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_template,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("template","",$s,$msg_admin_menu_edit,$vcuser['canedittemplate']) ?>
	<?php if($debug) { echo "<br>"; dohtml_admin_menulink("template","add",$s,"add new template",$vcuser['superuser']); } ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_search,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("search","",$s,$msg_admin_menu_search,$vcuser['canviewsearch']) ?>
	<?php dohtml_admin_menulink("search","log",$s,$msg_admin_search_log); ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_style,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("style","",$s,"$msg_admin_menu_download/$msg_admin_menu_import",$vcuser['canviewstyle']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_emaillog,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("emaillog","",$s,$msg_admin_menu_view,$vcuser['canviewemailog']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_maintenance,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("backup","",$s,$msg_admin_dbbackup,$vcuser['superuser']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_stats,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("stats","",$s,$msg_admin_menu_stats,$vcuser['canviewstats']) ?>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_help,"","",1);
?>
<tr>
	<td>
	<a href="<?php echo "http://www.belchiorfoundry.com/vcard/docs/?action=&s=$s"; ?>" target="main" onMouseOver="window.status=' the user manual ';return true" onMouseOut="window.status='';return true"><?php echo "$msg_admin_menu_manual"; ?></a>
	</td>
</tr>
<?php
dothml_local_titlelabel($msg_admin_menu_phpinfo,"","",$vcuser['superuser']);
?>
<tr>
	<td>
	<?php dohtml_admin_menulink("phpinfo","",$s,$msg_admin_menu_phpinfo,$vcuser['canviewphpinfo']) ?>
	</td>
</tr>
</table>
<br>
<br>
</body>
</html>
<?php
	exit;
}
?>
