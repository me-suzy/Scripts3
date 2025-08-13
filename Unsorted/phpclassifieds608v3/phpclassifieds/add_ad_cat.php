<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");

if (!$special_mode)
{ include("navigation.php"); }


include_once("member_header.php");
check_valid_user();


?>
<table border="0" cellspacing="1" width="100%">
<tr>
<td width="100%" valign="top">
<!-- START include catcol.php -->

<p /><?
print "<b>$la_choose_cat</b><p />";
$frontpage=2;
include("link_title.php");
include("catcol.php");
?>
<!-- END include catcol.php -->
</td>
</tr>
</table>


<?
include_once("member_footer.php");
include_once("admin/config/footer.inc.php");
?>