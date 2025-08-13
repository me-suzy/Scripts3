<?
$release_res = $db_handler->sql_query("SELECT SUM(downloads) AS downloads, SUM(size) AS size, release.* FROM $sql_table[files] AS files, $sql_table[release] AS release WHERE release.release_id=files.release_id AND release.released='Y' GROUP BY release_id ORDER BY voted/votes DESC LIMIT 0,$settings[top_count]");
$count = 0;
$total = $db_handler->sql_num_rows($release_res);

$usetext = false;
if(preg_match("/\{text\}/",$template[rated_row])) $usetext = true;

$release_rows = "";
while($release_row = $db_handler->sql_fetch_array($release_res))
 {
  $count++;
  $release_row[count] = $count;
  $release_row[id] = $release_row[release_id];
  $release_row[name] = stripslashes($release_row[name]);
  if($settings['shortname'] > 0)
   {
    if(strlen($release_row['name']) > $settings['shortname'])
     {
      $release_row['name'] = substr($release_row['name'],0,$settings['shortname']-3)."...";
     }
   }

  if($usetext == true)
   {
    $release_row[text] = stripslashes($release_row[text]);
    if(!$release_row[text])
     { $release_row[text] = "N/A"; }
    elseif($settings[trenn_durch] == "zeichen")
     {
      $release_row[text] = str_replace($settings[trenn_string],"",$release_row[text]);
      if(strlen($release_row[text]) <= $settings[trenn_zeichen])
       { $release_row[text] = $release_row[text]; }
      else
       { $release_row[text] = substr($release_row[text],0,$settings[trenn_zeichen])."..."; }
     }
    elseif($settings[trenn_durch] == "string")
     {
      $text = explode($settings[trenn_string], $release_row[text]);
      $release_row[text] = $text[0];
     }
    if($release_row[text] != "N/A") $release_row[text] = bbcode($release_row[text],$settings[badwords_releases],$settings[smilies],$settings[glossary],$settings[bb_code],$settings[html_releases]);
   }

  if($release_row[votes] > 0) $release_row[vote] = round($release_row[voted]/$release_row[votes],1);
  else $release_row[vote] = 0;

  $release_rows.= replace($template[rated_row], $release_row);
 }

echo replace($template[rated_box], $release_rows);
?>
