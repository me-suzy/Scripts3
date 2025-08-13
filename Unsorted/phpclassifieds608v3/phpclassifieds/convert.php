<?
include_once("admin/inc.php");


$string = "select * from $usr_tbl order by userid";
$result = mysql_query($string);

while ($myrow = mysql_fetch_array($result))
{

 $pass = $myrow["pass"];
 $userid = $myrow["userid"];
 $registered = $myrow["registered"];

 $sql_update = mysql_query("update $usr_tbl set password_enc = password('$pass'),registered ='$registered' where userid = $userid");
 if ($sql_update)
 {
                 print "$pass $registered<br />";
 }


}


// Update ads

$result_2 = mysql_query ("select * from $ads_tbl, $usr_tbl where $ads_tbl.sites_userid = $usr_tbl.userid");
$num_2 = mysql_num_rows($result_2);

for ($i=0; $i<$num_2; $i++)
{
 $myrow = mysql_fetch_array($result_2);
 $userid = $myrow["userid"];
 $email = $myrow["email"];
 $datestamp = $myrow["datestamp"];
 $sql = "update $ads_tbl set ad_username = '$email', datestamp = '$datestamp' where sites_userid = $userid";

 $result = mysql_query($sql);
}

?>
