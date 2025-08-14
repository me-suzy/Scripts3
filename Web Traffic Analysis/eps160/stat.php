<?
include("config.php");
$referer=$_REQUEST['referer'];
$color=$_REQUEST['color'];
$res=$_REQUEST['res'];
$js=$_REQUEST['js'];
$temp1=reados();
$temp2=readbrowser();
$temp3=readstat();
$temp4=readscreen();
$temp5=readlang();
$temp6=readjs();
$temp7=readip();
webstat($temp1, $temp2, $temp3, $temp4, $temp5, $temp6, $color, $referer, $res, $js, $temp7);
header("Content-type: image/gif");
readfile("$path_img/a.gif");

function write($list, $filename) {
include("config.php");
 include("$path_data/setup.php");
 $fp = fopen($filename, "wb") or die ("The File \"$filename\" does not exist");
 flock( $fp, 2);
 if ($filename == "$path_data/os.dat") {
 fputs ($fp, "$list[os_95]<new>$list[os_31]<new>$list[os_98]<new>$list[os_me]<new>$list[os_nt]<new>$list[os_2000]<new>$list[os_xp]<new>$list[os_linux]<new>$list[os_unix]<new>$list[os_mac]<new>$list[os_3195]<new>$list[os_95nt]<new>$list[os_other]<new>\n");
 }
 elseif ($filename == "$path_data/screen.dat") {
 fputs ($fp, "$list[color_256]<new>$list[color_16]<new>$list[color_24]<new>$list[color_32]<new>$list[color_other]<new>$list[res_640_480]<new>$list[res_720_480]<new>$list[res_800_600]<new>$list[res_832_624]<new>$list[res_848_480]<new>$list[res_1024_768]<new>$list[res_1088_880]<new>$list[res_1152_864]<new>$list[res_1280_800]<new>$list[res_1280_854]<new>$list[res_1280_960]<new>$list[res_1280_1024]<new>$list[res_1400_1050]<new>$list[res_1440_900]<new>$list[res_1600_1200]<new>$list[res_1680_1050]<new>$list[res_1920_1200]<new>$list[res_other]<new>\n");
 }
 elseif ($filename == "$path_data/browser.dat") {
 fputs ($fp, "$list[browser_ie3]<new>$list[browser_ie4]<new>$list[browser_ie5]<new>$list[browser_ie6]<new>$list[browser_ie]<new>$list[browser_nn4]<new>$list[browser_nn6]<new>$list[browser_opera]<new>$list[browser_lynx]<new>$list[browser_konqueror]<new>$list[browser_firefox]<new>$list[browser_nn7]<new>$list[browser_nn8]<new>$list[browser_opera7]<new>$list[browser_opera8]<new>$list[browser_safari]<new>$list[browser_sylera]<new>$list[browser_icab]<new>$list[browser_galeon]<new>$list[browser_epiphany]<new>$list[browser_camino]<new>$list[browser_other]<new>\n");
 }
 elseif ($filename == "$path_data/stats.dat") {
 fputs ($fp, "$list[adate]<new>$list[sadate]<new>$list[hits]<new>$list[unique]<new>$list[ip]<new>$list[referer]<new>\n");
 }
 elseif ($filename == "$path_data/js.dat") {
 fputs ($fp, "$list[json]<new>$list[jsoff]<new>\n");
 }
 elseif ($filename == "$path_data/ip.dat") {
 fputs ($fp, "$list");
 }
 elseif ($filename == "$path_data/lang.dat") {
 $temp = (join($list, "<p>")."<p>\n");
 fputs ($fp, $temp);
 }
 flock( $fp, 1);
 fclose ($fp);
}

