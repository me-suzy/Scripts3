<?
header("Pragma: no-cache");   
?>
<? 
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."config/config_member.php");
	require($INC."functions/functions_bu_and_reports.php");
	
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	if(!isset($ll)||$ll==""||$ll==0){
	if(session_is_registered("member_id")&&session_is_registered("member_login")&&session_is_registered("member_pw")){
		if(dbSelectCount($TMembersAccounts, "MemberID=$member_id and MemberLogin='".addslashes($member_login)."' and MemberPassword='".$member_pw."'")>0){
			if(!isset($cmd)||$cmd==""||$cmd==0){
			//main control panel page
				$location = "";
				$tmp = ReadTemplate($MEMBER_CP_TMP);
				$vars = array(
					"action"=>$MEMBER_CP_URL,
					"menu_item0"=>$MEMBER_CP_URL."?menu=0",
					"menu_item1"=>$MEMBER_CP_URL."?menu=1",
					"menu_item2"=>$MEMBER_CP_URL."?menu=2",
					"menu_item3"=>$MEMBER_CP_URL."?menu=3",
					"menu_item4"=>$MEMBER_CP_URL."?menu=4",
					"menu_item5"=>$MEMBER_CP_URL."?menu=5",
					"menu_item6"=>$MEMBER_CP_URL."?menu=6",
					"menu_item7"=>$MEMBER_CP_URL."?menu=7",
					"menu_item8"=>$MEMBER_CP_URL."?menu=8",
					"menu_item9"=>$MEMBER_CP_URL."?menu=9",
					"menu_item10"=>$MEMBER_CP_URL."?menu=10",
					"logoff"=>$MEMBER_CP_URL."?cmd=16384",
					"username"=>GetUserName($member_id)
				);
				$tmp = ParseTemplate($tmp, $vars);
				$content = "";
				
				if(!isset($menu)||$menu==""||$menu==0){
					$content = MemberCPMain($member_id);
				}elseif($menu==1){
					if(!isset($sdate)||$sdate==""){
						$tt = dbSelect($TMembersAccounts, "CreateDate", "MemberID=$member_id");
						$sdate = $tt["CreateDate"];
					}
					if(!isset($edate)||$edate==""){
						$edate = date("Y-m-d");
					}
					if(!isset($sort)||$sort=="") $sort = 0;
					if(!isset($d)||$d=="") $d = 0;
					$content = MemberCPStatistics($member_id, $sdate, $edate, $sort, $d);
				}elseif($menu==2){
					$content = MemberCPModifyAccount($member_id, urldecode($moderr));
				}elseif($menu==3){
					if(!isset($page)||$page=="") $page = 0;
					$content = MemberCPManageListings($member_id, $page, $wb);
				}elseif($menu==4){
					$content = MemberCPUpdateBalance($member_id, urldecode($mess));
				}elseif($menu==4001){
					$content = ReadTemplate($MEMBER_COMPLETE_TRANSFER);
				}elseif($menu==4002){
					$content = GetMoneyFromAccount($member_id, $warning);
				}elseif($menu==4003){
					$content = ReadTemplate($MEMBER_COMPLETE_GETMONEY);
				}elseif($menu==5){
					$content = MemberCPUpdateBids($member_id);
				}elseif($menu==6){
					$content = MemberCPBannersStat($member_id);
				}elseif($menu==7){
					$content = MemberCPBanners($member_id);
				}elseif($menu==8){
					$content = MemberCPNoMatchesStatistics($member_id);
				}elseif($menu==9){
					$content = MemberCPSSearch();
				}elseif($menu==10){
					if(!isset($sdate)){
						$sdate = date("Y-m-d");
					}
					if(!isset($edate)){
						$edate = date("Y-m-d");
					}
					if($edate<$sdate){
						$edate = $sdate;
					}
					$content = MemberAffiliate($member_id, $sdate, $edate);
				}elseif($menu==11){
				//printing html code for affiliates
					$content = GetHTMLACode($member_id);
				}elseif($menu==12){
				//get aff. money
					$content = GetAMoney($member_id);
				}elseif($menu==1005){
				//getmoney success
					$tt = dbSelect($TMembersBalance, "Balance", "MemberID=$member_id");
					$bb = $tt["Balance"]/100;
					$vars = array(
						"balance"=>$bb
					);
					$content = ParseTemplate(ReadTemplate($MEMBER_GET_AFF_MONEY_TO_BALANCE_COMPLETE_TMP), $vars);
				}elseif($menu==900){
					$content = MemberCPSSearchResults($term);
				}elseif($menu==255){
				//add new term form
					if(isset($term)&&$term!="") $term = urldecode($term);
					$content = MemberCPAddTerm($member_id, $term, $bid, urldecode($moderr));
				//add new term form
				}elseif($menu==2551){
				//add bulk of terms
					$content = MemberCPAddBulk($member_id, urldecode($moderr));
				}elseif($menu==258){
				//'no matches' bid form
					$content = MemberCPNoMatches($member_id, urldecode($moderr));
				//end of 'no matches' bid form
				}elseif($menu==355){
				//add new banner
					$content = MemberCPAddBanner($member_id, urldecode($moderr));
				}elseif($menu==356){
				//edit banner
					if(!isset($id)||$id=="") $id=0;
					$content = MemberCPEditBanner($id, urldecode($moderr));
				}elseif($menu==256){
				//edit term
					if(!isset($id)||$id=="") $id=0;
					$content = MemberCPEditTerm($id, urldecode($moderr));
				//edit term
				}elseif($menu==257){
					$content = MemberCPEditCC($member_id, urldecode($moderr));
				}else{
					ShowError("System error", "incorrect menu item", $MEMBER_LOGIN_URL);
				}
				$tmp = preg_replace("/<#content#>/", $content, $tmp);
				print $tmp;
			//end of main control panel page
			}elseif($cmd==1){
			//modify account settings
				$error = "";
				if(!isset($name)||$name=="") $error.="Missing name!<br>";
				if(!isset($email)||$email=="") $error.="Missing email!<br>";
				else{
					if(!preg_match("/^\w+\@\w+(?:\.\w+)+$/",$email)) $error.="Email is incorrect!<br>";
				}
				if(!isset($street)||$street=="") $error.="Missing street!<br>";
				if(!isset($city)||$city=="") $error.="Missing city!<br>";
				if(!isset($country)||$country=="") $error.="Missing country!<br>";
				if(!isset($zip)||$zip=="") $error.="Missing zip!<br>";
				else{
					if(preg_match("/[^0-9]/",$zip)) $error.="Zip is incorrect!<br>";
				}
				if(!isset($title)||$title=="") $error.="Missing site title!<br>";
				if(!isset($url)||$url=="") $error.="Missing site url!<br>";
				else{
					$url = preg_replace("/^http:\/\//i", "", $url);
				}
				if(!isset($category)||$category==""||$category==0){
					$error.="Missing Category!<br>";
					$category = 0;
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=2&moderr=".urlencode($error);
				}else{
					
					$logofile = $HTTP_POST_FILES['logo']['tmp_name'];
					$logofilename = basename($HTTP_POST_FILES['logo']['name']);
					if(file_exists($logofile)){
						if(dbSelectCount($TMembersLogos, "MemberID=$member_id")>0){
							if($tt4 = dbSelect($TMembersLogos, "LogoURL", "MemberID=$member_id")){
								$lurl = $LOGOS_DIR.stripslashes($tt4["LogoURL"]);
								if(file_exists($lurl)) @unlink($lurl);
									dbDelete($TMembersLogos, "MemberID=$member_id");
							}
						}
						$logourl = 	md5(microtime()).$logofilename;
						move_uploaded_file($logofile, $LOGOS_DIR.$logourl);
						if($logourl!="") dbInsert($TMembersLogos, "MemberID, LogoURL", "$member_id, '".addslashes($logourl)."'");
					}
					
					$Q = "Name = '".addslashes($name)."', ";
					$Q.= "Company = '".addslashes($company)."', ";
					$Q.= "EMail = '".addslashes($email)."', ";
					$Q.= "Street = '".addslashes($street)."', ";
					$Q.= "City = '".addslashes($city)."', ";
					$Q.= "Zip = '".addslashes($zip)."', ";
					$Q.= "Country = '".addslashes($country)."', ";
					$Q.= "Phone = '".addslashes($phone)."', ";
					$Q.= "State = '".addslashes($state)."'";
					dbUpdate($TMembersInfo, $Q, "MemberID=$member_id");
					$Q = "Title = '".addslashes($title)."', ";
					$Q.= "Descr = '".addslashes($descr)."', ";
					$Q.= "Link = '".addslashes($url)."', ";
					$Q.= "CategoryID = '".$category."'";
					#<fix author="kovalsky">
					if (dbSelectCount($TMembersSites, "MemberID=$member_id") > 0)
						dbUpdate($TMembersSites, $Q, "MemberID=$member_id");
					else
						dbInsert($TMembersSites, "MemberID,Title,Descr,Link,CategoryID", "$member_id,'$title','$descr','$url','$category'");
					#<end fix>
					$location = $MEMBER_CP_URL."?menu=2";
				}
			//end of modify account settings
			}elseif($cmd==2){
			//add new term form processing
				$error = "";
				if(!isset($term)||$term=="") $error.="Missing Term!<br>";
				if(!isset($title)||$title=="") $error.="Missing Title!<br>";
				if(!isset($url)||$url=="") $error.="Missing Link URL!<br>";
				else{
					$url = preg_replace("/^http:\/\//i", "", $url);
				}
				if(!isset($bid)||$bid=="") $error.="Missing Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $bid)) $error.="Bid is incorrect!<br>";
					if($tt = dbSelect($TAdminSettings)){
						$minbid = $tt["MinBid"];
					}else{
						$minbid = 0;
					}
					$bid*=100;
					if($bid<$minbid) $error.="Bid is too small!<br>";
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=255&moderr=".urlencode($error);
				}else{
					$logofile = $HTTP_POST_FILES['logo']['tmp_name'];
					$logofilename = basename($HTTP_POST_FILES['logo']['name']);
					$logourl = "";
					if(file_exists($logofile)){
						$logourl = 	md5(microtime()).$logofilename;
						move_uploaded_file($logofile, $LOGOS_DIR.$logourl);
					}
					if(GetSettings("acceptbids")>0){
						$F = "TermID, MemberID, Term, Title, Link, Descr, LLogoURL";
						$Q = "null, $member_id, '".addslashes($term)."', '".addslashes($title)."', '".addslashes($url)."', '".addslashes($descr)."', '".addslashes($logourl)."'";
						if(dbSelectCount($TMembersTerms, "MemberID=$member_id and Term like '%".addslashes($term)."%'")==0){
							$tid = dbInsert($TMembersTerms, $F, $Q);
							if($tid>0){
								dbInsert($TMembersBids, "TermID, Bid", "$tid, $bid");
							}
						}
						$location = $MEMBER_CP_URL."?menu=3";
					}else{
						$F = "MemberID, Term, Title, Link, Descr, Bid, TYPE, LLogoURL";
						$Q = "$member_id, '".addslashes($term)."', '".addslashes($title)."', '".addslashes($url)."', '".addslashes($descr)."', '$bid', 0, '".addslashes($logourl)."'";
						if(dbSelectCount($TMembersTerms, "MemberID=$member_id and Term like '%".addslashes($term)."%'")==0){
							$tid = dbInsert($TTempTermsBids, $F, $Q);
						}
						$location = $MEMBER_CP_URL."?menu=3&wb=".urlencode("Your bid will be added to waiting list.");
					}
				}
			//end of add new term form processing
			}elseif($cmd==2551){
			//add new bulk of terms form processing
				for($i=0; $i<$BULK_SIZE; $i++){

					$term = $HTTP_POST_VARS["term".$i];
					$title = $HTTP_POST_VARS["title".$i];
					$url = $HTTP_POST_VARS["url".$i];
					$bid = $HTTP_POST_VARS["bid".$i];
					$descr = $HTTP_POST_VARS["descr".$i];
					
//					print $term."<hr>".$title."<hr>".$url."<hr>".$bid."<hr>--<hr>";
					
					$error = "";
					if(!isset($term)||$term=="") $error.="Missing Term!<br>";
					if(!isset($title)||$title=="") $error.="Missing Title!<br>";
					if(!isset($url)||$url=="") $error.="Missing Link URL!<br>";
					else{
						$url = preg_replace("/^http:\/\//i", "", $url);
					}
					if(!isset($bid)||$bid=="") $error.="Missing Bid!<br>";
					else{
						if(!preg_match("/^\d+(?:\.\d\d?)?$/", $bid)) $error.="Bid is incorrect!<br>";
						if($tt = dbSelect($TAdminSettings)){
							$minbid = $tt["MinBid"];
						}else{
							$minbid = 0;
						}
						$bid*=100;
						if($bid<$minbid) $error.="Bid is too small!<br>";
					}
//					print "ERROR: ".$error."!<hr>";
					if($error==""){
						if(GetSettings("acceptbids")>0){
							$addon = 0;
							$F = "TermID, MemberID, Term, Title, Link, Descr";
							$Q = "null, $member_id, '".addslashes($term)."', '".addslashes($title)."', '".addslashes($url)."', '".addslashes($descr)."'";
							if(dbSelectCount($TMembersTerms, "MemberID=$member_id and Term like '%".addslashes($term)."%'")==0){
								$tid = dbInsert($TMembersTerms, $F, $Q);
								if($tid>0){
									dbInsert($TMembersBids, "TermID, Bid", "$tid, $bid");
								}	
							}
						}else{
							$F = "MemberID, Term, Title, Link, Descr, Bid, TYPE";
							$Q = "$member_id, '".addslashes($term)."', '".addslashes($title)."', '".addslashes($url)."', '".addslashes($descr)."', '$bid', 0";
							if(dbSelectCount($TMembersTerms, "MemberID=$member_id and Term like '%".addslashes($term)."%'")==0){
								$tid = dbInsert($TTempTermsBids, $F, $Q);
							}
							$addon = "&wb=".urlencode("Your bids will be added to wait list");
						}
					}
				}
				$location = $MEMBER_CP_URL."?menu=3".$addon;
			//end of add bulk of term form processing
			}elseif($cmd==200){
			//add new banner form processing
				$error = "";
				$bannerfile = $HTTP_POST_FILES['banner']['tmp_name'];
				$bannerfilename = basename($HTTP_POST_FILES['banner']['name']);
				if(!isset($banner)||$banner==""||!file_exists($bannerfile)) $error.="Missing Banner file!<br>";
				if($tt = dbSelect($TAdminSettings)){
					$minbid = $tt["MinBid"];
				}else{
					$minbid = 0;
				}
				if(!isset($sbid)||$sbid=="") $error.="Missing Show Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $sbid)) $error.="Show Bid is incorrect!<br>";
					$sbid*=100;
					if($sbid<$minbid) $error.="Show Bid is too small!<br>";
				}
				if(!isset($cbid)||$cbid=="") $error.="Missing Click Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $cbid)) $error.="Click Bid is incorrect!<br>";
					$cbid*=100;
					if($cbid<$minbid) $error.="Click Bid is too small!<br>";
				}
				$bterms = array();
				$tterms = array();
				if($res = dbSelectAll($TMembersTerms, "TermID", "MemberID=$member_id")){
					while($tt = mysql_fetch_array($res)){
						array_push($bterms, $tt["TermID"]);
					}
				}
				if(count($bterms)>0){
					foreach($bterms as $termid){
						if(isset($HTTP_POST_VARS["term".$termid])&&$HTTP_POST_VARS["term".$termid]!=""&&$HTTP_POST_VARS["term".$termid]!=0){
							array_push($tterms, $termid);
						}
					}
				}else{
					$error.="You must add terms first!<br>";
				}
				if(count($tterms)==0){
					$error.="There are no specified terms for banner";
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=355&moderr=".urlencode($error);
				}else{
					$bannerurl = 	md5(microtime()).$bannerfilename;
					move_uploaded_file($bannerfile, $BANNERS_DIR.$bannerurl);
						$F = "BannerID, MemberID, BannerURL, BannerAlt, STATUS";
						$Q = "null, $member_id, '".addslashes($bannerurl)."', '".addslashes($alt)."', 0";
						$bid = dbInsert($TBanners, $F, $Q);
						if($bid>0){
								dbInsert($TBannersBids, "BannerID, ShowBid, ClickBid", "$bid, '$sbid', '$cbid'");
								foreach($tterms as $termid){
									dbInsert($TBannersTerms, "BannerID, TermID", "$bid, $termid");
								}
						}
					$location = $MEMBER_CP_URL."?menu=7";
				}
			//end of add new banner form processing
			}elseif($cmd==201){
			//update banner
				$error = "";
				$bannerfile = $HTTP_POST_FILES['banner']['tmp_name'];
				$bannerfilename = basename($HTTP_POST_FILES['banner']['name']);
				if($tt = dbSelect($TAdminSettings)){
					$minbid = $tt["MinBid"];
				}else{
					$minbid = 0;
				}
				if(!isset($sbid)||$sbid=="") $error.="Missing Show Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $sbid)) $error.="Show Bid is incorrect!<br>";
					$sbid*=100;
					if($sbid<$minbid) $error.="Show Bid is too small!<br>";
				}
				if(!isset($cbid)||$cbid=="") $error.="Missing Click Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $cbid)) $error.="Click Bid is incorrect!<br>";
					$cbid*=100;
					if($cbid<$minbid) $error.="Click Bid is too small!<br>";
				}
				
				$bterms = array();
				$tterms = array();
				if($res = dbSelectAll($TMembersTerms, "TermID", "MemberID=$member_id")){
					while($tt = mysql_fetch_array($res)){
						array_push($bterms, $tt["TermID"]);
					}
				}
				if(count($bterms)>0){
					foreach($bterms as $termid){
						if(isset($HTTP_POST_VARS["term".$termid])&&$HTTP_POST_VARS["term".$termid]!=""&&$HTTP_POST_VARS["term".$termid]!=0){
							array_push($tterms, $termid);
						}
					}
				}else{
					$error.="You must add terms first!<br>";
				}
				if(count($tterms)==0){
					$error.="There are no specified terms for banner";
				}
				
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=355&moderr=".urlencode($error);
				}else{
					$tt = dbSelect($TBanners, "BannerURL", "BannerID=".$bannerid);
					$oldbanner = stripslashes($tt["BannerURL"]);
					if($bannerfile!=""&&file_exists($bannerfile)){
						unlink($BANNERS_DIR.$oldbanner);
						$bannerurl = 	md5(microtime()).$bannerfilename;
						move_uploaded_file($bannerfile, $BANNERS_DIR.$bannerurl);
					}else{
						$bannerurl = $oldbanner;
					}
					$Q = "BannerURL = '".addslashes($bannerurl)."', BannerAlt='".addslashes($alt)."'";
					dbUpdate($TBanners, $Q, "BannerID=".$bannerid);
					dbUpdate($TBannersBids, "ShowBid='$sbid', ClickBid='$cbid'", "BannerID=".$bannerid);
					dbDelete($TBannersTerms, "BannerID=$bannerid");
					foreach($tterms as $termid){
						dbInsert($TBannersTerms, "BannerID, TermID", "$bannerid, $termid");
					}
					$location = $MEMBER_CP_URL."?menu=7";
				}
			//end of update banner
			}elseif($cmd==3){
			//deleting terms
				$location = $MEMBER_CP_URL."?menu=3";
				if($tt = dbSelect($TMembersTerms, "min(TermID), max(TermID)", "MemberID=$member_id")){
					$minid = $tt["min(TermID)"];
					$maxid = $tt["max(TermID)"];
				}else{
					$minid=0;
					$maxid=0;
				}
				if($minid>0&&$maxid>0){
					for($i=$minid; $i<=$maxid; $i++){
						if(isset($HTTP_POST_VARS["elem".$i])){
							if($ll = dbSelect($TMembersTerms, "LLogoURL", "TermID=$id")){
								if($ll["LLogoURL"]!=""){
									@unlink($LOGOS_DIR.$ll["LLogoURL"]);
								}
							}
							dbDelete($TMembersTerms, "TermID=$i");
							dbDelete($TMembersBids, "TermID=$i");
							dbDelete($TMembersClicks, "TermID=$i");
							dbDelete($TBannersTerms, "TermID=$i");
						}
					}
				}
			//end of deleting terms
			}elseif($cmd==355){
			//deleting banners
				$location = $MEMBER_CP_URL."?menu=7";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=$member_id")){
					$minid = $tt["min(BannerID)"];
					$maxid = $tt["max(BannerID)"];
				}else{
					$minid=0;
					$maxid=0;
				}
				if($minid>0&&$maxid>0){
					for($i=$minid; $i<=$maxid; $i++){
						if(isset($HTTP_POST_VARS["elem".$i])){
							$tt = dbSelect($TBanners, "BannerURL", "BannerID=$i");
							unlink($BANNERS_DIR.$tt["BannerURL"]);
							dbDelete($TBanners, "BannerID=$i");
							dbDelete($TBannersBids, "BannerID=$i");
							dbDelete($TBannersShows, "BannerID=$i");
							dbDelete($TBannersClicks, "BannerID=$i");
							dbDelete($TBannersTerms, "BannerID=$i");
						}
					}
				}
			//end of deleting banners
			}elseif($cmd==356){
			//activate banners
				$location = $MEMBER_CP_URL."?menu=7";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=$member_id")){
					$minid = $tt["min(BannerID)"];
					$maxid = $tt["max(BannerID)"];
				}else{
					$minid=0;
					$maxid=0;
				}
				if($minid>0&&$maxid>0){
					for($i=$minid; $i<=$maxid; $i++){
						if(isset($HTTP_POST_VARS["elem".$i])){
							$tt = dbSelect($TBanners, "BannerURL", "BannerID=$i");
							dbUpdate($TBanners, "STATUS=0", "BannerID=$i");
						}
					}
				}
			//end of activate banners
			}elseif($cmd==357){
			//deactivate banners
				$location = $MEMBER_CP_URL."?menu=7";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=$member_id")){
					$minid = $tt["min(BannerID)"];
					$maxid = $tt["max(BannerID)"];
				}else{
					$minid=0;
					$maxid=0;
				}
				if($minid>0&&$maxid>0){
					for($i=$minid; $i<=$maxid; $i++){
						if(isset($HTTP_POST_VARS["elem".$i])){
							$tt = dbSelect($TBanners, "BannerURL", "BannerID=$i");
							dbUpdate($TBanners, "STATUS=1", "BannerID=$i");
						}
					}
				}
			//end of deactivate banners
			}elseif($cmd==4){
			//update term record
				$error = "";
				if(!isset($id)||$id==""||$id==0) $error.="Missing Term ID!<br>";
				if(!isset($term)||$term=="") $error.="Missing Term!<br>";
				if(!isset($title)||$title=="") $error.="Missing Title!<br>";
				if(!isset($url)||$url=="") $error.="Missing Link URL!<br>";
				else{
					$url = preg_replace("/^http:\/\//i", "", $url);
				}
				if(!isset($bid)||$bid=="") $error.="Missing Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $bid)) $error.="Bid is incorrect!<br>";
					if($tt = dbSelect($TAdminSettings)){
						$minbid = $tt["MinBid"];
					}else{
						$minbid = 0;
					}
					$bid*=100;
					if($bid<$minbid) $error.="Bid is too small!<br>";
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=256&id=$id&moderr=".urlencode($error);
				}else{
					
					$logourl = "";
					$logofile = $HTTP_POST_FILES['logo']['tmp_name'];
					$logofilename = basename($HTTP_POST_FILES['logo']['name']);
					if(file_exists($logofile)){
						if($tt4 = dbSelect($TMembersTerms, "LLogoURL", "TermID=$id")){
							$lurl = $LOGOS_DIR.stripslashes($tt4["LLogoURL"]);
							if(file_exists($lurl)) @unlink($lurl);
							$logourl = 	md5($logofilename.rand()).$logofilename;
							move_uploaded_file($logofile, $LOGOS_DIR.$logourl);
							dbUpdate($TMembersTerms, "LLogoURL='".addslashes($logourl)."'", "TermID=$id");
						}
					}
					
					$Q = "Term='".addslashes($term)."', Title='".addslashes($title)."', Link='".addslashes($url)."', Descr='".addslashes($descr)."'";
					if(dbSelectCount($TMembersTerms, "TermID=$id")>0){
						$tid = dbUpdate($TMembersTerms, $Q, "TermID=$id");
						if($tid>0){
							dbUpdate($TMembersBids, "Bid=$bid", "TermID=$id");
						}
					}
					$location = $MEMBER_CP_URL."?menu=256&id=$id";
				}
			//end of update term record
			}elseif($cmd==5){
				$error = "";
				$location = "";
				if(!isset($ppa)||$ppa=="") $error.="Missing Pay Pal account!<br>";
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=257&moderr=".urlencode($error);
				}else{
					$Q = "PayPalAccount='".addslashes($ppa)."'";
					dbUpdate($TMembersCC, $Q, "MemberID=$member_id");
					$location = $MEMBER_CP_URL."?menu=4";
				}
