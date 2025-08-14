<?
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."config/config_admin.php");
	if(!isset($cmd)||$cmd==""||$cmd==0){
		print preg_replace("/<#error#>/",urldecode($err), ReadTemplate($ADMIN_LOGIN_FORM_TMP));
	}else{
		session_register("alogin");
		session_register("apw");
		$alogin = $login;
		$apw = $pw;
		header("Location: admin.php");
		exit;
	}
?>