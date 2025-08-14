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

if (preg_match("/(admin\/profilefield.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

if ($use_forum) {
  adminerror("Not Using VirtuaNews User Tables","This site is currently set to use the user tables from the forums on this site.  To edit the profile fields you must use the administration panel for the forums.");
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "profilefield":

  echohtmlheader();
  echotableheader("Edit User Profile Fields",1);
  echotabledescription("Using this section you can add, edit and delete user profile fields that are available on the site.",1);
  echotabledescription(returnlinkcode("Add Profile Field","admin.php?action=profilefield_add"),1);

  $tablerows = returnminitablerow("<b>Title</b>","<b>Required</b>","<b>Hidden</b>","<b>Editable</b>","<b>Options</b>");

  $getdata = query("SELECT id,title,required,hidden,editable FROM news_profilefield ORDER BY displayorder");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[title],iif($data_arr[required],"Yes","No"),iif($data_arr[hidden],"Yes","No"),iif($data_arr[editable],"Yes","No"),returnlinkcode("Edit","admin.php?action=profilefield_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=profilefield_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "profilefield_add":

  $temp = query_first("SELECT count(id) AS count FROM news_profilefield");
  $displayorder = $temp[count] + 1;

  echohtmlheader();
  echoformheader("profilefield_new","Add Profile Field");
  echotabledescription("Below you can add a custom user profile field, once you have finished your changes press submit to save them.");
  echoinputcode("Title:","title");
  echoinputcode("Description:","description","",40,1);
  echoinputcode("Maximum Length:","maxlength");
  echoinputcode("Field Size:<br />Size of the input box displayed on the page","size");
  echoyesnocode("Required:","required",0);
  echoyesnocode("Hidden:","hidden",0);
  echoyesnocode("Editable:","editable");
  echoinputcode("Display Order:","displayorder",$displayorder,10);
  echoformfooter();
  echohtmlfooter();

break;

case "profilefield_new":

  if (($title == "") | ($maxlength == "") | ($size == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  query("INSERT INTO news_profilefield VALUES (NULL,'$title','$description','$required','$hidden','$editable','$displayorder','".intval($maxlength)."','$size')");
  $fieldid = getlastinsert();
  query("ALTER TABLE news_userfield ADD field$fieldid VARCHAR(".intval($maxlength).") NOT NULL DEFAULT ''");
  query("OPTIMIZE TABLE news_userfield");

  writeallpages();

  echoadminredirect("admin.php?action=profilefield");

break;

case "profilefield_edit":

  verifyid("news_profilefield",$id);

  $data_arr  = query_first("SELECT title,description,maxlength,size,required,hidden,editable,displayorder FROM news_profilefield WHERE id = $id");

  echohtmlheader();
  echoformheader("profilefield_update","Edit Profile Field");
  updatehiddenvar("id",$id);
  echotabledescription("Below you can add a custom user profile field, once you have finished your changes press submit to save them.");
  echoinputcode("Title:","title",$data_arr[title]);
  echoinputcode("Description: <span class=\"red\">(Optional)</span>","description",$data_arr[description]);
  echoinputcode("Maximum Length:","maxlength",$data_arr[maxlength]);
  echoinputcode("Field Size:<br />Size of the input box displayed on the page","size",$data_arr[size]);
  echoyesnocode("Required:","required",$data_arr[required]);
  echoyesnocode("Hidden:","hidden",$data_arr[hidden]);
  echoyesnocode("Editable:","editable",$data_arr[editable]);
  echoinputcode("Display Order:","displayorder",$data_arr[displayorder],10);
  echoformfooter();
  echohtmlfooter();

break;

case "profilefield_update":

  verifyid("news_profilefield",$id);
  if (($title == "") | ($maxlength == "") | ($size == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  query("UPDATE news_profilefield SET title = '$title' , description = '$description' , required = '$required' , hidden = '$hidden' , editable = '$editable' , displayorder = '$displayorder' , maxlength = '".intval($maxlength)."' , size = '$size' WHERE id = $id");

  query("ALTER TABLE news_userfield CHANGE field$id field$id VARCHAR(".intval($maxlength).") NOT NULL DEFAULT ''");
  query("OPTIMIZE TABLE news_userfield");

  writeallpages();

  echoadminredirect("admin.php?action=profilefield");

break;


case "profilefield_delete":

  verifyid("news_profilefield",$id);
  echodeleteconfirm("profile field","profilefield_kill",$id);

break;

case "profilefield_kill":

  verifyid("news_profilefield",$id);
  query("DELETE FROM news_profilefield WHERE id = $id");
  query("ALTER TABLE news_userfield DROP field$id");
  query("OPTIMIZE TABLE news_userfield");
  writeallpages();
  echoadminredirect("admin.php?action=profilefield");

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/profilefield.php
|| ####################################################################
\*======================================================================*/

?>