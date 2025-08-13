<?
// SQL Zugangsdaten

$config_sql_server = "localhost";// SQL Server
$config_sql_database = "pdl3";   // SQL Datenbank
$config_sql_user = "root";       // SQL Benutzer
$config_sql_password = "";       // SQL Passwort
$config_sql_persistent = false;  // Soll eine Persistente Verbindung aufgebaut werden?
$config_sql_type = "MySQL";      // SQL Typ. Im Release ist nur MySQL enthalten aber man
                                 // kann sich selber eine Klasse für MsSQL oder ODBC
                                 // schreiben.

// Tabellen Namen
// Die Tabellennamen sollten selbsterklärend sein.
$sql_table['comments'] = "pdl3_comments";
$sql_table['files'] = "pdl3_files";
$sql_table['iplock'] = "pdl3_iplock";
$sql_table['ordner'] = "pdl3_ordner";
$sql_table['release'] = "pdl3_release";
$sql_table['replacements'] = "pdl3_replacements";
$sql_table['rights'] = "pdl3_rights";
$sql_table['screens'] = "pdl3_screens";
$sql_table['settings'] = "pdl3_settings";
$sql_table['settingsgroup'] = "pdl3_settingsgroup";
$sql_table['template'] = "pdl3_template";
$sql_table['templategroup'] = "pdl3_templategroup";
$sql_table['user'] = "pdl3_user";
$sql_table['usergroup'] = "pdl3_usergroup";
?>