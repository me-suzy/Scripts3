<? 
require("functions.php");
require("header.php");

if($user_id) {
	eval ("\$output = \"".gettemplate("error26")."\";");
	eval("dooutput(\"".gettemplate("action_error")."\");");
	exit;

}
if(!$register) {
	eval("dooutput(\"".gettemplate("registration_disable")."\");");
	exit;
}

if($action == "disclaimer") eval("dooutput(\"".gettemplate("disclaimer")."\");");

if($action == "register") {
	if($send == "send") {
		if(!$name || !$email || !$emailconfirm || !$password || !$passwordconfirm || $email != $emailconfirm || $password != $passwordconfirm) eval ("\$error = \"".gettemplate("register_error1")."\";");
		elseif(checkname($name)) eval ("\$error = \"".gettemplate("register_error2")."\";");
		elseif(checkemail($email)) eval ("\$error = \"".gettemplate("register_error3")."\";");
		else {
			$default_group = $db_connect->query_first("SELECT id FROM db_groups WHERE default_group = 2");
			$default_group = $default_group[0];
			$time = time();
			$password = md5($password);
			if($act_code) {		
                        	$datum = date("s");
                           	mt_srand($datum);
                           	$z = mt_rand();
                           	$db_connect->query("INSERT INTO db_users (username,userpassword,useremail,regemail,groupid,regdate,lastvisit,lastactivity,activation,useritem1,useritem2,useritem3,useritem4,useritem5,useritem6,useritem7,useritem8,userweapon,usershield,userarmour,usergloves,userhelmet,userboots,userextra1,userextra2,userroom) VALUES ('$name','$password','$email','$email','$default_group','$time','$time','$time','1','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','useritem1','lobby')");
                        	$userid = $db_connect->insert_id();
                        	if($act_permail) {
                        		eval ("\$inhalt = \"".gettemplate("reg_mail")."\";");
                        		eval ("\$betreff = \"".gettemplate("reg_mail_betreff")."\";");
                        		$email = trim($email);
                        		mail($email,$betreff,$inhalt,"From: ".$master_email);
                        	}
                        }
                        else {
                        	$db_connect->query("INSERT INTO db_users (username,userpassword,useremail,regemail,groupid,regdate,lastvisit,lastactivity,activation,useritem1,useritem2,useritem3,useritem4,useritem5,useritem6,useritem7,useritem8,userweapon,usershield,userarmour,usergloves.userhelmet,userboots,userextra1,userextra2,userroom) VALUES ('$name','$password','$email','$email','$default_group','$time','$time','$time','1','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','Empty','useritem1','lobby')");
                       		$userid = $db_connect->insert_id();
                       		$user_id = $userid;
                       		$user_password = getUserPW($userid);
                		session_register("user_id");
                		session_register("user_password");
                		setcookie("user_id", "$user_id", time()+(3600*24*365));
				setcookie("user_password", "$user_password", time()+(3600*24*365));
                        }	
               		if($regnotify) {
				eval ("\$inhalt = \"".gettemplate("reg_notifymail")."\";");
                        	eval ("\$betreff = \"".gettemplate("reg_mail_notifybetreff")."\";");
                        	mail($master_email,$betreff,$inhalt,"From: ".$master_email);
                        }
                        		
			if($act_code) {
				if($act_permail) eval ("\$output = \"".gettemplate("register_note1")."\";");
				else eval ("\$output = \"".gettemplate("register_note2")."\";");
			}
			else eval ("\$output = \"".gettemplate("register_note3")."\";");
			$ride = "index.php";
			eval("dooutput(\"".gettemplate("action_ride")."\");");
			exit;
		}
	
	}
	eval("dooutput(\"".gettemplate("register")."\");");
}
?>
			
					