function readlang() {
include("config.php");
 $filename = "$path_data/lang.dat";
 $line1 = file($filename);
   $line1 = split("<p>", $line1[0]);
  $la['ac'] = $line1[0];
  $la['ad'] = $line1[1];
  $la['ae'] = $line1[2];
  $la['af'] = $line1[3];
  $la['ag'] = $line1[4];
  $la['ai'] = $line1[5];
  $la['al'] = $line1[6];
  $la['am'] = $line1[7];
  $la['an'] = $line1[8];
  $la['ao'] = $line1[9];
  $la['aq'] = $line1[10];
  $la['ar'] = $line1[11];
  $la['as'] = $line1[12];
  $la['at'] = $line1[13];
  $la['au'] = $line1[14];
  $la['aw'] = $line1[15];
  $la['az'] = $line1[16];
  $la['ba'] = $line1[17];
  $la['bb'] = $line1[18];
  $la['bd'] = $line1[19];
  $la['be'] = $line1[20];
  $la['bf'] = $line1[21];
  $la['bg'] = $line1[22];
  $la['bh'] = $line1[23];
  $la['bi'] = $line1[24];
  $la['bj'] = $line1[25];
  $la['bm'] = $line1[26];
  $la['bn'] = $line1[27];
  $la['bo'] = $line1[28];
  $la['br'] = $line1[29];
  $la['bs'] = $line1[30];
  $la['bt'] = $line1[31];
  $la['bv'] = $line1[32];
  $la['bw'] = $line1[33];
  $la['by'] = $line1[34];
  $la['bz'] = $line1[35];
  $la['ca'] = $line1[36];
  $la['cc'] = $line1[37];
  $la['cf'] = $line1[38];
  $la['cg'] = $line1[39];
  $la['ch'] = $line1[40];
  $la['ci'] = $line1[41];
  $la['ck'] = $line1[42];
  $la['cl'] = $line1[43];
  $la['cm'] = $line1[44];
  $la['cn'] = $line1[45];
  $la['co'] = $line1[46];
  $la['com'] = $line1[47];
  $la['cr'] = $line1[48];
  $la['cs'] = $line1[49];
  $la['cu'] = $line1[50];
  $la['cv'] = $line1[51];
  $la['cx'] = $line1[52];
  $la['cy'] = $line1[53];
  $la['cz'] = $line1[54];
  $la['de'] = $line1[55];
  $la['dj'] = $line1[56];
  $la['dk'] = $line1[57];
  $la['dm'] = $line1[58];
  $la['do'] = $line1[59];
  $la['dz'] = $line1[60];
  $la['ec'] = $line1[61];
  $la['edu'] = $line1[62];
  $la['ee'] = $line1[63];
  $la['eg'] = $line1[64];
  $la['eh'] = $line1[65];
  $la['er'] = $line1[66];
  $la['es'] = $line1[67];
  $la['et'] = $line1[68];
  $la['fi'] = $line1[69];
  $la['fj'] = $line1[70];
  $la['fk'] = $line1[71];
  $la['fm'] = $line1[72];
  $la['fo'] = $line1[73];
  $la['fr'] = $line1[74];
  $la['fx'] = $line1[75];
  $la['ga'] = $line1[76];
  $la['gb'] = $line1[77];
  $la['gd'] = $line1[78];
  $la['ge'] = $line1[79];
  $la['gf'] = $line1[80];
  $la['gh'] = $line1[81];
  $la['gi'] = $line1[82];
  $la['gl'] = $line1[83];
  $la['gm'] = $line1[84];
  $la['gn'] = $line1[85];
  $la['gov'] = $line1[86];
  $la['gp'] = $line1[87];
  $la['gq'] = $line1[88];
  $la['gr'] = $line1[89];
  $la['gs'] = $line1[90];
  $la['gt'] = $line1[91];
  $la['gu'] = $line1[92];
  $la['gw'] = $line1[93];
  $la['gy'] = $line1[94];
  $la['hk'] = $line1[95];
  $la['hm'] = $line1[96];
  $la['hn'] = $line1[97];
  $la['hr'] = $line1[98];
  $la['ht'] = $line1[99];
  $la['hu'] = $line1[100];
  $la['is'] = $line1[101];
  $la['id'] = $line1[102];
  $la['ie'] = $line1[103];
  $la['il'] = $line1[104];
  $la['in'] = $line1[105];
  $la['io'] = $line1[106];
  $la['iq'] = $line1[107];
  $la['ir'] = $line1[108];
  $la['it'] = $line1[109];
  $la['jm'] = $line1[110];
  $la['jo'] = $line1[111];
  $la['jp'] = $line1[112];
  $la['ke'] = $line1[113];
  $la['kg'] = $line1[114];
  $la['kh'] = $line1[115];
  $la['ki'] = $line1[116];
  $la['km'] = $line1[117];
  $la['kn'] = $line1[118];
  $la['kp'] = $line1[119];
  $la['kr'] = $line1[120];
  $la['kw'] = $line1[121];
  $la['ky'] = $line1[122];
  $la['kz'] = $line1[123];
  $la['la'] = $line1[124];
  $la['lb'] = $line1[125];
  $la['lc'] = $line1[126];
  $la['li'] = $line1[127];
  $la['lk'] = $line1[128];
  $la['lr'] = $line1[129];
  $la['ls'] = $line1[130];
  $la['lt'] = $line1[131];
  $la['lu'] = $line1[132];
  $la['lv'] = $line1[133];
  $la['ly'] = $line1[134];
  $la['ma'] = $line1[135];
  $la['mc'] = $line1[136];
  $la['md'] = $line1[137];
  $la['mg'] = $line1[138];
  $la['mh'] = $line1[139];
  $la['mil'] = $line1[140];
  $la['mk'] = $line1[141];
  $la['ml'] = $line1[142];
  $la['mm'] = $line1[143];
  $la['mn'] = $line1[144];
  $la['mo'] = $line1[145];
  $la['mp'] = $line1[146];
  $la['mq'] = $line1[147];
  $la['mr'] = $line1[148];
  $la['ms'] = $line1[149];
  $la['mt'] = $line1[150];
  $la['mu'] = $line1[151];
  $la['mv'] = $line1[152];
  $la['mw'] = $line1[153];
  $la['mx'] = $line1[154];
  $la['my'] = $line1[155];
  $la['mz'] = $line1[156];
  $la['na'] = $line1[157];
  $la['nc'] = $line1[158];
  $la['ne'] = $line1[159];
  $la['net'] = $line1[160];
  $la['nf'] = $line1[161];
  $la['ng'] = $line1[162];
  $la['ni'] = $line1[163];
  $la['nl'] = $line1[164];
  $la['no'] = $line1[165];
  $la['np'] = $line1[166];
  $la['nr'] = $line1[167];
  $la['nt'] = $line1[168];
  $la['nu'] = $line1[169];
  $la['nz'] = $line1[170];
  $la['om'] = $line1[171];
  $la['org'] = $line1[172];
  $la['pa'] = $line1[173];
  $la['pe'] = $line1[174];
  $la['pf'] = $line1[175];
  $la['pg'] = $line1[176];
  $la['ph'] = $line1[177];
  $la['pk'] = $line1[178];
  $la['pl'] = $line1[179];
  $la['pm'] = $line1[180];
  $la['pn'] = $line1[181];
  $la['pr'] = $line1[182];
  $la['pt'] = $line1[183];
  $la['pw'] = $line1[184];
  $la['py'] = $line1[185];
  $la['qa'] = $line1[186];
  $la['re'] = $line1[187];
  $la['ro'] = $line1[188];
  $la['ru'] = $line1[189];
  $la['rw'] = $line1[190];
  $la['sa'] = $line1[191];
  $la['sb'] = $line1[192];
  $la['sc'] = $line1[193];
  $la['sd'] = $line1[194];
  $la['se'] = $line1[195];
  $la['sg'] = $line1[196];
  $la['sh'] = $line1[197];
  $la['si'] = $line1[198];
  $la['sj'] = $line1[199];
  $la['sk'] = $line1[200];
  $la['sl'] = $line1[201];
  $la['sm'] = $line1[202];
  $la['sn'] = $line1[203];
  $la['so'] = $line1[204];
  $la['sr'] = $line1[205];
  $la['st'] = $line1[206];
  $la['su'] = $line1[207];
  $la['sv'] = $line1[208];
  $la['sy'] = $line1[209];
  $la['sz'] = $line1[210];
  $la['tc'] = $line1[211];
  $la['td'] = $line1[212];
  $la['tf'] = $line1[213];
  $la['tg'] = $line1[214];
  $la['th'] = $line1[215];
  $la['tj'] = $line1[216];
  $la['tk'] = $line1[217];
  $la['tm'] = $line1[218];
  $la['tn'] = $line1[219];
  $la['to'] = $line1[220];
  $la['tp'] = $line1[221];
  $la['tr'] = $line1[222];
  $la['tt'] = $line1[223];
  $la['tv'] = $line1[224];
  $la['tw'] = $line1[225];
  $la['tz'] = $line1[226];
  $la['ua'] = $line1[227];
  $la['ug'] = $line1[228];
  $la['uk'] = $line1[229];
  $la['um'] = $line1[230];
  $la['us'] = $line1[231];
  $la['uy'] = $line1[232];
  $la['uz'] = $line1[233];
  $la['va'] = $line1[234];
  $la['vc'] = $line1[235];
  $la['ve'] = $line1[236];
  $la['vg'] = $line1[237];
  $la['vi'] = $line1[238];
  $la['vn'] = $line1[239];
  $la['vu'] = $line1[240];
  $la['wf'] = $line1[241];
  $la['ws'] = $line1[242];
  $la['ye'] = $line1[243];
  $la['yt'] = $line1[244];
  $la['yu'] = $line1[245];
  $la['za'] = $line1[246];
  $la['zm'] = $line1[247];
  $la['zr'] = $line1[248];
  $la['zw'] = $line1[249];
  $la['other'] = $line1[250];
return $la;
}

