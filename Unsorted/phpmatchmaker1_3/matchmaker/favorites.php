<?
require_once("php_inc.php"); 
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 do_html_heading("Favorites List");
 member_menu();
			
 if ($profile)
 {
 		$result = mysql_query ("insert into favorites (owner, fav_user) values ('$valid_user', '$profile')");
 }
 if ($result)
 {
 	print "<p>User $profile is now added to you favorite list.";
 }
 
 if ($delete)
 {
 		$result = mysql_query ("delete from favorites where favid = $favid");
 }
 if ($delete AND $result)
 {
 	print "<p>User $profile is delete from you favorite list.";
 }
 
 
 $sql = "select * from favorites where owner = '$valid_user'";
 
 print "<p><table width='100%'>";
 print "<tr><td bgcolor='#D8D4D8'>Favorite user</td><td bgcolor='#D8D4D8'>Added</td><td bgcolor='#D8D4D8'>Delete</td>";

 $result = mysql_query ("$sql");
 while ($row=mysql_fetch_array($result)) 
 {
 	 $fav_user = $row[fav_user];
 	 $favdate= $row[favdate];
	 $favid = $row[favid];
   
	 $favdate = $row[favdate];
 	 $year = substr($favdate,0,4);
 	 $month = substr($favdate,4,2);
 	 $day = substr($favdate,6,2);
 	 $formatted_fav_date = "$year-$month-$day"; 
	
	
	 print "<tr>";
 	 print "<td><a href='detail.php?profile=$fav_user'>$fav_user</a></td><td>$formatted_fav_date</td><td><a href='favorites.php?favid=$favid&delete=1'>delete</a></td>";
 	 print "</tr>";
 }
 
 if (!$fav_user)
 {
 		print "<tr><td colspan='3'>No users in your list.</td></tr>";
 }
 
 print "</table>";

// ----- END OF CONTENT ----------- // 
}
else
{
	 		print "Session expired, please logon again.";
			exit;
}
print "<p>";
include("footer_inc.php");
?>