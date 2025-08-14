<?php
class language
{

 function language($languagegroup)
 {
  global $inadmindir, $settings;
  $this->groupid = $languagegroup;
  if ($inadmindir) $path = '../';
  $path .= 'languages/';
  if (!file_exists($path . $languagegroup .'.lng'))
  {
   $path .= 'setup/'. $languagegroup .'.lng';
   if (!file_exists($path)) 
   { 
	if($inadmindir) $newpath = '../';
    $newpath .= 'languages/'. getvalidlanguage() .'.lng';
	if (file_exists($newpath))
	{
	 $path = $newpath;
	}
	else
	{
	 if ($inadmindir) $newpath = '../';
         else $newpath = '';
         if (file_exists($newpath .'languages/setup/fullenglish.lng')) $path = $newpath .'languages/setup/fullenglish.lng';
         else
         {
	  $newpath .= 'languages/setup/'. getvalidlanguage() .'.lng';	
	  $path = $newpath;
         }
	}
   }
  }
  else
  {
   $path .= $languagegroup .'.lng';
  }
  $this->downloadcontent = fileread($path);
  $data = decodelanguage($this->downloadcontent);
  $language->sourcedata = $data;
  $groups = explode('|||END LINE|||', $data);
  foreach ($groups as $group)
  {
   $parts = explode('|||DIVIDER|||', $group);
   $name = $parts[0];
   $value = $parts[1];
   $this->$name = $value;
   if ($this->internalnamelist == '') $this->internalnamelist = $name;
   else $this->internalnamelist .= ','. $name;   
  }
 }

 function allnames()
 {
  return $this->internalnamelist;  
 }

 function updateitem($name)
 {
  $this->writelanguage();
  return true;
 }

 function writelanguage()
 {
  global $inadmindir;
  // format: title_register|||DIVIDER|||Registration|||END LINE|||
  $langarray = get_object_vars($this);
  while(list($key, $value) = each($langarray)) 
  {
   if ($key != 'internalnamelist' && $key != 'groupid' && $key != 'sourcedata')
     $data .= $key .'|||DIVIDER|||'. $value .'|||END LINE|||';
  }
  if ($inadmindir) $test = filewrite('../languages/'. $this->groupid .'.lng', $data);
  else $test = filewrite('languages/'. $this->groupid .'.lng', $data);
  return $test;
 }
 
 function deleteitem($name)
 {
  unset($this->$name);
  $test = $this->writelanguage();
  return $test;
 }

 function filter($field, $filter)
 {
  $langarray = get_object_vars($this);
  $skip = '    groupid internalnamelist sourcedata   ';
  while(list($name, $value) = each($langarray)) 
  {
   if (!strstr($skip, ' '. $name .' '))
   {
    if ($field == 'name')
    {
     if ($filter == '') $filteredarray[] = array($name,$value);
     else if (strstr($name, $filter)) 
	 {
	  $filteredarray[] = array($name,$value);
 	 }
    }
    else if ($field == 'value')
    {
     if ($filter == '') $filteredarray[] = array($name,$value);
     else if (strstr($this->$name, $filter)) 
	 {
	  $filteredarray[] = array($name,$value);
	 }   
    }
   }
  }
  @sort($filteredarray);
  return $filteredarray;
 }
 
 function downloadcontent()
 {
  return $this->downloadcontent;
 }
 
 function removeduplicates()
 {
 // this function is unimportant -- maybe finish it later
  global $inadmindir;
  $list = explode(',', $this->allnames());
  $num = sizeof($list);
/*  for ($s=0; $s<$num; $s++)
  { 
   if ( ($list[$s] != '') && (substr_count($this->allnames(), ','. $list[$s] .',') > 1) )
   { 
    $q = $db->select('id', 'languagetable', "groupid='". $this->groupid ."' AND name='". $list[$s] ."'", 'ORDER BY id ASC', '');
    $n = $db->numrows($q);
    $duplist .= ' '. $list[$s] .' ';
    for ($r=1; $r<$n; $r++)
    { 
     $id = $db->rowitem($q);
     $db->delete('languagetable', "id='$id'");
    }
   }
  } */
  return $duplist;
 }

}

?>