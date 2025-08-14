<?php
// do timed tasks

if ( (time() - ($settings->backupdelay * 86400)) > $settings->lastautocron)
{
 if ($settings->backupfile != '')
 {
  $dobackup = true;
  $settings->lastautocron = time();
  $settings->update('lastautocron');  
  include 'automatebackup.php';
 }
}

if ( (time() - 86400) > $settings->lastdaily)
{
 // do some sort of nice useful autocron daily maintinence feature here if you like.
 // re-calc total members if integrating with other DB
 if ($custommemberdb)
 {
  $gettotal = $db->select($settings->memberfields,'memberstable',"$newid > 0",'','');
  $settings->totalmembers = $db->numrows($gettotal);
  $settings->update('totalmembers');
 }

 // reset failed attempts on member accounts
 $done = $db->update('memberstable', $newfailedattempts, 0, "$newfailedattempts > 0");

 $expired = $db->select($settings->linkfields, 'linkstable', 'expire > 0 AND expire < '. time(), '', '');
 $num = $db->numrows($expired);
 for ($x=0; $x<$num; $x++)
 {
  $thislink = new onelink('row', $db->row($expired));
  $thislink->hide = 'yes';
  $thislink->update('hide');
 }
 $settings->lastdaily = time();
 $settings->update('lastdaily');
}

if ($settings->sponsortype == 'promotion')
{
 $debitdelay = 86400; // the charge amount is daily
 $now = time();
 if (($now - $debitdelay) > $settings->debittime)
 {
  $listem = $db->select($settings->linkfields, 'linkstable', "type='". $settings->sponsorlinktype ."'", '', '');
  $num = $db->numrows($listem);
  for ($x=0;$x<$num;$x++)
  {
   $nextlink = new onelink('row', $db->row($listem));
   $nextlink->funds = $nextlink->funds - $settings->sponsorcharge;
   $nextlink->update('funds');
   if (!(($nextlink->funds - $settings->sponsorcharge) >= 0)) 
   {
	$nextlink->type = 'regular';
	// $nextlink->type = $nextlink->origtype
	$nextlink->update('type'); 
if ($language->email_sponsorshipendtitle != '')
{
 sendemail($nextlink->email, linkreplacements($language->email_sponsorshipendtitle, $nextlink),  linkreplacements($language->email_sponsorshipendbody, $nextlink), "From: ". $settings->email);
}
   }  
   if ($nextlink->id > 0)
   {
    $aliases = $db->select($settings->linkfields, 'linkstable', 'alias='. $nextlink->id, '', '');
    $n = $db->numrows($aliases);
   }
   for ($x=0; $x<$n; $x++)
   {
    $alias = new onelink('row', $db->row($aliases));
    $alias->funds = $nextlink->funds;
    $alias->type = $nextlink->type;
    $alias->update('funds,type');
   }
  }
  $settings->debittime = time();
  $settings->update('debittime');  
  calctypeorder();  
 }
}

$resetdelay = $settings->resetdelay * 86400; // convert from days to seconds
if ( (time() - $resetdelay) > $settings->resettime) 
{ 
 if ($settings->resetfields != '')
 {
  $toreset = explode(',', $settings->resetfields);
  if (!(is_array($toreset))) $toreset[0] = $settings->resetfields;
  $num = sizeof($toreset);
  for ($x=0; $x<$num; $x++)
  {
   $db->update('linkstable', $toreset[$x], '0', 'id>0');
  }
 }
 $settings->resettime = time();
 $settings->update('resettime');
 if ($settings->resetscript == ' ') $settings->resetscript = '';
 if ($settings->resetscript != '')
 {
  $ourinc = $settings->resetscript;
  include "$ourinc";
 }
}

?>