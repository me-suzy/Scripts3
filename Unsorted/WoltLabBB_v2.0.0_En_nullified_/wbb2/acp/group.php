<? 
require ("global.php");
isAdmin();

function set_hilight_ids() {
 global $db, $n;
 
 $modids = "";
 $smodids = "";
 $adminids = "";
 
 $result=$db->query("SELECT groupid FROM bb".$n."_groups WHERE canuseacp=1");
 while($row=$db->fetch_array($result)) {
  if($adminids) $adminids.=",".$row['groupid'];
  else $adminids=$row['groupid'];
 }
 
 $result=$db->query("SELECT groupid FROM bb".$n."_groups WHERE issupermod=1 AND canuseacp=0");
 while($row=$db->fetch_array($result)) {
  if($smodids) $smodids.=",".$row['groupid'];
  else $smodids=$row['groupid'];
 }
 
 $result=$db->query("SELECT groupid FROM bb".$n."_groups WHERE ismod=1 AND issupermod=0 AND canuseacp=0");
 while($row=$db->fetch_array($result)) {
  if($modids) $modids.=",".$row['groupid'];
  else $modids=$row['groupid'];
 }
 	
 $db->query("UPDATE bb".$n."_options SET value='$adminids' WHERE varname='adminids'");
 $db->query("UPDATE bb".$n."_options SET value='$smodids' WHERE varname='smodids'");
 $db->query("UPDATE bb".$n."_options SET value='$modids' WHERE varname='modids'");
 
 require("lib/class_options.php");
 $option=new options("lib");
 $option->write();
}

if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
else $action="view";

if($action == "view") {
 $result=$db->query("SELECT COUNT(u.userid) AS count, g.groupid, g.title FROM bb".$n."_groups g LEFT JOIN bb".$n."_users u USING (groupid) GROUP BY groupid ORDER BY title ASC");
 $count=0;
 $group_viewbit="";
 while($row=$db->fetch_array($result)) {
  $rowclass=getone($count++,"firstrow","secondrow");
  eval ("\$group_viewbit .= \"".gettemplate("group_viewbit")."\";");
 }
 eval("print(\"".gettemplate("group_view")."\");");
}

function makeboardlist($boardid,$x=0) {
 global $boardcache, $session, $maxcolspan, $permissioncache;

 if(!isset($boardcache[$boardid])) return;
 
 while (list($key1,$val1) = each($boardcache[$boardid])) {
  while(list($key2,$boards) = each($val1)) {
   $colspan=$maxcolspan-$x;
   $temp=$maxcolspan-($maxcolspan-$x);
   if($temp) $tds=str_repeat("<td class=\"secondrow\">&nbsp;&nbsp;</td>",$temp);
   else $tds="";
   
   if($permissioncache[$boards['boardid']]['boardpermission']==1) $checked1=" checked";
   else $checked1="";
   if($permissioncache[$boards['boardid']]['startpermission']==1) $checked2=" checked";
   else $checked2="";
   if($permissioncache[$boards['boardid']]['replypermission']==1) $checked3=" checked";
   else $checked3="";
   
   
   eval ("\$out .= \"".gettemplate("group_rightsbit")."\";");
   $out .= makeboardlist($boards[boardid],$x+1);
  } 
 } 
 unset($boardcache[$boardid]);
 return $out;
}

if($action == "rights") {
 $groupid=intval($_REQUEST['groupid']);
 $group = $db->query_first("SELECT groupid, title FROM bb".$n."_groups WHERE groupid='$groupid'");
 if(!$group['groupid']) {
  header("Location: group.php?action=view&sid=$session[hash]");
  exit();
 }

 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=$val;
   
  $result=$db->query("SELECT boardid FROM bb".$n."_boards");
  while($row=$db->fetch_array($result)) $db->query("REPLACE INTO bb".$n."_permissions (boardid,groupid,boardpermission,startpermission,replypermission) VALUES ('$row[boardid]','$groupid','".$boardpermission[$row['boardid']]."','".$startpermission[$row['boardid']]."','".$replypermission[$row['boardid']]."')");
  header("Location: group.php?action=view&sid=$session[hash]");
  exit();
 }
 
 $result = $db->query("SELECT * FROM bb".$n."_permissions WHERE groupid = '$groupid'");
 while ($row = $db->fetch_array($result)) $permissioncache[$row['boardid']] = $row;
 
 $maxcolspan=0;
 $result = $db->query("SELECT boardid, parentid, boardorder, title, parentlist FROM bb".$n."_boards ORDER by parentid ASC, boardorder ASC");
 while ($row = $db->fetch_array($result)) {
  $temp=count(explode(",",$row['parentlist']));
  if($temp>$maxcolspan) $maxcolspan=$temp;
  $boardcache[$row['parentid']][$row['boardorder']][$row['boardid']] = $row;	
 }
 $boardlist = makeboardlist(0);
 
 $maxcolspan2=$maxcolspan+3;
 eval("print(\"".gettemplate("group_rights")."\");");
}

