<?php

class settingsdata
{
 
 function settingsdata()
 {
  global $db, $databasename, $inadmindir;
  
  $getem = $db->select('name,content', 'metatable', 'id>0', 'ORDER BY id ASC', '');
  $num = $db->numrows($getem);
  for ($count=0; $count<$num; $count++)
  {
   $row = $db->row($getem);
   $name = $row[0];
   if ($count < $num-1) $this->allnames .= $name .',';
   else $this->allnames .= $name;
   $content = $row[1];
   if ($content == ' ') $content = '';
   $this->$name = $content;
  }
 }

 function update($field)
 {
  global $db;
  if ($field=='all')
  {
   $check = $db->select('name', 'metatable', 'id>0', '', '');
   $num = $db->numrows($check);
   for ($count=0; $count<$num; $count++)
   {
    $nextset = $db->rowitem($check);
	$doit = $db->update('metatable', 'content', $this->$nextset, "name='$nextset'");
   }
  }
  else
  {
   $fields = explode(',', $field);
   $num = sizeof($fields);
   for ($q=0; $q<$num; $q++)
   { 
    $doit = $db->update('metatable', 'content', $this->$fields[$q], "name='". $fields[$q] ."'");
   }
   return true;
  }
 }
 
 function allnames()
 {
  return $this->allnames;
 }

}


?>