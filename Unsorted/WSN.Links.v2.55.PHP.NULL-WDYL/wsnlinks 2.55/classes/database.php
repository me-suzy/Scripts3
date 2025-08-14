<?php
/* To use WSN with a database other than MySQL, simply change the functions in this database class as appropriate. You'll also need to modify your config.php file. */

class database
{ 

 function fetcharray($sqllump)
 {
  global $debug, $debuginfo;
  if ($debug == 6) $result = @mysql_fetch_array($sqllump);
  else $result = mysql_fetch_array($sqllump);
  return $result;
 }

 function fieldnamelist($table)
 {
  global $databasename, $debug;
  if ($debug == 6) $list = @mysql_list_fields($databasename, $table);
  else $list = mysql_list_fields($databasename, $table);
  if ($debug == 6) $num = mysql_num_fields($table);  
  else $num = mysql_num_fields($list);
  for ($x=0; $x<$num; $x++)
  {
   if ($x > 0) $fieldlist .= ',';
   $fieldlist .= mysql_field_name($list, $x);
  }
  return $fieldlist;
 }

 function fieldtype($table, $field)
 {
  global $debug; 
  $resource = $this->query('SELECT * FROM '. $table);
  $num = mysql_num_fields($resource);
  for ($x=0; $x<$num; $x++)
  {
   $thisfield = mysql_fetch_field($resource);
   if ($thisfield->name == $field)
   {
    $type = $thisfield->type;
	if ($type = 'blob') $type = 'text';
	if ($thisfield->not_null) $type .= ' NOT NULL';
	if ($thisfield->numeric) $type .= " default '0'";
   }
  }
  return $type;
 }
 
 function row($sqllump)
 {
  global $debug, $debuginfo;
  if ($debug == 6) $row = @mysql_fetch_row($sqllump);
  else $row = mysql_fetch_row($sqllump);
  if (($debug>0) && ($debug<4) && (mysql_error() != ''))
  {
   if ($debug == 2)$debuginfo .= "<p>Mysql error while fetching row for $sqllump:". mysql_error() ."</p>";  
   else echo "<p>Mysql error while fetching row for $sqllump:". mysql_error() ."</p>";  
  }
  return $row;
 }

 function numrows($sqllump)
 {
  global $debug, $debuginfo;
  if ($debug == 6) $num = @mysql_num_rows($sqllump);
  else $num = mysql_num_rows($sqllump);
echo "";
  if (($debug>0) && ($debug<4))
  {
   if ($debug == 2)$debuginfo .= "<p>Number of rows is $num for $sqllump". mysql_error() ."</p>";  
   else echo "<p>Number of rows is $num for $sqllump". mysql_error() ."</p>";  
  }
  return $num;
 }

 function rowitem($sqllump)
 {
  global $debug, $debuginfo;
  $row = $this->row($sqllump);
  $item = $row[0];
  if (($debug>0) && ($debug<4) && (mysql_error() != ''))
  {
   if ($debug == 2)$debuginfo .= "<p>Mysql error while fetching row for $sqllump:". mysql_error() ."</p>";  
   else echo "<p>Mysql error while fetching row for $sqllump:". mysql_error() ."</p>";  
  }
  return $item;
 }

 function update($table, $field, $value, $where)
 {
  global $connection, $$table, $debug, $debuginfo, $querycount;
  $table = $$table;
  $query = "UPDATE $table SET $field='$value' WHERE $where";
  return $this->query($query, $connection);
 }

 function alter($table, $what, $value, $type)
 {
  global $connection, $$table, $debug, $debuginfo, $querycount;
  $table = $$table;
  $query = "ALTER TABLE `$table` $what `$value`  $type";
  return $this->query($query, $connection);
 }
 
 function delete($table, $where)
 {
  global $connection, $$table, $debug, $debuginfo, $querycount;
  $table = $$table;
  $query = "DELETE FROM  $table WHERE $where";
  return $this->query($query, $connection);
 }
  
 function insert($table, $fields, $values)
 {
  global $connection, $$table, $debug, $debuginfo, $querycount;
  $table = $$table;
  $query = "INSERT INTO $table($fields) VALUES($values)";
  return $this->query($query, $connection);
 }
 
 function select($fields, $table, $where, $order, $limit)
 {
  global $connection, $$table, $debug, $debuginfo, $querycount;
  // if ($table == 'memberstable') $fields = str_replace('id', 'whateverthenewmemberstablecallsids', $fields);
  $table = $$table;
  if ($fields == 'linksfields') $fields = getfields('links');
  if ($fields == 'categoriesfields') $fields = getfields('categories');
  if ($fields == 'metafields') $fields = getfields('settings');
  $query = "SELECT $fields FROM $table WHERE $where $order $limit";
  return $this->query($query);
 }
 
 function query($sqllump)
 {
  global $connection, $debug, $debuginfo, $querycount;
  $querycount++;  
  if (($debug>0) && ($debug<4))
  {
   if ($debug == 2) $debuginfo .= "<p>Performing query: $sqllump;</p> <p>". mysql_error() ."</p>";
   else echo "<p>Performing query: $sqllump;</p> <p>". mysql_error() ."</p>";
  }
  if ($debug == 6) return @mysql_query($sqllump, $connection);
  else return mysql_query($sqllump, $connection);
 }
 
 function closedb($connection)
 {
  global $connection, $debug, $debuginfo;
  if ($debug == 6) @mysql_close($connection);
  else mysql_close($connection);
  if (($debug>0) && ($debug<4))
  {
   if ($debug == 2) $debuginfo .= "</p>closing database connection". mysql_error() ."</p>";
   else echo "<p>closing database connection ". mysql_error() ."</p>";  
  }
  return true;
 }
}

?>