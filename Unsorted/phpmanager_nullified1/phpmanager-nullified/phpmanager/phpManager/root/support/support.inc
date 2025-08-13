<?php

$sql = "SELECT * FROM support WHERE status='New'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$result = mysql_query("SELECT * FROM support ORDER BY ticketno ASC",$db);

while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF NEW SUPPORT REQUESTS

if ($myrow["status"]=="New"){

printf("<tr><td width='150'><a href='support/support.php?ticketno=%s' class='link'>%s</a></td>
<td width='250'><a href='support/support.php?ticketno=%s' class='link'>%s</a></td>",
$myrow["ticketno"], $myrow["ticketno"], $myrow["ticketno"], $myrow["username"]);

printf("<td width='170' class='summary_message'>%s</td></tr>\n", $myrow["date"]);

}
} // END PRINT LIST OF NEW SUPPORT REQUESTS

} else {

echo "<td width='570' colspan='3' class='summary_message'>$support_nonew</td>";

}

?>

