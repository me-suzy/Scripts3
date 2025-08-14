<?php
require ("global.php");
isAdmin();
@set_time_limit(1200);
$split="~~~";

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action=="view") {
 $result=$db->query("SELECT s.*, tp.templatepackname, sp.subvariablepackname
 FROM bb".$n."_styles s
 LEFT JOIN bb".$n."_templatepacks tp USING (templatepackid)
 LEFT JOIN bb".$n."_subvariablepacks sp ON (sp.subvariablepackid=s.subvariablepackid)
 ORDER BY s.stylename");
 $count=0;
 $stylebit="";
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  if(!$row['templatepackid']) $row['templatepackname']="---";
  if($row['default_style']==1) $star="*";
  else $star="";
  eval ("\$stylebit .= \"".gettemplate("style_viewbit")."\";");
 }	
	
 eval("print(\"".gettemplate("style_view")."\");");	
}

if($action=="default") {
 $styleid=intval($_REQUEST['styleid']);	
 $db->unbuffered_query("UPDATE bb".$n."_styles SET default_style=0 WHERE default_style=1",1);	
 $db->unbuffered_query("UPDATE bb".$n."_styles SET default_style=1 WHERE styleid='$styleid'",1);
 header("Location: style.php?action=view&sid=$session[hash]");
 exit();	
}

if($action=="add") {
 if(isset($_POST['send'])) {
  $subvariablepackid=intval($_POST['subvariablepackid']);	
  $templatepackid=intval($_POST['templatepackid']);	
  $stylename = trim($_POST['stylename']);
  $db->unbuffered_query("INSERT INTO bb".$n."_styles (styleid,stylename,templatepackid,subvariablepackid) VALUES (NULL,'".addslashes($stylename)."','$templatepackid','$subvariablepackid')",1);
  header("Location: style.php?action=view&sid=$session[hash]");
  exit();	
 }

 $subp_options="";
 $result=$db->query("SELECT * FROM bb".$n."_subvariablepacks ORDER BY subvariablepackname ASC");	
 while($row=$db->fetch_array($result)) $subp_options.=makeoption($row['subvariablepackid'],$row['subvariablepackname'],"",0);	

 $tplp_options="";
 $result=$db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");	
 while($row=$db->fetch_array($result)) $tplp_options.=makeoption($row['templatepackid'],$row['templatepackname'],"",0);	
	
 eval("print(\"".gettemplate("style_add")."\");");	
}

if($action=="del") {
 $styleid=intval($_REQUEST['styleid']);
 
 if(isset($_POST['send'])) {
  $db->unbuffered_query("UPDATE bb".$n."_users SET styleid = 0 WHERE styleid='$styleid'",1); 	
  $db->unbuffered_query("UPDATE bb".$n."_boards SET styleid = 0 WHERE styleid='$styleid'",1); 	
  $db->unbuffered_query("DELETE FROM bb".$n."_styles WHERE styleid='$styleid'",1); 	
  header("Location: style.php?action=view&sid=$session[hash]");
  exit();
 }

 $style=$db->query_first("SELECT stylename FROM bb".$n."_styles WHERE styleid='$styleid'");	
	
 eval("print(\"".gettemplate("style_del")."\");");	
}

if($action=="edit") {
 $styleid=intval($_REQUEST['styleid']);
 
 if(isset($_POST['send'])) {
  $subvariablepackid=intval($_POST['subvariablepackid']);	
  $templatepackid=intval($_POST['templatepackid']);	
  $stylename = trim($_POST['stylename']);
  $db->unbuffered_query("UPDATE bb".$n."_styles SET stylename='".addslashes($stylename)."', templatepackid='$templatepackid', subvariablepackid='$subvariablepackid' WHERE styleid='$styleid'",1);
  header("Location: style.php?action=view&sid=$session[hash]");
  exit();	
 }

 $style=$db->query_first("SELECT * FROM bb".$n."_styles WHERE styleid='$styleid'");	

 $subp_options="";
 $result=$db->query("SELECT * FROM bb".$n."_subvariablepacks ORDER BY subvariablepackname ASC");	
 while($row=$db->fetch_array($result)) $subp_options.=makeoption($row['subvariablepackid'],$row['subvariablepackname'],$style['subvariablepackid'],1);	

 $tplp_options="";
 $result=$db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");	
 while($row=$db->fetch_array($result)) $tplp_options.=makeoption($row['templatepackid'],$row['templatepackname'],$style['templatepackid'],1);	
	
 eval("print(\"".gettemplate("style_edit")."\");");	
}

