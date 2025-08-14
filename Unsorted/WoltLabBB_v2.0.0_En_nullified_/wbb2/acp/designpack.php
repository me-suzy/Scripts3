<?php
require ("global.php");
isAdmin();

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action=="add") {
 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=trim($val);	
  
  $db->query("INSERT INTO bb".$n."_subvariablepacks (subvariablepackid,subvariablepackname) VALUES (NULL,'".addslashes($subvariablepackname)."')");
  $subvariablepackid=$db->insert_id();
  
  $body="<body bgcolor=\"$bgcolor\" text=\"$textcolor\"".ifelse($bodytags," $bodytags");
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','<body','".addslashes($body)."')",1);
  if($imagelogo) $imagelogo="<img src=\"$imagelogo\" border=0>";
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{imagelogo}','".addslashes($imagelogo)."')",1);
  if($imageback) $imageback="background=\"$imageback\"";
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{imageback}','".addslashes($imageback)."')",1);
  if($cssfile) $cssfile="<link rel=\"stylesheet\" href=\"$cssfile\">";
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{cssfile}','".addslashes($cssfile)."')",1);
  
  $normalfont="<font face=\"$normalfont\" size=\"$normalfontsize\"".ifelse($normalfonttags," $normalfonttags");
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','<normalfont','".addslashes($normalfont)."')",1);
  $smallfont="<font face=\"$smallfont\" size=\"$smallfontsize\"".ifelse($smallfonttags," $smallfonttags");
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','<smallfont','".addslashes($smallfont)."')",1);
  
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{imagefolder}','".addslashes($imagefolder)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tableoutbordercolor}','".addslashes($tableoutbordercolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tableinbordercolor}','".addslashes($tableinbordercolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tabletitlecolor}','".addslashes($tabletitlecolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tablecolora}','".addslashes($tablecolora)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tablecolorb}','".addslashes($tablecolorb)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{fontcolorsecond}','".addslashes($fontcolorsecond)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{fontcolorthird}','".addslashes($fontcolorthird)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tablecatcolor}','".addslashes($tablecatcolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tableinwidth}','".addslashes($tableinwidth)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{tableoutwidth}','".addslashes($tableoutwidth)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{timecolor}','".addslashes($timecolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{imagefolder}','".addslashes($imagefolder)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{inposttablecolor}','".addslashes($inposttablecolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{prefixcolor}','".addslashes($prefixcolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{mainbgcolor}','".addslashes($mainbgcolor)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{css}','".addslashes($css)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','{!DOCTYPE}','".addslashes($doctype)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','<hilight>','".addslashes($hilightstart)."')",1);
  $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','</hilight>','".addslashes($hilightend)."')",1);
  
  header("Location: designpack.php?action=view&sid=$session[hash]");
  exit();	
 }
 
 eval("print(\"".gettemplate("designpack_add")."\");");	
}

if($action=="view") {
 $count=0;
 $designpack_viewbit="";
 $result=$db->query("SELECT * FROM bb".$n."_subvariablepacks ORDER BY subvariablepackname");	
 while($row=$db->fetch_array($result)) {
  $rowclass = getone($count++,"firstrow","secondrow");
  eval ("\$designpack_viewbit .= \"".gettemplate("designpack_viewbit")."\";");	
 }
 
 eval("print(\"".gettemplate("designpack_view")."\");");		
}

