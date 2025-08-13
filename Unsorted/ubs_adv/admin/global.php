<?
require "data.inc.php";
require "db_connect.php";

$db_connect = new db_connect;

$db_connect->appname="Character battle system";
$db_connect->database=$mysqldb;
$db_connect->server=$mysqlhost;
$db_connect->user=$mysqluser;
$db_connect->password=$mysqlpassword;

$db_connect->connect();

// ####################### Sessions ##############################

session_name("sid");
session_start();
if(!$sid) $sid = session_id();

if(!$user_id) $user_id = $HTTP_SESSION_VARS[user_id];
if(!$user_password) $user_password = $HTTP_SESSION_VARS[user_password];

$session = "&sid=".$sid;
$session2 = "?sid=".$sid;


function gettemplate($template) {

        $file = file("templates/".$template.".htm");
        $template = implode("",$file);
        $template = str_replace("\"","\\\"",$template);
        return $template;
}

function dooutput($template) {

        echo $template;
}

function getUserid($usernick) {
        global $n,$db_connect;
        $result = $db_connect->query_first("SELECT userid FROM db_users WHERE username='$usernick'");
        return $result[userid];
}

function getUser_stat($userid,$password)  {
	global $n,$db_connect;
	$result = $db_connect->query_first("SELECT groupid, blocked FROM db_users WHERE userid='$userid' AND userpassword='$password' AND activation='1'");
        $result = $db_connect->query_first("SELECT canuseacp FROM db_groups WHERE id = '$result[groupid]'");
        return $result[0];
}

function editDBdata($data) {
	$data = str_replace("&acute;","'", $data);
	$data = str_replace("&quot;","\"", $data);
	return $data;
}

function editPostdata($data) {
	$data = str_replace("'","&acute;", $data);
	$data = str_replace("\"","&quot;", $data);
	return $data;
}

function check_boardobject($boardid,$objectid,$field) {
	global $db_connect, $n;
	$result = $db_connect
->query_first("SELECT COUNT(*) FROM db_object2board WHERE boardid = '$boardid' AND objectid = '$objectid' AND $field = 1");
	return $result[0];
}

function checkemail($email) {
	
	global $db_connect, $n;
	if(!substr_count($email,"@") || substr_count($email,"@")>1) return 1;
	$position1 = strrpos($email,"@");
	if(!$position1) return 1;
	$position2 = strrpos($email,".");
	if(!$position2) return 1;
	if(strlen(substr($email, $position2)) < 3)return 1;
	if(strlen(substr($email, $position1,$position2-$position1-1))<2) return 1;

	$result = $db_connect->query_first("SELECT multi_email, banemail FROM db_configuration");
	if(!$result[0]) {
		$check = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE useremail = '$email'");
		if($check[0]) return 1;
	}
	$banemail = explode("\n",$result[banemail]);
	for($i = 0; $i < count($banemail); $i++) {
		if(!trim($banemail[$i])) continue;
		if(ereg("\*", $banemail[$i])) {
			$banemail[$i] = str_replace("*",".*", trim($banemail[$i]));
			if(eregi("$banemail[$i]", $email)) return 1;
			break;
		}
		elseif(strtolower($email)==strtolower(trim($banemail[$i]))) {
			return 1;
			break;
		}  
	}
	
}

function checkname($name) {
	global $db_connect, $n;
	$check = $db_connect->query_first("SELECT COUNT(userid) FROM db_users WHERE username = '$name'");
	return $check[0];
}

function Hackdate($time) {
	global $db_connect, $n;
	$config = $db_connect->query_first("SELECT timeoffset, shortdateformat, timetype  FROM db_configuration");
	$out = str_replace("DD",date("d", $time+(3600*$config[timeoffset])), $config[shortdateformat]);
	$out = str_replace("MM",date("m", $time+(3600*$config[timeoffset])), $out);
	$out = str_replace("YYYY",date("Y", $time+(3600*$config[timeoffset])), $out);
	$out = str_replace("YY",date("y", $time+(3600*$config[timeoffset])), $out);
	$out = str_replace("MN",get_month_name(date("n", $time+(3600*$config[timeoffset]))), $out);
	if($config[timetype]) { #12 Stunden
		$out = str_replace("II","II ".date("A", $time+(3600*$config[timeoffset])), $out);	
		$out = str_replace("HH",date("h", $time+(3600*$config[timeoffset])), $out);
	}
	else $out = str_replace("HH",date("H", $time+(3600*$config[timeoffset])), $out);
	$out = str_replace("II",date("i", $time+(3600*$config[timeoffset])), $out);
	return $out;
}

function get_month_name($month_number) {
	$name_monat[1]    =  "January";  
	$name_monat[2]    =  "February";  
	$name_monat[3]    =  "March";  
	$name_monat[4]    =  "April";  
	$name_monat[5]    =  "May";  
	$name_monat[6]    =  "June";  
	$name_monat[7]    =  "July";  
	$name_monat[8]    =  "August";  
	$name_monat[9]    =  "September";  
	$name_monat[10]  =  "October";  
	$name_monat[11]  =  "November";  
	$name_monat[12]  =  "December";
	
	return $name_monat[$month_number];
}

function bb_order() {
	global $db_connect, $n;
	
	$board_result = $db_connect->query("SELECT boardid FROM bb".$n."_boards");
	while($boards = $db_connect->fetch_array($board_result)) {
		$countp = $db_connect->query_first("SELECT COUNT(postid) FROM bb".$n."_posts WHERE boardparentid = '$boards[boardid]'");
               	$countt = $db_connect->query_first("SELECT COUNT(threadid) FROM bb".$n."_threads WHERE boardparentid = '$boards[boardid]'");
                $lastpost = $db_connect->query_first("SELECT postid, posttime FROM bb".$n."_posts WHERE boardparentid = '$boards[boardid]' ORDER BY posttime DESC LIMIT 1");
                $db_connect->query("UPDATE bb".$n."_boards SET posts = '$countp[0]', threads = '$countt[0]', lastposttime = '$lastpost[posttime]', lastpostid = '$lastpost[postid]' WHERE boardid = '$boards[boardid]'");	
	}
}

function ifelse ($expression,$returntrue,$returnfalse) {
	if (!$expression) return $returnfalse;
	else return $returntrue;
}

?>
