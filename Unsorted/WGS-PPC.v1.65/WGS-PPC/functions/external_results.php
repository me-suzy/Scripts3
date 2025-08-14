<?
	//DMOZ
	$DELAY = 5;
	$DMOZ_URL = "http://search.dmoz.org/cgi-bin/search?";
	$MSN_URL = "http://search.msn.com/results.asp?rd=0&v=1&FORM=SMCB&RS=CHECKED";
	$REVENUE_URL = "http://www.revenuepilot.com/servlet/search?mode=xml";
	$TRY_CONNECTIONS = 50;
	$SEARCHFEED_URL = "http://www.searchfeed.com/rd/feed/XMLFeed.jsp?";
	$AHHA_URL = "http://partner.ah-ha.com/xmlv4.aspx?";
	//microseconds
	$TRY_PAUSE = 5;
	
	function Pause($delay = 10){
		$ts = explode(" ", microtime());
		$start_t = $ts[0] + $ts[1]*1000;
		do{
			$ts = explode(" ", microtime());
			$c_t = $ts[0] + $ts[1]*1000;
		}while(($c_t - $start_t)<=$delay);
	}	
	
	
	function GetDMOZResults(&$results, $ss, $start = 1, $num = 1){
		global $DMOZ_URL;
		global $TRY_CONNECTIONS, $TRY_PAUSE;
		$results = array(
								"0" => array(
								"title"=>"",
								"link"=>"",
								"summary"=>"",
								"category"=>""
							 )
		);
		$link = $DMOZ_URL."search=".urlencode($ss)."&start=".$start;
		$cc = 1;
		while(!($fh = @fopen($link, 'r'))){
			if($cc==$TRY_CONNECTIONS) break;
			$cc++;
			Pause($TRY_PAUSE);
		}
		if($fh)
		{
			$buff = "";
			$tmp = "";
			$tmp_arr = array();
			while($buff = fread($fh, 256)){
				$tmp.=$buff;
			}
			fclose($fh);
			$sc = 0;
			if(preg_match("/Open Directory Sites.*?(\d+?)\)/", $tmp, $m1)){
				$sc = $m1[1];
			}
			$tmp = preg_replace("/^.*Open Directory Sites/", "", $tmp);
			$tmp = preg_replace("/<img.*?>/", "", $tmp);
			$count = 1;
			if(preg_match_all("/<li>(<a href=\"http:.*?)(?:<li>|<\/ol>)/s", $tmp, $matches)){
				foreach($matches[1] as $st){
					if($count<=$num&&$count<=$sc){
						if(preg_match("/^<a href=\"(.*?)\">(.*?)<\/a>(.*?)<br>.*?<a href=\".*?\">(.*?)<\/a>/s", $st, $m2)){
							$linkurl = $m2[1];
							$title = $m2[2];
							$summary = $m2[3];
							$category = $m2[4];
						}
//						print $count." - ".$title."<hr>";
						$results[$count] = array(
							"title"=>$title,
							"link"=>$linkurl,
							"summary"=>$summary,
							"category"=>$category
						);
						$count++;
					}else{
						break;
					}
				}
				return $sc;
			}else return "";
		}else{
			return "";
		}
	}
	
	
	function PPCParseRecord_External($type, $record, $counter, $page, $rpp, $hs, $ss, $rest){
		global $PPC_RESULTS_PART, $CLICK_SCRIPT, $CLICK_TARGET, $MAIN_URL;
		
			$tmp = ReadTemplate($PPC_RESULTS_PART);
			$shl = "";
			if($rest=="dmoz"||$rest=="msn"){
				if(preg_match("/(http:\/\/(?:[-\w]\.?)+\/??)/", $record["link"], $mm)){
					$shl = $mm[1];
					$cat = $record["category"];
				}
			}elseif($rest=="revenue"){
				$shl = $record["category"];
				$cat = "n/a";
			}
			
			$vars = array(
					"logo"=>"",
					"counter"=>$counter,
					"id"=>"",
					"title"=>"<a href=\"".$record["link"]."\" target=\"_blank\">".$record["title"]."</a>",
					"summary"=>($hs==0?stripslashes($record["summary"]):"").($record["bid"]!=""?"<br><i>Bid: ".$record["bid"]."</i>":""),
					"link"=>$shl,
					"category"=>$cat
			);
			return ParseTemplate($tmp, $vars);
			
	}
	
