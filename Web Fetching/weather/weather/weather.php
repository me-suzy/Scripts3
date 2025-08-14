<font face="Arial, Helvetica, sans-serif" size="4"><SPAN id=neon><b>Current Weather</b></span></font> 
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr> 
    <td valign="top"> <p align=center> 
        <?php

// By I Ketut Sukandia (www.balidwipa.com and www.nusa-penida.com)
// License : free/donation.
// info@nusa-penida.com
// goto http://www.msnbc.com/news/wea_front.asp?ta=y&tab=BW&tp=&sdiv=&rgn=&ctry=&accid=
// and you will find the ACID or ICAO parameter for your city.
// the ACID for Denpasar - Bali is WRRR or IDXX0005
// the ACID for New York NY is LGA or USNY0996
// the ACID for Paris, Fance is LFPO or FRXX0076
// the ACID for Tokyo is RJTT or JAXX0085
// You can find also more ACID code on weather.php
// TO INSTALL JUST COPY TO YOUR public_html/weather folder (you must create weather folder first) and place all file under this folder your just create, php file in ascii and .gif and .jpg file in binary.
// If you use my code plese link back to my web site http://www.nusa-penida.com.
// You also can just insert or incude this php code to any of your php document like me at http://www.nusa-penida.com/index.php 
// (just cut and paste or include this code to any of your php page/php template page) your php file should be under weather folder in your puclic_html, but please remember to replace the IDXX0019 with your city ACCID code.
// that all.



if (!$accid) {
	$accid="IDXX0019"; # Your default city location code, please change to your city ACCID code.
}

if (!$tp) {
	$tp=C; # Your Default temperature unit (C for Celsius, F for Fahrenheit)
}

# Fonts Setting
################
$fontface="Arial";
$fontcolor1="#000000";
$fontcolor2="red";
$fontsize1="1";
$fontsize2="3";
$fontsize3="4";


