<?php
require ("./global.php");

$wbb_userpassword=md5($_POST['l_password']);
$result = $db->query_first("SELECT bb".$n."_users.userid, bb".$n."_groups.canuseacp, bb".$n."_groups.issupermod, bb".$n."_groups.ismod FROM bb".$n."_users LEFT JOIN bb".$n."_groups USING (groupid) WHERE username = '".addslashes(htmlspecialchars($_POST['l_username']))."' AND password = '".$wbb_userpassword."' AND activation = 1");
if($result['userid'] && $result['canuseacp']+$result['issupermod']+$result['ismod']>0) {
 $adminsession = new adminsession();
 $adminsession->create($result['userid'],$REMOTE_ADDR,$HTTP_USER_AGENT);
 
 header("Location: index.php?sid=".$adminsession->hash);
}
else eval("acp_error(\"".gettemplate("error_login")."\");");
?>
