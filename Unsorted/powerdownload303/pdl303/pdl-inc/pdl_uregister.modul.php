<?
if($submit == 1)
 {
  $error = false;
  if(!$nick)
   {
    echo "<center><b>Bitte geben sie einen Nickname an.<br><a href=\"javascript:history.back()\">Zurück</a></b></center>";
   }
  elseif(!$email)
   {
    echo "<center><b>Bitte geben sie eine Email Adresse an.<br><a href=\"javascript:history.back()\">Zurück</a></b></center>";
   }
  elseif(($pw_new != $pw_new2) || (!$pw_new || !$pw_new2))
   {
    echo "<center><b>Es ist kein Passwort eingegeben oder es stimmt nicht mit der Bestätigung überein.<br><a href=\"javascript:history.back()\">Zurück</a></b></center>";
   }
  elseif($db_handler->sql_num_rows($db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE nick='$nick'")) > 0)
   {
    echo "<center><b>Es ist bereits ein User mit diesem Nick registriert. Doppelanmeldungen werden nicht geduldet.<br><a href=\"javascript:history.back()\">Zurück</a></b></center>";
   }
  elseif($db_handler->sql_num_rows($db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE email='$email'")) > 0)
   {
    echo "<center><b>Es ist bereits ein User mit dieser Email Adresse registriert. Doppelanmeldungen werden nicht geduldet.<br><a href=\"javascript:history.back()\">Zurück</a></b></center>";
   }
  else
   {
    if(!preg_match("!http:\/\/!",$homepage)) $homepage = "http://$homepage";
    if($get_letter != "Y") $get_letter = "N";
    $db_handler->sql_query("INSERT INTO $sql_table[user] (nick,email,passwort,homepage,icq,get_letter,ugroup_id,lastactive) VALUES ('$nick','$email','".md5($pw_new)."','$homepage','$icq','$get_letter','2','".time()."')");
    echo "<center><b>Anmeldung erfolgreich. Sie können sich nun mit den Daten <a href=\"$settings[script_file]usercenter=login\">Einloggen</a>. Sie erhalten auch eine Bestätigung per Email.</b></center>";

    $message = str_replace("{nick}", $nick, $template[mail_register]);
    $message = str_replace("{pw}", $pw_new, $message);
    $message = str_replace("{script_file}", $settings[script_file], $message);
    mail("$email", "Anmeldung", $message, "FROM: $settings[mail_fromname] <$settings[mail_fromaddr]>");
   }
 }
else
 {
  if($user_details)
   {
    echo "<center><b>Sie sind bereits angemeldet und eingeloggt. Doppelanmeldungen werden nicht geduldet.</b></center>";
   }
  else
   {
    echo "<form action=\"$settings[script_file]usercenter=register&submit=1\" method=\"post\">".replace($template[uregister_form], "")."</form>";
   }
 }
?>
