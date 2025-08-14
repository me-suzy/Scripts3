<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

if (preg_match("/(admin\/staff.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "staff":

  echohtmlheader();
  echotableheader("Edit Staff",1);
  echotabledescription("Below is a list of all the current staff, to edit them click the appropriate link next to the name you wish to edit",1);
  echotabledescription(returnlinkcode("Add Staff","admin.php?action=staff_search_form"),1);

  $tablerows = returnminitablerow("<b>Username</b>","<b>Number Of News Posts</b>","<b>Options</b>");

  $getdata = query("SELECT news_staff.id,news_staff.userid,news_staff.newsposts,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_staff LEFT JOIN ".$foruminfo[user_table]." ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow(htmlspecialchars($data_arr[username]),"$data_arr[newsposts]&nbsp;",returnlinkcode("Edit","admin.php?action=staff_edit&id=$data_arr[id]")." | ".returnlinkcode("Delete","admin.php?action=staff_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "staff_edit":

  settype($id,"integer");
  if ($data_arr = query_first("SELECT news_staff.*,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_staff LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." WHERE news_staff.id = $id")) {

    echohtmlheader("adminjs");
    echoformheader("staff_update","Edit Staff");
    updatehiddenvar("id",$id);
    echotabledescription(returnlinkcode("Select All","javascript:ca()")." | ".returnlinkcode("Deselect All","javascript:cn()"));
    echotablerow("Username:",htmlspecialchars($data_arr[username]));
    echoinputcode("Job:","job",$data_arr[job]);

    foreach ($cat_arr AS $key => $val) {
      if ($val[parentid] == 0) {
        echoyesnocode("Able to post $val[name] news?","canpost_$key",$data_arr[canpost_.$key]);
      }
    }

    echoyesnocode("Able to edit comments from all categories?","caneditallcomments",$data_arr[caneditallcomments]);
    echoyesnocode("Able to maintain database?","canmaintaindb",$data_arr[canmaintaindb]);
    echoyesnocode("Able to edit all news posts?","caneditallnews",$data_arr[caneditallnews]);
    echoyesnocode("Able to make news posts sticky?","canmakesticky",$data_arr[canmakesticky]);
    echoyesnocode("Able to edit delete news logos?","candeletelogos",$data_arr[candeletelogos]);
    echoyesnocode("Able to view admin log?","canviewlog",$data_arr[canviewlog]);
    echoyesnocode("Able to edit staff?","caneditstaff",$data_arr[caneditstaff]);
    echoyesnocode("Able to edit polls?","caneditpolls",$data_arr[caneditpolls]);
    echoyesnocode("Able to edit newscategories?","caneditcategories",$data_arr[caneditcategories]);
    echoyesnocode("Able to edit articles?","caneditarticles",$data_arr[caneditarticles]);
    echoyesnocode("Able to edit site options?","caneditoptions",$data_arr[caneditoptions]);
    echoyesnocode("Able to edit smilies?","caneditsmilies",$data_arr[caneditsmilies]);
    echoyesnocode("Able to edit profile fields?","caneditprofilefields",$data_arr[caneditprofilefields]);
    echoyesnocode("Able to edit users?","caneditusers",$data_arr[caneditusers]);
    echoyesnocode("Able to edit themes?","caneditthemes",$data_arr[caneditthemes]);
    echoyesnocode("Able to edit modules?","caneditmodules",$data_arr[caneditmodules]);

    $getmods = query("SELECT name,text FROM news_module");
    while ($mod_arr = fetch_array($getmods)) {
      echoyesnocode("Able to edit module $mod_arr[text]?","caneditmod_$mod_arr[name]",$data_arr[caneditmod_.$mod_arr[name]]);
    }

    echoformfooter();
    echohtmlfooter();

  } else {
    adminerror("Invalid ID","Youhave specified an invalid id.");
  }
break;

case "staff_update":
  verifyid("news_staff",$id);

  if ($job == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  $getfields = query("SHOW FIELDS FROM news_staff");
  while ($field_arr = fetch_array($getfields)) {
    if (($field_arr[Field] != "id") & ($field_arr[Field] != "userid") & ($field_arr[Field] != "job") & ($field_arr[Field] != "newsposts")) {
      $sql .= ",\n    $field_arr[Field] = '".${$field_arr[Field]}."'";
    }
  }

  query("
    UPDATE news_staff
    SET job = '$job'$sql
    WHERE id = $id");

  writeallpages();

  echoadminredirect("admin.php?action=staff");
  exit;
break;

case "staff_delete":

  verifyid("news_staff",$id);
  echodeleteconfirm("staff member","staff_kill",$id);

break;

case "staff_kill":
  verifyid("news_staff",$id);

  query("DELETE FROM news_staff WHERE id = $id");

  writeallpages();

  echoadminredirect("admin.php?action=staff");
  exit;
break;

case "staff_search_form":

  echohtmlheader();
  echoformheader("staff_search","Add Staff");
  echotabledescription("Search for the user below");
  echoinputcode("Username:","searchname");
  echoyesnocode("Search type:","searchtype",1,"Exact Search","<br />Keyword Search");
  echoformfooter();
  echohtmlfooter();

break;

case "staff_search":

  $condition = iif($searchtype == 1,"= '$searchname'","LIKE '%$searchname%'");

  $getdata = query("SELECT ".$foruminfo[user_table].".".$foruminfo[username_field]." AS username,".$foruminfo[user_table].".".$foruminfo[userid_field]." AS userid,".$foruminfo[user_table].".".$foruminfo[email_field]." AS email,news_staff.id FROM $foruminfo[user_table] LEFT JOIN news_staff ON ".$foruminfo[user_table].".".$foruminfo[userid_field]." = news_staff.userid WHERE ".$foruminfo[user_table].".".$foruminfo[username_field]." $condition ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);
  $num_found = countrows($getdata);

  $users_found = returnminitablerow("<b>Username</b>","<b>Email</b>","<b>User id</b>","<b>Options</b>");

  while ($data_arr = fetch_array($getdata)) {
    if ($data_arr[id]) {
      $num_found --;
    } else {
      $users_found .= returnminitablerow(htmlspecialchars($data_arr[username]),returnlinkcode($data_arr[email],"mailto:$data_arr[email]"),$data_arr[userid],returnlinkcode("Add","admin.php?action=staff_add&id=$data_arr[userid]"));
    }
  }

  echohtmlheader();
  echotableheader("Add Staff",1);
  echotabledescription("Your search has returned $num_found result(s) (note that the username you searched for may be already added, and there for not be found)",1);
  echotabledescription("\n".returnminitable($users_found,0,100)."    ",1);
  echotabledescription("Click ".returnlinkcode("here","javascript:history.back(1)")." to go back and refine search",1);
  echotablefooter();
  echohtmlfooter();

break;

case "staff_add":

  verifyid($foruminfo[user_table],$id,$foruminfo[userid_field]);

  if ($staff_presant = query_first("SELECT userid FROM news_staff WHERE userid = $id")) {
    adminerror("User already added","The user you have tried to add has already been added to the database");
  }

  $data_arr = query_first("SELECT $foruminfo[username_field] AS username FROM $foruminfo[user_table] WHERE $foruminfo[userid_field] = $id");

  echohtmlheader("adminjs");
  echoformheader("staff_new","Add Staff");
  updatehiddenvar("id",$id);
  echotabledescription(returnlinkcode("Select All","javascript:ca()")." | ".returnlinkcode("Deselect All","javascript:cn()"));
  echotablerow("Username:",htmlspecialchars($data_arr[username]));
  echoinputcode("Job:","job");

  foreach ($cat_arr AS $key => $val) {
    if ($val[parentid] == 0) {
      echoyesnocode("Able to post $val[name] news?","canpost_$key",1);
    }
  }

  echoyesnocode("Able to edit comments from all categories?","caneditallcomments",0);
  echoyesnocode("Able to maintain data base?","canmaintaindb",0);
  echoyesnocode("Able to edit all news posts?","caneditallnews",0);
  echoyesnocode("Able to make news posts sticky?","canmakesticky",0);
  echoyesnocode("Able to edit delete news logos?","candeletelogos",0);
  echoyesnocode("Able to view admin log?","canviewlog",0);
  echoyesnocode("Able to edit staff?","caneditstaff",0);
  echoyesnocode("Able to edit polls?","caneditpolls",0);
  echoyesnocode("Able to edit newscategories?","caneditcategories",0);
  echoyesnocode("Able to edit articles?","caneditarticles",0);
  echoyesnocode("Able to edit site options?","caneditoptions",0);
  echoyesnocode("Able to edit smilies?","caneditsmilies",0);
  echoyesnocode("Able to edit profile fields?","caneditprofilefields",0);
  echoyesnocode("Able to edit users?","caneditusers",0);
  echoyesnocode("Able to edit themes?","caneditthemes",0);
  echoyesnocode("Able to edit modules?","caneditmodules",0);

  $getmods = query("SELECT name,text FROM news_module");
  while ($mod_arr = fetch_array($getmods)) {
    echoyesnocode("Able to edit module $mod_arr[text]?","caneditmod_$mod_arr[name]",0);
  }

  echoformfooter();
  echohtmlfooter();

break;

case "staff_new":

  verifyid($foruminfo[user_table],$id,$foruminfo[userid_field]);

  if ($job == "") {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if ($staff_presant = query_first("SELECT userid FROM news_staff WHERE userid = $id")) {
    adminerror("User already added","The user you have tried to add has already been added to the database");
  }

  $getfields = query("SHOW FIELDS FROM news_staff");
  while ($field_arr = fetch_array($getfields)) {
    if (($field_arr[Field] != "id") & ($field_arr[Field] != "userid") & ($field_arr[Field] != "job") & ($field_arr[Field] != "newsposts")) {
      $sql .= ",\n    '".${$field_arr[Field]}."'";
    }
  }

  query("
    INSERT INTO news_staff VALUES (
    NULL,
    '$id',
    '$job',
    '0'$sql)");

  writeallpages();

  echoadminredirect("admin.php?action=staff");
  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/staff.php
|| ####################################################################
\*======================================================================*/

?>