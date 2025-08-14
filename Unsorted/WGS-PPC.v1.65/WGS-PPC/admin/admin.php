<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Administrators control panel   //
	//                                 //
	/////////////////////////////////////
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."config/config_admin.php");
	require($INC."functions/functions_bu_and_reports.php");
	require($INC."config/dbstructure.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	if(session_is_registered("alogin")&&session_is_registered("apw")){
		if($alogin!=$ADMIN_LOGIN||$apw!=$ADMIN_PW){
			$rights = 0;
			if($res = dbSelectAll($TAdminSpecialAccounts)){
				while($tt = mysql_fetch_array($res)){
					if($alogin==stripslashes($tt["AccountLogin"])&&$apw==stripslashes($tt["AccountPassword"])){
						$rights = $tt["AccessRights"];
					}
				}
			}
			if($rights==0){
				header("Location: index.php?err=".urlencode("Login or Password is incorrect!"));
				exit;
			}
		}else{
			$rights = 255;
		}
	}else{
		header("Location: index.php?err=".urlencode("Login or Password is incorrect!"));
		exit;
	}
	$IPP = GetSettings("ipp");
	if(!isset($cmd)||$cmd==""||$cmd==0){
			//main control panel page
				$location = "";
				$tmp = ReadTemplate($ADMIN_MAIN_TMP);
				$vars = array(
					"action"=>$ADMIN_URL,
					"menu_item0"=>$ADMIN_URL."?menu=0",
					"menu0"=>"",
					"\/menu0"=>""
				);
				$vars["logoff"] = "?cmd=65535";
				if($rights==255){
					$vars["account"] = "";
					$vars["menu_item1"] = $ADMIN_URL."?menu=1";
					$vars["menu_item2"] = $ADMIN_URL."?menu=2";
					$vars["menu_item3"] = $ADMIN_URL."?menu=3";
					$vars["menu_item4"] = $ADMIN_URL."?menu=4";
					$vars["menu_item5"] = $ADMIN_URL."?menu=5";
					$vars["menu_item6"] = $ADMIN_URL."?menu=6";
					$vars["menu_item8"] = $ADMIN_URL."?menu=8";
					$vars["menu_item12"] = $ADMIN_URL."?menu=10";
					$vars["menu_item16"] = $ADMIN_URL."?menu=16";
					
					$vars["menu1"] = "";
					$vars["\/menu1"] = "";
					
					$vars["menu2"] = "";
					$vars["\/menu2"] = "";
					
					$vars["menu3"] = "";
					$vars["\/menu3"] = "";
					
					$vars["menu4"] = "";
					$vars["\/menu4"] = "";
					
					$vars["menu5"] = "";
					$vars["\/menu5"] = "";
					
					$vars["menu6"] = "";
					$vars["\/menu6"] = "";
					
					$vars["menu8"] = "";
					$vars["\/menu8"] = "";
					
					$vars["menu12"] = "";
					$vars["\/menu12"] = "";
					
					$vars["menu16"] = "";
					$vars["\/menu16"] = "";

					
				}else{
					$vars["account"] = "<b>Low-level account:</b> ".$alogin;
					$vars["menu_item1"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu1#>.*<#\\/menu1#>/","", $tmp);
					
					$vars["menu_item2"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu2#>.*<#\\/menu2#>/","", $tmp);
					
					$vars["menu_item3"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu3#>.*<#\\/menu3#>/","", $tmp);
					
					$vars["menu_item4"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu4#>.*<#\\/menu4#>/","", $tmp);
					
					$vars["menu_item5"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu5#>.*<#\\/menu5#>/","", $tmp);
					
					$vars["menu_item6"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu6#>.*<#\\/menu6#>/","", $tmp);
					
					$vars["menu_item8"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu8#>.*<#\\/menu8#>/","", $tmp);
					
					$vars["menu_item12"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu12#>.*<#\\/menu12#>/","", $tmp);
					
				}
				
				if(($rights&$ADMIN_MASKS["ADMIN_MASK_PAY"])!=0){
					$vars["menu_item13"] = $ADMIN_URL."?menu=13";
					$vars["menu13"] = "";
					$vars["\/menu13"] = "";
				}else{
					$vars["menu_item13"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu13#>.*<#\\/menu13#>/","", $tmp);
				}
				
				if(($rights&$ADMIN_MASKS["ADMIN_MASK_WB"])!=0){
					$vars["menu_item9"] = $ADMIN_URL."?menu=9";
					$vars["menu9"] = "";
					$vars["\/menu9"] = "";
				}else{
					$vars["menu_item9"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu9#>.*<#\\/menu9#>/","", $tmp);
				}
				
				if(($rights&$ADMIN_MASKS["ADMIN_MASK_FILTER"])!=0){
					$vars["menu_item7"] = $ADMIN_URL."?menu=7";
					$vars["menu7"] = "";
					$vars["\/menu7"] = "";
				}else{
					$vars["menu_item7"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu7#>.*<#\\/menu7#>/","", $tmp);
				}
				
				if(($rights&$ADMIN_MASKS["ADMIN_MASK_AM"])!=0){
					$vars["menu_item15"] = $ADMIN_URL."?menu=15";
					$vars["menu15"] = "";
					$vars["\/menu15"] = "";
				}else{
					$vars["menu_item15"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu15#>.*<#\\/menu15#>/","", $tmp);
				}
				
				if(($rights&$ADMIN_MASKS["ADMIN_MASK_BU"])!=0){
					$vars["menu_item10"] = $ADMIN_URL."?cmd=9999";
					$vars["menu_item11"] = $ADMIN_URL."?cmd=7777";
					$vars["menu_item14"] = $ADMIN_URL."?menu=7778";
					$vars["menu10"] = "";
					$vars["\/menu10"] = "";
					$vars["menu11"] = "";
					$vars["\/menu11"] = "";
					$vars["menu14"] = "";
					$vars["\/menu14"] = "";
				}else{
					$vars["menu_item10"] = $ADMIN_URL."?menu=65535";
					$vars["menu_item11"] = $ADMIN_URL."?menu=65535";
					$vars["menu_item14"] = $ADMIN_URL."?menu=65535";
					$tmp = preg_replace("/<#menu10#>.*<#\\/menu10#>/","", $tmp);
					$tmp = preg_replace("/<#menu11#>.*<#\\/menu11#>/","", $tmp);
					$tmp = preg_replace("/<#menu14#>.*<#\\/menu14#>/","", $tmp);
				}

				$tmp = ParseTemplate($tmp, $vars);
				$content = "";
				if(!isset($menu)||$menu==""||$menu==0){
					$content = AdminMain();
				}elseif($menu==1){
					$content = AdminCategories();
				}elseif($menu==2){
					if(!isset($page)||$page==""||preg_match("/[^0-9]/",$page)) $page = 0;
					if(!isset($sort)||$sort==""||preg_match("/[^0-9]/",$sort)) $sort = 0;
					if(!isset($d)||$d==""||preg_match("/[^0-9]/",$d)) $d = 0;
					$content = AdminMembers($page, $sort, $d);
				}elseif($menu==3){
					$content = AdminSettings($error = urldecode($moderr));
				}elseif($menu==4){
					if(!isset($page)||$page==""||preg_match("/[^0-9]/",$page)) $page = 0;
					if(!isset($sort)||$sort==""||preg_match("/[^0-9]/",$sort)) $sort = 0;
					if(!isset($d)||$d==""||preg_match("/[^0-9]/",$d)) $d = 0;
					if(!isset($stype)) $stype=0;
					if(!isset($sdate)||$sdate=="") $sdate = date("Y-m-d");
					$content = AdminStatisticsMain($stype, $sort, $page, $d, $sdate);
				}elseif($menu==5){
					dbDelete($TSearchStatistics);
					header("Location: $ADMIN_URL");
					exit;
				}elseif($menu==6){
					if(!isset($member)||$member==""||preg_match("/[^0-9]/",$member)){
						if($tt = dbSelect($TBanners, "min(MemberID)")){
							$member = $tt["min(MemberID)"];
						}else $member = 0;
					}
					if(!isset($page)||$page==""||preg_match("/[^0-9]/",$page)) $page = 0;
					if(!isset($sort)||$sort==""||preg_match("/[^0-9]/",$sort)) $sort = 0;
					if(!isset($d)||$d==""||preg_match("/[^0-9]/",$d)) $d = 0;
					$content = AdminStatisticsBanners($member, $sort, $page, $d);
				}elseif($menu==7){
					$content = AdminFilter();
				}elseif($menu==8){
					$content = AdminBanners();
				}elseif($menu==9){
					$content = AdminWaitingBids();
				}elseif($menu==10){
				//Spec.Accounts
					$content = AdminLLAccountsList();
				}elseif($menu==11){
				//Add Spec.Accounts
					$content = AdminLLAccountsAdd();
				}elseif($menu==12){
				//Edit Spec.Accounts
					$content = AdminLLAccountsEdit($id);
				}elseif($menu==13){
				//Edit Spec.Accounts
					$content = AdminPaymentQuery();
				}elseif($menu==15){
				//affiliates listings
					if(!isset($page)) $page = 0;
					if(!isset($sort)) $sort = 0;
					if(!isset($d)) $d = 0;
					$content = AdminGetAffiliatesMembers($page, $sort, $d);
				}elseif($menu==16){
					//get money query list
					if(!isset($page)) $page = 0;
					if(!isset($sort)) $sort = 0;
					if(!isset($d)) $d = 0;
					$content = AdminGetMoneyQueryList($page, $sort, $d);
				}elseif($menu==355){
				//add new banner
					$content = AdminAddBanner(urldecode($moderr));
				}elseif($menu==356){
				//edit banner
					if(!isset($id)||$id=="") $id=0;
					$content = AdminEditBanner($id, urldecode($moderr));
				}elseif($menu==255){
					$content = AdminAddCategory();
				}elseif($menu==500){
					$content = AdminMemberModifyAccount($id, urldecode($moderr));
				}elseif($menu==5000){
					if(preg_match("/^\d+$/",$id)&&$id!=0){
						$content = AdminMemberListings($id);
					}else{
						$content = "<h2>Error: there is no such member</h2>";
					}
				}elseif($menu==600){
					$content = AdminSendEmail($id);
				}elseif($menu==601){
					//view/change member balance
					$content = AdminMemberBalance($id);
				}elseif($menu==700){
					if($ids=="all"){
						$content = AdminSendEmail(0);
					}else{
						$ids = explode(",",urldecode($ids));
						$content = AdminSendEmail($ids);
					}
				}elseif($menu==7778){
					$content = DBRestoreForm();
				}elseif($menu==7779){
					$content = ReadTemplate($ADMIN_RESTORE_DB_FAILED);
				}elseif($menu==7770){
					$content = ReadTemplate($ADMIN_RESTORE_DB_OK);
				}elseif($menu==65535){
					$content = ReadTemplate($ADMIN_BLOCKED_TMP);
				}else{
					ShowError("System error", "incorrect menu item", $ADMIN_URL);
				}
				$tmp = preg_replace("/<#content#>/", $content, $tmp);
				print $tmp;
			//end of main control panel page
	}elseif($cmd==1){
	//adding new category
		$location = $ADMIN_URL."?menu=1";
		if(!isset($cname)||$cname==""||!isset($category)||$category==""){
			$location = $ADMIN_URL."?menu=255";
		}else{
			$name = addslashes(htmlspecialchars($cname));
			if(dbSelectCount($TCategories, "Parent=$category and Name='$name'")==0) dbInsert($TCategories, "CategoryID, Parent, Name", "null, '".$category."', '".$name."'");
			else $location = $ADMIN_URL."?menu=255";
		}
	}elseif($cmd==2){
		$location = $ADMIN_URL."?menu=1";
		if($tt = dbSelect($TCategories, "min(CategoryID), max(CategoryID)")){
			$minid = $tt["min(CategoryID)"];
			$maxid = $tt["max(CategoryID)"];
		}else{
			$minid=0;
			$maxid=0;
		}
		if($minid>0&&$maxid>0){
			for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["categ".$i])){
					dbDelete($TCategories, "CategoryID=$i");
				}
			}
		}
	}elseif($cmd==3){
	//Locking member
		$location = $ADMIN_URL."?menu=2";
		if($tt = dbSelect($TMembersAccounts, "min(MemberID), max(MemberID)")){
			$minid = $tt["min(MemberID)"];
			$maxid = $tt["max(MemberID)"];
		}else{
			$minid=0;
			$maxid=0;
		}
		if($minid>0&&$maxid>0){
			for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["member".$i])){
					dbUpdate($TMembersAccounts, "STATUS=1", "MemberID=$i");
					if($ee = dbSelect($TMembersInfo, "EMail", "MemberID=$i")){
						SendMessage($ee["EMail"], 3);
					}
				}
			}
		}
	}elseif($cmd==4){
	//UNLocking member
		$location = $ADMIN_URL."?menu=2";
		if($tt = dbSelect($TMembersAccounts, "min(MemberID), max(MemberID)")){
			$minid = $tt["min(MemberID)"];
			$maxid = $tt["max(MemberID)"];
		}else{
			$minid=0;
			$maxid=0;
		}
		if($minid>0&&$maxid>0){
			for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["member".$i])){
					dbUpdate($TMembersAccounts, "STATUS=0", "MemberID=$i");
				}
			}
		}
	}elseif($cmd==5){
	//deleting member
		$location = $ADMIN_URL."?menu=2";
		if($tt = dbSelect($TMembersAccounts, "min(MemberID), max(MemberID)")){
			$minid = $tt["min(MemberID)"];
			$maxid = $tt["max(MemberID)"];
		}else{
			$minid=0;
			$maxid=0;
		}
		if($minid>0&&$maxid>0){
			for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["member".$i])){
					dbDelete($TMembersAccounts, "MemberID=$i");
					dbDelete($TMembersInfo, "MemberID=$i");
					dbDelete($TMembersBalance, "MemberID=$i");
					dbDelete($TMembersCC, "MemberID=$i");
					dbDelete($TMembersSites, "MemberID=$i");
					dbDelete($TNoMatchesBids, "MemberID=$i");
					dbDelete($TASearches, "MemberID=$i");
					dbDelete($TAClicks, "MemberID=$i");
					dbDelete($TMembersTransfers, "MemberID=$i");
					dbDelete($TTempTermsBids, "MemberID=$i");
					if($tt4 = dbSelect($TMembersLogos, "LogoURL", "MemberID=$i")){
						$lurl = $LOGOS_DIR.stripslashes($tt4["LogoURL"]);
						if(file_exists($lurl)) @unlink($lurl);
						dbDelete($TMembersLogos, "MemberID=$i");
					}
					if($res = dbSelectAll($TMembersTerms, "TermID, LLogoURL", "MemberID=$i")){
						while($tt = mysql_fetch_array($res)){
							if($tt["LLogoURL"]!="") @unlink($LOGOS_DIR.$tt["LLogoURL"]);
							dbDelete($TMembersClicks, "TermID=".$tt["TermID"]."");
							dbDelete($TMembersBids, "TermID=".$tt["TermID"]."");
						}
					}
					dbDelete($TMembersTerms, "MemberID=$i");
					if($res = dbSelectAll($TBanners, "BannerID", "MemberID=$i")){
						while($tt = mysql_fetch_array($res)){
							dbDelete($TBannersBids, "BannerID=".$tt["BannerID"]);
							dbDelete($TBannersClicks, "BannerID=".$tt["BannerID"]);
							dbDelete($TBannersShows, "BannerID=".$tt["BannerID"]);
						}
						dbDelete($TBanners, "MemberID=$i");
					}
					if($ee = dbSelect($TMembersInfo, "EMail", "MemberID=$i")){
						SendMessage($ee["EMail"], 4);
					}
				}
			}
		}
	}elseif($cmd==6){
			//modify member account settings
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
					$location = $ADMIN_URL."?menu=500&id=$member_id&moderr=".urlencode($error);
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
					dbUpdate($TMembersSites, $Q, "MemberID=$member_id");
					$location = $ADMIN_URL."?menu=500&id=$member_id";
				}
			//end of modify member account settings
	}elseif($cmd==7){
	//saving settings
		$location = "";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $minbid)) $error.="Minimal Bid is incorrect!<br>";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $nmminbid)) $error.="&quot;No matches&quot; Minimal Bid is incorrect!<br>";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $bonus)) $error.="Bonus is incorrect!<br>";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $minbal)) $error.="Minimal Balance is incorrect!<br>";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $ascost)) $error.="Affiliates searches cost is incorrect!<br>";
		if(!preg_match("/^\d+(?:\.\d\d?)?$/", $accost)) $error.="Affiliates clicks cost is incorrect!<br>";
		if(preg_match("/[><]/", $symbol)) $error.="Currncy Symbol is incorrect!<br>";
		if(!preg_match("/^\d+$/", $dipp)) $error.="Items per page number is incorrect!<br>";
		if(!preg_match("/^\d+$/", $topnum)) $error.="Top items number is incorrect!<br>";
		if(!isset($accbids)||$accbids==""||$accbids==0) $accbids = 0;
		if(!isset($allowipn)||$allowipn==""||$allowipn==0) $allowipn = 0;
		if($error==""){
			//saving backup:
			$minbid*=100;
			$nmminbid*=100;
			$minbal*=100;
			$bonus*=100;
			$ascost*=100;
			$accost*=100;
			$F = "MinBid, MinNoMatchesBid, Porog, CurrencySymbol, IPP, TopPosNumber, AcceptBids, AllowSS, ACOCost, ACLCost, AllowIPN, APPEMail, AllowMSN, AllowDMOZ";
			$V = "'$minbid', '$nmminbid', '$minbal', '".addslashes($symbol)."', '$dipp', '$topnum', '$accbids', '$allowss', '$ascost', '$accost', '$allowipn', '".addslashes($appemail)."', '$allowmsn', '$allowdmoz'";
			dbDelete($TAdminSettingsBU);
			dbDelete($TAdminMembersBonusBU);
			$pbonus = 0;
			$pminbid = "1";
			$pminbal = "500";
			$psymb = "\\$";
			$ptopnum = 5;
			$pipp = 10;
			if($tt = dbSelect($TAdminSettings)){
				$pminbal = $tt["Porog"];
				$pminbid = $tt["MinBid"];
				$psymb = stripslashes($tt["CurrencySymbol"]);
				$ptopnum = $tt["TopPosNumber"];
				$pipp = $tt["IPP"];
				$paccbids = $tt["AcceptBids"];
				$pallowss = $tt["AllowSS"];
				$pascost = $tt["ACOCost"];
				$paccost = $tt["ACLCost"];
				$pallowipn = $tt["AllowIPN"];
				$pappemail = stripslashes($tt["APPEMail"]);
				$pallowmsn = $tt["AllowMSN"];
				$pallowdmoz = $tt["AllowDMOZ"];
			}
			if($tt = dbSelect($TAdminMembersBonus)){
				$pbonus = $tt["Bonus"];
			}
			$PV = "'$pminbid', '$nmminbid', '$pminbal', '".addslashes($psymb)."', '$pipp', '$ptopnum', '$paccbids', '$pallowss', '$pascost', '$paccost', '$pallowipn', '".addslashes($pappemail)."', '$pallowmsn', '$pallowdmoz'";
			dbInsert($TAdminSettingsBU, $F, $PV);
			dbInsert($TAdminMembersBonusBU, "Bonus", "'$pbonus'");
			//updating current settings:
			dbDelete($TAdminSettings);
			dbDelete($TAdminMembersBonus);
			dbInsert($TAdminSettings, $F, $V);
			dbInsert($TAdminMembersBonus, "Bonus", "'$bonus'");
			$location = $ADMIN_URL."?menu=3&moderr=".urlencode("Update completed");
		}else{
			$location = $ADMIN_URL."?menu=3&moderr=".urlencode($error);
		}
	}elseif($cmd==8){
	//restoring settings
		$location = "";
		//saving backup:
		$F = "MinBid, MinNoMatchesBid, Porog, CurrencySymbol, IPP, TopPosNumber, AcceptBids, AllowSS, ACOCost, ACLCost, AllowIPN, APPEMail, AllowMSN, AllowDMOZ";
		$bonus = 0;
		$minbid = "1";
		$minbal = "500";
		$topnum = 5;
		$ipp = 10;
		if($tt = dbSelect($TAdminSettingsBU)){
			$minbal = $tt["Porog"];
			$nmminbid = $tt["MinNoMatchesBid"];
			$minbid = $tt["MinBid"];
			$symb = stripslashes($tt["CurrencySymbol"]);
			$topnum = $tt["TopPosNumber"];
			$ipp = $tt["IPP"];
			$accbids = $tt["AcceptBids"];
			$allowss = $tt["AllowSS"];
			$ascost = $tt["ACOCost"];
			$accost = $tt["ACLCost"];
			$allowipn = $tt["AllowIPN"];
			$allowmsn = $tt["AllowMSN"];
			$allowdmoz = $tt["AllowDMOZ"];
			$appemail = stripslashes($tt["APPEMail"]);
		}
		if($tt = dbSelect($TAdminMembersBonusBU)){
			$bonus = $tt["Bonus"];
		}
		$V = "'$minbid', '$nmminbid', '$minbal', '".addslashes($symbol)."', '$dipp', '$topnum', '$accbids', '$allowss', '$ascost', '$accost', '$allowipn', '".addslashes($appemail)."', '$allowmsn', '$allowdmoz'";
		$pbonus = 0;
		$pminbid = "1";
		$pminbal = "500";
		$psymb = "\\$";
		$ptopnum = 5;
		$pipp = 10;
		if($tt = dbSelect($TAdminSettings)){
			$pminbal = $tt["Porog"];
			$pminbid = $tt["MinBid"];
			$psymb = stripslashes($tt["CurrencySymbol"]);
			$ptopnum = $tt["TopPosNumber"];
			$pipp = $tt["IPP"];
			$paccbids = $tt["AcceptBids"];
			$pallowss = $tt["AllowSS"];
			$pascost = $tt["ACOCost"];
			$paccost = $tt["ACLCost"];
			$pallowipn = $tt["AllowIPN"];
			$pallowmsn = $tt["AllowMSN"];
			$pallodmoz = $tt["AllowDMOZ"];
			$pappemail = stripslashes($tt["APPEMail"]);
		}
		if($tt = dbSelect($TAdminMembersBonus)){
			$pbonus = $tt["Bonus"];
		}
		dbDelete($TAdminSettingsBU);
		dbDelete($TAdminMembersBonusBU);
		dbDelete($TAdminSettings);
		dbDelete($TAdminMembersBonus);
		$PV = "'$pminbid', '$nmminbid', '$pminbal', '".addslashes($psymb)."', '$pipp', '$ptopnum', '$paccbids', '$pallowss', '$pascost', '$paccost', '$pallowipn', '".addslashes($pappemail)."', '$pallowmsn', '$pallowdmoz'";
		dbInsert($TAdminSettingsBU, $F, $PV);
		dbInsert($TAdminMembersBonusBU, "Bonus", "'$pbonus'");
		//updating current settings:
		dbInsert($TAdminSettings, $F, $V);
		dbInsert($TAdminMembersBonus, "Bonus", "'$bonus'");
		$location = $ADMIN_URL."?menu=3";
	}elseif($cmd==9){
		$aids = array();
		if($tt = dbSelect($TMembersAccounts, "min(MemberID), max(MemberID)")){
			$minid = $tt["min(MemberID)"];
			$maxid = $tt["max(MemberID)"];
		}else{
			$minid=0;
			$maxid=0;
		}
		if($minid>0&&$maxid>0){
			for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["member".$i])){
					array_push($aids, $i);
				}
			}
		}
		$ids = join(",", $aids);
		if(count($ids)>0) $location = $ADMIN_URL."?menu=700&ids=$ids";
		else $location = $ADMIN_URL."?menu=2";
	}elseif($cmd==900){
	//add keyword to filter
		$location = $ADMIN_URL."?menu=7";
		if(isset($fkw)&&$fkw!=""){
			if(dbSelectCount($TFiltered, "Keyword like '".addslashes($fkw)."'")==0){
				dbInsert($TFiltered, "KeywordID, Keyword", "null, '".addslashes($fkw)."'");
			}
		}
	//end of add keyword to filter
	}elseif($cmd==901){
	//add keyword to filter
		$location = $ADMIN_URL."?menu=7";
		$maxid = 0; $minid = 0;
		if($tt = dbSelect($TFiltered, "max(KeywordID)")){
			$maxid = $tt["max(KeywordID)"];
		}
		if($tt = dbSelect($TFiltered, "min(KeywordID)")){
			$minid = $tt["min(KeywordID)"];
		}
		for($i=$minid; $i<=$maxid; $i++){
			if(isset($HTTP_POST_VARS["kw".$i])&&$HTTP_POST_VARS["kw".$i]!=""&&$HTTP_POST_VARS["kw".$i]!=0){
				dbDelete($TFiltered, "KeywordID=".$i);
			}
		}
	//end of add keyword to filter
	}elseif($cmd==10){
	//send email to all members
		$location = $ADMIN_URL."?menu=700&ids=all";
	}elseif($cmd==355){
			//deleting banners
				$location = $ADMIN_URL."?menu=8";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=0")){
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
							dbDelete($TBannersShows, "BannerID=$i");
							dbDelete($TBannersClicks, "BannerID=$i");
						}
					}
				}
			//end of deleting banners
			}elseif($cmd==356){
			//activate banners
				$location = $ADMIN_URL."?menu=8";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=0")){
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
				$location = $ADMIN_URL."?menu=8";
				if($tt = dbSelect($TBanners, "min(BannerID), max(BannerID)", "MemberID=0")){
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
	}elseif($cmd==400){
			//add new banner form processing
				$error = "";
				$bannerfile = $HTTP_POST_FILES['banner']['tmp_name'];
				$bannerfilename = basename($HTTP_POST_FILES['banner']['name']);
				if(!isset($banner)||$banner==""||!file_exists($bannerfile)) $error.="Missing Banner file!<br>";
				if(!isset($bannerlink)||$bannerlink=="") $error.="Missing Banner Link URL!<br>";
				else $bannerlink = preg_replace("/^http:\/\//", "", $bannerlink);
				if($error!=""){
					$location = $ADMIN_URL."?menu=355&moderr=".urlencode($error);
				}else{
					$bannerurl = 	md5(microtime()).$bannerfilename;
					move_uploaded_file($bannerfile, $BANNERS_DIR.$bannerurl);
						$F = "BannerID, MemberID, BannerURL, BannerAlt, STATUS, Link";
						$Q = "null, 0, '".addslashes($bannerurl)."', '".addslashes($alt)."', 0, '".addslashes($bannerlink)."'";
						$bid = dbInsert($TBanners, $F, $Q);
					$location = $ADMIN_URL."?menu=8";
				}
			//end of add new banner form processing
			}elseif($cmd==401){
			//update banner
				$error = "";
				$bannerfile = $HTTP_POST_FILES['banner']['tmp_name'];
				$bannerfilename = basename($HTTP_POST_FILES['banner']['name']);
				if(!isset($bannerlink)||$bannerlink=="") $error.="Missing Banner Link URL!<br>";
				else $bannerlink = preg_replace("/^http:\/\//", "", $bannerlink);
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
					$Q = "BannerURL = '".addslashes($bannerurl)."', BannerAlt='".addslashes($alt)."', Link='".addslashes($bannerlink)."'";
					dbUpdate($TBanners, $Q, "BannerID=".$bannerid);
					$location = $ADMIN_URL."?menu=8";
				}
			//end of update banner
	}elseif($cmd==600){
	//sending a e-mail to member
		if(isset($id)&&$id!=""&&isset($subject)&&$subject!=""){
			if($id==0){
				if($res = dbSelectAll($TMembersInfo, "EMail")){
					while($tt = mysql_fetch_array($res)){
						@mail($tt["EMail"], $subject, $body, $MAIL_HEADERS);
					}
				}
			}elseif(preg_match("/,/", $id)){
				if($res = dbSelectAll($TMembersInfo, "EMail", "MemberID IN(".$id.")")){
					while($tt = mysql_fetch_array($res)){
						@mail($tt["EMail"], $subject, $body, $MAIL_HEADERS);
					}
				}
			}else{
				$location = $ADMIN_URL."?menu=500&id=$id";
				if($tt = dbSelect($TMembersInfo, "EMail", "MemberID=$id")){
					@mail($tt["EMail"], $subject, $body, $MAIL_HEADERS);
				}
			}
		}else{
			$location = $ADMIN_URL."?menu=2";
		}
	}elseif($cmd==601){
		$location = $ADMIN_URL."?menu=601&id=$mid";
		if(isset($mid)&&preg_match("/^\d+$/", $mid)&&$mid!=0&&isset($tid)&&preg_match("/^\d+$/", $tid)&&$tid!=0){
			if(preg_match("/^\d+(?:\.\d\d?)?$/", $balance)){
				$balance = $balance*100;
				if($tt = dbSelect($TMembersTransfers, "STATUS", "TransID='$tid'")){
					if($tt["STATUS"]!=2){
						dbUpdate($TMembersBalance, "Balance='".$balance."'", "MemberID='".$mid."'");
						if(isset($bb)&&$bb!=""){
							$location = $ADMIN_URL."?menu=13";
							dbUpdate($TMembersTransfers, "STATUS=2", "TransID='$tid'");
						}
					}
				}
			}
		}
	}elseif($cmd==5001){
		if(preg_match("/\d+/",$mid)&&$mid!=0){
			$location = $ADMIN_URL."?menu=5000&id=$mid";
			$maxid = 0;
			$minid = 0;
			if($tt = dbSelect($TMembersTerms, "max(TermID)", "MemberID=$mid")){
				$maxid = $tt["max(TermID)"];
			}
			if($tt = dbSelect($TMembersTerms, "min(TermID)", "MemberID=$mid")){
				$minid = $tt["min(TermID)"];
			}
			if($maxid>0) for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["term".$i])){
					dbDelete($TMembersTerms, "TermID=$i");
					dbDelete($TMembersBids, "TermID=$i");
				}
			}
		}else{
			$location = $ADMIN_URL;
		}
	}elseif($cmd==9001){
		$location = $ADMIN_URL."?menu=9";
			$maxid = 0;
			$minid = 0;
			if($tt = dbSelect($TTempTermsBids, "max(TempID)")){
				$maxid = $tt["max(TempID)"];
			}
			if($tt = dbSelect($TTempTermsBids, "min(TempID)")){
				$minid = $tt["min(TempID)"];
			}
			if($maxid>0) for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["tb".$i])){
					if($tt = dbSelect($TTempTermsBids, "*", "TempID=$i")){
						$bid = $tt["Bid"];
						$member_id = $tt["MemberID"];
						if($tt["TYPE"]==0){
							$F = "TermID, MemberID, Term, Title, Link, Descr, LLogoURL";
							$Q = "null, $member_id, '".addslashes($tt["Term"])."', '".addslashes($tt["Title"])."', '".addslashes($tt["Link"])."', '".addslashes($tt["Descr"])."', '".addslashes($tt["LLogoURL"])."'";
							$tid = dbInsert($TMembersTerms, $F, $Q);
							if($tid>0){
								dbInsert($TMembersBids, "TermID, Bid", "$tid, $bid");
							}
						}else{
							$F = "MemberID, Bid, Title, Link, Descr";
							$Q = "$member_id, $bid, '".addslashes($tt["Title"])."', '".addslashes($tt["Link"])."', '".addslashes($tt["Descr"])."'";
							if(dbSelectCount($TNoMatchesBids, "MemberID=$member_id")==0){
								dbInsert($TNoMatchesBids, $F, $Q);
							}else{
								dbUpdate($TNoMatchesBids, "Bid=$bid, Title='".addslashes($tt["Title"])."', Link='".addslashes($tt["Link"])."', Descr='".addslashes($tt["Descr"])."'", "MemberID=$member_id");
							}
							$location = $MEMBER_CP_URL."?menu=9";
						}
						dbDelete($TTempTermsBids, "TempID=$i");
					}
				}
			}
	}elseif($cmd==1001){
		//delete low-level accounts
		$location = $ADMIN_URL."?menu=10";
			$maxid = 0;
			$minid = 0;
			if($tt = dbSelect($TAdminSpecialAccounts, "max(AccountID)")){
				$maxid = $tt["max(AccountID)"];
			}
			if($tt = dbSelect($TAdminSpecialAccounts, "min(AccountID)")){
				$minid = $tt["min(AccountID)"];
			}
			if($maxid>0) for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["lla".$i])){
					dbDelete($TAdminSpecialAccounts, "AccountID=$i");
				}
			}
	}elseif($cmd==1100){
		//add low-level account
		$location = $ADMIN_URL."?menu=10";
		if(isset($llalogin)&&$llalogin!=""&&isset($llapw)&&$llapw!=""){
			if(dbSelectCount($TAdminSpecialAccounts, "AccountLogin='".addslashes($llalogin)."'")==0){
				$rights = 0;
				foreach($ADMIN_MASKS as $m=>$v){
					if(isset($HTTP_POST_VARS["mask".$v])) $rights+=$v;
				}
				dbInsert($TAdminSpecialAccounts, "AccountID, AccountLogin, AccountPassword, AccessRights", "null, '".addslashes($llalogin)."', '".addslashes($llapw)."', $rights");
			}
		}
	}elseif($cmd==1101){
		//add low-level account
		$location = $ADMIN_URL."?menu=10";
		if(isset($aid)&&$aid!=""&&$aid!=0&&isset($llalogin)&&$llalogin!=""&&isset($llapw)&&$llapw!=""){
			if(dbSelectCount($TAdminSpecialAccounts, "AccountLogin='".addslashes($llalogin)."'")==1){
				$rights = 0;
				foreach($ADMIN_MASKS as $m=>$v){
					if(isset($HTTP_POST_VARS["mask".$v])) $rights+=$v;
				}
				dbUpdate($TAdminSpecialAccounts, "AccountLogin='".addslashes($llalogin)."', AccountPassword='".addslashes($llapw)."', AccessRights='".$rights."'", "AccountID='$aid'");
			}
		}
	}elseif($cmd==1300){
		//delete payment transfers
		$location = $ADMIN_URL."?menu=13";
			$maxid = 0;
			$minid = 0;
			if($tt = dbSelect($TMembersTransfers, "max(TransID)")){
				$maxid = $tt["max(TransID)"];
			}
			if($tt = dbSelect($TMembersTransfers, "min(TransID)")){
				$minid = $tt["min(TransID)"];
			}
			if($maxid>0) for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["tr".$i])){
					dbDelete($TMembersTransfers, "TransID=$i");
				}
			}
	}elseif($cmd==2500){
		//HERE IS PROCESSING OF PAYMENT 
		$location = $ADMIN_URL."?menu=13";
		if(isset($id)&&preg_match("/^\d+(?:\.\d\d?)?$/",$id)&&$id!=0){
			dbUpdate($TMembersTransfers, "STATUS=1", "TransID='$id'");
		}
	}elseif($cmd==4000){
		//process getmoney query
		$location = $ADMIN_URL."?menu=16";
		if(isset($id)&&preg_match("/^\d+$/",$id)&&$id!=0){
			dbUpdate($TGMQuery, "STATUS=1", "TransID=$id");
		}
	}elseif($cmd==4001){
		//process getmoney query
		$location = $ADMIN_URL."?menu=16";
		if(isset($id)&&preg_match("/^\d+$/",$id)&&$id!=0){
			if($tt = dbSelect($TGMQuery, "MemberID, Amount", "TransID=$id")){
				if($tt2 = dbSelect($TMembersBalance, "Balance", "MemberID=".$tt["MemberID"])){
					if(($tt2["Balance"] - GetSettings("minbal"))>$tt["Amount"]){
						dbUpdate($TGMQuery, "STATUS=2", "TransID=$id");
						dbUpdate($TMembersBalance, "Balance=Balance-".$tt["Amount"], "MemberID=".$tt["MemberID"]);
					}
				}
			}
		}
	}elseif($cmd==4002){
		//delete get money query
		$location = $ADMIN_URL."?menu=16";
		if($tt = dbSelect($TGMQuery, "max(TransID), min(TransID)")){
			$maxid = $tt["max(TransID)"];
			$minid = $tt["min(TransID)"];
			for($i = $minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["trans".$i])&&$HTTP_POST_VARS["trans".$i]!=""&&$HTTP_POST_VARS["trans".$i]!=0){
					dbDelete($TGMQuery, "TransID=$i");
				}
			}
		}
	}elseif($cmd==9002){
		$location = $ADMIN_URL."?menu=9";
			$maxid = 0;
			$minid = 0;
			if($tt = dbSelect($TTempTermsBids, "max(TempID)")){
				$maxid = $tt["max(TempID)"];
			}
			if($tt = dbSelect($TTempTermsBids, "min(TempID)")){
				$minid = $tt["min(TempID)"];
			}
			if($maxid>0) for($i=$minid; $i<=$maxid; $i++){
				if(isset($HTTP_POST_VARS["tb".$i])){
					dbDelete($TTempTermsBids, "TempID=$i");
				}
			}
	}elseif($cmd==9999){
		$FILE = BackUpDatabase();
		header("Content-type: application/bin");
        header("Content-Disposition: attachment; filename=ASPPC_DB_backup_".date("Ymd").".sql");
		print $FILE;
		exit;
	}elseif($cmd==7777){
		//generating stats report
		$REPORT = CommonStatsReport();
		header("Content-type: application/bin");
        header("Content-Disposition: attachment; filename=ASPPC_Stats_Report_".date("Ymd").".txt");
		print $REPORT;
		exit;
	}elseif($cmd==7778){
		//restoring DB from recieved file
			$location = $ADMIN_URL."?menu=7779"; //restore faild page
			
			$dfile = $HTTP_POST_FILES['dump']['tmp_name'];
//			$dfilename = basename($HTTP_POST_FILES['dump']['name']);
			
			if($dfile!=""&&file_exists($dfile)){
				if($fp = @fopen($dfile, 'r')){
					$rdump = fread($fp, filesize($dfile));
					if(ProcessRestoreDB($rdump)){
						$location = $ADMIN_URL."?menu=7770"; //restore complete page
					}
					fclose($fp);
				}
			}
			
	}elseif($cmd==65535){
		//log off
		$location = $ADMIN_URL;
		unset($alogin);
		unset($apw);
		session_unregister("alogin");
		session_unregister("apw");
	}else{
		$location = $ADMIN_URL;
	}
	mysql_close();
	if($location!=""){
		header("Location: $location");
		exit;
	}
?>