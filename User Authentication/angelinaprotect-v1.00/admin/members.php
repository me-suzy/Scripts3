<?

require("protect.php");
include("header.php");

require("../library/config.php");
require("../library/opendb.php");

if ($page == del)  {

$mysql_link = mysql_connect($dbhost, $dbuname, $dbpass);
$sql = "DELETE FROM member_user WHERE id = '$del'";
mysql_db_query($dbname, $sql, $mysql_link);
echo "<font color=$fontcolor size=2 face=verdana><b><center><i><p>MEMBER DELETED</i></center></b></font>";
}
else {
dbconnect();
	$data = query_db("SELECT * FROM member_user"); 
	if (!$data) { echo( mysql_error()); }
    else {
ECHO"<P><center><table bgcolor=RED cellspacing=2 cellpading=2><tr><th bgcolor=white>ID</th><th bgcolor=white>USER</th><th bgcolor=white>EMAIL</th><th bgcolor=white>FULL NAME</th><th bgcolor=white>DATE ADDED</th><th bgcolor=white>COMMENT</th><th bgcolor=white></th><th bgcolor=white></th></tr>";	

		while ($row = mysql_fetch_array($data)) {
			$id = $row["id"];
			$user_id = $row["user_id"];
			$email = $row["email"];
			$fullname = $row["fullname"];
			$date = $row["date"];
			$comment = $row["comment"];

echo "<tr><th bgcolor=white>$id</th><th bgcolor=white>$user_id</th><th bgcolor=white>$email</th><th bgcolor=white>$fullname</th><th bgcolor=white>$date</th><th bgcolor=white>$comment</th>
<th bgcolor=white>EDIT</th><th bgcolor=yellow><a href=members.php?page=del&del=$id>DELETE</a></th></tr>";
		}
echo"</table></center>";
}}
include("footer.php");
?>
</body> 
</html> 
