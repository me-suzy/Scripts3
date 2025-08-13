<?
session_start();

include_once("define.inc.php");
include_once("main.inc.php");

// ########## BEGIN USER PANEL LOGIN ########## //

if ($_SERVER["QUERY_STRING"] == "login") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$table = $dl->select("uid","sl18_users",array('urealname'=>$_POST["loginusername"],'upass'=>$_POST["loginpassword"]));

	if ( count( $table ) != 0 ) {	
		session_register("uid");
		$uid = $table[0]["uid"];	


	}
	header("Location: " . SL_ROOT_URL . "/userpanel.php");
} 

// ########## END USER PANEL LOGIN ########## //



// ########## BEGIN USER PANEL LOGOUT ########## //

if ($_SERVER["QUERY_STRING"] == "logout") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	
	$dl->update("sl18_users",array('uactive'=>"0000-00-00"),array('uid'=>$_SESSION["uid"]));

	session_unset();
	session_destroy();

	header("Location: " . SL_ROOT_URL);

}
// ########## END REMOVE USER PANEL LOGOUT ########## //


// ########## BEGIN PROFILE ALTER ########## //

if ($_SERVER["QUERY_STRING"] == "profile") {

	$test = array(upenname,upass,uemail,uurl,umsn,uaol,uicq,uava,ubio);
	foreach($test as $is) {
		if( !empty($_POST[$is]) )
			$_POST[$is] = strip_tags(trim($_POST[$is]));
		else {
			if ($is == "upenname" || $is == "upass" || $is == "uemail") {
				header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");
				die();
			}
		}
	}

	foreach($test as $is) {
		if( empty($_POST[$is]) ) {
			if($is == "upenname" || $is == "upass" || $is == "uemail") {
				header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");
				die();
			}
		}
	}


	if (strlen($_POST["upenname"]) > 10 || strlen($_POST["upenname"]) < 3) {
		header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");
		die();
	}

	if (!eregi('^[A-Z0-9]+@([A-Z0-9-]+.)+([A-Z0-9]){2,4}$',$_POST["uemail"])) {
		header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");
		die();
	}

	if (!eregi("^[A-Z]*$",$_POST["upenname"])) {
		header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");
		die();
	}


	

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$dl->update("sl18_users",
		array(
			'upenname'=>$_POST["upenname"],
			'upass'=>$_POST["upass"],
			'uemail'=>$_POST["uemail"],
			'uurl'=>$_POST["uurl"],
			'umsn'=>$_POST["umsn"],
			'uaol'=>$_POST["uaol"],
			'uicq'=>$_POST["uicq"],
			'uava'=>$_POST["uava"],
			'ubio'=>$_POST["ubio"]
		),
	array('uid'=>$_SESSION["uid"]));

	header("Location: " . SL_ROOT_URL . "/userpanel.php?profile");

} 

// ########## END PROFILE ALTER ########## //



// ########## BEGIN STORY EDIT ########## //

if ($_SERVER["QUERY_STRING"] == "editstory") {

	$test = array(sname,sdescrip,addcat,addsubcat);
	foreach($test as $is) {
		if( !empty($_POST[$is]) )
			$_POST[$is] = strip_tags(trim($_POST[$is]));
		else {
			header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
			die();
		}
	}

	foreach($test as $is) {
		if( empty($_POST[$is]) ) 
			header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");		
	}

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$table = $dl->update("sl18_stories",
		array(
			'sname'=>$_POST["sname"],
			'sdescrip'=>$_POST["sdescrip"],
			'sadd'=>$_POST["sadd"],
			'srating'=>$_POST["srating"],
			'scid'=>$_POST["addcat"],
			'ssubid'=>$_POST["addsubcat"]
		),
	array('sid'=>$_POST["sid"],'suid'=>$_SESSION["uid"]));

	header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");

} 

// ########## END STORY EDIT ########## //

// ########## BEGIN ADD CHAPTER ########## //