function readbrowser() {
include("config.php");
 $filename = "$path_data/browser.dat";
 $line1 = file($filename);
     $line1 = split("<new>", $line1[0]);

     $browserlist[browser_ie3] = $line1[0];
     $browserlist[browser_ie4] = $line1[1];
     $browserlist[browser_ie5] = $line1[2];
     $browserlist[browser_ie6] = $line1[3];
     $browserlist[browser_ie] = $line1[4];
     $browserlist[browser_nn4] = $line1[5];
     $browserlist[browser_nn6] = $line1[6];
     $browserlist[browser_opera] = $line1[7];
     $browserlist[browser_lynx] = $line1[8];
     $browserlist[browser_konqueror] = $line1[9];
     $browserlist[browser_firefox] = $line1[10];
     $browserlist[browser_nn7] = $line1[11];
     $browserlist[browser_nn8] = $line1[12];
     $browserlist[browser_opera7] = $line1[13];
     $browserlist[browser_opera8] = $line1[14];
     $browserlist[browser_safari] = $line1[15];
     $browserlist[browser_sylera] = $line1[16];
     $browserlist[browser_icab] = $line1[17];
     $browserlist[browser_galeon] = $line1[18];
     $browserlist[browser_epiphany] = $line1[19];
     $browserlist[browser_camino] = $line1[20];
     $browserlist[browser_other] = $line1[21];

 return $browserlist;
}

