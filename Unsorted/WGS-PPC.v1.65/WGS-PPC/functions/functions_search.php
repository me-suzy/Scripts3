<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Search engine functions        //
	//                                 //
	/////////////////////////////////////
	
	//Returns array of banners' ids randomly selected from DB
/*	function GetRandomBanners($mode = 0){
		global $TBanners;
		srand();
		$selected = array("0");
		$banners = array();
		$count = 0;
		$lbound = 1;
		$ubound = 2;
		if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "STATUS=0")){
			$lbound = $tt["min(BannerID)"];
			$ubound = $tt["max(BannerID)"];
		}
		while($count<3){
			$randid = rand($lbound, $ubound);
			if($tt = dbSelect($TBanners, "BannerID", "STATUS=0 and BannerID=$randid and BannerID not in(".join(",",$selected).")")){
				$count++;
				array_push($banners, $tt["BannerID"]);
				array_push($selected, $tt["BannerID"]);
			}
		}
		return $banners;
	}
*/

	//Input: search string in ( -keyword means that results must not contains 'keyword' )
	//Output: sorted array of IDs
	function GetTermsIDs(&$terms_ids, $ss){
		global $TMembersTerms;
		$Q = "TermID>0";
		foreach($ss as $kw){
			if($kw!=""){
				if(!preg_match("/^-(.*)$/", $kw)){
					$kw = preg_replace("/^\s*?[+]/","",$kw);
					$Q.= " and ( Term LIKE '%".$kw."%') ";
				}else{
					$kw = preg_replace("/^-/", "", $kw);
					$Q.= " and ( Term NOT LIKE '%".$kw."%') ";
				}
			}
		}
		if($res = dbSelectAll($TMembersTerms, "TermID, Term", $Q)){
			while($tt = mysql_fetch_array($res)){
				array_push($terms_ids, $tt["TermID"]);
			}
		}
	}

	function SortTermsIDsByBids(){
	}
	
	function GetRandomBanners2($bcount, $mode, &$results, $type){
		global $TBanners, $TBannersTerms, $TMembersTerms;
		list($oo, $oo2) = microtime();
		mt_srand($oo);
		$all = array();
		$selected = array();
		$banners = array();
		$count = 0;
		if($mode==0||$type!=0){
			$bmode = "$TBanners.MemberID=0 and ";
			$T2 = "";
		}elseif($mode==1){
			$T2 = ", $TBannersTerms";
			$bmode = "$TBanners.MemberID!=0 and $TBannersTerms.TermID in (".join(",",$results).") and ";
			
		}else{
			$bmode = "";
		}
		if($res = dbSelectAll($TBanners.$T2, "$TBanners.BannerID", $bmode."$TBanners.STATUS=0")){
			while($tt = mysql_fetch_array($res)){
				if($tt["BannerID"]!="") array_push($all, $tt["BannerID"]);
			}
		}
		$maxind = count($all)-1;
		if($bcount>($maxind+1)) $bcount = $maxind+1;
		if($maxind>0){
			while($count<$bcount){
				$rnd = mt_rand(0, $maxind);
				if(!ArrayContains($selected, $rnd)){
//					print $rnd."-".$all[$rnd]."<hr>";
					array_push($selected, $rnd);
					array_push($banners, $all[$rnd]);
					$count++;
				}
			}
		}elseif($maxind==0){
			array_push($banners, $all[0]);
		}elseif($maxind<0){
			if($tt = dbSelect($TBanners, "BannerID", "MemberID=0 and STATUS=0")){
				array_push($banners, $tt["BannerID"]);
			}
		}
		return $banners;
	}
	
	function GetSubcategories($id){
		global $TCategories, $MAIN_URL;
		$OUT = array();
		if($res = dbSelectAll($TCategories, "CategoryID, Name", "Parent=$id", "Name")){
			while($tt = mysql_fetch_array($res)){
				array_push($OUT, "<a href=\"".$MAIN_URL."?cmd=200&id=".$tt["CategoryID"]."\">".stripslashes($tt["Name"])."</a>");
			}
		}
		return join(", ", $OUT);
	}
	
	function SitesInCategory($id){
		global $TMembersAccounts, $TMembersSites, $TMembersTerms;
		$out = 0;
		$minbal = GetSettings("minbal");
		if($res = dbSelectAll("$TMembersAccounts, $TMembersSites", "distinct $TMembersAccounts.MemberID", "$TMembersAccounts.STATUS=0 and $TMembersSites.MemberID=$TMembersAccounts.MemberID and $TMembersSites.CategoryID=$id")){
			while($tt = mysql_fetch_array($res)){
				if(GetMemberBalance($tt["MemberID"])>$minbal&&dbSelectCount($TMembersTerms, "MemberID=".$tt["MemberID"])>0) $out++;
			}
		}
		return $out;
	}
	
	function GetCategories(){
		global $MAIN_CATEGORIESLIST_TMP, $MAIN_CATEGORIESLIST_PARTS_TMP, $MAIN_CATEGORIESLIST_PARTS2_TMP, $CATEGORIES_COLUMNS, $MAIN_URL;
		global $TCategories;
		$tmp = ReadTemplate($MAIN_CATEGORIESLIST_TMP);
		$tmp2 = ReadTemplate($MAIN_CATEGORIESLIST_PARTS_TMP);
		$tmp3 = ReadTemplate($MAIN_CATEGORIESLIST_PARTS2_TMP);
		$parts = "";
		if($res = dbSelectAll($TCategories, "CategoryID, Name", "Parent=0", "Name")){
			$total_c = dbSelectCount($TCategories, "Parent=0");
			$rows = ceil($total_c/$CATEGORIES_COLUMNS);
			for($i = 1; $i<=$rows; $i++){
				$part = "";
				for($j = 1; $j<=$CATEGORIES_COLUMNS; $j++){
					$tt = mysql_fetch_array($res);
					if($tt["CategoryID"]!=""){
						$vars3 = array(
							"category"=>stripslashes($tt["Name"])." (".SitesInCategory($tt["CategoryID"]).")",
							"categorylink"=>$MAIN_URL."?cmd=200&id=".$tt["CategoryID"],
							"subcategories"=>GetSubcategories($tt["CategoryID"])
						);
						$part.=ParseTemplate($tmp3, $vars3);
					}
				}
				$vars2 = array(
					"part"=>$part
				);
				$parts.=ParseTemplate($tmp2, $vars2);
			}
		}
		$vars = array(
			"parts"=>$parts
		);
		return ParseTemplate($tmp, $vars);
	}
	
	//Displays Top Searches
	function TopSearches(){
		global $MAIN_TOP_SEARCHES, $MAIN_TOP_SEARCHES_PARTS, $MAIN_TOP_SEARCHES_PARTS_EMPTY;
		global $TSearchStatistics, $MAIN_URL;
		$tmp = "";
		$tmp = ReadTemplate($MAIN_TOP_SEARCHES);
		$tmp2 = ReadTemplate($MAIN_TOP_SEARCHES_PARTS);
		$top_num = GetSettings("topnum");
		$T = $TSearchStatistics;
		$F = "Term, count(Term) as tcount";
		$Q = "1=1 group by Term";
		$O = "tcount desc";
		$parts = "";
		if($res = dbSelectAll($T, $F, $Q, $O, "0,".$top_num)){
			$counter = 1;
			while($tt = mysql_fetch_array($res)){
				$vars2 = array(
					"term"=>stripslashes($tt["Term"]),
					"counter"=>$counter,
					"searchlink"=>$MAIN_URL."?cmd=1&ss=".urlencode($tt["Term"])
				);
				$parts.=ParseTemplate($tmp2, $vars2);
				$counter++;
			}
		}else{
			$parts = ReadTemplate($MAIN_TOP_SEARCHES_PARTS_EMPTY);
		}
		$vars = array(
			"parts"=>$parts
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function AllowedKeywords($ss){
		global $TFiltered;
		$ss = preg_replace("/[-+.,;!'\"()]/"," ",$ss);
		$kwds = explode(" ", $ss);
		$flag = "1";
		$filtered = array();
		if($res = dbSelectAll($TFiltered, "Keyword")){
			while($tt = mysql_fetch_array($res)){
				array_push($filtered, stripslashes($tt["Keyword"]));
			}
		}
		foreach($kwds as $kw){
			if(ArrayContains($filtered, $kw)){
				$flag = "";
				break;
			}
		}
		return $flag;
	}
	
	function GetMemberBalance($mid){
		global $TMembersBalance;
		$out = 0;
		if($tt = dbSelect($TMembersBalance, "Balance", "MemberID=$mid")){
			$out = $tt["Balance"];
		}
		return $out;
	}
	
	//main search function
	//input: $ss - search string
	//OUTPUT:
	//$results - target array - contains all found TermID
	//$categs_count - number of categories of founded Terms
	function PPCSearch($ss, &$results, &$categs_count){
		global $TMembersAccounts, $TSearchStatistics, $TMembersTerms, $TMembersBids, $TMembersSites, $TNoMatchesBids;
		dbInsert($TSearchStatistics, "Term, SearchDate", "'".addslashes($ss)."', CURDATE()");
		$T = "$TMembersAccounts, $TMembersTerms, $TMembersBids, $TMembersSites";
		$F = "$TMembersAccounts.MemberID, $TMembersAccounts.STATUS, $TMembersTerms.TermID, $TMembersSites.CategoryID, max($TMembersBids.Bid) as MBid";
		$W = " and $TMembersAccounts.MemberID=$TMembersTerms.MemberID and $TMembersAccounts.STATUS=0 and $TMembersSites.MemberID=$TMembersTerms.MemberID and $TMembersBids.TermID=$TMembersTerms.TermID group by $TMembersTerms.MemberID";
		$O = "MBid desc";
		$kwds = explode(" ", $ss);
		$Q = "$TMembersTerms.TermID>0 ";
		$tmp = array();
		foreach($kwds as $kw){
			if($kw!=""){
				if(!preg_match("/^-(.*)$/", $kw)){
					$kw = preg_replace("/^\s*?[+]/","",$kw);
					$Q.= " and ( '".$kw."' like CONCAT('%',$TMembersTerms.Term,'%') OR  CONCAT('%',$TMembersTerms.Term,'%') like '%".$kw."%')";
				}else{
					$kw = preg_replace("/^-/", "", $kw);
					$Q.= " and ( '".$kw."' not like CONCAT('%',$TMembersTerms.Term,'%') AND CONCAT('%',$TMembersTerms.Term,'%') not like '%".$kw."%')";
				}
			}
		}
		$minbal = GetSettings("minbal");
		if($res = dbSelectAll($T, $F, $Q.$W, $O)){
			while($tt = mysql_fetch_array($res)){
				if(GetMemberBalance($tt["MemberID"])>$minbal){
					array_push($results, $tt["TermID"]);
					if(!ArrayContains($tmp, $tt["CategoryID"])) array_push($tmp, $tt["CategoryID"]);
				}
			}
		}
		$categs_count = count($tmp);
		$type = 0;
		if(count($results)==0){
			$type = 1;
			if($res = dbSelectAll($TNoMatchesBids.", $TMembersAccounts", "$TNoMatchesBids.MemberID", "$TMembersAccounts.STATUS=0 and $TNoMatchesBids.MemberID=$TMembersAccounts.MemberID")){
				while($tt = mysql_fetch_array($res)){
					if(GetMemberBalance($tt["MemberID"])>$minbal){
						array_push($results, $tt["MemberID"]);
					}
				}
			}
		}
		return $type;
	}
	
	function GetCategoryNameByMember($mid){
		global $TCategories, $TMembersSites;
		$OUT = "";
		if($tt = dbSelect("$TCategories, $TMembersSites", "$TCategories.Name", "$TMembersSites.MemberID=$mid and $TCategories.CategoryID=$TMembersSites.CategoryID")){
			$OUT = stripslashes($tt["Name"]);
		}
		return $OUT;
	}
	
	function GetCategoryIDByMember($mid){
		global $TMembersSites;
		$OUT = "";
		if($tt = dbSelect($TMembersSites, "CategoryID", "MemberID=$mid")){
			$OUT = stripslashes($tt["CategoryID"]);
		}
		return $OUT;
	}
	
	function GetSiteLink($mid){
		global $TMembersSites;
		$OUT = "";
		if($tt = dbSelect($TMembersSites, "Link", "MemberID=$mid")){
			$OUT = stripslashes($tt["Link"]);
		}
		return $OUT;
	}
	
	function PPCParseRecord($type, $id, $counter, $page, $rpp, $hs, $ss){
		global $PPC_RESULTS_PART, $TMembersTerms, $CLICK_SCRIPT, $CLICK_TARGET, $MAIN_URL, $TNoMatchesBids;
		$tmp = ReadTemplate($PPC_RESULTS_PART);
		if($type==0){
			$Table = $TMembersTerms;
			$nID = "TermID";
			$ll = dbSelect($TMembersTerms, "LLogoURL", "TermID=$id");
		}else{
			$llogo = GetLogo($id);
			$Table = $TNoMatchesBids;
			$nID = "MemberID";
		}
		$tt = dbSelect($Table, "MemberID, Title, Descr", "$nID=$id");
		if($type==0){
			if($ll["LLogoURL"]==""){
				$llogo = GetLogo($tt["MemberID"]);
			}else{
				$llogo = GetLogo($id,1);
			}
		}
		$vars = array(
//				"logo"=>"<a href=\"".$CLICK_SCRIPT."?tid=$id".($type==0?"":"&nm=1")."&ss=".$ss."\" target=\"".$CLICK_TARGET."\">".$llogo."</a>",
				"logo"=>"<a href=\"".$CLICK_SCRIPT."?query=".($type==0?"0":"1").substr(md5($tt["Title"].rand()),1,30).$id."&ss=$ss\" target=\"".$CLICK_TARGET."\">".$llogo."</a>",
				"counter"=>$counter,
				"id"=>$id,
				"title"=>"<a href=\"".$CLICK_SCRIPT."?query=".($type==0?"0":"1").substr(md5($tt["Title"].rand()),1,30).$id."&ss=$ss\" target=\"".$CLICK_TARGET."\">".stripslashes($tt["Title"])."</a>",
				"summary"=>($hs==0?stripslashes($tt["Descr"]):""),
				"link"=>GetSiteLink($tt["MemberID"]),
				"category"=>"<a href=\"".$MAIN_URL."?cmd=200&id=".GetCategoryIDByMember($tt["MemberID"])."\">".GetCategoryNameByMember($tt["MemberID"])."</a>"
		);
		return ParseTemplate($tmp, $vars);
	}
	
	function PPCParseResults($type, &$results, $page, $rpp, $hs, $ss = ""){
		global $dmoz_results, $msn_results;
		$start = $page*$rpp;
		$end = ($page+1)*$rpp;
		$dstart = 1;
		$dend = $rpp;
		$counter = $start;
		$OUT = "";
		$icount = 0;
		if($start<count($results)) for($i=$start; $i<$end; $i++){
			if($results[$i]!=""){
				$counter++;
				$icount++;
				$OUT.=PPCParseRecord($type, $results[$i], $counter, $page, $rpp, $hs, $ss);
			}
		}
		if(GetSettings("msn")!=0||GetSettings("dmoz")!=0)
		while($icount<$rpp){
			if($icount<$rpp){
				$dstart = 1; $dend = $rpp - $icount+1;
				if($dstart<count($msn_results)) for($i=$dstart; $i<$dend; $i++){
					if($msn_results[$i]["title"]!=""){
						$counter++;
						$icount++;
						$OUT.=PPCParseRecord_External($type, $msn_results[$i], $counter, $page, $rpp, $hs, $ss);	
					}
				}
			}
			if($icount<$rpp){
				$dstart = 1; $dend = $rpp - $icount+1;
				if($dstart<count($dmoz_results)) for($i=$dstart; $i<$dend; $i++){
					if($dmoz_results[$i]["title"]!=""){
						$counter++;
						$icount++;
						$OUT.=PPCParseRecord_External($type, $dmoz_results[$i], $counter, $page, $rpp, $hs, $ss);
					}
				}
			}
			$icount++;
		}
		return $OUT;
	}
	function GetTotalPPCResPages(&$results, $rpp){
		return ceil(count($results)/$rpp);
	}
	
	function GetCategoryName($id){
		global $TCategories;
		$OUT = "";
		if($tt = dbSelect($TCategories, "Name", "CategoryID=$id")){
			$OUT = stripslashes($tt["Name"]);
		}
		return $OUT;
	}
	
	function PPCCategory($id, &$results){
		global $TMembersSites, $TMembersTerms, $TMembersBids;
		$T = "$TMembersSites LEFT JOIN $TMembersTerms USING(MemberID) LEFT JOIN $TMembersBids USING(TermID)";
		$F = "$TMembersTerms.MemberID, $TMembersTerms.TermID";
		$Q = "$TMembersSites.CategoryID=$id group by $TMembersSites.MemberID";
		$O = "$TMembersBids.Bid desc";
		$minbal = GetSettings("minbal");
		if($res = dbSelectAll($T, $F, $Q, $O)){
			while($tt = mysql_fetch_array($res)){
				//if($tt["MemberID"]!="") if(GetMemberBalance($tt["MemberID"])>$minbal&&$tt["TermID"]!="") 
				array_push($results, $tt["TermID"]);
			}
		}
	}
?>
