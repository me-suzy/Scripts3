<?php
//=================================================================\\
// Aardvark Topsites PHP 4.1.0                                     \\
//-----------------------------------------------------------------\\
// Copyright (C) 2003 Jeremy Scheff - http://www.aardvarkind.com/  \\
//-----------------------------------------------------------------\\
// This program is free software; you can redistribute it and/or   \\
// modify it under the terms of the GNU General Public License     \\
// as published by the Free Software Foundation; either version 2  \\
// of the License, or (at your option) any later version.          \\
//                                                                 \\
// This program is distributed in the hope that it will be useful, \\
// but WITHOUT ANY WARRANTY; without even the implied warranty of  \\
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the   \\
// GNU General Public License for more details.                    \\
//=================================================================\\

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);

require_once 'config.php';

require_once $CONFIG['path'].'/sources/functions.php';
$FORM = parse_form();

if (!is_numeric($FORM['id'])) {
  require $CONFIG['path'].'/index.php';
  exit;
}
else { $FORM['id'] = intval($FORM['id']); }

require_once $CONFIG['path'].'/sources/drivers/'.$CONFIG['sql'].'.php';
$db = new SQL;
$db->Connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_pass'], $CONFIG['sql_database']);

$ip = getenv("REMOTE_ADDR");
$result = $db->Execute("SELECT ip_address, unq_pv FROM ".$CONFIG['sql_prefix']."_iplog WHERE ip_address = '$ip' AND id4 = ".$FORM['id']);
list($ip2, $unq_pv) = $db->FetchArray($result);

if ($ip2 == $ip && $unq_pv == 1) {

}
elseif ($ip2 == $ip) {
  $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_iplog SET unq_pv = 1 WHERE ip_address = '$ip' AND id4 = ".$FORM['id']);
  $unique = ", unq_pv_today = unq_pv_today + 1";
}
else {
  $db->Execute("INSERT INTO ".$CONFIG['sql_prefix']."_iplog (ip_address, id4, unq_pv) VALUES ('".$ip."', ".$FORM['id'].",1)");
  $unique = ", unq_pv_today = unq_pv_today + 1";
}

$db->Execute("UPDATE ".$CONFIG['sql_prefix']."_stats SET tot_pv_today = tot_pv_today + 1".$unique." WHERE id2 = ".$FORM['id']);

if ($CONFIG['ranks_on_buttons']) {
  $result = $db->Execute("SELECT id2 FROM ".$CONFIG['sql_prefix']."_stats ORDER BY (".$CONFIG['rankingmethod']."_today + ".$CONFIG['rankingmethod']."_1 + ".$CONFIG['rankingmethod']."_2 + ".$CONFIG['rankingmethod']."_3) / 4 DESC LIMIT 0,".$CONFIG['button_num']);
  $num = 1;
  while (list($id) = $db->FetchArray($result)) {
    if ($id == $FORM['id']) {
      $location = $CONFIG['button_dir']."/".$num.".".$CONFIG['button_ext'];
      $rankonbutton = 1;
    }
    $num++;
  }
}
if (!$rankonbutton) {
  $location = $CONFIG['button_url'];
}

$db->Close;

header("Location: ".$location);
?>