function readjs() {
include("config.php");
 $filename = "$path_data/js.dat";
 $line1 = file($filename);
     $line1 = split("<new>", $line1[0]);
     $jslist[json] = $line1[0];
	 $jslist[jsoff] = $line1[1];
 return $jslist;
}

function readip() {
include("config.php");
 $filename = "$path_data/ip.dat";
 $line1 = file($filename);
     $llist = $line1[0];
return $llist;
}

function readstat() {
include("config.php");
 $filename = "$path_data/stats.dat";
  $line1 = file($filename);
     $line1 = split("<new>", $line1[0]);
     $statlist[adate] = $line1[0];
     $statlist[sadate] = $line1[1];
     $statlist[hits] = $line1[2];
     $statlist[unique] = $line1[3];
     $statlist[ip] = $line1[4];
	 $statlist[referer] = $line1[5];
  return $statlist;
}

function readscreen() {
include("config.php");
$filename= "$path_data/screen.dat";
 $line1 = file($filename);
     $line1 = split("<new>", $line1[0]);
     $screenlist[color_256] = $line1[0];
     $screenlist[color_16] = $line1[1];
     $screenlist[color_24] = $line1[2];
     $screenlist[color_32] = $line1[3];
     $screenlist[color_other] = $line1[4];
     $screenlist[res_640_480] = $line1[5];
     $screenlist[res_720_480] = $line1[6];
     $screenlist[res_800_600] = $line1[7];
     $screenlist[res_832_624] = $line1[8];
     $screenlist[res_848_480] = $line1[9];
     $screenlist[res_1024_768] = $line1[10];
     $screenlist[res_1088_880] = $line1[11];
     $screenlist[res_1152_864] = $line1[12];
     $screenlist[res_1280_800] = $line1[13];
     $screenlist[res_1280_854] = $line1[14];
	 $screenlist[res_1280_960] = $line1[15];
     $screenlist[res_1280_1024] = $line1[16];
     $screenlist[res_1400_1050] = $line1[17];
     $screenlist[res_1440_900] = $line1[18];
     $screenlist[res_1600_1200] = $line1[19];
     $screenlist[res_1680_1050] = $line1[20];
     $screenlist[res_1920_1200] = $line1[21];
     $screenlist[res_other] = $line1[22];
  return $screenlist;
}