if($action == "default") {
 if(isset($_POST['send'])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=trim($val);
  
  if($default1==$default2) eval("acp_error(\"".gettemplate("error_default2groups")."\");");
  $db->query("UPDATE bb".$n."_groups SET default_group='0'");
  $db->query("UPDATE bb".$n."_groups SET default_group='1' WHERE groupid='$default1'");
  $db->query("UPDATE bb".$n."_groups SET default_group='2' WHERE groupid='$default2'");
 }
 
 $result=$db->query("SELECT groupid, default_group FROM bb".$n."_groups WHERE default_group > 0");
 while($row=$db->fetch_array($result)) $default[$row['default_group']]=$row['groupid'];
 
 $result=$db->query("SELECT groupid, title FROM bb".$n."_groups ORDER BY title ASC");
 while($row=$db->fetch_array($result)) {
  $options1.=makeoption($row['groupid'],$row['title'],$default[1],1);
  $options2.=makeoption($row['groupid'],$row['title'],$default[2],1);
 }
 
 eval("print(\"".gettemplate("group_default")."\");");
}

if($action == "del") {
 $groupid=intval($_REQUEST['groupid']);
 
 $group = $db->query_first("SELECT groupid, title, default_group FROM bb".$n."_groups WHERE groupid='$groupid'");
 if(!$group[groupid]) {
  header("Location: group.php?action=view&sid=$session[hash]");
  exit();
 }
 
 if($group['default_group']!=0) eval("acp_error(\"".gettemplate("error_isdefaultgroup")."\");");
 if($group['groupid']==$wbbuserdata[groupid]) eval("acp_error(\"".gettemplate("error_isyourgroup")."\");");
 
 if(isset($_POST['send'])) {
  list($newgroupid)=$db->query_first("SELECT groupid FROM bb".$n."_groups WHERE default_group='2'");
  $db->query("UPDATE bb".$n."_users SET groupid='$newgroupid' WHERE groupid='$groupid'");
  $db->query("DELETE FROM bb".$n."_groups WHERE groupid='$groupid'");
  $db->query("UPDATE bb".$n."_avatars SET groupid=0 WHERE groupid='$groupid'");
  $db->query("UPDATE bb".$n."_events SET groupid=0 WHERE groupid='$groupid'");
  $db->query("DELETE FROM bb".$n."_permissions WHERE groupid='$groupid'");
  $db->query("DELETE FROM bb".$n."_ranks WHERE groupid='$groupid'");
  set_hilight_ids();
  header("Location: group.php?action=view&sid=$session[hash]");
  exit();
 }
 eval("print(\"".gettemplate("group_del")."\");");
}

