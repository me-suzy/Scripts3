<?php

if ($newdescription) {

$sql = "SELECT * FROM support WHERE ticketno='$ticketno' && username='$clientname'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$subject = $myrow["subject"];
$email = $myrow["email"];
$olddescription = $myrow["description"];
$date =  strftime("$dateformat - %H:%M",time());

$addnewdescription = "$olddescription
============================================================
$newdescription

$clientname - $date";

$sql = "UPDATE support SET description=\"$addnewdescription\" WHERE ticketno='$ticketno'";
$result = mysql_query($sql);

include ("../../emails/amendsupportnotice.inc");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $support_modified ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='support.php'}</script>

</td>
</tr>
</table>

<?php

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $support_enter ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

?>