//END OF DMOZ

//MSN
	
	function GetMSNResults(&$results, $ss, $start = 1, $num = 1){
		global $MSN_URL, $TRY_CONNECTIONS, $TRY_PAUSE;
		global $rpp;
		if($rpp==""||$rpp==0) $rpp = 10;
		$results = array(
								"0" => array(
								"title"=>"",
								"link"=>"",
								"summary"=>"",
								"category"=>""
							 )
		);
		$link = $MSN_URL."&ba=(0.".($start!=0?$start+1:$start).")0(.)0.......&q=".urlencode($ss)."&co=(0.".($rpp).")4(0.1)3.200.2.4.10.3..";
//		print $link."<hr>";
		$cc = 1;
		while(!($fh = @fopen($link, 'r'))){
			if($cc==$TRY_CONNECTIONS) break;
			$cc++;
			Pause($TRY_PAUSE);
		}
		if($fh){
			$buff = "";
			$tmp = "";
			$tmp_arr = array();
			while($buff = fread($fh, 256)){
				$tmp.=$buff;
			}
			fclose($fh);
			$sc = 0;
			if(preg_match("/Results \d+-\d+ of about (\d+)/", $tmp, $m1)){
				$sc = $m1[1];
			}
			$count = 1;
			if(preg_match_all("/<LI xmlns=\"\">(<A.*?)(?:<LI xmlns=\"\">|<\/OL>)/si", $tmp, $matches)){
				foreach($matches[1] as $st){
					if($count<=$num&&$count<=$sc){
						if(preg_match("/^<A href=\"(.*?)\" class=\"clsResultTitle\"\s*>(.*?)<\/A>.*?<TD>(.*?)<BR>/si", $st, $m2)){
							$linkurl = $m2[1];
							$title = $m2[2];
							$summary = $m2[3];
							$category = "n/a";
						}
						$results[$count] = array(
							"title"=>$title,
							"link"=>$linkurl,
							"summary"=>$summary,
							"category"=>$category
						);
						$count++;
					}else{
						break;
					}
				}
				return $sc;
			}else return "";
		}else{
			return "";
		}
	}

//END OF MSN

