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

// Settings
require_once 'config.php';

// Require functions and process GET and POST input
require_once $CONFIG['path'].'/sources/functions.php';
$FORM = parse_form();

echo <<<EndHTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Aardvark Topsites PHP - Upgrade</title>
<link rel="stylesheet" type="text/css" media="screen" title="Default" href="templates/default.css" />
<link rel="alternate stylesheet" type="text/css" media="screen" title="Dark" href="templates/default_dark.css" />
</head>
<body>
<center>
<img src="templates/logo.png" width="728" height="66" alt="Aardvark Topsites PHP" border="0" />
</center>
<table align="center" width="728" style="border: 2px solid #069;"><tr><td>
EndHTML;

if (!$FORM['old_path']) {
  echo <<<EndHTML
This script is for importing your members from Aardvark Topsites 3.2.3.  Before you run this script, you have to install Aardvark Topsites PHP.  There must be no members in your Aardvark Topsites PHP list or else there might be conflicts with ID numbers.<br /><br />
<form action="upgrade-3.2.3.php" method="post">
What is the full path to your old Data directory?<br />
<input type="text" name="old_path" size="20" /><br />
<input type="submit" value="Import Members" />
</form>
EndHTML;
}
else {
  $file = $FORM['old_path']."/info.cgi";
  $fh_info = fopen($file, "r");
  $info = fread($fh_info, filesize($file)); 
  fclose($fh_info);

  $info_array = explode("\n", $info); 

  // Connect to the database
  require_once $CONFIG['path'].'/sources/drivers/'.$CONFIG['sql'].'.php';
  $db = new SQL;
  $db->Connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_pass'], $CONFIG['sql_database']);

  $result = $db->Execute("SELECT num_members FROM ".$CONFIG['sql_prefix']."_etc");
  list($num_members) = $db->FetchArray($result);

  $old_num_members = $num_members;

  foreach ($info_array as $value) {
    if ($value) {
      $value = rtrim($value);
      list($id, $url, $title, $description, $email, $password, $urlbanner) = explode('|', $value);

      $id = strip($id);
      $url = strip($url);
      $title = strip($title);
      $description = strip($description);
      $email = strip($email);
      $urlbanner = strip($urlbanner);
      $password = strip($password);

      $password = md5($password);

      $db->Execute("INSERT INTO ".$CONFIG['sql_prefix']."_members (id, password, url, title, description, urlbanner, email, jointime, active)
                    VALUES ($id, '$password', '$url', '$title', '$description', '$urlbanner', '$email', 1, ".$CONFIG['active_default'].")");
      $db->Execute("INSERT INTO ".$CONFIG['sql_prefix']."_stats (id2, old_rank, days_inactive, unq_pv_today, unq_pv_1, unq_pv_2, unq_pv_3, tot_pv_today, tot_pv_1, tot_pv_2, tot_pv_3, unq_in_today, unq_in_1, unq_in_2, unq_in_3, tot_in_today, tot_in_1, tot_in_2, tot_in_3, unq_out_today, unq_out_1, unq_out_2, unq_out_3, tot_out_today, tot_out_1, tot_out_2, tot_out_3) 
                    VALUES ($id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");

      $num_members++;
    }
  }

  $result = $db->Execute("SELECT MAX(id) FROM ".$CONFIG['sql_prefix']."_members");
  list($highest_id) = $db->FetchArray($result);

  $db->Execute("UPDATE ".$CONFIG['sql_prefix']."_etc SET num_members = $num_members, highest_id = $highest_id");

  $db->Execute($dbl);

  $difference = $num_members - $old_num_members;

  echo "$difference members have been imported into your topsites list.  You should delete upgrade.php now.";
}

$db->Close;

echo <<<EndHTML
</td></tr></table>
</body>
</html>
EndHTML;
?>