function reados() {
include("config.php");
 $filename = "$path_data/os.dat";
   $line1 = file($filename);
     $line1 = split("<new>", $line1[0]);
     $oslist[os_95] = $line1[0];
     $oslist[os_31] = $line1[1];
     $oslist[os_98] = $line1[2];
     $oslist[os_me] = $line1[3];
     $oslist[os_nt] = $line1[4];
     $oslist[os_2000] = $line1[5];
     $oslist[os_xp] = $line1[6];
     $oslist[os_linux] = $line1[7];
     $oslist[os_unix] = $line1[8];
     $oslist[os_mac] = $line1[9];
     $oslist[os_3195] = $line1[10];
     $oslist[os_95nt] = $line1[11];
     $oslist[os_other] = $line1[12];
 return $oslist;
}

function webstat($oslist, $browserlist, $statlist, $screenlist, $la, $jslist, $color, $referer, $res, $js, $iplist) {
include("config.php");
 global $REMOTE_ADDR;
$ip = getenv('REMOTE_ADDR');
if (strstr($ip, ', ')) {
   $ips = explode(', ', $ip);
   $ip = $ips[0];
}
 $host = @GetHostByAddr($ip);
  if ($host != NULL){
	 $top = (strrchr($host, "."));
     $top = (ereg_replace("\.", "", $top));
  	 } else {
      $top = "";
	  }
	 function in_array_key($key, $array, $value = false) {
      while(list($k, $v) = each($array)) {
        if($key == $k) {
            if($value && $value == $v)
                return true;
             elseif($value && $value != $v)
                return false;
            else
                return true;
        }
    }
    return false;
}
	 if (in_array_key($top, $la)) {
      $la[$top]++;
      } else {
       $la['other']++;
      }
	  if ($js == "yes") {
	  $jslist[json]++;
	  } else {
	  $jslist[jsoff]++;
	  }
  $ip = getenv("REMOTE_ADDR");
  $num="(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])";
  if (preg_match("/^$num\\.$num\\.$num\\.$num$/", $ip))
  {
    if(!ereg($ip, $iplist))
  	{
  		$tarray = explode("<n>", $iplist);
  		if(count($tarray) >= 100)
  		{
  			$test = array_shift($tarray);
  			$iplist = implode("<n>", $tarray);
  		}
  		$iplist .= $ip;
  		$iplist .= "<n>";
  	}
  }
  $date = date("j/n Y");
  if (ereg($date, $statlist[adate]) && ereg($ip, $statlist[ip])) {
     $statlist[hits]++;
     } else {
     $statlist[unique]++;
	 $statlist[hits]++;
  }
  if (ereg($ip, $statlist[ip])) {
     $ip = NULL;
     } else {
     $ip .= " ";
	 }
  if (ereg($date, $statlist[adate])) {
     $statlist[ip] .= $ip;
     } else {
     $statlist[ip] = NULL;
     }
 $statlist[adate] = $date;

 if ($referer != NULL)
 {
   $refsplit = explode("<refnew>", $statlist[referer]);
   $isengine = "no";
   if (eregi("google|msn|altavista", $referer))
   {
      $isengine = "yes";
      $thisurl = parse_url($referer);
      parse_str($thisurl[query], $outputurl);

      if($outputurl[q] != "")
      {
         $outputurl[q] = strtolower($outputurl[q]);
         $outputurl[q] = str_replace("\"", "", $outputurl[q]);

         $filename = "$path_data/keywords.dat";

	     $kl = file($filename);
	     $klist = trim($kl[0]);
	     $kstat = trim($kl[1]);

    	 $prevkeylist = explode("<n>", $klist);

         $prevkeystat = explode("<n>", $kstat);

         if(ereg(" ", $outputurl[q]))
         {
            $keywordlist = explode(" ", $outputurl[q]);

              for($i = 0; $i < count($keywordlist); $i++)
              {
                 if($skey = array_search($keywordlist[$i], $prevkeylist))
                 {
                     $prevkeystat[$skey]++;
                 }
                 else
                 {
    				 $prevkeylist[] = $keywordlist[$i];
    				 $prevkeystat[] = 1;
                 }
              }
         }
		 else
		 {
             if($skey = array_search($outputurl[q], $prevkeylist))
             {
                 $prevkeystat[$skey]++;
             }
             else
             {
				 $prevkeylist[] = $outputurl[q];
				 $prevkeystat[] = 1;
             }
         }
         $fp11 = fopen($filename, 'wb');
	     flock( $fp11, 2);
         $klist1 = implode("<n>", $prevkeylist);
         $kstat1 = implode("<n>", $prevkeystat);

	     fputs($fp11, "$klist1 \n $kstat1");
         flock( $fp11, 1);
         fclose ($fp11);
      }
   }

   if(count($refsplit) >= 100)
   {
     $nouse = array_shift($refsplit);
     $statlist[referer] = implode("<refnew>", $refsplit);
     $referer .= "<refnew>";
     $statlist[referer] .= $referer;
   }
   else
   {
      $referer .= "<refnew>";
      $statlist[referer] .= $referer;
   }
 }
 $browser = getenv("HTTP_USER_AGENT");
  if (preg_match("/opera 8/i", $browser)) {
         $browserlist[browser_opera8]++;
         }
  elseif (preg_match("/opera\/8/i", $browser)) {
         $browserlist[browser_opera8]++;
         }
  elseif (preg_match("/opera 7/i", $browser)) {
         $browserlist[browser_opera7]++;
         }
  elseif (preg_match("/opera\/7/i", $browser)) {
         $browserlist[browser_opera7]++;
         }
  elseif (preg_match("/opera/i", $browser)) {
         $browserlist[browser_opera]++;
         }
  elseif (preg_match("/konqueror/i", $browser)) {
         $browserlist[browser_konqueror]++;
		 }
  elseif (preg_match("/safari/i", $browser)) {
         $browserlist[browser_safari]++;
		 }
  elseif (preg_match("/sylera/i", $browser)) {
         $browserlist[browser_sylera]++;
		 }
  elseif (preg_match("/icab/i", $browser)) {
         $browserlist[browser_icab]++;
		 }
  elseif (preg_match("/galeon/i", $browser)) {
         $browserlist[browser_galeon]++;
		 }
  elseif (preg_match("/epiphany/i", $browser)) {
         $browserlist[browser_epiphany]++;
		 }
  elseif (preg_match("/chimera/i", $browser)) {
         $browserlist[browser_camino]++;
		 }
  elseif (preg_match("/camino/i", $browser)) {
         $browserlist[browser_camino]++;
		 }
  elseif (preg_match("/firefox/i", $browser)) {
         $browserlist[browser_firefox]++;
         }
  elseif (preg_match("/firebird/i", $browser)) {
         $browserlist[browser_firefox]++;
         }
  elseif (preg_match("/lynx/i", $browser)) {
         $browserlist[browser_lynx]++;
		 }
  elseif (preg_match("/netscape\/7/i", $browser)) {
         $browserlist[browser_nn7]++;
         }
  elseif (preg_match("/netscape\/8/i", $browser)) {
         $browserlist[browser_nn8]++;
         }
  elseif (preg_match("/msie 3/i", $browser)) {
         $browserlist[browser_ie3]++;
         }
  elseif (preg_match("/msie 4/i", $browser)) {
         $browserlist[browser_ie4]++;
		 }
  elseif (preg_match("/msie 5/i", $browser)) {
         $browserlist[browser_ie5]++;
		 }
  elseif (preg_match("/msie 6/i", $browser)) {
         $browserlist[browser_ie6]++;
		 }
  elseif (preg_match("/msie/i", $browser)) {
         $browserlist[browser_ie]++;
		 }
  elseif (preg_match("/mozilla\/6/i", $browser)) {
         $browserlist[browser_nn6]++;
         }
  elseif (preg_match("/mozilla/i", $browser)) {
         $browserlist[browser_nn4]++;
         }
  else {
         $browserlist[browser_other]++;
	     }

  if ($color == "8") {
     $screenlist[color_256]++;
     } elseif ($color == "16") {
     $screenlist[color_16]++;
     } elseif ($color == "24") {
     $screenlist[color_24]++;
     } elseif ($color == "32") {
     $screenlist[color_32]++;
     } else {
     $screenlist[color_other]++;
     }
  if ($res == "640x480") {
     $screenlist[res_640_480]++;
     } elseif ($res == "720x480") {
     $screenlist[res_720_480]++;
     } elseif ($res == "800x600") {
     $screenlist[res_800_600]++;
     } elseif ($res == "832x624") {
     $screenlist[res_832_624]++;
     } elseif ($res == "848x480") {
     $screenlist[res_848_480]++;
     } elseif ($res == "1024x768") {
     $screenlist[res_1024_768]++;
     } elseif ($res == "1152x864") {
     $screenlist[res_1152_864]++;
     } elseif ($res == "1280x800") {
	 $screenlist[res_1280_800]++;
	 } elseif ($res == "1280x854") {
     $screenlist[res_1280_854]++;
     } elseif ($res == "1280x960") {
     $screenlist[res_1280_960]++;
     } elseif ($res == "1280x1024") {
     $screenlist[res_1280_1024]++;
     } elseif ($res == "1400x1050") {
     $screenlist[res_1400_1050]++;
     } elseif ($res == "1440x900") {
     $screenlist[res_1440_900]++;
     } elseif ($res == "1600x1200") {
     $screenlist[res_1600_1200]++;
     } elseif ($res == "1088x880") {
     $screenlist[res_1088_880]++;
     } elseif ($res == "1680x1050") {
     $screenlist[res_1680_1050]++;
     } elseif ($res == "1920x1200") {
     $screenlist[res_1920_1200]++;
     } else {
     $screenlist[res_other]++;
     }
	 $os = getenv("HTTP_USER_AGENT");
   if (preg_match("/win95/i", $os)) {
      $oslist[os_95]++;
      }
      elseif (preg_match("/Win 9x 4\.9/i", $os)) {
      $oslist[os_me]++;
      }
	  elseif (preg_match("/win98/i", $os)) {
      $oslist[os_98]++;
      }
	  elseif (preg_match("/winnt/i", $os)) {
      $oslist[os_nt]++;
      }
	  elseif (preg_match("/windows 2000/i", $os)) {
      $oslist[os_2000]++;
      }
	  elseif (preg_match("/windows me/i", $os)) {
      $oslist[os_me]++;
      }
	  elseif (preg_match("/windows xp/i", $os)) {
      $oslist[os_xp]++;
      }
	  elseif (preg_match("/winweb/i", $os)) {
	  $oslist[os_3195]++;
	  }
	  elseif (preg_match("/windows 95/i", $os)) {
      $oslist[os_95]++;
      }
	  elseif (preg_match("/windows 98/i", $os)) {
      $oslist[os_98]++;
      }
	  elseif (preg_match("/Windows NT 5\.1/i", $os)) {
      $oslist[os_xp]++;
      }
	  elseif (preg_match("/windows nt 5/i", $os)) {
      $oslist[os_2000]++;
      }
	  elseif (preg_match("/windows nt/i", $os)) {
      $oslist[os_nt]++;
      }
	  elseif (preg_match("/win16/i", $os)) {
	  $oslist[os_3195]++;
	  }
	  elseif(preg_match("/windows 3\.1/i", $os)) {
	  $oslist[os_31]++;
	  }
	  elseif (preg_match("/win32/i", $os)) {
	  $oslist[os_95nt]++;
	  }
	  elseif (preg_match("/windows/i", $os)) {
	   if (preg_match("/32bit/i", $os)) {
	      $oslist[os_95nt]++;
		  } else {
		    $oslist[os_3195]++;
		  }
	  }
	  elseif (preg_match("/iweng/i", $os)) {
	  $oslist[os_3195]++;
	  }
	  elseif (preg_match("/mac/i", $os)) {
      $oslist[os_mac]++;
      }
	  elseif (preg_match("/linux/i", $os)) {
      $oslist[os_linux]++;
	  }
	  elseif (preg_match("/konqueror/i", $os)) {
	  $oslist[os_linux]++;
	  }
	  elseif (preg_match("/hp-ux/i", $os)) {
      $oslist[os_unix]++;
	  }
	  elseif (preg_match("/sunos/i", $os)) {
      $oslist[os_unix]++;
	  }
	  elseif (preg_match("/x11/i", $os)) {
      $oslist[os_unix]++;
	  } else {
	  $oslist[os_other]++;
      }



    $statfile = "$path_data/stats.dat";
    $osfile = "$path_data/os.dat";
    $browserfile = "$path_data/browser.dat";
    $screenfile = "$path_data/screen.dat";
    $lafile = "$path_data/lang.dat";
	$jsfile = "$path_data/js.dat";
	$ipfile = "$path_data/ip.dat";
    write($statlist, $statfile);
    write($oslist, $osfile);
    write($browserlist, $browserfile);
    write($screenlist, $screenfile);
    write($la, $lafile);
	write($jslist, $jsfile);
	write($iplist, $ipfile);
}
?>