if($action=="edit") {
 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=trim($val);	
  $subvariablepackid=intval($subvariablepackid);
  	
  $db->unbuffered_query("UPDATE bb".$n."_subvariablepacks SET subvariablepackname='".addslashes($subvariablepackname)."' WHERE subvariablepackid='$subvariablepackid'",1);
  
  $body="<body bgcolor=\"$bgcolor\" text=\"$textcolor\"".ifelse($bodytags," $bodytags");
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($body)."' WHERE variable='<body' AND subvariablepackid='$subvariablepackid'",1);
  if($imagelogo) $imagelogo="<img src=\"$imagelogo\" border=0>";
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($imagelogo)."' WHERE variable='{imagelogo}' AND subvariablepackid='$subvariablepackid'",1);
  if($imageback) $imageback="background=\"$imageback\"";
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($imageback)."' WHERE variable='{imageback}' AND subvariablepackid='$subvariablepackid'",1);
  if($cssfile) $cssfile="<link rel=\"stylesheet\" href=\"$cssfile\">";
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($cssfile)."' WHERE variable='{cssfile}' AND subvariablepackid='$subvariablepackid'",1);
  
  $normalfont="<font face=\"$normalfont\" size=\"$normalfontsize\"".ifelse($normalfonttags," $normalfonttags");
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($normalfont)."' WHERE variable='<normalfont' AND subvariablepackid='$subvariablepackid'",1);
  $smallfont="<font face=\"$smallfont\" size=\"$smallfontsize\"".ifelse($smallfonttags," $smallfonttags");
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($smallfont)."' WHERE variable='<smallfont' AND subvariablepackid='$subvariablepackid'",1);
  
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($imagefolder)."' WHERE variable='{imagefolder}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tableoutbordercolor)."' WHERE variable='{tableoutbordercolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tableinbordercolor)."' WHERE variable='{tableinbordercolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tabletitlecolor)."' WHERE variable='{tabletitlecolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tablecolora)."' WHERE variable='{tablecolora}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tablecolorb)."' WHERE variable='{tablecolorb}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($fontcolorsecond)."' WHERE variable='{fontcolorsecond}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($fontcolorthird)."' WHERE variable='{fontcolorthird}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tablecatcolor)."' WHERE variable='{tablecatcolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tableinwidth)."' WHERE variable='{tableinwidth}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($tableoutwidth)."' WHERE variable='{tableoutwidth}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($timecolor)."' WHERE variable='{timecolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($imagefolder)."' WHERE variable='{imagefolder}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($inposttablecolor)."' WHERE variable='{inposttablecolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($prefixcolor)."' WHERE variable='{prefixcolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($mainbgcolor)."' WHERE variable='{mainbgcolor}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($css)."' WHERE variable='{css}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($doctype)."' WHERE variable='{!DOCTYPE}' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($hilightstart)."' WHERE variable='<hilight>' AND subvariablepackid='$subvariablepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_subvariables SET substitute='".addslashes($hilightend)."' WHERE variable='</hilight>' AND subvariablepackid='$subvariablepackid'",1);
  
  header("Location: designpack.php?action=view&sid=$session[hash]");
  exit();	
 }
 
 $subvariablepackid=intval($_REQUEST['subvariablepackid']);
 $dp = $db->query_first("SELECT * FROM bb".$n."_subvariablepacks WHERE subvariablepackid='$subvariablepackid'");
 $result=$db->query("SELECT * FROM bb".$n."_subvariables WHERE subvariablepackid='$subvariablepackid'");
 while($row=$db->fetch_array($result)) {
  switch($row['variable']) {
   case "<body":
    preg_match("/<body bgcolor=\"([^\"]*)\" text=\"([^\"]*)\"(.*)/i",$row['substitute'],$match);
    $bgcolor=htmlspecialchars($match[1]);
    $textcolor=htmlspecialchars($match[2]);
    $bodytags=htmlspecialchars(trim($match[3]));
    break;
   case "<smallfont":
    preg_match("/<font face=\"([^\"]*)\" size=\"([^\"]*)\"(.*)/i",$row['substitute'],$match);
    $smallfont=htmlspecialchars($match[1]);
    $smallfontsize=htmlspecialchars($match[2]);
    $smallfonttags=htmlspecialchars(trim($match[3]));
    break;
   case "<normalfont":
    preg_match("/<font face=\"([^\"]*)\" size=\"([^\"]*)\"(.*)/i",$row['substitute'],$match);
    $normalfont=htmlspecialchars($match[1]);
    $normalfontsize=htmlspecialchars($match[2]);
    $normalfonttags=htmlspecialchars(trim($match[3]));
    break;
   case "{imageback}":
    preg_match("/background=\"([^\"]*)\"/i",$row['substitute'],$match);
    $imageback=htmlspecialchars($match[1]);
    break;
   case "{imagelogo}":
    preg_match("/<img src=\"([^\"]*)\"/i",$row['substitute'],$match);
    $imagelogo=htmlspecialchars($match[1]);
    break;
   case "{cssfile}":
    preg_match("/href=\"([^\"]*)\">/i",$row['substitute'],$match);
    $cssfile=htmlspecialchars($match[1]);
    break; 
   case "{tableoutbordercolor}":
    $tableoutbordercolor=htmlspecialchars($row['substitute']);
    break;
   case "{tableinbordercolor}":
    $tableinbordercolor=htmlspecialchars($row['substitute']);
    break;
   case "{tabletitlecolor}":
    $tabletitlecolor=htmlspecialchars($row['substitute']);
    break;
   case "{tablecolora}":
    $tablecolora=htmlspecialchars($row['substitute']);
    break;
   case "{tablecolorb}":
    $tablecolorb=htmlspecialchars($row['substitute']);
    break;
   case "{fontcolorsecond}":
    $fontcolorsecond=htmlspecialchars($row['substitute']);
    break;
   case "{fontcolorthird}":
    $fontcolorthird=htmlspecialchars($row['substitute']);
    break;
   case "{tablecatcolor}":
    $tablecatcolor=htmlspecialchars($row['substitute']);
    break;
   case "{tableinwidth}":
    $tableinwidth=htmlspecialchars($row['substitute']);
    break;
   case "{tableoutwidth}":
    $tableoutwidth=htmlspecialchars($row['substitute']);
    break;
   case "{timecolor}":
    $timecolor=htmlspecialchars($row['substitute']);
    break;
   case "{imagefolder}":
    $imagefolder=htmlspecialchars($row['substitute']);
    break;
   case "<hilight>":
    $hilightstart=htmlspecialchars($row['substitute']);
    break;
   case "</hilight>":
    $hilightend=htmlspecialchars($row['substitute']);
    break;
   case "{inposttablecolor}":
    $inposttablecolor=htmlspecialchars($row['substitute']);
    break;
   case "{prefixcolor}":
    $prefixcolor=htmlspecialchars($row['substitute']);
    break;
   case "{mainbgcolor}":
    $mainbgcolor=htmlspecialchars($row['substitute']);
    break;
   case "{css}":
    $css=htmlspecialchars($row['substitute']);
    break;
   case "{!DOCTYPE}":
    $doctype=htmlspecialchars($row['substitute']);
    break;
  }	
 }
 
 eval("print(\"".gettemplate("designpack_edit")."\");");
}

if($action=="del") {
 $subvariablepackid=intval($_REQUEST['subvariablepackid']);
 if(isset($_POST['send'])) {
  $db->unbuffered_query("DELETE FROM bb".$n."_subvariablepacks WHERE subvariablepackid='$subvariablepackid'",1);	
  $db->unbuffered_query("DELETE FROM bb".$n."_subvariables WHERE subvariablepackid='$subvariablepackid'",1);	
  $db->unbuffered_query("UPDATE bb".$n."_styles SET subvariablepackid=0 WHERE subvariablepackid='$subvariablepackid'",1);	
  header("Location: designpack.php?action=view&sid=$session[hash]");
  exit();
 }	
	
 $dp = $db->query_first("SELECT subvariablepackname FROM bb".$n."_subvariablepacks WHERE subvariablepackid='$subvariablepackid'");
 eval("print(\"".gettemplate("designpack_del")."\");");
}
?>