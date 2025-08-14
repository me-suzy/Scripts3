<?php


if (preg_match("/(admin\/logo.php)/i",$PHP_SELF)) {
  header("location:../admin.php");
  exit;
}

updateadminlog(iif($id,"id = $id",""));

switch ($action) {

case "newslogo":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Edit Logos","You are unable to access this area as you do not have permission to do so.");
  }

  $javascript = "<script type=\"text/javascript\">

function ca() {
  var i=0;
  for (var i=0;i<document.form.elements.length;i++) {
    if ((document.form.elements[i].name != 'checkall') && (document.form.elements[i].type=='checkbox')) {
      document.form.elements[i].checked = document.form.checkall.checked;
    }
  }
}

</script>";

  echohtmlheader($javascript);
  echoformheader("newslogo_mass_delete","News Logos");
  echotabledescription("You may use this page to edit the news logos available for displaying along with news posts.  You can delete multiple logos by checking the box next to each logo you wish to delete and pressing submit.");

  $validtypes = explode(" ",$newslogouptype);
  foreach ($validtypes AS $val) {
    $extensions[$val] = 1;
  }

  $handle = opendir("images/news/logos/");

  while (false !== ($file = readdir($handle))) {
    $filetype = explode(".",$file);
    if ($extensions[$filetype[(count($filetype)-1)]]) {
      if (($file != ".") & ($file != "..") & ($file != "none.gif")) {
        $file_arr[] = $file;
      }
    }
  }

  closedir($handle);

  $tablerows = returnminitablerow("<b>File Name</b>","<b>Options</b>","<input type=\"checkbox\" name=\"checkall\" value=\"1\" onClick=\"ca()\"> <b>Delete</b>");

  if ($file_arr) {
    sort($file_arr);

    foreach ($file_arr as $val) {
      $count ++;
      $tablerows .= returnminitablerow($val,returnlinkcode("View","images/news/logos/".urlencode($val),1)." |".returnlinkcode("Rename","admin.php?action=newslogo_rename&logo=".urlencode($val))." |".returnlinkcode("Delete","admin.php?action=newslogo_delete&logo=".urlencode($val)),"<input type=\"checkbox\" name=\"logodelete[$count]\" value=\"$val\" />");
    }
  }

  echotabledescription("\n".returnminitable($tablerows,0,75)."    ");
  echoformfooter();
  echohtmlfooter();

break;


case "newslogo_upload":

  if (!$canpostnews) {
    adminerror("Cannot Upload Logos","You are unable upload news logos as you do not have the permission to do so.");
  }

  if (!$newsallowlogoup) {
    adminerror("Logo Upload Disabled","Uploading logos for news posts has been disabled.");
  }

  echohtmlheader();
  echoformheader("newslogo_doupload","Add a news logo to the server",2,1);
  echotabledescription("Below you can upload a file that can then be selected as a logo to display with a news post.  Select the file on your computer and press submit".iif($newslogoupsize > 0,", you may upload a maximum of $newslogoupsize bytes.","."));
  echouploadcode("Select the file to upload:","userfile",iif($newslogoupsize > 0,$newslogoupsize,1048576));
  echoformfooter();
  echohtmlfooter();

break;

case "newslogo_doupload":

  if (!$canpostnews) {
    adminerror("Cannot Upload Logos","You are unable upload news logos as you do not have the permission to do so.");
  }

  if (!$newsallowlogoup) {
    adminerror("Logo Upload Disabled","Uploading logos for news posts has been disabled.");
  }

  if (($userfile[size] > $newslogoupsize) & ($newslogoupsize > 0)) {
    adminerror("File To Large","The file you have tried to upload is too big, please upload a smaller logo or use a different one already uploaded.");
  }

  if (empty($userfile)) {
    adminerror("Blank field","All fields must be filled in, only ones saying <span class=\"red\">(optional)</span> may be left blank");
  }

  $valid = 0;

  $name_arr = explode(".",$userfile[name]);
  $filetype = $name_arr[count($name_arr)-1];

  $validextensions = explode(" ",$newslogouptype);

  foreach ($validextensions AS $extension) {
    if ($filetype == $extension) {
      $valid = 1;
      break;
    }
  }

  if (!$valid) {
    adminerror("Invalid File Type","You have tried to upload a valid file type, you may only upload files with the following extensions; $newslogouptype");
  }

  if (file_exists("images/news/logos/$userfile[name]") & !$newslogooverwrite) {
    adminerror("Logo Already Exists","There is already a logo that exists with the same name as the one that you have tried to upload, you are not allowed to overwrite it.");
  }

  if (!@copy($userfile[tmp_name],"images/news/logos/$userfile[name]")) {
    adminerror("Upload Failed","The upload of the image has failed, please check you can actually write to the forder /images/news/logos/ as this may be the problem.");
  }

  echohtmlheader();
  echotableheader("Image uploaded",1);
  echotabledescription("Thank you, the image how now been uploaded, please reload the add/edit news post page in order to view this new logo for selection.",1);
  echotablefooter();
  echohtmlfooter();

break;

