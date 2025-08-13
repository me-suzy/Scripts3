<?
if($user_rights[addcomments] == "Y" && $settings[enable_comments] == "Y")
 {
  if($submit == 1)
   {
    if(!$titel || !$text) echo "<br><center>Bitte Titel und Text eingeben.<br><a href=\"javascript:history.back()\">Zurück</a></center>";
    else
     {
      $db_handler->sql_query("INSERT INTO $sql_table[comments] (user_id,release_id,titel,text,time) VALUES ('$user_details[user_id]','$release_id','".addslashes($titel)."','".addslashes($text)."','".time()."')");
      echo "<br><center>Ihr Kommentar wurde gepostet.<br><a href=\"$settings[script_file]release_id=$release_id\">Zurück zum Release</a></center>";
     }
   }
  else
   {
    if($settings[html_comments] == "Y") $html = "An"; else $html = "Aus";
    if($settings[badwords_comments] == "Y") $zensur = "An"; else $zensur = "Aus";
    if($settings[bb_code] == "Y") $bbcode = "An"; else $bbcode = "Aus";
    if($settings[smilies] == "Y") $smilies = "An"; else $smilies = "Aus";
    if($settings[glossary] == "Y") $glossar = "An"; else $glossar = "Aus";

    if($user_details) $user = $user_details[nick];
    else $user = "Gast - <a href=\"$settings[script_file]usercenter=login\">Login</a> - <a href=\"$settings[script_file]usercenter=register\">Anmelden</a>";

    $form = str_replace("{html}",$html,$template[comments_form]);
    $form = str_replace("{zensur}",$zensur,$form);
    $form = str_replace("{bbcode}",$bbcode,$form);
    $form = str_replace("{smilies}",$smilies,$form);
    $form = str_replace("{glossar}",$glossar,$form);
    $form = str_replace("{user}",$user,$form);

    echo "<form action=\"$settings[script_file]usercenter=comments&submit=1&release_id=$release_id\" method=\"post\">".replace($form,"")."</form>";
   }
 }
else
 { echo "<br><center>Sie haben keine Rechte ein Kommentar zu posten. Ihnen oder ihrer Benutzergruppe wurde das Recht entzogen oder die Kommentare sind Global ausgeschaltet.</center>"; }
?>