if($action == "add") {
 if(isset($_POST["send"])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=trim($val);
  
  $allowedavatarextensions = preg_replace("/\s*\n\s*/","\n",trim($allowedavatarextensions));
  $allowedattachmentextensions = preg_replace("/\s*\n\s*/","\n",trim($allowedattachmentextensions));
  
  $db->query("INSERT INTO bb".$n."_groups (groupid,title,canviewboard,canviewoffboard,canusesearch,canusepms,canstarttopic,canreplyowntopic,canreplytopic,canpostwithoutmoderation,caneditownpost,candelownpost,cancloseowntopic,candelowntopic,caneditowntopic,canpostpoll,canvotepoll,canuseavatar,canuploadavatar,canuploadattachments,canratethread,canviewmblist,appendeditnote,avoidfc,ismod,issupermod,canuseacp,maxpostimage,maxsigimage,maxsiglength,allowedavatarextensions,maxavatarwidth,maxavatarheight,maxavatarsize,allowedattachmentextensions,maxattachmentsize,maxusertextlength,candownloadattachments,canviewprofile,canviewcalender,canprivateevent,canpublicevent,canrateusers)
  VALUES (NULL,'".addslashes($title)."','$canviewboard','$canviewoffboard','$canusesearch','$canusepms','$canstarttopic','$canreplyowntopic','$canreplytopic','$canpostwithoutmoderation','$caneditownpost','$candelownpost','$cancloseowntopic','$candelowntopic','$caneditowntopic','$canpostpoll','$canvotepoll','$canuseavatar','$canuploadavatar','$canuploadattachments','$canratethread','$canviewmblist','$appendeditnote','$avoidfc','$ismod','$issupermod','$canuseacp','".intval($maxpostimage)."','".intval($maxsigimage)."','".intval($maxsiglength)."','".addslashes($allowedavatarextensions)."','".intval($maxavatarwidth)."','".intval($maxavatarheight)."','".intval($maxavatarsize)."','".addslashes($allowedattachmentextensions)."','".intval($maxattachmentsize)."','".intval($maxusertextlength)."','$candownloadattachments','$canviewprofile','$canviewcalender','$canprivateevent','$canpublicevent','$canrateusers')");
  set_hilight_ids();
  header("Location: group.php?action=view&sid=$session[hash]"); 
  exit(); 
 }
 
 eval("print(\"".gettemplate("group_add")."\");");
}

if($action == "edit") {
 $groupid=intval($_REQUEST['groupid']);
 $group = $db->query_first("SELECT * FROM bb".$n."_groups WHERE groupid='$groupid'");
 if(!$group['groupid']) {
  header("Location: group.php?action=view&sid=$session[hash]");
  exit();
 }
 
 if(isset($_POST["send"])) {
  reset($_POST);
  while(list($key,$val)=each($_POST)) $$key=trim($val);
  
  $allowedavatarextensions = preg_replace("/\s*\n\s*/","\n",trim($allowedavatarextensions));
  $allowedattachmentextensions = preg_replace("/\s*\n\s*/","\n",trim($allowedattachmentextensions));
  
  $db->query("UPDATE bb".$n."_groups SET title='".addslashes($title)."',canviewboard='$canviewboard',canviewoffboard='$canviewoffboard',canusesearch='$canusesearch',canusepms='$canusepms',canstarttopic='$canstarttopic',canreplyowntopic='$canreplyowntopic',canreplytopic='$canreplytopic',canpostwithoutmoderation='$canpostwithoutmoderation',caneditownpost='$caneditownpost',candelownpost='$candelownpost',cancloseowntopic='$cancloseowntopic',candelowntopic='$candelowntopic',caneditowntopic='$caneditowntopic',canpostpoll='$canpostpoll',canvotepoll='$canvotepoll',canuseavatar='$canuseavatar',canuploadavatar='$canuploadavatar',canuploadattachments='$canuploadattachments',canratethread='$canratethread',appendeditnote='$appendeditnote',avoidfc='$avoidfc',ismod='$ismod',issupermod='$issupermod',canuseacp='$canuseacp',maxpostimage='".intval($maxpostimage)."',maxsigimage='".intval($maxsigimage)."',maxsiglength='".intval($maxsiglength)."',allowedavatarextensions='".addslashes($allowedavatarextensions)."',maxavatarwidth='".intval($maxavatarwidth)."',maxavatarheight='".intval($maxavatarheight)."',maxavatarsize='".intval($maxavatarsize)."',allowedattachmentextensions='".addslashes($allowedattachmentextensions)."',maxattachmentsize='".intval($maxattachmentsize)."',maxusertextlength='".intval($maxusertextlength)."', candownloadattachments='$candownloadattachments', canviewprofile='$canviewprofile', canviewcalender='$canviewcalender', canprivateevent='$canprivateevent', canpublicevent='$canpublicevent', canrateusers='$canrateusers', canviewmblist='$canviewmblist' WHERE groupid='$groupid'");
  set_hilight_ids();
  header("Location: group.php?action=view&sid=$session[hash]"); 
  exit(); 
 }
 
 $sel_canviewboard[$group[canviewboard]]=" selected";
 $sel_canviewoffboard[$group[canviewoffboard]]=" selected";
 $sel_canusesearch[$group[canusesearch]]=" selected";
 $sel_canusepms[$group[canusepms]]=" selected";
 $sel_canvotepoll[$group[canvotepoll]]=" selected";
 $sel_canratethread[$group[canratethread]]=" selected";
 $sel_canuseavatar[$group[canuseavatar]]=" selected";
 $sel_canuploadavatar[$group[canuploadavatar]]=" selected";
 $sel_canstarttopic[$group[canstarttopic]]=" selected";
 $sel_canreplyowntopic[$group[canreplyowntopic]]=" selected";
 $sel_canreplytopic[$group[canreplytopic]]=" selected";
 $sel_canpostwithoutmoderation[$group[canpostwithoutmoderation]]=" selected";
 $sel_caneditownpost[$group[caneditownpost]]=" selected";
 $sel_candelownpost[$group[candelownpost]]=" selected";
 $sel_cancloseowntopic[$group[cancloseowntopic]]=" selected";
 $sel_candelowntopic[$group[candelowntopic]]=" selected";
 $sel_caneditowntopic[$group[caneditowntopic]]=" selected";
 $sel_canpostpoll[$group[canpostpoll]]=" selected";
 $sel_appendeditnote[$group[appendeditnote]]=" selected";
 $sel_avoidfc[$group[avoidfc]]=" selected";
 $sel_canuploadattachments[$group[canuploadattachments]]=" selected";
 $sel_ismod[$group[ismod]]=" selected";
 $sel_issupermod[$group[issupermod]]=" selected";
 $sel_canuseacp[$group[canuseacp]]=" selected";
 $sel_candownloadattachments[$group['candownloadattachments']]=" selected";
 $sel_canviewprofile[$group['canviewprofile']]=" selected";
 $sel_canviewcalender[$group['canviewcalender']]=" selected";
 $sel_canprivateevent[$group['canprivateevent']]=" selected";
 $sel_canpublicevent[$group['canpublicevent']]=" selected";
 $sel_canrateusers[$group['canrateusers']]=" selected";
 $sel_canviewmblist[$group['canviewmblist']]=" selected";
 
 eval("print(\"".gettemplate("group_edit")."\");");
}
?>