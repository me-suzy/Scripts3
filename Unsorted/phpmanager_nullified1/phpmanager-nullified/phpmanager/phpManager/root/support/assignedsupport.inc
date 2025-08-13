<table width="560" border="0" cellspacing="0" cellpadding="2">
<tr>
<td width="280" valign="top">
<table width="275" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="275" align="center" height="20" class="summary_message">
- <?php echo $support_new ?> -
</td>
</tr>
<tr>
<td width="275" align="center" height="20" class="main">

<?php

$sql = "SELECT * FROM support WHERE status='New'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$result = mysql_query("SELECT * FROM support ORDER BY ticketno ASC",$db);

while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF NEW SUPPORT REQUESTS

if ($myrow["status"]=="New"){

printf("<span class='summary_message'>Ticket: %s - </span><a href='support.php?ticketno=%s' class='link'>%s</a><br>",
$myrow["ticketno"], $myrow["ticketno"], $myrow["username"]);

}
} // END PRINT LIST OF NEW SUPPORT REQUESTS

} else {

echo "<span class='summary_message'>$support_nonew</span>";

}

?>

</td>
</tr>
</table>
</td>
<td width="280" valign="top">
<table width="275" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
<tr>
<td width="275" align="center" height="20" class="summary_message">
- <?php echo $support_assigned ?> -
</td>
</tr>
<tr>
<td width="275" align="center" height="20" class="main">

<?php

$sql = "SELECT * FROM support WHERE status='Assigned' && admin='$logonname'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$result = mysql_query("SELECT * FROM support ORDER BY ticketno ASC",$db);

while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF ASSIGNED SUPPORT REQUESTS

if ($myrow["status"]=="Assigned"){

printf("<span class='summary_message'>Ticket: %s - </span><a href='support.php?ticketno=%s' class='link'>%s</a><br>",
$myrow["ticketno"], $myrow["ticketno"], $myrow["username"]);

}
} // END PRINT LIST OF ASSIGNED SUPPORT REQUESTS

} else {

echo "<span class='summary_message'>$support_noassigned</span>";

}

?>

</td>
</tr>
</table>
</td>
</tr>
</table>