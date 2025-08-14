<?
ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phpAuth from Designanet.co.uk</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
if (isset($_POST["phpAuth_Submit"])) {

		include "phpAuth_config.php";
		$filename = $database_name;
		
	if (!file_exists($filename)) {
			
			echo "No database exists. Please check your config file.";
			
	} else {
		
		$handler = fopen($filename,'r');
		$members = file_get_contents($filename);
		// get username and password
		$users_array = explode ("\n",$members);
		
		foreach ($users_array as $users) {
			if ($users != "" || $users != NULL) {
		
				// break username and password string into seperate variables.
				list($username,$password) = explode("\t",$users);
					
				if ($_POST["phpAuth_usr"] == trim($username) && md5($_POST["phpAuth_pwd"]) == trim($password)) {	

					$logged_in = 1;
					break;
					
				} else {
						
					$logged_in = 0;
						
				}
					
			} else {
			
				break;
			
			}
			
		} // end foreach
		
		if ($logged_in != 1) { // IF USER IS NOT LOGGED IN
			
			echo "Error! Incorrect Details. <a href=\"".$_SERVER['HTTP_REFERER']."\">Go Back</a>";
		
		} else { // ELSE LOGGED IN			
					
			session_start();
			$_SESSION["phpAuth_username"] = $_POST["phpAuth_usr"];
			$_SESSION["phpAuth_password"]  = $_POST["phpAuth_pwd"];
			
				if ($_POST["phpAuth_rm"] == 1) {
					setcookie("phpAuth_username",$_POST["phpAuth_usr"],time()+3600);
				}
			
			header('Location: '.$login_redirect.'');
		}	
	
	} // END IF FILE EXISTS
	
} else {	
		
?>
<form name="form1" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
  <table width="100%"  border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td><strong>Username</strong></td>
    </tr>
    <tr>
      <td><input name="phpAuth_usr" type="text" id="phpAuth_usr" value="<? if (isset($_COOKIE["phpAuth_username"])) { echo $_COOKIE["phpAuth_username"]; } ?>"></td>
    </tr>
    <tr>
      <td><strong>Password</strong></td>
    </tr>
    <tr>
      <td><input name="phpAuth_pwd" type="password" id="phpAuth_pwd"></td>
    </tr>
    <tr>
      <td>Remember Me 
        <input name="phpAuth_rm" type="checkbox" id="phpAuth_rm" value="1"></td>
    </tr>
    <tr>
      <td>Not Registered? <a href="user_register.php">Register Here</a> </td>
    </tr>
    <tr>
      <td><input name="phpAuth_Submit" type="submit" id="phpAuth_Submit" value="Login"></td>
    </tr>
  </table>
</form>
<?
}
?>
</body>
</html>
<?
ob_end_flush();
?>