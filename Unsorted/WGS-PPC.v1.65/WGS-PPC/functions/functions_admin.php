<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Administrators functions       //
	//                                 //
	/////////////////////////////////////

	function AdminMain($mode = 0){
		global $ADMIN_MAINPAGE_TMP;
		global $TMembersAccounts, $TMembersTerms, $TMembersClicks, $TMembersBids;
		global $TBannersClicks, $TBannersShows, $TBannersBids, $TNoMatchesBids, $TMembersNMClicks;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_MAINPAGE_TMP);
		$allmembers = 0;
		if($tt = dbSelectCount($TMembersAccounts)){
			$allmembers = $tt;
		}
		$blockedmembers = 0;
		if($tt = dbSelectCount($TMembersAccounts, "STATUS=1")){
			$blockedmembers = $tt;
		}
		$clicks = 0;
		if($tt = dbSelectCount($TMembersClicks)){
			$clicks = $tt;
		}
		
		if($tt = dbSelectCount($TMembersNMClicks)){
			$nmclicks = $tt;
			$clicks+= $tt;
		}
		
		$tclicks = 0;
		if($tt = dbSelectCount($TMembersClicks, "ClickDate='".date("Y-m-d")."'")){
			$tclicks = $tt;
		}

		if($tt = dbSelectCount($TMembersNMClicks, "ClickDate='".date("Y-m-d")."'")){
			$tclicks+= $tt;
		}

		
		
		$tbclicks = 0;
		if($tt = dbSelectCount($TBannersClicks, "ClickDate='".date("Y-m-d")."'")){
			$tclicks+=$tt;
			$tbclicks = $tt;
		}
		
		$bclicks = 0;
		if($tt = dbSelectCount($TBannersClicks)){
			$clicks+=$tt;
			$bclicks = $tt;
		}
		$banners = 0;
		if($tt = dbSelectCount($TBannersShows)){
			$banners = $tt;
		}
		$clicksperday = 0;
		if($res = dbSelectAll($TMembersClicks, "count(ClickID)", "1=1 group by ClickDate")){
			$cc = 0; $sum = 0;
			while($tt = mysql_fetch_array($res)){
				$sum+=$tt["count(ClickID)"];
				$cc++;
			}
			$clicksperday = (int)($sum/$cc);
		}
		$money = 0;
		if($res = dbSelectAll($TMembersTerms." NATURAL LEFT JOIN ".$TMembersBids." NATURAL LEFT JOIN ".$TMembersClicks, "$TMembersTerms.TermID, $TMembersBids.Bid, count($TMembersClicks.ClickID)", "1=1 group by TermID")){
			while($tt = mysql_fetch_array($res)){
				$money+=$tt["count($TMembersClicks.ClickID)"]*$tt["Bid"];
			}
		}
		if($res = dbSelectAll($TBannersBids." LEFT JOIN ".$TBannersClicks." using(BannerID)", "$TBannersBids.BannerID, $TBannersBids.ClickBid, count($TBannersClicks.ClickID)", "1=1 group by BannerID")){
			while($tt = mysql_fetch_array($res)){
				$money+=$tt["count($TBannersClicks.ClickID)"]*$tt["ClickBid"];
			}
		}
		if($res = dbSelectAll($TBannersBids." LEFT JOIN ".$TBannersShows." using(BannerID)", "$TBannersBids.BannerID, $TBannersBids.ShowBid, count($TBannersShows.ShowID)", "1=1 group by BannerID")){
			while($tt = mysql_fetch_array($res)){
				$money+=$tt["count($TBannersShows.ShowID)"]*$tt["ShowBid"];
			}
		}
		
		if($res = dbSelectAll($TNoMatchesBids." NATURAL LEFT JOIN ".$TMembersNMClicks, "$TNoMatchesBids.MemberID, $TNoMatchesBids.Bid, count($TMembersNMClicks.ClickID)", "1=1 group by MemberID")){
			while($tt = mysql_fetch_array($res)){
				$money+=$tt["count($TMembersNMClicks.ClickID)"]*$tt["Bid"];
			}
		}
		
		$tmoney = 0;
		if($res = dbSelectAll($TMembersTerms." NATURAL LEFT JOIN ".$TMembersBids." NATURAL LEFT JOIN ".$TMembersClicks, "$TMembersTerms.TermID, $TMembersBids.Bid, count($TMembersClicks.ClickID)", "$TMembersClicks.ClickDate='".date("Y-m-d")."' group by TermID")){
			while($tt = mysql_fetch_array($res)){
				$tmoney+=$tt["count($TMembersClicks.ClickID)"]*$tt["Bid"];
			}
		}
		
		if($res = dbSelectAll($TNoMatchesBids." NATURAL LEFT JOIN ".$TMembersNMClicks, "$TNoMatchesBids.MemberID, $TNoMatchesBids.Bid, count($TMembersNMClicks.ClickID)", "$TMembersNMClicks.ClickDate='".date("Y-m-d")."' group by MemberID")){
			while($tt = mysql_fetch_array($res)){
				$tmoney+=$tt["count($TMembersNMClicks.ClickID)"]*$tt["Bid"];
			}
		}
		
		if($res = dbSelectAll($TBannersBids." LEFT JOIN ".$TBannersClicks." using(BannerID)", "$TBannersBids.BannerID, $TBannersBids.ClickBid, count($TBannersClicks.ClickID)", "$TBannersClicks.ClickDate='".date("Y-m-d")."' group by BannerID")){
			while($tt = mysql_fetch_array($res)){
				$tmoney+=$tt["count($TBannersClicks.ClickID)"]*$tt["ClickBid"];
			}
		}
		if($res = dbSelectAll($TBannersBids." LEFT JOIN ".$TBannersShows." using(BannerID)", "$TBannersBids.BannerID, $TBannersBids.ShowBid, count($TBannersShows.ShowID)", "$TBannersShows.ShowDate='".date("Y-m-d")."' group by BannerID")){
			while($tt = mysql_fetch_array($res)){
				$tmoney+=$tt["count($TBannersShows.ShowID)"]*$tt["ShowBid"];
			}
		}

		
		$moneyperday = 0;
		if($res = dbSelectAll("$TMembersBids NATURAL LEFT JOIN $TMembersClicks", "avg($TMembersBids.Bid)", "1=1 group by ClickDate")){
			$msum = 0; $cc = 0;
			while($tt = mysql_fetch_array($res)){
				$msum+=$tt["avg($TMembersBids.Bid)"];
				$cc++;
			}
			$moneyperday = (int)($msum/$cc);
		}

		if($res = dbSelectAll($TNoMatchesBids." NATURAL LEFT JOIN ".$TMembersNMClicks, "avg($TNoMatchesBids.Bid)", "1=1 group by ClickDate")){
			$msum = 0; $cc = 0;
			while($tt = mysql_fetch_array($res)){
				$msum+=$tt["avg($TNoMatchesBids.Bid)"];
				$cc++;
			}
			$moneyperday+= (int)($msum/$cc);
		}

		
		if($res = dbSelectAll("$TBannersBids LEFT JOIN $TBannersClicks USING(BannerID)", "avg($TBannersBids.ClickBid)", "1=1 group by ClickDate")){
			$msum = 0; $cc = 0;
			while($tt = mysql_fetch_array($res)){
				$msum+=$tt["avg($TBannersBids.ClickBid)"];
				$cc++;
			}
			$moneyperday+= (int)($msum/$cc);
		}
		if($res = dbSelectAll("$TBannersBids LEFT JOIN $TBannersShows USING(BannerID)", "avg($TBannersBids.ShowBid)", "1=1 group by ShowDate")){
			$msum = 0; $cc = 0;
			while($tt = mysql_fetch_array($res)){
				$msum+=$tt["avg($TBannersBids.ShowBid)"];
				$cc++;
			}
			$moneyperday+= (int)($msum/$cc);
		}
		$vars = array(
			"action"=>$ADMIN_URL,
			"allmembers"=>$allmembers,
			"blockedmembers"=>$blockedmembers,
			"money"=>($money/100),
			"clicks"=>$clicks,
			"bclicks"=>$bclicks,
			"tbclicks"=>$tbclicks,
			"clicksperday"=>$clicksperday,
			"moneyperday"=>($moneyperday/100),
			"todayclicks"=>$tclicks,
			"todaymoney"=>($tmoney/100),
			"banners"=>$banners,
			"currency"=>GetSettings("currency")
		);
		return ($mode==0?ParseTemplate($tmp, $vars):$vars);
	}
	
	function CountCChilds($id){
		global $TCategories;
		$childs = 0;
		if($res = dbSelectAll($TCategories, "CategoryID", "Parent=$id")){
			$count = 0;
			while($tt = mysql_fetch_array($res)){
				$count++;
				if(dbSelectCount($TCategories,"Parent=".$tt["CategoryID"])>0){
					$childs+=CountCChilds($tt["CategoryID"]);
				}
			}
		}
		return $childs+$count;
	}
	function GetCategories($parent=0, $level=0){
		global $TCategories, $TMembersSites, $ADMIN_URL, $ADMIN_CATEGORIES_PARTS_TMP;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_CATEGORIES_PARTS_TMP);
		$T = "$TCategories";
		$F = "$TCategories.CategoryID, $TCategories.Name";
		$Q = "$TCategories.Parent = $parent";
		$O = "$TCategories.Name";
		$out = "";
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				$count = dbSelectCount($TMembersSites, "CategoryID=".$tt["CategoryID"]);
				if($count==0&&CountCChilds($tt["CategoryID"])==0) $del = "<input type=\"checkbox\" name=\"categ".$tt["CategoryID"]."\" value=\"".$tt["CategoryID"]."\">";
				else $del = "&nbsp;";
				$vars = array(
					"del"=>$del,
					"name"=>str_repeat("&nbsp;&nbsp;",$level*3).$tt["Name"],
					"members"=>$count
				);
				$out.=ParseTemplate($tmp, $vars);
				if(dbSelectCount($TCategories, "Parent=".$tt["CategoryID"])>0){
					$out.= GetCategories($tt["CategoryID"], $level+1);
				}
			}
		}
		return $out;
	}
	function AdminCategories(){
		global $ADMIN_CATGORIES_TMP, $ADMIN_URL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_CATGORIES_TMP);
		$vars = array(
			"action"=>$ADMIN_URL,
			"addnew"=>$ADMIN_URL."?menu=255",
			"categories"=>GetCategories()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminAddCategory(){
		global $ADMIN_CATGORIES_ADD_TMP, $ADMIN_URL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_CATGORIES_ADD_TMP);
		$vars = array(
			"action"=>$ADMIN_URL,
			"categories"=>GetCategoriesSelectList2()
		);
		return ParseTemplate($tmp, $vars);
	}
