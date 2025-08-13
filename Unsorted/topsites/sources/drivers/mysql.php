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

class SQL {
  var $dbl;

  function Connect ($host, $user, $pass, $database) {
    $this->dbl = mysql_connect($host, $user, $pass) or die(mysql_error());
    $db = mysql_select_db($database, $this->dbl) or die(mysql_error());

    return $db;
  }

  function Execute ($query) {
    global $DEBUG, $queries, $TMPL;

    if ($DEBUG) { array_push($queries, $query); }

    $result = mysql_query($query) or die(mysql_error());
    $TMPL['queries']++;

    return $result;
  }

  function SelectLimit ($query, $num, $offset) {
    global $DEBUG, $queries, $TMPL;

    if ($offset) { $limit = " LIMIT $offset,$num"; }
    else { $limit = " LIMIT $num"; }

    if ($DEBUG) { array_push($queries, $query.$limit); }

    $result = mysql_query($query.$limit) or die(mysql_error());
    $TMPL['queries']++;

    return $result;
  }

  function FetchArray ($result) {
    return mysql_fetch_array($result, MYSQL_NUM);
  }

  function Close () {
    mysql_close($this->dbl);
  }
}
?>