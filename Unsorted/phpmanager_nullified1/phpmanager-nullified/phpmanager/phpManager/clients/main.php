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

setcookie ("id", $id);

include ("../variables.php");

if ($submit){

$sql = "UPDATE clients SET language='$new_language', template='$new_template' WHERE username='$clientname'";
$result = mysql_query($sql);

}

$sql = "SELECT * FROM clients WHERE id='$id' AND username='$clientname' AND password='$clientid'";
$result = mysql_query($sql);
$myrow = mysql_fetch_array($result);
$language = $myrow["language"];
$template = $myrow["template"];
$domainname = $myrow["domainname"];
$domain = $myrow["domain"];
include ("$rootdirectory/languages/$language");

?>

<html>

<head>
<title><?php echo $accountmanager_title ?></title>
<LINK rel="stylesheet" type="text/css" href="../templates/<?php echo $template ?>/styles.css">
</head>

<body>

<?php

if(mysql_num_rows($result)) {

include ("../templates/$template/clients/accountmanager_header.inc");

include ("../templates/$template/clients/accountmanager_menu.inc");

include ("../templates/$template/clients/accountmanager_main.inc");

} else {

echo "<script>window.setTimeout('changeurl();',100); function changeurl(){window.location='index.php';}</script>";

}

?>

</body>
</html>


