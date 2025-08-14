<?php

require "settings.php";
require "lib/mysql.lib";
$r = q("select status, rdate from members where id='$auth' and pswd='$pass'");
if(e($r)) header("Location: login.php");
q("update profiles set ldate='".strtotime(date("d M Y H:i:s"))."' where id='$auth'");

$tm0=f(q("select * from members where id='$auth'"));
$tp0=f(q("select * from profiles where id='$auth'"));
if (!$tm0[type]) $tm0[type]=0;

$tm0pics=$mem_free_pics;
$tm0message=$mem_free_message;
$tm0chat=$mem_free_chat;
$tm0web=0;
$tm0emails=0;
$tm0phone=0;

if ($tm0[type]>=1000) 
{
$tm0pics=$mem_silver_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=$mem_silver_web;
$tm0emails=0;
$tm0phone=0;
};

if ($tm0[type]>=2000) 
{
$tm0pics=$mem_gold_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=1;
$tm0emails=$mem_gold_emails;cine 
$tm0phone=0;
};

if ($tm0[type]>=3000) 
{
$tm0pics=$mem_platinum_pics;
$tm0message=1;
$tm0chat=1;
$tm0web=1;
$tm0emails=1;
$tm0phone=$mem_platinum_phone;
};

if (!$tm0message) exit;
?>
<html>

<title>NeoDate Chat</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<body bgcolor="#FFFFFF" text="#333333" link="#333333" vlink="#333333" alink="#333333" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0" WIDTH=550 HEIGHT=400 hspace="0" vspace="0" align="absmiddle"
 ID="chat">
 <PARAM NAME=movie VALUE="chat.swf"><PARAM NAME=menu VALUE=false><PARAM NAME=quality VALUE=high><PARAM NAME=wmode VALUE=transparent><EMBED src="chat.swf" WIDTH=550 HEIGHT=400 hspace="0" vspace="0" align="absmiddle" name="chat" menu=false quality=high wmode=transparent TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" swLiveConnect="true"></EMBED>
</OBJECT>
</body></html>