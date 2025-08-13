<?
include("./admin/include.inc");
include("./admin/funcs.inc");
include("./admin/libmail.inc");
include("./admin/Snoopy.class.inc");
$link = mysql_connect ($sql_host, $sql_user , $sql_pass);
mysql_select_db($sql_db);
$query = "select * from mytgp_set where UIN='$sql_uin'";
$result = mysql_query($query);
$msetstr = mysql_fetch_array($result);
list($temp, $global_extension) = split("[.]", $msetstr["TGPMAINFILE"]);
include_once($msetstr["PROGSDIR"]."language/".$msetstr["LANGUAGE"]);
if (!$action) 
{
$IP = getenv("REMOTE_ADDR");
$REFERER = getenv("HTTP_REFERER");
}
$file_terms=0;
$file_new=0;
$file_post=0;
  $handle=opendir("./templates/"); 
  while ($file = readdir($handle)) 
  { 
    if ($file != "." && $file != "..") { 
      if ($file == $templates_all[2]) $file_terms=1;
      if ($file == $templates_all[0]) $file_new=1;
      if ($file == $templates_all[6]) $file_post=1;
    } 
  }
  closedir($handle); 
if (!$action) 
{
 if (($file_terms) and ($file_terms = fopen("./templates/".$templates_all[2],"r")))
  {
   $filecontent = "";
   while (!feof($file_terms)) 
    {
     $buffer = fgets($file_terms, 4096);
     $filecontent .= $buffer;
    }
   fclose($file_terms);
   $filecontent = str_replace ("<!--join-->","<form><input type=hidden name=IP value='$IP'><input type=hidden name=REFERER value='$REFERER'><input type=submit name=action value=Agree style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\"></form>\n<br><center><SCRIPT LANGUAGE=JavaScript SRC=http://www.adultbussiness.com/phpAdsNew/remotehtmlview.php?what=sponsor&target=_blank></SCRIPT></center>",$filecontent);
   echo $filecontent;
  } else show_error_msg ($lang_interface4." ".$templates_all[2]);
 $action="Agree";
 exit;
}
?>

