<?php 
require 'adminstart.php';
if ($filled)
{
 $template = new template("blank");
 $template->text = '<div class="nav"><a href="../index.php">'. $language->navorigin .'</a> > <a href="index.php">Admin Panel</a> > <a href="toplist.php">Toplist Generator</a> > Results</div><br>';
$var = $table .'field';
$field = $$var;
$template->text .= 'Copy the text below and paste it into the template where you want the toplist to appear. Please note that you must re-number this toplist to a number which will be unique for whatever page you are using it on:<br><br>
<textarea rows="10" cols="60">';
if ($columns > 0) $template->text .= '<table>
';
$template->text .= '<!-- BEGIN TOPLIST 1 -->
<CONFIG>'. $table .','. $field .','. $number .','. $order .','. $condition .','. $start .','. $columns .'</CONFIG>
';
if ($columns > 0 && !strstr($content, '<td>')) $template->text .= '<td>
'. $content .'
</td>';
else $template->text .= $content;
$template->text .= '
<!-- END TOPLIST 1 -->';
if ($columns > 0) $template->text .= '
</table>'; 
$template->text .= '</textarea>
<br>
<p align="center">[<a href="index.php">Back to main admin page</a>]</p>
';
$template->text = stopvars($template->text);
}
else
{
 $template = new template("../$templatesdir/admin/toplist.tpl");

$skip = ' sendsubscriptions onelink hidealiases deletealiases subscribe category comment deletethis register add update reject validate updateparents calcnumsub getsubcats getsubcatsall linkdata linkdatabytype member login logout addlink addentry addcomment ';
$q = $db->select($settings->linkfields, 'linkstable', 'validated=1', '', 'LIMIT 0,1');
$thelink = new onelink('row', $db->row($q));
$linkfuncarray = get_class_methods($thelink);
foreach($linkfuncarray as $key) 
{
 $tempvar = $pref . strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' ')) $linkvars .= ' '. $tempvar;
}
$linkarray = get_object_vars($thelink);
while(list($key, $value) = each($linkarray)) 
{
 $tempvar = $pref . strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))
 {
  $linkvars .= ' '. $tempvar;
  $linkops .= '<option value="'. $key .'">'. $key .'</option>';
 }
} 
$q = $db->select($settings->categoryfields, 'categoriestable', 'validated=1', '', 'LIMIT 0,1');
$thelink = new category('row', $db->row($q));
$linkfuncarray = get_class_methods($thelink);
foreach($linkfuncarray as $key) 
{
 $tempvar = '{CAT'. strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))
 {
  $catvars .= ' '. $tempvar;
 }
}
$linkarray = get_object_vars($thelink);
while(list($key, $value) = each($linkarray)) 
{
 $tempvar = '{CAT'. strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))
 {
  $catvars .= ' '. $tempvar;
  $catops .= '<option value="'. $key .'">'. $key .'</option>';
 }
} 
$q = $db->select($settings->commentfields, 'commentstable', 'validated=1', '', 'LIMIT 0,1');
$thelink = new comment('row', $db->row($q));
$linkfuncarray = get_class_methods($thelink);
foreach($linkfuncarray as $key) 
{
 $tempvar = '{COMMENT'. strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))  
 {
  $commentvars .= ' '. $tempvar;
 }
}
$linkarray = get_object_vars($thelink);
while(list($key, $value) = each($linkarray)) 
{
 $tempvar = '{COMMENT'. strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))
 {
  $commentvars .= ' '. $tempvar;
  $commentops .= '<option value="'. $key .'">'. $key .'</option>';
 }
} 
$q = $db->select($settings->memberfields, 'memberstable', 'validated=1', '', 'LIMIT 0,1');
$thelink = new member('row', $db->row($q));
$linkfuncarray = get_class_methods($thelink);
foreach($linkfuncarray as $key) 
{
 $tempvar = '{M&#69;MBER' . strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' '))  $membervars .= ' '. $tempvar;
}
$linkarray = get_object_vars($thelink);
while(list($key, $value) = each($linkarray)) 
{
 $tempvar = '{M&#69;MBER' . strtoupper($key) .'}';
 if (!strstr($skip, ' '. $key .' ')) 
 {
  $membervars .= ' '. $tempvar;
  if (!strstr($key, 'group') || $key == 'usergroup') $memberops .= '<option value="'. $key .'">'. $key .'</option>';
 }
} 

$template->replace("{LINKVARS}", $linkvars);
$template->replace("{CATVARS}", $catvars);
$template->replace("{COMMENTVARS}", $commentvars);
$template->replace("{MEMBERVARS}", $membervars);

$template->replace("{OPTIONSLINKFIELDS}", $linkops);
$template->replace("{OPTIONSCATEGORYFIELDS}", $catops);
$template->replace("{OPTIONSCOMMENTFIELDS}", $commentops);
$template->replace("{OPTIONSMEMBERFIELDS}", $memberops);

}
require 'adminend.php';
?>