//REVENUEPILOT XML FEEDS
//	$URL='http://www.revenuepilot.com/servlet/search?mode=xml&id=0&filter=on&perpage=20&ip=123.45.67.89&skip=0&keyword=flowers';
	
	Function LoadText($FileName)
	{
	Global $TRY_CONNECTIONS ;
	Global $TRY_PAUSE       ;
	$cc=0;
	While(!($fh=@FOpen($FileName,'r')))
		{
		If($cc==$TRY_CONNECTIONS)
			Return False;
		$cc++;
		Pause($TRY_PAUSE);
		}
	$Res='';
	While(!FEof($fh))
		$Res.=Str_Replace("\r",'',FRead($fh,256*256));
	FClose($fh);
	Return $Res;
	}
	
	Function SaveText($FileName,$Data)
	{
	If(!($fh=@FOpen($FileName,'w')))
		Return False;
	$Res=FWrite($fh,$Data)==StrLen($Data);
	FClose($fh);
	Return $Res;
	}
	
	Function ParseXML($URL)
	{
	$Res=Array(0=>'');
	If(!($Text=LoadText($URL)))
		Return False;
	If(!Preg_Match('/^.*?<RESULTS>(.*)<\/RESULTS>/is',$Text,$M))
		Return False;
	$Text=$M[1];
	If(!Preg_Match_All('/<LISTING((?:[^"]*?"[^"]*?")*?[^"]*?)\/>/is',$Text,$M))
		Return False;
	$Text=$M[1];
	$c=Count($Text);
	For($i=0;$i<$c;$i++)
		{
		If(!Preg_Match_All('/(\w*?)="([^"]*?)"/is',$Text[$i],$M))
				Break;
		$k=$M[1];
		$v=$M[2];
		$c2=Count($k);
		$t=Array();
		For($j=0;$j<$c2;$j++)
			$t[$k[$j]]=$v[$j];
		$R['title'   ]=$t['TITLE'       ];
		$R['link'    ]=$t['LINK'        ];
		$R['summary' ]=$t['DESCRIPTION' ];
		$R['category']=$t['DOMAIN' ];
		$R['bid']=$t['BID' ];
		$Res[]=$R;
		}
	UnSet($Res[0]);
	Return $Res;
	}
	
	function GetREVENUEResults(&$results, $ss, $start = 1, $num = 1){
		global $REVENUE_URL, $REVENUE_FAMILY_FILTER;
		$link_prev = $REVENUE_URL."&id=".$REVENUE_MEMBER_ID."&filter=".$REVENUE_FAMILY_FILTER."&perpage=1&skip=".($start-$num>0?$start-$num:0)."&ip=".getenv("REMOTE_ADDR")."&keyword=".urlencode($ss);
		if($start!=0) $dr = ParseXML($link_prev);
		else $dr[1]['title'] = "1234567890qwertyuioplkjhgfdsazxcvbnm";
		$REVENUE_MEMBER_ID = GetSettings("revenue_id");
		$link = $REVENUE_URL."&id=".$REVENUE_MEMBER_ID."&filter=".$REVENUE_FAMILY_FILTER."&perpage=".$num."&skip=".$start."&ip=".getenv("REMOTE_ADDR")."&keyword=".urlencode($ss);
		$results = ParseXML($link);
		$f = 0;
		foreach($results as $tt){
			if($tt["title"]==$dr[1]["title"]){
				$f = 1;
				break;
			}
		}
		if($f!=0){
			$results = array();
		}
	}
//END OF REVENUEPILOT XML FEEDS

//SEARCHFEED XML FEEDS
	function GetSearchFeedResults(&$results, $ss, $start = 1, $num = 1){
		global $SEARCHFEED_URL, $TRY_CONNECTIONS, $TRY_PAUSE;
		$results = array(
								"0" => array(
								"title"=>"",
								"link"=>"",
								"summary"=>"",
								"category"=>"",
								"bid"=>""
							 )
		);
		$SEARCHFEED_CID = GetSettings("searchfeed_id");
		$link = $SEARCHFEED_URL."cat=".urlencode($ss)."&pID=".$SEARCHFEED_CID."&IP=".getenv("REMOTE_ADDR")."&page=".($start+1)."&nl=".$num;
		$cc = 1;
		while(!($fh = @fopen($link, 'r'))){
			if($cc==$TRY_CONNECTIONS) break;
			$cc++;
			Pause($TRY_PAUSE);
		}
		if($fh)
		{
			$buff = "";
			$tmp = "";
			$tmp_arr = array();
			while($buff = fread($fh, 256)){
				$tmp.=$buff;
			}
			fclose($fh);
/*
							print "SF working...<hr>";
							$ft = fopen("temp2.txt","a");
							fwrite($ft, "\n\n$link\n\n");
							fwrite($ft, $tmp);
							fclose($ft);
*/
			$sc = $num;
			$count = 1;
			if(preg_match("/<Listings>(.*)<\/Listings>/is", $tmp, $matches)){
				$recs = $matches[1];
					if(preg_match("/<Count>(.*?)<\/Count>/is", $recs, $m1)){
						$sc = $m1[1];
					}
					if(preg_match_all("/<Listing>(.*?)<\/Listing>/is", $recs, $m1)){
						foreach($m1[1] as $tt){
							if(preg_match("/<URI>\s*(.*)\s*<\/URI>/is", $tt, $m2)){
								$linkurl = $m2[1];
							}
							if(preg_match("/<URL>\s*(.*)\s*<\/URL>/is", $tt, $m2)){
								$category = $m2[1];
							}
							if(preg_match("/<Title>\s*<!\[CDATA\[(.*)\]\]>\s*<\/Title>/is", $tt, $m2)){
								$title = $m2[1];
							}
							if(preg_match("/<Description>\s*<!\[CDATA\[(.*)\]\]>\s*<\/Description>/is", $tt, $m2)){
								$summary = $m2[1];
							}
							if(preg_match("/<Bid>\s*(.*)\s*<\/Bid>/is", $tt, $m2)){
								$bid = $m2[1];
							}
/*
							$ft = fopen("temp.txt","a");
							fwrite($ft, "$title\n$summary\n$linkurl\n$category\n$bid\n---------------------------\n");
							fclose($ft);
*/
							$results[$count] = array(
								"title"=>$title,
								"link"=>$linkurl,
								"summary"=>$summary,
								"category"=>$category,
								"bid"=>$bid
							);
							$count++;
						}
					}
				return $sc;
			}else return "";
		}else{
			return "";
		}
	}
