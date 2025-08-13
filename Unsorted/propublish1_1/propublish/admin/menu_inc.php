<?
print "<LINK REL=STYLESHEET HREF=style.css TYPE=text/css>";
print "<span class='meny'>";
print "<b>";
print "<a href='list.php'>$la_art_list</a> | ";
if ($set_normaleditor) { print "<a href='editor.php'>$la_new_art</a> |  "; }
if ($set_htmleditor) {   print "<a href='editor_html.php'>$la_new_art_html</a> | "; }
print "<a href='user.php'>$la_change_user</a> | ";
if ($level == 1)
{
	print "<a href='category.php'>$la_cat</a> |  <a href='level.php'>$la_level</a> |  <a href='setup.php'>$la_setup</a>";
}
print " | <a href=\"javascript:Start1('help.php')\";>$la_help</a>";
print " | <a href='../login.php?logout=1'>$la_logout</a>";
print "</b></span>";
?>