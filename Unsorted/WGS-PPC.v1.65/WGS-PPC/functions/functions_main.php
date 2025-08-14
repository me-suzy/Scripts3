<?

	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	//  Common functions               //
	//                                 //
	/////////////////////////////////////

	//parameter - date in yyyy-mm-dd format
	//output - date in dd monthname yyyy
	function date2($instr){
	$Months = array(0=>"",1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"Desember");
		$temp = array();
		$temp = explode("-",$instr);
		$mn = (int)$temp[1];
		$outd = $temp[2]." ".$Months[$mn]." ".$temp[0];
		return $outd;
	}

	function month2($instr){
	$Months = array(0=>"",1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"Desember");
		$temp = array();
		$temp = explode("-",$instr);
		$mn = (int)$temp[1];
		$outd = $Months[$mn]." ".$temp[0];
		return $outd;
	}
	
	//return not false if array contains element
	function ArrayContains($array, $elem){
		foreach($array as $a) if($a==$elem) return "true";
		return "";
	}

	//parameter - path to file
	//output - file content
	function ReadTemplate($tmp_file){
		$tmp = "";
		if($fh = @fopen($tmp_file,"r")){
			$tmp = fread($fh, filesize($tmp_file));
			fclose($fh);
		}
		return $tmp;
	}

	function ShowError($header, $error, $returnurl){
		global $ERROR_TMP;
		$tmp = ReadTemplate($ERROR_TMP);
		$tmp = preg_replace("/<#header#>/", $header, $tmp);
		$tmp = preg_replace("/<#error#>/", $error, $tmp);
		$tmp = preg_replace("/<#returnurl#>/", $returnurl, $tmp);
		print $tmp;
	}
	
	function ParseTemplate($tmp, $vars){
		foreach($vars as $var=>$val){
			$tmp = preg_replace("/<#".$var."#>/", $val, $tmp);
		}
		return $tmp;
	}

	function CCildsCount($id){
		global $TCategories;
		return dbSelectCount($TCategories, "Parent=$id");
	}
	
	function GetCategsSList($selected, $parent = 0, $level = 0){
		global $TCategories;
		$OUT = "";
		if($res = dbSelectAll($TCategories, "*", "Parent=$parent")){
			while($tt = mysql_fetch_array($res)){
				if($selected==$tt["CategoryID"]) $sel = "selected";
				else $sel = "";
				$OUT.= "<option value=\"".$tt["CategoryID"]."\" ".$sel.">".(str_repeat("&raquo;",$level)).$tt["Name"]."</option>\n";
				if(CCildsCount($tt["CategoryID"])>0) $OUT.= GetCategsSList($selected, $tt["CategoryID"], $level+1);
			}
		}
		return $OUT;
	}
	
	function GetCategoriesSelectList($selected = 0){
		$OUT = "<select name=\"category\">\n";
		$OUT.= GetCategsSList($selected, 0,0);
		$OUT.= "</select>\n";
		return $OUT;
	}
	function GetCategoriesSelectList2($selected = 0){
		$OUT = "<select name=\"category\">\n";
		$OUT.= "<option value=\"0\">root</option>";
		$OUT.= GetCategsSList($selected, 0,1);
		$OUT.= "</select>\n";
		return $OUT;
	}
	
	function GetSettings($var){
		global $TAdminSettings, $TAdminMembersBonus;
		if($var=="bonus"){
			$tt = dbSelect($TAdminMembersBonus);
			return $tt["Bonus"];
		}else{
			$tt = dbSelect($TAdminSettings);
			$out = "";
			if($var=="currency"){
				$out = stripslashes($tt["CurrencySymbol"]);
			}elseif($var=="minbid"){
				$out = $tt["MinBid"];
			}elseif($var=="minbal"){
				$out = $tt["Porog"];
			}elseif($var=="ipp"){
				$out = $tt["IPP"];
			}elseif($var=="topnum"){
				$out = $tt["TopPosNumber"];
			}elseif($var=="nmbid"){
				$out = $tt["MinNoMatchesBid"];
			}elseif($var=="acceptbids"){
				$out = $tt["AcceptBids"];
			}elseif($var=="allowss"){
				$out = $tt["AllowSS"];
			}elseif($var=="affsearch"){
				$out = $tt["ACOCost"];
			}elseif($var=="affclicks"){
				$out = $tt["ACLCost"];
			}elseif($var=="allowipn"){
				$out = $tt["AllowIPN"];
			}elseif($var=="appemail"){
				$out = $tt["APPEMail"];
			}elseif($var=="dmoz"){
				$out = $tt["AllowDMOZ"];
			}elseif($var=="msn"){
				$out = $tt["AllowMSN"];
			}
			
			return $out;
		}
	}

	function GetBanner($banner_id, $ignore_status = 0, $sys = 0){
		global $TMembersAccounts, $TBanners, $TMembersBalance, $TBannersBids, $TBannersShows, $TMembersSites, $BANNERS_DIR, $BANNERS_PATH, $BANNER_CLICK, $MAIN_URL;
		$OUT = "&nbsp;";
		if($banner_id!=""){
		if($tt = dbSelect($TBanners, "MemberID, BannerURL, BannerAlt, STATUS, Link", "BannerID=$banner_id")){
			if($sys==0){
				if($tt2 = dbSelect($TBannersBids, "ShowBid", "BannerID=$banner_id")){
					dbInsert($TBannersShows, "ShowID, BannerID, ShowDate", "null, $banner_id, CURDATE()");
					dbUpdate($TMembersBalance, "Balance=Balance-".$tt2["ShowBid"], "MemberID=".$tt["MemberID"]);
					$minbal = GetSettings("minbal");
					if($tt3 = dbSelect($TMembersBalance, "Balance", "MemberID=".$tt["MemberID"])){
						if($tt3["Balance"]<=$minbid){
							dbUpdate($TMembersAccounts, "STATUS=1", "MemberID=".$tt["MemberID"]);
						}
					}
				}
			}
			$OUT = "<a href='".$BANNER_CLICK."?bid=$banner_id&mode=".$tt["MemberID"]."' target='_blank'><img src='".$BANNERS_PATH.stripslashes($tt["BannerURL"])."' border='0' alt='".$tt["BannerAlt"]."'></a>";
			if($ignore_status==0){
				if($tt["STATUS"]!=0) $OUT = "";
			}
		}
		}
		return $OUT;
	}
	
	
	
	//Output: pages links - grouped by $PAGES_PER_WINDOW links per window
	// 		  with "prev" and "next" links and "start page" and "last page" links
	function PagesLinks($page, $total_records, $RESULTS_PER_PAGE, $suffix = ""){
		global $MAX_LINKS_PER_PAGE, $MAIN_URL, $PAGES_PER_WINDOW;
		$total_pages = (int)(($total_records+($RESULTS_PER_PAGE-1))/$RESULTS_PER_PAGE);
		$pages = array();
		$delta = ceil($PAGES_PER_WINDOW/2);
		if($page>0){
			if(($total_pages>$PAGES_PER_WINDOW)&&($page>$delta)){
				array_push($pages, "<a href=\"".$MAIN_URL."?page=0".$suffix."\">start page</a>");
			}
			array_push($pages, "<a href=\"".$MAIN_URL."?page=".($page-1).$suffix."\">&laquo; previous page</a>");
		}
		$start = ($page-$delta);
		$end = ($page+$delta);
		if($start<1){
			$start = 0;
			$end = $PAGES_PER_WINDOW;
		}
		if($end>$total_pages) $end = $total_pages;
		for($i=$start; $i<$end; $i++){
			if($i!=$page) $link = "<a href=\"".$MAIN_URL."?page=".$i.$suffix."\"><u>".($i+1)."</u></a>";
			else $link = ($i+1);
			array_push($pages, $link);
		}
		if($page!=($total_pages-1)){
			array_push($pages, "<a href=\"".$MAIN_URL."?page=".($page+1).$suffix."\">next page &raquo;</a>");
			if(($total_pages>$PAGES_PER_WINDOW)&&($page<($total_pages - $delta))){
				array_push($pages, "<a href=\"".$MAIN_URL."?page=".($total_pages-1).$suffix."\">last page</a>");
			}
		}
		
		return join(" | ", $pages);
	}
	function GetPagesLinks($page, $total_records, $rpp,  $suffix = ""){
		global $PAGES_LINKS_TMP;
		$tmp = ReadTemplate($PAGES_LINKS_TMP);
		$vars = array(
			"pages"=>PagesLinks($page, $total_records, $rpp, $suffix)
		);
		return ParseTemplate($tmp, $vars);
	}

	function GetLogo($member_id, $mode = 0){
		global $TMembersLogos, $TMembersTerms, $LOGOS_DIR, $LOGOS_PATH, $LOGO_WIDTH;
		$OUT = "&nbsp;";
		if($mode==0){
			if($tt = dbSelect($TMembersLogos, "LogoURL", "MemberID=$member_id")){
				$OUT = "<img src=\"".$LOGOS_PATH.stripslashes($tt["LogoURL"])."\" border=\"0\" width=\"".$LOGO_WIDTH."\">";
			}
		}else{
			if($tt = dbSelect($TMembersTerms, "LLogoURL", "TermID=$member_id")){
				$OUT = "<img src=\"".$LOGOS_PATH.stripslashes($tt["LLogoURL"])."\" border=\"0\" width=\"".$LOGO_WIDTH."\">";
			}
		}
		return $OUT;
	}

	function SendMessage($to, $mode, $id = "", $login = "", $pw = ""){
		global $MAIL_HEADERS, $ADMIN_MAIL;
		global $EMAIL_MEMBER_REGISTRATION, $EMAIL_MEMBER_REGISTRATION_SUBJECT;
		global $EMAIL_MEMBER_BLOCK_LF, $EMAIL_MEMBER_BLOCK_LF_SUBJECT;
		global $EMAIL_MEMBER_BLOCK_M, $EMAIL_MEMBER_BLOCK_M_SUBJECT;
		global $EMAIL_MEMBER_TRANSFER_COMPLETED, $EMAIL_MEMBER_TRANSFER_COMPLETED_SUBJECT;
		global $EMAIL_A_TRANSFER_COMPLETED, $EMAIL_A_TRANSFER_COMPLETED_SUBJECT;
		global $TMembersInfo;
		if($mode==1){
			//registration
			$subject = $EMAIL_MEMBER_REGISTRATION_SUBJECT;
			$tmp = ReadTemplate($EMAIL_MEMBER_REGISTRATION);
			$vars = array(
				"id"=>$id,
				"login"=>$login,
				"pw"=>$pw
			);
		}elseif($mode==2){
			//block accout because of low funds
			$subject = $EMAIL_MEMBER_BLOCK_LF_SUBJECT;
			$tmp = ReadTemplate($EMAIL_MEMBER_BLOCK_LF);
			$vars = array(
				"id"=>$id
			);
		}elseif($mode==3){
			//block accout manual
			$subject = $EMAIL_MEMBER_DELETE_SUBJECT;
			$tmp = ReadTemplate($EMAIL_MEMBER_DELETE);
			$vars = array(
				"admin"=>$ADMIN_MAIL
			);
		}elseif($mode==4){
			//block accout manual
			$subject = $EMAIL_MEMBER_BLOCK_M_SUBJECT;
			$tmp = ReadTemplate($EMAIL_MEMBER_BLOCK_M);
			$vars = array(
				"admin"=>$ADMIN_MAIL
			);
		}elseif($mode==5){
			//transfer money - members message
			$subject = $EMAIL_MEMBER_TRANSFER_COMPLETED_SUBJECT;
			$tmp = ReadTemplate($EMAIL_MEMBER_TRANSFER_COMPLETED);
			$vars = array(
				"amount"=>$id
			);
		}elseif($mode==6){
			//transfer - admin message
			$subject = $EMAIL_A_TRANSFER_COMPLETED_SUBJECT;
			$tmp = ReadTemplate($EMAIL_A_TRANSFER_COMPLETED);
			$mm = "";
			if($tt = dbSelect($TMembersInfo, "Name", "MemberID=$login")){
				$mm = stripslashes($tt["Name"])." ( $login )";
			}
			$vars = array(
				"amount"=>$id,
				"member"=>$mm
			);
		}
		$message = ParseTemplate($tmp, $vars);
		@mail($to, $subject, $message, $MAIL_HEADERS);
	}
	
?>
