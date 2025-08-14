
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
// (just cut and paste this code to your php page) your php file can be anywhere under your puclic_html, but please remember to replace the IDXX0019 with your city ACCID code.
// that all.


if (!$accid) {
	$accid="IDXX0019"; # Your default city location code, please change to your city ACCID code.
}

$url ="http://www.msnbc.com/m/chnk/d/weather_d_src.asp?acid=$accid";

$fa = fopen($url,"r");

/*
$fa = fsockopen("www.msnbc.com", 80, &$num_error, &$str_error, 30);
if(!$fa)
   { print "Weather is not available: $str_error ($num_error)\n"; }
else
{
  fputs($fa,"GET /m/chnk/d/weather_d_src.asp?acid=WRRR HTTP/1.0\n\n"); # Replace WRRR with your city ACID code!
  $answer=fgets($fa,128);
*/

  $v_City    = "";
  $v_SubDiv  = "";
  $v_Country = "";
  $v_Region  = "";
  $v_Temp    = "";
  $v_CIcon   = "";
  $v_Clouds  = "";
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

$v_Text[] = "N/A!";
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
     if (substr($grabline,7,5) == "Temp ")    { $v_Temp   = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "CIcon")   { $v_CIcon   = substr($grabline,16,20); }
     if (substr($grabline,7,3) == "Clouds")  { $v_Clouds  = substr($grabline,14,20); }
     if (substr($grabline,7,5) == "WindS")   { $v_WindS   = substr($grabline,16,20); }
     if (substr($grabline,7,5) == "WindD")   { $v_WindD   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Baro")    { $v_Baro    = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "Humid")   { $v_Humid   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Real")    { $v_Real    = substr($grabline,15,20); }
     if (substr($grabline,7,2) == "UV")      { $v_UV      = substr($grabline,13,20); }
     if (substr($grabline,7,3) == "Vis")     { $v_Vis     = substr($grabline,14,20); }
     if (substr($grabline,7,6) == "LastUp")  { $v_LastUp  = substr($grabline,17,20); }
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
  $v_Clouds  = substr($v_Clouds,0,strlen($v_Clouds)-3);
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

  print "<table border=\"0\" width=\"185\" cellspacing=\"1\" cellpadding=\"0\"><tr><td valign=\"top\"><font size=\"1\">";
  print " "     . $v_City     . "\n"; 
  print "<br>"  . $v_Country  . "\n";
  print "<br>"   . $v_Region   . "</font><br>\n";
  print "<img src=\"/weather/i/". $v_CIcon . ".gif\"><br><font size=\"1\" face=\"Verdana\"><b> ".$v_Text[$v_CIcon]." </b></font></td>";
  print "<td valign=\"top\"><font size=\"1\">Temperature: "     . $v_Temp     . "&deg;F<br>\n";
  print "Humidity: "    . $v_Humid    . "%<br>\n";
  print "Barometer: "     . $v_Baro     . "<br>\n";
  print "Wind: "     . $v_WindD    . " \n";
  print "at "    . $v_WindS    . "mph<br>\n";
  print "Real Feel: "     . $v_Real     . "&deg;<br>\n";
  print "UV: "       . $v_UV       . "<br>\n";
  print "Visibility: "      . $v_Vis      . "<br>\n";
  print "</font><font size=\"1\"><a href=\"/weather/\">more</a>...\n";
  print "</font></td></tr><tr><td colspan=2><font size=1>\n";
  print "Updated: "      . $v_LastUp      . "<br>\n";
  print "powered by <a href=\"http://nusa-penida.com\">nusa-penida.com</a></font></td></tr></table>\n";

  fclose($fa);
//}

?>
