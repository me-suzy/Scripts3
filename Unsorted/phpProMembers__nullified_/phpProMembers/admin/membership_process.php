<?php
/*********************************************************************/
/* Program Name         : phpProMembers                              */
/* Home Page            : http://www.gacybertech.com                 */
/* Retail Price         : $149.99 United States Dollars              */
/* WebForum Price       : $000.00 Always 100% Free                   */
/* xCGI Price           : $000.00 Always 100% Free                   */
/* Supplied by          : South [WTN]                                */
/* Nullified by         : CyKuH [WTN]                                */
/* Distribution         : via WebForum and Forums File Dumps         */
/*********************************************************************/
$page_account = "admin";
require "include.php";

$this_id = $GLOBALS["id"];
$this_process = $GLOBALS["process"];
$this_member = $GLOBALS["member"];

if ($this_process == "delete") {
	$update_sql = "DELETE FROM memberships WHERE id = \"$this_id\"";
	mysql_query($update_sql);		
}

if ($this_process == "activate") {
	$update_sql = "UPDATE memberships SET active = \"1\" WHERE id = \"$this_id\"";
	mysql_query($update_sql);		
}else{
	$update_sql = "UPDATE memberships SET active = \"0\" WHERE id = \"$this_id\"";
	mysql_query($update_sql);	

}


header("Location: view_memberships.php?id=$this_member");
exit;
?>