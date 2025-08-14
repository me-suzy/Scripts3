<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + template.inc.php - read template and define output function
// + Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// +
// + Release v4.2.0:	06.04.2005 - Daniel Sokoll
// + Last Changes:	19.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(!isset($id)) $id = (isset($_GET["id"])?$_GET["id"]:"");
$show = (isset($show)?$show:"login");
// --- read template ---
if(isset($_GET["print"])) $tpl_to_use = "print";
include_once($tpl_dir.$tpl_to_use."/template.conf.php");

$location = $tpl_dir.$tpl_to_use."/statistics.html";
$newfile = fopen($location,"r");
$template = stripslashes(fread($newfile, filesize($location)));
fclose($newfile);

$template = str_replace("<%TPLPATH%>",$tpl_dir.$tpl_to_use."/",$template);
$template = str_replace("<%VERSION%>",$showlog_ver,$template);
$template = str_replace("<%LANGUAGECODE%>",$lang_code,$template);
$template = str_replace("<%REFRESH%>",($show=="dellog" || $show=="dellast" || $show=="maintain"?"<meta http-equiv=\"Refresh\" content=\"5;URL=".$_SERVER["PHP_SELF"].($show=="dellog"?"":"?show=logfile").($login_require?($show=="dellog"?"?":"&amp;")."id=".$id:"")."\">":""),$template);
$layout = explode("<%CONTENT%>",$template);

// --- show header (top) or footer (bottom) of the document -----------------------------
// --- input paremeters: $what  ->  values: top / bottom
function show_document($what) {
  global $layout;

  if($what == "top") { 
    echo $layout[0];
  } elseif($what == "bottom") {
    echo $layout[1];
  }
}

// --- show horizontal line in head-color ---------------------------------------------------
// --- input paremeters: $width  ->  values: any width format
function show_hr($width="100%") { 
  global $pic; ?>
<table border="0" cellspacing="0" cellpadding="0" width="<?php echo $width; ?>" summary="header of SHOWlog-statistic">
<tr><td><img src="<?php echo $pic["fake"]; ?>" width="2" height="2" alt=" "></td></tr>
<tr><td class="head"><img src="<?php echo $pic["fake"]; ?>" width="2" height="2" alt=" "></td></tr>
<tr><td><img src="<?php echo $pic["fake"]; ?>" width="2" height="2" alt=" "></td></tr>
</table>
<?php
}

// --- session control: initialize session -------------------------------------------------
// --- output parameters: session id
function session_control() {
  ini_set("session.use_cookies", 0);
  ini_set("session.use_trans_sid", 0);
  ini_set("url_rewriter.tags", "");
  session_name('id');
  session_start();
  return session_id();
} ?>