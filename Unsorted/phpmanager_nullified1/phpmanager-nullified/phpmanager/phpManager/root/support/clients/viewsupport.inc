<?php

$sql = "SELECT * FROM clients WHERE id='$id' AND username='$clientname' AND password='$clientid'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$sql = "SELECT * FROM support WHERE ticketno='$ticketno' && username='$clientname'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$myrow = mysql_fetch_array($result);
$date = $myrow["date"];
$status = $myrow["status"];
$username = $myrow["username"];
$admin = $myrow["admin"];
$subject = $myrow["subject"];
$description = $myrow["description"];
$email = $myrow["email"];
$url = $myrow["url"];

?>
<table width="570" align="center" border="0" cellspacing="0" cellpadding="2">
<tr>
<td width="170" class="main_bold">
<?php echo $ticketnumber ?> <span class="summary_message"><?php echo $ticketno ?></span>
</td>
<td width="200" class="main_bold">
<?php echo $usernamemsg ?> <span class="summary_message"><?php echo $username ?></span>
</td>
<td width="200" class="main_bold">
<?php echo $time ?> <span class="summary_message"><?php echo $date ?></span>
</td>
</tr>
<tr>
<td width="170" class="main_bold">
<?php echo $statusmsg ?> <span class="summary_message"><?php echo $status ?></span>
</td>
<td width="200" class="main_bold">
<?php echo $dealtby ?> <span class="summary_message"><?php echo $admin ?></span>
</td>
<td width="200" class="main_bold">
<?php echo $subjectmsg ?> <span class="summary_message"><?php echo $subject ?></span>
</td>
</tr>
<tr>
<td width="570" colspan="3">
<table width="570" border="0" cellspacing="0" cellpadding="0"
<tr>
<td width="285" class="main_bold">
<?php echo $emailmsg ?> <a href="mailto:<?php echo $email ?>" class="link"><?php echo $email ?></a>
</td>
<td width="285" class="main_bold">
<?php echo $webaddress ?> <a href="<?php echo $url ?>" target="_blank" class="link"><?php echo $url ?></a></span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="570" colspan="3" align="center" class="main_bold">
<br>
- <?php echo $descriptionmsg ?> -
</td>
</tr>
<tr>
<td width="570" colspan="3" class="main_bold">
<textarea cols="94" rows="13" class="formfield" readonly><?php echo $description ?></textarea>
</td>
</tr>
</table>

<?php

if ($status=="New"){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="100" class="main_bold">

<?php echo $support_received ?>
<br>
<br>
<center><button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button></center>

</td>
</tr>
</table>

<?php

} elseif ($status=="Closed"){

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="100" align="center" class="main_bold">

<?php echo $support_resolved ?>
<br>
<br>
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>

</td>
</tr>
</table>

<?php

} else {

?>

<form method="post" action="<?php echo $PHP_SELF?>?append=yes">
<table width="570" border="0" cellspacing="0" cellpadding="0" class="main_bold">
	<tr>
		<td width="570" align="center">
		<input type="hidden" name="ticketno" value="<?php echo $ticketno ?>">
		- <?php echo $support_description ?> -
		<br>
		<textarea cols="95" rows="3" name="newdescription" class="formfield"></textarea>
		<br>
		</td>
	<tr>
		<td width="570" align="center">
		<input type="Submit" name="submit" value="<?php echo $add ?>" class="formfield">
		&nbsp;
		<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
		</td>
	</tr>
</table>
</form>

<?php

}

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main_bold">

<?php echo $support_error ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

} else {

echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='index.php';}</script>";

}

?>