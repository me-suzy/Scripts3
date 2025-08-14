<?php
$filename="forgotpw.php";

require ("global.php");

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="";

if(!$action) {
 if(isset($_POST['send'])) {
  $result=$db->query_first("SELECT userid, username, email, password FROM bb".$n."_users WHERE username='".addslashes(htmlspecialchars(trim($_POST['username'])))."'");	
  if(!$result['userid']) eval("error(\"".$tpl->get("error_usernotexist")."\");");
  
  $pwhash = md5($result['password']);
  
  eval ("\$message = \"".$tpl->get("forgotpw_message1")."\";");
  eval ("\$subject = \"".$tpl->get("forgotpw_subject1")."\";");
  mailer($result['email'],$subject,$message);
  eval("redirect(\"".$tpl->get("redirect_hashsend")."\",\"index.php?sid=$session[hash]\",10);");
 }	
	
 eval("\$tpl->output(\"".$tpl->get("forgotpw")."\");");	
}

if($action=="pw") {
 $result=$db->query_first("SELECT userid, username, email, password FROM bb".$n."_users WHERE userid='".intval($_REQUEST['userid'])."'");	
 if(!$result['userid']) eval("error(\"".$tpl->get("error_usernotexist")."\");");	
 if($_REQUEST['pwhash']!=md5($result['password'])) eval("error(\"".$tpl->get("error_falseverifycode")."\");");	
	
 $newpw=password_generate(2,8);
 $db->unbuffered_query("UPDATE bb".$n."_users SET password='".md5($newpw)."' WHERE userid='".intval($_REQUEST['userid'])."'",1);
 eval ("\$message = \"".$tpl->get("forgotpw_message2")."\";");
 eval ("\$subject = \"".$tpl->get("forgotpw_subject2")."\";");
 mailer($result['email'],$subject,$message);
 eval("redirect(\"".$tpl->get("redirect_pwsend")."\",\"index.php?sid=$session[hash]\",10);");
}
?>
