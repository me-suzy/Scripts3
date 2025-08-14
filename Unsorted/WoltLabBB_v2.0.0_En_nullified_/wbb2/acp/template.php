<?php
require ("./global.php");
isAdmin();
@set_time_limit(0);

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action=="view") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=$_REQUEST['templatepackid'];
 else $templatepackid=0;
 
 $count=0;
 $result = $db->query("SELECT templateid, templatename FROM bb".$n."_templates WHERE templatepackid = '$templatepackid' ORDER BY templatename ASC");
 while($row=$db->fetch_array($result)) {
  $template_options .= makeoption($row['templateid'],$row['templatename'],"",0)."\n";
  $count++;
 }
 
 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 
 if(isset($_COOKIE['editor'])) $checked[$_COOKIE['editor']]=" checked";
 else $checked['normal']=" checked";
 
 eval("print(\"".gettemplate("template_view")."\");"); 
}

if($action=="add") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=intval($_REQUEST['templatepackid']);
 else $templatepackid=0;
 
 if(isset($_POST['send'])) {
  $template=$_POST['template'];
  $templatename=trim($_POST['templatename']);
  
  if($_POST['editor']=="deluxe") $template=preg_replace("/<([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))>/i", "\\1", $template);
  if(!$templatename) eval("\$error = acp_error_frame(\"".gettemplate("error_emptyfields")."\");");
  else {
   $result=$db->query_first("SELECT COUNT(*) FROM bb".$n."_templates WHERE templatepackid='$templatepackid' AND templatename='".addslashes($templatename)."'");	
   if($result[0]) eval("\$error = acp_error_frame(\"".gettemplate("error_templatealreadyexists")."\");");	
   else {
    $db->query("INSERT INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templatename)."','".addslashes($template)."')");
    $templateid=$db->insert_id();
    header("Location: template.php?action=edit&templateid=$templateid&sid=$session[hash]");
    exit();
   }
  }	
 }
 
 	
 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 
 if(isset($_GET['editor'])) $editor=$_GET['editor'];
 elseif(isset($_COOKIE['editor'])) $editor=$_COOKIE['editor'];
 else $editor="normal";
 
 if($template) $template=htmlspecialchars($template);
 if($editor=="deluxe") {
  if($template) $template = preg_replace("/([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))/i", "&lt;\\1&gt;", $template);
  eval("print(\"".gettemplate("template_editor_deluxe")."\");"); 	
 }
 else {
  eval("print(\"".gettemplate("template_editor_normal")."\");"); 	
 }		
}

if($action=="edit") {
 if(isset($_REQUEST['templateid'])) $templateid=intval($_REQUEST['templateid']);
 else $templateid=0;
 $result=$db->query_first("SELECT * FROM bb".$n."_templates WHERE templateid='$templateid'");
  
 if($_POST['send']=="send") {
  $template=$_POST['template'];
  $templatename=trim($_POST['templatename']);
  $templatepackid=intval($_POST['templatepackid']);
  
  if($_POST['editor']=="deluxe") $template=preg_replace("/<([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))>/i", "\\1", $template);
   
  if(!$templatename) eval("\$error = acp_error_frame(\"".gettemplate("error_emptyfields")."\");");
  else {
   if($templatepackid!=$result['templatepackid'] || $templatename!=$result['templatename']) $result=$db->query_first("SELECT COUNT(*) FROM bb".$n."_templates WHERE templatepackid='$templatepackid' AND templatename='".addslashes($templatename)."'");	
   else $result[0]=0;
   if($result[0]) eval("\$error = acp_error_frame(\"".gettemplate("error_templatealreadyexists")."\");");
   else $db->query("UPDATE bb".$n."_templates SET templatepackid='$templatepackid', templatename='".addslashes($templatename)."', template='".addslashes($template)."' WHERE templateid='$templateid'");
  }	
 }
 else {
  $template=$result['template'];
  $templatename=$result['templatename'];
  $templatepackid=$result['templatepackid'];
 }
 
 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 
 $template=htmlspecialchars($template);  
 if(isset($_GET['editor'])) $editor=$_GET['editor'];
 elseif(isset($_POST['editor'])) $editor=$_POST['editor'];
 elseif(isset($_COOKIE['editor'])) $editor=$_COOKIE['editor'];
 else $editor="normal";
  
 if($editor=="deluxe") {
  $template = preg_replace("/([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))/i", "&lt;\\1&gt;", $template);
  eval("print(\"".gettemplate("template_editor_deluxe")."\");"); 	
 }
 else {
  eval("print(\"".gettemplate("template_editor_normal")."\");"); 	
 }	
}

