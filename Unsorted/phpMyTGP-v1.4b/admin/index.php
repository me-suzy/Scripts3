<?
include("./include.inc");
include("./funcs.inc");
include("./Snoopy.class.inc");
include("./libmail.inc");
if ((!$action))
{
?>
  <html>
  <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver;?> Admin Login</title>
  <style type="text/css">
  <!--
  BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
  -->
  </style>
  </head>
  <body bgcolor=white text=black link=black>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <font size=2>phpMyTGP <?echo $phpMyTGP_ver;?> Admin Login</font>
  </td></tr></table>
  <form method="POST">
  <p align=center>
  password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=pw maxlength=10>
  </p>
  <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
  <input type=submit name=action value=login style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>
  </form>
  </body>
  </html>
<?
exit;
}
$link = mysql_connect ($sql_host, $sql_user , $sql_pass);
mysql_select_db($sql_db);
$query = "select * from mytgp_set where UIN='$sql_uin'";
$result = mysql_query($query);
$msetstr = mysql_fetch_array($result);
if ($pw != $msetstr["ADMINPASS"]) 
	{
	echo "<p align=center>invalid password</p>";
        echo "<meta http-equiv=REFRESH content='1;url=index.php'>";
	mysql_close($link);
	exit;
	}
$temp_langfile = $msetstr["LANGUAGE"];
list($temp, $global_extension) = split("[.]", $msetstr["TGPMAINFILE"]);
include_once("../language/".$temp_langfile);

// START MAIN SETUP SECTION
if (($action=="login") or ($action=="refresh") or ($action=="cancel") or ($action=="back") or ($action=="rebuild")) 
  {
// SOME STATISTICS
  $query = "select * from mytgp_post where VALIDATED='NO' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_waiting_posts = mysql_num_rows($result);
  $query = "select * from mytgp_post where UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_posts = mysql_num_rows($result);
  $query = "select * from mytgp_post where VALIDATED='YES' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_approved_posts = mysql_num_rows($result);
  $query = "select * from mytgp_cats where UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_categories = mysql_num_rows($result);
  $query = "select * from mytgp_mail where UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_mails = mysql_num_rows($result);
  $lastupdate = unix_to_date($msetstr["LASTUPDATE"],2);
?>
<?show_admin_header();?>
  <tr><td align=center width=80%>
  <font size=+1><?echo "<a href=".$msetstr["TGPMAINURL"]." target=_blank>".$msetstr["TGPNAME"]."</a> ".$lang_admin_8.":";?></font><br>
  <?
  echo $lang_admin_9." <b>".$total_posts."</b> ".$lang_admin_10." <b>".$total_categories."</b> ".$lang_admin_6.". ".$lang_admin_11." <b>".$total_approved_posts."</b> ".$lang_admin_10.". ".$lang_admin_12." <b>".$total_waiting_posts."</b> ".$lang_admin_10.".<br>";
  echo "<b>".$total_mails."</b> ".$lang_admin_13.". ".$lang_admin_14.": <b>".$lastupdate."</b><br>";
  ?>
  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_4;?></h3>
<?
if ($action=="rebuild") {
ignore_user_abort(true);
echo "<p align=center>";

update_posts();
update_templates();
update_lastupdate();
echo $lang_admin_92;
  
echo "</p>";
ignore_user_abort(false);
}
?>
  <input type=submit name=action value=settings style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value=categories style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value=blacklists style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value=templates style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <br><br>
  <input type=submit name=action value="mail menu" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="post menu" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <? if ($msetstr["USEVIP"]=="YES")
  echo "<input type=submit name=action value=\"VIP menu\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
  ?>
  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=refresh style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value=rebuild style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?
mysql_close ($link);
exit;
  }
// END MAIN SETUP SECTION ?> 