//MEMBERS FUNCTIONS
	function GetMembers($page, $sort, $d){
		global $TCategories, $TMembersAccounts, $TMembersInfo, $TMembersBalance, $TMembersTerms, $TMembersClicks, $TMembersSites, $ADMIN_URL, $ADMIN_MEMBERS_PARTS_TMP, $ADMIN_MEMBERS_PARTS_EMPTY_TMP, $DEFAULT_IPP, $ADMIN_MEMBERS_PARTS2_TMP;
		global $TMembersNMClicks;
		$IPP = GetSettings("ipp");
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_MEMBERS_PARTS_TMP);
		$tmp2 = "";
		$tmp2 = ReadTemplate($ADMIN_MEMBERS_PARTS2_TMP);
		$T = "$TCategories, $TMembersAccounts ";
		$T.= "LEFT JOIN $TMembersInfo USING(MemberID) ";
		$T.= "LEFT JOIN $TMembersSites USING(MemberID) ";
		$T.= "LEFT JOIN $TMembersBalance USING(MemberID) ";
		$T.= "LEFT JOIN $TMembersTerms USING(MemberID) ";
		$T.= "LEFT JOIN $TMembersClicks USING(TermID) ";
		$F = "$TMembersAccounts.MemberID, count($TMembersTerms.TermID) as cterm, count($TMembersClicks.ClickID) as clcount, $TMembersAccounts.STATUS, $TMembersInfo.Name, $TMembersSites.Title, $TMembersSites.Link, $TMembersBalance.Balance, $TCategories.Name as CName";
		$Q = "$TMembersSites.CategoryID=$TCategories.CategoryID group by MemberID";
		$O = "$TMembersAccounts.MemberID";
		if($sort==0){
			$O = "$TMembersAccounts.MemberID";
		}elseif($sort==1){
			$O = "$TMembersInfo.Name";
		}elseif($sort==2){
			$O = "$TMembersSites.Title";
		}elseif($sort==3){
			$O = "CName";
		}elseif($sort==4){
			$O = "cterm";
		}elseif($sort==5){
			$O = "$TMembersBalance.Balance";
		}elseif($sort==6){
			$O = "clcount";
		}
		if($d==0){
			$O.=" desc";
		}
		$OUT = "";
		$total_records = dbSelectCount($TMembersAccounts);
		if($res = dbSelectAll($T, $F, $Q, $O, $page*$IPP.",".$IPP)){
			$ids = array();
			while($tt = mysql_fetch_array($res)){
				if($tt["STATUS"]==0) $warnings="";
				else $warnings = "<br><span style='color:#ff0000'>Blocked</span>";
				if($tt["Balance"]<=GetSettings("minbal")) $warnings.= "<br><span style='color:#ff0000'>LOW BALANCE!</span>";
				$vars = array(
					"pos"=>$tt["MemberID"],
					"name"=>"<a href=\"".$ADMIN_URL."?menu=500&id=".$tt["MemberID"]."\">".stripslashes($tt["Name"])."</a>",
					"listings"=>"<a href=\"".$ADMIN_URL."?menu=5000&id=".$tt["MemberID"]."\">view listing(s)</a>",
					"site"=>stripslashes($tt["Title"])."<br><i><a href=\"http://".stripslashes($tt["Link"])."\">http://".stripslashes($tt["Link"])."</a></i>",
					"categ"=>stripslashes($tt["CName"]),
					"terms"=>$tt["cterm"],
					"clicks"=>($tt["clcount"]+dbSelectCount($TMembersNMClicks, "MemberID=".$tt["MemberID"])),
					"balance"=>($tt["Balance"]/100).$warnings,
					"id"=>$tt["MemberID"]
				);
				array_push($ids, $tt["MemberID"]);
				$OUT.=ParseTemplate($tmp, $vars);
			}
			if($total_records>$IPP){
/*				$text = "Pages: ";
				$pages = ceil($total_records/$IPP);
				$pps = array();
				for($i = 0; $i<$pages; $i++){
					if($i==$page) $pp = ($i+1);
					else $pp = "<a href=\"".$ADMIN_URL."?menu=2&page=$i&d=".($d==0?1:0)."&sort=$sort\">".($i+1)."</a>";
					$pps[$i] = $pp;
				}
				$text.=join(" | ", $pps);
*/			
				$vars = array(	
					"text"=>GetPagesLinks($page, $total_records, $IPP,  "&menu=2&d=".($d==0?1:0)."&sort=$sort"),
				);
				$OUT.=ParseTemplate($tmp2, $vars);
			}
		}else{
			$OUT = ReadTemplate($ADMIN_MEMBERS_PARTS_EMPTY_TMP);
		}
		return $OUT;
	}

	function AdminMembers($page, $sort, $d){
		global $ADMIN_MEMBERS_TMP, $ADMIN_URL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_MEMBERS_TMP);
		if($d==0) $d=1; else $d=0;
		$vars = array(
			"action"=>$ADMIN_URL,
			"page"=>$page,
			"d"=>$d,
			"sort"=>$sort,
			"currency"=>GetSettings("currency"),
			"members"=>GetMembers($page, $sort, $d)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminMemberModifyAccount($id, $error = ""){
		global $ADMIN_MEMBERS_MODIFY_TMP, $ADMIN_URL;
		global $TMembersInfo, $TMembersSites;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_MEMBERS_MODIFY_TMP);
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
			"action"=>$ADMIN_URL,
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
			"member_id"=>$id,
			"categories"=>GetCategoriesSelectList($category)
		);
		return ParseTemplate($tmp, $vars);
	}

	function AdminSendEmail($id = 0){
		global $ADMIN_SEN_MAIL_TO_MEMBER_TMP, $TMembersInfo, $ADMIN_EMAIL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_SEN_MAIL_TO_MEMBER_TMP);
		$member = "Unknown";
		if($id==0){
			$member = "all members";
		}elseif(is_array($id)){
			$member = "selected members";
		}else{
			if($tt = dbSelect($TMembersInfo, "Name", "MemberID=$id")){
				$member = $tt["Name"];
			}
		}
		$vars = array(
			"member"=>$member,
			"id"=>$id,
			"adminemail"=>$ADMIN_EMAIL
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminSettings($error = ""){
		global $ADMIN_SETTINGS_TMP;
		global $TAdminSettings, $TAdminMembersBonus;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_SETTINGS_TMP);
		$minbid = 0; $minbal = 0; $bonus = 0; $symbol = "\$";
		$topnum = 5;
		$sel = 0; $dipp = "";
		if($tt = dbSelect($TAdminMembersBonus, "Bonus")){
			$bonus = $tt["Bonus"]/100;
		}
		if($tt = dbSelect($TAdminSettings)){
			$minbid = $tt["MinBid"]/100;
			$nmminbid = $tt["MinNoMatchesBid"]/100;
			$minbal = $tt["Porog"]/100;
			$symbol = stripslashes($tt["CurrencySymbol"]);
			$topnum = $tt["TopPosNumber"];
			$sel = $tt["IPP"];
			$accbids = $tt["AcceptBids"];
			$allowss = $tt["AllowSS"];
			$ascost = $tt["ACOCost"];
			$accost = $tt["ACLCost"];
			$allowipn = $tt["AllowIPN"];
			$allowmsn = $tt["AllowMSN"];
			$allowdmoz = $tt["AllowDMOZ"];
			$appemail = stripslashes($tt["APPEMail"]);
		}
		for($i=10; $i<=50; $i+=10){
			if($sel!=$i) $sels = ""; else $sels = "selected";
			$dipp.="<option value='".$i."' $sels>$i</option>\n";
		}
		$vars = array(
			"error"=>$error,
			"bonus"=>$bonus,
			"minbid"=>$minbid,
			"nmminbid"=>$nmminbid,
			"minbal"=>$minbal,
			"symbol"=>$symbol,
			"dipp"=>$dipp,
			"acceptbids"=>($accbids==1?"checked":""),
			"allowss"=>($allowss==1?"checked":""),
			"allowipn"=>($allowipn==1?"checked":""),
			"topnum"=>$topnum,
			"ascost"=>($ascost/100),
			"accost"=>($accost/100),
			"appemail"=>$appemail,
			"allowmsn"=>($allowmsn==1?"checked":""),
			"allowdmoz"=>($allowdmoz==1?"checked":""),
		);
		return ParseTemplate($tmp, $vars);
	}

