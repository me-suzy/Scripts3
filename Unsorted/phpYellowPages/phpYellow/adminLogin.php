<?php require_once("util.php");?>
<?php 
// begin SECURITY - DO NOT CHANGE!
$loginAttempts = !isset($loginAttempts)?0:$HTTP_POST_VARS['loginAttempts'];
/* compare the PHP server version with the phpYellow Pages required version to see if 4.1.2 session handling works */
$currentPHPversion = phpversion();
$currentPHPversion = str_replace  ( ".", "", $currentPHPversion); // remove period characters example 4.1.2
$currentPHPversion = substr($currentPHPversion, 0, 2); // get the first 2 characters
// do the same data handling again for the benchmark php version of PHP 4.1.2
$yardstick = 41; // actually 4.1.2 but we chop the 2 anyway 
if($currentPHPversion < $yardstick) { // is current php version greater or less than the minimum needed 4.1.2?
	// less than or equal to code here
	// handle with no superglobals
	if (($formuser == ADMINUSER ) && ($formpassword == ADMINPASSWORD )) {
		$adminHome = ADMINHOME;
		include($adminHome);
		exit;
	}
	if(($formuser != ADMINUSER ) || ($formpassword != ADMINPASSWORD )) {
		if ($loginAttempts == 0) { /* 3 strikes and they're out */
			$loginAttempts = 1;
			include("adminLoginForm.php");
		}else{
			$loginAttempts++;
			if ( $loginAttempts >= 4 ) {
				echo "<blink><p align='center' style=\"font-weight:bold;font-size:170px;color:red;font-family:sans-serif;\">Log In<br>Failed.</p></blink>";		
				exit;
			}else{
				include("adminLoginForm.php");
				exit;
			}
		}
	}	
}else{ // greater than code here
	/* test for valid username and password
	   if valid then initialize the session
		register the username and password variables
		and redirect to the ADMINHOME page
	*/
	// initialize or retreive the current values for the login variables
	$formuser = !isset($_POST['formuser'])?NULL:$_POST['formuser'];
	$formpassword = !isset($_POST['formpassword'])?NULL:$_POST['formpassword'];
	$loginAttempts = !isset($_POST['loginAttempts'])?NULL:$_POST['loginAttempts'];



	if (($formuser == ADMINUSER ) && ($formpassword == ADMINPASSWORD )) {	// test for valid username and password
		session_name();
		session_start(); 
		session_register('adminUser');
		session_register('adminPassword');
		session_register('sessionloginAttempts');
		$HTTP_SESSION_VARS['sessionloginAttempts'] = $loginAttempts;
		$HTTP_SESSION_VARS['adminUser'] = ADMINUSER;
		$HTTP_SESSION_VARS['adminPassword'] = ADMINPASSWORD;
		$SID = session_id();
		$adminHome = ADMINHOME;
		include($adminHome);
	}else{
		if ($loginAttempts == 0) { /* 3 strikes and they're out */
			$loginAttempts = 1;
			include("adminLoginForm.php");
		}else{
			$loginAttempts++;
			if ( $loginAttempts >= 4 ) {
				echo "<blink><p align='center' style=\"font-weight:bold;font-size:170px;color:red;font-family:sans-serif;\">Log In<br>Failed.</p></blink>";		
				exit;
			}else{
				include("adminLoginForm.php");		
			}
		}
	}	
}
?>