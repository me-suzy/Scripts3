<?
// Vote eintragen
$iplocked = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[iplock] WHERE file_id='$release_id' AND ip='$ip' AND art='vote'"));
if($vote == 1 && $iplocked == 0 && $vote_id)
 {
  $db_handler->sql_query("INSERT INTO $sql_table[iplock] VALUES ('$ip', '".time()."', '$release_id', '$user_details[user_id]', 'vote')");
  $db_handler->sql_query("UPDATE $sql_table[release] SET votes=votes+1, voted=voted+$vote_id WHERE release_id='$release_id'");
  $iplocked = 1;
 }

// Release Daten auslesen
$release_row = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[release] WHERE release_id='$release_id'"));

if($release_row[released] == "N") // Release versteckt?
 { echo "Der Release ist zwar vorhanden aber versteckt und kann deswegen nicht angesehen werden."; }
else
 {
// Views erhöhen
  $db_handler->sql_query("UPDATE $sql_table[release] SET views=views+1 WHERE release_id='$release_id'");

// Release ausgeben
  echo "<form action=\"".$settings[script_file]."release_id=$release_id&vote=1\" method=\"post\">";

  $files_res = $db_handler->sql_query("SELECT * FROM $sql_table[files] WHERE release_id='$release_id'");
  // Hack: erste Datei in das Release Template mit einbeziehen
  $fileone = $db_handler->sql_fetch_array($files_res);
  $release_row[id] = $fileone[file_id];
  $release_row[filename] = basename($fileone[url]);

  $files_res = $db_handler->sql_query("SELECT * FROM $sql_table[files] WHERE release_id='$release_id'");
  $files = "";
  $total_files = $db_handler->sql_num_rows($files_res);
  $total_downloads = 0;
  while($files_row = $db_handler->sql_fetch_array($files_res))
   {
    $files_row[id] = $files_row[file_id];
    $files_row[traffic] = $files_row[size]*$files_row[downloads];
    $files_row[size] = $files_row[size];
    if($files_row[mirror] > 0)
     {
      $mirror_of = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[files] WHERE file_id='$files_row[mirror]'"));
      $files_row[size] = $mirror_of[size];
      $files_row[traffic] = $mirror_of[size]*$files_row[downloads];
     }
    else $total_size += $files_row[size];
    $total_traffic += $files_row[traffic];
    $total_downloads += $files_row[downloads];

    $files_row[filename] = basename($files_row[url]);
    $files .= replace($template[dfiles_row], $files_row);
   }

  $release_row[files] = $files;
  if($release_row[votes] > 0) $release_row[vote] = round($release_row[voted]/$release_row[votes],1);
  else $release_row[vote] = 0;

  if($iplocked == 0 && $user_rights[vote] == "Y")
   {
    $release_row[vote_form] = "
    <br>Bewerten:
    <select name=\"vote_id\">
    <option value=\"10\">10 Sehr gut</option>
    <option value=\"9\">9</option>
    <option value=\"8\">8</option>
    <option value=\"7\">7</option>
    <option value=\"6\">6</option>
    <option value=\"5\">5</option>
    <option value=\"4\">4</option>
    <option value=\"3\">3</option>
    <option value=\"2\">2</option>
    <option value=\"1\">1 Sehr schlecht</option>
    </select>
    <input type=\"submit\" value=\"Vote!\">";
   }
  if($user_rights[vote] == "N") $release_row[vote_form] = "<br>Sie haben keine Berechtigung den Download zu bewerten.";

  $template[file_detail] = str_replace("{total_size}", size($total_size), $template[file_detail]);
  $template[file_detail] = str_replace("{total_traffic}", size($total_traffic), $template[file_detail]);
  $template[file_detail] = str_replace("{total_downloads}", $total_downloads, $template[file_detail]);
  $template[file_detail] = str_replace("{total_files}", $total_files, $template[file_detail]);
  $template[file_detail] = str_replace("{dlspeed}", dlspeed($total_size), $template[file_detail]);
  $template[file_detail] = str_replace("{speed}", $settings[dlspeed], $template[file_detail]);

  if($release_row[autor] == 0)
   {
    if($release_row[autor_email]) $autor = "<a href=\"mailto:".ascii_encode($release_row[autor_email])."\">$release_row[autor_nick]</a>";
    else $autor = $release_row[autor_nick];
    if($release_row[autor_icq] > 0) $autor .= " <a href=\"http://wwp.icq.com/scripts/search.dll?to=$release_row[autor_icq]\"><img src=\"http://wwp.icq.com/scripts/online.dll?icq=$release_row[autor_icq]&img=5\" border=\"0\"></a>";
    if($release_row[autor_homepage]) $autor .= " <a href=\"$release_row[autor_homepage]\"><img src=\"pdl-gfx/www.gif\" border=\"0\"></a>";
   }
  elseif($release_row[autor] == -1) $autor = "Unbekannt";
  else $autor = user($release_row[autor]);
  $release_row[autor] = $autor;

  $release_row[name] = stripslashes($release_row[name]);
  $release_row[text] = stripslashes($release_row[text]);
  if(!$release_row[text]) $release_row[text] = "N/A";
  else $release_row[text] = str_replace($settings[trenn_string],"",$release_row[text]);
  if($release_row[text] != "N/A") $release_row[text] = bbcode($release_row[text],$settings[badwords_releases],$settings[smilies],$settings[glossary],$settings[bb_code],$settings[html_releases]);

  $screens_res = $db_handler->sql_query("SELECT * FROM $sql_table[screens] WHERE release_id='$release_id'");
  if($db_handler->sql_num_rows($screens_res) == 0)
   { $screens = "keine Screens vorhanden..."; }
  else
   {
    while($screens_row = $db_handler->sql_fetch_array($screens_res))
     {
      $screens .= " <a href=\"$settings[script_file]screen_id=$screens_row[screen_id]\"><img src=\"pdl-gfx/screens/release".$release_id."screen".$screens_row[screen_id]."k.jpg\" border=\"0\"></a> ";
     }
   }
  $release_row[screens] = $screens;
  echo replace($template[file_detail], $release_row);
  echo "</form>
  $template[own_footer]<br><br>";
  if($settings[enable_comments] == "Y")
   {
    echo "<center><b>Kommentare</b><br>";
    $comments_res = $db_handler->sql_query("SELECT * FROM $sql_table[comments] WHERE release_id='$release_id' ORDER BY time DESC");
    $comments_num = $db_handler->sql_num_rows($comments_res);

    if(!$showcomments) $showcomments = 0;
    if($showcomments == 1) echo "<a href=\"$settings[script_file]release_id=$release_id\">Kommentare ($comments_num) verstecken</a>";
    else echo "<a href=\"$settings[script_file]release_id=$release_id&showcomments=1\">Kommentare ($comments_num) zeigen</a>";
    echo " - ";
    if($user_details) echo "<a href=\"$settings[script_file]usercenter=comments&release_id=$release_id\">Kommentar schreiben</a>";
    else
     {
      echo "
      <a href=\"$settings[script_file]usercenter=register\">Anmelden</a> -
      <a href=\"$settings[script_file]usercenter=login\">Einloggen</a> -
      <a href=\"$settings[script_file]usercenter=comments&release_id=$release_id\">Anonym Posten</a>
      ";
     }
    echo "<br>";

    if($showcomments == 1)
     {
      while($comments_row = $db_handler->sql_fetch_array($comments_res))
       {
        if($comments_row[user_id] == 0) $comments_row[autor] = "Gast";
        else $comments_row[autor] = user($comments_row[user_id]);
        $comments_row[titel] = stripslashes($comments_row[titel]);
        $comments_row[text] = stripslashes($comments_row[text]);
        $comments_row[text] = bbcode($comments_row[text],$settings[badwords_comments],$settings[smilies],$settings[glossary],$settings[bb_code],$settings[html_comments]);

        echo replace($template[comments],$comments_row);
       }
     }
    echo "</center>";
   }
 }
?>
