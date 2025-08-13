<? 
require("_functions.php");
require("_header.php");
require("_board_jump.php");

if($action == "ip") {

	$post = $db_zugriff->query_first("SELECT boardparentid, userid, ip FROM bb".$n."_posts WHERE postid='$postid'");
	if($userdata[canuseacp] || $userdata[issupermod] || ($userdata[ismod] && check_boardobject($boardid,$user_id,"mod"))) {
		$author = getUsername($post[userid]);
		eval("dooutput(\"".gettemplate("show_ip")."\");");
	}
	else header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session");
}

if($action == "report") {

	if(!$user_id) header("LOCATION: misc.php?action=access_error&boardid=$boardid&styleid=$styleid$session");
	else {
		if($session) $session_post = "<input type=\"hidden\" name=\"sid\" value=\"$sid\">";
		eval("dooutput(\"".gettemplate("report")."\");");
	}
}

if($action == "smilies") {
	
	$result = $db_zugriff->query("SELECT * FROM bb".$n."_smilies");
	
	while($row = $db_zugriff->fetch_array($result)) eval ("\$help_smiliebit .= \"".gettemplate("help_smiliebit")."\";");
	eval("dooutput(\"".gettemplate("help_smilies")."\");");
}

if($action == "showrank") {
	$rank = $db_zugriff->query_first("SELECT bb".$n."_ranks.*, bb".$n."_groups.title as groupname FROM bb".$n."_ranks, bb".$n."_groups WHERE bb".$n."_ranks.groupid = bb".$n."_groups.id AND bb".$n."_ranks.id='$id'");
		for($i = 0; $i < $rank[mal]; $i++) {
			$grafik .= "<img src=\"$rank[grafik]\">";
		}
		$status = editDBdata($rank[groupname]);
		$rankname = editDBdata($rank[rank]);
	eval("dooutput(\"".gettemplate("show_rank")."\");");
}

if($action == "faq") {
	if(!$page) eval("dooutput(\"".gettemplate("faq")."\");");
	if($page==1) {
		$result = $db_zugriff->query("SELECT bb".$n."_ranks.*, bb".$n."_groups.title as groupname FROM bb".$n."_ranks, bb".$n."_groups WHERE bb".$n."_ranks.groupid = bb".$n."_groups.id ORDER by bb".$n."_ranks.groupid DESC, bb".$n."_ranks.posts ASC");
		while($row = $db_zugriff->fetch_array($result)) {
			$backcolor = rowcolor($j++);
			unset($grafik);
			for($i = 0; $i < $row[mal]; $i++) {
				$grafik .= "<img src=\"$row[grafik]\">";
			}
			$rank = editDBdata($row[rank]); 
			$status = editDBdata($row[groupname]);
			eval ("\$rankbit .= \"".gettemplate("faq_rankbit")."\";");
		}
		eval("dooutput(\"".gettemplate("faq_page1")."\");");
	}
	if($page==2) eval("dooutput(\"".gettemplate("faq_page2")."\");");
	if($page==3) eval("dooutput(\"".gettemplate("faq_page3")."\");");
}

if($action == "forgotpw") {
	if($send == "send") {
		$result = $db_zugriff->query_first("SELECT userid, username, userpassword, useremail FROM bb".$n."_user_table WHERE username = '".trim($username)."'");
		if(!$result[userid]) {
			eval ("\$output = \"".gettemplate("error1")."\";");
			eval("dooutput(\"".gettemplate("action_error")."\");");
			exit;
		}
		eval ("\$betreff = \"".gettemplate("forgotpw_betreff1","txt")."\";");
		eval ("\$inhalt = \"".gettemplate("forgotpw_mail1","txt")."\";");
		mail($result[useremail],$betreff,$inhalt,"From: $master_email");
		
		header("Location: main.php$session2");	
		exit;
	}
	

	eval("dooutput(\"".gettemplate("forgotpw")."\");");
}
?>
			
					