/*
				if(!isset($ccn)||$ccn=="") $error.="Missing Credit card number!<br>";
				if(!isset($name)||$name=="") $error.="Missing name!<br>";
				if(!isset($expires)||$expires=="") $error.="Missing Expiration date!<br>";
				else{
					if(!preg_match("/^\d\d\d\d-[01]\d-[0-3]\d$/",$expires)) $error.="Expiration date is incorrect!<br>";
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=257&moderr=".urlencode($error);
				}else{
					$Q = "CCNumber='".addslashes($ccn)."', CCName='".addslashes($name)."', CCExpires='".$expires."'";
					dbUpdate($TMembersCC, $Q, "MemberID=$member_id");
					$location = $MEMBER_CP_URL."?menu=4";
				}
*/
			}elseif($cmd==6){
				$location = $MEMBER_CP_URL."?menu=5";
				$T = "$TMembersBids,$TMembersTerms";
				$F = "max($TMembersBids.Bid)";
				$Q = "$TMembersTerms.MemberID=$member_id and $TMembersBids.TermID=$TMembersTerms.TermID";
				if($tt = dbSelect($T, $F, $Q)){
					$maxbid = $tt["max($TMembersBids.Bid)"];
					if($res = dbSelectAll($T, "$TMembersTerms.TermID", $Q)){
						while($tt = mysql_fetch_array($res)){
							dbUpdate($TMembersBids, "Bid='$maxbid'", "TermID=".$tt["TermID"]);
						}
					}
				}
			}elseif($cmd==7){
				$location = $MEMBER_CP_URL."?menu=5";
				$T = "$TMembersTerms";
				$F = "max(TermID)";
				$Q = "MemberID=$member_id";
				$maxid = 0;
				$minid = 0;
				if($tt = dbSelect($T, $F, $Q)){
					$maxid = $tt[$F];
				}
				$F = "min(TermID)";
				if($tt = dbSelect($T, $F, $Q)){
					$minid = $tt[$F];
				}
				$minbid = 0;
				if($tt = dbSelect($TAdminSettings, "MinBid")){
					$minbid = $tt["MinBid"];
				}
				if($minid>0&&$maxid>0&&$minbid>0){
					for($i = $minid; $i<=$maxid; $i++){
						if(isset($HTTP_POST_VARS["bid".$i])){
							$bid = $HTTP_POST_VARS["bid".$i];
							if((preg_match("/^\d+(?:\.\d\d?)?$/", $bid))&&($minbid<=(100*$bid))) dbUpdate($TMembersBids, "Bid='".(int)(100*$bid)."'", "TermID=$i");
						}
					}
				}
			}elseif($cmd==8){
			//change bids to minimal
				$location = $MEMBER_CP_URL."?menu=5";
				$T = "$TMembersBids,$TMembersTerms";
				$F = "min($TMembersBids.Bid)";
				$Q = "$TMembersTerms.MemberID=$member_id and $TMembersBids.TermID=$TMembersTerms.TermID";
				if($tt = dbSelect($T, $F, $Q)){
					$minbid = $tt["min($TMembersBids.Bid)"];
					if($res = dbSelectAll($T, "$TMembersTerms.TermID", $Q)){
						while($tt = mysql_fetch_array($res)){
							dbUpdate($TMembersBids, "Bid='$minbid'", "TermID=".$tt["TermID"]);
						}
					}
				}
			}elseif($cmd==9){
			//change bids to average
				$location = $MEMBER_CP_URL."?menu=5";
				$T = "$TMembersBids,$TMembersTerms";
				$F = "avg($TMembersBids.Bid)";
				$Q = "$TMembersTerms.MemberID=$member_id and $TMembersBids.TermID=$TMembersTerms.TermID group by $TMembersBids.TermID";
				if($tt = dbSelect($T, $F, $Q)){
					$avgbid = $tt["avg($TMembersBids.Bid)"];
//					print $avgbid."<hr>";
					if($res = dbSelectAll($T, "$TMembersTerms.TermID", $Q)){
						while($tt = mysql_fetch_array($res)){
							dbUpdate($TMembersBids, "Bid='".(int)($avgbid)."'", "TermID=".$tt["TermID"]);
						}
					}
				}
			}elseif($cmd==255){
			//updating member balance from credit card
				$location = $MEMBERS_CP_URL."?menu=4";
				if(preg_match("/^\d+(\.\d\d)?$/", $amount)){
					$amount = $amount*100;
					$tid = dbInsert($TMembersTransfers, "TransID, MemberID, Ammount, STATUS", "null, $member_id, $amount, 0");
					$location = PayPalDonationsSendForm($member_id, $tid, $amount/100);
					//print $location."<hr>";
				}
			//end balance update
			}elseif($cmd==258){
			//set bid on no matches
				$error = "";
				if(!isset($title)||$title=="") $error.="Missing Title!<br>";
				if(!isset($url)||$url=="") $error.="Missing Link URL!<br>";
				else{
					$url = preg_replace("/^http:\/\//i", "", $url);
				}
				if(!isset($bid)||$bid=="") $error.="Missing Bid!<br>";
				else{
					if(!preg_match("/^\d+(?:\.\d\d?)?$/", $bid)) $error.="Bid is incorrect!<br>";
					$minbid = GetSettings("nmbid");
					$bid*=100;
					if($bid<$minbid) $error.="Bid is too small!<br>";
				}
				if($error!=""){
					$location = $MEMBER_CP_URL."?menu=258&moderr=".urlencode($error);
				}else{
						$F = "MemberID, Bid, Title, Link, Descr";
						$Q = "$member_id, $bid, '".addslashes($title)."', '".addslashes($url)."', '".addslashes($descr)."'";
						if(dbSelectCount($TNoMatchesBids, "MemberID=$member_id")==0){
							dbInsert($TNoMatchesBids, $F, $Q);
						}else{
							dbUpdate($TNoMatchesBids, "Bid=$bid, Title='".addslashes($title)."', Link='".addslashes($url)."', Descr='".addslashes($descr)."'", "MemberID=$member_id");
						}
						$location = $MEMBER_CP_URL."?menu=3";
				}
			//end of set bid on no matches
			}elseif($cmd==1002){
			//clear affiliate stats
				$location = $MEMBER_CP_URL."?menu=10";
				dbDelete($TASearches, "MemberID=$member_id");
				dbDelete($TAClicks, "MemberID=$member_id");
			}elseif($cmd==1003){
			//generate aff. report
		//generating stats report
				$REPORT = AffStatsReport($member_id);
				header("Content-type: application/bin");
		        header("Content-Disposition: attachment; filename=ASPPC_Affiliate_Stats_Report_".date("Ymd").".txt");
				print $REPORT;
				exit;
			}elseif($cmd==1005){
				$location = $MEMBERS_CP_URL."?menu=1005";
				$total = AffGetTotalMoney($member_id);
				dbUpdate($TMembersBalance, "Balance=Balance+".($total*100), "MemberID=$member_id");
				dbDelete($TASearches, "MemberID=$member_id");
				dbDelete($TAClicks, "MemberID=$member_id");
			}elseif($cmd==4002){
				//processing get money from account
				$location = $MEMBERS_CP_URL."?menu=4002&warning=".urlencode("You must specify amount and PayPal account!");
				if(preg_match("/^\d+(\.\d\d)?$/", $amount)&&$amount!=0&&$ppa!=""){
					if($tt = dbSelect($TMembersBalance, "Balance", "MemberID=$member_id")){
						$na = $amount*100;
						if($na<=abs($tt["Balance"] - GetSettings("minbal"))){
							dbInsert($TGMQuery, "TransID,MemberID,PayPalAccount, Amount, STATUS", "null, $member_id, '".addslashes($ppa)."', '$na', 0");
							$location = $MEMBERS_CP_URL."?menu=4003";
						}else{
							$location = $MEMBERS_CP_URL."?menu=4002&warning=".urlencode("Your balance is less than amount that You wants to get!");
						}
					}
				}
			}elseif($cmd==16384){
				$location = $MEMBER_CP_URL;
				unset($member_id);
				unset($member_login);
				unset($member_pw);
				session_unregister("member_id");
				session_unregister("member_login");
				session_unregister("member_pw");
			}else{
				$location = $MEMBER_CP_URL;
			}
		}else{
			ShowError("Login error", "Your login or password is incorrect", $MEMBER_LOGIN_URL);
		}
	}else{
		$location = $MEMBER_LOGIN_URL;
	}
	}else{
		if($ll==1){
		//login page
			$location = "";
			$tmp = ReadTemplate($MEMBER_LOGIN_TMP);
			$tmp = preg_replace("/<#action#>/", $MEMBER_CP_URL, $tmp);
			print $tmp;
		//end of login page
		}elseif($ll==255){
			//send forgotten password
			$location = $MEMBER_CP_URL."?ll=1";
			if(isset($login)&&$login!=""){
				SendPassword($login);
			}
		}else{
			if(isset($lop)&&$lop!=""&&isset($pw)&&$pw!=""){
				$cpw = md5($pw);
				if(dbSelectCount($TMembersAccounts, "MemberLogin='".addslashes($lop)."' and MemberPassword='".$cpw."'")>0){
					$tt = dbSelect($TMembersAccounts, "MemberID", "MemberLogin='".addslashes($lop)."' and MemberPassword='".$cpw."'");
					session_unregister("member_id");
					session_unregister("member_login");
					session_unregister("member_pw");
					session_register("member_id");
					session_register("member_login");
					session_register("member_pw");
					$member_id = $tt["MemberID"];
					$member_login = $lop;
					$member_pw = $cpw;
					$location = $MEMBER_CP_URL;
				}else{
					ShowError("Login error", "Your login or password is incorrect", $MEMBER_LOGIN_URL);
				}
			}else{
				ShowError("Login error", "Your login or password is incorrect", $MEMBER_LOGIN_URL);
			}
		}
	}
	mysql_close();
	if($location!=""){
	ob_start();
		header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/$location");
		ob_end_flush();
		exit;
	}
?>