//END OF SEARCHFEEDS XML FEEDS

//AH-HA XML Feeds
	function GetAHHAResults(&$results, $ss, $start = 1, $num = 1){
		global $AHHA_URL, $TRY_CONNECTIONS, $TRY_PAUSE;
		$results = array(
								"0" => array(
								"title"=>"",
								"link"=>"",
								"summary"=>"",
								"category"=>"",
								"bid"=>""
							 )
		);
		$AHHA_CID = GetSettings("ahha_id");
		$link = $AHHA_URL."Q=".urlencode($ss)."&CID=".$AHHA_CID."&IP=".getenv("REMOTE_ADDR")."&LL=0&O=".$start."&S=".$num;
		$cc = 1;
		while(!($fh = @fopen($link, 'r'))){
			if($cc==$TRY_CONNECTIONS) break;
			$cc++;
			Pause($TRY_PAUSE);
		}
		if($fh)
		{
			$buff = "";
			$tmp = "";
			$tmp_arr = array();
			while($buff = fread($fh, 256)){
				$tmp.=$buff;
			}
			fclose($fh);
			$sc = $num;
			$count = 1;
			if(preg_match("/<RESULTS>(.*)<\/RESULTS>/is", $tmp, $matches)){
				$recs = $matches[1];
					if(preg_match_all("/<LINK>(.*?)<\/LINK>/is", $recs, $m1)){
						foreach($m1[1] as $tt){
							if(preg_match("/<REDIRECT_URL>\s*<!\[CDATA\[(.*)\]\]>\s*<\/REDIRECT_URL>/is", $tt, $m2)){
								$linkurl = $m2[1];
							}
							if(preg_match("/<DOMAIN>\s*<!\[CDATA\[(.*)\]\]>\s*<\/DOMAIN>/is", $tt, $m2)){
								$category = $m2[1];
							}
							if(preg_match("/<TITLE>\s*<!\[CDATA\[(.*)\]\]>\s*<\/TITLE>/is", $tt, $m2)){
								$title = $m2[1];
							}
							if(preg_match("/<DESCRIPTION>\s*<!\[CDATA\[(.*)\]\]>\s*<\/DESCRIPTION>/is", $tt, $m2)){
								$summary = $m2[1];
							}
							if(preg_match("/<BID>\s*(.*)\s*<\/BID>/is", $tt, $m2)){
								$bid = $m2[1];
							}
/*
							$ft = fopen("temp.txt","a");
							fwrite($ft, "$title\n$summary\n$linkurl\n$category\n---------------------------\n");
							fclose($ft);
*/
							$results[$count] = array(
								"title"=>$title,
								"link"=>$linkurl,
								"summary"=>$summary,
								"category"=>$category,
								"bid"=>$bid
							);
							$count++;
						}
					}
				return $sc;
			}else return "";
		}else{
			return "";
		}
	}
//END OF AG-HA

?>