<? // START SETUP CATEGORIES SECTION
if (($action=="categories") or ($action=="delete category") or ($action=="add category"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_6." ".$lang_admin_4?></h3>
<?
if ($action=="delete category")
{
ignore_user_abort(true);
if ($category!="") {
$query = "select NAME from mytgp_cats where NAME='$category' and UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
if (mysql_num_rows($result)!=0) {
$query1 = "delete from mytgp_cats where NAME='$category' and UIN='$sql_uin'";	
$result1 = mysql_query ($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>".$lang_admin_6." <b>".$new_cat."</b> ".$lang_admin_45."</p>";
}
else {
echo "<p align=center>".$lang_admin_6." <b>".$new_cat."</b> ".$lang_admin_46."</p>";
}
}
else {
echo "<p align=center>".$lang_admin_47."</p>".$category;
}
ignore_user_abort(false);
}

if ($action=="add category")
{
ignore_user_abort(true);
if ($new_cat!="") {
$query = "select NAME from mytgp_cats where NAME='$new_cat' and UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
if (mysql_num_rows($result)==0) {
$query1 = "insert into mytgp_cats values ('','$new_cat','$sql_uin')";	
$result1 = mysql_query ($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>".$lang_admin_6." <b>".$new_cat."</b> ".$lang_admin_49."</p>";
}
else {
echo "<p align=center>".$lang_admin_6." <b>".$new_cat."</b> ".$lang_admin_50."</p>";
}
}
else {
echo "<p align=center>".$lang_admin_51."</p>";
}
ignore_user_abort(false);
}
?>  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <?
  ignore_user_abort(true);
  $query = "select * from mytgp_cats where UIN='$sql_uin' order by NAME";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
    echo "<input type=radio name=category value=".$row["NAME"].">".$row["NAME"]."<BR>";
  }
  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  <input type=submit name=action value="delete category" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br>
  </td><td align=left valign=top>
  <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=new_cat size=20 maxlength=100><BR>
  <input type=submit name=action value="add category" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END SETUP CATEGORIES SECTION ?> 

<? // START SETUP BLACKLIST SECTION
if (($action=="blacklists") or ($action=="back black") or ($action=="delete from base"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_7." ".$lang_admin_4?></h3>
  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <?
  if ($action=="delete from base") // Delete from base
  {
    $new_black = strtolower($new_black);
    switch ($blackfield) {
     case "E-MAIL":
    	 $query = "delete from mytgp_post where LOCATE('$new_black',POSTEMAIL)>0 and UIN='$sql_uin'";
	 $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
         break;
     case "URL":
    	 $query = "delete from mytgp_post where LOCATE('$new_black',POSTURL)>0 and UIN='$sql_uin'";
	 $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
         break;
     case "DESCRIPTION":
    	 $query = "delete from mytgp_post where LOCATE('$new_black',POSTDESCR)>0 and UIN='$sql_uin'";
	 $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
         break;
     case "IP":
    	 $query = "delete from mytgp_post where LOCATE('$new_black',POSTIP)>0 and UIN='$sql_uin'";
	 $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
         break;
     case "REFERER":
    	 $query = "delete from mytgp_post where LOCATE('$new_black',POSTREF)>0 and UIN='$sql_uin'";
	 $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
         break;
    }
  }
  ignore_user_abort(true);
  $query = "select * from mytgp_black where UIN='$sql_uin' order by BLACKTEXT";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
    echo "<input type=radio name=blacktext value=".$row["BLACKTEXT"].">".$row["BLACKTEXT"]." from ".$row["BLACKFIELD"]."<BR>";
  }
  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  <input type=submit name=action value="delete black" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br>
  </td><td align=left valign=top>
  <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=new_black size=20 maxlength=100><BR>
  <?echo $lang_admin_20;?>:<BR>
  <input type=radio name=blackfield value="E-MAIL"><? echo $lang_admin_15;?><BR>
  <input type=radio name=blackfield value="URL"><? echo $lang_admin_16;?><BR>
  <input type=radio name=blackfield value="DESCRIPTION"><? echo $lang_admin_17;?><BR>
  <input type=radio name=blackfield value="REFERER"><? echo $lang_admin_18;?><BR>
  <input type=radio name=blackfield value="IP"><? echo $lang_admin_19;?><BR>
  <input type=submit name=action value="add black" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END SETUP CATEGORIES SECTION ?> 

<? // START SETTINGS SECTION
if (($action=="settings") or ($action=="back to settings") or ($action=="save settings"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3>TGP <?echo $lang_admin_4?></h3>
<?
if ($action == "save settings")
{
$adminmail=trim($adminmail);
if ( !ValidEmail($adminmail) ) {show_error_msg ($lang_error_2);}
$progsdir=trim($progsdir);
$tgpmainfile=trim($tgpmainfile);
$tgpmaindir=trim($tgpmaindir);
$tgpmainurl=trim($tgpmainurl);
$sendmail=trim($sendmail);
$maxlist=trim($maxlist);
$maxdom=trim($maxdom);
$maxmail=trim($maxmail);
$updatetime=trim($updatetime);
$rotatedays=trim($rotatedays);
$rotatedaysvip=trim($rotatedaysvip);
$checkfrase=trim($checkfrase);
if ($sendemail) $sendemail="YES"; else $sendemail="NO";
if ($autovalidate) $autovalidate="YES"; else $autovalidate="NO";
if ($check_win) $check_win="YES"; else $check_win="NO";
if ($checkstatus) $checkstatus="YES"; else $checkstatus="NO";
if ($checkonclick) $checkonclick="YES"; else $checkonclick="NO";
if ($checkonmouse) $checkonmouse="YES"; else $checkonmouse="NO";
if ($checkbefore) $checkbefore="YES"; else $checkbefore="NO";
if ($usevip) $usevip="YES"; else $usevip="NO";
if ($usevipfirst) $usevipfirst="YES"; else $usevipfirst="NO";
$query = "update mytgp_set set ADMINPASS='$adminpass',ADMINMAIL='$adminmail',TGPNAME='$tgpname',TGPMAINFILE='$tgpmainfile',TGPMAINDIR='$tgpmaindir',TGPMAINURL='$tgpmainurl',LANGUAGE='$language',SENDMAIL='$sendmail',MAXLIST='$maxlist',AUTOVALIDATE='$autovalidate',SENDEMAIL='$sendemail',MAXDOMAIN='$maxdom',MAXMAIL='$maxmail',UPDATETIME='$updatetime',ROTATEDAYS='$rotatedays',CHECKFRASE='$checkfrase',CHECKBEFORE='$checkbefore',PROGSDIR='$progsdir',CHECKWIN='$check_win',USEVIP='$usevip',USEVIPFIRST='$usevipfirst',MAXPERCATEGORY='$maxpercategory',MAXPERDOMVIP='$maxperdomvip',MAXPERMAILVIP='$maxpermailvip',MAXPERCATEGORYVIP='$maxpercategoryvip',CHECKSTATUS='$checkstatus',CHECKONCLICK='$checkonclick',CHECKONMOUSE='$checkonmouse',ROTATEDAYSVIP='$rotatedaysvip' where UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
$pw=$adminpass;
echo "<p align=center>".$lang_admin_21.".</p>";
} 
?> 
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <?
  ignore_user_abort(true);
  $query = "select * from mytgp_set where UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $msetstr = mysql_fetch_array($result);
  $tgpname_temp=$msetstr['TGPNAME'];
  echo $lang_admin_22.": <input type=Text name=adminpass value=".$msetstr['ADMINPASS']." size=20 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";
  echo $lang_admin_23.": <input type=Text name=adminmail value='".$msetstr['ADMINMAIL']."' size=20 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";
  echo $lang_admin_24.": <input type=Text name=tgpname value='".$tgpname_temp."' size=20 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";
  echo $lang_admin_25.": <select name=language>";
  $handle=opendir($msetstr['PROGSDIR']."language/"); 
  while ($file = readdir($handle)) 
  { 
    if ($file != "." && $file != "..") { 
    if ($file != $msetstr["LANGUAGE"])  echo "<option value=".$file.">".$file."</option>";
    else echo "<option value=".$file." selected>".$file."</option>";
    } 
  }
  closedir($handle); 
  echo "</select><BR>";
  echo $lang_admin_26.": <input type=Text name=progsdir value=".$msetstr['PROGSDIR']." size=50 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (".$lang_admin_44.")<BR>";
  echo $lang_admin_27.": <input type=Text name=tgpmainfile value=".$msetstr['TGPMAINFILE']." size=20 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";
  echo $lang_admin_28.": <input type=Text name=tgpmaindir value=".$msetstr['TGPMAINDIR']." size=50 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (".$lang_admin_44.")<BR>";
  echo $lang_admin_29.": <input type=Text name=tgpmainurl value=".$msetstr['TGPMAINURL']." size=50 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (".$lang_admin_44.")<BR>";
  echo $lang_admin_30.": <input type=Text name=sendmail value=".$msetstr['SENDMAIL']." size=20 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\">&nbsp;&nbsp;&nbsp;&nbsp;";
  echo "<input type=checkbox name=sendemail";
  if ($msetstr['SENDEMAIL']=="YES") echo " checked";
  echo ">$lang_admin_33<br>\n";
  echo $lang_admin_31.": <input type=Text name=maxlist value=".$msetstr['MAXLIST']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";
  echo $lang_admin_37.": <input type=Text name=updatetime value=".$msetstr['UPDATETIME']." size=3 maxlength=3 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> ".$lang_admin_41." (60 - 1 hour, 1440 - 1 day)<BR>";
  echo $lang_admin_38.": <input type=Text name=rotatedays value=".$msetstr['ROTATEDAYS']." size=3 maxlength=3 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> ".$lang_admin_42."&nbsp;&nbsp;&nbsp;&nbsp;";
  echo $lang_admin_96.": <input type=Text name=rotatedaysvip value=".$msetstr['ROTATEDAYSVIP']." size=3 maxlength=3 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> ".$lang_admin_42."<BR>\n";

  echo "<h4>".$lang_admin_101."</h4><hr>";
  echo $lang_admin_35.": <input type=Text name=maxdom value=".$msetstr['MAXDOMAIN']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (0 - ".$lang_admin_40.")&nbsp;&nbsp;&nbsp;&nbsp;";
  echo $lang_admin_96.": <input type=Text name=maxperdomvip value=".$msetstr['MAXPERDOMVIP']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>\n";
  echo $lang_admin_36.": <input type=Text name=maxmail value=".$msetstr['MAXMAIL']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (0 - ".$lang_admin_40.")&nbsp;&nbsp;&nbsp;&nbsp;";
  echo $lang_admin_96.": <input type=Text name=maxpermailvip value=".$msetstr['MAXPERMAILVIP']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>\n";
  echo $lang_admin_102.": <input type=Text name=maxpercategory value=".$msetstr['MAXPERCATEGORY']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"> (0 - ".$lang_admin_40.")&nbsp;&nbsp;&nbsp;&nbsp;";
  echo $lang_admin_96.": <input type=Text name=maxpercategoryvip value=".$msetstr['MAXPERCATEGORYVIP']." size=4 maxlength=4 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>\n";

  echo "<h4>".$lang_admin_104."</h4><hr>";
  echo "<input type=checkbox name=autovalidate";
  if ($msetstr['AUTOVALIDATE']=="YES") echo " checked";
  echo ">$lang_admin_32";
  echo "&nbsp;&nbsp;&nbsp;";
  echo "<input type=checkbox name=checkbefore";
  if ($msetstr['CHECKBEFORE']=="YES") echo " checked";
  echo ">$lang_admin_34<br>\n";
  echo "<input type=checkbox name=check_win";
  if ($msetstr['CHECKWIN']=="YES") echo " checked";
  echo ">$lang_admin_94&nbsp;&nbsp;&nbsp;&nbsp;";
  echo "<input type=checkbox name=checkstatus";
  if ($msetstr['CHECKSTATUS']=="YES") echo " checked";
  echo ">$lang_admin_105&nbsp;&nbsp;&nbsp;&nbsp;";
  echo "<input type=checkbox name=checkonclick";
  if ($msetstr['CHECKONCLICK']=="YES") echo " checked";
  echo ">$lang_admin_106<br>\n";
  echo "<input type=checkbox name=checkonmouse";
  if ($msetstr['CHECKONMOUSE']=="YES") echo " checked";
  echo ">$lang_admin_107<br>\n";
  echo $lang_admin_39.": <input type=Text name=checkfrase value=".$msetstr['CHECKFRASE']." size=15 maxlength=100 style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\"><BR>";


  echo "<h4>".$lang_admin_103."</h4><hr>";
  echo "<input type=checkbox name=usevip";
  if ($msetstr['USEVIP']=="YES") echo " checked";
  echo ">$lang_admin_97";
  echo "&nbsp;&nbsp;&nbsp;";
  echo "<input type=checkbox name=usevipfirst";
  if ($msetstr['USEVIPFIRST']=="YES") echo " checked";
  echo ">$lang_admin_98<BR>\n";

  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value="save settings" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="back" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END SETTINGS SECTION ?> 

<? // START ADD BLACK SECTION
if ($action=="add black") {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><?echo $lang_admin_52;?></font>
<form method="POST">
</td></tr></table>

<?
ignore_user_abort(true);
if (($new_black!="") and ($blackfield!="")) {
$query = "select * from mytgp_black where BLACKTEXT='$new_black' and BLACKFIELD='$blackfield' and UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
if (mysql_num_rows($result)==0) {
$query1 = "insert into mytgp_black values ('$blackfield','$new_black','$sql_uin')";	
$result1 = mysql_query ($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>".$lang_admin_53.": <b>".$blackfield."=".$new_black."</b> ".$lang_admin_54."</p>";
echo "<p align=center>".$lang_admin_108." <b>".$blackfield."=".$new_black."</b>?<br>";
echo "<input type=submit name=action value=\"delete from base\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\"></p>";
}
else echo "<p align=center>".$lang_admin_53." <b>".$new_black."</b> ".$lang_admin_55."</p>";
}
else echo "<p align=center>".$lang_admin_56."</p>";
ignore_user_abort(false);
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=hidden name=new_black value="<?php echo $new_black; ?>">
<input type=hidden name=blackfield value="<?php echo $blackfield; ?>">
<input type=submit name=action value="back black" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END ADD BLACK SECTION ?>

<? // START DELETE BLACK SECTION
if ($action=="delete black") {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><?echo $lang_admin_57;?></font>
</td></tr></table>
<?
ignore_user_abort(true);
if ($blacktext!="") {
$query = "select BLACKTEXT from mytgp_black where BLACKTEXT='$blacktext' and UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
if (mysql_num_rows($result)!=0) {
$query1 = "delete from mytgp_black where BLACKTEXT='$blacktext' and UIN='$sql_uin'";	
$result1 = mysql_query ($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>".$lang_admin_53." <b>".$blacktext."</b> ".$lang_admin_58."</p>";
}
else echo "<p align=center>".$lang_admin_53." <b>".$blacktext."</b> ".$lang_admin_59."</p>";
}
else echo "<p align=center>".$lang_admin_60."</p>";
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back black" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END DELETE BLACK SECTION ?>

<? // START SETUP MAIL SECTION
if (($action=="mail menu") or ($action=="back mail"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_61;?></h3>
  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <?
  ignore_user_abort(true);
  $query = "select * from mytgp_mail where UIN='$sql_uin' order by MAILID";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
    if ($row["MAILPASS"]) echo "<b>";
    echo "<input type=checkbox name=del_mail[] value=".$row["MAILPOST"].">".$row["MAILPOST"];
    if ($row["MAILPASS"]) echo "&nbsp;:&nbsp;".$row["MAILPASS"]."</b>";
    echo "\n<BR>";
  }
  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  <input type=submit name=action value="delete mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br><br>
  </td><td align=left valign=top>
  <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=new_mail size=20 maxlength=100><BR>
  <input type=submit name=action value="add mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <BR><BR>
  <input type=submit name=action value="mass mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <BR><BR>
  <input type=submit name=action value="send mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <BR><BR>
  <input type=submit name=action value="<?echo $lang_admin_93;?>" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">

  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END SETUP MAIL SECTION ?> 

<? // START ADD MAIL SECTION
if ($action=="add mail") {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<h3><?echo $lang_admin_62;?></h3>
</td></tr></table>

<?
ignore_user_abort(true);
$new_mail = trim($new_mail);
if (!ValidEmail($new_mail)) {show_error_msg ($lang_error_2);$new_mail="";}
if ($new_mail!="") {
$query = "select MAILPOST from mytgp_mail where MAILPOST='$new_mail' and UIN='$sql_uin'";
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
if (mysql_num_rows($result)==0) {
$query = "insert into mytgp_mail values ('','$new_mail', '', '$sql_uin')";	
$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>".$lang_admin_15." <b>".$new_mail."</b> ".$lang_admin_49."</p>";
}
else {
echo "<p align=center>".$lang_admin_15." <b>".$new_mail."</b> ".$lang_admin_63."</p>";
}
}
else {
echo "<p align=center>".$lang_admin_64."</p>";
}
ignore_user_abort(false);
?>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END ADD MAIL SECTION ?>

<? // START DELETE MAIL SECTION
if ($action=="delete mail") {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<h3><?echo $lang_admin_65;?></h3>
</td></tr></table>
<?
ignore_user_abort(true);
if (isset($del_mail)) {
   while(list($null, $del_mod) = each($del_mail)) 
   {
   $query = "delete from mytgp_mail where MAILPOST='$del_mod' and UIN='$sql_uin'";	
   $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
   echo "<p align=center>".$lang_admin_15." <b>".$del_mod."</b> ".$lang_admin_45."</p>";
   }
} else echo "<p align=center>".$lang_admin_66."</p>";
ignore_user_abort(false);
?>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END DELETE MAIL SECTION ?>

<? // START TEMPLATES SECTION
if (($action=="templates") or ($action=="back templates") or ($action=="edit template"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_67;?></h3>
  
  <table align=center cellpadding=5>
  <tr><td align=right valign=top>
  <?
  ignore_user_abort(true);
  echo $lang_admin_68.":  <select name=template>";
  $handle=opendir("../templates/"); 
  while ($file = readdir($handle)) 
  { 
    if (($file != ".") && ($file != "..") && (in_array($file, $templates_all)))
    { 
     if ($file == $template) echo "<option value=".$file." selected>".$file."</option>";
      else echo "<option value=".$file.">".$file."</option>";
    } 
  }
  closedir($handle); 
  echo "</select><BR>";

  ignore_user_abort(false);  
  ?>
  <input type=submit name=action value="edit template" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br>
  </td><td align=left valign=top>
<?
if (($action=="edit template"))
 {
   if (in_array($template, $templates_all))
   {
     if ($file = fopen("../templates/".$template,"r"))
      {
      $filecontent = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $filecontent .= $buffer;
       }
      fclose($file);
      } else show_error_msg ($lang_error_3.": ".$template);

    echo "Template file: <b>".$template."</b><BR>";
    echo "<input type=hidden name=template_file value=".$template.">";
    echo "<textarea name=new_template cols=80 rows=10>".$filecontent."</textarea><br>";

    echo $lang_admin_69.":<br>";
    if ($template == $templates_all[0]) // new.tpl
     { 
      echo "<b>&lt;!--start--></b>&nbsp;&nbsp;<b>&lt;!--email--></b>&nbsp;&nbsp;<b>&lt;!--cats--></b>&nbsp;&nbsp;<b>&lt;!--pics--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc--></b>&nbsp;&nbsp;<b>&lt;!--mlist--></b>&nbsp;&nbsp;<b>&lt;!--finish--></b>";
     }
    if ($template == $templates_all[1]) // default.tpl
     { 
      echo "<b>&lt;!--join--></b>&nbsp;&nbsp;<b>&lt;!--terms--></b>&nbsp;&nbsp;<b>&lt;!--navbox--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--list--></b>&nbsp;&nbsp;<b>&lt;!--list|CATNAME|NUMPOST|--></b>";
     }
    if ($template == $templates_all[2]) // terms.tpl
     { 
      echo "<b>&lt;!--join--></b>";
     }
    if ($template == $templates_all[3]) // categories.tpl
     { 
      echo "<b>&lt;!--join--></b>&nbsp;&nbsp;<b>&lt;!--terms--></b>&nbsp;&nbsp;<b>&lt;!--navbox--></b>&nbsp;&nbsp;<b>&lt;!--catname--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--list--></b>";
     }
    if ($template == $templates_all[4]) // mailout.tpl
     { 
      echo "<b>&lt;!--email--></b>&nbsp;&nbsp;<b>&lt;!--catname--></b>&nbsp;&nbsp;<b>&lt;!--pics--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc-->&nbsp;&nbsp;<b>&lt;!--tgpname--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
     }
    if ($template == $templates_all[5]) // mailwelcome.tpl
     { 
      echo "<b>&lt;!--email--></b>&nbsp;&nbsp;<b>&lt;!--catname--></b>&nbsp;&nbsp;<b>&lt;!--pics--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc-->&nbsp;&nbsp;<b>&lt;!--tgpname--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
     }
    if ($template == $templates_all[6]) // post.tpl
     { 
      echo "<b>&lt;!--email--></b>&nbsp;&nbsp;<b>&lt;!--cats--></b>&nbsp;&nbsp;<b>&lt;!--pics--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
     }
    if ($template == $templates_all[7]) // link.tpl
     { 
      echo "<b>&lt;!--date--></b>&nbsp;&nbsp;<b>&lt;!--catname--></b>&nbsp;&nbsp;<b>&lt;!--images--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc--></b>&nbsp;&nbsp;<b>&lt;!--vip--></b>";
     }
    if ($template == $templates_all[8]) // maildelete.tpl
     { 
      echo "<b>&lt;!--email--></b>&nbsp;&nbsp;<b>&lt;!--catname--></b>&nbsp;&nbsp;<b>&lt;!--pics--></b>";
      echo "&nbsp;&nbsp;<b>&lt;!--url--></b>&nbsp;&nbsp;<b>&lt;!--desc-->&nbsp;&nbsp;<b>&lt;!--tgpname--></b>&nbsp;&nbsp;<b>&lt;!--link--></b>";
     }

    echo "<br><input type=submit name=action value=\"save template\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\"><br>";
   }

 }
?>
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END SETUP TEMPLATES SECTION ?> 

<? // START SAVE TEMPLATE SECTION
if ($action=="save template") {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<h3><?echo $lang_admin_70;?></h3>
</td></tr></table>
<p align=center>
<?
     if ($file = fopen( "../templates/".$template_file, "w+" ))
     {
     $len_gen = strlen( $new_template );
     fwrite( $file, StripSlashes($new_template), $len_gen);
     fclose( $file );
     } else show_error_msg ($lang_error_4.": ".$template);
     echo $len_gen." ".$lang_admin_71;
?>
</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back templates" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
exit;
} // END SAVE TEMPLATE SECTION ?>

<? // START EDIT POST SECTION
if (($action=="post menu") or ($action=="back post menu"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_72;?></h3>
  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
<? // Have a non validated posts?
  $query = "select * from mytgp_post where VALIDATED='NO' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $total_waiting_posts = mysql_num_rows($result);
  if ($total_waiting_posts>0) 
  {
  echo "  <input type=submit name=action value=\"validate new\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
  echo "<select name=limit>";  
  echo "<option value=0 selected>All</option>";
  echo "<option value=5>5</option><option value=10>10</option><option value=15>15</option>";
  echo "<option value=20>20</option><option value=25>25</option><option value=30>30</option>";
  echo "<option value=35>35</option><option value=40>40</option>";
  echo "<option value=45>45</option><option value=50>50</option></select>";
  }
?>

  <br>
  <br>
  <select name=category>
  <?
  $query = "select NAME from mytgp_cats where UIN='$sql_uin' order by NAME";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
   $query1 = "select count(postcat) from mytgp_post where UIN='$sql_uin' and postcat='".$row["NAME"]."'";
   $result1 = mysql_query($query1) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
   $total = MYSQL_RESULT($result1,0,"count(postcat)");
   if ($total) echo "<option value=".$row['NAME'].">".$row['NAME']."</option>";
  }
  ?>
  </select>
  <input type=submit name=action value="check post" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="edit posts" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <?
  $query1 = "select * from mytgp_mail where UIN='$sql_uin' and MAILPASS!='' order by MAILID";
  $result1 = mysql_query($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  if (($result1) and ($msetstr["USEVIP"]=="YES"))  echo "<br>\n".$lang_admin_100."&nbsp;<input type=checkbox name=vip_only><br>\n";
  ?>
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END POST MENU SECTION ?> 

<? // START CHECK POST SECTION
if (($action=="check post") or ($action=="validate new") or ($action=="check new") or ($action=="edit posts"))
{
 if (($action=="check new") or ($action=="check post"))
	{
	$snoopy = new Snoopy;
	$snoopy->maxredirs = 0;
	$snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)".$msetstr["TGPNAME"]." link checker";
	$snoopy->read_timeout = 0;
	}
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_73;?></h3>
  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <CENTER>
  <?if($action=="validate new")
  echo "<input type=submit name=action value=\"check new\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
  echo "<input type=hidden name=limit value=$limit>";
  ?>
  <input type=submit name=action value="edit post" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="validate post" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="delete post" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  <input type=submit name=action value="accept changes" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </CENTER>
  <?
  ignore_user_abort(true);

  if (($action=="validate new") or ($action=="check new")) {
  $query = "select * from mytgp_post where VALIDATED='NO' and UIN='$sql_uin' order by POSTDATE desc"; 
  if ($limit != 0) {$query.= " LIMIT 0,".$limit;}
  }
  elseif ($vip_only) $query = "select * from mytgp_post where POSTCAT='$category' and POSTVIP='YES' and UIN='$sql_uin' order by POSTDATE desc";
  else $query = "select * from mytgp_post where POSTCAT='$category' and UIN='$sql_uin' order by POSTDATE desc";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 

  $color = "silver";
  echo "<table align=center><tr>";
  echo "<td align=center bgcolor=".$color."><b>".$lang_admin_95."</b></td>";
  echo "<td align=center bgcolor=".$color."><b>".$lang_admin_6."</b></td>";
  echo "<td align=center bgcolor=".$color."><b>".$lang_admin_17."</b></td>";
  echo "<td align=center bgcolor=".$color."><b>".$lang_admin_74."</b></td>";
  echo "<td align=center bgcolor=".$color."><b>".$lang_admin_15."</b></td>";
if (($action=="check post") or ($action=="check new")) echo "<td bgcolor=".$color." colspan=2>".$lang_admin_75."</td>";
  echo "<td bgcolor=".$color.">Del</td>";
  echo "<td bgcolor=".$color.">Val.</td>";
  echo "<td bgcolor=".$color.">Edit</td>";
if ($msetstr['USEVIP']=="YES") echo "<td bgcolor=".$color.">".$lang_admin_96."</td>";
  echo "</tr>";
  while($row = mysql_fetch_array($result)) 
{
    ($color=="silver") ? ($color="white") : ($color="silver");
    echo "<tr>";
    $date = unix_to_date($row["POSTDATE"]);
    echo "<td bgcolor=".$color.">".$date."</td>";
    echo "<td bgcolor=".$color.">".$row["POSTCAT"]."</td>";
    echo "<td bgcolor=".$color."><a href=".$row["POSTURL"]." target=_blank>".$row["POSTDESCR"]."</a></td>";
    echo "<td bgcolor=".$color." align=center>".$row["POSTNUM"]."</td>";
    echo "<td bgcolor=".$color.">".$row["POSTEMAIL"]."</td>";
 $found=1;
 if (($action=="check new") or ($action=="check post"))
   {
        echo "<td bgcolor=".$color.">";
       	if($snoopy->fetch($row["POSTURL"]))
	{
                $code = $snoopy->response_code;
		list ($head, $code, $text) = split (" ", $code);
                if (($code == "404") or ($code == "500")) $color1 = "Red"; 
                elseif ($code == "302") $color1 = "Blue";
 		else $color1="Green";
		echo "<font color=".$color1.">".$code."</font>\n";
        }
	else
		echo "<font color=Red>ERR</font>\n";
    echo "</td>";
    echo "<td bgcolor=".$color.">";
    $found=0;
    if ($color1 == "Green")
       {	   
	while(list($key,$val) = each($snoopy->headers))
	 {
           if (stristr(htmlspecialchars($snoopy->results), $msetstr["CHECKFRASE"])) {$found=1;break;}
         }
	 if ($row["POSTVIP"]=="YES") $found=1;	// Don't check VIP on return link
         if ($found)  echo "<font color=Green>Yes</font>"; else echo "<font color=Red>NO</font>";
        }
    echo "</td>";
      }
    echo "<td bgcolor=".$color."><input type=checkbox name=del_post[] value=".$row['POSTID'];

   if (!$found) echo " checked></td>";
   else echo "></td>";

    echo "<td bgcolor=".$color."><input type=checkbox name=val_post[] value=".$row['POSTID'];

   if ($found) echo " checked></td>";
   else echo "></td>";

    echo "<td bgcolor=".$color."><input type=radio name=edit_post value=".$row["POSTID"]."></td>";

  if ($msetstr['USEVIP']=="YES") 
  {
  echo "<td bgcolor=".$color."><input disabled type=checkbox name=vip_post[] value=".$row['POSTID'];
  if ($row["POSTVIP"]=="YES") echo " checked></td>";
  else echo "></td>";
  }
  
  echo "</tr>";
  flush();
}
echo "</table>";
  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END EDIT POST SECTION ?> 

<? // START DELETE POST SECTION
if (($action=="delete post") or ($action=="accept changes") or ($action=="validate post")) {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<h3><?echo $lang_admin_76;?></h3>
</td></tr></table>
<?
ignore_user_abort(true);

if (($action=="accept changes") or ($action=="validate post")) {
	if (isset($val_post)) {
	   while(list($null, $val_mod) = each($val_post)) 
	   {
	   $query = "update mytgp_post set VALIDATED='YES' where POSTID='$val_mod' and UIN='$sql_uin'";	
	   $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	   echo "<center>".$lang_admin_10." <b>".$val_mod."</b> ".$lang_admin_79."</center>\n";
	   }
	} else echo "<p align=center>".$lang_admin_80."</p>";
} 
if (($action=="delete post") or ($action=="accept changes")) {
	if (isset($del_post)) {
	   while(list($null, $del_mod) = each($del_post)) 
	   {
	  if ($msetstr["SENDEMAIL"]=='YES')
	  {
	   $query = "select * from mytgp_post where POSTID='$del_mod' and UIN='$sql_uin'";	
	   $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	   $row = mysql_fetch_array ($result);
	   send_mail_delete($row['POSTEMAIL'], $row['POSTNUM'], $row['POSTURL'], $row['DESCR'], $row['POSTCAT']);
	  }
	   $query = "delete from mytgp_post where POSTID='$del_mod' and UIN='$sql_uin'";	
	   $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	
	   echo "<center>".$lang_admin_10." <b>".$del_mod."</b> ".$lang_admin_45."</center>\n";
	   }
	} else echo "<p align=center>".$lang_admin_77."</p>";
}
ignore_user_abort(false);
?>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back post menu" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END DELETE POST SECTION ?>

<? // START SEND MAIL SECTION
if (($action=="send mail") or ($action=="send") or ($action=="mass mail") or ($action==$lang_admin_93)) {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Send mail result</font>
<form method="POST">
</td></tr></table>
<table align=center width=90% bgcolor=white border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<tr><td width=100%>
<?
ignore_user_abort(true);
if ($action=="send mail")
{
    echo "<b>To:</b> <input type=text name=mail_to value='";
if (isset($del_mail)) {
   $cur = 0;
   while(list($null, $del_mod) = each($del_mail)) 
   {
   $cur++;
   if ($cur == 1) echo "$del_mod";
   else echo ", $del_mod";
   }
} 
    echo "' style='font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;' size=100 maxlength=250><BR>\n";
    echo "<b>".$lang_admin_82.":</b> ".$msetstr['TGPNAME']." &lt;".$msetstr['ADMINMAIL']."><BR>\n";
    echo "<b>".$lang_admin_83.":</b> <input type=text name=mail_subj value='' style='font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;' maxlength=100><BR>\n";
    echo "<b>".$lang_admin_84.":</b><br>\n";
    echo "<textarea name=mail_message cols=80 rows=10>".$filecontent."</textarea><br><br>\n";
    echo "<input type=hidden name=mass value=0>\n";
    echo "<input type=submit name=action value='send' style='border-width: 1; border-style: solid; font-size: x-small; font-family: arial'><br>\n";
} elseif ($action=="mass mail") // mass mail here
{
    echo "<b>To:</b> All posters<br>";
    echo "<b>".$lang_admin_82.":</b> ".$msetstr['TGPNAME']." &lt;".$msetstr['ADMINMAIL']."><BR>\n";
    echo "<b>".$lang_admin_83.":</b> <input type=text name=mail_subj value='' style='font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;' maxlength=100><BR>\n";
    echo "<b>".$lang_admin_84.":</b><br>\n";
    echo "<textarea name=mail_message cols=80 rows=10>".$filecontent."</textarea><br><br>\n";
  $query = "select MAILPOST from mytgp_mail where UIN='$sql_uin' order by MAILPOST";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $cur = 0;
  while($row = mysql_fetch_array($result))
     {
   $cur++;
   if ($cur == 1) $mail .= $row["MAILPOST"];
   else $mail .= ",".$row["MAILPOST"];;
   }
    echo "<input type=hidden name=mail_to value='$mail'>\n";
    echo "<input type=hidden name=mass value=1>\n";
    echo "<input type=submit name=action value='send' style='border-width: 1; border-style: solid; font-size: x-small; font-family: arial'><br>\n";

} elseif ($action==$lang_admin_93) // Send contact to Author
{
    echo "<b>To:</b> Author &lt;".$phpMyTGP_Author_mail."><br>\n";
    echo "<b>".$lang_admin_82.":</b> ".$msetstr['TGPNAME']." &lt;".$msetstr['ADMINMAIL']."><BR>\n";
    echo "<b>".$lang_admin_83.":</b> <input type=text name=mail_subj value='' style='font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;' maxlength=100><BR>\n";
    echo "<b>".$lang_admin_84.":</b><br>\n";
    echo "<textarea name=mail_message cols=80 rows=10>".$filecontent."</textarea><br><br>\n";
    echo "<input type=hidden name=mail_to value='$phpMyTGP_Author_mail'>\n";
    echo "<input type=hidden name=mass value=0>\n";
    echo "<input type=hidden name=author value=1>\n";
    echo "<input type=submit name=action value='send' style='border-width: 1; border-style: solid; font-size: x-small; font-family: arial'><br>\n";
}
else // just send mail
{
 $mail = array ();
 $mail = split (",", $mail_to);
foreach ($mail as $key => $value) {
    $mail[$key] = trim($value);
}
 if ($author==1) 
 $mail_message .= "\nFrom url: ".$msetstr['TGPMAINURL']."\n";
 if (!send_mail ($mail, $mail_subj, $mail_message, $mass)) show_error_msg ($lang_admin_85);
   else
 echo "<p>".$lang_admin_86."</p>";
}


ignore_user_abort(false);
?>
</td></tr></table>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back mail" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END SEND MAIL SECTION
?>

<? // START EDIT POST SECTION
if (($action=="edit post") or ($action=="save post")) {
?>
<?show_admin_header();?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Edit post result</font>
<form method="POST">
</td></tr></table>
<table align=center width=90% bgcolor=white border=1 bordercolor=black cellspacing=0><tr><td width=100% align=left>
<?
   ignore_user_abort(true);
if ($action=="edit post")
   {
   if ($edit_post) 
   {
      $query = "select * from mytgp_post where POSTID='$edit_post' and UIN='$sql_uin'";
      $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
      $row = mysql_fetch_array($result);

	echo "<b>".$lang_admin_6.":</b> <select name=category style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";	
	$query1 = "select * from mytgp_cats where UIN='$sql_uin' order by NAME";
	$result1 = mysql_query($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	while($row1 = mysql_fetch_array($result1)) 
	    {
	    echo "<option value=".$row1["NAME"];
	    if ($row1["NAME"]==$row["POSTCAT"]) echo " selected";
	    echo ">".$row1["NAME"]."</option>\n";
 	    }
        echo "</select><BR>\n";
	
	echo "<b>".$lang_admin_17.":</b> <input type=text name=description value='".$row["POSTDESCR"]."' style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=100 size=50><BR>\n";
	echo "<b>".$lang_admin_16.":</b> <input type=text name=url value='".$row["POSTURL"]."' style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=100 size=50><BR>\n";
	echo "<b>".$lang_admin_74.":</b> <input type=text name=pics value='".$row["POSTNUM"]."' style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=3 size=3><BR>\n";
	echo "<b>".$lang_admin_15.":</b> <input type=text name=email value='".$row["POSTEMAIL"]."' style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" maxlength=50 size=25><BR>\n";
	echo "<b>".$lang_admin_87.":</b> <select name=validated style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
        echo "<option value=YES";
	if ($row["VALIDATED"]=="YES") echo " selected>YES</option>"; else echo ">YES</option>";
        echo "<option value=NO";
        if ($row["VALIDATED"]=="NO") echo " selected>NO</option>"; else echo ">NO</option>";	
	echo "</select><BR>";
	echo "<b>".$lang_admin_96."?</b> <select name=vip style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
        echo "<option value=YES";
	if ($row["POSTVIP"]=="YES") echo " selected>YES</option>"; else echo ">YES</option>";
        echo "<option value=NO";
        if ($row["POSTVIP"]=="NO") echo " selected>NO</option>"; else echo ">NO</option>";	
	echo "</select><BR>\n";
	echo "<b>".$lang_admin_89.":</b> ".unix_to_date($row['POSTDATE'],1)."<BR>";
	echo "<b>".$lang_admin_90.":</b> ".$row['POSTREF']."<BR>\n";
	echo "<b>".$lang_admin_19.":</b> ".$row['POSTIP']."<BR>\n";
	echo "<b>".$lang_admin_91."?</b> <input type=checkbox name=delete value='".$row["POSTID"]."' style=\"font-size: x-small; font-family: arial; background-color: white;\"><BR><BR>\n";

        echo "<input type=hidden name=id value=".$row["POSTID"].">";
        echo "<input type=submit name=action value=\"save post\" style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">";
   }
   else echo "<p align=center>".$lang_admin_88."</p>";
   }
  else
  {
   // delete post checked
    if ($delete)
    {
	if ($msetstr["SENDEMAIL"]=='YES')
  	{
	$query = "select * from mytgp_post where POSTID='$id' and UIN='$sql_uin'";	
	$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	$row = mysql_fetch_array ($result);
	send_mail_delete($row['POSTEMAIL'], $row['POSTNUM'], $row['POSTURL'], $row['DESCR'], $row['POSTCAT']);
	}
	$query = "delete from mytgp_post where POSTID='$id' and UIN='$sql_uin'";
	$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
        echo "<p align=center>".$lang_admin_10." ".$lang_admin_45."</p>";       
    }
   // just save post
    else {
	$query = "update mytgp_post set POSTCAT='$category',POSTDESCR='$description',POSTURL='$url',POSTNUM='$pics',POSTEMAIL='$email',VALIDATED='$validated',POSTVIP='$vip' where POSTID='$id'";
	$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
        echo "<p align=center>".$lang_admin_92."</p>";       
    }
  }
   ignore_user_abort(false);
?>
</td></tr></table>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value="back post menu" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?
mysql_close ($link);
exit;
} // END DELETE POST SECTION ?>


<?
// START EDIT VIP SECTION
if (($action=="VIP menu") or ($action=="back VIP") or ($action=="add VIP") or ($action=="delete VIP"))
	{
?>
<?show_admin_header();?>
  <tr><td align=center width=80% bgcolor=white>
  <form method="POST">
  <input type=hidden name=pw value="<?php echo $pw; ?>">
  <h2>phpMyTGP <?echo $lang_admin_2.'. '.$msetstr["TGPNAME"]?></h2>
  <h3><?echo $lang_admin_99;?></h3>

<?
if ($action=="delete VIP") { // DELETE VIP
  ignore_user_abort(true);

	if (isset($del_mail)) {
	   while(list($null, $del_mod) = each($del_mail)) 
	   {
	   $query = "delete from mytgp_mail where MAILPOST='$del_mod' and UIN='$sql_uin'";	
	   $result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	   echo "<p align=center>".$lang_admin_15." <b>".$del_mod."</b> ".$lang_admin_45."</p>";
	   }
	} else echo "<p align=center>".$lang_admin_66."</p>";
  ignore_user_abort(false);

}
?>

<? if ($action=="add VIP") { // ADD VIP
  ignore_user_abort(true);

	$new_mail = trim($new_mail);
	$new_pass = trim($new_pass);
	if (!ValidEmail($new_mail)) {show_error_msg ($lang_error_2);$new_mail="";}
	if (($new_mail) and ($new_pass)) {
	$query = "select MAILPOST from mytgp_mail where MAILPOST='$new_mail' and UIN='$sql_uin'";
	$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	if (mysql_num_rows($result)==0) {
	$query = "insert into mytgp_mail values ('','$new_mail', '$new_pass', '$sql_uin')";	
	$result = mysql_query ($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	echo "<p align=center>".$lang_admin_15." <b>".$new_mail."&nbsp;:&nbsp;".$new_pass."</b> ".$lang_admin_49."</p>";
	}
	else {
	echo "<p align=center>".$lang_admin_15." <b>".$new_mail."&nbsp;:&nbsp;".$new_pass."</b> ".$lang_admin_63."</p>";
	}
   }
else {
echo "<p align=center>".$lang_admin_64."</p>";
}
  ignore_user_abort(false);

   }
?>

  
  <table align=center cellpadding=5>
  <tr><td align=left valign=top>
  <?
  ignore_user_abort(true);
  $query = "select * from mytgp_mail where UIN='$sql_uin' and MAILPASS!='' order by MAILID";
  $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
    echo "<input type=checkbox name=del_mail[] value=".$row["MAILPOST"].">".$row["MAILPOST"]."&nbsp;:&nbsp;".$row["MAILPASS"]."<BR>\n";
  }
  mysql_free_result($result);

  ignore_user_abort(false);  
  ?>
  <input type=submit name=action value="delete VIP" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br><br>
  </td><td align=right valign=top>
  <?echo $lang_admin_15;?>:&nbsp;<input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=new_mail size=20 maxlength=100><BR>
  <?echo $lang_admin_1;?>:&nbsp;<input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=new_pass size=20 maxlength=100><BR>
  <input type=submit name=action value="add VIP" style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr></table>

  </td></tr>
  <tr><td align=center width=80% bgcolor=white>
  <input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
  </td></tr>
  <tr><td align=center width=80%>
  <?echo $phpMyTGP_link;?>
  </td></tr>
  </table>
  </form>
  </body>
  </html>
<?	mysql_close ($link);
	exit;
	}
// END EDIT VIP SECTION ?> 
