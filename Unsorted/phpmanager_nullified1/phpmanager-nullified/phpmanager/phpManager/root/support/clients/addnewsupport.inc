<?php

if ($clientname && $email && $subject && $description){

$status = "New";
$date =  strftime("$dateformat - %H:%M",time());

$sql = "INSERT INTO support (username,email,url,subject,description,status,date) VALUES ('$clientname','$email','$url','$subject','$description',
'$status','$date')";
$result = mysql_query($sql);

$sql = "SELECT * FROM support WHERE date='$date' && username='$clientname'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);

$ticketno = $myrow["ticketno"];
$url = $myrow["url"];

include ("../../emails/support.inc");

include ("../../emails/supportnotice.inc");

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $support_submit ?> <font color="#FF0000"><?php echo $ticketno ?></font>
<br>
<br>
<?php echo $support_email ?>

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

<?php echo $support_fields ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

?>