<? // START ADD POST SECTION
if ($action=="Agree")
	{   
 if (($file_new) and ($file_new = fopen("./templates/".$templates_all[0],"r")))
  {
   $filecontent = "";
   while (!feof($file_new)) 
    {
     $buffer = fgets($file_new, 4096);
     $filecontent .= $buffer;
    }
   fclose($file_new);
   $filecontent = str_replace ("<!--start-->","<form method=POST>",$filecontent);
   $filecontent = str_replace ("<!--finish-->","<input type=Password style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" name=password size=20 maxlength=100><br><br>\n\n<input type=submit name=action value=Add style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\">&nbsp;&nbsp;<input type=reset name=action value=Reset style=\"border-width: 1; border-style: solid; font-size: x-small; font-family: arial\"></form>\n<br><center><SCRIPT LANGUAGE=JavaScript SRC=http://www.adultbussiness.com/phpAdsNew/remotehtmlview.php?what=sponsor&target=_blank></SCRIPT></center>",$filecontent);
   $cats="";
   $query = "select * from mytgp_cats where UIN='$sql_uin' order by NAME";
   $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
   $cats = $cats."<select name=category>";
   while($row = mysql_fetch_array($result)) {
    $cats = $cats."<option value=".$row['NAME'].">".$row['NAME'];
   }
   $cats = $cats."</select>";
   $filecontent = str_replace ("<!--cats-->",$cats,$filecontent);
   $filecontent = str_replace ("<!--email-->","<input type=Text style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" name=email size=20 maxlength=100>",$filecontent);
   $filecontent = str_replace ("<!--pics-->","<input type=Text style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" name=pics size=3 maxlength=3>",$filecontent);
   $filecontent = str_replace ("<!--url-->","<input type=Text style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" name=url size=50 maxlength=100>",$filecontent);
   $filecontent = str_replace ("<!--desc-->","<input type=Text style=\"font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;\" name=desc size=50 maxlength=100>",$filecontent);
   $filecontent = str_replace ("<!--mlist-->","<input type=checkbox name=mlist value=YES>",$filecontent);
   echo $filecontent;

  }
 else {echo $lang_interface4." ".$templates_all[0];exit;}
?>
<?	mysql_close ($link);
	exit;
	}
// END ADD POST SECTION ?> 

<? // START END ADD SECTION
if ($action=="Add")
	{   
ignore_user_abort(true);

$checktime = time() - 86400;

$email = trim($email);

if (!ValidEmail($email)) {show_error_msg ("Wrong E-Mail address");exit;}
  $query = "select MAILPOST, MAILPASS from mytgp_mail where MAILPOST='$email' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  if (!($row = mysql_fetch_array($result))) {show_error_msg ("Can't find VIP E-mail");exit;}
  if ($row['MAILPASS']!=$password) {show_error_msg ("Wrong combination: E-mail and password");exit;}

// Check max post per E-Mail VIP
if ($msetstr["MAXPERMAILVIP"]>0)
	{
	$query = "select POSTID from mytgp_post where POSTDATE>'$checktime' and POSTEMAIL='$email' and UIN='$sql_uin'";
	$result = mysql_query($query); // or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	if (mysql_num_rows($result)>$msetstr["MAXPERMAILVIP"]) {show_error_msg ("<b>".$email."</b> ".$lang_interface1);exit;} //too many daily post per E-Mail
	}

$pics = intval(trim($pics));
if ((!$pics) or ($pics>100) or ($pics<0)) {show_error_msg ("Change number of pics");exit;}

  $query = "select * from mytgp_black where BLACKFIELD='REFERER' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) { 
    if (strpos($REFERER,$row["BLACKTEXT"])) {show_error_msg ("Referer <b>".$row["BLACKTEXT"]."</b> in Blacklist");exit;}
  }

  $query = "select * from mytgp_black where BLACKFIELD='IP' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) { 
    if (strpos($IP,$row["BLACKTEXT"])) {show_error_msg ("IP <b>".$row["BLACKTEXT"]."</b> in Blacklist");exit;}
  }

$desc = trim($desc);
if (!strrpos($desc," ") or (strlen($desc)<15)) {show_error_msg ("Wrong description");exit;}
  $query = "select * from mytgp_black where BLACKFIELD='DESCRIPTION' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) { 
    if (strpos($desc,$row["BLACKTEXT"])) {show_error_msg ("Description <b>".$row["BLACKTEXT"]."</b> in Blacklist");exit;}
  }

$url = trim($url);
if (strpos($url,"http://") or !strrpos($url,".") or (strlen($url)<15)) {show_error_msg ("Wrong url");exit;}
  $query = "select * from mytgp_black where BLACKFIELD='URL' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) 
    if (strpos($url,$row["BLACKTEXT"])) {show_error_msg ("URL <b>".$row["BLACKTEXT"]."</b> in Blacklist");exit;}
// Check duplicate url
  $query = "select * from mytgp_post where POSTURL='$url' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  if (mysql_num_rows($result)!=0) {show_error_msg ("URL <b>".$url."</b> is already exist in database");exit;}

// Check max daily post per domain VIP
  if ($msetstr["MAXPERDOMVIP"]>0) 
  {
  $count=0;
  $temp_url=parse_url($url);
  $temp_url=eregi_replace("www.","",$temp_url["host"]);
  $query = "select * from mytgp_post where POSTDATE>'$checktime' and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) {
    $temp_url1 = parse_url($row["POSTURL"]);
    $temp_url1 = eregi_replace("www.","",$temp_url1["host"]);
    if (stristr($temp_url,$temp_url1)) $count++;
    if ($count>=$msetstr["MAXPERDOMVIP"]) {show_error_msg ("URL <b>".$url."</b> too many post per domain ".$temp_url1);exit;}
    }
  }

   if ($msetstr["CHECKBEFORE"]=="YES")
   {
    $precheck_result = precheck_posting($url);
    if ($precheck_result!="FOUND"){
    if ($precheck_result=="NOT FOUND") {show_error_msg ("ERROR: Can't find linkback to my site");exit;}
    elseif (($msetstr["CHECKWIN"]=="YES")&&($precheck_result=="WINDOW")) {show_error_msg ("ERROR: Found window.open script: ".$url);exit;}
    elseif ($precheck_result=="ERR") {show_error_msg ("ERROR: Can't connect to your gallery: ".$url);exit;}
    elseif ($precheck_result!="200") {show_error_msg ("ERROR: Can't connect to your gallery: ".$url." with result code: ".$precheck_result);exit;}
    }
   }

