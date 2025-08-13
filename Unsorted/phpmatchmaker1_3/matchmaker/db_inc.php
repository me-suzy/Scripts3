<?
function db_connect()

{
   $result = mysql_pconnect("localhost", "mysql username", "mysql password"); 

   if (!$result)

      return false;

   if (!mysql_select_db("your databasename"))

      return false;



   return $result;

}



?>
