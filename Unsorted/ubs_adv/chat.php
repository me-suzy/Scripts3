<?php

require "functions.php";
require "chatconfig.php";
require "data.inc.php";

$userroom_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom = $userroom_stat[userroom];

if($user_name == Guest){
   $file="gendat.dat";
}

if ($act==add) {

//--------------------------------------------------------------------------- Start Add

echo "<link rel='stylesheet' href='text.css' type='text/css'>";
echo "<body bgcolor='$color1' style='margin: 0pt;'>";

$name = strip_tags($user_name,"");

if ($site == "http://") {
$name_link = "$user_name";
} elseif ($site == "") {
$name_link = "$user_name";
} else {
$name_link = "<a href=\"$site\" target=\"_blank\">$user_name</a>";

}

if ($user_name == "name") {
	print "<meta http-equiv=\"refresh\" content=\"0; URL=chat.php?message=Enter+Name&info2=$info&site2=$site\">";
} elseif ($name == "") {
	print "<meta http-equiv=\"refresh\" content=\"0; URL=chat.php?message=Enter+Name&info2=$info&site2=$site\">";
} elseif ($info == "") {
	print "<meta http-equiv=\"refresh\" content=\"0; URL=chat.php?message=Enter+Message&name2=$user_name&site2=$site\">";
} elseif ($info == "message") {
	print "<meta http-equiv=\"refresh\" content=\"0; URL=chat.php?message=Enter+Message&name2=$user_name&site2=$site\">";
} elseif (strlen($info)>$max_char) {
	print "<meta http-equiv=\"refresh\" content=\"0; URL=chat.php?message=Max+Characters+($max_char)&name2=$user_name&site2=$site\">";
} else {

$userroom_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom = $userroom_stat[userroom];

   if($userroom == db_arena1){
      $file = "data1.dat";
   }

   if($userroom == db_arena2){
      $file = "data2.dat";
   }

   if($userroom == db_arena3){
      $file = "data3.dat";
   }

   if($userroom == db_arena4){
      $file = "data4.dat";
   }

   if($userroom == db_arena5){
      $file = "data5.dat";
   }

   if($userroom == db_arena6){
      $file = "data6.dat";
   }

   if($userroom == db_arena7){
      $file = "data7.dat";
   }

   if($userroom == db_arena8){
      $file = "data8.dat";
   }

   if($userroom == db_arena9){
      $file = "data9.dat";
   }

   if($userroom == db_arena10){
      $file = "data10.dat";
   }

   if($userroom == lobby){
      $file = "gendat.dat";
   }
}

//----------------------- Start Bcode

	$info = strip_tags($info,"");
	$info = str_replace(":)","<img src='smilies/smile.gif'>",$info);
	$info = str_replace(":(","<img src='smilies/sad.gif'>",$info);
	$info = str_replace(":P","<img src='smilies/bigrazz.gif'>",$info);
	$info = str_replace(":D","<img src='smilies/biggrin.gif'>",$info);
	$info = str_replace("8)","<img src='smilies/cool.gif'>",$info);
	$info = str_replace(":@","<img src='smilies/mad.gif'>",$info);
	$info = str_replace(";)","<img src='smilies/wink.gif'>",$info);
	$info = str_replace("???","<img src='smilies/confused.gif'>",$info);
	$info = str_replace("[url]","[<a href=\"",$info);
	$info = str_replace("[/url]","\" target=\"_blank\">www</a>]",$info);
	$info = str_replace("[mail]","[<a href=\"mailto:",$info);
	$info = str_replace("[/mail]","\">@</a>]",$info);
	$info = str_replace("$word1","$censor1",$info);
	$info = str_replace("$word2","$censor2",$info);
	$info = str_replace("$word3","$censor3",$info);
	$info = str_replace("$word4","$censor4",$info);
	$info = stripslashes($info);
	$user_name = stripslashes($user_name);
	$name_link = stripslashes($name_link);

//----------------------- End Bcode

//----------------------- Start Add Content

$date = date("G:i", time());

$date_array = explode("-", $date);

$new = $date_array[0] + $time_a;

$daten = date(":: m/d @ $new:i ::", time());


$fp = fopen ($file, "r+") or die ("error when opening $file in $userroom");
flock($fp,2);
$old=fread($fp, filesize($file));
rewind($fp);
fwrite ($fp, ":: <b>$name_link</b> : $info<br>$daten<br>\n".$old);
flock($fp,3);
fclose ($fp);

//----------------------- End Add Content

//}

//--------------------------------------------------------------------------- End Add

} elseif ($act==all) {

//--------------------------------------------------------------------------- Start View All

print "<html><head>

<style type=\"text/css\">
<!--
a:active {  color: $link3; text-decoration: none}
a:visited {  color: $link1; text-decoration: none}
a:hover {  color: $link2; text-decoration: none}
a:link {  color: $link3; text-decoration: none}
-->
</style>

<style>body{scrollbar-face-color: $color2; scrollbar-shadow-color: $color3; 
scrollbar-highlight-color: $color2; scrollbar-3dlight-color: $color3; 
scrollbar-darkshadow-color: $color2; scrollbar-track-color: $color2; 
scrollbar-arrow-color: $color3;}</style>

<title>:: shoutBOX</title>

<link rel='stylesheet' href='text.css' type='text/css'></head>

<body bgcolor=\"$color1\" style=\"margin: 0pt;\">";

$userroom_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom = $userroom_stat[userroom];

   if($userroom == db_arena1){
      $file = "data1.dat";
   }

   if($userroom == db_arena2){
      $file = "data2.dat";
   }

   if($userroom == db_arena3){
      $file = "data3.dat";
   }

   if($userroom == db_arena4){
      $file = "data4.dat";
   }

   if($userroom == db_arena5){
      $file = "data5.dat";
   }

   if($userroom == db_arena6){
      $file = "data6.dat";
   }

   if($userroom == db_arena7){
      $file = "data7.dat";
   }

   if($userroom == db_arena8){
      $file = "data8.dat";
   }

   if($userroom == db_arena9){
      $file = "data9.dat";
   }

   if($userroom == db_arena10){
      $file = "data10.dat";
   }

   if($userroom == lobby){
      $file = "gendat.dat";
   }

$fp = fopen ($file, "r") or die ("error when reading $file in $userroom");
while ( !feof ($fp) ) {
$line = fgets ($fp, 9216);
print "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
        <tr> 
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$line</font></td>
        </tr></table>";

}

print "<br><div align=\"center\"><font face=\"Verdana\" color=\"$text\" size=\"1\">[ <a href=\"javascript:self.close()\">close</a> ]</div></font></body></html>";

//--------------------------------------------------------------------------- End View All

} elseif ($act == "help") {

//--------------------------------------------------------------------------- Start Help

print "<html>
<head>
<title>:: shoutBOX : Info</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">

<style>body{scrollbar-face-color: $color2; scrollbar-shadow-color: $color3; 
scrollbar-highlight-color: $color2; scrollbar-3dlight-color: $color3; 
scrollbar-darkshadow-color: $color2; scrollbar-track-color: $color2; 
scrollbar-arrow-color: $color3;}</style>

<style type=\"text/css\">
<!--
a:active {  color: $link3; text-decoration: none}
a:visited {  color: $link1; text-decoration: none}
a:hover {  color: $link2; text-decoration: none}
a:link {  color: $link3; text-decoration: none}
-->
</style>

</head>

<body bgcolor=\"$color1\" text=\"$text\" style=\"margin: 0pt;\">
<table width=\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"200\">
  <tr>
    <td valign=\"top\">
      <p><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:: I want 
        a shoutBOX!<br>
        </font></b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">Point 
        you browser to endity.com where you will be able to download the latest 
        version of shoutBOX</font></p>
      <p><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:: Bcode<br>
        </font></b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">shoutBOX 
        has the ability to convert smilies. It also has certain codes for links 
        etc. Heres a list of the codes that can be used:</font></p>
      <table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" align=\"center\">
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">Code</font></b></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">Appears</font></b></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:)</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/smile.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:(</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/sad.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">;)</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/wink.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:P</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/bigrazz.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:D</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/biggrin.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">:@</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/mad.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">???</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/confused.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">8)</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><img src=\"smilies/cool.gif\"></font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">[url]http://end<br>
              ity.com[/url]</font></div>
          </td>
          <td width=\"50%\"> 
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">[<a href=\"http://endity.com\">www</a>]</font></div>
          </td>
        </tr>
        <tr bgcolor=\"$table2\"> 
          <td width=\"50%\">
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">[mail]name@msn<br>
              .com[/mail]</font></div>
          </td>
          <td width=\"50%\">
            <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">[<a href=\"mailto:name@msn.com\">@</a>]</font></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table></body>