if (!$category) {show_error_msg ("Wrong category");exit;}

// Check max post per Category VIP
if ($msetstr["MAXPERCATEGORYVIP"]>0)
	{
	$query = "select POSTIP from mytgp_post where POSTDATE>'$checktime' and POSTIP='$IP' and POSTCAT='$category' and UIN='$sql_uin'";
	$result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
	if (mysql_num_rows($result)>=$msetstr["MAXPERCATEGORYVIP"]) {show_error_msg ("<b>".$category."</b> ".$lang_interface2);exit;} //too many daily post in one category
	}

  $query = "select * from mytgp_black where BLACKFIELD='E-MAIL'and UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  while($row = mysql_fetch_array($result)) 
    if (strpos($email,$row["BLACKTEXT"])) {show_error_msg ("E-MAIL <b>".$row["BLACKTEXT"]."</b> in Blacklist");exit;}

  $query = "select * from mytgp_set where UIN='$sql_uin'";
  $result = mysql_query($query) or die ($lang_interface3.$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
  $msetstr = mysql_fetch_array($result);
  $validated = "YES";
  
  $date = time();


// POSTID, POSTIP, POSTREF, POSTCAT, POSTDESCR, POSTNUM, POSTURL, POSTEMAIL, VALIDATED, POSTDATE, POSTCHECK, UIN, POSTVIP
$query = "insert into mytgp_post values ('','$IP','$REFERER','$category','$desc','$pics','$url','$email','$validated','$date', '', '$sql_uin', 'YES')";
$result = mysql_query ($query) or die ($lang_interface3." ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>"); 
echo "<p align=center>Adding post done.</p>";

 if (($file_post) and ($file_post = fopen("./templates/".$templates_all[6],"r")))
  {
   $filecontent = "";
   while (!feof($file_post)) 
    {
     $buffer = fgets($file_post, 4096);
     $filecontent .= $buffer;
    }
   fclose($file_post);
   $filecontent = str_replace ("<!--cats-->",$category,$filecontent);
   $filecontent = str_replace ("<!--email-->",$email,$filecontent);
   $filecontent = str_replace ("<!--pics-->",$pics,$filecontent);
   $filecontent = str_replace ("<!--url-->",$url,$filecontent);
   $filecontent = str_replace ("<!--desc-->",$desc,$filecontent);

// <!--link-->
   $filecontent = str_replace ("<!--link-->",$link,$filecontent);
   echo $filecontent;
  } else {
?>
 <html>
 <head>
  <title>phpMyTGP <?echo $phpMyTGP_ver.' '.$lang_add_2.'. '.$msetstr["TGPNAME"]?></title>
 </head>
 <style type="text/css">
 <!--
 BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
 a:link { color:black;text-decoration:none;font-weight:bold} 
 -->
 </style>
 <body>
 <table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
 <font size=2>Add posting result</font>
 </td></tr></table>
 <p align=center><?echo $lang_interface4." ".$templates_all[6];?></p>
 </body></html>
<?
 }
 

// SEND POSTING MAIL HERE
  if ($msetstr["SENDEMAIL"]=='YES')
  {
   send_mail_welcome($email, $pics, $url, $desc, $category);
   }

if (time()>($msetstr["LASTUPDATE"]+$msetstr["UPDATETIME"]*60)){
   update_posts();
   update_templates();
   update_lastupdate();}

ignore_user_abort(false);
?>
</body>
</html>
<?	mysql_close ($link);
	exit;
	}
?>