if($action=="copy") {
 if(isset($_REQUEST['templateid'])) $templateid=intval($_REQUEST['templateid']);
 else $templateid=0;
 
 if(isset($_POST['send'])) {
  $template=$_POST['template'];
  $templatename=trim($_POST['templatename']);
  $templatepackid=intval($_POST['templatepackid']);
  
  if($_POST['editor']=="deluxe") $template=preg_replace("/<([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))>/i", "\\1", $template);
  if(!$templatename) eval("\$error = acp_error_frame(\"".gettemplate("error_emptyfields")."\");");
  else {
   $result=$db->query_first("SELECT COUNT(*) FROM bb".$n."_templates WHERE templatepackid='$templatepackid' AND templatename='".addslashes($templatename)."'");	
   if($result[0]) eval("\$error = acp_error_frame(\"".gettemplate("error_templatealreadyexists")."\");");	
   else {
    $db->query("INSERT INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templatename)."','".addslashes($template)."')");
    $templateid=$db->insert_id();
    header("Location: template.php?action=edit&templateid=$templateid&sid=$session[hash]");
    exit();
   }
  }	
 }
 else {
  $result=$db->query_first("SELECT * FROM bb".$n."_templates WHERE templateid='$templateid'");
  $template=$result[template];
  $templatename=$result[templatename];
  $templatepackid=$result[templatepackid];
  $template=htmlspecialchars($template);  
 }
 	
 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 	
 if(isset($_GET['editor'])) $editor=$_GET['editor'];
 elseif(isset($_COOKIE['editor'])) $editor=$_COOKIE['editor'];
 else $editor="normal";
 	
 if($editor=="deluxe") {
  $template = preg_replace("/([$]{1,2}([0-9a-z_\-\[\]]+|(\[\$)))/i", "&lt;\\1&gt;", $template);
  eval("print(\"".gettemplate("template_editor_deluxe")."\");"); 	
 }
 else {
  eval("print(\"".gettemplate("template_editor_normal")."\");"); 	
 }			
}

if($action=="del") {
 if(isset($_REQUEST['templateid'])) $templateid=intval($_REQUEST['templateid']);
 else $templateid=0;
  
 $template=$db->query_first("SELECT templatename, templatepackid FROM bb".$n."_templates WHERE templateid='$templateid'");
 
 if(isset($_POST['send'])) {
  $db->query("DELETE FROM bb".$n."_templates WHERE templateid='$templateid'");	
  header("Location: template.php?action=view&templatepackid=$template[templatepackid]&sid=$session[hash]");
  exit();	
 }	
	
 eval("print(\"".gettemplate("template_del")."\");");
}

if($action=="search") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=$_REQUEST['templatepackid'];
 else $templatepackid="*";
 
 if($templatepackid!="*") $templateid=intval($templatepackid);
  
 if(isset($_POST['send'])) {
  if(!$_POST['search']) eval("acp_error(\"".gettemplate("error_noresult")."\");");
  if(isset($_POST['dosearch'])) {
   $where_templateid="";   
   if($templatepackid!="*") {
    if(count($_POST['templateid'])) {
     $templateids=implode(",",$_POST['templateid']);
     $where_templateid=" t.templateid IN (".$templateids.") AND";
    }
    else $where_templateid=" t.templateid IN (0) AND"; 
   } 
   $result=$db->query("SELECT t.templateid, t.templatename, t.templatepackid, p.templatepackname FROM bb".$n."_templates t LEFT JOIN bb".$n."_templatepacks p USING(templatepackid) WHERE$where_templateid t.template LIKE '%".addslashes($_POST['search'])."%' ORDER BY p.templatepackname ASC, t.templatename ASC");
   if(!$db->num_rows($result)) eval("acp_error(\"".gettemplate("error_noresult")."\");");
   $i=0;
   while($row=$db->fetch_array($result)) {
    $rowclass=getone($i++,"firstrow","secondrow");
    if(!$row[templatepackid]) $defaultname="---";
    else $defaultname="";
    eval("\$resultbit .= \"".gettemplate("template_search_resultbit")."\";");
   }
   eval("print(\"".gettemplate("template_search_result")."\");");
   exit(); 
  }
  elseif(isset($_POST['doreplace'])) {
   $where_templateid="";   
   if($templatepackid!="*") {
    if(count($_POST['templateid'])) {
     $templateids=implode(",",$_POST['templateid']);
     $where_templateid=" templateid IN (".$templateids.") AND";
    }
    else $where_templateid=" templateid IN (0) AND"; 
   } 
   $db->query("UPDATE bb".$n."_templates SET template=REPLACE(template,'".addslashes($_POST['search'])."','".addslashes($_POST['replace'])."') WHERE$where_templateid template LIKE '%".addslashes($_POST['search'])."%'");
   $count=$db->affected_rows();
  
   eval("print(\"".gettemplate("template_replace_result")."\");");
   exit();
  }
 }	

 if($templatepackid!="*") {
  if($templatepackid==0) $selected=" selected";
  $count=0;
  $result = $db->query("SELECT templateid, templatename FROM bb".$n."_templates WHERE templatepackid = '$templatepackid' ORDER BY templatename ASC");
  while($row=$db->fetch_array($result)) {
   $template_options .= makeoption($row[templateid],$row[templatename],"",0)."\r\n";
   $count++;
  }
  eval("\$select = \"".gettemplate("template_search_select")."\";");
 }

 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 
 eval("print(\"".gettemplate("template_search")."\");");
}

