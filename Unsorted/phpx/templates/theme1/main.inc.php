<html>
<meta name=keywords content='<?php print "$keywords"; ?>'>
<meta name=description content='<?php print "$description"; ?>'>
<head>
<title><?php print "$title";?></title>
<?php print "<link rel=\"stylesheet\" type=\"text/css\" href=\"$template/style.css\">"; ?>
<script language="JavaScript" src="includes/javascript.js"></script>
</head>
<body marginheight=0 topmargin=0 leftmargin=0 marginwidth=0>

<table border=0 cellpadding=0 cellspacing=0 width=100% height=69>
<tr><td background=<?php print "$template/"; ?>images/filler.jpg>
<table border=0 cellpadding=0 cellspacing=0 width=750 align=center>
<tr><td class=maintitle><?php print "$siteName"; ?></td></tr>
</table>
</td></tr></table>

<br>
<table border=0 cellpadding=0 cellspacing=0 width=750 align=center>
<tr><td background=<?php print "$template/"; ?>images/header.gif height=20 class=menu>
<?php print "$menu"; ?>
</td></tr>
<tr><td align=center class=main>
<table border=0 cellpadding=0 cellspacing=0 width=750 align=center>
<tr>
<td width=* class=mainbody background=<?php print "$template/"; ?>images/bodyfiller.gif>
<br>
<!--Start Body-->
<?php print "$body"; ?>
<!--End Body-->
</td>
</tr>
</table>
</td></tr>
<tr><td align=center background=<?php print "$template/"; ?>images/bodyfiller.gif><img src=<?php print "$template/"; ?>images/footer.gif width=750 height=30></td></tr>
</table>
<br>
<table border=0 cellpadding=0 cellspacing=0 width=750 align=center>
<tr><td class=small align=center><?php print "$footer"; ?></td></tr>
</table>
















</body>
</html>


