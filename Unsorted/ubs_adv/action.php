<?
require "functions.php";
// ############## Login ###############
if($action=="login") {
$userpass = md5($userpass);
$username = htmlspecialchars(trim($username));
$usercheck = checkUser($username,$userpass);
if($usercheck==2) {
                $ride="index.php";

	$user_id = getUserid($username);
	$user_password = $userpass;
	session_register("user_id");
	session_register("user_password");

	setcookie("user_id", "$user_id", time()+(3600*24*365));
	setcookie("user_password", "$user_password", time()+(3600*24*365));

	eval ("\$output = \"".gettemplate("note1")."\";");
}
if($usercheck==0) eval ("\$output = \"".gettemplate("error1")."\";");
if($usercheck==1) eval ("\$output = \"".gettemplate("error2")."\";");
}

// ############## Logout ###############
if($action=="logout") {
	$ride = "index.php";
	if(!@session_destroy()) @session_unset();
	setcookie("user_id");
	setcookie("user_password");
	if(count($cbpassword)) while(list($key,$val)=each($cbpassword)) setcookie("cbpassword[$key]");
	eval ("\$output = \"".gettemplate("note2")."\";");
}

// ############## Formmailer ###############
if($action=="formmail") {
        if($userid) {
                $useremail = getUserEmail($userid);
                $username = getUsername($userid);
        }
        if($absender && $message && $useremail) {
                formmail($absender,$message,$betreff,$useremail);
                $name = ($username ? $username : $useremail);
                eval ("\$output = \"".gettemplate("note13")."\";");
                $ride = urldecode($url_jump);
        } else eval ("\$output = \"".gettemplate("error17")."\";");
}

// ############## Activation ###############
if($action=="activation") {
        $result = activat($userid,$code);
        if($result == 1) eval ("\$output = \"".gettemplate("error1")."\";");
        if($result == 2) eval ("\$output = \"".gettemplate("error22")."\";");
        if($result == 3) eval ("\$output = \"".gettemplate("error23")."\";");
        if(!$result) {
                $user_id = $userid;
                eval ("\$output = \"".gettemplate("note21")."\";");
                $user_password = getUserPW($userid);
                session_register("user_id");
                session_register("user_password");
                setcookie("user_id", "$user_id", time()+(3600*24*365));
		setcookie("user_password", "$user_password", time()+(3600*24*365));
	}
        $ride = "main.php?styleid=$styleid$session";
}

if($action == "boardpw") {
	if(!$boardpassword) eval ("\$output = \"".gettemplate("error3")."\";");
	else {
		if($db_connect->query_first("SELECT boardid FROM bb".$n."_boards WHERE boardid='$boardid' AND boardpassword='$boardpassword'")) {
			setcookie("cbpassword[$boardid]",md5($boardpassword), time()+3600*24*365);
			$ride = urldecode($url_jump);
        	        eval ("\$output = \"".gettemplate("note25")."\";");	
		}
		else eval ("\$output = \"".gettemplate("error2")."\";");
	}
}

if($action == "forgotpw") {
	$result = $db_connect->query_first("SELECT userid, username, useremail FROM bb".$n."_user_table WHERE userid = '$userid' AND userpassword = '$code'");
	if(!$result[userid]) eval ("\$output = \"".gettemplate("error3")."\";");
	else {
		$kette = "abcdefghijklmnopqrstuvwxyz";
		for($i = 0; $i < 6; $i++) {
			$datum = date("s", time()+$i*4567);
                	mt_srand($datum);
                        $zahl = mt_rand(0,25);
                        $newpw .= substr($kette, $zahl, 1);
		}
		eval ("\$betreff = \"".gettemplate("forgotpw_betreff2","txt")."\";");
		eval ("\$inhalt = \"".gettemplate("forgotpw_mail2","txt")."\";");
		mail($result[useremail],$betreff,$inhalt,"From: $master_email");
		$db_connect->query("UPDATE bb".$n."_user_table SET userpassword = '".md5($newpw)."' WHERE userid = '$userid'");
		
		header("Location: main.php$session2");	
		exit;
	}
}

eval ("\$headinclude = \"".gettemplate("headinclude")."\";");
if($ride) eval("dooutput(\"".gettemplate("action_ride")."\");");
else eval("dooutput(\"".gettemplate("action_error")."\");");
?>
