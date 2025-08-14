<?
header("Pragma: no-cache");   
?>

<? 

	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Main Search page               //
	//                                 //
	/////////////////////////////////////
	error_reporting(0);
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."config/config_main2.php");
	require($INC."functions/functions_search.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	if(!isset($cmd)||$cmd==""||$cmd==0){
	////////////////////////////////////////////////////////////////////////////////////////
	//main page
		$location = "";
		$tmp = ReadTemplate($MAIN_MAIN_SCREEN);
		$bbb = array();
		$banners = GetRandomBanners2(3,0,$bbb,1);
		$vars = array("action"=>$MAIN_URL, "members_registration"=>"../members/registration.php", "members_login"=>"../members/", "banner1"=>GetBanner($banners[0])/*, "banner2"=>GetBanner($banners[1]), "banner3"=>GetBanner($banners[2])*/);
		$tmp = ParseTemplate($tmp, $vars);
		$content = "";
		if(!isset($menu)||$menu==0||$menu==""){
			$tmp = preg_replace("/<#topsearches#>/", TopSearches(), $tmp);
			$tmp = preg_replace("/<#categories#>/", GetCategories(), $tmp);
			
			$content = "";
		}else{
			ShowError("System error", "incorrect menu item", $ADMIN_URL);
		}
		$tmp = preg_replace("/<#content#>/", $content, $tmp);
		print $tmp;
	//end of main  page
	////////////////////////////////////////////////////////////////////////////////////////
	}elseif($cmd==1){
	////////////////////////////////////////////////////////////////////////////////////////
	//Process search Query
		$location = "";
		if(isset($ss)&&$ss!=""&&!preg_match("/[%?_]/", $ss)&&AllowedKeywords($ss)){
			
			//check affiliates searches
			if(isset($member)&&$member!=0){
				if(dbSelectCount($TMembersAccounts, "STATUS=0")>0){
					session_unregister("amember");
					session_register("amember");
					$amember = $member;
					dbInsert($TASearches, "MemberID, ASDate", "'$member', CURDATE()");
				}
			}
			//end of check affiliates searches
			
			if(!isset($rpp)||$rpp==""||$rpp==0) $rpp = 10;
			if(!isset($hidesummary)||$hidesummary=="") $hidesummary = 0;
			if(!isset($page)||$page=="") $page = 0;
			
			$ppc_results = array();
			$categs_count = 0;
			$ppctype = PPCSearch($ss, $ppc_results, $categs_count);
			if(GetSettings("dmoz")!=0){
				$dmoz_res_count = GetDMOZResults($dmoz_results, $ss, $page*$rpp, $rpp+ceil($rpp/2));
				if($dmoz_res_count==""){
					$dmoz_na = "";//"<br>no results were found on this page";
				}else{
					$dmoz_na = "";
				}
			}
			if(GetSettings("msn")!=0){
				$msn_res_count = GetMSNResults($msn_results, $ss, $page*$rpp, $rpp+ceil($rpp/2));
				if($msn_res_count==""){
					$msn_na = "";//"<br>no results were found on this page";
				}else{
					$msn_na = "";
				}
			}
			$ppc_rescount = count($ppc_results) + $dmoz_res_count + $msn_res_count;
			$back = "";
			$tmp = ReadTemplate($MAIN_SRES_TMP);
			if($ppc_rescount>0){
				$content = PPCParseResults($ppctype, $ppc_results, $page, $rpp, $hidesummary, $ss);
				$total_pages = GetTotalPPCResPages($ppc_results, $rpp);
				$dmoz_pages = ceil($dmoz_res_count/$rpp);
				$msn_pages = ceil($msn_res_count/$rpp);
				$total_pages+=$dmoz_pages+$msn_pages;
				if($page>$total_pages) $page = $total_pages;
				if($total_pages>1){
					$pages_links = GetPagesLinks($page, $ppc_rescount, $rpp, "&cmd=1&ss=$ss&rpp=$rpp&hidesummary=$hidesummary");
				}else{
					$pages_links = "";
				}
				$ssumm = $ppc_rescount." site".($ppc_rescount>1?"s":"");
				if($ppctype==0){
					$ssumm.= " in ".$categs_count." categor".($categs_count>1?"ies":"y");
				}
			}else{
				$content = "<h2><br>No results were found</h2>";
				$pages_links = "";
				$ssumm = "";
			}
			$hdr = "<h3>Search results for &laquo;".$ss."&raquo;:</h3>";
			$banners = GetRandomBanners2(3, 1, $ppc_results, $ppctype);
			$vars = array("pages"=>$pages_links, "header"=>$hdr.$dmoz_na,"ss"=>$ss, "action"=>$MAIN_URL, "search_summary"=>$ssumm, "members_registration"=>"../members/registration.php", "members_login"=>"../members/", "banner1"=>GetBanner($banners[0])/*, "banner2"=>GetBanner($banners[1]), "banner3"=>GetBanner($banners[2])*/);
			$tmp = ParseTemplate($tmp, $vars);
			$tmp = preg_replace("/<#content#>/", $content, $tmp);
			print $tmp;
			
		}else{
			$location = $MAIN_URL;
		}
	//End of Process Search Query
	////////////////////////////////////////////////////////////////////////////////////////
	}elseif($cmd==200){
		if(isset($id)&&$id!=""&&$id!=0){
			$rpp = 10;
			$ppc_results = array();
			PPCCategory($id, $ppc_results);
			$ppc_rescount = count($ppc_results);
			$back = "";
			$tmp = ReadTemplate($MAIN_SRES_TMP);
			if($ppc_rescount>0){
				$content = PPCParseResults(0, $ppc_results, $page, $rpp, $hidesummary);
				$total_pages = GetTotalPPCResPages($ppc_results, $rpp);
				if($total_pages>1){
					$pages_links = GetPagesLinks($page, $ppc_rescount, $rpp, "&cmd=200&id=$id");
				}else{
					$pages_links = "";
				}
				$ssumm = $ppc_rescount." site".($ppc_rescount>1?"s":"");
			}else{
				$content = "<h2><br>No results were found</h2>";
				$pages_links = "";
				$ssumm = "";
			}
			$cname = GetCategoryName($id);
			$hdr = "<h3>Browse category &laquo;".($cname!=""?$cname:"N/A")."&raquo;:</h3>";
			$bbb = array();
			$banners = GetRandomBanners2(3,0,$bbb,1);
			$vars = array("pages"=>$pages_links, "ss"=>"", "header"=>$hdr, "action"=>$MAIN_URL, "search_summary"=>$ssumm, "members_registration"=>"../members/registration.php", "members_login"=>"../members/", "banner1"=>GetBanner($banners[0])/*, "banner2"=>GetBanner($banners[1]), "banner3"=>GetBanner($banners[2])*/);
			$tmp = ParseTemplate($tmp, $vars);
			$tmp = preg_replace("/<#content#>/", $content, $tmp);
			print $tmp;
		}else{
			$location = $MAIN_URL;
		}
	}else{
		$location = $MAIN_URL;
	}
	mysql_close();
	if($location!=""){
		header("Location: $location");
		exit;
	}
?>