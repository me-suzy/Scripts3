<?php
class tpl {
 
 var $templates   = array();
 var $templatepackid = 0;
 var $subvariablepackid = 0;
 var $templatelist = "";
 
 /* constuctor */
 function tpl($templatepackid=1,$subvariablepackid=1) {
  $this->templatepackid = $templatepackid;
  $this->subvariablepackid = $subvariablepackid;
 }
 
 /* cache templates */
 function cache() {
  global $db, $n;
  $this->templatelist = str_replace(",", "','", $this->templatelist);
  $result = $db->query("SELECT templatename, template FROM bb".$n."_templates WHERE templatename IN ('$this->templatelist') AND templatepackid IN (0,".$this->templatepackid.") ORDER BY templatepackid ASC");
  while($row=$db->fetch_array($result)) $this->templates[$row['templatename']] = str_replace("\"", "\\\"", $row['template']);
  $db->free_result($result);
 }

 /* get template */
 function get($templatename,$nodevmode=0) {
  if(!isset($this->templates[$templatename])) {
   global $db, $n;
   $result = $db->query_first("SELECT template FROM bb".$n."_templates WHERE templatename = '$templatename' AND templatepackid IN (0,".$this->templatepackid.") ORDER BY templatepackid DESC");
   $this->templates[$templatename] = str_replace("\"","\\\"",$result['template']);
  }
  return $this->templates[$templatename];
 }
 
 /* print template */
 function output($template) {
  headers::send();
  $template = $this->replacevars($template);
  print($template);
 }
 
 /* replace vars */
 function replacevars($template) {
  global $db, $n;
  $result = $db->query("SELECT variable,substitute FROM bb".$n."_subvariables WHERE subvariablepackid = '".$this->subvariablepackid."'");
  while($row = $db->fetch_array($result)) if($row['variable']) $template = str_replace($row['variable'],$row['substitute'],$template);
  return $template;
 }
 
