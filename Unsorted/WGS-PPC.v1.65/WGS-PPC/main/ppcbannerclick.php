<?
	require("path.php");
	require($INC."config/config_main.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	$location = "";
	if(isset($bid)&&$bid!=""&&$bid!=0){
		dbInsert($TBannersClicks, "ClickID, BannerID, ClickDate", "null, $bid, CURDATE()");
		if($tt = dbSelect($TBanners, "MemberID, Link", "BannerID=$bid")){
			$location  = stripslashes($tt["Link"]);
			$mid = $tt["MemberID"];
			if(isset($mode)&&$mode!=0&&$mode!=""){
				if($tt3 = dbSelect($TMembersSites, "Link", "MemberID=$mid")){
					$location = stripslashes($tt3["Link"]);
				}
				if($tt2 = dbSelect($TBannersBids, "ClickBid", "BannerID=$bid")){
					$bid = $tt2["ClickBid"];
					dbUpdate($TMembersBalance, "Balance=Balance-$bid", "MemberID=$mid");
					$minbal = GetSettings("minbal");
					if($tt3 = dbSelect($TMembersBalance, "Balance", "MemberID=".$mid)){
						if($tt3["Balance"]<=$minbid){
							dbUpdate($TMembersAccounts, "STATUS=1", "MemberID=".$mid);
						}
					}
				}
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