if($action=="export") {
 $styleid=intval($_REQUEST['styleid']);	
  
 $style=$db->query_first("SELECT s.*, dp.*, tp.* FROM bb".$n."_styles s LEFT JOIN bb".$n."_subvariablepacks dp USING (subvariablepackid) LEFT JOIN bb".$n."_templatepacks tp ON (tp.templatepackid=s.templatepackid) WHERE styleid='$styleid'");	
 if(!$style['templatepackid']) $style['templatepackname']=-1;

 $export=$style['stylename'].$split.$style['subvariablepackname'].$split.$style['templatepackname']."\n";
 
 $result = $db->query("SELECT variable, substitute FROM bb".$n."_subvariables WHERE subvariablepackid='$style[subvariablepackid]'");
 while($row=$db->fetch_array($result)) $export.=ifelse($row['variable'],$row['variable']," ").$split.ifelse($row['substitute'],$row['substitute']," ").$split;
 
 $result = $db->query("SELECT templatename, template FROM bb".$n."_templates WHERE templatepackid='$style[templatepackid]'");
 while($row=$db->fetch_array($result)) $export.=$split.ifelse($row['templatename'],$row['templatename']," ").$split.ifelse($row['template'],$row['template']," ");
 
 $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA') ? 'application/octetstream' : 'application/octet-stream';
 $content_disp = (USR_BROWSER_AGENT == 'IE') ? 'inline; ' : 'attachment; ';
 
 header('Content-Type: '.$mime_type);
 header('Content-disposition: '.$content_disp.'filename="bb'.$styleid.'.style"');
 header('Pragma: no-cache');
 header('Expires: 0');

 print($export);	
}

if($action=="import/export") {
 $style_options="";	
 $result=$db->query("SELECT styleid, stylename FROM bb".$n."_styles ORDER BY stylename ASC");
 while($row=$db->fetch_array($result)) $style_options.=makeoption($row['styleid'],$row['stylename'],"",0);

 eval("print(\"".gettemplate("style_import_export")."\");");	
}

if($action=="import") {
 $mode=$_POST['mode'];
 if($mode=="local") $stylefile=$_POST['stylefile'];
 else $stylefile=$_FILES['uploadfile']['tmp_name'];
 
 if(file_exists($stylefile)) {
  $file = file($stylefile);
  $firstrow = explode($split,array_shift($file));	
 
  $file=explode($split.$split,implode("",$file));
  $db->query("INSERT INTO bb".$n."_subvariablepacks (subvariablepackid,subvariablepackname) VALUES (NULL,'".addslashes($firstrow[1])."')");
  $subvariablepackid=$db->insert_id();
 
  $subvariables=explode($split,$file[0]);
  for($i=0;$i<count($subvariables)/2;$i++) $db->unbuffered_query("INSERT INTO bb".$n."_subvariables (subvariableid,subvariablepackid,variable,substitute) VALUES (NULL,'$subvariablepackid','".addslashes($subvariables[($i*2)])."','".addslashes($subvariables[($i*2+1)])."')",1);
 
  if($firstrow[2]==-1) $templatepackid=0;
  else {
   $db->query("INSERT INTO bb".$n."_templatepacks (templatepackid,templatepackname) VALUES (NULL,'".addslashes($firstrow[2])."')");
   $templatepackid=$db->insert_id();	
  }
  
  $templates=explode($split,$file[1]);
  for($i=0;$i<count($templates)/2;$i++) $db->unbuffered_query("REPLACE INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templates[($i*2)])."','".addslashes($templates[($i*2+1)])."')");
 
  $db->unbuffered_query("INSERT INTO bb".$n."_styles (styleid,stylename,templatepackid,subvariablepackid) VALUES (NULL,'".addslashes($firstrow[0])."','$templatepackid','$subvariablepackid') ",1);
 
  header("Location: style.php?action=view&sid=$session[hash]");
  exit();
 }
 else eval("acp_error(\"".gettemplate("error_fileerror")."\");");
}
?>