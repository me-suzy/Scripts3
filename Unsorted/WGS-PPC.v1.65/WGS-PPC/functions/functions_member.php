<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Members functions              //
	//                                 //
	/////////////////////////////////////
	function MemberCPMain($id){
		global $MEMBER_CP_MAIN_TMP;
		global $TMembersAccounts, $TMembersBalance, $TMembersClicks, $TMembersTerms, $TMembersBids, $TAdminMembersSettings;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_MAIN_TMP);
		if($tt = dbSelect($TMembersAccounts, "CreateDate", "MemberID=$id")) $date = $tt["CreateDate"];
		else $date = "unknown";
		if($tt = dbSelect($TMembersBalance, "Balance", "MemberID=$id")) $balance = $tt["Balance"];
		else $balance = "0";
		$T = "$TMembersClicks, $TMembersTerms, $TMembersBids";
		$F = "AVG($TMembersBids.Bid)";
		$C = "$TMembersTerms.MemberID=$id and ";
		$C.= "$TMembersClicks.TermID=$TMembersTerms.TermID and ";
		$C.= "$TMembersBids.TermID=$TMembersTerms.TermID group by $TMembersClicks.ClickDate";
		if($tt = dbSelect($T, $F, $C)) $acost = $tt["AVG($TMembersBids.Bid)"];
		else $acost = 0;
		if($tt = dbSelect($TAdminMembersSettings, "Porog")) $porog = $tt["Porog"];
		else $porog = 0;
		if($balance<=$porog) $warning = "Update Your balance or Your Account will be deleted in 5 days";
		else $warning = "";
		if($tt = dbSelect($TMembersAccounts, "STATUS", "MemberID=$id")){
			if($tt["STATUS"]!=0) $warning = "<p style='color:#ff0000'>Your Account was blocked! Update Your Balance!</p>";
		}
		$vars = array(
			"date"=>date2($date),
			"balance"=>($balance/100),
			"average_cost"=>($acost/100),
			"currency"=>GetSettings("currency"),
			"warning"=>$warning
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPModifyAccount($id, $error = ""){
		global $MEMBER_CP_MODIFY_TMP;
		global $TMembersInfo, $TMembersSites, $TMembersLogos;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_MODIFY_TMP);
		if($tt = dbSelect($TMembersInfo, "*", "MemberID=$id")){
			$name = stripslashes($tt["Name"]);
			$company = stripslashes($tt["Company"]);
			$email = stripslashes($tt["EMail"]);
			$street = stripslashes($tt["Street"]);
			$city = stripslashes($tt["City"]);
			$zip = stripslashes($tt["Zip"]);
			$country = stripslashes($tt["Country"]);
			$phone = stripslashes($tt["Phone"]);
			$state = stripslashes($tt["State"]);
		}else{
			$name = "";
			$company = "";
			$email = "";
			$street = "";
			$city = "";
			$zip = "";
			$country = "";
			$phone = "";
			$state = "";
		}
		if($tt = dbSelect($TMembersSites, "*", "MemberID=$id")){
			$title = stripslashes($tt["Title"]);
			$category = $tt["CategoryID"];
			$descr = stripslashes($tt["Descr"]);
			$url = stripslashes($tt["Link"]);
		}else{
			$title = "";
			$category = 0;
			$descr = "";
			$url = "";
		}
		$logo = GetLogo($id);
		$vars = array(
			"logo"=>$logo,
			"error"=>$error, 
			"name"=>$name, 
			"company"=>$company, 
			"email"=>$email, 
			"street"=>$street, 
			"city"=>$city, 
			"state"=>$state, 
			"zip"=>$zip, 
			"country"=>$country, 
			"phone"=>$phone,
			"title"=>$title,
			"descr"=>$descr,
			"url"=>$url,
			"categories"=>GetCategoriesSelectList($category)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetListings($id, $page){
		global $MEMBER_CP_LIST_PART_TMP, $MEMBER_CP_LIST_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TMembersSites, $TMembersTerms, $TMembersBids;
		$IPP = $DEFAULT_IPP;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_LIST_PART_TMP);
		$T = "$TMembersTerms, $TMembersBids";
		$F = "$TMembersTerms.TermID, $TMembersTerms.Term, $TMembersTerms.Title, $TMembersTerms.Descr, $TMembersTerms.Link, $TMembersBids.Bid";
		$Q = "$TMembersTerms.MemberID=$id and $TMembersBids.TermID=$TMembersTerms.TermID";
		$O = "$TMembersTerms.Term, $TMembersTerms.Title, $TMembersBids.Bid";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			$count = 1;
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"id"=>$tt["TermID"],
					"count"=>$count,
					"term"=>stripslashes($tt["Term"]),
					"listing"=>"<b>".stripslashes($tt["Title"])."</b><br>".stripslashes($tt["Descr"])."<br><i>".stripslashes($tt["Link"])."</i>",
					"bid"=>($tt["Bid"]/100),
					"edit"=>$MEMBER_CP_URL."?menu=256&id=".$tt["TermID"]
				);
				$OUT.=ParseTemplate($tmp, $vars);
				$count++;
			}
		}else{
			$OUT = ReadTemplate($MEMBER_CP_LIST_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function MemberCPManageListings($id, $page, $wb = ""){
		global $MEMBER_CP_LIST_TMP, $MEMBER_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_LIST_TMP);
		$vars = array(
			"wb"=>urldecode($wb),
			"nores"=>$MEMBER_CP_URL."?menu=258",
			"addnew"=>$MEMBER_CP_URL."?menu=255",
			"addbulk"=>$MEMBER_CP_URL."?menu=2551",
			"currency"=>GetSettings("currency"),
			"listings"=>GetListings($id, $page)
		);
		return ParseTemplate($tmp, $vars);
	}

//ADD TERMS
	
	function MemberCPAddBulk($id, $error){
		global $MEMBER_CP_ADDBULK_TMP, $TAdminSettings, $TMembersSites;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_ADDBULK_TMP);
		if($tt = dbSelect($TAdminSettings)){
			$minbid = ($tt["MinBid"]/100);
		}else{
			$minbid = "0.00";
		}
		if($tt = dbSelect($TMembersSites, "*", "MemberID=$id")){
			$title = stripslashes($tt["Title"]);
			$descr = stripslashes($tt["Descr"]);
			$url = stripslashes($tt["Link"]);
		}else{
			$title = "";
			$descr = "";
			$url = "";
		}
		$vars = array(
			"error"=>$error,
			"term"=>$term,
			"title"=>$title,
			"descr"=>$descr,
			"url"=>$url,
			"currency"=>GetSettings("currency"),
			"bid"=>$minbid
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPAddTerm($id, $term, $bid, $error = ""){
		global $MEMBER_CP_ADDTERM_TMP, $TAdminSettings, $TMembersSites;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_ADDTERM_TMP);
		if($tt = dbSelect($TAdminSettings)){
			$minbid = ($tt["MinBid"]/100);
		}else{
			$minbid = "0.00";
		}
		if($bid!=""&&preg_match("/^\d+\.\d\d$/", $bid)&&$bid>=$minbid){
			$minbid = $bid;
		}
		if($tt = dbSelect($TMembersSites, "*", "MemberID=$id")){
			$title = stripslashes($tt["Title"]);
			$descr = stripslashes($tt["Descr"]);
			$url = stripslashes($tt["Link"]);
		}else{
			$title = "";
			$descr = "";
			$url = "";
		}
		$vars = array(
			"error"=>$error,
			"term"=>$term,
			"title"=>$title,
			"descr"=>$descr,
			"url"=>$url,
			"currency"=>GetSettings("currency"),
			"bid"=>$minbid
		);
		return ParseTemplate($tmp, $vars);
	}
	
	
	function MemberCPEditTerm($id, $error){
		global $MEMBER_CP_EDITTERM_TMP, $TMembersTerms, $TMembersBids;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_EDITTERM_TMP);
		if(dbSelectCount($TMembersTerms, "TermID=$id")){
			$term = "";
			$title = "";
			$descr = "";
			$url = "";
			$bid = 0;
			if($tt = dbSelect($TMembersTerms.",".$TMembersBids, "$TMembersTerms.Term,$TMembersTerms.MemberID, $TMembersTerms.Title, $TMembersTerms.Descr, $TMembersTerms.LLogoURL, $TMembersTerms.Link, $TMembersBids.Bid", "$TMembersTerms.TermID=$id and $TMembersBids.TermID=$TMembersTerms.TermID")){
				$term = stripslashes($tt["Term"]);
				$title = stripslashes($tt["Title"]);
				$descr = stripslashes($tt["Descr"]);
				$url = stripslashes($tt["Link"]);
				$bid = ($tt["Bid"]/100);
				$ll = $tt["LLogoURL"];
			}
		}else $error = "Listing ID is incorrect";
		$vars = array(
			"logo"=>($ll==""?GetLogo($tt["MemberID"]):GetLogo($id, 1)),
			"error"=>$error,
			"term"=>$term,
			"title"=>$title,
			"descr"=>$descr,
			"url"=>$url,
			"id"=>$id,
			"bid"=>$bid
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPUpdateBalance($id, $mess = ""){
		global $MEMBER_CP_BALANCE_TMP, $TMembersBalance, $TMembersCC, $MEMBERS_CP_URL, $SITE_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_BALANCE_TMP);
		if($tt = dbSelect($TMembersBalance.",".$TMembersCC, "CURDATE(), $TMembersBalance.Balance, $TMembersCC.CCExpires", "$TMembersBalance.MemberID=$id and $TMembersCC.MemberID=$id")){
			$balance = ($tt["Balance"]/100);
			$exp = $tt["CCExpires"];
			$cd = $tt["CURDATE()"];
		}else{
			$balance = 0;
			$exp = "0000-00-00";
		}
		$pp = dbSelect($TMembersCC, "PayPalAccount", "MemberID=$id");
		if($pp["PayPalAccount"]==""){
			if($cd>$exp) $warning = "Warning! Your Credit card has expired!";
			else $warning = "";
		}
		$vars = array(
			"warning"=>$warning,
			"balance"=>$balance,
			"currency"=>GetSettings("currency"),
			"message"=>$mess,
			"ccinfo"=>$MEMBERS_CP_URL."?menu=257",
			"getmoney"=>$MEMBERS_CP_URL."?menu=4002"
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPEditCC($id, $error){
		global $MEMBER_CP_EDITCC_TMP, $TMembersCC;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_EDITCC_TMP);
		if($tt = dbSelect($TMembersCC, "PayPalAccount, CCName, CCNumber, CCExpires", "MemberID=$id")){
			$name = stripslashes($tt["CCName"]);
			$expires = $tt["CCExpires"];
			$nn = stripslashes($tt["CCNumber"]);
			$ppa = stripslashes($tt["PayPalAccount"]);
			$rl = abs(strlen($nn)-6);
			$rstr = str_repeat("*", $rl);
			$current_ccn = substr_replace($nn, $rstr, 3, $rl);
		}
		$vars = array(
			"error"=>$error,
			"name"=>$name,
			"ccn"=>"",
			"expires"=>$expires,
			"current_ccn"=>$current_ccn,
			"ppa"=>$ppa
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetBidPosition($member_id, $bid){
		global $TMembersTerms, $TMembersBids;
		$out = "";
		$T = "$TMembersBids,$TMembersTerms";
		$F = "distinct $TMembersBids.Bid";
		$Q = "$TMembersTerms.MemberID=$member_id and $TMembersBids.TermID=$TMembersTerms.TermID";
		if($res = dbSelectAll($T, $F, $Q, "$TMembersBids.Bid")){
			$bids = array();
			while($tt = mysql_fetch_array($res)){
				array_push($bids, $tt["Bid"]);
			}
			arsort($bids);
			$co = count($bids);
			$pos = 0;
			for($i = 0; $i<=$co; $i++){
				if($bids[$i]==$bid){
//					print "<h3>".$bids[$i]."</h3>";
					$pos = $co - $i;
					break;
				}
			}
			$out = $pos;
		}
		return $out;
	}
	
	function GetBB1($member_id, $bid){
		global $TMembersTerms, $TMembersBids;
		$out = "0.00";
		$T = "$TMembersBids,$TMembersTerms";
		$F = "max($TMembersBids.Bid)";
		$Q = "$TMembersTerms.MemberID=$member_id and $TMembersBids.TermID=$TMembersTerms.TermID";
		if($tt = dbSelect($T, $F, $Q)){
			if($bid<$tt["max($TMembersBids.Bid)"]){
				$out = ($tt["max($TMembersBids.Bid)"] - $bid)/100;
			}
		}
		return $out;
	}
	
	function GetBidPop($id, $term_id){
		global $TMembersTerms, $TMembersClicks;
		$OUT = "";
		$total_clicks = 0;
		if($count = dbSelectCount("$TMembersTerms, $TMembersClicks", "$TMembersTerms.MemberID=$id and $TMembersClicks.TermID=$TMembersTerms.TermID")){
			$total_clicks = $count;
		}
		if($res = dbSelectAll("$TMembersTerms, $TMembersClicks", "$TMembersTerms.TermID, count($TMembersClicks.ClickID)", "$TMembersTerms.MemberID=$id and $TMembersClicks.TermID=$TMembersTerms.TermID group by $TMembersClicks.TermID")){
			$terms_clicks = array();
			while($tt = mysql_fetch_array($res)){
				$terms_clicks[$tt["TermID"]] = $tt["count($TMembersClicks.ClickID)"];
//				print $tt["TermID"]." - ".$terms_clicks[$tt["TermID"]]."<br>";
			}
			$minid = 0;
			$mincl = 65535;
			foreach($terms_clicks as $tid=>$cl){
				if($cl<=$mincl){
					$mincl = $cl;
					$minid = $tid;
				}
			}
			$tp = 100;
			$out_a = array();
			foreach($terms_clicks as $tid=>$cl){
				if($tid!=$minid){
					$pp = (int)(($cl/$total_clicks)*100);
					$tp = $tp - $pp;
					$out_a[$tid] = $pp;
				}
			}
			$out_a[$minid] = $tp;
			if($out_a[$term_id]!=""&&$out_a[$term_id]!=0) $OUT = $out_a[$term_id]."%";
		}
		return $OUT;
	}
	
	function GetBidsList($id){
		global $MEMBER_CP_UPDATEBIDS_PART_TMP, $MEMBER_CP_UPDATEBIDS_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TMembersTerms, $TMembersBids;
		$IPP = $DEFAULT_IPP;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_UPDATEBIDS_PART_TMP);
		$T = "$TMembersTerms, $TMembersBids";
		$F = "$TMembersTerms.TermID, $TMembersTerms.Term, $TMembersBids.Bid";
		$Q = "$TMembersTerms.MemberID=$id and $TMembersBids.TermID=$TMembersTerms.TermID";
		$O = "$TMembersBids.Bid desc, $TMembersTerms.Term";
		if($res = dbSelectAll($T, $F, $Q, $O, $page*$IPP.",".$IPP)){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"id"=>$tt["TermID"],
					"pos"=>GetBidPosition($id, $tt["Bid"]),
					"pop"=>GetBidPop($id, $tt["TermID"]),
					"bb1"=>GetBB1($id, $tt["Bid"]),
					"term"=>$tt["Term"],
					"bid"=>($tt["Bid"]/100)
				);
				$tmp2 = ParseTemplate($tmp, $vars);
				$OUT.=$tmp2;
			}
		}else{
			$OUT = ReadTemplate($MEMBER_CP_UPDATEBIDS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function MemberCPUpdateBids($id){
		global $MEMBER_CP_UPDATEBIDS_TMP, $MEMBERS_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_UPDATEBIDS_TMP);
		$vars = array(
			"allto1"=>$MEMBERS_CP_URL."?cmd=6",
			"alltomin"=>$MEMBERS_CP_URL."?cmd=8",
			"alltoavg"=>$MEMBERS_CP_URL."?cmd=9",
			"currency"=>GetSettings("currency"),
			"bids"=>GetBidsList($id)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetStatistics($member_id, $sdate, $edate, $sort, $d){
		global $MEMBER_CP_STATS_PART_TMP, $MEMBER_CP_STATS_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TMembersTerms, $TMembersBids, $TMembersClicks;
		$tmp = ReadTemplate($MEMBER_CP_STATS_PART_TMP);
		$OUT = "";
		$T = "$TMembersClicks, $TMembersTerms, $TMembersBids";
		$F = "$TMembersClicks.TermID, count($TMembersClicks.ClickID), $TMembersTerms.Term, $TMembersBids.Bid, UNIX_TIMESTAMP($TMembersClicks.ClickDate)";
		$Q = "$TMembersTerms.MemberID=$member_id and $TMembersClicks.TermID=$TMembersTerms.TermID and $TMembersBids.TermID=$TMembersTerms.TermID and (UNIX_TIMESTAMP($TMembersClicks.ClickDate)>=UNIX_TIMESTAMP('$sdate') and UNIX_TIMESTAMP('$edate')>=UNIX_TIMESTAMP($TMembersClicks.ClickDate)) group by $TMembersClicks.TermID";
		$O = "$TMembersTerms.Term";
//		print "select $F from $T where $Q order by $O<hr>";
		$total_clicks = 0;
		$total_cost = 0;
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"term"=>($tt["TermID"]!=0?"<a href='".$MEMBERS_CP_URL."?menu=256&id=".$tt["TermID"]."'>".$tt["Term"]."</a>":"<a href='".$MEMBERS_CP_URL."?menu=258'>&quot;no matches&quot;</a>"),
					"clicks"=>$tt["count($TMembersClicks.ClickID)"],
					"pop"=>GetBidPop($member_id, $tt["TermID"]),
					"cost"=>($tt["count($TMembersClicks.ClickID)"]*$tt["Bid"])/100
				);
				$total_clicks+=$tt["count($TMembersClicks.ClickID)"];
				$total_cost+=($tt["count($TMembersClicks.ClickID)"]*$tt["Bid"])/100;
				$tmp2 = ParseTemplate($tmp, $vars);
				$OUT.=$tmp2;
			}
			$vars = array(
				"term"=>"<b>Total:</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"pop"=>"<b>&nbsp;</b>",
				"cost"=>"<b>".$total_cost."</b>"
			);
			$tmp2 = ParseTemplate($tmp, $vars);
			$OUT.=$tmp2;
		}else{
			$OUT = ReadTemplate($MEMBER_CP_STATS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function dFrom($member_id, $sdate, $edate){
		global $TMembersTerms, $TMembersClicks, $TMembersAccounts;
		$OUT = "<select name=\"sdate\">";
		$T = "$TMembersClicks, $TMembersTerms";
		$F = "distinct $TMembersClicks.ClickDate";
		$Q = "$TMembersTerms.MemberID=$member_id and $TMembersClicks.TermID=$TMembersTerms.TermID";
		$O = "$TMembersClicks.ClickDate desc";
		$qq = dbSelect($TMembersAccounts,"CreateDate","MemberID=$member_id");
		$now = $qq["CreateDate"];
		$OUT.= "<option value=\"".$now."\">".date2($now)."</option>";
//		print "select $F from $T where $Q";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				if($tt["ClickDate"]==$sdate) $sel = "selected"; $sel = "";
				if($tt["ClickDate"]!=$now) $OUT.= "<option value=\"".$tt["ClickDate"]."\" $sel>".date2($tt["ClickDate"])."</option>";
			}
		}
		$OUT.= "</select>";
		return $OUT;
	}
	
	function dTo($member_id, $sdate, $edate){
		global $TMembersTerms, $TMembersClicks;
		global $TMembersTerms, $TMembersClicks;
		$OUT = "<select name=\"edate\">";
		$T = "$TMembersClicks, $TMembersTerms";
		$F = "distinct $TMembersClicks.ClickDate";
		$Q = "$TMembersTerms.MemberID=$member_id and $TMembersClicks.TermID=$TMembersTerms.TermID";
		$O = "$TMembersClicks.ClickDate desc";
		$now = date("Y-m-d");
		$OUT.= "<option value=\"".$now."\">".date2($now)."</option>";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				if($tt["ClickDate"]==$edate) $sel = "selected"; $sel = "";
				if($tt["ClickDate"]!=$now) $OUT.= "<option value=\"".$tt["ClickDate"]."\" $sel>".date2($tt["ClickDate"])."</option>";
			}
		}
		$OUT.= "</select>";
		return $OUT;
	}

	
	function MemberCPStatistics($member_id, $sdate, $edate, $sort, $d){
		global $MEMBER_CP_STATS_TMP, $MEMBERS_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_STATS_TMP);
		if($d==0) $d=1; else $d=0;
		$vars = array(
			"action"=>$MEMBERS_CP_URL,
			"d"=>$d,
			"d_from"=>dFrom($member_id, $sdate, $edate),
			"d_to"=>dTo($member_id, $sdate, $edate),
			"sdate"=>date2($sdate),
			"edate"=>date2($edate),
			"sort"=>$sort,
			"currency"=>GetSettings("currency"),
			"stats"=>GetStatistics($member_id, $sdate, $edate, $sort, $d)
		);
		return ParseTemplate($tmp, $vars);
	}
	
