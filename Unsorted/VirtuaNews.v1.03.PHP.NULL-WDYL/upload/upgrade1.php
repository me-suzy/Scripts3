<?php


error_reporting(7);

require("includes/getglobals.php");

$oldversion = "1.0.0 Pro";
$newversion = "1.0.1 Pro";

if (function_exists("set_time_limit") == 1) {
  @set_time_limit(1200);
}

settype($step,"integer");

if (!$step) {
  $step = 1;
}

function undoescapetext($string="") {
  $string = str_replace("||| ||","|||||",$string);
  $string = substr($string,1,-1);

  return $string;
}

include("admin/config.php");
include("includes/db_$dbservertype.php");
$db = vn_connect();

?>
<html>
<head>
<title>Upgrade VirtuaNews From <?php echo $oldversion?> To <?php echo $newversion?></title>

<link rel="StyleSheet" href="admin/style.css" type="text/css">
</head>
<body>
<table cellspacing="0" cellpadding="2" class="header">
  <tr>
    <td class="header">Welcome to the VirtuaNews upgrade process</td>
  </tr>
</table>
<table cellpadding="2" cellspacing="0" class="main">
<?

if ($step == "1") {

  echo "  <tr>\n    <td>Running this upgrade script will upgrade you previous version of VirtuaNews $oldversion to VirtuaNews $newversion.  If you do not have the old version installed you should exit this script immediatly.</td>\n  </tr>\n";
  echo "  <tr>\n    <td>Click <a href=\"upgrade1.php?step=2\">here</a> to start the upgrade process</td>\n  </tr>\n";

}

if ($step == 2) {

  query("UPDATE news_option SET value = '$newversion' WHERE varname = 'version'");
  query("UPDATE news_style SET value = 'utf-8' WHERE (stylesetid = '-1') AND (varname = 'charset')");

  echo "  <tr>\n    <td>Database upgrade complete........Updating default templates;</td>\n  </tr>\n";

  if (!file_exists("admin/virtuanews.set")) {
    echo "  <tr>\n    <td>Cannot find the template file.  Please ensure it is uploaded to admin/virtuanews.set  When you are sure it is uploaded correctly please refresh this page and continue</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $filesize = @filesize("admin/virtuanews.set");

  $fp = @fopen("admin/virtuanews.set","r");
  $filecontent = @fread($fp,$filesize);
  @fclose($fp);

  if (trim($filecontent) == "") {
    echo "  <tr>\n    <td>The template file is not the virtuanews master file, you must upload the master file and then refresh this page to continue.</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $data_arr = explode("|||||",$filecontent);

  unset($filecontent);

  foreach ($data_arr AS $data) {
    $count ++;
    if ($count%2 == 1) {
      $info[$data] = "";
      $last_info = $data;
    } else {
      $info[$last_info] = $data;
    }
  }
  unset($data_arr);

  if (($info[' theme title'] == "!!MASTER!!") & ($info[' page set'] == "!!MASTER!!") & ($info[' style set'] == "!!MASTER!!")) {
    $is_master = 1;
  } else {
    echo "  <tr>\n    <td>The template file is not the virtuanews master file, you must upload the master file and then refresh this page to continue.</td>\n  </tr>\n";
    echo "</body>\n</html>";
    exit;
  }

  $pagesetid = "-1";
  $stylesetid = "-1";

  unset($info[' version']);
  unset($info[' theme title']);
  unset($info[' style set']);
  unset($info[' page set']);

  foreach($info AS $title => $data) {

    $data = undoescapetext($data);

    if (substr($title,0,9) == " page !!!") {
      $name = substr($title,9);
      if ($name != "") {
        if ($file = @fopen("pages/default/".$name.".vnp","w")) {
          @fwrite($file,$data);
          @$file = fclose($file);
        } else {
          $error = 1;
        }
      }
    }
  }

  if ($error) {
      echo "  <tr>\n    <td>There has been an error inserting one or more of the records.</td>\n  </tr>\n";
  } else {
      echo "  <tr>\n    <td>All templates inserted correctly......Updating static files;</tr>\n";
  }

  $get_options = query("SELECT varname,value FROM news_option WHERE varname IN ('use_forum','forumpath','defaultcat_loggedout')");
  while ($option_arr = fetch_array($get_options)) {
    ${$option_arr[varname]} = $option_arr[value];
  }

  include("includes/functions.php");

  if (!empty($use_forum)) {
    include("includes/forum_".strtolower(trim($use_forum)).".php");
  } else {
    include("includes/forum_vn.php");
  }

  include("includes/adminfunctions.php");
  include("includes/writefunctions.php");

  saveoptions();
  $cat_arr = getcat_arr();
  $theme_arr = getthemearr();
  $timeoffset = $timeoffset * 3600;
  $defaultcategory = $defaultcat_loggedout;

  $themeid = 1;
  $pagesetid = 1;

  writeallpages();

  echo "  <tr>\n    <td>Upgrade complete.  Please ensure that you delete this file and the install.php file before you continue.</td>\n  </tr>\n";

}
?>
</table>
</body>
</html>