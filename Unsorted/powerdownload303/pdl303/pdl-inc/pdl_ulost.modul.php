<?
if($submit == 1)
 {
  $getuser = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE email='$email'"));
  $remind_code = md5(generate_string(16));
  $db_handler->sql_query("UPDATE $sql_table[user] SET remind_code='$remind_code' WHERE user_id='$getuser[user_id]'");

  $message = str_replace("{user}", $getuser[nick], $template[mail_lost1]);
  $message = str_replace("{url}", "$settings[script_file]usercenter=lost2&remind_code=$remind_code", $message);
  if($getuser)
   {
    mail("$email", "Password Bestätigungscode", $message, "FROM: $settings[mail_fromname] <$settings[mail_fromaddr]>");
    echo "<center><b>Bestätigungsmail versendet.</b></center>";
   }
  else
   {
    echo "<center><b>Kein Benutzer mit dieser E-Mail Adresse gefunden.</b></center>";
   }
 }
else
 {
  echo "
  <form name=\"lost\" method=\"post\" action=\"".$settings[script_file]."usercenter=lost&submit=1\">
  ".replace($template[ulost_form],"")."
  </form>";
 }
?>
