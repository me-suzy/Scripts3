<? 
require("functions.php");
require("header.php");

if($mode == "view") {
	$uproseite = 30;
	$anzahl = $db_connect->query_first("SELECT COUNT(userid)as anzahl FROM db_users WHERE activation='1'");
	$anzahl = $anzahl[anzahl];
	if(!$page) $page=1;
	$user_result = $db_connect->query("SELECT * FROM db_users WHERE activation='1' ORDER by $by $order LIMIT ".($uproseite*($page-1)).",".$uproseite);
	$pages=ceil($anzahl/$uproseite);
	
	while($users = $db_connect->fetch_array($user_result)) {	
		$regdate = formatdate($users[regdate],$shortdateformat);
		eval ("\$members_view_memberbit .= \"".gettemplate("members_view_memberbit")."\";");
	}						

	if($pages>1) $page_link = makepagelink("members.php?mode=view&boardid=$boardid&styleid=$styleid&by=$by&order=$order$session", $page, $pages);
	eval("dooutput(\"".gettemplate("members_view")."\");");
}
if($mode=="profile") {
	$user_info = $db_connect->query_first("SELECT users.* FROM db_users users WHERE users.userid='$userid'");
	$regdate = formatdate($user_info[regdate],$shortdateformat);
	
	$dabeiseit = (time() - $user_info[regdate]) / 86400;
	if ($dabeiseit < 1) $beitragprotag = "$user_info[userposts]";
	else $beitragprotag = sprintf("%.2f",($user_info[userposts] / $dabeiseit)); 
	
	if($user_info[usertext]) $user_text = prepare_topic($user_info[usertext]);
	if($user_info[gender]) {
		if($user_info[gender]==1) eval ("\$gender = \"".gettemplate("profile_male")."\";");
		else eval ("\$gender  = \"".gettemplate("profile_female")."\";");
	}
	else eval ("\$gender = \"".gettemplate("profile_nodeclaration")."\";");
		
        for($i = 0; $i<$rank[mal]; $i++) $stars .= "<img src=\"$rank[grafik]\" border=\"0\">";
        $stars = "<a href=\"javascript:rank($rank[id])\" title=\"Information on Rank $rank[rank]\">".$stars."</a>";
	
	if($user_info[statusextra]) $user_rang = editDBdata($user_info[statusextra]);
	else $user_rang = editDBdata($rank[rank]);
	$userpic=$user_info[mypic];
	
	if($user_info[show_email_global]) $useremail = "<a href=\"mailto:".editDBdata($user_info[useremail])."\" class=\"link\">".editDBdata($user_info[useremail])."</a>";
	else eval ("\$useremail = \"".gettemplate("profile_nodeclaration")."\";"); 
	
	if($user_info[age_m] && $user_info[age_d] && $user_info[age_y]) $birthday = $user_info[age_d].". ".$user_info[age_m]." ".$user_info[age_y];
	else eval ("\$birthday = \"".gettemplate("profile_nodeclaration")."\";");
	eval("dooutput(\"".gettemplate("members_profile")."\");");
}
?>
			
					