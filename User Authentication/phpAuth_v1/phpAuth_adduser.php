<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>phpAuth : Register User</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?
	if (isset($_POST["phpAuth_Add"])) {
	
	include "phpAuth_config.php";
	
	$filename = $database_name;
	$handler = fopen($filename,'a+');

	// CHECK IF USERNAME EXISTS!
	$read = file_get_contents($filename);
	$usr_pwd = explode("\n",$read);
	
	$register_user = "1"; // Default!
	
		foreach ($usr_pwd as $usr_pwd_array) {
			if ($usr_pwd_array != "" || $usr_pwd_array != NULL) {
				list($db_username, $db_password) = explode("\t",$usr_pwd_array);
				
				if (trim($db_username) == trim($_POST["phpAuth_usr"])) {
					
					$register_user = "0";
					break;
					
				} else {
				
					$register_user = "1";
					
				}
			}
		}
	
	// END CHECK IF USER EXISTS
		if (trim($_POST["phpAuth_usr"]) == "" || (trim($_POST["phpAuth_pwd"])) == "") {
			
			echo "Both username and password are required. <a href=".$_SERVER['HTTP_REFERER'].">Go Back</a>";
			
		} else if ($register_user == "0") {
		
			echo "Username already exists. <a href=".$_SERVER['HTTP_REFERER'].">Go Back</a>";
			
		} else if (file_exists($filename) && $register_user == "1") {
			
			fwrite($handler,$_POST["phpAuth_usr"]."\t".md5($_POST["phpAuth_pwd"])."\r\n");
			echo "You are now registered";
			
		} else {
		
			echo "file doesn't exist";
		
		}
	
	} else {	
	
?>
<form name="form1" method="post" action="">
  <table width="100%"  border="0">
    <tr>
      <td>&nbsp;</td>
      <td><p><strong>Register</strong></p>
      <p>* Denotes field is required </p></td>
    </tr>
    <tr>
      <td>* Username : </td>
      <td><input name="phpAuth_usr" type="text" id="phpAuth_usr"></td>
    </tr>
    <tr>
      <td>* Password : </td>
      <td><input name="phpAuth_pwd" type="password" id="phpAuth_pwd"></td>
    </tr>
    <tr>
      <td height="26">&nbsp;</td>
      <td><input name="phpAuth_Add" type="submit" id="phpAuth_Add" value="Add User"></td>
    </tr>
  </table>
</form>
<?
}
?>
</body>
</html>