case "newslogo_rename":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Edit Logos","You are unable to access this area as you do not have permission to do so.");
  }

  $logo = urldecode($logo);

  if (!file_exists("images/news/logos/$logo")) {
    adminerror("Invalid Logo","You have specified an invalid logo that does not exist.");
  }

  if ($logo == "none.gif") {
    adminerror("Cannot Rename Logo","You cannot rename this logo as it is needed for the admin panel.");
  }

  if (($logo == "..") | ($logo == ".")) {
    adminerror("Cannot Rename Logo","You cannot rename this logo for security reasons.");
  }

  echohtmlheader();
  echoformheader("newslogo_dorename","Rename News Logo");
  updatehiddenvar("logo",$logo);
  echotabledescription("You may use this form to rename a news logo for the news posts.");
  echotablerow("Rename From:",htmlspecialchars($logo));
  echoinputcode("Rename To:","newname",$logo);
  echoformfooter();
  echohtmlfooter();

break;

case "newslogo_dorename":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Edit Logos","You are unable to access this area as you do not have permission to do so.");
  }

  if (!file_exists("images/news/logos/$logo")) {
    adminerror("Invalid Logo","You have specified an invalid logo that does not exist.");
  }

  if ($logo == "none.gif") {
    adminerror("Cannot Rename Logo","You cannot rename this logo as it is needed for the admin panel.");
  }

  if (($logo == "..") | ($logo == ".")) {
    adminerror("Cannot Rename Logo","You cannot rename this logo for security reasons.");
  }

  $valid = 0;

  $name_arr = explode(".",$newname);
  $filetype = $name_arr[count($name_arr)-1];

  $validextensions = explode(" ",$newslogouptype);

  foreach ($validextensions AS $extension) {
    if ($filetype == $extension) {
      $valid = 1;
      break;
    }
  }

  unset($name_arr[count($name_arr)-1]);

  if (!$valid) {
    adminerror("Invalid File Type","You cannot rename this logo to the name specified as it is an invalid file type, you may only use files with the following extensions; $newslogouptype");
  }

  if (preg_match("/\W/i",join("",$name_arr))) {
    adminerror("Invalid Name","You have specified an invalid name, only characters, digits or underscores are allowed in the name.");
  }

  if (file_exists("images/news/logos/$newname") & ($newname != $logo)) {
    adminerror("Name Not Unique","The logo cannot be renamed because the new name you have specified is not unique.");
  }

  if (!@rename("images/news/logos/$logo","images/news/logos/$newname")) {
    adminerror("Unable To Rename","The logo has not been renamed as there has been an error, please ensure that you have the correct permissions set on the images/news/logos/ directory.");
  }

  query("UPDATE news_news SET logoimage = '$newname' WHERE logoimage = '$logo'");

  writeallpages();

  echoadminredirect("admin.php?action=newslogo");

break;

case "newslogo_delete":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Edit Logos","You are unable to access this area as you do not have permission to do so.");
  }

  if (!file_exists("images/news/logos/".urldecode($logo))) {
    adminerror("Invalid Logo","You have specified an invalid logo that does not exist.");
  }

  if ($logo == "none.gif") {
    adminerror("Cannot Delete Logo","You cannot delete this logo as it is needed for the admin panel.");
  }

  if (($logo == "..") | ($logo == ".")) {
    adminerror("Cannot Delete Logo","You logo you specified cannot be deleted as it is a potential security risk doing so.");
  }

  echodeleteconfirm("news logo","newslogo_kill","","","&logo=".urlencode($logo));

break;

case "newslogo_mass_delete":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Delete Logos","You are unable to access this area as you do not have permission to do so.");
  }

  if ($logodelete) {
    $logos = join(",",$logodelete);
  }

  echodeleteconfirm("news logos","newslogo_kill",""," This will delete ALL the logos you checked.","&logo=".urlencode($logos));

break;

case "newslogo_kill":

  if (!$userinfo[candeletelogos]) {
    adminerror("Cannot Delete Logos","You are unable to access this area as you do not have permission to do so.");
  }

  if ($logo) {
    $logo_arr = explode(",",urldecode($logo));
  } else {
    adminerror("Invalid Logo","You have specified to delete an invalid logo.");
  }

  foreach ($logo_arr AS $logo) {

    if (!file_exists("images/news/logos/$logo")) {
      adminerror("Invalid Logo","You have specified an invalid logo that does not exist.");
    }

    if ($logo == "none.gif") {
      adminerror("Cannot Delete Logo","You cannot delete this logo as it is needed for the admin panel.");
    }

    if (($logo == "..") | ($logo == ".")) {
      adminerror("Cannot Delete Logo","You logo you specified cannot be deleted as it is a potential security risk doing so.");
    }

    if (!@unlink("images/news/logos/$logo")) {
      adminerror("Unable To Delete","The logo has not been deleted, most likely because you do not have the correct permissions set on the images/news/logos/ directory to allow the deletion of the files.  Please check the permissions on this directory or delete the file manually.");
    }

    query("UPDATE news_news SET logoimage = '' WHERE logoimage = '$logo'");

  }

  writeallpages();

  echoadminredirect("admin.php?action=newslogo");

break;

default:
  adminerror("Invalid Link","You have followed an invalid link");
}

/*======================================================================*\
|| ####################################################################
|| # File: admin/newslogo.php
|| ####################################################################
\*======================================================================*/

?>