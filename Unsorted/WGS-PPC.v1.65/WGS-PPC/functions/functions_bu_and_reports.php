<?

	function BackUpDatabase(){
		global $DBSTRUCTURE;
		$FILE = "######################################################\n";
		$FILE.= "# Ettica.com PPC Search engine database backup       #\n";
		$FILE.= "######################################################\n";
		$FILE.= "# Backup date: ".date("d M Y G:i:s")."\n";
		$FILE.= "#-----------------------------------------------------\n\n";
		foreach($DBSTRUCTURE as $table=>$temp){
//			$FILE.="DROP TABLE IF EXISTS ".$table.";\n";
//			$FILE.="create table ".$table."(".$temp.");\n\n";
			$FILE.= "#data of table $table\n\n";
			$Q = "show fields from $table";
//			print $table."<hr>";
			if($res0 = mysql_query($Q)){
				$fields = array();
				while($tt0 = mysql_fetch_array($res0)){
					if($tt0["Field"]!="") array_push($fields, $tt0["Field"]);
				}
				$ffc = count($fields);
				if($ffc>0){
					if($res = dbSelectAll($table)){
						while($tt = mysql_fetch_array($res)){
							$F = "";
							$V = "";
							$fc = $ffc;
							foreach($fields as $f){
								$F.=$f.($fc!=1?", ":"");
								$V.=(preg_match("/^\d+$/",$tt[$f])?$tt[$f]:"'".$tt[$f]."'").($fc!=1?", ":"");
								$fc--;
							}
							$FILE.="insert into $table ($F) values ($V);\n";
						}
					}
				}
			}
			$FILE.="\n\n";
		}
		return $FILE;
	}

	function CommonStatsReport(){
		
		global $TSearchStatistics, $TTempTermsBids, $TMembersClicks;
	
		$REPORT = "========================================================\n";
		$REPORT.= "Ettica.com PPC Search engine statistics report          \n";
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= "Report date: ".date("d M Y G:i:s")."\n";
		$REPORT.= "========================================================\n\n";
		$REPORT.= "Common statistics\n";
		$REPORT.= "--------------------------------------------------------\n";

		$st = array();
		$st = AdminMain(255);
		
		$REPORT.= "Number of all members:       ".$st["allmembers"]."\n";
		$REPORT.= "Number of blocked members:   ".$st["blockedmembers"]."\n";
		$REPORT.= "Total clicks number:         ".$st["clicks"]."\n";
		$REPORT.= "Today clicks:                ".$st["todayclicks"]."\n";
		$REPORT.= "Total banners clicks number: ".$st["bclicks"]."\n";
		$REPORT.= "Today banners clicks:        ".$st["tbclicks"]."\n";
		$REPORT.= "Total banners shows:         ".$st["banners"]."\n";
		$REPORT.= "Total money collected:       ".$st["money"]."\n";
		$REPORT.= "Today money collected:       ".$st["todaymoney"]."\n";
		$REPORT.= "--------------------------------------------------------\n\n";
		
		
		if($res = dbSelectAll($TTempTermsBids, "*")){
			$REPORT.= "Waiting bids:\n";
			$REPORT.= "--------------------------------------------------------\n";
			while($tt = mysql_fetch_array($res)){
				$mname = MemberName($tt["MemberID"]);
				$tterm = ($tt["TYPE"]==0?stripslashes($tt["Term"]):"'No matches'");
				$REPORT.= "From: ".$mname.str_repeat(" ", (30-strlen($mname)))." Term: ".$tterm.str_repeat(" ", (20-strlen($tterm)))." Bid: ".($tt["Bid"]/100)."\n";
			}
			$REPORT.= "--------------------------------------------------------\n\n";
		}
		
		$REPORT.= "Listings statistics\n";
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= "Today searches:\n";
		if($res = dbSelectAll($TSearchStatistics, "distinct Term", "SearchDate='".date("Y-m-d")."'", "Term")){
			while($tt = mysql_fetch_array($res)){
				$clicks = dbSelectCount($TMembersClicks, "SearchTerm='".$tt["Term"]."' and ClickDate='".date("Y-m-d")."'");
				$REPORT.= "   ".$tt["Term"].str_repeat(" ",abs(51-strlen($tt["Term"])))." ".$clicks."\n";
			}
		}else{
			$REPORT.= "   there are no searches today\n";
		}
		$REPORT.= "\nAll searches:\n";
		if($res = dbSelectAll($TSearchStatistics, "distinct Term", "1=1", "Term")){
			while($tt = mysql_fetch_array($res)){
				$clicks = dbSelectCount($TMembersClicks, "SearchTerm='".$tt["Term"]."'");
				$REPORT.= "   ".$tt["Term"].str_repeat(" ",abs(51-strlen($tt["Term"])))." ".$clicks."\n";
			}
		}else{
			$REPORT.= "   there are no searches\n";
		}
		return $REPORT;
	}
	
	function DBRestoreForm(){
		global $ADMIN_RESTORE_DB_TMP;
		return ReadTemplate($ADMIN_RESTORE_DB_TMP);
	}
	
	function ProcessRestoreDB(&$rdump){
		$qs = explode("\n", $rdump);
		foreach($qs as $Q){
			@mysql_unbuffered_query($Q);
//			print $Q."<hr>";
		}
		return "1";
	}
	
	function AffStatsReport($id){
		global $TASearches, $TAClicks;
		$sdate = date("Y-m-d");
		$edate = $sdate;
		if($tt = dbSelect($TASearches, "max(ASDate), min(ASDate)", "MemberID=$id")){
			$sdate = $tt["min(ASDate)"];
			$edate = $tt["max(ASDate)"];
		}
		if($tt = dbSelect($TAClicks, "max(ACDate), min(ACDate)", "MemberID=$id")){
			if($sdate>$tt["min(ACDate)"]) $sdate = $tt["min(ACDate)"];
			if($edate<$tt["max(ACDate)"]) $edate = $tt["max(ACDate)"];
		}
		$REPORT = "========================================================\n";
		$REPORT.= "Ettica.com PPC Search engine affiliate report           \n";
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= "Report date: ".date("d M Y G:i:s")."\n";
		$REPORT.= "========================================================\n\n";
		$REPORT.= "Period: ".date2($sdate)." - ".date2($edate)."\n";
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= "      date       |      searches     |      clicks      \n";
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= GetAStats($id, $sdate, $edate, 1);
		$REPORT.= "--------------------------------------------------------\n";
		$REPORT.= "Total money collected: ".AffGetTotalMoney($id);
		return $REPORT;
	}
?>