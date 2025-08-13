<?php

////////////////////////////////////////////////////////////////////////
//                                                                    //
//                - phpManager, Copyright 2002 Taysoft -              //
//                                                                    //
// All scripts contained within are protected by international        //
// copyright law. Any unauthorised copying or distribution of         //
// this software will be dealt with accordingly.                      //
//                                                                    //
// Subsequently, the copyright message must not be                    //
// removed from the software. A fee of US$200 is payable to           //
// remove the copyright message. Additionally, phpManager is          //
// distributed as a single domain license (Single IP). You will       //
// be required to purchase another license if you wish to install     //
// on another domain.                                                 //
// Please note: Removal of the copyright message does NOT give        //
// you distribution rights to the software. All you are paying        //
// for is to remove the copyright message to give a more professional //
// look for your clients.                                             //
//                                                                    //
////////////////////////////////////////////////////////////////////////

include ("../variables.php");

$sql = "SELECT * FROM clients WHERE id='$id' AND username='$clientname' AND password='$clientid'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$language = $myrow["language"];
$template = $myrow["template"];
include ("$rootdirectory/languages/$language");

?>

<html>
<body>

<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">

<?php

if(mysql_num_rows($result)) {

// ------------------------------------------------------ VIEW TRANSACTIONS BY DATE -------------------------------------------------------------

if ($transdate){

$sql = "SELECT * FROM transactions WHERE date='$transdate'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

?>
<table width="500" align="center" border="0" cellspacing="1" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="500" colspan="3" align="center">
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
<tr>
<td width="100" class="main_bold">
<?php echo $client_id ?>
</td>
<td width="300" class="main_bold">
<?php echo $details ?>
</td>
<td width="100" class="main_bold">
<?php echo $date ?>
</td>
</tr>
<?php

$result = mysql_query("SELECT * FROM transactions",$db);
while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF TRANSACTIONS
if ($myrow["date"]==$transdate and $myrow["id"]==$id){
printf("<tr><td width='100' class='main_bold'>%s</td>
<td width='200' class='main_bold'>%s</td>
<td width='100' class='summary_message'>%s</td></tr>",
$myrow["id"], $myrow["details"], $myrow["date"]);
}
} // PRINT LIST OF TRANSACTIONS

?>
</table>
<?php

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $notransactions ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ---------------------------------------------------------- END VIEW TRANSACTIONS BY DATE ----------------------------------------------------

// ---------------------------------------------------------- SHOW ALL TRANSACTIONS ------------------------------------------------------------

} elseif ($showall) {

$sql = "SELECT * FROM transactions WHERE id='$id'";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

?>
<table width="500" align="center" border="0" cellspacing="1" cellpadding="2" style="border: 1 solid #000000">
<tr>
<td width="500" colspan="3" align="center">
<button onclick="history.go(-1);" class="formfield"><?php echo $back ?></button>
</td>
</tr>
<tr>
<td width="100" class="main_bold">
<?php echo $client_id ?>
</td>
<td width="300" class="main_bold">
<?php echo $details ?>
</td>
<td width="100" class="main_bold">
<?php echo $date ?>
</td>
</tr>
<?php

$result = mysql_query("SELECT * FROM transactions ORDER BY date DESC",$db);
while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF TRANSACTIONS
if ($myrow["id"]==$id){
printf("<tr><td width='100' class='main_bold'>%s</td>
<td width='200' class='main_bold'>%s</td>
<td width='100' class='summary_message'>%s</td></tr>",
$myrow["id"], $myrow["details"], $myrow["date"]);
}
} // PRINT LIST OF TRANSACTIONS

?>
</table>
<?php

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $notransactionsmsg ?>

<script>window.setTimeout('changeurl();',2000); function changeurl(){history.go(-1);}</script>

</td>
</tr>
</table>

<?php

}

// ------------------------------------------------------------ END SHOW ALL TRANSACTIONS -------------------------------------------------------

// ------------------------------------------------------------- PAGE FORMATTING STARTS HERE ----------------------------------------------------

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center">
<form method="post" action="<?php echo $PHP_SELF?>">
<table width="300" border="0" cellspacing="0" cellpadding="2" class="main_bold" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="center">
		<?php echo $searchtransactiondate ?> <input type="text" name="transdate" class="formfield">
		<br>
		<br>
		</td>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="<?php echo $search ?>" class="formfield">
		&nbsp;
		<button onclick="window.location='transactions.php?showall=yes'"
		class="formfield"><?php echo $showallmsg ?></button>
		</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>

<?php

}

} else {

echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='index.php';}</script>";

}

?>

</body>
</html>