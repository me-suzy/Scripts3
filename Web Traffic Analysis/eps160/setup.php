<?
session_start();
include("config.php");
if($_SESSION['runindex'] != "yes" || $_GET['check'] == "yes" || $_GET['check'] == "admin")
{
	?>
	<html>

	<head>
	<title>Access Error</title>
	</head>
	<body>
	<div class="headline">Please run Setup from the PHPstat main page</div>
	</body>
	</html>
	<?
	exit("");
}
include("$path_data/setup.php");
$cleararray['check'] = $_POST['check'];
$cleararray['pass'] = $_POST['pass'];
$cleararray['user'] = $_POST['user'];
$cleararray['formsecure'] = $_POST['formsecure'];
if ($cleararray['check'] == "admin" && $cleararray['pass'] == $password && $cleararray['user'] == $username && $cleararray['formsecure'] == $_SESSION['formcheck']) {
showsetup();
$_SESSION['secure'] = "yes";
} elseif (($cleararray['check'] == "admin") && ($cleararray['pass'] != $password || $cleararray['user'] != $username) && $_SESSION['secure'] != "yes") {
adminerror();
} elseif ($cleararray['check'] == "yes" && $_SESSION['secure'] == "yes") {
write($_POST);
} else {
$_SESSION['formcheck'] = rand(1, 100000);
admin();
}

function write($input1) {
include("config.php");

$disalowedtags = array("script",
                       "object",
                       "iframe",
                       "image",
                       "applet",
                       "meta",
                       "form",
                       "noscript",
                       "html",
                       "body",
                       "a",
                       "img",
                       "onmouseout");

foreach ($input1 as $varname)
foreach ($disalowedtags as $tag)
if (eregi("<[^>]*".$tag."*\"?[^>]*>", $varname))
exit("Error: Wrong input in setup - Only use characters a-z 0-9 as input");
if (strlen($input1['hcolor']) > 6 || strlen($input1['lcolor']) > 6 || strlen($input1['color']) > 6 || strlen($input1['bgcolor']) > 6)
exit("Error: Wrong input in setup - Color values should be in hex (e.g. 3f3f3f)");

 $show = strtolower($input1['show']);
 $refshow = strtolower($input1['refshow']);
 $keyshow = strtolower($input1['keyshow']);
 $ldec = strtolower($input1['ldec']);
 $hcolor = strtolower($input1['hcolor']);
 $lcolor = strtolower($input1['lcolor']);
 $font_family = strtolower($input1['font_family']);
 $font_size = strtolower($input1['font_size']);
 $color = strtolower($input1['color']);
 $font_style = strtolower($input1['font_style']);
 $font_weight = strtolower($input1['font_weight']);
 $letter_spacing = strtolower($input1['letter_spacing']);
 $admin = strtolower($input1['admin']);
 $slist = strtolower($input1['sort']);
 $username = strtolower($input1['username']);
 $password = strtolower($input1['password']);
 $bgcolor = strtolower($input1['bgcolor']);
 $chbrowser = strtolower($input1['chbrowser']);
 $chjs = strtolower($input1['chjs']);
 $chcolor = strtolower($input1['chcolor']);
 $chscreen = strtolower($input1['chscreen']);
 $chos = strtolower($input1['chos']);
 $chcountry = strtolower($input1['chcountry']);
 $chkeywords = strtolower($input1['chkeywords']);
 $chref = strtolower($input1['chref']);
 $chip = strtolower($input1['chip']);
 $ipshow = strtolower($input1['ipshow']);
 $fp = fopen("$path_data/setup.php", "wb") or die ("The File \"$path_data/setup.php\" does not exist");
 flock( $fp, 2);
 fputs ($fp, "<?php\n\$show = \"$show\";\n\$refshow = \"$refshow\";\n\$keyshow = \"$keyshow\";\n\$ldec = \"$ldec\";\n\$lcolor = \"$lcolor\";\n\$hcolor = \"$hcolor\";\n\$font_family = \"$font_family\";\n\$font_size = \"$font_size\";\n\$color = \"$color\";\n\$font_style = \"$font_style\";\n\$font_weight = \"$font_weight\";\n\$letter_spacing = \"$letter_spacing\";\n\$sortlist = \"$slist\";\n\$admin = \"$admin\";\n\$username = \"$username\";\n\$password = \"$password\";\n\$bgcolor = \"$bgcolor\";\n\$chbrowser = \"$chbrowser\";\n\$chjs = \"$chjs\";\n\$chcolor = \"$chcolor\";\n\$chscreen = \"$chscreen\";\n\$chos = \"$chos\";\n\$chcountry = \"$chcountry\";\n\$chkeywords = \"$chkeywords\";\n\$chref = \"$chref\";\n\$chip = \"$chip\";\n\$ipshow = \"$ipshow\";\n?>");
 flock( $fp, 1);
 fclose ($fp);
 include("config.php");
 include("$path_data/setup.php");
 ?>
 <html>
 <head>
 <style type="text/css">
 .headline {
  color: <? echo $hcolor; ?>;
  font-family: <? echo $font_family; ?>;
  font-size: <? echo $font_size; ?>px;
  font-style: <? echo $font_style; ?>;
  font-weight: bold;
  letter-spacing: <? echo $letter_spacing; ?>px;
 }
 </style>
  <title>easyPHPstat Setup</title>
 </head>
 <body>
  <img src="<? echo $path_img; ?>/setup.gif" alt="" border="0"><p>
  <div class="headline">Setup has been updated</div>
  <p>
  <META HTTP-EQUIV="REFRESH" CONTENT="1; URL=index.php">
 </body>
 </html>
 <?
}

