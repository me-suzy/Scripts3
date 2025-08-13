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

if ($op != "1") {
	if ($include_template == "1") {
		include "$template_directory/header.php";	
	}
?>	

<FORM ACTION="login.php" METHOD="POST">
<CENTER>

<table bgcolor="#cccccc" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan=2 align="right" bgcolor="#000000">
			<FONT SIZE="-1" FACE="verdana, arial, helvetica" color="#ffffff">
				<b>MEMBERSHIP LOGIN</b>
			</FONT>
		</td>
	</tr>
	<tr>
		<td>
			<FONT SIZE="-1" FACE="verdana, arial, helvetica">
				USERNAME:
			</FONT>
		</td>
		<td>
			<FONT SIZE="-1" FACE="verdana, arial, helvetica">
				<input type="text" name="username">
			</FONT>
		</td>
	</tr>
	<tr>
		<td>
			<FONT SIZE="-1" FACE="verdana, arial, helvetica">
				PASSWORD:
			</FONT>
		</td>
		<td>
			<FONT SIZE="-1" FACE="verdana, arial, helvetica">
				<input type="hidden" name="op" value="1">
				<input type="password" name="password">
			</FONT>
		</td>
	</tr>
	<tr>
		<td colspan=2 align="right">
			<input type="submit" name="submit" value=" LOGIN ">
		</td>
	</tr>
</table>    	

</CENTER>
</FORM>

<?php
// Adding footer if we got it.
	if ($include_template == "1") {
		include "$template_directory/footer.php";	
	}

}else{
	$get_user_info = "SELECT * FROM members WHERE user_name = \"$username\" AND user_password = \"$password\"";
	$result = mysql_query($get_user_info);
	if($row = mysql_fetch_object($result)) {
		session_register("valid_user");
		$valid_user = $username;
		header("Location: $member_index");
	}else{
		if ($include_template == "1") {
			include "$template_directory/header.php";	
		}
		echo "<center><br><br><FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b>The was an error with the user name and password.</b></font></center>";	
		echo "<center><br><br><FONT SIZE=\"2\" FACE=\"verdana, arial, helvetica\"><b>Please Try Again.</b></font></center>";	
		if ($include_template == "1") {
			include "$template_directory/footer.php";	
		}
	}
}	
?>	