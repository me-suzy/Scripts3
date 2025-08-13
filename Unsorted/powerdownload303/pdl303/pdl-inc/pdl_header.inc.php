<?
// für Leute wo noch auf die in pdl2.2.4 üblichen ?file_id=... verlinkt haben
// ids müssen aber geändert werden... :/
if($_GET[file_id]) $release_id = $_GET[file_id];

// Error Reporting: alle Errors außer Notizen.
error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(E_ALL);

// Zeitmessung
$rendertime1=microtime();
$rendertimetemp=explode(" ",$rendertime1);
$rendertime1=$rendertimetemp[0]+$rendertimetemp[1];

// Include required Files
if(!isset($incdir)) $incdir = "";
require($incdir."pdl-inc/pdl_config.inc.php");
require($incdir."pdl-inc/pdl_db_class_".strtolower($config_sql_type).".inc.php");
require($incdir."pdl-inc/pdl_functions.inc.php");

// Initialize SQL Class
$db_handler = new pdl_db_class;

$db_handler->config_sql_server = $config_sql_server;
$db_handler->config_sql_database = $config_sql_database;
$db_handler->config_sql_user = $config_sql_user;
$db_handler->config_sql_password = $config_sql_password;
$db_handler->config_sql_persistent = $config_sql_persistent;

$db_handler->sql_connect();

$config_sql_password = "";
$db_handler->config_sql_password = "";

// Load Settings
$settings = array();
$settings_res = $db_handler->sql_query("SELECT * FROM $sql_table[settings]");
while($settings_row = $db_handler->sql_fetch_array($settings_res))
 {
  $settings[$settings_row['variablenname']] = $settings_row['wert'];
 }

if(preg_match("/\?/", $settings['script_file']))
 { $settings['script_file'] = $settings['script_file']."&"; }
else
 { $settings['script_file'] = $settings['script_file']."?"; }

$settings['pdlversion'] = "v3.0.3";
$settings['debug'] = false;
$settings['showcopy'] = true;
$settings['phpversion'] = str_replace(".", "", phpversion());

if($settings[ftp_server] == "") $settings[ftp_on] = "N";

// Load Templates
$template = array();
$gettemplate_res = $db_handler->sql_query("SELECT * FROM $sql_table[template]");
while($gettemplate_row = $db_handler->sql_fetch_array($gettemplate_res))
 {
  $template[$gettemplate_row['variablenname']] = $gettemplate_row['wert'];
 }

// Load Users
$users = array();
$users_res = $db_handler->sql_query("SELECT * FROM $sql_table[user]");
while($users_row = $db_handler->sql_fetch_array($users_res))
 {
  $users[$users_row[user_id]][nick] = $users_row[nick];
  $users[$users_row[user_id]][email] = ascii_encode($users_row[email]);
  $users[$users_row[user_id]][icq] = $users_row[icq];
  $users[$users_row[user_id]][homepage] = $users_row[homepage];
 }

// Load Replacements
$smilies = array();
$smilies_res = $db_handler->sql_query("SELECT * FROM $sql_table[replacements] WHERE type='s' ORDER BY LENGTH(old) DESC");
while($smilies_row = $db_handler->sql_fetch_array($smilies_res))
 {
  $smilies[] = array("old" => $smilies_row[old],"neu" => $smilies_row[neu]);
 }

$glossary = array();
$glossary_res = $db_handler->sql_query("SELECT * FROM $sql_table[replacements] WHERE type='g' ORDER BY LENGTH(old) DESC");
while($glossary_row = $db_handler->sql_fetch_array($glossary_res))
 {
  $glossary[] = array("old" => $glossary_row[old],"neu" => $glossary_row[neu]);
 }

$badwords = array();
$badwords_res = $db_handler->sql_query("SELECT * FROM $sql_table[replacements] WHERE type='b' ORDER BY LENGTH(old) DESC");
while($badwords_row = $db_handler->sql_fetch_array($badwords_res))
 {
  $badwords[] = $badwords_row[old];
 }

// IP Lock säubern
$loesch = time()-24*3600;
$db_handler->sql_query("DELETE FROM $sql_table[iplock] WHERE art='vote' AND time<$loesch");
$loesch = time()-60;
$db_handler->sql_query("DELETE FROM $sql_table[iplock] WHERE art='comment' AND time<$loesch");

// register_globals Problem umgehen
if(get_cfg_var('register_globals') == 0)
 {
  extract($HTTP_SERVER_VARS, EXTR_SKIP);
  extract($HTTP_COOKIE_VARS, EXTR_SKIP);
  extract($HTTP_POST_FILES, EXTR_SKIP);
  extract($HTTP_POST_VARS, EXTR_SKIP);
  extract($HTTP_GET_VARS, EXTR_SKIP);
  extract($HTTP_ENV_VARS, EXTR_SKIP);
 }

