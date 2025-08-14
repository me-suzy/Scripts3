<?php
//8.1
$blocks_modules[login] = array(
	'title' => "Login",
	'func_display' => 'blocks_login_block',
	'func_add' => '',
	'func_update' => '',
	'text_type' => 'Login',
	'text_type_long' => '',
	'text_content' => "User's Login",
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
    'show_preview' => true,
    'has_templates' => true
    );
function blocks_login_block($row) {
  global $displayloggedin,$cookietimeout,$info,$DB_site,$bburl,$bbuserinfo,$user,$username,$userid,$newusername,$newuserid,$newsforum,$block_sidetemplate;
  $numbersmembers=$DB_site->query_first('SELECT COUNT(*) AS users,MAX(userid) AS max FROM user');
  $numbermembers=$numbersmembers['users'];
  $getnewestusers=$DB_site->query_first("SELECT userid,username FROM user WHERE userid=$numbersmembers[max]");
  $newusername=$getnewestusers['username'];
  $newuserid=$getnewestusers['userid'];
  if ($displayloggedin) {
    $datecut=time()-$cookietimeout;
	$guestonline = $DB_site->query_first("SELECT COUNT(host) AS sessions FROM session WHERE userid=0 AND lastactivity>$datecut");
	$membersonline = $DB_site->query("SELECT DISTINCT userid FROM session WHERE userid<>0 AND lastactivity>$datecut");
	$guests = number_format($guestonline['sessions']);
	$members = number_format($DB_site->num_rows($membersonline));
	$totalonline = number_format($guests + $members);
  }
  $newpms=$DB_site->query_first("SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid] AND dateline>$bbuserinfo[lastvisit] AND folderid=0");
  if ($bbuserinfo['userid']!=0) {
    include("config.php");
    $userid=$bbuserinfo['userid'];
    $username=$bbuserinfo['username'];
    if($row[templates]) {
		 eval("\$block_content .= \"".gettemplate('P_logoutcode')."\";");
	}else{
		// Edit logout code here
	    $block_content = "<p align=\"center\"><smallfont>Welcome back<br><i>$username</i>. <br>
		You last visited:<br> $bbuserinfo[lastvisitdate].<br>
                You have $newpms[messages] new message(s) since your last visit.<br><br>
		Our newest member <a href=\"$bburl/member.php?s=$session[sessionhash]&action=getinfo&userid=$newuserid\"><br>
		<b><font color=\"#000000\">$newusername</font></b></a><br><br></smallfont><table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#000000\" width=\"100%\">
		<tr>
		<td width=\"100%\" bgcolor=\"{categorybackcolor}\">
	    <p align=\"center\"><font size = \"2\" color =\"{categoryfontcolor}\"><b>Options</b></font></td>
		</tr>
		<tr>
		<td width=\"100%\" bgcolor=\"{secondaltcolor}\"><smallfont>
		<li><smallfont><a href=\"$bburl/newthread.php?s=$session[sessionhash]&action=newthread&forumid=$newsforum\">Submit News</a></smallfont></li>
		<li><smallfont><a href=\"$bburl/search.php?s=$session[sessionhash]&action=getnew\">View New Posts</a></smallfont></li>
	        <li><smallfont><a href=\"$bburl/usercp.php?s=$session[sessionhash]\">My Info</a></smallfont></li>
		<li><smallfont><a href=\"$bburl/online.php?s=$session[sessionhash]\">$totalonline Users OnLine</a></smallfont></li>
		<li><smallfont><a href=\"$bburl/member2.php?s=$session[sessionhash]&action=viewlist&userlist=buddy\">Your Buddies</a></smallfont></li>
		<li> <smallfont><a href=\"$bburl/member.php?s=$session[sessionhash]&action=logout\">Log out</a></smallfont></li>
		</smallfont><br><br></td>
		</tr>
		</table>
		<p align=\"center\">";
    }
  } else {
	if($row[templates]) {
		eval("\$block_content .= \"".gettemplate('P_logincode')."\";");
	}else{
		$user="";
		// Edit Login code here
		$block_content = "<form action=\"$bburl/member.php\" method=\"post\">
		<input type=\"hidden\" name=\"action\" value=\"login\"><input type=\"hidden\" name=\"s\" value=\"$session[sessionhash]\">
		<input type=\"hidden\" name=\"url\" value=\"$nukeurl/index.php\">
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
		<tr><td nowrap>
		<br>
		</td></tr>
		<tr><td>
		<p align=\"center\"><smallfont><b>Username</b>&nbsp;</smallfont><INPUT TYPE=\"TEXT\" NAME=\"username\" SIZE=7>
		<br><smallfont><b>Password</b>&nbsp;&nbsp;</smallfont><INPUT TYPE=\"PASSWORD\" NAME=\"password\" SIZE=7><br>
		<center>
		<input type=\"submit\" value=\"Login!\"></center>
		</p>
		</td></tr>
		<tr><td nowrap>
		<p align=\"center\"><smallfont><br>
		Don't have an account? <br>
		<a href=\"$bburl/register.php?action=signup\">Register</a> for one now!<br><br>
		<a href=\"$bburl/member.php\">Lost Password</a>
		</smallfont>
		</td></tr>
		</table>
		</form>";
	}
  }
  $block_title = $row[title];
  eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
}
?>