function adminerror() {
include("config.php");
include("$path_data/setup.php");
 ?>
 <html>
 <head>
 <style type="text/css">
 <!--
 .regtext {
 font-family : <? echo $font_family; ?>;
 font-size : <? echo $font_size; ?>px;
 font-style : <? echo $font_style; ?>;
 font-weight : <? echo $font_weight; ?>;
 letter-spacing : <? echo $letter_spacing; ?>px;
 color : <? echo $color; ?>;
 }
 .headline {
 font-family : <? echo $font_family; ?>;
 font-size : <? echo $font_size; ?>px;
 font-style : <? echo $font_style; ?>;
 font-weight : bold;
 letter-spacing : <? echo $letter_spacing; ?>px;
 color : <? echo $color; ?>;
 }
 body {
  	background: #<?echo $bgcolor;?>;
 }

 -->
 </style>
 <title>easyPHPstat Error - 401</title>
 </head>
 <body>
 <img src="<? echo $path_img; ?>/error.gif" alt="" border="0"><br><br><br><br>
 <div class="headline">Wrong password and/or username!</div><p>
 <META HTTP-EQUIV="REFRESH" CONTENT="1; URL=setup.php">
 </body>
 </html>
 <?
}



function showsetup() {
include("config.php");
include("$path_data/setup.php");
?>
 <html>
 <head>
 <style type="text/css">
 .regtext {
 	font-family : <? echo $font_family; ?>;
 	font-size : <? echo $font_size; ?>px;
 	font-style : <? echo $font_style; ?>;
 	font-weight : <? echo $font_weight; ?>;
 	letter-spacing : <? echo $letter_spacing; ?>px;
 	color : #<? echo $color; ?>;
 }
 .box {
    color: <? echo $color; ?>;
    font-family: <? echo $font_family; ?>;
    font-size: <? echo $font_size; ?>px;
    font-style: <? echo $font_style; ?>;
 	font-weight: <? echo $font_weight; ?>;
 	letter-spacing: <? echo $letter_spacing; ?>px;
 }
 .headline {
    color: <? echo $hcolor; ?>;
    font-family: <? echo $font_family; ?>;
    font-size: <? echo $font_size; ?>px;
    font-style: <? echo $font_style; ?>;
 	font-weight: bold;
 	letter-spacing: <? echo $letter_spacing; ?>px;
 }
 .link {
 	font-family : <? echo $font_family; ?>;
 	font-size : <? echo $font_size; ?>px;
 	font-style : <? echo $font_style; ?>;
 	letter-spacing : <? echo $letter_spacing; ?>px;
 	color : #<? echo $lcolor; ?>;
 	text-decoration : <? echo $ldec; ?>;
 }
  body {
   	background: #<?echo $bgcolor;?>;
 }
 </style>
 <title>easyPHPstat Setup</title>
 </head>
 <body>
  <img src="<? echo $path_img; ?>/setup.gif" alt="" border="0"><p>
  <table border="0" cellspacing="2" cellpadding="2">
  <tr><td><br><div class="headline">Go to:</div></td></tr>
  <tr><td><a class="link" href="#show">Layout Settings</a></td></tr>
  <tr><td><a class="link" href="#css">CSS</a></td></tr>
  <tr><td><a class="link" href="#password">Administrator Settings</a></td></tr>
  <tr><td><br></td></tr>
  </table>
  <form method="post" action="setup.php">
  <input type="hidden" name="check" value="yes">
  <a name=show></a>
  <table>
  <tr><td>
  <div class="headline">Layout Settings:</div>
  </td></tr>
  <tr><td>
  <div class="regtext">- Show statistics:</div>
  </td></tr>
  <tr><td>
  <input class="box" type=checkbox name='chbrowser' value='1'<?if ($chbrowser==1) {echo " CHECKED";}?>><span class="regtext">Browser</span><br>
  <input class="box" type=checkbox name='chjs' value='1'<?if ($chjs==1) {echo " CHECKED";}?>><span class="regtext">Javascript</span><br>
  <input class="box" type=checkbox name='chcolor' value='1'<?if ($chcolor==1) {echo " CHECKED";}?>><span class="regtext">Color depth</span><br>
  <input class="box" type=checkbox name='chscreen' value='1'<?if ($chscreen==1) {echo " CHECKED";}?>><span class="regtext">Screen area</span><br>
  <input class="box" type=checkbox name='chos' value='1'<?if ($chos==1) {echo " CHECKED";}?>><span class="regtext">Operating system</span><br>
  <input class="box" type=checkbox name='chcountry' value='1'<?if ($chcountry==1) {echo " CHECKED";}?>><span class="regtext">Country</span><br>
  <input class="box" type=checkbox name='chkeywords' value='1'<?if ($chkeywords==1) {echo " CHECKED";}?>><span class="regtext">Keywords</span><br>
  <input class="box" type=checkbox name='chref' value='1'<?if ($chref==1) {echo " CHECKED";}?>><span class="regtext">Reference</span><br>
  <input class="box" type=checkbox name='chip' value='1'<?if ($chip==1) {echo " CHECKED";}?>><span class="regtext">IP Address</span><br>
  </td></tr>
  <tr><td>
  <div class="regtext">- Show stats as hits or percent:</div>
  </td></tr>
  <tr><td>
  <select name="show" size="1" class="box">
  <option<?if ($show=="hits") {echo " SELECTED";}?>>Hits
  <option<?if ($show=="percent") {echo " SELECTED";}?>>Percent
  </select>
  </td></tr>
  <tr><td>
  <div class="regtext">- Sort stats:</div>
  </td></tr>
  <tr><td>
  <select name="sort" size="1" class="box">
  <option<?if ($sortlist=="ýes") {echo " SELECTED";}?>>Yes
  <option<?if ($sortlist=="no") {echo " SELECTED";}?>>No
  </select>
  </td></tr>
  <tr><td>
  <div class="regtext">- Number of reference to show:</div>
  </td></tr>
  <tr><td>
  <select name="refshow" size="1" class="box">
  <option<?if ($refshow=="5") {echo " SELECTED";}?>>5
  <option<?if ($refshow=="10") {echo " SELECTED";}?>>10
  <option<?if ($refshow=="15") {echo " SELECTED";}?>>15
  <option<?if ($refshow=="20") {echo " SELECTED";}?>>20
  <option<?if ($refshow=="30") {echo " SELECTED";}?>>30
  <option<?if ($refshow=="40") {echo " SELECTED";}?>>40
  <option<?if ($refshow=="50") {echo " SELECTED";}?>>50
  <option<?if ($refshow=="100") {echo " SELECTED";}?>>100
  </select>
  </td></tr>
  <tr><td>
  <div class="regtext">- Number of keywords to show:</div>
  </td></tr>
  <tr><td>
  <select name="keyshow" size="1" class="box">
  <option<?if ($keyshow=="5") {echo " SELECTED";}?>>5
  <option<?if ($keyshow=="10") {echo " SELECTED";}?>>10
  <option<?if ($keyshow=="15") {echo " SELECTED";}?>>15
  <option<?if ($keyshow=="20") {echo " SELECTED";}?>>20
  <option<?if ($keyshow=="30") {echo " SELECTED";}?>>30
  <option<?if ($keyshow=="40") {echo " SELECTED";}?>>40
  <option<?if ($keyshow=="50") {echo " SELECTED";}?>>50
  <option<?if ($keyshow=="100") {echo " SELECTED";}?>>100
  </select>
  </td></tr>
  <tr><td>
  <div class="regtext">- Number of IP adresses to show:</div>
  </td></tr>
  <tr><td>
  <select name="ipshow" size="1" class="box">
  <option<?if ($ipshow=="5") {echo " SELECTED";}?>>5
  <option<?if ($ipshow=="10") {echo " SELECTED";}?>>10
  <option<?if ($ipshow=="15") {echo " SELECTED";}?>>15
  <option<?if ($ipshow=="20") {echo " SELECTED";}?>>20
  <option<?if ($ipshow=="30") {echo " SELECTED";}?>>30
  <option<?if ($ipshow=="40") {echo " SELECTED";}?>>40
  <option<?if ($ipshow=="50") {echo " SELECTED";}?>>50
  <option<?if ($ipshow=="100") {echo " SELECTED";}?>>100
  </select>
  </td></tr>
  </table>
  <a name=css></a>
  <table>
  <tr><td>
  <br><div class="headline">CSS:</div>
  </td></tr>
  <tr><td>
  <div class="regtext">- Background color (in hex - e.g. 3f3f3f):</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="bgcolor" value="<? echo $bgcolor; ?>" size="20">
  </td></tr><tr><td>
  <div class="regtext">- Link color (in hex - e.g. 3f3f3f):</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="lcolor" value="<? echo $lcolor; ?>" size="20">
  </td></tr><tr><td>
  <div class="regtext">- Headline color (in hex - e.g. 3f3f3f):</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="hcolor" value="<? echo $hcolor; ?>" size="20">
  </td></tr><tr><td>
  <div class="regtext">- Font Color (in hex - e.g. 3f3f3f):</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="color" value="<? echo $color; ?>" size="20">
  </td></tr><tr><td>
  <div class="regtext">- Font family:</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="font_family">
  <option<?if ($font_family=="verdana, geneva, arial, helvetica, sans-serif") {echo " SELECTED";}?>>Verdana, Geneva, Arial, Helvetica, Sans-serif
  <option<?if ($font_family=="arial, helvetica, sans-serif") {echo " SELECTED";}?>>Arial, Helvetica, Sans-serif
  <option<?if ($font_family=="times new roman, times, serif") {echo " SELECTED";}?>>Times New Roman, Times, Serif
  <option<?if ($font_family=="ms sans serif, geneva, sans-serif") {echo " SELECTED";}?>>MS Sans Serif, Geneva, Sans-serif
  <option<?if ($font_family=="courier new, courier, monospace") {echo " SELECTED";}?>>Courier New, Courier, Monospace
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Font size (in pixels):</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="font_size">
  <option<?if ($font_size=="5") {echo " SELECTED";}?>>5
  <option<?if ($font_size=="6") {echo " SELECTED";}?>>6
  <option<?if ($font_size=="7") {echo " SELECTED";}?>>7
  <option<?if ($font_size=="8") {echo " SELECTED";}?>>8
  <option<?if ($font_size=="9") {echo " SELECTED";}?>>9
  <option<?if ($font_size=="10") {echo " SELECTED";}?>>10
  <option<?if ($font_size=="11") {echo " SELECTED";}?>>11
  <option<?if ($font_size=="12") {echo " SELECTED";}?>>12
  <option<?if ($font_size=="13") {echo " SELECTED";}?>>13
  <option<?if ($font_size=="14") {echo " SELECTED";}?>>14
  <option<?if ($font_size=="15") {echo " SELECTED";}?>>15
  <option<?if ($font_size=="16") {echo " SELECTED";}?>>16
  <option<?if ($font_size=="17") {echo " SELECTED";}?>>17
  <option<?if ($font_size=="18") {echo " SELECTED";}?>>18
  <option<?if ($font_size=="19") {echo " SELECTED";}?>>19
  <option<?if ($font_size=="20") {echo " SELECTED";}?>>20
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Font style:</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="font_style">
  <option<?if ($font_style=="normal") {echo " SELECTED";}?>>Normal
  <option<?if ($font_style=="italic") {echo " SELECTED";}?>>Italic
  <option<?if ($font_style=="oblique") {echo " SELECTED";}?>>Oblique
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Font weight:</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="font_weight">
  <option<?if ($font_weight=="normal") {echo " SELECTED";}?>>Normal
  <option<?if ($font_weight=="bolder") {echo " SELECTED";}?>>Bolder
  <option<?if ($font_weight=="bold") {echo " SELECTED";}?>>Bold
  <option<?if ($font_weight=="lighter") {echo " SELECTED";}?>>Lighter
  <option<?if ($font_weight=="200") {echo " SELECTED";}?>>200
  <option<?if ($font_weight=="300") {echo " SELECTED";}?>>300
  <option<?if ($font_weight=="400") {echo " SELECTED";}?>>400
  <option<?if ($font_weight=="500") {echo " SELECTED";}?>>500
  <option<?if ($font_weight=="600") {echo " SELECTED";}?>>600
  <option<?if ($font_weight=="700") {echo " SELECTED";}?>>700
  <option<?if ($font_weight=="800") {echo " SELECTED";}?>>800
  <option<?if ($font_weight=="900") {echo " SELECTED";}?>>900
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Letter spacing (in pixels):</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="letter_spacing">
  <option<?if ($letter_spacing=="0") {echo " SELECTED";}?>>0
  <option<?if ($letter_spacing=="1") {echo " SELECTED";}?>>1
  <option<?if ($letter_spacing=="2") {echo " SELECTED";}?>>2
  <option<?if ($letter_spacing=="3") {echo " SELECTED";}?>>3
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Link decoration:</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="ldec">
  <option<?if ($ldec=="none") {echo " SELECTED";}?>>None
  <option<?if ($ldec=="underline") {echo " SELECTED";}?>>Underline
  <option<?if ($ldec=="overline") {echo " SELECTED";}?>>Overline
  <option<?if ($ldec=="line-through") {echo " SELECTED";}?>>Line-through
  <option<?if ($ldec=="blink") {echo " SELECTED";}?>>Blink
  </select>
  </td></tr>
  </table>
  <a name=password></a>
  <table>
  <tr><td>
  <br><div class="headline">Administrator Settings</div>
  </td></tr>
  <tr><td>
  <div class="regtext">- Password protection:</div>
  </td></tr><tr><td>
  <select class="box" size="1" name="admin">
  <option<?if ($admin=="yes") {echo " SELECTED";}?>>Yes
  <option<?if ($admin=="no") {echo " SELECTED";}?>>No
  </select>
  </td></tr><tr><td>
  <div class="regtext">- Username:</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="username" value="<? echo $username; ?>" size="20">
  </td></tr><tr><td>
  <div class="regtext">- Password:</div>
  </td></tr><tr><td>
  <input class="box" type="text" name="password" value="<? echo $password; ?>" size="20">
  </td></tr><tr><td>
  <br>
  <input class="regtext" type="submit" name="Send" value="Send" style="border: 1px solid #3f3f3f;">
  </td></tr></table>
  </form>
  </body>
  </html>
 <?
}

