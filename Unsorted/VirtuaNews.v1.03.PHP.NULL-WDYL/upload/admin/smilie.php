<?php


if (preg_match("/(admin\/smilie.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

if ($use_forum) {
  adminerror("Not Using VirtuaNews User Tables","This site is currently set to use the user tables from the forums on this site.  To edit the smilies you must use the administration panel for the forums.");
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "smilie":

  echohtmlheader();
  echotableheader("Edit Smilies",1);
  echotabledescription("Using this section you can add, edit and delete smilies that are available for use on the site.",1);
  echotabledescription(returnlinkcode("Add Smilie","admin.php?action=smilie_add"),1);

  $tablerows = returnminitablerow("<b>Title</b>","<b>Smilie Text</b>","<b>Options</b>");

  $getdata = query("SELECT id,title,smilietext,smiliepath FROM news_smilie ORDER BY title");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[title],$data_arr[smilietext],returnlinkcode("Edit","admin.php?action=smilie_edit&id=$data_arr[id]")." |".returnlinkcode("Delete","admin.php?action=smilie_delete&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "smilie_add":

  echohtmlheader();
  echoformheader("smilie_new","Add Smilie");
  echotabledescription("Below you can add a new smilie, once you have finished your changes press submit to save them.");
  echoinputcode("Title:","title");
  echoinputcode("Smilie Text: <span class=\"red\">(Case Sensative)</span>","smilietext");
  echoinputcode("Smilie Directory: <font class=\"red\">(Relative to site directory)","smiliepath","images/smilies/");
  echoformfooter();
  echoformheader("smilie_new","Upload New Smilie",2,1);
  echotabledescription("Below you can add a new smilie and upload one at the same time.  This will only work if PHP has permission to write to the directory you specify, once you have finished your changes press submit to save them.");
  echoinputcode("Title:","title");
  echoinputcode("Smilie Text: <span class=\"red\">(Case Sensative)</span>","smilietext");
  echoinputcode("Smilie Directory: <font class=\"red\">(Relative to site directory)","smiliepath","images/smilies/");
  echouploadcode("Upload File:","smiliefile");
  echoformfooter();
  echohtmlfooter();

break;

case "smilie_new":

  if (($title == "") | ($smilietext == "") | ($smiliepath == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (!empty($smiliefile)) {
    if (!@copy($smiliefile[tmp_name],"$smiliepath/$smiliefile[name]")) {
      adminerror("Upload Failed","The upload of the smilie has failed, please try uploading it manually, then adding it specifying where it is on the server.");
    }
    $smiliepath .= $smiliefile[name];
  }

  query("INSERT INTO news_smilie VALUES (NULL,'$title','$smilietext','$smiliepath')");

  writeallpages();

  echoadminredirect("admin.php?action=smilie");

break;

case "smilie_edit":

  verifyid("news_smilie",$id);

  $data_arr  = query_first("SELECT title,smilietext,smiliepath FROM news_smilie WHERE id = $id");

  echohtmlheader();
  echoformheader("smilie_update","Edit Smilie");
  updatehiddenvar("id",$id);
  echotabledescription("Below you can edit the smilie which is displayed, once you have finished your changes press submit to save them.");
  echoinputcode("Title:","title",$data_arr[title]);
  echoinputcode("Smilie Text: <span class=\"red\">(Case Sensative)</span>","smilietext",$data_arr[smilietext]);
  echoinputcode("Smilie Path: <font class=\"red\">(Relative to site directory)","smiliepath",$data_arr[smiliepath]);
  echoformfooter();
  echohtmlfooter();

break;

case "smilie_update":

  verifyid("news_smilie",$id);
  if (($title == "") | ($smilietext == "") | ($smiliepath == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  query("UPDATE news_smilie SET title = '$title' , smilietext = '$smilietext' , smiliepath = '$smiliepath' WHERE id = $id");

  writeallpages();

  echoadminredirect("admin.php?action=smilie");

break;


case "smilie_delete":

  verifyid("news_smilie",$id);
  echodeleteconfirm("smilie","smilie_kill",$id);

break;

case "smilie_kill":

  verifyid("news_smilie",$id);
  query("DELETE FROM news_smilie WHERE id = $id");
  writeallpages();
  echoadminredirect("admin.php?action=smilie");

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/smilie.php
|| ####################################################################
\*======================================================================*/

?>