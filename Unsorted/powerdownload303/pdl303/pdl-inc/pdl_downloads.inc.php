<?
if(!$ordner_id) $ordner_id = 0;
if(!$page) $page = 1;

// Äußere Tabelle
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"$template[all_width]\"><tr><td>";

// Treeview oder Alternative

if($ordner_id == 0) $isindex = true;
$sordner_id = $ordner_id;
if($release_id || $screen_id)
 {
  if($screen_id)
   {
    $screen = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT release_id FROM $sql_table[screens] WHERE screen_id='$screen_id'"));
    $release_id = $screen[release_id];
   }
  $release = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[release] WHERE release_id='$release_id'"));
  $sordner_id = $release[ordner_id];
  $isindex = false;
 }

if($wrong_referer || $wrong_rights || $usercenter || $show_search || $show_stats) $isindex = false;

echo "
<table border=\"0\" width=\"100%\">
  <tr>
    <td>";
if($settings[enable_treeview] == "Y")
 {
  if($isindex == true)
   { echo "<img src=\"pdl-gfx/folder_open.gif\" border=\"0\"> Index<br>"; }
  else
   { echo "<img src=\"pdl-gfx/folder.gif\" border=\"0\"> <a href=\"".$settings[script_file]."ordner_id=0\">Index</a><br>"; }
  treeview_ordner(0,"");
 }
else
 {
  $ordner_id = $sordner_id;
  if($isindex == true)
   { echo "Index"; }
  elseif($isindex == false && $ordner_id == 0)
   { echo "<a href=\"".$settings[script_file]."ordner_id=0\">Index</a>"; }
  else
   { treeview_pfeil($sordner_id); }
  if($release && !$screen_id) echo " &raquo; ".stripslashes($release[name]);
  elseif($screen_id) echo " &raquo; <a href=\"$settings[script_file]release_id=$release[release_id]\">".stripslashes($release[name])."</a> &raquo; Screenshot";
 }
echo "
    </td>
    <td align=\"right\" valign=\"top\">
      <a href=\"$settings[script_file]\">Home</a>
      - <a href=\"$settings[script_file]show_stats=1\">Statistik</a>
      - <a href=\"$settings[script_file]show_search=1\">Suche</a> ";
      if(!$user_details) echo "- <a href=\"$settings[script_file]usercenter=login\">Login</a> ";
      if(!$user_details) echo "- <a href=\"$settings[script_file]usercenter=register\">Anmelden</a> ";
      if($user_details) echo "- <a href=\"$settings[script_file]usercenter=profil\">Profil</a> ";
      if($user_rights[adminaccess] == "Y") echo "- <a href=\"pdl-admin/\">Admin Center</a> ";
      if($user_details) echo "- <a href=\"$settings[script_file]logout=1\">Logout</a> ";
echo "
    </td>
  </tr>
</table>
<br>";

// Module Includen
if($screen_id) include("pdl-inc/pdl_showscreen.modul.php");
elseif($usercenter) include("pdl-inc/pdl_u".strtolower($usercenter).".modul.php");
elseif($release_id) include("pdl-inc/pdl_release.modul.php");
elseif($show_search) include("pdl-inc/pdl_search.modul.php");
elseif($show_stats) include("pdl-inc/pdl_stats.modul.php");
elseif($wrong_referer) echo "<center><b>Es wurde illegal auf die Datei verlinkt.</b></center>";
elseif($wrong_rights) echo "<center><b>Sie haben keine Berechtigung eine Datei zu downloaden.</b></center>";
else include("pdl-inc/pdl_ordner.modul.php");

// Admin Links
if($settings[enable_extrernadmin] == "Y" && $user_rights[adminaccess] == "Y")
 {
  if($release_id AND !$screen_id)
   {
    if($user_rights[editfiles] == "Y" && $user_rights[delfiles] == "Y")
     {
      echo "<div align=\"right\"><select name=\"admin\" onchange=\"window.location=('pdl-admin/'+this.options[this.selectedIndex].value)\">
      <option value=\"\">Admin optionen</option>";
      if($user_rights[editfiles] == "Y") echo "<option value=\"editrelease.php?release_id=$release_id\">Release Editieren</option>";
      if($user_rights[editfiles] == "Y") echo "<option value=\"addfile.php?release_id=$release_id\">Datei hinzufügen</option>";
      if($user_rights[editfiles] == "Y") echo "<option value=\"addscreen.php?release_id=$release_id\">Screenshot hochladen</option>";
      if($user_rights[delfiles] == "Y") echo "<option value=\"delrelease.php?release_id=$release_id\">Release Löschen</option>";
      echo "</select></div>";
     }
   }
  else
   {
    if(!$screen_id AND !$usercenter AND !$wrong_referer AND !$wrong_rights AND !$show_search AND !$show_stats)
     {
      echo "<div align=\"right\"><select name=\"admin\" onchange=\"window.location=('pdl-admin/'+this.options[this.selectedIndex].value)\">
      <option value=\"\">Admin optionen</option>
      <option value=\"addfile.php?ordner_id=$ordner_id\">Datei Adden</option>";
      if($user_rights[adddirs] == "Y") echo "<option value=\"adddir.php?ordner_id=$ordner_id\">Sub-Ordner Adden</option>";
      if($user_rights[editdirs] == "Y" && $ordner_id != 0) echo "<option value=\"editdirs.php?ordner_id=$ordner_id\">Ordner Editieren</option>";
      if($user_rights[deldirs] == "Y" && $ordner_id != 0) echo "<option value=\"deldirs.php?ordner_id=$ordner_id\">Ordner Löschen</option>";
      echo "</select></div>";
     }
   }
 }

// Copyright und Schließen der Äußeren Tabelle
echo "<br><center>";
if($settings[debug] == true)
 {
  $rendertime2=microtime();
  $rendertimetemp=explode(" ",$rendertime2);
  $rendertime2=$rendertimetemp[0]+$rendertimetemp[1];
  $rendertime=$rendertime2-$rendertime1;
  $rendertime=round($rendertime,3);
  echo "Renderzeit: ".$rendertime."s; ".$db_handler->querys." SQL Anfragen<br>";
 }
if($settings[showcopy] == true) echo "PowerDownload $settings[pdlversion] &copy; 2001-2002 by <a href=\"http://www.powerscripts.org\" target=\"_blank\">PowerScripts</a>";
echo "</center>
</td></tr></table>";

?>
