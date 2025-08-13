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
$page_account = "form";
require "include.php";
if ($include_template == "1") {
	include "$template_directory/header.php";	
}
?>	

<center>
<FONT color="#000000" size="2" face="Arial, Helvetica, sans-serif">
<center><h3>Thank was an error processing your payment.  Please try again.</h3></center>
</FONT>
</center>

<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	