</html>";

//--------------------------------------------------------------------------- End Help

} else {

//--------------------------------------------------------------------------- Start Board View

$userroom_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom = $userroom_stat[userroom];

   if($userroom == db_arena1){
      $file="data1.dat";
   }

   if($userroom == db_arena2){
      $file = "data2.dat";
   }

   if($userroom == db_arena3){
      $file = "data3.dat";
   }

   if($userroom == db_arena4){
      $file = "data4.dat";
   }

   if($userroom == db_arena5){
      $file = "data5.dat";
   }

   if($userroom == db_arena6){
      $file = "data6.dat";
   }

   if($userroom == db_arena7){
      $file = "data7.dat";
   }

   if($userroom == db_arena8){
      $file = "data8.dat";
   }

   if($userroom == db_arena9){
      $file = "data9.dat";
   }

   if($userroom == db_arena10){
      $file = "data10.dat";
   }

   if($userroom == lobby){
      $file = "gendat.dat";
   }

$fp = fopen ($file, "r+") or die ("error when reading $file in $userroom");
$mess = file($file);

if ($name2 == "$user_name" ) { $name2 = "name"; } 

if ($info2 == "$info" ) { $info2 = "message"; } 

if ($site2 == "$site" ) { $site2 = "http://"; } 

print "<head>

<style type=\"text/css\">
<!--
a:active {  color: $link3; text-decoration: none}
a:visited {  color: $link1; text-decoration: none}
a:hover {  color: $link2; text-decoration: none}
a:link {  color: $link3; text-decoration: none}
-->
</style>

<style>body{scrollbar-face-color: $color2; scrollbar-shadow-color: $color3; 
scrollbar-highlight-color: $color2; scrollbar-3dlight-color: $color3; 
scrollbar-darkshadow-color: $color2; scrollbar-track-color: $color2; 
scrollbar-arrow-color: $color3;}</style>

<html>
<script type=\"text/javascript\">

function openScript(url, width, height) {
        var Win = window.open(url,\"openScript\",'width=' + width + ',height=' + height + ',resizable=no,scrollbars=yes,menubar=no,status=no' );
}

</script>

<link rel='stylesheet' href='text.css' type='text/css'>
</head>
<body bgcolor=\"$color1\" style=\"margin: 0pt;\" scroll=\"$scroll\">
<font face=\"Verdana\" color=\"#000000\" size=\"1\">";
print "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td bgcolor=\"$table_bdr\"> 
      <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
        <tr> 
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[0]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table2\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[1]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[2]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table2\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[3]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[4]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table2\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[5]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[6]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table2\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[7]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[8]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table2\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[9]<BR>In room: $userroom</font></td>
        </tr>
        <tr>
          <td bgcolor=\"$table1\"><font face=\"Verdana\" color=\"$text\" size=\"1\">$mess[10]<BR>In room: $userroom</font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>";
print "</font>";
print "<div align=\"center\"><form name=\"input\" method=\"post\" action=\"chat.php?act=add\">
  <font face=\"Verdana, Arial, Helvetica, sans-serif\"> <font size=\"1\" color=\"$text\"> 
  <input type=\"hidden\" name=\"userroom\" value=\"$userroom\">
  <input type=\"hidden\" name=\"$user_name\" value=\"$name2\" onfocus=\"this.value=''\" class=\"text\" style=\"border:1px solid $color4; border-style: solid; background-color:$color5;\" size=\"17\"><br>
<input type=\"text\" name=\"site\" value=\"$site2\" class=\"text\" style=\"border:1px solid $color4; border-style: solid; background-color:$color5;\" size=\"17\">
  <br>
  <input type=\"text\" name=\"info\" value=\"$info2\" onfocus=\"this.value=''\" class=\"text\" style=\"border:1px solid $color4; border-style: solid; background-color:$color5;\" size=\"17\"><br>
  <input type=\"submit\" name=\"Submit\" value=\"shout\" class=\"text\" style=\"border:1px solid $color4; border-style: solid; background-color:$color6;\"> [ <a href=\"javascript:openScript('?act=all','150','400')\">all</a> : <a href=\"javascript:openScript('?act=help','216','400')\">info</a> ] 
<br><font color=\"#FF0000\"><br><b> $message </b></font>
  </font> </font> 
</form></html></div>";

//--------------------------------------------------------------------------- End Board View

}

?>