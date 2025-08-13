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
require "include.php";

if ($include_template == "1") {
	include "$template_directory/header.php";	
}
	
$get_user_info_sql = "SELECT * FROM members WHERE user_name = \"$valid_user\"";
$result = mysql_query($get_user_info_sql);
if($row = mysql_fetch_object($result)) {
	$test_account_for_renewal = "SELECT * FROM account_types WHERE account_name = \"$row->account_type_name\"";
	$result2 = mysql_query($test_account_for_renewal);
	while($row2 = mysql_fetch_object($result2)) {
		if ($row2->renewal == "0") {
			echo "<FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b><center>We are sorry, your account can not be renewed.</center></b></font>";	
			exit;			
		}
	}
?>	
<br>
<br>
<center>
echo "<FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b><center>Click the button to renew your account for another period.</center></b></font>";	
<br><br>
<FORM ACTION="process_renewal.php" METHOD="POST">
	<INPUT TYPE="SUBMIT" NAME="process" VALUE="Submit My Order">
</FORM>
</center>	
<?php
}else{
	global $company_name, $company_email;
	echo "<FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b><center>There was an error please contact $company_name at <a href=mailto:$company_email><font color=\"red\">$company_email</font></a> so we may be able to correct this.</center></b></font>";
}
?>	
<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}

?>	