//STATISTICS

	function GetStatsFull($page, $sort, $d, $stype, $mode = 0, $sdate = '0000-00-00'){
		global $TSearchStatistics, $TMembersTerms, $TMembersBids, $TMembersClicks;
		global $ADMIN_STATISTICS_FULL_PARTS_TMP, $ADMIN_STATISTICS_FULL_PARTS2_TMP, $IPP;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS_TMP);
		$tmp2 = "";
		$tmp2 = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS2_TMP);
		if($sdate=="0000-00-00") $sdate = date("Y-m-d");
		$today = $sdate;
		if($mode==0) $W = "1=1";
		elseif($mode==1) $W = "UNIX_TIMESTAMP($TSearchStatistics.SearchDate)=UNIX_TIMESTAMP('$today')";
		$T = "$TSearchStatistics LEFT JOIN $TMembersTerms ON ($TSearchStatistics.Term like concat('%',$TMembersTerms.Term,'%') OR  CONCAT('%',$TMembersTerms.Term,'%') like $TSearchStatistics.Term)";
		$F = "$TSearchStatistics.Term, count($TSearchStatistics.Term) as tcount, count($TMembersTerms.TermID) mcount";
		$Q = "$W group by $TSearchStatistics.Term";
		$O = "";
		if($sort==0){
			$O = "$TSearchStatistics.Term";
		}elseif($sort==1){
			$O = "tcount";
		}elseif($sort==2){
			$O = "mcount";
		}else{
			$O = "$TSearchStatistics.Term";
		}
		if($d==0) $O.=" desc";
		$total_records = 0;
		if($tt = dbSelectAll($TSearchStatistics, "distinct Term", "$W")){
			$total_records = mysql_num_rows($tt);
		}
		if($res = dbSelectAll($T, $F, $Q, $O, $page*$IPP.",".$IPP)){
			$total_kw = 0; $total_ss = 0; $total_mm = 0; $total_clicks = 0; $total_cost = 0;
			while($tt = mysql_fetch_array($res)){
				
				$clicks = 0;
				$cost = 0;
				$carr = array();
				$coarr = array();
				if($res2 = dbSelectAll($TMembersClicks, "TermID", "SearchTerm='".$tt["Term"]."'".($mode!=1?"":" and UNIX_TIMESTAMP(ClickDate)=UNIX_TIMESTAMP('$today')"))){
					while($tt2 = mysql_fetch_array($res2)){
						$carr[$tt2["TermID"]]++;
						$clicks++;
						if($cc = dbSelect($TMembersBids, "Bid", "TermID=".$tt2["TermID"])){
							$coarr[$tt2["TermID"]] = $cc["Bid"];
						}
					}
				}
				$matches = dbSelectCount("$TSearchStatistics,$TMembersTerms",($mode==1?" $W and ":"")."$TSearchStatistics.Term='".$tt["Term"]."' and $TSearchStatistics.Term like CONCAT('%',$TMembersTerms.Term,'%') OR  CONCAT('%',$TMembersTerms.Term,'%') like $TSearchStatistics.Term");
				foreach($carr as $term_id=>$cclicks){
					$cost+=$cclicks*$coarr[$term_id];
				}
				$tcost = $cost/100;
				
/*				$clicks = 0;
				if($tt2 = dbSelectAll("$TSearchStatistics, $TMembersClicks, $TMembersTerms", "$TMembersClicks.ClickID, $TMembersTerms.TermID", "$TSearchStatistics.Term='".$tt["Term"]."' and ".($mode==1?" $W and ":"")." $TMembersClicks.SearchTerm='".$tt["Term"]."' group by ClickID")){
					$clicks = mysql_num_rows($tt2);
				}
				print "select $TMembersClicks.ClickID from $TSearchStatistics, $TMembersClicks where $TSearchStatistics.Term='".$tt["Term"]."' and ".($mode==1?" $W and ":"")." $TMembersClicks.SearchTerm='".$tt["Term"]."' group by ClickID<hr>";
				$cost = 0;
				if($tt2 = dbSelect("$TSearchStatistics, $TMembersTerms, $TMembersBids, $TMembersClicks", "$TMembersBids.Bid", "$TSearchStatistics.Term='".$tt["Term"]."' and ".($mode==1?" $W and ":"")." $TMembersClicks.SearchTerm='".$tt["Term"]."' and $TMembersTerms.TermID=$TMembersClicks.TermID and $TMembersBids.TermID=$TMembersTerms.TermID")){
					$cost+=$tt2["Bid"];
				}
				$tcost = ($clicks*$cost)/100;
*/

//				$scount = $tt["tcount"];
				
				$scount = dbSelectCount($TSearchStatistics, "Term='".$tt["Term"]."'");
				
				$vars = array(
					"keywords"=>stripslashes($tt["Term"]),
					"searches"=>$scount,
					"matches"=>$tt["mcount"],
					"clicks"=>$clicks,
					"cost"=>$tcost
				);
				$total_kw++;
				$total_ss+=$scount;
				$total_mm+=$tt["mcount"];
				$total_clicks+=$clicks;
				$total_cost+=$tcost;
				$OUT.=ParseTemplate($tmp, $vars);
			}
			if($mode!=1){
				$total_clicks = dbSelectCount($TMembersClicks);
				$qcost = 0;
				$carr = array();
				$coarr = array();
				if($res2 = dbSelectAll($TMembersClicks, "TermID")){
					while($tt2 = mysql_fetch_array($res2)){
						$carr[$tt2["TermID"]]++;
						$clicks++;
						if($cc = dbSelect($TMembersBids, "Bid", "TermID=".$tt2["TermID"])){
							$coarr[$tt2["TermID"]] = $cc["Bid"];
						}
					}
				}
				foreach($carr as $term_id=>$cclicks){
					$qcost+=$cclicks*$coarr[$term_id];
				}
				$total_cost = $qcost/100;
			}
			
			$vars = array(
				"keywords"=>"<b>Total: $total_records keyword(s)</b>",
				"searches"=>"<b>".$total_ss."</b>",
				"matches"=>"<b>".$total_mm."</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"cost"=>"<b>".$total_cost."</b>"
			);
			$total_kw++;
			$total_ss+=$tt["tcount"];
			$total_mm+=$tt["mcount"];
			$total_clicks+=$clicks;
			$total_cost+=$tcost;
			$OUT.=ParseTemplate($tmp, $vars);
			if($mode!=1)
			if($total_records>$IPP){
/*				$text = "Pages: ";
				$pages = ceil($total_records/$IPP);
				$pps = array();
				for($i = 0; $i<$pages; $i++){
					if($i==$page) $pp = ($i+1);
					else $pp = "<a href=\"".$ADMIN_URL."?menu=4&page=$i&d=".($d==0?1:0)."&sort=$sort&stype=$stype".($mode!=0?"&sdate=$sdate":"")."\">".($i+1)."</a>";
					$pps[$i] = $pp;
				}
				$text.=join(" | ", $pps);
*/				
				$vars = array(	
					"text"=>GetPagesLinks($page, $total_records, $IPP, "&menu=4&d=".($d==0?1:0)."&sort=$sort&stype=$stype".($mode!=0?"&sdate=$sdate":"")),
				);
				$OUT.=ParseTemplate($tmp2, $vars);
			}
		}else{
			$vars = array("text"=>"empty");
			$OUT = ParseTemplate($tmp2, $vars);
		}
		return $OUT;
	}

	function GetStatsMonthly($page, $sort, $d, $stype, $sdate){
		global $TSearchStatistics, $TMembersTerms, $TMembersBids, $TMembersClicks;
		global $ADMIN_STATISTICS_FULL_PARTS_TMP, $ADMIN_STATISTICS_FULL_PARTS2_TMP, $IPP, $ADMIN_URL;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS_TMP);
		$tmp2 = "";
		$tmp2 = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS2_TMP);
		$T = "$TSearchStatistics";
		$F = "SearchDate, count(Term) as tcount";
		$Q = "YEAR(SearchDate)=YEAR('$sdate') and MONTH(SearchDate)=MONTH('$sdate') group by SearchDate";
		$O = "SearchDate";
		if($d==0) $O.=" desc";
		$total_records = 0;
		if($res = dbSelectAll($T, $F, $Q, $O)){
			$total_records = mysql_num_rows($res);
			$total_kw = 0; $total_ss = 0; $total_mm = 0; $total_clicks = 0; $total_cost = 0;
			while($tt = mysql_fetch_array($res)){
				$clicks = 0;
				$cost = 0;
				$carr = array();
				$coarr = array();
				if($res2 = dbSelectAll($TMembersClicks, "TermID", "ClickDate='".$tt["SearchDate"]."'")){
					while($tt2 = mysql_fetch_array($res2)){
						$carr[$tt2["TermID"]]++;
						$clicks++;
						if($cc = dbSelect($TMembersBids, "Bid", "TermID=".$tt2["TermID"])){
							$coarr[$tt2["TermID"]] = $cc["Bid"];
						}
					}
				}
				$matches = dbSelectCount("$TSearchStatistics,$TMembersTerms","$TSearchStatistics.SearchDate='".$tt["SearchDate"]."' and $TSearchStatistics.Term like CONCAT('%',$TMembersTerms.Term,'%') OR  CONCAT('%',$TMembersTerms.Term,'%') like $TSearchStatistics.Term");
				foreach($carr as $term_id=>$cclicks){
					$cost+=$cclicks*$coarr[$term_id];
				}
				$tcost = $cost/100;
				$vars = array(	
					"keywords"=>"<a href=\"".$ADMIN_URL."?menu=4&stype=2&sdate=".$tt["SearchDate"]."\">".date2($tt["SearchDate"])."</a>",
					"searches"=>$tt["tcount"],
					"matches"=>$matches,
					"clicks"=>$clicks,
					"cost"=>$tcost
				);
				$total_kw++;
				$total_ss+=$tt["tcount"];
				$total_mm+=$matches;
				$total_clicks+=$clicks;
				$total_cost+=$tcost;
				$OUT.=ParseTemplate($tmp, $vars);
			}
			$vars = array(
				"keywords"=>"<b>Total: $total_records keyword(s)</b>",
				"searches"=>"<b>".$total_ss."</b>",
				"matches"=>"<b>".$total_mm."</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"cost"=>"<b>".$total_cost."</b>"
			);
			$total_kw++;
			$total_ss+=$tt["tcount"];
			$total_mm+=$tt["mcount"];
			$total_clicks+=$clicks;
			$total_cost+=$tcost;
			$OUT.=ParseTemplate($tmp, $vars);
		}else{
			$vars = array("text"=>"empty");
			$OUT = ParseTemplate($tmp2, $vars);
		}
		return $OUT;
	}
	
	
	function GetStatsTopSearches(){
		global $TSearchStatistics, $TMembersTerms, $TMembersBids, $TMembersClicks;
		global $ADMIN_STATISTICS_FULL_PARTS_TMP, $ADMIN_STATISTICS_FULL_PARTS2_TMP, $IPP;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS_TMP);
		$tmp2 = "";
		$tmp2 = ReadTemplate($ADMIN_STATISTICS_FULL_PARTS2_TMP);
		$today = $sdate;
		$W = "1=1";
		$T = "$TSearchStatistics LEFT JOIN $TMembersTerms ON $TSearchStatistics.Term like concat('%',$TMembersTerms.Term,'%')";
		$F = "$TSearchStatistics.Term, count($TSearchStatistics.Term) as tcount, count($TMembersTerms.TermID) mcount";
		$Q = "$W group by $TSearchStatistics.Term";
		$O = "tcount desc, mcount desc";
		$total_records = 0;
		if($tt = dbSelectAll($TSearchStatistics, "distinct Term", "$W")){
			$total_records = mysql_num_rows($tt);
		}
		if($res = dbSelectAll($T, $F, $Q, $O, GetSettings("topnum"))){
			$total_kw = 0; $total_ss = 0; $total_mm = 0; $total_clicks = 0; $total_cost = 0;
			while($tt = mysql_fetch_array($res)){
				
				$clicks = 0;
				$cost = 0;
				$carr = array();
				$coarr = array();
				if($res2 = dbSelectAll($TMembersClicks, "TermID", "SearchTerm='".$tt["Term"]."'")){
					while($tt2 = mysql_fetch_array($res2)){
						$carr[$tt2["TermID"]]++;
						$clicks++;
						if($cc = dbSelect($TMembersBids, "Bid", "TermID=".$tt2["TermID"])){
							$coarr[$tt2["TermID"]] = $cc["Bid"];
						}
					}
				}
				$matches = dbSelectCount("$TSearchStatistics,$TMembersTerms","$TSearchStatistics.Term='".$tt["Term"]."' and $TSearchStatistics.Term like CONCAT('%',$TMembersTerms.Term,'%') OR  CONCAT('%',$TMembersTerms.Term,'%') like $TSearchStatistics.Term");
				foreach($carr as $term_id=>$cclicks){
					$cost+=$cclicks*$coarr[$term_id];
				}
				$tcost = $cost/100;

				
/*				
				$clicks = dbSelectCount("$TSearchStatistics, $TMembersClicks", "$TSearchStatistics.Term='".$tt["Term"]."' and ".($mode==1?" $W and ":"")." $TMembersClicks.SearchTerm='".$tt["Term"]."'");
				$cost = 0;
				if($tt2 = dbSelect("$TSearchStatistics, $TMembersTerms, $TMembersBids", "sum($TMembersBids.Bid)", "$TSearchStatistics.Term='".$tt["Term"]."' and ".($mode==1?" $W and ":"")." $TSearchStatistics.Term like concat('%',$TMembersTerms.Term,'%') and $TMembersBids.TermID=$TMembersTerms.TermID")){
					$cost = $tt2["sum($TMembersBids.Bid)"];
				}
				$tcost = ($clicks*$cost)/100;
*/
				$vars = array(
					"keywords"=>$tt["Term"],
					"searches"=>$tt["tcount"],
					"matches"=>$tt["mcount"],
					"clicks"=>$clicks,
					"cost"=>$tcost
				);
				$total_kw++;
				$total_ss+=$tt["tcount"];
				$total_mm+=$tt["mcount"];
				$total_clicks+=$clicks;
				$total_cost+=$tcost;
				$OUT.=ParseTemplate($tmp, $vars);
			}
			$vars = array(
				"keywords"=>"<b>Total: $total_records keyword(s)</b>",
				"searches"=>"<b>".$total_ss."</b>",
				"matches"=>"<b>".$total_mm."</b>",
				"clicks"=>"<b>".$total_clicks."</b>",
				"cost"=>"<b>".$total_cost."</b>"
			);
			$total_kw++;
			$total_ss+=$tt["tcount"];
			$total_mm+=$tt["mcount"];
			$total_clicks+=$clicks;
			$total_cost+=$tcost;
			$OUT.=ParseTemplate($tmp, $vars);
		}else{
			$vars = array("text"=>"empty");
			$OUT = ParseTemplate($tmp2, $vars);
		}
		return $OUT;
	}
	
	function GetStatsTopBids(){
		global $ADMIN_STATISTICS_TOPBIDS_PARTS_TMP, $ADMIN_STATISTICS_TOPBIDS_PARTS2_TMP;
		global $TMembersInfo, $TMembersTerms, $TMembersBids;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_STATISTICS_TOPBIDS_PARTS_TMP);
		$tmp2 = "";
		$tmp2 = ReadTemplate($ADMIN_STATISTICS_TOPBIDS_PARTS2_TMP);
		$T = "$TMembersBids LEFT JOIN $TMembersTerms USING(TermID) LEFT JOIN $TMembersInfo USING(MemberID)";
		$F = "$TMembersBids.Bid, $TMembersTerms.Term, $TMembersInfo.Name";
		$Q = "1=1";
		$O = "$TMembersBids.Bid desc";
		if($res = dbSelectAll($T, $F, $Q, $O, GetSettings("topnum"))){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"bid"=>$tt["Bid"]/100,
					"term"=>stripslashes($tt["Term"]),
					"member"=>stripslashes($tt["Name"])
				);
				$OUT.=ParseTemplate($tmp, $vars);
			}
		}else{
			$vars = array("text"=>"empty");
			$OUT = ParseTemplate($tmp2, $vars);
		}
		return $OUT;
	}
	
	function AdminStatisticsMain($stype=0, $sort=0, $page=0, $d=0, $sdate=""){
		global $TSearchStatistics, $TMembersTerms, $TMembersBids;
		global $ADMIN_STATISTICS_FULL_TMP, $ADMIN_STATISTICS_MONTHLY_TMP, $ADMIN_STATISTICS_DAYLY_TMP, $ADMIN_STATISTICS_TOPSEARCHES_TMP, $ADMIN_STATISTICS_TOPBIDS_TMP, $ADMIN_URL;
		$tmp = "";
		if($d==0) $d=1; else $d=0;
		$vars = array(
			"action"=>$ADMIN_URL,
			"page"=>$page,
			"d"=>$d,
			"sort"=>$sort,
			"stype"=>$stype,
			"currency"=>GetSettings("currency")
		);
		if($stype==0){
			$vars["stats"] = GetStatsFull($page, $sort, $d, $stype);
			$template = $ADMIN_STATISTICS_FULL_TMP;
		}elseif($stype==1){
			$vars["stats"] = GetStatsMonthly($page, $sort, $d, $stype, $sdate);
			$mdate = month2($sdate);
			$months = "<option value='$sdate'>".$mdate."</option>";
			$mon = array();
			if($res = dbSelectAll($TSearchStatistics, "distinct SearchDate")){
				while($tt = mysql_fetch_array($res)){
					$mm = month2($tt["SearchDate"]);
					if($mm!=$mdate&&!ArrayContains($mon, $mm)){
						$months.="<option value='".$tt["SearchDate"]."'>".$mm."</option>";
						array_push($mon, $mm);
					}
				}
			}
			$vars["months"] = $months;
			$vars["sdate"] = $sdate;
			$template = $ADMIN_STATISTICS_MONTHLY_TMP;
		}elseif($stype==2){
			$vars["stats"] = GetStatsFull($page, $sort, $d, $stype, 1, $sdate);
			$days = "<option value='$sdate'>".date2($sdate)."</option>";
			if($res = dbSelectAll($TSearchStatistics, "distinct SearchDate")){
				while($tt = mysql_fetch_array($res)){
					if($tt["SearchDate"]!=$sdate) $days.="<option value='".$tt["SearchDate"]."'>".date2($tt["SearchDate"])."</option>";
				}
			}
			$vars["days"] = $days;
			$vars["sdate"] = $sdate;
			$template = $ADMIN_STATISTICS_DAYLY_TMP;
		}elseif($stype==3){
			$vars["tops"] = GetStatsTopSearches();
			$vars["topnum"] = GetSettings("topnum");
			$template = $ADMIN_STATISTICS_TOPSEARCHES_TMP;
		}elseif($stype==4){
			$vars["tops"] = GetStatsTopBids();
			$vars["topnum"] = GetSettings("topnum");
			$template = $ADMIN_STATISTICS_TOPBIDS_TMP;
		}else{
			$vars["stats"] = GetStatsFull($page, $sort, $d, $stype);
			$template = $ADMIN_STATISTICS_FULL_TMP;
		}
		$tmp = ReadTemplate($template);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetBMembers($member_id){
		global $TMembersInfo, $TBanners;
		$OUT = "";
		if($res = dbSelectAll("$TMembersInfo, $TBanners", "distinct $TMembersInfo.MemberID, $TMembersInfo.Name", "$TMembersInfo.MemberID=$TBanners.MemberID", "Name")){
			while($tt = mysql_fetch_array($res)){
				if($tt["MemberID"]!=$member_id) $sel = "";
				else $sel = "selected";
				$OUT.="<option value=\"".$tt["MemberID"]."\" $sel 	>".stripslashes($tt["Name"])."</option>\n";
			}
		}
		return $OUT;
	}
	
	function AdminStatisticsBanners($member_id, $sort, $page, $d){
		global $ADMIN_BANNERSSTATS_TMP, $MEMBERS_CP_URL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_BANNERSSTATS_TMP);
		$vars = array(
			"members"=>GetBMembers($member_id),
			"currency"=>GetSettings("currency"),
			"stats"=>GetBannersStatistics($member_id)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetFilteredWords(){
		global $TFiltered, $ADMIN_FILTER_PARTS_TMP, $ADMIN_FILTER_PARTS_EMPTY_TMP;
		$tmp = ReadTemplate($ADMIN_FILTER_PARTS_TMP);
		$OUT = "";
		$T = "$TFiltered";
		$F = "KeywordID, Keyword";
		$Q = "1=1";
		$O = "Keyword";
		$total_kw = 0;
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"kw"=>stripslashes($tt["Keyword"]),
					"id"=>$tt["KeywordID"]
				);
				$total_kw++;
				$OUT.=ParseTemplate($tmp, $vars);
			}
		}else{
			$OUT = ReadTemplate($ADMIN_FILTER_PARTS_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function AdminFilter(){
		global $ADMIN_FILTER_TMP;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_FILTER_TMP);
		$vars = array(
			"filtered"=>GetFilteredWords()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetAdminsBanners(){
		global $ADMIN_BANNERS_PART_TMP, $ADMIN_BANNERS_PART_EMPTY_TMP, $ADMIN_URL, $DEFAULT_IPP;
		global $TBanners;
		$OUT = "";
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_BANNERS_PART_TMP);
		$T = "$TBanners";
		$F = "BannerID, STATUS";
		$Q = "MemberID=0";
		$O = "BannerID";
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
					"edit"=>$ADMIN_URL."?menu=356&id=".$tt["BannerID"]
				);
				$OUT.=ParseTemplate($tmp, $vars);
				$count++;
			}
		}else{
			$OUT = ReadTemplate($ADMIN_BANNERS_PART_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function AdminBanners(){
		global $ADMIN_BANNERS_TMP, $ADMIN_URL;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_BANNERS_TMP);
		$vars = array(
			"addnew"=>$ADMIN_URL."?menu=355",
			"currency"=>GetSettings("currency"),
			"banners"=>GetAdminsBanners()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminAddBanner($error=""){
		global $ADMIN_ADDBANNER_TMP, $TMembersTerms;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_ADDBANNER_TMP);
		$vars = array(
			"error"=>$error
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminEditBanner($id, $error){
		global $ADMIN_EDITBANNER_TMP, $TBanners;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_EDITBANNER_TMP);
		if(dbSelectCount($TBanners, "BannerID=$id")){
			$banner = "";
			$alt = "";
			$bannerlink = "";
			if($tt = dbSelect($TBanners, "*", "BannerID=$id and MemberID=0")){
				$banner = GetBanner($id);
				$alt = stripslashes($tt["BannerAlt"]);
				$bannerlink = stripslashes($tt["Link"]);
			}
		}else $error = "Banner ID is incorrect";
		$vars = array(
			"error"=>$error,
			"banner"=>$banner,
			"alt"=>$alt,
			"bannerid"=>$id,
			"bannerlink"=>$bannerlink
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetMemberListings($id){
		global $TMembersAccounts, $TMembersTerms, $TMembersBids, $ADMIN_MEMBERS_LISTINGS_PART;
		$OUT = "";
		$tmp = ReadTemplate($ADMIN_MEMBERS_LISTINGS_PART);
		if($res = dbSelectAll("$TMembersTerms,$TMembersBids", "$TMembersTerms.TermID, $TMembersTerms.Term, $TMembersBids.Bid", "$TMembersTerms.MemberID=$id and $TMembersBids.TermID=$TMembersTerms.TermID", "$TMembersBids.Bid desc, $TMembersTerms.Term")){
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"term"=>stripslashes($tt["Term"]),
					"del"=>"<input type='checkbox' name='term".$tt["TermID"]."' value='".$tt["TermID"]."'>",
					"bid"=>$tt["Bid"]/100
				);
				$OUT.=ParseTemplate($tmp, $vars);
			}
		}
		return $OUT;
	}
	
	function MemberName($id){
		global $TMembersInfo;
		$OUT = "N/A";
		if($tt = dbSelect($TMembersInfo, "Name, Company", "MemberID=$id")){
			$cc = stripslashes($tt["Company"]);
			$OUT = stripslashes($tt["Name"]).($cc!=""?" ( $cc )":"");
		}
		return $OUT;
	}
	
	function GetNMBidM($id){
		global $TNoMatchesBids;
		$OUT = "N/A";
		if($tt= dbSelect($TNoMatchesBids, "Bid", "MemberID=$id")){
			$OUT = $tt["Bid"]/100;
		}
		return $OUT;
	}
	
	function AdminMemberListings($id){
		global $ADMIN_MEMBER_LISTINGS;
		$tmp = "";
		$tmp = ReadTemplate($ADMIN_MEMBER_LISTINGS);
		$vars = array(
			"member"=>MemberName($id),
			"listings"=>GetMemberListings($id),
			"mid"=>$id,
			"nmbid"=>GetNMBidM($id)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	//WAITING BIDS FROM MEMBERS
	
	function GetWaitingBids(){
		global $TMembersInfo, $TMembersTerms, $TMembersBids, $TTempTermsBids;
		global $ADMIN_WAITING_BIDS_PART, $ADMIN_WAITING_BIDS_EMPTY, $ADMIN_URL;
		$OUT = "";
		if($res = dbSelectAll($TTempTermsBids, "*")){
			$tmp = ReadTemplate($ADMIN_WAITING_BIDS_PART);
			while($tt = mysql_fetch_array($res)){
				$vars = array(
					"member"=>"<a href=\"".$ADMIN_URL."?menu=5000&id=".$tt["MemberID"]."\">".MemberName($tt["MemberID"])."</a>",
					"term"=>($tt["TYPE"]==0?stripslashes($tt["Term"]):"&quot;No matches&quot;"),
					"bid"=>$tt["Bid"]/100,
					"del"=>"<input type='checkbox' name='tb".$tt["TempID"]."' value='".$tt["TYPE"]."'>"
				);
				$OUT.=ParseTemplate($tmp, $vars);
			}
		}
		if($OUT=="") $OUT = ReadTemplate($ADMIN_WAITING_BIDS_EMPTY);
		return $OUT;
	}
	
	function AdminWaitingBids(){
		global $ADMIN_WAITING_BIDS;
		$tmp = ReadTemplate($ADMIN_WAITING_BIDS);
		$vars = array(
			"waitingbids"=>GetWaitingBids()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminMemberBalance($id){
		global $ADMIN_MEMBER_BALANCE, $TMembersBalance, $TMembersInfo;
		$tmp = ReadTemplate($ADMIN_MEMBER_BALANCE);
		$name = "N/A";
		$balance = "N/A";
		if($tt = dbSelect("$TMembersBalance, $TMembersInfo", "$TMembersBalance.Balance, $TMembersInfo.Name", "$TMembersBalance.MemberID=$id and $TMembersInfo.MemberID=$id")){
			$name = stripslashes($tt["Name"]);
			$balance = $tt["Balance"]/100;
		}
		$vars = array(
			"member"=>$name,
			"balance"=>$balance,
			"member_id"=>$id
		);
		return ParseTemplate($tmp, $vars);
	}
	
	//LowLevel Acccounts listing
	
	function GetSpecAccounts(){
		global $TAdminSpecialAccounts, $ADMIN_LLA_PARTS_TMP, $ADMIN_LLA_PARTS_EMPTY_TMP;
		global $ADMIN_MASKS, $ADMIN_URL, $ADMIN_MASKS_DESCR;
		$OUT = "";
		if($res = dbSelectAll($TAdminSpecialAccounts, "AccountID, AccountLogin, AccessRights")){
			$tmp = ReadTemplate($ADMIN_LLA_PARTS_TMP);
			while($tt = mysql_fetch_array($res)){
				$rr = $tt["AccessRights"];
				$rights = "";
				if(($rr&$ADMIN_MASKS["ADMIN_MASK_REG"])!=0) $rights.= $ADMIN_MASKS_DESCR["ADMIN_MASK_REG"]."<br>";
				if(($rr&$ADMIN_MASKS["ADMIN_MASK_PAY"])!=0) $rights.= $ADMIN_MASKS_DESCR["ADMIN_MASK_PAY"]."<br>";
				if(($rr&$ADMIN_MASKS["ADMIN_MASK_WB"])!=0) $rights.= $ADMIN_MASKS_DESCR["ADMIN_MASK_WB"]."<br>";
				if(($rr&$ADMIN_MASKS["ADMIN_MASK_BU"])!=0) $rights.= $ADMIN_MASKS_DESCR["ADMIN_MASK_BU"]."<br>";
				if(($rr&$ADMIN_MASKS["ADMIN_MASK_FILTER"])!=0) $rights.= $ADMIN_MASKS_DESCR["ADMIN_MASK_FILTER"]."<br>";
				
				$vars = array(
					"id"=>$tt["AccountID"],
					"login"=>"<a href=\"".$ADMIN_URL."?menu=12&id=".$tt["AccountID"]."\">".stripslashes($tt["AccountLogin"])."</a>",
					"rights"=>$rights
				);
				$OUT.=ParseTemplate($tmp,$vars);
			}
		}else{
			$OUT = ReadTemplate($ADMIN_LLA_PARTS_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function AdminLLAccountsList(){
		global $ADMIN_LLA_TMP, $ADMIN_URL;
		$tmp = ReadTemplate($ADMIN_LLA_TMP);
		$vars = array(
			"action"=>$ADMIN_URL,
			"accounts"=>GetSpecAccounts()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetRighsTable($rights = 0){
		global $ADMIN_MASKS, $ADMIN_MASKS_DESCR;
		$OUT = "<table border='0' cellspacing='0' cellpadding='3'>";
		foreach($ADMIN_MASKS as $m=>$v){
			$checked = "";
			if(($rights&$v)!=0) $checked = "checked";
			$OUT.= "<tr><td><input type=\"checkbox\" name=\"mask".$v."\" value=\"".$v."\" ".$checked.">".$ADMIN_MASKS_DESCR[$m]."</td></tr>";
		}
		$OUT.= "</table>";
		return $OUT;
	}
	
	function AdminLLAccountsAdd($error = ""){
		global $ADMIN_LLA_ADD_TMP;
		$tmp = ReadTemplate($ADMIN_LLA_ADD_TMP);
		$vars = array(
			"error"=>$error,
			"rights"=>GetRighsTable()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AdminLLAccountsEdit($id){
		global $ADMIN_LLA_EDIT_TMP, $TAdminSpecialAccounts;
		$tmp = ReadTemplate($ADMIN_LLA_EDIT_TMP);
		if($tt = dbSelect($TAdminSpecialAccounts, "*", "AccountID='$id'")){
			$login = stripslashes($tt["AccountLogin"]);
			$pw = stripslashes($tt["AccountPassword"]);
			$rights = $tt["AccessRights"];
		}
		$vars = array(
			"error"=>"",
			"id"=>$id,
			"login"=>$login,
			"pw"=>$pw,
			"rights"=>GetRighsTable($rights)
		);
		return ParseTemplate($tmp, $vars);
	}
	
	
	//PAYMENT QUERY
	
	function GetPTransfers(){
		global $TMembersInfo, $TMembersTransfers, $TMembersBalance;
		global $ADMIN_PAYMENT_QUERY_PARTS_TMP, $ADMIN_PAYMENT_QUERY_PARTS_EMPTY_TMP, $ADMIN_URL;
		$OUT = "";
		if($res = dbSelectAll("$TMembersTransfers LEFT JOIN $TMembersInfo USING(MemberID) LEFT JOIN $TMembersBalance USING(MemberID)", "$TMembersTransfers.TransID, $TMembersTransfers.STATUS, $TMembersTransfers.Ammount, $TMembersTransfers.MemberID, $TMembersInfo.Name, $TMembersBalance.Balance")){
			$tmp = ReadTemplate($ADMIN_PAYMENT_QUERY_PARTS_TMP);
			while($tt = mysql_fetch_array($res)){
				if($tt["STATUS"]==0){
					$status = "unprocessed";
					$process = "<a href=\"".$ADMIN_URL."?cmd=2500&id=".$tt["TransID"]."\">process</a>";
					$ato = "";
				}elseif($tt["STATUS"]==1){
					$status = "in process";
					$process = "<a href=\"".$ADMIN_URL."?cmd=2500&id=".$tt["TransID"]."\" onclick=\"return confirm('Are You sure want to process this payment again?')\">process</a><br>";
					$ato = "<a onclick=\"AcceptBalanceTransfer(".($tt["Ammount"]/100).", '".$ADMIN_URL."?cmd=601&mid=".$tt["MemberID"]."&bb=1&tid=".$tt["TransID"]."', ".($tt["Balance"]/100).")\" href=\"#\">add to balance</a>";
				}elseif($tt["STATUS"]>=2){
					$status = "completed";
					if($tt["STATUS"]==3) $status.=" via PayPal IPN";
					$process = "";
					$ato = "";
				}
				$vars = array(
					"id"=>$tt["TransID"],
					"member"=>"<a href=\"".$ADMIN_URL."?menu=500&id=".$tt["MemberID"]."\">".stripslashes($tt["Name"])."</a>",
					"balance"=>$tt["Balance"]/100,
					"ammount"=>$tt["Ammount"]/100,
					"process"=>$process,
					"addtobalance"=>$ato,
					"status"=>$status
				);
				$OUT.=ParseTemplate($tmp, $vars);
			}
		}else{
			$OUT = ReadTemplate($ADMIN_PAYMENT_QUERY_PARTS_EMPTY_TMP);
		}
		return $OUT;
	}
	
	function AdminPaymentQuery(){
		global $ADMIN_PAYMENT_QUERY_TMP;
		$tmp = ReadTemplate($ADMIN_PAYMENT_QUERY_TMP);
		$vars = array(
			"query"=>GetPTransfers()
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function GetAllAffiliates($page, $sort, $d){
		global $TMembersAccounts, $TMembersInfo, $TASearches, $TAClicks, $ADMIN_AFFILIATES_PARTS, $ADMIN_AFFILIATES_PARTS_EMPTY;
		$OUT = "";
		$tmp = ReadTemplate($ADMIN_AFFILIATES_PARTS);
		$IPP = GetSettings("ipp");
		$T = "$TMembersAccounts LEFT JOIN $TMembersInfo USING(MemberID) LEFT JOIN $TASearches USING(MemberID) LEFT JOIN $TAClicks USING(MemberID)";
		$F = "$TMembersInfo.MemberID, $TMembersInfo.Name, count($TASearches.ASDate) searches, count($TAClicks.ACDate) clicks";
		$W = "$TMembersAccounts.STATUS=0 group by MemberID";
		if($sort==0){
			$O = "clicks, searches";
		}elseif($sort==1){
			$O = "$TMembersInfo.Name";
		}elseif($sort==2){
			$O = "searches";
		}elseif($sort==3){
			$O = "clicks";
		}
		if($d==1) $O.=" desc";
		$L = $page*$IPP.",".$IPP;
		if($res = dbSelectAll($T, $F, $W, $O, $L)){
			while($tt = mysql_fetch_array($res)){
				if($tt["MemberID"]!=""){
					$clicks = dbSelectCount($TAClicks, "MemberID=".$tt["MemberID"]);
					$searches = dbSelectCount($TASearches, "MemberID=".$tt["MemberID"]);
					$vars = array(
							"member"=>stripslashes($tt["Name"]),
							"clicks"=>$clicks,
							"searches"=>$searches,
							"cost"=>($clicks*GetSettings("affsearch") + $searches*GetSettings("affclicks"))/100
					);
					$OUT.=ParseTemplate($tmp, $vars);
				}
			}
		}else{
			$OUT = ReadTemplate($ADMIN_AFFILIATES_PARTS_EMPTY);
		}
		return $OUT;
	}
	
	function AdminGetAffiliatesMembers($page = 0, $sort = 0, $d = 0){
		global $ADMIN_AFFILIATES, $ADMIN_URL, $TMembersAccounts;
		$ipp = GetSettings("ipp");
		if($d==0){
			$d=1;
			$d1 = 0;
		}else{
			$d=0;
			$d1=1;
		}
		$count = dbSelectCount($TMembersAccounts, "STATUS=0");
		$vars = array(
			"affiliates"=>GetAllAffiliates($page, $sort, $d),
			"d"=>$d,
			"page"=>$page,
			"action"=>$ADMIN_URL,
			"pages"=>GetPagesLinks($page, $count, $ipp,  "&menu=15&sort=$sort&d=$d1")
		);
		return ParseTemplate(ReadTemplate($ADMIN_AFFILIATES), $vars);
	}
	
	function GetGMoneyQuery($page, $sort, $d){
		global $ADMIN_GET_MONEY_QUERY_PART, $ADMIN_GET_MONEY_QUERY_PART_EMPTY;
		global $TMembersAccounts, $TMembersInfo, $TMembersBalance, $TGMQuery, $ADMIN_URL;
		$tmp = ReadTemplate($ADMIN_GET_MONEY_QUERY_PART);
		$IPP = GetSettings("ipp");
		if($sort==0){
			$O = "$TMembersInfo.Name, $TGMQuery.Amount, $TMembersBalance.Balance";
		}elseif($sort==1){
			$O = "$TMembersInfo.Name";
		}elseif($sort==2){
			$O = "$TMembersBalance.Balance";
		}elseif($sort==3){
			$O = "$TGMQuery.Amount";
		}elseif($sort==3){
			$O = "$TGMQuery.PayPalAccount";
		}
		if($d==1) $O.=" desc";
		$OUT = "";
		$L = $page*$IPP.",".$IPP;
		$T = "$TGMQuery, $TMembersAccounts LEFT JOIN $TMembersInfo USING(MemberID) LEFT JOIN $TMembersBalance USING(MemberID)";
		$F = "$TGMQuery.MemberID, $TGMQuery.STATUS, $TGMQuery.TransID, $TGMQuery.Amount, $TGMQuery.PayPalAccount, $TMembersInfo.Name, $TMembersBalance.Balance";
		$Q = "$TGMQuery.MemberID=$TMembersAccounts.MemberID";
		if($res = dbSelectAll($T, $F, $Q, $O, $L)){
			while($tt = mysql_fetch_array($res)){
				if($tt["TransID"]!=""){
					if($tt["STATUS"]==0){
						$actions = "<a href=\"".$ADMIN_URL."?cmd=4000&id=".$tt["TransID"]."\">process</a>";
					}elseif($tt["STATUS"]==1){
						$actions = "<a href=\"".$ADMIN_URL."?cmd=4001&id=".$tt["TransID"]."\">finish</a>";
					}elseif($tt["STATUS"]==2){
						$actions = "completed";
					}
					$vars = array(
						"id"=>$tt["TransID"],
						"member"=>stripslashes($tt["Name"]),
						"balance"=>$tt["Balance"]/100,
						"amount"=>$tt["Amount"]/100,
						"ppa"=>stripslashes($tt["PayPalAccount"]),
						"actions"=>$actions,
					);
					$OUT.=ParseTemplate($tmp, $vars);
				}
			}
		}else{
			$OUT = ReadTemplate($ADMIN_GET_MONEY_QUERY_PART_EMPTY);
		}
		return $OUT;
	}
	
	function AdminGetMoneyQueryList($page = 0, $sort = 0, $d = 0){
		global $TGMQuery, $ADMIN_GET_MONEY_QUERY;
		$ipp = GetSettings("ipp");
		if($d==0){
			$d=1;
			$d1 = 0;
		}else{
			$d=0;
			$d1=1;
		}
		$count = dbSelectCount($TGMQuery, "STATUS=0");
		$vars = array(
			"currency"=>GetSettings("currency"),
			"query"=>GetGMoneyQuery($page, $sort, $d),
			"d"=>$d,
			"page"=>$page,
			"action"=>$ADMIN_URL,
			"pages"=>($count>1?GetPagesLinks($page, $count, $ipp,  "&menu=16&sort=$sort&d=$d1"):"")
		);
		return ParseTemplate(ReadTemplate($ADMIN_GET_MONEY_QUERY), $vars);
	}
?>