if($action=="import/export") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=$_REQUEST['templatepackid'];
 else $templatepackid="*";
 
 if($templatepackid!="*") {
  if($templatepackid==0) $selected=" selected";
  $count=0;
  $result = $db->query("SELECT templateid, templatename FROM bb".$n."_templates WHERE templatepackid = '$templatepackid' ORDER BY templatename ASC");
  while($row=$db->fetch_array($result)) {
   $template_options .= makeoption($row[templateid],$row[templatename],"",0)."\r\n";
   $count++;
  }
  eval("\$select = \"".gettemplate("template_export_select")."\";");
 }

 $result = $db->query("SELECT templatepackid, templatepackname FROM bb".$n."_templatepacks ORDER BY templatepackname ASC");
 while($row=$db->fetch_array($result)) $templatepack_options .= makeoption($row[templatepackid],"Templatepack: ".$row[templatepackname],$templatepackid,1)."\r\n";
 
 $fileimportAction="fileimport";
 
 $selectbit="";
 if(isset($_REQUEST['templatefolder'])) {
  $templatefolder="../".$_REQUEST['templatefolder'];
  if(is_dir($templatefolder)) {
   $handle=@opendir($templatefolder);
   while($file=readdir($handle)) {
    if($file==".." || $file=="." || is_dir("$templatefolder/$file")) continue; 
     $filesize=formatFilesize(filesize("$templatefolder/$file"));
     $changedate=formatdate($wbbuserdata['dateformat']." ".$wbbuserdata['timeformat'],filemtime("$templatefolder/$file"));
     $perms=(substr(sprintf("%o",fileperms("$templatefolder/$file")),3));
     eval("\$selectbit .= \"".gettemplate("template_import_selectbit")."\";");
   }
   
   $fileimportAction="fileimport2";
   eval("\$importSelect = \"".gettemplate("template_import_select")."\";");
  }	
 	
 }
 
 eval("print(\"".gettemplate("template_import_export")."\");");	
}

if($action=="export") {
 if(isset($_POST['templateid'])) {
  if(count($_POST['templateid'])) {
   if($_POST['templatepackid']==0) $templatefolder="../templates";
   else {
    list($templatefolder)=$db->query_first("SELECT templatefolder FROM bb".$n."_templatepacks WHERE templatepackid='$_POST[templatepackid]'");
    $templatefolder="../$templatefolder";
   }
   if(!is_dir($templatefolder)) mkdir("$templatefolder",0777);
 
   $result=$db->query("SELECT templatename, template FROM bb".$n."_templates WHERE templateid IN ('".implode("','",$_POST['templateid'])."')");
   while($row=$db->fetch_array($result)) {
    if(!$row['templatename']) continue;
    $fp=fopen("$templatefolder/$row[templatename].tpl","w+");
    fwrite($fp,$row['template']); 	
    fclose($fp);	
   }
  }	
 }
 else {
  if($_POST['templatepackid']=="*") {
   if(!is_dir("../templates")) mkdir("../templates",0777);
   $folder[0]="../templates";
   $result=$db->query("SELECT templatepackid, templatefolder FROM bb".$n."_templatepacks");	
   while($row=$db->fetch_array($result)) {
    if(!is_dir("../$row[templatefolder]")) mkdir("../$row[templatefolder]",0777);
    $folder[$row['templatepackid']]="../$row[templatefolder]";	
   }
   
   $result=$db->query("SELECT templatepackid, templatename, template FROM bb".$n."_templates");
   while($row=$db->fetch_array($result)) {
    if(!$row['templatename']) continue;
    $fp=fopen($folder[$row['templatepackid']]."/$row[templatename].tpl","w+");
    fwrite($fp,$row['template']); 	
    fclose($fp);	
   }
  }
  else {
   if($_POST['templatepackid']==0) $templatefolder="../templates";
   else {
    list($templatefolder)=$db->query_first("SELECT templatefolder FROM bb".$n."_templatepacks WHERE templatepackid='$_POST[templatepackid]'");
    $templatefolder="../$templatefolder";
   }
    
   if(!is_dir($templatefolder)) mkdir("$templatefolder",0777);
  
   $result=$db->query("SELECT templatename, template FROM bb".$n."_templates WHERE templatepackid='$_POST[templatepackid]'");
   while($row=$db->fetch_array($result)) {
    $fp=fopen("$templatefolder/$row[templatename].tpl","w+");
    fwrite($fp,$row['template']); 	
    fclose($fp);	
   }
  }
 }
 eval("print(\"".gettemplate("template_export_done")."\");");
}

