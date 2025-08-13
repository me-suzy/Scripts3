<?
// Rechnet alle Subordner und Subfiles aus
function sub($ordner_id)
 {
  global $subfiles,$subdirs,$db_handler,$sql_table;
  $subfiles += $db_handler->sql_num_rows($db_handler->sql_query("SELECT release_id FROM $sql_table[release] WHERE ordner_id='$ordner_id' AND released='Y'"));
  $sordner_res = $db_handler->sql_query("SELECT ordner_id FROM $sql_table[ordner] WHERE sordner_id='$ordner_id'");
  $subdirs += $db_handler->sql_num_rows($sordner_res);
  while($sordner_row = $db_handler->sql_fetch_array($sordner_res))
   {
    sub($sordner_row[ordner_id]);
   }
 }

$files_check = $db_handler->sql_num_rows($db_handler->sql_query("SELECT release_id FROM $sql_table[release] WHERE ordner_id='$ordner_id' AND released='Y'"));
$ordner_check = $db_handler->sql_num_rows($db_handler->sql_query("SELECT ordner_id FROM $sql_table[ordner] WHERE sordner_id='$ordner_id'"));

if($files_check == 0 && $ordner_check == 0)
 {
  echo "Dieser Ordner ist leer.";
 }
else
 {
  if($ordner_check != 0)
   {
    $ordner_rows = "";
    $ordner_res = $db_handler->sql_query("SELECT * FROM $sql_table[ordner] WHERE sordner_id='$ordner_id' ORDER BY name ASC");
    while($ordner_row = $db_handler->sql_fetch_array($ordner_res))
     {
      $subfiles = 0;
      $subdirs = 0;
      sub($ordner_row[ordner_id]);
      $ordner_row[files] = $subfiles;
      $ordner_row[subdirs] = $subdirs;
      $ordner_row[id] = $ordner_row[ordner_id];
      $ordner_row[name] = stripslashes($ordner_row[name]);
      $ordner_row[text] = stripslashes($ordner_row[text]);

      $ordner_rows .= replace($template[ordner_row], $ordner_row);
     }

    echo replace($template[ordner_box], $ordner_rows);
   }
  if($files_check != 0)
   {
    $temp1=$page * $settings[perpage] - $settings[perpage];
    $limit=$temp1.",".$settings[perpage];
    $total = $db_handler->sql_num_rows($db_handler->sql_query("SELECT * FROM $sql_table[release] WHERE ordner_id='$ordner_id'"));

    echo "<center>".seiten($total,$settings[perpage],"&ordner_id=$ordner_id",$settings[script_file])."</center>";
    echo "<form action=\"$settings[script_file]change_list=1\" method=\"post\">";

    $release_rows = "";
    $files_res = $db_handler->sql_query("SELECT * FROM $sql_table[release] WHERE ordner_id='$ordner_id' AND released='Y' ORDER BY $settings[orderby] $settings[orderseq] LIMIT $limit");
    while($files_row = $db_handler->sql_fetch_array($files_res))
     {
      $size = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT SUM(size) AS tsize FROM $sql_table[files] WHERE release_id='$files_row[release_id]' AND mirror='0'"));
      $files_row[size] = $size[tsize];
      $files_row[id] = $files_row[release_id];

      $files_row[name] = stripslashes($files_row[name]);
      $files_row[text] = stripslashes($files_row[text]);
      if(!$files_row[text])
       { $files_row[text] = "N/A"; }
      elseif($settings[trenn_durch] == "zeichen")
       {
        $files_row[text] = str_replace($settings[trenn_string],"",$files_row[text]);
        if(strlen($files_row[text]) <= $settings[trenn_zeichen])
         { $files_row[text] = $files_row[text]; }
        else
         { $files_row[text] = substr($files_row[text],0,$settings[trenn_zeichen])."..."; }
       }
      elseif($settings[trenn_durch] == "string")
       {
        $text = explode($settings[trenn_string], $files_row[text]);
        $files_row[text] = $text[0];
       }
      if($files_row[text] != "N/A") $files_row[text] = bbcode($files_row[text],$settings[badwords_releases],$settings[smilies],$settings[glossary],$settings[bb_code],$settings[html_releases]);

      $release_rows .= replace($template[release_row], $files_row);
     }

    echo replace($template[release_box], $release_rows)."</form>";
   }
 }
?>
