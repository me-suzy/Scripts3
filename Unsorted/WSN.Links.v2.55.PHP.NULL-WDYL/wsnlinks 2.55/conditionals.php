<?php
// Handle my conditionals syntax -- shortened version of php code
$pattern = "<IF (.*?)>";   
$pattern = '/'. $pattern .'/i';
preg_match_all($pattern, $doc, $codes);
for ($i=0; $i<sizeof($codes[0]); $i++)
{
 $command = $codes[1][$i];
 if ($command && strstr($doc, '</IF>'))
 {
  $origcode = $codes[1][$i];
  $codes[1][$i] = str_replace(' is not ', ' != ', $codes[1][$i]);
  $codes[1][$i] = str_replace(' not ', '!', $codes[1][$i]);
  $codes[1][$i] = str_replace(' = ', ' == ', $codes[1][$i]);
  $codes[1][$i] = str_replace(' is less than ', ' < ', $codes[1][$i]);
  $codes[1][$i] = str_replace(' is greater than ', ' > ', $codes[1][$i]);
  $codes[1][$i] = str_replace(' is ', ' == ', $codes[1][$i]);   
  $codes[1][$i] = str_replace('"', '', $codes[1][$i]);   
  // build by term and add "" around strings
  if (strstr($codes[1][$i], ' and ') || strstr($codes[1][$i], ' or ') || strstr($codes[1][$i], ' contains ') || strstr($codes[1][$i], ' < ') || strstr($codes[1][$i], ' > ') || strstr($codes[1][$i], ' == ') || strstr($codes[1][$i], ' != ') || strstr($codes[1][$i], ' >= ') || strstr($codes[1][$i], ' <= '))
  { // two or more terms
   $new = str_replace(' and ', '||.M.||', $codes[1][$i]);
   $new = str_replace(' or ', '||.M.||', $new);	
   $new = str_replace(' contains', '||.M.||', $new);		
   $new = str_replace(' > ', '||.M.||', $new);		
   $new = str_replace(' < ', '||.M.||', $new);
   $new = str_replace(' == ', '||.M.||', $new);
   $new = str_replace(' != ', '||.M.||', $new);   
   $new = str_replace(' >= ', '||.M.||', $new);   
   $new = str_replace(' <= ', '||.M.||', $new);     
   $terms = explode('||.M.||', $new);
   for ($q=0; $q<sizeof($terms);$q++)
   {
    $terms[$q] = trim($terms[$q]);
	$others = false;
	$nxt = trim($terms[$q+1]);
	if ($nxt != '' && !is_numeric($nxt)) $others = true;
	$prv = trim($terms[$q-1]);
	if ($prv != '' && !is_numeric($prv)) $others = true;	
    if (((is_numeric($terms[$q]) || (is_float($terms[$q]))) && (!$others)) || (strstr($codes[1][$i], $terms[$q] .'"') || strstr($codes[1][$i], '"'. $terms[$q])))
    {
     if (strstr($codes[1][$i], $terms[$q] .'"') || strstr($codes[1][$i], '"'. $terms[$q]))
     {
       $terms[$q] = ' '. $terms[$q];
       $newterm = '"'. str_replace('"', '', $terms[$q]) .'"';
     }
     else if (strstr($codes[1][$i], ' contains '))
     {
      $newterm = '"'. $terms[$q] .'"';
     }
     else
     {
      $newterm = $terms[$q];
     }
    }
    else
    {
     $newterm = '"'. str_replace('"', '', $terms[$q]) .'"';
    }
	// all terms have a space on one side or the other
	
	// changes made in the following are to try to prevent mis-quoting instances when first term is repeated in second
	$stp = @strpos($origcode, $terms[$q]);
	$st = substr($origcode, $stp);
	$getop = explode(' ', str_replace($terms[$q], '', $st));
	$operator = $getop[0] . $getop[1];
	if ($getop[2] != '') $stopat = strpos($st, $getop[2]);
	$after = substr($st, 0, $stopat);
	$after = str_replace(' is ', '', $after);
	if ($after == $terms[$q]) $after = '';
	$codes[1][$i] = str_replace($terms[$q] . $after , $newterm . $after, $codes[1][$i]);
//	 die (stripcode($codes[1][$i]));	
	// end changed area
   }
   $codes[1][$i] = str_replace(' and ', ' && ', $codes[1][$i]);	
   $codes[1][$i] = str_replace(' or ', ' || ', $codes[1][$i]);		
   if (strstr($codes[1][$i], ' contains'))
   {
    if (substr_count($codes[1][$i], '"') < 4)
    {
     $test = explode(' contains', $codes[1][$i]);
     if ($test[0] == '') $codes[1][$i] = '"" '. $codes[1][$i];
     if ($test[1] == '') $codes[1][$i] .= ' ""';    
     if ($test[0] == ' ') $codes[1][$i] = '" " '. $codes[1][$i];
     if ($test[1] == ' ') $codes[1][$i] .= ' " "';
     if (!strstr($test[0], '"'))
     {
      $test[0] = '"'. trim($test[0]) .'"';      
      $recreate = true;
     }
     if (!strstr($test[1], '"'))
     {
      $test[1] = '"'. trim($test[1]) .'"';      
      $recreate = true;
     }
     if ($recreate) $codes[1][$i] = $test[0] .', '. $test[1];
     $codes[1][$i] = str_replace(' contains" ', ', "', $codes[1][$i]);
    }
    $codes[1][$i] = str_replace(' contains', ', ', $codes[1][$i]);
    if (substr_count($codes[1][$i], '"') < 4)
    {
     $codes[1][$i] = str_replace(' contains ', '", "', $codes[1][$i]);
     $codes[1][$i] = str_replace(', ', '", "', $codes[1][$i]);
     $altered = '<'.'?php if (strstr("'. $codes[1][$i] .'")) { ?'.'>';
    }
    else
     $altered = '<'.'?php if (strstr('. $codes[1][$i] .')) { ?'.'>';
   }
   else
   {
    if (strstr($codes[1][$i], '<'))
    {
     $test = explode('<', $codes[1][$i]);
     if ($test[0] == '') $codes[1][$i] = '"" '. $codes[1][$i];
     if ($test[1] == '') $codes[1][$i] .= ' ""';
    }
    else if (strstr($codes[1][$i], '>'))
    {
     $test = explode('>', $codes[1][$i]);
     if ($test[0] == '') $codes[1][$i] = '"" '. $codes[1][$i];
     if ($test[1] == '') $codes[1][$i] .= ' ""';
    }
    else if (strstr($codes[1][$i], '=='))
    {
     $test = explode('==', $codes[1][$i]);
     if ($test[0] == '') $codes[1][$i] = '"" '. $codes[1][$i];
     if ($test[1] == '') $codes[1][$i] .= ' ""';
    }
    else if (strstr($codes[1][$i], '!='))
    {
     $test = explode('!=', $codes[1][$i]);
     if ($test[0] == '') $codes[1][$i] = '"" '. $codes[1][$i];
     if ($test[1] == '') $codes[1][$i] .= ' ""';
    }
    $altered = '<'.'?php if ('. $codes[1][$i] .') { ?'.'>';
   }
  }
  else
  {
   $codes[1][$i] = str_replace('"', '&quot;', $codes[1][$i]);
   if (strstr($codes[1][$i], 'not '))
   {
    $codes[1][$i] = str_replace('not ', '!("', $codes[1][$i]) . '")';
    $altered = '<'.'?php if ('. $codes[1][$i] .') { ?'.'>';	
   }
   else if (!is_numeric($codes[1][$i])) $altered = '<'.'?php if ("'. $codes[1][$i] .'") { ?'.'>';
   else $altered = '<'.'?php if ('. $codes[1][$i] .') { ?'.'>';
  }
  $doc = str_replace($codes[0][$i], $altered, $doc);
 }
}
// Handle the really annoying case of where it's just <IF x>
$pattern = "<IF (.)>";   
$pattern = '/'. $pattern .'/i';
preg_match_all($pattern, $doc, $codes);
for ($i=0; $i<sizeof($codes[0]); $i++)
{
 $command = $codes[1][$i];
 if ($command && strstr($doc, '</IF>'))
 {
  if (!strstr($codes[1][$i], '"') && !strstr($codes[1][$i], "'") && !is_numeric($codes[1][$i])) $altered = '<'.'?php if ("'. $codes[1][$i] .'") { ?'.'>';
  else $altered = '<'.'?php if ('. $codes[1][$i] .') { ?'.'>';
  $doc = str_replace($codes[0][$i], $altered, $doc);
 }
}
// Handle the even more annoying cases of <IF 0> and <IF >
$doc = str_replace('<IF 0>', '<'.'?php if (0) { ?'.'>', $doc);
$doc = str_replace('<IF >', '<'.'?php if ("") { ?'.'>', $doc);

$doc = str_replace('</IF>', '<'.'?php } ?'.'>', $doc);
$doc = str_replace('<OTHERWISE>', '<'.'?php } else { ?'.'>', $doc);
$doc = str_replace('<ELSE>', '<'.'?php } else { ?'.'>', $doc);
?>