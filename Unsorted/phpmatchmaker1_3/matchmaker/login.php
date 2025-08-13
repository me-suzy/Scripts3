<?
require_once("php_inc.php");
include("header_inc.php");
do_html_heading("Log in form");

display_login_form();
print "<p>";
print "<a href=\"lostpassword.php\">Lost password ?</a>";
include("footer_inc.php");
?>