if ($_SERVER["QUERY_STRING"] == "addchapter") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;


	$_POST["cname"] = trim(strip_tags($_POST["cname"]));

	if (!$_POST["cname"]) {										// if no title
		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
		die();

	} if (!empty($_POST["upchapb"]) && !empty($_FILES["upchapa"]["name"])) {					// if both areas filled
		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
		die();

	} if (!empty($_POST["upchapb"]) && empty($_FILES["upchapa"]["name"])) {					// if textarea
		$_POST["upchap"] = trim(strip_tags($_POST["upchapb"]));
	}
		
	else {												// if upload
		if ($_FILES["upchapa"]["type"] == "text/plain" || $_FILES["upchapa"]["type"] == "text/html") {		// correct type
			$_POST["upchap"] = implode("",file($_FILES["upchapa"]["tmp_name"]));
			$_POST["upchap"] = trim(strip_tags($_POST["upchap"]));
		} else {
			header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
			die();
		}
	}
	
	if (!empty($_POST["upchap"]) && !empty($_POST["cname"])) {						// got everything?
		$chapter["cname"] = $_POST["cname"];
		$chapter["cuid"] = $_SESSION["uid"];
		$chapter["cdate"] = date("Y-m-d");
		$chapter["csid"] = $_POST["sid"];
		$chapter["cbody"] = $_POST["upchap"];

		$dl->insert("sl18_chapters",$chapter) or die($dl->getError());
		$dl->update("sl18_stories",array('stime'=>date("YmdHis"),'scdate'=>date("Y-m-d")),array('sid'=>$_POST["sid"])) or die($dl->getError());

		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
		die();
	} else {
		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
		die();
	}
} 

// ########## END ADD CHAPTER ########## //


// ########## BEGIN EDIT CHAPTER ########## //

if ($_SERVER["QUERY_STRING"] == "editchapter") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	if(empty($_POST["cbody"]) || empty($_POST["cname"])) {
		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
		die();
	} else {
		$dl->update("sl18_chapters",array('cbody'=>$_POST["cbody"],'cname'=>$_POST["cname"]),array('cid'=>$_POST["cid"]));
		$dl->update("sl18_stories",array('stime'=>date("YmdHis")),array('sid'=>$_POST["csid"]));
		header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");
	}
}
// ########## END EDIT CHAPTER ########## //


// ########## BEGIN DELETE CHAPTER ########## //

if ($_SERVER["QUERY_STRING"] == "deletechapter") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$dl->delete("sl18_chapters",array('cid'=>$_POST["cid"]));
	header("Location: " . SL_ROOT_URL . "/userpanel.php?edit");

}
// ########## END DELETE CHAPTER ########## //

// ########## BEGIN DELETE STORY ########## //

if ($_SERVER["QUERY_STRING"] == "deletestory") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$dl->delete("sl18_stories",array('sid'=>$_POST["sid"],'suid'=>$_SESSION["uid"]));
	$dl->delete("sl18_chapters",array('csid'=>$_POST["sid"]));
	$dl->delete("sl18_review",array('rsid'=>$_POST["sid"]));
	$dl->delete("sl18_rate",array('ratsid'=>$_POST["sid"]));

	header("Location: " . SL_ROOT_URL . "/userpanel.php?delete");

}
// ########## END DELETE STORY ########## //

// ########## BEGIN UPDATE RECS ########## //

if ($_SERVER["QUERY_STRING"] == "recs") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	
	foreach($_POST["recs"] as $is) {
		if(ereg("^[0-9]+$",$is) && $is > 0)
			$rec[] = $is;
	}

	if(count($rec) == 0)
		$recs = "";
	else {
		$rec = array_unique($rec);
		$recs = implode("|",$rec);
	}

	$dl->update("sl18_users",array('urecs'=>$recs),array('uid'=>$_SESSION["uid"]));
	header("Location: " . SL_ROOT_URL . "/userpanel.php?recs");

}
// ########## END UPDATE RECS ########## //

// ########## BEGIN REMOVE REVIEW ########## //

if ($_SERVER["QUERY_STRING"] == "revs") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	
	$dl->delete("sl18_review",array('rsid'=>$_POST["sid"],'rid'=>$_POST["rid"]));
	header("Location: " . SL_ROOT_URL . "/userpanel.php?revs");

}
// ########## END REMOVE REVIEW ########## //

// ########## BEGIN ADD REVIEW ########## //

if ($_SERVER["QUERY_STRING"] == "addreview") {

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;
	
	if (empty($_POST["rbody"])) {
		header("Location: " . SL_ROOT_URL . "/review.php?set=add&no=".$_POST["rsid"]);
		die();
	}
	
	if (!empty($_POST["ruid"])) {
		$user = $dl->select("*","sl18_users",array('uid'=>$_POST["ruid"]));
		$_POST["ruser"] = $user[0]["upenname"];
	} 

	if (empty($_POST["ruid"]) && empty($_POST["ruser"])) {
		$_POST["ruser"] = "Anon";
	} 


	$dl->insert("sl18_review", array(
		'rsid'=>$_POST["rsid"],
		'ruser'=>$_POST["ruser"],
		'rbody'=>$_POST["rbody"],
		'rdate'=>date("Y-m-d"),
		'remail'=>$_POST["remail"],
		'ruid'=>$_POST["ruid"])
	);
	header("Location: " . SL_ROOT_URL . "/review.php?set=read&no=".$_POST["rsid"]);

}
// ########## END ADD REVIEW ########## //

?>