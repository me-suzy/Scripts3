<?
require("protect.php");
include("header.php");

if ($page == add) {
if (!$user) {
echo "<p>PLEASE FILL IN ALL FIELDS<P>FIELD USER IS EMPTY!";
} else if (!$password) {
echo "<p>PLEASE FILL IN ALL FIELDS<P>FIELD PASSWORD IS EMPTY!";
} else if (!$email) {
echo "<p>PLEASE FILL IN ALL FIELDS<P>FIELD EMAIL IS EMPTY!";
} else {
require("../library/config.php");
$mysql_link = mysql_connect($dbhost, $dbuname, $dbpass);
$sql = "INSERT INTO member_user VALUES ('', '$user', PASSWORD('$password'), '$email', '$fullname', NOW(''), '$comment')";
mysql_db_query($dbname, $sql, $mysql_link);
echo "<center><P><b>Member added!</b></center>";
}
} else {
echo "<center><form method=POST action=addmembers.php?page=add>
<table bgcolor=RED cellpadding=2 cellspacing=2>
<tr><th bgcolor=white colspan=2>ADD NEW MEMBER</th></tr>
<tr><th bgcolor=white>USER NAME</th><th bgcolor=white><input type=text name='user' size=35></th></tr>
<tr><th bgcolor=white>PASSWORD</th><th bgcolor=white><input type=text name='password' size=35></th></tr>
<tr><th bgcolor=white>EMAIL</th><th bgcolor=white><input type=text name='email' size=35></th></tr>
<tr><th bgcolor=white>FULL NAME</th><th bgcolor=white><input type=text name='fullname' size=35></th></tr>
<tr><th bgcolor=white>COMMENT (100 caracters)</th><th bgcolor=white><input type=text name='comment' value='' size=35></th></tr>
<tr><th bgcolor=white colspan=2><input type=submit value='Add New Member'> <input type=reset value='Reset'></td></tr>
</table></form></center>";
}
include("footer.php");
?>
