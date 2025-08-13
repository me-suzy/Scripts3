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

if ($submit) {

// ------------------------------------------------------ UPDATE CONTACT DETAILS ---------------------------------------------------------------

$sql = "UPDATE clients SET telephone='$telephone',email='$email',icq='$icq' WHERE id=$id";

$result = mysql_query($sql);

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php echo $client_updatedmsg ?>

</td>
</tr>
</table>

<?php

echo "<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='details.php'}</script>";

// ------------------------------------------------------------ END UPDATE CONTACT DETAILS ---------------------------------------------------------

} else {

?>

<table width="570" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="570" height="360" align="center" class="main">

<?php $client_error ?>

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