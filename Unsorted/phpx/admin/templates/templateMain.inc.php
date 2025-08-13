<?php
#$Id: templateMain.inc.php,v 1.4 2003/10/28 15:23:35 ryan Exp $
?>
<html>
<head>
<script language="JavaScript" src="templates/javascript.js"></script>
<link rel="stylesheet" type="text/css" href="templates/style.css"></link>

<title><?php print "$progTitle Version $progVersion"; ?></title>
</head>
<body>
<table class=main border=0 cellspacing=2 cellpadding=1 width=100%>
<tr>
<td colspan=2>
<table class=outline border=0 cellspacing=1 cellpadding=0 width=100%>
<tr><td class=bg><table border=0 cellpadding=2 cellspacing=0 width=100%>
<td class=bg ><b><?php print "$progTitle - $progVersion"; ?></b></td>
<td class=bg  align=right>
<a class=dlinks href=index.php?action=logout>Logout</a>&nbsp;
</td></tr></table></td></tr>
</table>
</td>
</tr>
<tr>
<td colspan=2>
<table class=outline border=0 cellspacing=1 cellpadding=0 width=100%>
<tr><td class=bg>
<script language="JavaScript" src="templates/menu.js"></script>
<script language="JavaScript" src="templates/items.js"></script>
<script language="JavaScript" src="templates/template.js"></script>
<link rel="stylesheet" href="templates/menuXP.css">
<script language=Javascript>
new menu (MENU_ITEMS_XP, MENU_POS_XP);
</script>
</td></tr>
</table>
</td>
</tr>
<td valign=top width=*%>
<?php print "$body"; ?>
</td>
</tr>
<tr>
<td colspan=2>
<table class=outline border=0 cellpadding=2 cellspacing=1 width=100%>
<tr>
<td class=bg align=center valign=bottom>
<a class=dlinks href=http://www.phpx.org>PHPX</a> &copy; 2003
</td>
</tr>
</table>
</td></tr>
</table>
</body>
</html>
