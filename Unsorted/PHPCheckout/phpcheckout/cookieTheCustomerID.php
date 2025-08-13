<?php
//////////// cookieTheCustomerID.php ///////////////// START OF COOKIE CODE
$remember_me = !isset($_POST['remember_me'])? NULL: $_POST['remember_me'];
$customeridCookie = !isset($_COOKIE['customeridCookie'])? $_REQUEST['customerid']: $_COOKIE['customeridCookie']; // initialize or capture
if($remember_me == "on") { // if the user selected to be remembered
	if(!empty($customerid)) { // if customerid exists to make a new cookie out of
		// delete the old cookie if it existed
		if(!empty($customeridCookie)) { // only delete the cookie if one exists
			// an old cookie exists, so delete it
			// set the expiration date to one hour ago
			setcookie ("customeridCookie", "", time() - 3600);
		}
		// next make the new cookie with a cookie lifetime of one (1) year
		// calculate 1 year from today
		$nextYear = mktime (0,0,0,date("m"),date("d"),date("Y")+1);
		if( setcookie("customeridCookie", "$customerid", $nextYear )) { // if the cookie was set
			$cookieWasSet = "true";
			//echo"<br><br><br>new cookie set. customeridCookie is: $customerid and expiry is: $nextYear<br>";
		} // if the cookie was set
	}else{ // if(!empty($customerid
					//echo"<h3>Cookie not made. No customerid exists</h3>";
	} // if(!empty($customerid
} // if the user selected to be remembered
///////////////////////////// END OF COOKIE CODE
?>