// andere Vars
$ip = $REMOTE_ADDR;

// Sicherheitslücke schließen:
unset($user_details);
unset($ugroup_id);
unset($user_rights);

// Check Cookie
if($_COOKIE['login_id'] && $_COOKIE['login_pw'])
 {
  $check_res = $db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE user_id='$login_id' AND passwort='$login_pw'");
  $check = $db_handler->sql_num_rows($check_res);
  if($check == 1)
   {
    $user_details = $db_handler->sql_fetch_array($check_res);
   }
  else
   {
    setcookie("login_id","",time() + 8760 * 3600);
    setcookie("login_pw","",time() + 8760 * 3600);
   }
 }
if($user_details)
 {
  $ugroup_id = $user_details['ugroup_id'];
 }
$rights_res = $db_handler->sql_query("SELECT * FROM $sql_table[usergroup] WHERE ugroup_id='$ugroup_id'");
$user_rights = $db_handler->sql_fetch_array($rights_res);

// Login
if($login == 1)
 {
  $pw = md5($pw);
  $check_res = $db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE nick='$nick' AND passwort='$pw'");
  if($db_handler->sql_num_rows($check_res) == 1)
   {
    $login_temp = $db_handler->sql_fetch_array($check_res);
    setcookie("login_id",$login_temp['user_id'],time() + 8760 * 3600);
    setcookie("login_pw",$login_temp['passwort'],time() + 8760 * 3600);
    if(basename($PHP_SELF) == "index.php") header("Location: index.php");
    else header("Location: $settings[script_file]");
   }
  else
   {
    $login_error = true;
   }
 }

// Logout
if($logout == 1)
 {
  setcookie("login_id","",time() + 8760 * 3600);
  setcookie("login_pw","",time() + 8760 * 3600);
  if(basename($PHP_SELF) == "index.php") header("Location: index.php");
  else header("Location: $settings[script_file]");
 }

// Download
if($load_file)
 {
  $file_id = $load_file;
  $dl_allowed = false;
  if($settings['referer_check'] == "Y")
   {
    $all_referer = explode(" ", $settings['allowed_referer']);
    for($i = 0; $i < count($all_referer); $i++)
     {
      if(preg_match("/".$all_referer[$i]."/siU",$HTTP_REFERER))
       {
        $dl_allowed = true;
        break;
       }
     }
   }
  else
   {
    $dl_allowed = true;
   }

  if($dl_allowed == true)
   {
    if($user_rights['download'] == "N") header("Location: $settings[script_file]wrong_rights=1");
    else
     {
      $dl_row = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[files] WHERE file_id='$file_id'"));
      $db_handler->sql_query("UPDATE $sql_table[files] SET downloads=downloads+1 WHERE file_id='$file_id'");
      header("Location: $dl_row[url]");
     }
   }
  else
   {
    header("Location: $settings[script_file]wrong_referer=1");
   }
 }

// individuelles Listen
if($change_list == 1)
 {
  setcookie("pdl_list", "$orderseq###$orderby###$perpage",time() + 8760 * 3600);
  header("Location: $HTTP_REFERER");
 }
if($pdl_list && $inadmin != 1)
 {
  $list_ops = explode("###", $pdl_list);
  $settings[orderseq] = $list_ops[0];
  $settings[orderby] = $list_ops[1];
  $settings[perpage] = $list_ops[2];
 }

$list = "Release sortieren nach
<select name=\"orderby\">
<option value=\"name\">Name</option>
<option value=\"text\"".(($settings[orderby] == "text") ? " selected" : "").">Beschreibung</option>
<option value=\"time\"".(($settings[orderby] == "time") ? " selected" : "").">Uploaddatum</option>
<option value=\"views\"".(($settings[orderby] == "views") ? " selected" : "").">Views</option>
<option value=\"votes\"".(($settings[orderby] == "votes") ? " selected" : "").">Bewertungen</option>
<option value=\"voted/votes\"".(($settings[orderby] == "voted/votes") ? " selected" : "").">Wertung</option>
</select>
in <select name=\"orderseq\">
<option value=\"ASC\">aufsteigender</option>
<option value=\"DESC\"".(($settings[orderseq] == "DESC") ? " selected" : "")."
>absteigender</option></select> Reihenfolge mit
<input type=\"text\" size=\"2\" name=\"perpage\" value=\"$settings[perpage]\">
Releasen auf einer Seite <input type=\"submit\" value=\"GO\">";
?>
