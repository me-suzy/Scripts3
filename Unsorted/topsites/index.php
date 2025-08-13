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

// Start the timer
$starttime = microtime();
$starttime = explode(' ', $starttime);
$starttime = $starttime[1] + $starttime[0];

// Settings
require_once 'config.php';
$queries = array();
$TMPL['queries'] = 0;
$DEBUG = 0; // Set to 1 and all the SQL queries will be shown at the bottom

// Require functions and process GET and POST input
require_once $CONFIG['path'].'/sources/functions.php';
$FORM = parse_form();

// The language file
require_once $CONFIG['path'].'/languages/'.$CONFIG['deflanguage'].'.php';

// Template functions
require_once $CONFIG['path'] . '/sources/template.php';

// Connect to the database
require_once $CONFIG['path'].'/sources/drivers/'.$CONFIG['sql'].'.php';
$db = new SQL;
$db->Connect($CONFIG['sql_host'], $CONFIG['sql_user'], $CONFIG['sql_pass'], $CONFIG['sql_database']);

// Get the number of members and the last reset
$result = $db->Execute("SELECT last_newday, num_members FROM ".$CONFIG['sql_prefix']."_etc");
list($last_newday, $TMPL['nummem']) = $db->FetchArray($result);

// Is it a new day/month?
$current_day = $CONFIG['daymonth'] ? date("m") : date("d");
if ($last_newday != $current_day) {
    require_once $CONFIG['path'].'/sources/new_day.php';
}
if ($CONFIG['daymonth']) {
  $LNG['g_today'] = $LNG['g_thismonth'];
  $LNG['g_yesterday'] = $LNG['g_lastmonth'];
  $LNG['g_2days'] = $LNG['g_2months'];
  $LNG['g_3days'] = $LNG['g_3months'];
}

// gzip
if ($CONFIG['gzip']) { ob_start("ob_gzhandler"); }

// Options of what to do
$action = array(
            'display' => 1,
            'edit' => 1,
            'email' => 1,
            'graph' => 1,
            'info' => 1,
            'join' => 1,
            'lostcode' => 1,
            'lostpw' => 1,
            'rate' => 1,
            'search' => 1,
            'stats' => 1
          );

// Require the appropriate script
if (!isset($action[$FORM['a']])) { $FORM['a'] = 'display'; }
require_once $CONFIG['path'].'/sources/'.$FORM['a'].'.php';

build_template_stuff();

$db->Close;

// Print the content
echo do_template("template");

if ($DEBUG) {
  echo "<div style=\"margin: 2px;\">\n";
  foreach ($queries as $value) {
    echo "<hr />$value\n";
  }
  echo "</div>";
}
?>