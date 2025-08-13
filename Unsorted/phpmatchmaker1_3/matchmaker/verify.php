<? 
require_once("php_inc.php");
include("header_inc.php");
do_html_heading("Verify user");

$verify = strip_tags($verify);
$sql = "UPDATE user SET verify = 0 WHERE verify = $verify";
$result = mysql_query($sql);

$res = mysql_affected_rows();


print "<h3>Validation</h3>";

if ($res >0)
{
	print "<b>Done!</b><br />You are now a valid user.<br />";
	print "You may now <a href='login.php'>login</a>. <p />";
}
else 
{
	print "<b>Error</b><br />";	
	print "Something went wrong...";
}


include("footer_inc.php");
?>