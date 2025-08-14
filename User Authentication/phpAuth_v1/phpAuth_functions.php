<?
function phpAuth_chk_login()
{
	session_start();
	if (!isset($_SESSION["phpAuth_username"])) {
		echo "You are currently not logged in. Please go to the login page";
		exit;
	}
}
?>