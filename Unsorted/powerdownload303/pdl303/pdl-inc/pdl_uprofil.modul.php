<?
if($submit == 1)
 {
  if(md5($pw_old) == $user_details[passwort])
   {
    if($pw_new)
     {
      if($pw_new == $pw_new2)
       {
        $pw_new = md5($pw_new);
        if(!preg_match("!http:\/\/!",$homepage)) $homepage = "http://$homepage";
        if($get_letter != "Y") $get_letter = "N";
        $db_handler->sql_query("UPDATE $sql_table[user] SET email='$email', get_letter='$get_letter', homepage='$homepage', icq='$icq', passwort='$pw_new' WHERE user_id='$user_details[user_id]'");
        echo "<center><b>Profil erfolgreich geändert. Da das Passwort geändert wurde müssen sie sich neu <a href=\"$settings[script_file]usercenter=login\">Einloggen</a>.</b></center>";
       }
      else
       {
        echo "<center><b>Neues Passwort stimmt nicht mit der Bestätigung überein.</b></center>";
       }
     }
    else
     {
      if(!preg_match("!http:\/\/!",$homepage)) $homepage = "http://$homepage";
      if($get_letter != "Y") $get_letter = "N";
      $db_handler->sql_query("UPDATE $sql_table[user] SET email='$email', get_letter='$get_letter', homepage='$homepage', icq='$icq' WHERE user_id='$user_details[user_id]'");
      echo "<center><b>Profil erfolgreich geändert.</b></center>";
     }
   }
  else
   {
    echo "<center><b>Altes Passwort ist falsch.</b></center>";
   }
 }
else
 {
  if(!$user_details) echo "hilfe!!!";
  $form = str_replace("{email}", $user_details[email], $template[uprofil_form]);
  if($user_details[getletter] == "Y") $get_letter = " checked";
  $form = str_replace("{get_letter}", $get_letter, $form);
  $form = str_replace("{homepage}", $user_details[homepage], $form);
  if($user_details[icq] > 0) $icq = $user_details[icq];
  $form = str_replace("{icq}", $icq, $form);
  echo "<form action=\"$settings[script_file]usercenter=profil&submit=1\" method=\"post\">".replace($form, "")."</form>";
 }
?>