//BANNERS
	function GetBanners($id){
		global $MEMBER_CP_BANNERS_PART_TMP, $MEMBER_CP_BANNERS_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TBanners, $TBannersBids;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_BANNERS_PART_TMP);
		$T = "$TBanners, $TBannersBids";
		$F = "$TBanners.BannerID, $TBanners.STATUS, $TBannersBids.ShowBid, $TBannersBids.ClickBid";
		$Q = "$TBanners.MemberID=$id and $TBannersBids.BannerID=$TBanners.BannerID";
		$O = "$TBannersBids.ShowBid desc, $TBannersBids.ClickBid desc";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			$count = 1;
			while($tt = mysql_fetch_array($res)){
				$status = "active";
				if($tt["STATUS"]!=0) $status = "inactive";
				$vars = array(
					"id"=>$tt["BannerID"],
					"count"=>$count,
					"status"=>$status,
					"banner"=>GetBanner($tt["BannerID"],1),
					"sbid"=>($tt["ShowBid"]/100),
					"cbid"=>($tt["ClickBid"]/100),
					"edit"=>$MEMBER_CP_URL."?menu=356&id=".$tt["BannerID"]
				);
				$OUT.=ParseTemplate($tmp, $vars);
				$count++;
			}
		}else{
			$OUT = ReadTemplate($MEMBER_CP_BANNERS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function MemberCPBanners($id){
		global $MEMBER_CP_BANNERS_TMP, $MEMBER_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_BANNERS_TMP);
		$vars = array(
			"addnew"=>$MEMBER_CP_URL."?menu=355",
			"currency"=>GetSettings("currency"),
			"banners"=>GetBanners($id)
		);
		return ParseTemplate($tmp, $vars);
	}

	function MemberCPAddBanner($member_id, $error=""){
		global $MEMBER_CP_ADDBANNER_TMP, $TAdminSettings, $TMembersTerms;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_ADDBANNER_TMP);
		if($tt = dbSelect($TAdminSettings)){
			$minbid = ($tt["MinBid"]/100);
		}else{
			$minbid = "0.00";
		}
		$terms = "";
		if($res = dbSelectAll($TMembersTerms, "TermID, Term", "MemberID=$member_id")){
			$terms = "<table cellspacing=\"0\" cellpadding=\"3\">\n";
			$records = mysql_num_rows($res);
			$rows = ceil($records/3);
			for($i = 1; $i<=$rows; $i++){
				$terms.="<tr>\n";
				for($j = 1; $j<=3; $j++){
					$tt = mysql_fetch_array($res);
					if($tt["TermID"]!="") $terms.= "<td><input type=\"checkbox\" name=\"term".$tt["TermID"]."\" value=\"".$tt["TermID"]."\">&nbsp;".$tt["Term"]."</td>\n";
				}
				$terms.="</tr>\n";
			}
			$terms.="</table>\n";
		}
		$vars = array(
			"selectterms"=>$terms,
			"error"=>$error,
			"currency"=>GetSettings("currency"),
			"sbid"=>$minbid,
			"cbid"=>$minbid
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPEditBanner($id, $error){
		global $MEMBER_CP_EDITBANNER_TMP, $TBanners, $TBannersBids, $TBannersTerms, $TMembersTerms;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_EDITBANNER_TMP);
		if(dbSelectCount($TBanners, "BannerID=$id")){
			$banner = "";
			$alt = "";
			$sbid = 0;
			$cbid = 0;
			if($tt = dbSelect($TBanners.",".$TBannersBids, "$TBanners.BannerAlt, $TBannersBids.ShowBid, $TBannersBids.ClickBid", "$TBanners.BannerID=$id and $TBannersBids.BannerID=$TBanners.BannerID")){
				$banner = GetBanner($id);
				$alt = stripslashes($tt["BannerAlt"]);
				$sbid = ($tt["ShowBid"]/100);
				$cbid = ($tt["ClickBid"]/100);
			}
		}else $error = "Banner ID is incorrect";
		$terms = "";
		$member_id = 0;
		if($tt = dbSelect($TBanners, "MemberID", "BannerID=$id")){
			$member_id = $tt["MemberID"];
		}
		if($res = dbSelectAll($TMembersTerms, "TermID, Term", "MemberID=$member_id")){
			$terms = "<table cellspacing=\"0\" cellpadding=\"3\">\n";
			$records = mysql_num_rows($res);
			$rows = ceil($records/3);
			for($i = 1; $i<=$rows; $i++){
				$terms.="<tr>\n";
				for($j = 1; $j<=3; $j++){
					$tt = mysql_fetch_array($res);
					if($tt["TermID"]!=""){
						if(dbSelectCount($TBannersTerms, "BannerID=$id and TermID=".$tt["TermID"])>0){
							$checked = "checked";
						}else{
							$checked = "";
						}
						$terms.= "<td><input type=\"checkbox\" name=\"term".$tt["TermID"]."\" value=\"".$tt["TermID"]."\" $checked>&nbsp;".$tt["Term"]."</td>\n";
					}
				}
				$terms.="</tr>\n";
			}
			$terms.="</table>\n";
		}
//		print htmlspecialchars($terms)."<hr>";
		$vars = array(
			"selectterms"=>$terms,
			"error"=>$error,
			"banner"=>$banner,
			"alt"=>$alt,
			"sbid"=>$sbid,
			"cbid"=>$cbid,
			"bannerid"=>$id,
			"currency"=>GetSettings("currency")
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetBannersStatistics($member_id){
		global $MEMBER_CP_BANNERSSTATS_PART_TMP, $MEMBER_CP_BANNERSSTATS_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TBanners, $TBannersBids, $TBannersClicks, $TBannersShows;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_BANNERSSTATS_PART_TMP);
		if($member_id=="") $member_id=0;
		$T = "$TBanners";
		$F = "BannerID";
		$Q = "MemberID=$member_id";
		$O = "BannerID";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			$total_clicks = 0; $total_shows = 0; $total_cost = 0;
			while($tt = mysql_fetch_array($res)){
				$scount = dbSelectCount($TBannersShows, "BannerID=".$tt["BannerID"]);
				$ccount = dbSelectCount($TBannersClicks, "BannerID=".$tt["BannerID"]);
				$tt2 = dbSelect($TBannersBids, "ShowBid, ClickBid", "BannerID=".$tt["BannerID"]);
				$cost = ($tt2["ShowBid"]*$scount + $tt2["ClickBid"]*$ccount);
				$total_clicks+= $ccount;
				$total_shows+= $scount;
				$total_cost+= $cost;
				$vars = array(
					"banner"=>GetBanner($tt["BannerID"],0,1),
					"shows"=>$scount,	
					"clicks"=>$ccount,
					"cost"=>$cost/100
				);
				$OUT.=ParseTemplate($tmp, $vars);
			}
			$vars = array(
				"banner"=>"<b>Total:</b>",
				"shows"=>"<b>".$total_shows."</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"cost"=>"<b>".($total_cost/100)."</b>"
			);
			$OUT.=ParseTemplate($tmp, $vars);
		}else{
			$OUT = ReadTemplate($MEMBER_CP_BANNERSSTATS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function MemberCPBannersStat($member_id){
		global $MEMBER_CP_BANNERSSTATS_TMP, $MEMBERS_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_BANNERSSTATS_TMP);
		$vars = array(
			"currency"=>GetSettings("currency"),
			"stats"=>GetBannersStatistics($member_id)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function MemberCPNoMatches($id, $error = ""){
		global $MEMBER_CP_NOMATCHESBID_TMP, $MEMBER_CP_NOMATCHESBIDEDIT_TMP, $TMembersSites, $TNoMatchesBids;
		$tmp = "";
		$template = $MEMBER_CP_NOMATCHESBID_TMP;
		$table = $TMembersSites;
		$minbid = GetSettings("nmbid");
		if(dbSelectCount($TNoMatchesBids, "MemberID=$id")>0){
			$template = $MEMBER_CP_NOMATCHESBIDEDIT_TMP;
			$table = $TNoMatchesBids;
		}
		$tmp = ReadTemplate($template);
		if($tt = dbSelect($table, "*", "MemberID=$id")){
			$title = stripslashes($tt["Title"]);
			$descr = stripslashes($tt["Descr"]);
			$url = stripslashes($tt["Link"]);
			if($table==$TNoMatchesBids) $minbid = $tt["Bid"];
		}else{
			$title = "";
			$descr = "";
			$url = "";
		}
		$vars = array(
			"error"=>$error,
			"title"=>$title,
			"descr"=>$descr,
			"url"=>$url,
			"currency"=>GetSettings("currency"),
			"bid"=>$minbid/100
		);
		return ParseTemplate($tmp, $vars);
	}

	function GetNMStatistics($member_id){
		global $MEMBER_CP_NMSTATS_PART_TMP, $MEMBER_CP_NMSTATS_PART_EMPTY_TMP, $MEMBER_CP_URL, $DEFAULT_IPP;
		global $TNoMatchesBids, $TMembersNMClicks;
		$tmp = ReadTemplate($MEMBER_CP_NMSTATS_PART_TMP);
		$OUT = "";
		$T = "$TNoMatchesBids LEFT JOIN $TMembersNMClicks USING(MemberID)";
		$F = "$TMembersNMClicks.SearchTerm, $TNoMatchesBids.Bid, count($TMembersNMClicks.ClickID)";
		$Q = "$TNoMatchesBids.MemberID=$member_id group by $TMembersNMClicks.SearchTerm";
		$O = "$TMembersNMClicks.SearchTerm";
//		print "select $F from $T where $Q order by $O<hr>";
		$total_clicks = 0;
		$total_cost = 0;
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"term"=>$tt["SearchTerm"],
					"clicks"=>$tt["count($TMembersNMClicks.ClickID)"],
					"cost"=>($tt["count($TMembersNMClicks.ClickID)"]*$tt["Bid"])/100
				);
				$total_clicks+=$tt["count($TMembersNMClicks.ClickID)"];
				$total_cost+=($tt["count($TMembersNMClicks.ClickID)"]*$tt["Bid"])/100;
				$tmp2 = ParseTemplate($tmp, $vars);
				$OUT.=$tmp2;
			}
			$vars = array(
				"term"=>"<b>Total:</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"cost"=>"<b>".$total_cost."</b>"
			);
			$tmp2 = ParseTemplate($tmp, $vars);
			$OUT.=$tmp2;
		}else{
			$OUT = ReadTemplate($MEMBER_CP_NMSTATS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function MemberCPNoMatchesStatistics($member_id){
		global $MEMBER_CP_NMSTATS_TMP, $MEMBERS_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($MEMBER_CP_NMSTATS_TMP);
		$vars = array(
			"action"=>$MEMBERS_CP_URL,
			"currency"=>GetSettings("currency"),
			"stats"=>GetNMStatistics($member_id)
		);
		return ParseTemplate($tmp, $vars);
	}
	
//SPECIAL SEARCH FOR BIDS	
	
	function MemberCPSSearch(){
		global $MEMBER_CP_SSEARCH_TMP;
		$tmp = ReadTemplate($MEMBER_CP_SSEARCH_TMP);
		$vars = array("term"=>"");
		return ParseTemplate($tmp, $vars);
	}
	
	function GetMemberCPSSResults($term){
		global $MEMBER_CP_SSEARCH_RESULTS_PARTS_TMP, $MEMBER_CP_SSEARCH_RESULTS_PARTS_EMPTY_TMP, $MEMBER_CP_URL;
		global $TMembersTerms, $TMembersBids;
		$T = "$TMembersTerms, $TMembersBids";
		$F = "min($TMembersBids.Bid) minbid, avg($TMembersBids.Bid) avgbid, max($TMembersBids.Bid) maxbid";
		$Q = "$TMembersTerms.Term='$term' and $TMembersBids.TermID=$TMembersTerms.TermID";
		$Nobids = 0;
		$OUT = "";
		if($tt = dbSelect($T, $F, $Q)){
			$tmp = ReadTemplate($MEMBER_CP_SSEARCH_RESULTS_PARTS_TMP);
			$minbid = $tt["minbid"]/100;
			$avgbid = round($tt["avgbid"]/100,2);
			$maxbid = $tt["maxbid"]/100;
			$vars = array(
				"minbid"=>$minbid,
				"avgbid"=>$avgbid,
				"maxbid"=>$maxbid
			);
			if($tt["minbid"]==0&&$tt["avgbid"]==0&&$tt["maxbid"]==0){
				$Nobids = 1;
			}else{
				$OUT = ParseTemplate($tmp, $vars);
				$vars = array(
					"minbid"=>"<a href=\"".$MEMBER_CP_URL."?menu=255&bid=".$minbid."&term=".urlencode($term)."\">Add term with minimal bid</a>",
					"avgbid"=>"<a href=\"".$MEMBER_CP_URL."?menu=255&bid=".$avgbid."&term=".urlencode($term)."\">Add term with average bid</a>",
					"maxbid"=>"<a href=\"".$MEMBER_CP_URL."?menu=255&bid=".$maxbid."&term=".urlencode($term)."\">Add term with maximal bid</a>"
				);
				$OUT.= ParseTemplate($tmp, $vars);
			}
		}else{
			$Nobids = 1;
		}
		if($Nobids==1){
			$tmp = ReadTemplate($MEMBER_CP_SSEARCH_RESULTS_PARTS_EMPTY_TMP);
			$vars = array("temp"=>"");
			$OUT = ParseTemplate($tmp, $vars);
		}
		return $OUT;
	}
	
	function MemberCPSSearchResults($term){
		global $MEMBER_CP_SSEARCH_RESULTS_TMP;
		$tmp = ReadTemplate($MEMBER_CP_SSEARCH_RESULTS_TMP);
		$vars = array(
			"term"=>$term,
			"results"=>GetMemberCPSSResults($term)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AffGetTotalMoney($id){
		global $TASearches, $TAClicks;
		$sc = GetSettings("affsearch");
		$cc = GetSettings("affclicks");
		$sn = dbSelectCount($TASearches, "MemberID=$id");
		$cn = dbSelectCount($TAClicks, "MemberID=$id");
		return ($sn*$sc+$cn*$cc)/100;
	}
	
	function ADFrom($sdate){
		global $TASearches, $TAClicks;
		$OUT = "<select name='sdate'>";
		$today = date("Y-m-d");
		$OUT.= "<option value='".$today."'>".date2($today)."</option>";
		$dates = array();
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TASearches.ASDate", "$TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ASDate"])) array_push($dates, $tt["ASDate"]);
			}
		}
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TAClicks.ACDate", "$TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ACDate"])) array_push($dates, $tt["ACDate"]);
			}
		}
		asort($dates);
		foreach($dates as $dd){
			if($dd!=$sdate) $sel = "";
			else $sel = "selected";
			$OUT.= "<option value='".$dd."' $sel>".date2($dd)."</option>";
		}
		return $OUT."</select>";
	}
	
	function ADTo($edate){
		global $TASearches, $TAClicks;
		$OUT = "<select name='edate'>";
		$today = date("Y-m-d");
		$OUT.= "<option value='".$today."'>".date2($today)."</option>";
		$dates = array();
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TASearches.ASDate", "$TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ASDate"])) array_push($dates, $tt["ASDate"]);
			}
		}
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TAClicks.ACDate", "$TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ACDate"])) array_push($dates, $tt["ACDate"]);
			}
		}
		asort($dates);
		foreach($dates as $dd){
			if($dd!=$edate) $sel = "";
			else $sel = "selected";
			$OUT.= "<option value='".$dd."' $sel>".date2($dd)."</option>";
		}
		return $OUT."</select>";
	}
	
	function GetAStats($id, $sdate, $edate, $mode = 0){
		global $TASearches, $TAClicks, $MEMBER_CP_AFFILIATE_PART_TMP, $AFF_REPORT_TMP;
		$OUT = "";
		$tmp = ReadTemplate(($mode==0?$MEMBER_CP_AFFILIATE_PART_TMP:$AFF_REPORT_TMP));
		$dates = array();
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TASearches.ASDate", "(UNIX_TIMESTAMP($TASearches.ASDate)>=UNIX_TIMESTAMP('$sdate') and UNIX_TIMESTAMP($TASearches.ASDate)<=UNIX_TIMESTAMP('$edate')) and $TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ASDate"])) array_push($dates, $tt["ASDate"]);
			}
		}
		if($res = dbSelectAll("$TASearches, $TAClicks", "$TAClicks.ACDate", "(UNIX_TIMESTAMP($TAClicks.ACDate)>=UNIX_TIMESTAMP('$sdate') and UNIX_TIMESTAMP($TAClicks.ACDate)<=UNIX_TIMESTAMP('$edate')) and $TASearches.ASDate!=$TAClicks.ACDate")){
			while($tt = mysql_fetch_array($res)){
				if(!ArrayContains($dates, $tt["ACDate"])) array_push($dates, $tt["ACDate"]);
			}
		}
		asort($dates);
		foreach($dates as $dd){
			$vars = array(
				"date"=>date2($dd),
				"searches"=>dbSelectCount($TASearches, "ASDate='".$dd."'"),
				"clicks"=>dbSelectCount($TAClicks, "ACDate='".$dd."'")
			);
			$OUT.= ParseTemplate($tmp, $vars);
		}
		return $OUT;
	}
	
	function MemberAffiliate($id, $sdate, $edate){
		global $MEMBER_CP_AFFILIATE_TMP, $MEMBERS_CP_URL;
		$vars = array(
			"getcode"=>$MEMBERS_CP_URL."?menu=11",
			"clearstats"=>$MEMBERS_CP_URL."?cmd=1002",
			"getmoney"=>$MEMBERS_CP_URL."?menu=12",
			"genreport"=>$MEMBERS_CP_URL."?cmd=1003",
			"total"=>GetSettings("currency")." ".AffGetTotalMoney($id),
			"d_from"=>ADFrom($sdate),
			"d_to"=>ADTo($edate),
			"stats"=>GetAStats($id, $sdate, $edate)
		);
		return ParseTemplate(ReadTemplate($MEMBER_CP_AFFILIATE_TMP), $vars);
	}
	
	function GetHTMLACode($id){
		global $MEMBER_AFFILIATE_CODE_TMP, $SITE_URL;
		$vars = array(
			"site"=>$SITE_URL,
			"id"=>$id
		);
		return ParseTemplate(ReadTemplate($MEMBER_AFFILIATE_CODE_TMP), $vars);
	}
	
	function GetAMoney($id){
		global $MEMBER_GET_AFF_MONEY_TMP;
		$vars = array(
			"total"=>GetSettings("currency")." ".AffGetTotalMoney($id),
			"addtobalance"=>$MEMBER_CP_URL."?cmd=1005",
			"paypal"=>"#"
		);
		return ParseTemplate(ReadTemplate($MEMBER_GET_AFF_MONEY_TMP), $vars);
	}

	function GetUserName($member_id){
		global $TMembersInfo;
		$tt = dbSelect($TMembersInfo, "Name", "MemberID=$member_id");
		return stripslashes($tt["Name"]);
	}
	
	
	function SendPassword($login){
		global $TMembersAccounts, $TMembersInfo, $MAIL_HEADERS, $ADMIN_MAIL, $MEMBER_F_PW_TMP;
		if($tt = dbSelect($TMembersAccounts, "MemberID, BUPW", "MemberLogin='".addslashes($login)."'")){
			$message = ReadTemplate($MEMBER_F_PW_TMP);
			$message = preg_replace("/<#pw#>/", base64_decode($tt["BUPW"]), $message);
			$message = preg_replace("/<#uname#>/", $login, $message);
			if($tt2 = dbSelect($TMembersInfo, "EMail", "MemberID=".$tt["MemberID"])){
				$email = stripslashes($tt2["EMail"]);
				@mail($email, "Your forgotten password", $message, $MAIL_HEADERS);
			}
		}
		return "1";
	}
	
	function GetMoneyFromAccount($member_id, $warning){
		global $TMembersCC, $TMembersBalance, $MEMBERS_GET_MONEY_TMP;
		$ppa = "";
		if($tt = dbSelect($TMembersCC, "PayPalAccount", "MemberID=$member_id")){
			$ppa = stripslashes($tt["PayPalAccount"]);
		}
		if($tt = dbSelect($TMembersBalance, "Balance", "MemberID=$member_id")){
			$balance = ($tt["Balance"]/100);
		}else{
			$balance = 0;
		}
		$vars = array(
			"ppa"=>$ppa,
			"currency"=>GetSettings("currency"),
			"balance"=>$balance,
			"warning"=>urldecode($warning)
		);
		return ParseTemplate(ReadTemplate($MEMBERS_GET_MONEY_TMP), $vars);
	}
	
?>