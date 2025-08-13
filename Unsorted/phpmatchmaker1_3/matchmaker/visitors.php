<?
require_once("php_inc.php"); 
session_start();
include("header_inc.php");

db_connect();

if (session_is_registered("valid_user"))
{
 do_html_heading("Member Visits");
 member_menu();
			

 print "<p><table width='100%'>";
 print "<tr><td bgcolor='#D8D4D8'>Visitor</td><td bgcolor='#D8D4D8'>Visit</td>";
 $sql = "select * from visitors where owner = '$valid_user'";
 $result = mysql_query ($sql);
 while ($row=mysql_fetch_array($result)) 
 {
 	 $visitor = $row[visitor];
 	 $visitdate = $row[visitdate];
	  $year = substr($visitdate,0,4);
 		$month = substr($visitdate,4,2);
		$day = substr($visitdate,6,2);
		$formatted_date = "$year-$month-$day"; 

	 print "<tr>";
 	 print "<td><a href='detail.php?profile=$visitor'>$visitor</a></td><td>$formatted_date</td>"; 
 }
 print "</table>";

 
 
// ----- END OF CONTENT ----------- // 
} else {
	 		print "Session expired, please logon again.";
			exit;
}
print "<p>";
include("footer_inc.php");
?>