<?php 
/*
***************************************************************************
Parameters :

$dbfield
$dbtable
$Id
***************************************************************************
*/
?>
<?php require("inc.auth.php"); ?>
<?
if ($Id != "" && in_array("$userlev", $admins)){
	$sql = "SELECT $dbfield FROM $dbtable WHERE Id = $Id";
	$result = mysql_query($sql);
	$num_res = mysql_num_rows($result);
	for ($i=0; $i<$num_res; $i++){
              $myrow = mysql_fetch_array($result);
              ${$dbfield} = $myrow["$dbfield"];
	}
echo ${$dbfield};
}
?>