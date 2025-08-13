<?php require_once("configure.php");?>
<?php 
// begin SECURITY - DO NOT CHANGE!
$phpVersion = phpversion();
if ($phpVersion < "4.1.2") { // real mccoy
	if(!isset($formuser)){$formuser = NULL;}
	if(!isset($formpassword)){$formpassword = NULL;}
	if(!isset($loginAttempts)){$loginAttempts = NULL;}	
	if(($formuser != ADMINUSER ) || ($formpassword != ADMINPASSWORD ) || (empty($formuser)) || (empty($formpassword))) {
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
	}else{
		if (($formuser == ADMINUSER ) && ($formpassword == ADMINPASSWORD )) {
			$adminHome = ADMINHOME;
			header("Location: $adminHome");
		}
	}
}else{ // if ($phpVersion
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
		header("Location: $adminHome");
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
}?>