 /** makes templatelist for caching **/
 function getlist() {
  global $boardoptions, $wbbuserdata, $filename, $_REQUEST, $offline, $boardid, $board;
  
  $this->templatelist="header,footer,headinclude,error,error_falselink,access_error,phpinclude";
  if($wbbuserdata['userid']) {
   $this->templatelist.=",header_usercp,usercbar,access_error_user";
   if($wbbuserdata['receivepm']==1) $this->templatelist.=",header_pms";
   if($wbbuserdata['canuseacp']==1) $this->templatelist.=",header_acp";
   elseif($wbbuserdata['issupermod']==1) $this->templatelist.=",header_modcp";
  }
  else $this->templatelist.=",header_register,usercbar_guest,access_error_guest";
  
  if($wbbuserdata['pmpopup']==2) $this->templatelist.=",pmpopup_open";
  if($offline==1 && $wbbuserdata['canviewoffboard']==0) {
   $this->templatelist.=",offline";
   return "";
  }
  if(isset($boardid) && $board['password']!='') $this->templatelist.=",board_password,error_falsepassword";
  
  if($filename=="addreply.php") {
   $this->templatelist.=",addreply_threadclose,codephptag,codetag,navbar_board,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,addreply_complete_thread,addreply_postbit,newthread_attachment,addreply,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";	
   if($wbbuserdata['userid']) $this->templatelist.=",newthread_username";
   else $this->templatelist.=",newthread_username_input";
 
   if(isset($_REQUEST['postid'])) $this->templatelist.=",addreply_quote_topic,addreply_quote_message";	
   if(isset($_REQUEST['preview'])) $this->templatelist.=",newthread_preview";	

   if(isset($_REQUEST['send'])) $this->templatelist.=",newthread_error4,newthread_error1,newthread_error,redirect_waiting4moderation,mt_newpost_lastone,mt_newpost,ms_newpost";
  }
   
  if($filename=="attachment.php") {
   global $attachmentid;
   $this->templatelist.=",window_close,attachmentedit_give_parent,attachmentedit_error";
   if(isset($attachmentid)) $this->templatelist.=",attachmentedit_edit";
   else $this->templatelist.=",attachmentedit_add";
  }
  
  if($filename=="board.php") {
   global $board, $showuseronlineonboard, $showlastposttitle, $show_subboards, $showuseronlineinboard;
   
   $this->templatelist.=",boardjump,navbar_board,navbar_boardend";	
   if($board['isboard']==0) $this->templatelist.=",board_cat";	
   else {
    $this->templatelist.=",board_moderatorbit,board_moderators,board_newthread,board_nothreads,board,board_threadbit_rating,board_thread_moved,board_thread_important,board_thread_poll,board_threadbit_firstnew,board_threadbit_starter,board_threadbit_lastposter,board_threadbit_multipages_lastpage,board_threadbit_multipages,board_threadbit_attachments,board_threadbit,board_thread_announce,today,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";
    if($showuseronlineonboard==1 || $showuseronlineinboard==2) $this->templatelist.=",board_useronline,useronline_buddy,useronline_admin,useronline_smod,useronline_mod,index_useronline_invisible,index_useronline";
   }
   if($board['childlist']!="0") {
    $this->templatelist.=",board_thread_prefix,board_subboards,index_boarddescription,index_lastposter,index_lastposter_guest,index_lastpost,index_nolastpost,index_moderatorbit,index_moderatorbit,index_moderators,index_boardbit1,index_boardbit2,index_showcat,index_hidecat,index_catbit1,index_catbit2";
    if($showlastposttitle==1) $this->templatelist.=",index_lastpost_title_hide,index_lastpost_title_show,index_lastpost_title";
    if($showuseronlineinboard==1 || $showuseronlineinboard==2) $this->templatelist.=",index_board_useronline";
    if($show_subboards==1) $this->templatelist.=",index_subboard,index_subboardbit";	
   }
  }
  
  if($filename=="calender.php") {
   if(!isset($_REQUEST['action'])) $this->templatelist.=",calender_addpublicevent,calender_addprivateevent,calender_birthdays,calender_view,calender_week,calender_publicevent,calender_privateevent,calender_birthday,calender_todaybits,calender_daybits,months,days";	
   else {
    if($_REQUEST['action']=="viewevent") $this->templatelist.=",calender_viewevent,codephptag,codetag";	
    if($_REQUEST['action']=="viewbirthdays") $this->templatelist.=",index_birthdaybit,index_birthdaybit,calender_viewbirthdays";	
    if($_REQUEST['action']=="addevent") $this->templatelist.=",newthread_error1,calender_addevent_error1,newthread_error,calender_addevent_grouppublic,calender_addevent,days,months,newthread,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";	
    if($_REQUEST['action']=="editevent") $this->templatelist.=",newthread_error1,calender_addevent_error1,newthread_error,calender_editevent,days,months,newthread,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";	
    if($_REQUEST['action']=="eventcalender") $this->templatelist.=",calender_monthbit,calender_eventbit,calender_events,months,days,codetag,codephptag";	
   }	
  }
  
  if($filename=="editpost.php") {
   $this->templatelist.=",navbar_board,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,editpost_appendeditnote,editpost_delpost,newthread_attachment,editpost,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";	
   if(isset($_REQUEST['preview'])) $this->templatelist.=",codephptag,codetag,newthread_preview";	
   if($wbbuserdata['userid']) $this->templatelist.=",newthread_username";
   else $this->templatelist.=",newthread_username_input";
 
   if(isset($_REQUEST['send'])) $this->templatelist.=",newthread_error4,newthread_error1,newthread_error";
  }
  
  if($filename=="forgotpw.php") {
   if(isset($_REQUEST['action']) && $_REQUEST['action']=="pw") $this->templatelist.=",error_usernotexist,error_falseverifycode,forgotpw_message2,forgotpw_subject2,redirect_pwsend";	
   else $this->templatelist.=",forgotpw,error_usernotexist,forgotpw_message1,forgotpw_subject1,redirect_hashsend";
  }
  
  if($filename=="formmail.php") $this->templatelist.=",formmail,formmail_to,formmail_friendmessage,error_falserecipient,redirect_emailsend";	
    
  if($filename=="index.php") {
   global $showpmonindex, $showuseronline, $showbirthdays, $showevents, $showstats, $showlastposttitle, $showuseronlineinboard, $show_subboards;
   
   $this->templatelist.=",board_thread_prefix,today,index,index_boarddescription,index_lastposter,index_lastposter_guest,index_lastpost,index_nolastpost,index_moderatorbit,index_moderators,index_boardbit1,index_boardbit2,index_showcat,index_hidecat,index_catbit1,index_catbit2";

   if($wbbuserdata['userid']) {
    $this->templatelist.=",index_hello";
    if($wbbuserdata['canusepms']==1 && $showpmonindex==1) $this->templatelist.=",index_newpm,index_nonewpm,index_pms";
   }
   else $this->templatelist.=",index_welcome,index_quicklogin";
  
   if($showuseronline==1 || $showuseronlineinboard==2) $this->templatelist.=",useronline_buddy,useronline_admin,useronline_smod,useronline_mod,index_useronline_invisible,index_useronline,index_showuseronline";
   if($showbirthdays==1) $this->templatelist.=",index_birthdaybit,index_birthdays";
   if($showevents==1) $this->templatelist.=",index_publicevent,index_privateevent,index_events,index_showevents";
   if($showstats==1) $this->templatelist.=",index_stats";
   if($showlastposttitle==1) $this->templatelist.=",index_lastpost_title_hide,index_lastpost_title_show,index_lastpost_title";
   if($showuseronlineinboard==1 || $showuseronlineinboard==2) $this->templatelist.=",index_board_useronline";
   if($show_subboards==1) $this->templatelist.=",index_subboard,index_subboardbit";
  }
  
  if($filename=="login.php") $this->templatelist.=",error_login,login";
  
  if($filename=="memberslist.php") $this->templatelist.=",memberslist_pm,memberslist_formmail,memberslist_email,memberslist_homepage,memberslist_search,memberslist_membersbit,memberslist,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";	
  
  if($filename=="misc.php") {
   if($_REQUEST['action']=="finduser") $this->templatelist.=",finduser,finduser_result";
   if($_REQUEST['action']=="moresmilies") $this->templatelist.=",popup_smiliesbits,popup_smilies";
   if($_REQUEST['action']=="whoposted") $this->templatelist.=",anonymous_plural,whopostedbit,whoposted";
   if($_REQUEST['action']=="viewip") $this->templatelist.=",navbar_board,viewip_userip,viewip";
   if($_REQUEST['action']=="faq") $this->templatelist.=",faq";
   if($_REQUEST['action']=="faq1") $this->templatelist.=",faq1_rankbit,faq1";
   if($_REQUEST['action']=="faq2") $this->templatelist.=",faq2";
   if($_REQUEST['action']=="faq3") $this->templatelist.=",faq3";
   if($_REQUEST['action']=="showsmilies") $this->templatelist.=",showsmilies,showsmiliesbit";
   if($_REQUEST['action']=="bbcode") $this->templatelist.=",faq_bbcode_links,faq_bbcode_content,faq_bbcode";
  }
  
  if($filename=="modcp.php") {
   if($_REQUEST['action']=="post_del") $this->templatelist.=",navbar_board,modcp_post_del,modcp_postbit,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";
   if($_REQUEST['action']=="thread_cut") $this->templatelist.=",error_cantcut,error_emptyfields,navbar_board,modcp_thread_cut,modcp_postbit2,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";
   if($_REQUEST['action']=="thread_del") $this->templatelist.=",navbar_board,modcp_thread_del";
   if($_REQUEST['action']=="thread_move") $this->templatelist.=",navbar_board,error_cantmove,modcp_thread_move";
   if($_REQUEST['action']=="thread_merge") $this->templatelist.=",navbar_board,error_cantmerge,modcp_thread_merge";
   if($_REQUEST['action']=="thread_edit") $this->templatelist.=",newthread_prefix,modcp_thread_remove_redirect,modcp_thread_edit_important,navbar_board,error_emptyfields,newthread_iconbit,newthread_icons,modcp_thread_edit";
  }
  
  if($filename=="newthread.php") {
   $this->templatelist.=",newthread_important,newthread_poll,navbar_board,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,newthread_attachment,newthread,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";	
   if($wbbuserdata['userid']) $this->templatelist.=",newthread_username";
   else $this->templatelist.=",newthread_username_input";
 
   if(isset($_REQUEST['preview'])) $this->templatelist.=",newthread_preview,codephptag,codetag";	
   if(isset($_REQUEST['send'])) $this->templatelist.=",newthread_error4,newthread_error1,newthread_error,redirect_waiting4moderation,ms_newthread,mt_newthread_lastone,mt_newthread";
  }
  
  if($filename=="pms.php") {
   if(!isset($_REQUEST['action'])) $this->templatelist.=",pms_folderbit,pms_moveto_options,pms_folder_rename,pms_bit_outbox,pms_outbox,pms_bit,pms_folder";
   else {
    if($_REQUEST['action']=="createfolder") $this->templatelist.=",redirect_falsefolder,redirect_toomanyfolders";
    if($_REQUEST['action']=="viewpm") $this->templatelist.=",thread_signature,pms_viewpm,codephptag,codetag";
    if($_REQUEST['action']=="newpm") $this->templatelist.=",pm_newpm,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies,newthread_error1,pms_newpm_error1,pms_newpm_error2,pms_newpm_error3,pms_newpm_error4,newthread_error,mt_newpm,ms_newpm,pms_newpm_preview";
    if($_REQUEST['action']=="forwardpm") $this->templatelist.=",pm_newpm,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies,newthread_error1,pms_newpm_error1,pms_newpm_error2,pms_newpm_error3,pms_newpm_error4,newthread_error,mt_newpm,ms_newpm,pms_newpm_preview,pms_forward_subject,pms_forward_message";
    if($_REQUEST['action']=="replypm") $this->templatelist.=",pm_newpm,newthread_iconbit,newthread_icons,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies,newthread_error1,pms_newpm_error1,pms_newpm_error2,pms_newpm_error3,pms_newpm_error4,newthread_error,mt_newpm,ms_newpm,pms_newpm_preview,pms_reply_subject,pms_reply_message";
    if($_REQUEST['action']=="tracking") $this->templatelist.=",pms_folderbit,thread_user_online,thread_user_offline,pms_tracking_readbit,pms_tracking_unreadbit,pms_tracking_read,pms_tracking_unread,pms_tracking";
    if($_REQUEST['action']=="deletepm") $this->templatelist.=",pms_deletepm";
    if($_REQUEST['action']=="printpm") $this->templatelist.=",thread_signature,pms_printpm,codephptag,codetag";
    if($_REQUEST['action']=="popup") $this->templatelist.=",pmpopup_pmbit,pmpopup";
   }	
  }
  
  if($filename=="pollstart.php") $this->templatelist.=",polledit";
  if($filename=="pollstart.php") $this->templatelist.=",window_close,pollstart_give_parent,pollstart";
  if($filename=="pollvote.php") $this->templatelist.=",error_falsevote,error_polltimeout,error_tomanyvotes,error_alreadyvoted";
  if($filename=="print.php") $this->templatelist.=",thread_signature,print_postbit,print_navbar,print";
  if($filename=="profile.php") {
   global $showlastpostinprofile, $userlevels;
   
   $this->templatelist.=",thread_user_online,thread_user_offline,userrating_rate,userrating_green,userrating_yellow,userrating_red,userrating,thread_formmail,thread_search,thread_pm,profile_male,profile_female,profile_nodeclaration,avatar_flash,avatar_image,profile_userfield,profile_hr,profile";
   if($showlastpostinprofile==1) $this->templatelist.=",profile_lastpost";
   if($userlevels==1) $this->templatelist.=",userlevel,userlevel_expbar";
  }
  
  if($filename=="register.php") {
   if(isset($_REQUEST['action']) && $_REQUEST['action']=="activation") $this->templatelist.=",error_usernotexist,error_accountalreadyactive,error_falseactivationcode,redirect_accountactive,register_activation";	
   else {
    global $allowregister;
    if($allowregister!=1) $this->templatelist.=",error_register_disabled";
    else {
     global $showdisclaimer;
     if($showdisclaimer==1 && (!isset($_REQUEST['disclaimer']) || $_REQUEST['disclaimer']!="viewed")) $this->templatelist.=",register_disclaimer";
     else {
      $this->templatelist.=",timezones,register_userfield,register_password,register,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow";
      if(isset($_REQUEST['send'])) {
       global $regnotify;
       if($regnotify==1) $this->templatelist.=",ms_regnotify,mt_regnotify";
       $this->templatelist.=",register_error1,register_error2,register_error3,register_error4,register_error5,register_error6,register_error7,register_error,register_mail1_subject,register_mail1_content,redirect_register1,redirect_register2,register_mail3_subject,register_mail3_content,redirect_register3";
      }
     }	
    }	
   }
  }
  
  if($filename=="report.php") $this->templatelist.=",report";
  if($filename=="search.php") {
   $this->templatelist.=",error_searchnoresult";
   if(isset($_REQUEST['searchid'])) $this->templatelist.=",board_thread_prefix,codephptag,codetag,today,thread_newpost,thread_nonewpost,thread_username,search_postbit,search_post,board_threadbit_rating,board_thread_poll,board_threadbit_firstnew,board_threadbit_starter,board_threadbit_lastposter,board_threadbit_multipages_lastpage,board_threadbit_multipages,board_threadbit_attachments,search_threadbit,search_thread,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";
   else $this->templatelist.=",error_invalidsearch,search,error_searchbad";	
  }
  
  if($filename=="team.php") $this->templatelist.=",boardjump,thread_user_online,boardjump,thread_user_offline,thread_pm,team_adminbit,team_modbit,team";
 
  if($filename=="thread.php") {
   global $thread, $modpermissions, $userratings, $showuserratinginthread, $showonlineinthread, $showregdateinthread, $showuserfieldsinthread, $showgenderinthread, $showuserpostsinthread, $showuserlevels;
   
   if(isset($_REQUEST['threadview'])) $threadview=intval($_REQUEST['threadview']);
   else $threadview=$wbbuserdata['threadview'];
   
   $this->templatelist.=",board_thread_prefix,thread_invisible,board_threadbit_rating,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last,thread_attachmentbit_show,thread_attachmentbit_show_small,codephptag,codetag,thread_formmail,thread_pm,today,anonymous,boardjump,error_nonextnewest,error_nonextoldest,navbar_board,board_newthread,thread_closed,thread_addreply,thread_newpost,thread_nonewpost,thread_attachmentbit,thread_lastedit,thread_rankimages,thread_search,thread_homie,thread_email,thread_homepage,thread_icq,thread_aim,thread_yim,avatar_flash,thread_useravatar,avatar_image,thread_username,thread_threadstarter,thread_signature,thread_postbit";	
   if($threadview==0) $this->templatelist.=",thread";
   else $this->templatelist.=",thread_threaded,thread_postlistbit,thread_postlistbit2";
   if($userratings==1 && $showuserratinginthread==1) $this->templatelist.=",userrating_rate,userrating_green,userrating_yellow,userrating_red,userrating";
   if($showonlineinthread==1) $this->templatelist.=",thread_user_online,thread_user_offline";
   if($showregdateinthread==1) $this->templatelist.=",thread_regdate";
   if($showuserfieldsinthread==1) $this->templatelist.=",thread_userfields";
   if($showgenderinthread==1) $this->templatelist.=",thread_gender_male,thread_gender_female";
   if($showuserpostsinthread==1) $this->templatelist.=",thread_userposts";
   if($showuserlevels==1) $this->templatelist.=",userlevel,userlevel_expbar";
   
   if($wbbuserdata['issupermod']==1 || $modpermissions['userid']) $this->templatelist.=",thread_modoptions";
   elseif($wbbuserdata['userid'] && $thread['starterid']==$wbbuserdata['userid']) $this->templatelist.=",thread_useroptions";
   if($thread['pollid']) $this->templatelist.=",mod_poll_edit,thread_poll_resultbit,thread_poll_result,thread_pollbit,thread_poll";
  }
  
  if($filename=="threadrating.php") $this->templatelist.=",error_threadarchive,error_falserating,error_alreadyrate";
  
  if($filename=="usercp.php") {
   if(!isset($_REQUEST['action'])) $this->templatelist.=",usercp";
   else {
    if($_REQUEST['action']=="profile_edit") $this->templatelist.=",ms_emailchange3,mt_emailchange3,ms_emailchange1,mt_emailchange1,redirect_emailchange3,redirect_emailchange1,redirect_emailchange2,register_error1,register_error4,register_error7,usercp_error,register_userfield,register_userfield,usercp_profile_edit";
    if($_REQUEST['action']=="signature_edit") $this->templatelist.=",register_error5,register_error6,usercp_error,usercp_signature_edit_preview,usercp_signature_edit_old,usercp_signature_edit,note_html_not_allow,note_bbcode_not_allow,note_smilies_not_allow,note_images_not_allow,note_html_allow,note_bbcode_allow,note_smilies_allow,note_images_allow,bbcode_sizebits,bbcode_fontbits,bbcode_colorbits,bbcode_buttons,bbcode_smilies_getmore,bbcode_smiliebit,bbcode_smilies";
    if($_REQUEST['action']=="options_change") $this->templatelist.=",usercp_options_change,months";
    if($_REQUEST['action']=="password_change") $this->templatelist.=",error_emptyfields,error_pwnotidentical,error_pwnotidentical,usercp_password_change";
    if($_REQUEST['action']=="buddy_list") $this->templatelist.=",error_usernotexist,error_cantaddyourself,thread_user_online,thread_user_offline,usercp_buddy_listbit,usercp_buddy_list";
    if($_REQUEST['action']=="ignore_list") $this->templatelist.=",error_usernotexist,error_cantaddyourself,error_cantaddmods,usercp_ignore_listbit,usercp_ignore_list";
    if($_REQUEST['action']=="buddy") $this->templatelist.=",error_usernotexist,error_usernotexist,error_cantaddyourself";
    if($_REQUEST['action']=="ignore") $this->templatelist.=",error_usernotexist,error_usernotexist,error_cantaddyourself,error_cantaddmods";
    if($_REQUEST['action']=="avatars") $this->templatelist.=",error_falseavatar,avatar_flash,avatar_image,usercp_avatarbit,usercp_avatarbit,usercp_avatarbit_tr,usercp_avatar_choice,usercp_avatar_useown,usercp_avatars,pagelink,pagelink_first,pagelink_left,pagelink_current,pagelink_nocurrent,pagelink_right,pagelink_last";
    if($_REQUEST['action']=="favorites") $this->templatelist.=",index_boarddescription,index_lastposter,index_lastposter_guest,index_lastpost_title_show,index_lastpost_title,index_lastpost,index_nolastpost,usercp_boardbit,usercp_boardheader,usercp_noboards,board_threadbit_rating,board_thread_poll,board_threadbit_firstnew,board_threadbit_starter,board_threadbit_lastposter,board_threadbit_multipages_lastpage,board_threadbit_multipages,board_threadbit_attachments,usercp_threadbit,usercp_threadheader,usercp_nothreads,usercp_favorites";
   }
  }
  
  if($filename=="wiw.php") $this->templatelist.=",useronline_buddy,useronline_admin,useronline_smod,useronline_mod,index_useronline_invisible,index_useronline,wiw,wiw_addreply,wiw_auto_refresh,wiw_board,wiw_calender,wiw_guest,wiw_index,wiw_line,wiw_login,wiw_logout,wiw_memberlist,wiw_modcp,wiw_newthread,wiw_pms,wiw_profile,wiw_register,wiw_search,wiw_team,wiw_thread,wiw_unknown,wiw_userbit,wiw_usercp,wiw_wiw";
 }
}
?>