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

$get_member_name = "select * FROM members WHERE id = \"$this_id\"";
$result = mysql_query($get_member_name);
while($row = mysql_fetch_object($result)) {
	$username = "$row->user_name";
}

$clean_up_sql = "DELETE FROM `members` WHERE id = \"$this_id\"";
mysql_query($clean_up_sql);

$clean_memberships = "DELETE FROM memberships WHERE user_name = \"$username\"";
mysql_query($clean_memberships);

header("Location: admin_search_members.php");
exit;

?>	