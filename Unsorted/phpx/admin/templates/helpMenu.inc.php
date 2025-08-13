<?php
#$Id: helpMenu.inc.php,v 1.1 2003/07/08 18:30:19 ryan Exp $
?>

<html>
<head>
<title><?php print "$prog_title - $title"; ?> </title>
<link rel="stylesheet" type="text/css" href="templates/style.css"></link>


</head>
<body>
<table border=0 width=100% align=center cellpadding=2 cellspacing=2>
<tr>
<?php
print "<td class=title width=50%><a class=hlinks href=helpmenu.php>Contents</a></td>";
print "<td class=title width=50%><a class=hlinks href=helpmenu.php?action=search>Search</a></td>";




?>
</tr></table>




<table border=0 width=100% align=center cellpadding=2 cellspacing=0>
<tr><td class=main>
<?php print "$body"; ?>
</td></tr></table>
</body>
</html>


