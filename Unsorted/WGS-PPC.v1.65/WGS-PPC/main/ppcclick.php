<?
	require("path.php");
	require($INC."config/config_main.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	$location = "";
	$nm = substr($query,0,1);
	$tid = substr($query, 31);
	$f = 0;
	if($res = dbSelectAll($TVisitors, "IP, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(VDate)")){
		while($tt = mysql_fetch_array($res)){
//			print getenv("REMOTE_ADDR")." : ".$tt["IP"]." - ".$tt["UNIX_TIMESTAMP()"]." - ".$tt["UNIX_TIMESTAMP(VDate)"]." - ".($tt["UNIX_TIMESTAMP()"]-$tt["UNIX_TIMESTAMP(VDate)"])."<hr>";
			$b = strcmp($tt["IP"],getenv('REMOTE_ADDR'));
			if($b==0){
				if(($tt["UNIX_TIMESTAMP()"]-$tt["UNIX_TIMESTAMP(VDate)"])>$TIME_DIFF){
					dbUpdate($TVisitors, "VDate=NOW()", "IP='".$tt["IP"]."'");
				}else{
					$f = 1;
				}
			}else{
				if(($tt["UNIX_TIMESTAMP(VDate)"]-$tt["UNIX_TIMESTAMP()"])>$TIME_DIFF){
					dbDelete($TVisitors, "IP='".$tt["IP"]."'");
				}
			}
		}
	}
	if($f==0){
		dbInsert($TVisitors, "IP, VDate", "'".getenv("REMOTE_ADDR")."', NOW()");
	}
	if(isset($tid)&&$tid!=""&&$tid!=0){
		if(!isset($nm)||$nm==""||$nm==0){
			$T = "$TMembersTerms, $TMembersBids";
			$F = "$TMembersTerms.MemberID, $TMembersTerms.Link, $TMembersBids.Bid";
			$Q = "$TMembersTerms.TermID=$tid and $TMembersBids.TermID=$TMembersTerms.TermID";
			if($tt = dbSelect($T, $F, $Q)){
				$location = stripslashes($tt["Link"]);
				$bid = $tt["Bid"];
				$mid = $tt["MemberID"];
				if($f==0){
					dbInsert($TMembersClicks, "ClickID, TermID, ClickDate, SearchTerm", "null, '$tid', CURDATE(), '".addslashes($ss)."'");
					dbUpdate($TMembersBalance, "Balance=Balance-$bid", "MemberID=$mid");
				}
			}
		}else{
			//no matches click
			$T = "$TNoMatchesBids";
			$F = "MemberID, Link, Bid";
			$Q = "MemberID=$tid";
			if($tt = dbSelect($T, $F, $Q)){
				$location = stripslashes($tt["Link"]);
				$bid = $tt["Bid"];
				$mid = $tt["MemberID"];
				if($f==0){
					dbInsert($TMembersNMClicks, "ClickID, MemberID, ClickDate, SearchTerm", "null, $mid, CURDATE(), '".addslashes($ss)."'");
					dbUpdate($TMembersBalance, "Balance=Balance-$bid", "MemberID=$mid");
				}
			}
		}
	}
	$minbal = GetSettings("minbal");
	if($tt3 = dbSelect($TMembersBalance, "Balance", "MemberID=".$tt["MemberID"])){
		if($tt3["Balance"]<=$minbid){
			dbUpdate($TMembersAccounts, "STATUS=1", "MemberID=".$tt["MemberID"]);
			if($tt5 = dbSelect($TMembersInfo, "EMail", "MemberID=".$tt["MemberID"])){
				SendMessage(stripslashes($tt5["EMail"]), 2);
			}
		}
	}
	if(session_is_registered("amember")){
		if($amember!=""&&$amember!=0){
			if($f==0){
				dbInsert($TAClicks, "MemberID, ACDate", "'$amember', CURDATE()");
			}
		}
	}
	mysql_close();
	if($location!=""){
		if(!preg_match("/^http:/i",$location)){
			$location = "http://".$location;
		}
		header("Location: $location");
		exit;
	}else{
		print "<h1>Error</h1>";
	}
?>