<?
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
// Database information.
// $db_name is the name of the database.
// $db_user_name is the username used to connect to the database.
// $db_password is the password of the username used to connect to the database.
// $db_host is the host name of your server.
// Database information.
$db_name = "gacybert_gacybertech";
$db_user_name = "gacybert_gslaven";
$db_password = "nascar";
$db_host = "localhost";

// Enter the name of your website. 
$site_name = "MyDomain.com";

// Enter the name of your company.
$company_name = "MyDomain";

// Enter you company's e-mail address.
$company_email = "info@mydomain.com";

// Enter the location of your temp directory? (Include full path and directory name)
$temp_directory = "/phpProMembers/temp";

// Where is the data directory? (Include full path and directory name)
$data_directory = "/phpProMembers/data";

// Where is the template directory? (Include full path and directory name)
$template_directory = "/phpProMembers/templates";

// Where is the member temp directory? (Include full path and directory name)
$member_temp = "/phpProMembers/mtemp"; 

// This number blocks the number of different IP's a member can use to enter in to your protected area.
$block_number = "5";

//  What is the full server path to your agreement file.  (include agreement page name)
$agreement_page = "";

//  Do I need to enter header and footer files?
//	Enter 1 for yes, blank or any other number for no.
$include_template = "1";

// Do I need to include an agreement?
// Enter 1 for yes, blank or any other number for no.
$include_agreement = "";

// Do I need to get billing information?
// Enter 1 for yes, blank or any other number for no.
$include_billing = "";

// What is your paypal email address?
$paypal_email_address = "billing@mydomain.com";

// Enter 1 to use paypal subscriptions.
$paypal_subsciptions = "1";

// Where do I send the user when a payment has been recieved?
$thank_you_page = "http://localhost/thankyou.php";

// Where do I send the user that needs to be approved first?
$thank_you_page_awaiting_approval = "http://localhost/thankyouwaiting.php";

// Where do I send the user for a declined payment?
$declined_page = "http://localhost/declined.php";

// Where do I send the user if they cancelled there payment?
$cancel_page = "http://localhost/cancelled.php";

// Where do I send the user if there is an error processing there payment?
$error_page = "http://localhost/error.php";

// FULL URL of your script directory.
$full_url = "http://localhost/";

// What is the URL of your website.
$website_address = "http://localhost/";

// FULL URL of the renewal.php...include renewal.php.
$renewal_link = "http://localhost/renewal.php";

// FULL URL of the renewal.php...include renewal.php.
$renewal_thank_you = "http://localhost/thankyourenewal.php";

// Where is the admin home?
$admin_home_page = "http://localhost/admin/admin_home.php";

// Index Page after user successfully logs in.
$member_index = "http://localhost/";

// Enter 1 to keep detailed stats.
$keep_stats = "1";

// Enter 1 to block users.
$block_users = "0";

// URL of login.php
$login_page = "http://localhost/login.php";

// If you want to use radio buttons instead of drop down box on the form enter 1.
$use_radio = "";

// Connecting to database for future queries.
$db = mysql_connect($db_host, $db_user_name, $db_password);
if (!db){
	echo "Error: Could Not Connect To Database.  Please check user name and password in config.php file.";
	exit;
}

$db_select = mysql_select_db($db_name);
if (!$db_select) {
	echo "Error: Could Not Connect To Database.  Please check database name in config.php file.";
	exit;
}

/*******  DO NOT EDIT BELOW THIS LINE *******/

session_start();
global $valid_user;
$user = $valid_user;
$todays_date = date("Ymd");
$member_date = date("Y-m-d");
$ok = "deny";
if (session_is_registered("valid_user")) {
	$check_user = "SELECT * FROM memberships WHERE user_name = \"$user\" AND active = \"1\" AND paid_until_date >= \"$member_date\"";
	$result = mysql_query($check_user);
	while ($row = mysql_fetch_object($result)) {
		if (stristr($row->package,$page_account)) {
			$ok = "valid";
		}
	}
	if ($ok != "valid" && $page_account != "form") {
		if ($include_template == "1") {
			include "$template_directory/header.php";	
		}
		echo "<center><h3>It's here Access Denied!</h3></center>";
		if ($include_template == "1") {
			include "$template_directory/footer.php";	
		}
		$update_user = "UPDATE memberships SET active = \"0\" WHERE id = \"$row->id\"";
		mysql_query($update_user);
		exit;
	}
}else{
	if ($page_account != "form") {
		header("Location: $login_page");	
		exit;
	}
}



if ($block_users == "1") {
	if (!file_exists("$member_temp"."/"."$user/"."$todays_date")) {
		if (!file_exists("$member_temp"."/"."$user/")){
			mkdir ("$member_temp/$user", 0755);
		}
		
		mkdir ("$member_temp/$user/$todays_date", 0755);
		if (!file_exists($member_temp."/".$user."/".$todays_date."/".$REMOTE_ADDR)) {
			$temp_file_name = $REMOTE_ADDR;
			$temp_output = "";
			$fp = fopen("$member_temp/$user/$todays_date/$REMOTE_ADDR", "w");
			fwrite($fp, $temp_output);
		}
	}else{
		if (!file_exists($member_temp."/".$user."/".$todays_date."/".$REMOTE_ADDR)) {
			$x = 0;
		
			$current_directory = "$member_temp/$user/$todays_date";
			$dir = opendir($current_directory);
		
			while($file = readdir($dir)){
				$x += 1;
			}
		
			if ($x > $block_number) {
				$member_sql = "UPDATE memberships SET active = \"0\" WHERE user_name = \"$user\" AND package = \"$page_account\"";
				mysql_query($member_sql);
				if ($include_template == "1") {
					include "$template_directory/header.php";	
				}
				echo "<center><h3>hhhAccess Denied!</h3></center>";
				if ($include_template == "1") {
					include "$template_directory/footer.php";	
				}
				exit;
			}else{
				$temp_file_name = $REMOTE_ADDR;
				$temp_output = "";
				$fp = fopen("$member_temp/$user/$todays_date/$REMOTE_ADDR", "w");
				fwrite($fp, $temp_output);
			}	
		}	
	}
}

if ($keep_stats == "1") {
	$the_page = $REQUEST_URI;

	$get_details_sql = "INSERT INTO members_stats (id, user_name, pages, ip)
				VALUES (\"\", \"$user\", \"$the_page\", \"$REMOTE_ADDR\");";
			
	mysql_query($get_details_sql);
}
?>