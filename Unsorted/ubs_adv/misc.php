<? 
require("functions.php");
require("header.php");

if($action == "login") {
	if(!$user_id) eval("dooutput(\"".gettemplate("login")."\");");
	else {
		eval ("\$output = \"".gettemplate("error26")."\";");
		eval("dooutput(\"".gettemplate("action_error")."\");");
	}
}

if($action == "access_error") {
	if(!$user_id) eval ("\$login = \"".gettemplate("access_error_login")."\";");
	else eval ("\$login = \"".gettemplate("access_error_logout")."\";");
	eval("dooutput(\"".gettemplate("access_error")."\");");
}

if($action == "disclaimer") {
	if(!$user_id) {
		if($register) eval("dooutput(\"".gettemplate("disclaimer")."\");");
		else eval("dooutput(\"".gettemplate("registration_disable")."\");");
	}
	else {
		eval ("\$output = \"".gettemplate("error26")."\";");
		eval("dooutput(\"".gettemplate("action_error")."\");");
	}
}

if($action == "register") {
	if(!$user_stat) {
		if($register) eval("dooutput(\"".gettemplate("register")."\");");
		else eval("dooutput(\"".gettemplate("registration_disable")."\");");
	}
	else {
		$username = getUsername($user_id);
		eval ("\$output = \"".gettemplate("error26")."\";");
		eval("dooutput(\"".gettemplate("action_error")."\");");
	}
}

if($action == "formmail") {
	if($userid) $userinput = "<INPUT TYPE=\"HIDDEN\" NAME=\"userid\" VALUE=\"$userid\">";
	else {
		$threadname = getThreadname($threadid);
		eval ("\$formmail_to = \"".gettemplate("formmail_to")."\";");
		eval ("\$formmail_message = \"".gettemplate("formmail_message")."\";");
	}
	
	if($user_id) $email = getUserEmail($user_id);
	eval("dooutput(\"".gettemplate("formmail")."\");");
}
?>
			
					