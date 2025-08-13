<?
class pdl_db_class
 {
  var $config_sql_server = "localhost";
  var $config_sql_database = "pdl3";
  var $config_sql_user = "root";
  var $config_sql_password = "";
  var $config_sql_persistent = false;
  var $handler = 0;
  var $querys = 0;

  function sql_connect()
   {
    if($this->config_sql_persistent == true)
     { $this->handler = @mysql_pconnect($this->config_sql_server,$this->config_sql_user,$this->config_sql_password) or die ("Verbindung zum MySQL Server konnte nicht aufgebaut werden. Überprüfen sie die Zugangsdaten zum MySQL Server."); }
    else
     { $this->handler = @mysql_connect($this->config_sql_server,$this->config_sql_user,$this->config_sql_password) or die ("Verbindung zum MySQL Server konnte nicht aufgebaut werden. Überprüfen sie die Zugangsdaten zum MySQL Server."); }
    @mysql_select_db($this->config_sql_database,$this->handler) or die ("Datenbank existiert nicht. Überprüfen sie den Datenbanknamen.");
   }

  function sql_query($query)
   {
    $this->querys++;
    #echo "Query: $query<br>";
    return @mysql_query($query,$this->handler);
   }

  function sql_fetch_array($result)
   {
    return @mysql_fetch_array($result);
   }

  function sql_num_rows($result)
   {
    return @mysql_num_rows($result);
   }

  function sql_num_fields($result)
   {
    return @mysql_num_fields($result);
   }

  function sql_escape_string($string)
   {
    return @mysql_escape_string($string);
   }

  function sql_insert_id()
   {
    return @mysql_insert_id($this->handler);
   }

  function sql_close()
   {
    @mysql_close($this->handler);
   }
 }
?>
