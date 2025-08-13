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
$insert_info = "UPDATE members SET 
				first_name = \"$first_name\", 
				last_name = \"$last_name\", 
				address1 = \"$address1\",	
				address2 = \"$address2\", 
				city = \"$city\", 
				state = \"$state\",	
				postal_code = \"$postal_code\",	
				country = \"$country\",	
				telephone_number = \"$telephone_number\", 
				e_mail_address = \"$e_mail_address\", 
				user_password = \"$user_password\"	
				WHERE id = \"$id\"";
mysql_query($insert_info);

echo "<br><br><font color=\"#000000\" face=\"arial\"><center><b>Member Data for $user_name has been updated.</b></center></center>";

// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}

?>