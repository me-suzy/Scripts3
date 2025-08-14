<?php


if (preg_match("/(admin\/module.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "module":

  echohtmlheader();
  echotableheader("Edit Modules",1);
  echotabledescription("Modules are addons that can be wrote to go along with VirtuaNews and be easily integrated into the site.  You can use this page to control the modules which are displayed on the site.  Below is a list of the currently installed modules, click the links next to them if you wish to edit them",1);
  echotabledescription(returnlinkcode("Add Module","admin.php?action=module_add"),1);

  $tablerows = returnminitablerow("<b>Module Text</b>","<b>Version</b>","<b>Display</b>","<b>Enabled</b>","<b>Options</b>");

  $getdata = query("SELECT id,text,display,version,enable FROM news_module ORDER BY text");

  while ($data_arr = fetch_array($getdata)) {
    $tablerows .= returnminitablerow($data_arr[text],$data_arr[version],iif($data_arr[display],"Yes","No"),iif($data_arr[enable],"Yes","No"),returnlinkcode("Edit","admin.php?action=module_edit&id=$data_arr[id]")." | ".returnlinkcode("Delete","admin.php?action=module_delete&id=$data_arr[id]")." | ".returnlinkcode("Options","admin.php?action=module_options&id=$data_arr[id]"));
  }

  echotabledescription("\n".returnminitable($tablerows,0,100)."    ",1);
  echotablefooter();
  echohtmlfooter();

break;

case "module_add":

  echohtmlheader();
  echoformheader("module_new","Add Modules");
  echotabledescription("Use this page to easily add a module to your site");
  echotabledescription("-");
  echotabledescription("NOTE The module name must not contain any spaces, the module files must have already been uploaded to the module directory, in a directory of their own named by the module name.  The add process will fail if this is not true");
  echotabledescription("You must ensure you have write permissions for the modules directory, otherwise this process will not work correctly and cause an error");
  echoinputcode("Module Name:","name");
  echoinputcode("Module Text:","text");
  echotextareacode("Module Description:","description","",10,100,1);
  echoyesnocode("Display:","display",1);
  echoyesnocode("Enable:","enable",1);

  if ($userinfo[caneditstaff]) {
    $getdata = query("SELECT news_staff.id,".$foruminfo[user_table].".".$foruminfo[username_field]." AS username FROM news_staff LEFT JOIN $foruminfo[user_table] ON news_staff.userid = ".$foruminfo[user_table].".".$foruminfo[userid_field]." ORDER BY ".$foruminfo[user_table].".".$foruminfo[username_field]);
    while ($data_arr = fetch_array($getdata)) {
      $checkboxes .= returncheckboxcode("staff[$data_arr[id]]","1",$data_arr[username],iif($data_arr[id] == $staffid,1,0));
    }
    echotablerow("Staff Permitted To Edit:","\n$checkboxes    ");
  }

  echoformfooter();
  echohtmlfooter();

break;

case "module_new":

  if (($name == "") | ($text == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (preg_match("/\W/i",$name)) {
    adminerror("Invalid Title","You have specified an invalid title for the module, only characters, digits or underscores are allowed in the title");
  }

  if ($check_name = query_first("SELECT name FROM news_module WHERE name = '$name'")) {
    adminerror("Name Not Unique","The name for the module must be unique, there is already a module with the name $name on this site");
  }

  if (!file_exists("modules/$name")) {
    adminerror("Invalid Name","The module name you have entered is not valid because the files for it are not already uploaded to the modules directory in the right place");
  }

  if (!file_exists("modules/$name/admin.php") | !file_exists("modules/$name/index.php") | !file_exists("modules/$name/install.php")) {
    adminerror("File Not Found","You must ensure the files index.php, admin.php and install.php exist in the module directory before you continue");
  }

  include("modules/$name/install.php");

  if ($name != $modname) {
    adminerror("Incorrect Module","You have specified an incorrect module name for the module you are trying to install, it is called $modname in the install file, the name you enter must be the same");
  }

  if ($template) {
    foreach ($template AS $key => $val) {
      query("INSERT INTO news_page VALUES (NULL,'-2','$key','".addslashes($val[description])."','$val[onserver]')");

      writepagebit("pages/default/mod/".$key.".vnp",$val[data]);
      $templatelist .= "$key,";
    }
    $templatelist = substr($templatelist,0,-1);
  }

  if ($tablenames) {
    foreach ($tablenames AS $tbl_name) {
      $tablelist .= "$tbl_name,";
    }
    $tablelist = substr($tablelist,0,-1);
  }

  if ($sqlqueries) {
    foreach ($sqlqueries AS $query) {
      query($query);
    }
  }

  query("ALTER TABLE news_staff ADD caneditmod_$name TINYINT(1) DEFAULT '0' NOT NULL");
  query("OPTIMIZE TABLE news_staff");
  query("INSERT INTO news_module VALUES (NULL,'$name','$text','$description','$modversion','$templatelist','$tablelist','$display','$enable','".addslashes($options)."')");

  if ($userinfo[caneditstaff]) {
    foreach ($staff AS $key => $val) {
      if ($val == "1") {
        query("UPDATE news_staff SET caneditmod_$name = '1' WHERE id = $key");
      }
    }
  }

  writeallpages();

  echoadminredirect("admin.php?action=module");
  exit;

break;

case "module_delete":

  verifyid("news_module",$id);
  echodeleteconfirm("module","module_kill",$id);

break;

case "module_kill":

  verifyid("news_module",$id);

  $data_arr = query_first("SELECT name,templates,table_list FROM news_module WHERE id = $id");

  query("ALTER TABLE news_staff DROP caneditmod_$data_arr[name]");
  query("OPTIMIZE TABLE news_staff");

  query("DELETE FROM news_module WHERE id = $id");

  if ($data_arr[table_list]) {
    $table_arr = explode(",",$data_arr[table_list]);
    foreach ($table_arr AS $tbl_name) {
      query("DROP TABLE IF EXISTS $tbl_name");
    }
  }

  if ($data_arr[templates]) {

    $templatelist = str_replace(",","','",$data_arr[templates]);

    query("DELETE FROM news_page WHERE title IN ('$templatelist')");

    $gettemplatesets = query("SELECT id FROM news_pageset");
    while ($set_arr = fetch_array($gettemplatesets)) {
      $tempset_arr[] = $set_arr[id];
    }

    $templatelist = explode("','",$templatelist);
    foreach ($templatelist AS $title) {
      @unlink("pages/default/mod/".$title.".vnp");

      foreach ($tempset_arr AS $set_id) {
        if (@file_exists("pages/user/mod/".$title."_".$set_id.".vnp")) {
          @unlink("pages/user/mod/".$title."_".$set_id.".vnp");
        }
      }
    }

  }

  if ($startpage == "mod_$data_arr[name]") {
    query("UPDATE news_option SET value = 'news' WHERE varname = 'startpage'");
  }

  writeallpages();

  echoadminredirect("admin.php?action=module");
  exit;

break;

case "module_edit":

  verifyid("news_module",$id);

  $data_arr = query_first("SELECT name,text,description,display,enable FROM news_module WHERE id = $id");

  echohtmlheader();
  echoformheader("module_update","Edit Modules");
  updatehiddenvar("id",$id);
  echotabledescription("Use this page to easily edit a module on your site");
  echotabledescription("NOTE The module name must not contain any spaces");
  echotabledescription("You must ensure you have write permissions for the modules directory, otherwise this process will not work correctly and cause an error");
  echoinputcode("Module Name:","name",$data_arr[name]);
  echoinputcode("Module Text:","text",$data_arr[text]);
  echotextareacode("Module Description: <span class=\"red\">(optional)</span>","description",$data_arr[description],10,100);
  echoyesnocode("Display:","display",$data_arr[display]);
  echoyesnocode("Enable:","enable",$data_arr[enable]);
  echoformfooter();
  echohtmlfooter();

break;

case "module_update":

  verifyid("news_module",$id);

  if (($name == "") | ($text == "")) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  if (preg_match("/\W/i",$name)) {
    adminerror("Invalid Title","You have specified an invalid title for the module, only characters, digits or underscores are allowed in the title");
  }

  $data_arr = query_first("SELECT name FROM news_module WHERE id = $id");
  $old_name = $data_arr[name];

  if ($old_name != $name) {
    if ($check_name = query_first("SELECT name FROM news_module WHERE name = '$name'")) {
      adminerror("Name Not Unique","The name for the module must be unique, there is already a module with the name $name on this site");
    }

    if (!@rename("modules/$old_name","modules/$name")) {
      adminerror("Cannot Rename Folder","The forder modules/$old_name cannot be renamed, you must ensure that you set this folder to allow renaming, or do it yourself before you can continue");
    }
    query("ALTER TABLE news_staff CHANGE caneditmod_$old_name caneditmod_$name TINYINT(1) DEFAULT '0' NOT NULL");
    query("OPTIMIZE TABLE news_staff");
  }

  query("UPDATE news_module SET name = '$name' , description = '$description' , text = '$text' , display = '$display' , enable = '$enable' WHERE id = $id");

  writeallpages();

  echoadminredirect("admin.php?action=module");
  exit;

break;

case "module_options":

  verifyid("news_module",$id);

  $data_arr = query_first("SELECT name,text,options FROM news_module WHERE id = $id");

  echohtmlheader();
  echoformheader("module_option_update","Edit Module &quot;$data_arr[text]&quot; Options");
  updatehiddenvar("id",$id);
  echotabledescription("Use this page to edit the options for the following modules.  To disable the module you must set the module to not display <a href=\"admin.php?action=module_edit&id=$id\">here</a>.  Note, if there are no options here, then this module has nothing you can configure");

  if ($data_arr[options]) {

    $options = explode("||||||",$data_arr[options]);

    foreach ($options AS $temp) {

      $option_data = explode("/\\/\\/\\",$temp);

      $varname = $option_data[0];
      $value = $option_data[1];
      $title = $option_data[2];
      $description = $option_data[3];
      $code = $option_data[4];

      if ($code == "") {
        echoinputcode("<b>$title:</b><br />$description","option_$varname",$value,40,0,65);
      } elseif ($code == "yesno") {
        echoyesnocode("<b>$title:</b><br />$description","option_$varname",$value,"Yes","No",65);
      } elseif ($code == "textarea") {
        echotextareacode("<b>$title:</b><br />$description","option_$varname",$value,5,40,0,65);
      } else {
        eval ("\$row = \"$code\";");
        echotablerow("<b>$title:</b><br />$description",$row,"",65);
      }
    }
  }

  echoformfooter();
  echohtmlfooter();

break;

case "module_option_update":

  verifyid("news_module",$id);

  $data_arr = query_first("SELECT options FROM news_module WHERE id = $id");

  if ($data_arr[options]) {

    $options = explode("||||||",$data_arr[options]);

    foreach ($options AS $temp) {

      $option_data = explode("/\\/\\/\\",$temp);
      $varname = "option_".$option_data[0];

      $new_options .= iif($new_options,"||||||","");

      $new_options .= "$option_data[0]/\\/\\/\\".$$varname."/\\/\\/\\$option_data[2]/\\/\\/\\$option_data[3]/\\/\\/\\$option_data[4]";
    }
  }

  query("UPDATE news_module SET options = '".addslashes($new_options)."' WHERE id = $id");

  writeallpages();

  echoadminredirect("admin.php?action=module");
  exit;

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/module.php
|| ####################################################################
\*======================================================================*/

?>