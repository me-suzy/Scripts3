<?
	function PayPalDonationsSendForm($member_id, $tid, $amount = 0){
		global $SITE_URL;
		$OUT = "https://www.paypal.com/cgi-bin/webscr?";
		$OUT.= "cmd=_xclick";
		$OUT.= "&business=".urlencode(GetSettings("appemail"));
		if($amount!=""&&$amount!=0&&$amount!="0.00"){
			$OUT.= "&amount=".urlencode($amount);
		}
		$OUT.= "&item_name=".urlencode("Charge PPC Account");
		$OUT.= "&invoice=".urlencode($member_id.":".$tid.":".md5(microtime()));
		$OUT.= "&return=".urlencode($SITE_URL."?menu=4001");
		return $OUT;
	}
	
	
	function GetMemberEmail($member_id){
		global $TMembersCC;
		$out = "";
		if($tt = dbSelect($TMembersCC, "PayPalAccount", "MemberID=$member_id")){
			$out = stripslashes($tt["PayPalAccount"]);
		}
		return $out;
	}
	
	function GetMemberIEmail($member_id){
		global $TMembersInfo;
		$out = "";
		if($tt = dbSelect($TMembersInfo, "EMail", "MemberID=$member_id")){
			$out = stripslashes($tt["EMail"]);
		}
		return $out;
	}
?>