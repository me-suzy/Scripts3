<?
$getuser = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[user] WHERE remind_code='$remind_code'"));
$pw_new = generate_string(16);
$pw_md5 = md5($pw_new);
$db_handler->sql_query("UPDATE $sql_table[user] SET passwort='$pw_md5' WHERE user_id='$getuser[user_id]'");

$message = str_replace("{user}", $getuser[nick], $template[mail_lost2]);
$message = str_replace("{new_pw}", $pw_new, $message);

if($getuser)
 {
  mail("$getuser[email]", "Accountdaten", $message, "FROM: $settings[mail_fromname] <$settings[mail_fromaddr]>");
  echo "<center><b>Accountdaten versendet.</b></center>";
 }
else
 {
  echo "<center><b>Kein Benutzer mit diesem Bestätigungcode gefunden.</b></center>";
 }
?>
