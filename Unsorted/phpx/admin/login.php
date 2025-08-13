<?php
#$Id: login.php,v 1.5 2003/09/19 20:25:34 ryan Exp $
require("includes/var.inc.php");
$code = $_GET[code];
?>
<html>
<head>
<script language="JavaScript" src="templates/javascript.js"></script>
<link rel="stylesheet" type="text/css" href="templates/style.css"></link>
<title><?php print "$prog_title Login"; ?></title>
<script language=javascript>
function bodyLoad(){
    document.f.username.focus();
}
</script>

</head>
<body onLoad=bodyLoad()><form method=post action=index.php name=f>
<table class=main border=0 cellspacing=2 cellpadding=2 width=250 align=center>
<tr><td class=title align=center><?php print "$prog_title Login"; ?></td></tr>
<tr><td class=attn align=center><?php print "$loginCodes[$code]"; ?></td></tr>
<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>
<tr><td class=mainbold align=right width=100>UserName: </td><td class=main><input type=text size=25 name=username></td></tr>
<tr><td class=mainbold align=right width=100>Password: </td><td class=main><input type=password size=25 name=password></td></tr>
<tr><td class=main align=center colspan=2><input type=hidden name=login value=yes><input type=submit value=Login></td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>