function admin() {
include("config.php");
 include("$path_data/setup.php");
?>
 <html>
 <head>
 <style type="text/css">
 <!--
 .regtext {
  font-family : <? echo $font_family; ?>;
  font-size : <? echo $font_size; ?>px;
  font-style : <? echo $font_style; ?>;
  font-weight : <? echo $font_weight; ?>;
  letter-spacing : <? echo $letter_spacing; ?>px;
  color : <? echo $color; ?>;
  }
 .headline {
  font-family : <? echo $font_family; ?>;
  font-size : <? echo $font_size; ?>px;
  font-style : <? echo $font_style; ?>;
  font-weight : bold;
  letter-spacing : <? echo $letter_spacing; ?>px;
  color : <? echo $color; ?>;
  }
  body {
   	background: #<?echo $bgcolor;?>;
  }
 -->
 </style>
 </head>
 <body>
 <table cellspacing="2" cellpadding="2" border="0">
 <tr>
 <td>
 <img src="<? echo $path_img; ?>/admin.gif" alt="" border="0"><p>
 <form method="post" action="setup.php">
 <input type="hidden" name="check" value="admin">
 <input type="hidden" name="formsecure" value="<? echo $_SESSION['formcheck']; ?>">
 <div class="regtext">Username:</div>
 <input type="text" name="user" size="25" style="border: 1px solid #3f3f3f; font-family: <? echo $font_family; ?>; font-size: <? echo $font_size; ?>px; color: <? echo $color; ?>;"><p>
 <div class="regtext">Password:</div>
 <input type="password" name="pass" size="25" style="border: 1px solid #3f3f3f; font-family: <? echo $font_family; ?>; font-size: <? echo $font_size; ?>px; color: <? echo $color; ?>;"><p>
 <input type="submit" name="Send" value="Send" style="border: 1px solid #3f3f3f;">
 </form>
 </td>
 </tr>
 </table>
 </body>
 </html>
 <?
}


?>
