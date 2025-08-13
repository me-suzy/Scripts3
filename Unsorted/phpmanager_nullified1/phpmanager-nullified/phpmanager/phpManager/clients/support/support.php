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

include ("../../variables.php");

$sql = "SELECT * FROM clients WHERE id='$id' AND username='$clientname' AND password='$clientid'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$language = $myrow["language"];
$template = $myrow["template"];
include ("$rootdirectory/languages/$language");

?>

<html>
<body>

<LINK rel="stylesheet" type="text/css" href="../../templates/<?php echo $template ?>/styles.css">

<?php

if(mysql_num_rows($result)) {

if ($addnew){

include ("$rootdirectory/support/clients/addnewsupport.inc");

} elseif ($view){

include ("$rootdirectory/support/clients/viewsupport.inc");

} elseif ($new) {

include ("../../templates/$template/support/addsupport.inc");

} elseif ($append){

include ("$rootdirectory/support/clients/appendsupport.inc");

} else {

// --------------------------------------------------------- PAGE FORMATTING STARTS HERE ------------------------------------------------------------

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center">
<form method="post" action="<?php echo $PHP_SELF?>?view=yes">
<table width="300" border="0" cellspacing="0" cellpadding="2" class="main" style="border: 1 solid #000000">
	<tr>
		<td width="300" align="center">
		<b><?php echo $ticketnumber ?> </b><input type="text" name="ticketno" class="formfield">
		<br>
		<br>
		</td>
	<tr>
		<td width="300" align="center">
		<input type="Submit" name="submit" value="<?php echo $viewticket ?>" class="formfield">
		&nbsp;
		<button onclick="window.location='<?php echo $PHP_SELF ?>?new=yes'"
		class="formfield"><?php echo $newticket ?></button>
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