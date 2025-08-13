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
	
	if (!$op) {
?>	

<FORM ACTION="lostpassword.php" METHOD="POST">
	<CENTER>
		<table>
			<tr>
				<td>
					<FONT SIZE="-1" FACE="verdana, arial, helvetica">
						<B>
							Please Enter Your E-Mail Address Below.
						</B>
					</FONT>
				</td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="op" value="1">
					<input type="text" name="e_mail_address" size="30">&nbsp;&nbsp;<input type="submit" name="submit" value="Process">
				</td>
			</tr>
		</table>    	
	</CENTER>
</FORM>

<?php
	}else{
		$get_login_info_sql = "SELECT * FROM members WHERE e_mail_address = \"$e_mail_address\"";
		
		$result = mysql_query($get_login_info_sql);
		if ($row = mysql_fetch_object($result)) {
			$file = "$data_directory/approved.txt";
			$handle = fopen ($file, "r");
			$original_body = fread ($handle, 10000);
			fclose($handle);

			$thebody = $original_body;
			
			$thebody = eregi_replace("<<first_name>>", "$row->first_name", $thebody);
			$thebody = eregi_replace("<<last_name>>", "$row->last_name", $thebody);
			$thebody = eregi_replace("<<site_name>>", "$site_name", $thebody);
			$thebody = eregi_replace("<<user_name>>", "$row->user_name", $thebody);
			$thebody = eregi_replace("<<password>>", "$row->user_password", $thebody);
			$thebody = eregi_replace("<<company_email>>", "$company_email", $thebody);
			$thebody = eregi_replace("<<company_name>>", "$company_name", $thebody);
    
			$mailTo = "$row->e_mail_address";
    		$mailSubject = "$company_name Login Information";
	    	$mailBody = "$thebody";
    		$mailHeaders = "From: $company_email";
    
			mail($mailTo, $mailSubject, $mailBody, $mailHeaders);
			
			echo "<FONT SIZE=\"-1\" FACE=\"verdana, arial, helvetica\"><b><center>The login information has been sent to $e_mail_address.</center></b></font>";
		}else{
			echo "<FONT SIZE=\"-1\" FACE=\"verdana, arial, helvetica\"><b><center>There are no members with $e_mail_address as the e-mail address.  Please try again</center></b></font>";
		}
	}
?>	
<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	