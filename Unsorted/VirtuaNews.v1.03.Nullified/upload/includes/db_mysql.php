<?php

/*======================================================================*\
|| #################################################################### ||
||  Program Name         : VirtuaNews Pro                                 
||  Release Version      : 1.0.3                                          
||  Program Author       : VirtuaSystems                                  
||  Supplied by          : Ravish                                         
||  Nullified by         : WTN Team                                       
||  Distribution         : via WebForum, ForumRU and associated file dumps
|| #################################################################### ||
\*======================================================================*/

function vn_connect($halt=1) {

  global $dbservername,$dbusername,$dbpassword,$dbname;
  $db = @mysql_pconnect($dbservername,$dbusername,$dbpassword);

  @mysql_select_db($dbname,$db);

  if ((geterrno() != 0) & $halt) {
    halt();
  } else {
    return $db;
  }

}

function query($sql,$halt=1) {

  global $db_query_count,$db,$showqueries,$db_query_time,$debug,$inadmin;

  if ($showqueries & ($debug > 0)) {
    echo "<pre>\nQuery Number: ".($db_query_count+1)."\n".iif($debug == 2,"Query: $sql\n");

    global $pagestarttime;
    $queryendtime = microtime();
    $starttime = explode(" ",$pagestarttime);
    $endtime = explode(" ",$queryendtime);

    $beforetime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];

    echo "Time before: $beforetime\n";

  }
  $result = @mysql_query($sql,$db);

  if (($debug == 3) & ($inadmin == 1)) {
    global $db_query_arr;
    $db_query_arr[] = $sql;
  }

  if (!$result & $halt) {
    halt($sql);
  }

  $db_query_count ++;

  if ($showqueries) {
    $queryendtime = microtime();
    $starttime = explode(" ",$pagestarttime);
    $endtime = explode(" ",$queryendtime);

    $aftertime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
    $db_query_time += $aftertime-$beforetime;

    echo "Time after:  $aftertime\nTotal time:".($aftertime-$beforetime)."</pre>\n";
  }

  return $result;
}

function query_first($sql,$halt=1) {
  $getdata = query($sql,$halt);
  if ($getdata) {
    $data_arr = fetch_array($getdata);
  }
  return $data_arr;
}

function fetch_array($query) {
  $record = mysql_fetch_array($query);
  return $record;
}

function countrows($resource) {
  $rows = mysql_num_rows($resource);
  return $rows;
}

function countfields($resource) {
  $fields = mysql_num_fields($resource);
  return $fields;
}

function affectedrows() {
  global $db;
  return mysql_affected_rows($db);
}

function data_seek($resource,$position) {
  return @mysql_data_seek($resource,$position);
}

function getlastinsert() {
  $id = mysql_insert_id();
  return $id;
}

function free_result($resource) {
  return mysql_free_result($resource);
}

function geterrno() {
  return mysql_errno();
}

function geterrordesc() {
  return mysql_error();
}

function halt($sql="") {

  global $sitename,$technicalemail,$staffid;

  if (!$sitename) {
    $sitename = "VirtuaNews";
  }

  $message="Database error:\n\n";
  $message.="mysql error: ".mysql_error()."\n\n";
  $message.="mysql error number: ".geterrno()."\n\n";

  if ($sql) {
    $message .= "Sql query: $sql\n\n";
  }

  $message.="Date: ".date("l dS of F Y h:i:s A")."\n\n";
  $message.="Script: ".getenv("REQUEST_URI")."\n\n";
  $message.="Referer: ".getenv("HTTP_REFERER")."";

  if ($technicalemail) {
    $temp = explode(" ",$technicalemail);
    foreach ($temp  AS $val) {
      @mail($val,"$sitename Database error",$message,"From: \"$sitename Mailer\" <$val>");
    }
  }

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-scrict.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\" dir=\"ltr\">
<head>
  <title>$sitename Database Error</title>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=windows-urf-8\" />
</head>

<body>

<!--

$message

-->

  <div style=\"font-weight:bold\">
    There is a problem with the database that is preventing the site from working.
    <br />
    <br />
  </div>
  <div>
    An email has been sent to the administrator notifying them of the problem.  Please try again later.
    <br />
    <br />
  </div>\n";

  if ($staffid) {
    echo "\n  <form id=\"message\">\n    <textarea rows=\"20\" cols=\"100\" readonly=\"readonly\">\n".htmlspecialchars($message)."\n    </textarea>\n  </form>\n\n";
  }

  echo "</body>\n</html>";

  exit;
}

/*======================================================================*\
|| ####################################################################
|| # File: includes/db_mysql.php
|| ####################################################################
\*======================================================================*/

?>