function WeatherIndex($tp) {
global $accid, $tp;

$url ="http://www.msnbc.com/m/chnk/d/weather_d_src.asp?acid=$accid";

$fa = fopen($url,"r");

		
			
/* $fa = fopen("www.msnbc.com", 80, &$num_error, &$str_error, 30);
if(!$fa)
  { print "Weather is not available: $str_error ($num_error)\n"; }
else
{
  fputs($fa,"GET /m/chnk/d/weather_d_src.asp?acid=$accid HTTP/1.0\n\n");
  $answer=fgets($fa,128);
*/

  $v_City    = "";
  $v_SubDiv  = "";
  $v_Country = "";
  $v_Region  = "";
  $v_Temp    = "";
  $v_CIcon   = "";
  $v_WindS   = "";
  $v_WindD   = "";
  $v_Baro    = "";
  $v_Humid   = "";
  $v_Real    = "";
  $v_UV      = "";
  $v_Vis     = "";
  $v_LastUp  = "";
  $v_Fore    = "";
  $v_Acid    = "";
  $v_ConText = "";

  
$v_Text[] = "Check again later !";
$v_Text[1] = "Rain/Wind";
$v_Text[3] = "Rain/T-Storms";
$v_Text[4] = "T-Storms";
$v_Text[5] = "Cloudy";
$v_Text[6] = "";
$v_Text[7] = "Snow/Icy";
$v_Text[8] = "";
$v_Text[9] = "";
$v_Text[10] = "";
$v_Text[11] = "Light Rain";
$v_Text[12] = "Rain";
$v_Text[13] = "Scattered Flurreis";
$v_Text[14] = "Light Snow";
$v_Text[15] = "";
$v_Text[16] = "";
$v_Text[17] = "";
$v_Text[18] = "";
$v_Text[19] = "Dusty";
$v_Text[20] = "Foggy";
$v_Text[21] = "Haze";
$v_Text[22] = "Smoke";
$v_Text[23] = "Wind";
$v_Text[24] = "Cloudy/Wind";
$v_Text[25] = "Extremely Cold";
$v_Text[26] = "Cloudy";
$v_Text[27] = "Mostly Cloudy";
$v_Text[28] = "Mostly Cloudy";
$v_Text[29] = "Partly Cloudy";
$v_Text[30] = "Partly Cloudy";
$v_Text[31] = "Partly Cloudy";
$v_Text[32] = "Sunny";
$v_Text[33] = "";
$v_Text[34] = "Mostly Sunny";
$v_Text[35] = "";
$v_Text[36] = "Extremely Hot";
$v_Text[37] = "Isolated T-Storms";
$v_Text[38] = "Sct T-Storms";
$v_Text[39] = "Showers";
$v_Text[40] = "Showers";
$v_Text[41] = "Snow";
$v_Text[41] = "Light Wind";
$v_Text[43] = "Snow/Wind";
$v_Text[44] = "Partly Coudy";



  while (!feof($fa))
     {
     $grabline = fgets($fa, 4096);
     $grabline= trim($grabline) . "\n";
     if (substr($grabline,7,4) == "City")    { $v_City    = substr($grabline,15,20); }
     if (substr($grabline,7,6) == "SubDiv")  { $v_SubDiv  = substr($grabline,17,20); }
     if (substr($grabline,7,7) == "Country") { $v_Country = substr($grabline,18,20); }
     if (substr($grabline,7,6) == "Region")  { $v_Region  = substr($grabline,17,20); }
     if (substr($grabline,7,5) == "Temp ")    { $v_Temp    = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "CIcon")   { $v_CIcon   = substr($grabline,16,20); }
     if (substr($grabline,7,5) == "WindS")   { $v_WindS   = substr($grabline,16,20); }
     if (substr($grabline,7,5) == "WindD")   { $v_WindD   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Baro")    { $v_Baro    = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "Humid")   { $v_Humid   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Real")    { $v_Real    = substr($grabline,15,20); }
     if (substr($grabline,7,2) == "UV")      { $v_UV      = substr($grabline,13,20); }
     if (substr($grabline,7,3) == "Vis")     { $v_Vis     = substr($grabline,14,20); }
     if (substr($grabline,7,6) == "LastUp")  { $v_LastUp  = substr($grabline,17,200); }
     if (substr($grabline,7,7) == "ConText") { $v_ConText = substr($grabline,16,20); } 


     if (substr($grabline,7,4) == "Fore")    { $v_Fore    = substr($grabline,15,200); }
     if (substr($grabline,7,4) == "Acid")    { $v_Acid    = substr($grabline,15,20); }
//     print $grabline . "\n";
     }

  $v_City    = substr($v_City,0,strlen($v_City)-3);
  $v_SubDiv  = substr($v_SubDiv,0,strlen($v_SubDiv)-3);
  $v_Country = substr($v_Country,0,strlen($v_Country)-3);
  $v_Region  = substr($v_Region,0,strlen($v_Region)-3);
  $v_Temp    = substr($v_Temp,0,strlen($v_Temp)-3);
  $v_CIcon   = substr($v_CIcon,0,strlen($v_CIcon)-3);
  $v_WindS   = substr($v_WindS,0,strlen($v_WindS)-3);
  $v_WindD   = substr($v_WindD,0,strlen($v_WindD)-3);
  $v_Baro    = substr($v_Baro,0,strlen($v_Baro)-3);
  $v_Humid   = substr($v_Humid,0,strlen($v_Humid)-3);
  $v_Real    = substr($v_Real,0,strlen($v_Real)-3);
  $v_UV      = substr($v_UV,0,strlen($v_UV)-3);
  $v_Vis     = substr($v_Vis,0,strlen($v_Vis)-3);
  $v_LastUp  = substr($v_LastUp,0,strlen($v_LastUp)-3);
  $v_Fore    = substr($v_Fore,0,strlen($v_Fore)-3);
  $v_Acid    = substr($v_Acid,0,strlen($v_Acid)-3);


$v_Fore = explode("|", $v_Fore);


echo "<div align=\"right\"><font size=\"1\" face=\"Verdana\">All Temperatures can be shown in: ";
if ($tp=="C") {
	echo "<b><a href=\"index.php?tp=C&amp;accid=$accid\">Celcius</a></b> or ";
	echo "<a href=\"index.php?tp=F&amp;accid=$accid\">Fahrenheit</a>";
} else {
	echo "<b><a href=\"index.php?tp=F&amp;accid=$accid\">Fahrenheit</a></b> or ";
	echo "<a href=\"index.php?tp=C&amp;accid=$accid\">Celcius</a>";
}
echo "</font></div><font size=2>";

echo "Region: </font><font color=navy size=2><b>$v_Region</b></font><br>";
echo "<font size=4 color=red><b>$v_City, $v_Country</b></font><hr size=1 noshade color=#999999>";
echo "<div align=right><font size=1 face=Verdana color=navy>Last Updated: $v_LastUp</font></div>";
?>
      <table cellpadding="4" cellspacing="0" border="0">
        <tr>
          <td align="center" valign="top"> <img src="current_cond.gif" align="absmiddle" alt="Current Conditions"> 
          </td>
          <td> <font size="5" face="Arial" color="red"><b> 
            <?
if ($tp == "C") {
	echo CelNumber($v_Temp);
} else {
	echo " $v_Temp&deg;F";
}
echo "</b></font></td><td align=\"center\">";
echo "<img src=\"i/".$v_CIcon . ".gif\" align=\"absmiddle\"><br>";
echo "<font size=\"1\" face=\"Verdana\"><b> ".$v_Text[$v_CIcon]." </b></font>";
?>
            </b></font></td>
          <td><table cellpadding="1" cellspacing="1" border="0">
              <tr>
                <td> 
                  <?
$v_Baro_nice = PreNumber($v_Baro, $tp);
$v_WindS_nice = VelNumber($v_WindS, $tp);
$v_Real_nice = CelNumber2($v_Real, $tp);
$v_Vis_nice = LenNumber($v_Vis, $tp);

echo "<font size=\"1\" face=\"Verdana\" color=\"navy\">Feels Like:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_Real_nice</b></td>";
echo "<td><font size=\"1\" face=\"Verdana\" color=\"navy\">UV Index:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_UV</b></td></tr><tr>";
echo "<td><font size=\"1\" face=\"Verdana\" color=\"navy\">Humidity:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_Humid%</b></td>";
echo "<td><font size=\"1\" face=\"Verdana\" color=\"navy\">Wind:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_WindD $v_WindS_nice</b></td></tr><tr>";
echo "<td><font size=\"1\" face=\"Verdana\" color=\"navy\">Visibility:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_Vis_nice</b></td>";
echo "<td><font size=\"1\" face=\"Verdana\" color=\"navy\">Barometer:</font></td><td><font size=\"1\" face=\"Verdana\"><b>$v_Baro_nice</b></td>";
?>
              </tr>
            </table></td>
        </tr>
      </table>
      <hr size=1 noshade color=#999999> <table cellpadding="4" cellspacing="0" border="0">
        <tr>
          <td valign="top"> <img src="forecast.gif" alt="5 Day Forecast"> </td>
          <td align=center><font size="1" face="Verdana"><b> 
            <?
echo Fore($v_Fore[0]);
if ($tp == "C") {
echo "</b></font><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo CelNumber($v_Fore[20]);
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo CelNumber($v_Fore[40]);
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
} else if ($tp=="F") {
echo "</b><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo "$v_Fore[20]&deg;F";
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo "$v_Fore[40]&deg;F";
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
}
?>
            <?
echo Fore($v_Fore[1]);
if ($tp == "C") {
echo "</b></font><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo CelNumber($v_Fore[21]);
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo CelNumber($v_Fore[41]);
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
} else if ($tp=="F") {
echo "</b><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo "$v_Fore[21]&deg;F";
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo "$v_Fore[41]&deg;F";
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
}
?>
            <?
echo Fore($v_Fore[2]);
if ($tp == "C") {
echo "</b></font><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo CelNumber($v_Fore[22]);
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo CelNumber($v_Fore[42]);
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
} else if ($tp=="F") {
echo "</b><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo "$v_Fore[22]&deg;F";
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo "$v_Fore[42]&deg;F";
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
}
?>
            <?
echo Fore($v_Fore[3]);
if ($tp == "C") {
echo "</b></font><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo CelNumber($v_Fore[23]);
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo CelNumber($v_Fore[43]);
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
} else if ($tp=="F") {
echo "</b><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo "$v_Fore[23]&deg;F";
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo "$v_Fore[43]&deg;F";
echo "</font></td><td align=center><font size=\"1\" face=\"Verdana\"><b>";
}
?>
            <?
echo Fore($v_Fore[4]);
if ($tp == "C") {
echo "</b></font><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo CelNumber($v_Fore[24]);
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo CelNumber($v_Fore[44]);
echo "</font></td></tr><tr><td align=center>&nbsp;</td>";
} else if ($tp=="F") {
echo "</b><br><font face=\"Verdana\" size=\"1\" color=\"red\">Hi: ";
echo "$v_Fore[24]&deg;F";
echo "</font><br><font face=\"Verdana\" size=\"1\" color=\"navy\">Lo: ";
echo "$v_Fore[44]&deg;F";
echo "</font></td></tr><tr><td align=center>&nbsp;</td>";
}
?>
            <?
echo "<td align=center><img src=\"i/".$v_Fore[10].".gif\"></td><td align=center>";
echo "<img src=\"i/".$v_Fore[11].".gif\"></td><td align=center>";
echo "<img src=\"i/".$v_Fore[12].".gif\"></td><td align=center>";
echo "<img src=\"i/".$v_Fore[13].".gif\"></td><td align=center>";
echo "<img src=\"i/".$v_Fore[14].".gif\"></td></tr><tr><td align=center>&nbsp;</td>";
echo "<td align=center><font face=\"Verdana\" size=\"1\" color=\"black\">".$v_Text[$v_Fore[10]]."</font></td>";
echo "<td align=center><font face=\"Verdana\" size=\"1\" color=\"black\">".$v_Text[$v_Fore[11]]."</font></td>";
echo "<td align=center><font face=\"Verdana\" size=\"1\" color=\"black\">".$v_Text[$v_Fore[12]]."</font></td>";
echo "<td align=center><font face=\"Verdana\" size=\"1\" color=\"black\">".$v_Text[$v_Fore[13]]."</font></td>";
echo "<td align=center><font face=\"Verdana\" size=\"1\" color=\"black\">".$v_Text[$v_Fore[14]]."</font></td>";
?>
            </b></font></tr>
      </table>
      <br>
      <br> 
      <?
echo "<div align=right>Extended forecast at <a href=\"http://www.weather.com/outlook/travel/detail/$accid\" target=\"_blank\">The Weather Channel</a>®<br>";
echo "<a href=\"http://www.weather.com/outlook/travel/map/$accid?from=LAPmaps&name=index_large_animated&day=1\" target=\"_blank\">Map in Motion</a>  -  <a href=\"http://www.weather.com/outlook/travel/map/$accid?from=LAPmaps\" target=\"_blank\">More Local Maps</a>";
echo "</div>";
?>
      <hr size=1 noshade color=#999999> 
      <?
fclose($fa);
}
?>
      <div align=right>
        <form method="POST" action="">
          Select City: 
          <select name="accid" onChange="this.form.submit()">
            <option <? if ($accid=="YPAD"){echo"selected";} ?> value="YPAD">Adelaide</option>
            <option <? if ($accid=="ANC"){echo"selected";} ?> value="ANC">Anchorage</option>
            <option <? if ($accid=="NZAA"){echo"selected";} ?> value="NZAA">Auckland</option>
            <option <? if ($accid=="WRLL"){echo"selected";} ?> value="WRLL">Balikpapan, 
            Kalimantan</option>
            <option <? if ($accid=="WABB"){echo"selected";} ?> value="WABB">Biak, 
            Papua</option>
            <option <? if ($accid=="GCFV"){echo"selected";} ?> value="GCFV">Canary 
            Islands</option>
            <option <? if ($accid=="WRRR"){echo"selected";} ?> value="WRRR">Denpasar, 
            Bali</option>
            <option <? if ($accid=="ADDN"){echo"selected";} ?> value="ADDN">Darwin</option>
            <option <? if ($accid=="SCFC"){echo"selected";} ?> value="SCFC">Frei 
            Chi Base, Antarctica</option>
            <option <? if ($accid=="ALGS"){echo"selected";} ?> value="ALGS">Gulf 
            Shore</option>
            <option <? if ($accid=="HNL"){echo"selected";} ?> value="HNL">Honolulu</option>
            <option <? if ($accid=="WIII"){echo"selected";} ?> value="WIII">Jakarta, 
            Indonesia</option>
            <option <? if ($accid=="PTRO"){echo"selected";} ?> value="PTRO">Koror</option>
            <option <? if ($accid=="LSLU"){echo"selected";} ?> value="LSLU">Lucerne</option>
            <option <? if ($accid=="WAAA"){echo"selected";} ?> value="WAAA">Makassar, 
            Sulawesi</option>
            <option <? if ($accid=="WIMM"){echo"selected";} ?> value="WIMM">Medan, 
            Sumatra</option>
            <option <? if ($accid=="MSMA"){echo"selected";} ?> value="MSMA">Maldives</option>
            <option <? if ($accid=="MAPL"){echo"selected";} ?> value="MAPL">Mauritius</option>
            <option <? if ($accid=="MEX"){echo"selected";} ?> value="MEX">Mexico 
            City</option>
            <option <? if ($accid=="LGA"){echo"selected";} ?> value="LGA">New 
            York</option>
            <option <? if ($accid=="RJOO"){echo"selected";} ?> value="RJOO">Osaka, 
            Japan</option>
            <option <? if ($accid=="ENFB"){echo"selected";} ?> value="ENFB">Oslo</option>
            <option <? if ($accid=="VT25"){echo"selected";} ?> value="VT25">Phuket</option>
            <option <? if ($accid=="YQB"){echo"selected";} ?> value="YQB">Quebec</option>
            <option <? if ($accid=="BZJR"){echo"selected";} ?> value="BZJR">Rio 
            de Janeiro</option>
            <option <? if ($accid=="LPAZ"){echo"selected";} ?> value="LPAZ">Santa 
            Maria Islands</option>
            <option <? if ($accid=="WSAP"){echo"selected";} ?> value="WSAP">Singapore</option>
            <option <? if ($accid=="LBSF"){echo"selected";} ?> value="LBSF">Sofia</option>
            <option <? if ($accid=="WRSJ"){echo"selected";} ?> value="WRSJ">Surabaya, 
            East Java</option>
            <option <? if ($accid=="RJTT"){echo"selected";} ?> value="RJTT">Tokyo, 
            Japan</option>
            <option <? if ($accid=="DTTA"){echo"selected";} ?> value="DTTA">Tunis</option>
            <option <? if ($accid=="TNCC"){echo"selected";} ?> value="TNCC">Willemstad</option>
            <option <? if ($accid=="LLBG"){echo"selected";} ?> value="LLBG">Tel 
            Aviv</option>
          </select>
          <br>
          <small>Powered by <a href="http://www.nusa-penida.com">www.nusa-penida.com</a><br>
          This Weather is provided by MSNBC</small> 
        </form>
      </div>
      <?