if($action=="fileimport2") {
 $files=$_POST['files'];	
 $templatepackid=intval($_POST['templatepackid']);
	
 if(is_array($files) && count($files)) {
  while(list($key,$val)=each($files)) {
   if(!file_exists($val)) continue;
   $templatename=basename($val);
   $templatename=substr($templatename,0,-1*strlen(strrchr($templatename,".")));
   $template = implode("",file($val));
   $db->query("REPLACE INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templatename)."','".addslashes($template)."')");
  }  
  eval("print(\"".gettemplate("template_import_done")."\");");
 }
 else eval("acp_error(\"".gettemplate("error_importerror")."\");");	
}

if($action=="fileimport") {
 $templatefolder="../".$_POST['templatefolder'];	
 $templatepackid=intval($_POST['templatepackid']);
	
 if($handle=@opendir($templatefolder)) {
  while($file=readdir($handle)) {
   if($file==".." || $file==".") continue; 
 
   $templatename=substr($file,0,-1*strlen(strrchr($file,".")));
   $template = implode("",file($templatefolder."/".$file));
   $db->query("REPLACE INTO bb".$n."_templates (templateid,templatepackid,templatename,template) VALUES (NULL,'$templatepackid','".addslashes($templatename)."','".addslashes($template)."')");
  }
  eval("print(\"".gettemplate("template_import_done")."\");");
 }
 else eval("acp_error(\"".gettemplate("error_importerror")."\");");	
}

if($action=="viewpack") {
 $result=$db->query("SELECT * FROM bb".$n."_templatepacks ORDER BY templatepackname");
 $count=0;
 $templatepackbit="";
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  eval ("\$templatepackbit .= \"".gettemplate("templatepack_viewbit")."\";");
 }
 eval("print(\"".gettemplate("templatepack_view")."\");");
}

if($action=="addpack") {
 if(isset($_POST['send'])) {
  $db->unbuffered_query("INSERT INTO bb".$n."_templatepacks (templatepackid,templatepackname,templatefolder) VALUES (NULL,'".addslashes(htmlspecialchars(trim($_POST['templatepackname'])))."','".addslashes($_POST['templatefolder'])."')",1);
 	
  header("Location: template.php?action=viewpack&sid=$session[hash]");
  exit();	
 }	
 eval("print(\"".gettemplate("templatepack_add")."\");");	
}

if($action=="delpack") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=intval($_REQUEST['templatepackid']);
 else $templatepackid=0;
 
 if(isset($_POST['send'])) {
  $db->unbuffered_query("DELETE FROM bb".$n."_templatepacks WHERE templatepackid='$templatepackid'",1);
  $db->unbuffered_query("DELETE FROM bb".$n."_templates WHERE templatepackid='$templatepackid'",1);
  $db->unbuffered_query("UPDATE bb".$n."_styles SET templatepackid=0 WHERE templatepackid='$templatepackid'",1);
  	
  header("Location: template.php?action=viewpack&sid=$session[hash]");
  exit();	
 }	
 
 $templatepack=$db->query_first("SELECT * FROM bb".$n."_templatepacks WHERE templatepackid='$templatepackid'");
 eval("print(\"".gettemplate("templatepack_del")."\");");	
}

if($action=="editpack") {
 if(isset($_REQUEST['templatepackid'])) $templatepackid=intval($_REQUEST['templatepackid']);
 else $templatepackid=0;
 
 if(isset($_POST['send'])) {
  $db->unbuffered_query("UPDATE bb".$n."_templatepacks SET templatepackname='$templatepackname', templatefolder='$templatefolder' WHERE templatepackid='$templatepackid'",1);
 	
  header("Location: template.php?action=viewpack&sid=$session[hash]");
  exit();	
 }	
 
 $templatepack=$db->query_first("SELECT * FROM bb".$n."_templatepacks WHERE templatepackid='$templatepackid'");
 eval("print(\"".gettemplate("templatepack_edit")."\");");	
}
?>
