<html>
<head>
<script language="JavaScript" src="templates/javascript.js"></script>
<link rel="stylesheet" type="text/css" href="templates/style.css"></link>
<?php print "<title>$prog_title - $page_title</title>"; ?>
</head>
<body onLoad="MM_preloadImages('images/close_over.gif', 'images/back_over.gif')">
<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>
<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>
<tr>
<td class=title width=200 valign=center align=left><?php print "$page_title"; ?></td>
<td class=title width=* valign=center align=right>
<a href="javascript:history.go(-1);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/back_over.gif',1)"><img src="images/back.gif" alt="Back" name="Image1" width="19" height="21" border="0"></a>

<a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','images/close_over.gif',1)"><img src="images/close.gif" alt="Close Window" name="Image2" width="19" height="21" border="0"></a>
</td>
</tr>
</table>
</td></tr>
<tr>
<td class=bg width=500 valign=top>
<table border=0 cellpadding=2 cellspacing=0 width=100% align=left>
<?php print "$body"; ?>
</table>
</td>
</tr>
</table>
</body>
</html>