//}
function CelNumber($number) {
	$number = $number-32;
	$number = $number * 5;
	$number = $number / 9;
	$number = round ($number);
	return $number."&deg;C";
}
    
function CelNumber2($number,$tp) {
        if ($tp == "C") {                $number = $number-32;
	        $number = $number * 5;
	        $number = $number / 9;
	        $number = round ($number);
	        return "$number&deg;C";
        }       else {                return "$number&deg;F";       } }


function PreNumber($number,$tp) {
	if ($tp == "C") {
		$number = $number *33.86;
		$number = round ($number);
		return "$number mbar";
	}
	else {
		return "$number inHg";
	}
}

function LenNumber($number,$tp) {
	if ($tp == "C") {
		$number = $number * 1.852;
		$number = round ($number);
        if ($number == 0)
        {
                return "N/A";
        }
        else {
		return "$number km";
	}
        }else {
	 if ($number == 0)
           {
	return "N/A";
           }


	else {
		return "$number mi";
	}
}
   } 

function VelNumber($number,$tp) {
	if ($tp == "C") {
		$number = $number * 0.44704;
		$number = round ($number);
		return "$number m/s";
	}
	else {
		return "$number mph";
	}
}

function Fore($numbers) {
	if ($numbers == "1") {
		$date="SUNDAY";
		echo "$date";
	}
	elseif ($numbers == "2") {
		$date="MONDAY";
		echo "$date";
	}
	elseif ($numbers == "3") {
		$date="TUESDAY";
		echo "$date";
	}
	elseif ($numbers == "4") {
		$date="WEDNESDAY";
		echo "$date";
	}
	elseif ($numbers == "5") {
		$date="THURSDAY";
		echo "$date";
	}
	elseif ($numbers == "6") {
		$date="FRIDAY";
		echo "$date";
	} else {
		$date="SATURDAY";
		echo "$date";
	}
}				

# End of code

switch($func) {

    default:
    WeatherIndex($tp);
    break;
}

?>
    </td>
  </tr>
</table>