<?php
/*********************************************************
 * Author: John Astill
 * Date  : 9th December
 * File  : dbaseFunctions.php
 ********************************************************/

/*******************************************************
* Open a connection to the database and select the
* database.
* The database name and connection information is
* taken from Global Configration files.
* @return TRUE if the connection is created 
* successfully
*******************************************************/
function OpenConnection() {

  // Allow access to the global table name.
  global $dbaseHost, $dbaseName, $dbaseUsername, $dbasePassword;

  // Connecting, selecting database
  $link = mysql_connect($dbaseHost, $dbaseUsername, $dbasePassword)
      or die("Could not connect");

  mysql_select_db($dbaseName, $link)
      or die("Could not select database $dbaseName for link[$link]\n");

  return $link;
}

/*******************************************************
* Close the database connection.
* @param link - the link returned when the connection
*               was opened.
*******************************************************/
function CloseConnection($link) {
  // Closing connection
  mysql_close($link);
}

/*********************************************************
 * Compare the two given dates.
 * @param d1 the first date
 * @param d2 the second date
 * @return -1 if d1 < d2
 *          0 if d1 = d2
 *          1 if d1 > d2
 ********************************************************/
function CompareDates($d1, $d2) {
  if ($d1 < $d2) {
    return -1;
  }
  if ($d1 > $d2) {
    return 1;
  }
  return 0;
}

/*********************************************************
 * Compare the given date with the current date.
 * Strip the individual components from the date in the
 * format YYYY-MM-DD.
 * @param d1 the first date
 * @return -1 if d1 < Current date
 *          0 if d1 = Current date
 *          1 if d1 > Current date
 ********************************************************/
function CompareDate($d1) {
  $year = substr($d1,0,4);
  $month = substr($d1,5,2);
  $day = substr($d1,8,2);

  $currentyear = date("Y");
  $currentmonth = date("m");
  $currentday = date("d");

  // Test the year first
  if ($year < $currentyear) {
    return -1;
  }
  // Test the year first
  if ($year > $currentyear) {
    return 1;
  }

  // At this point the year is the same.
  // Test the month
  if ($month < $currentmonth) {
    return -1;
  }
  if ($month > $currentmonth) {
    return 1;
  }

  // Finally the day
  if ($day < $currentday) {
    return -1;
  }
  if ($day > $currentday) {
    return 1;
  }

  // They are equal
  return 0;
}

/*********************************************************
 * Compare the given datetime with the current datetime.
 * Strip the individual components from the date in the
 * format YYYY-MM-DD.
 * @param d1 the first date
 * @return -1 if d1 < Current date
 *          0 if d1 = Current date
 *          1 if d1 > Current date
 ********************************************************/
function CompareDatetime($date) {
  // If the date isn't today, return the CompareDate value.
  $result = CompareDate($date);
  if ($result != 0) {
    return $result;
  }

  $currentHours = date("H");
  $currentMinutes = date("i");
  $hours = substr($date,11,2);
  $minutes = substr($date,14,2);
  if ($hours < $currentHours) {
    return -1;
  } else if ($hours > $currentHours) {
    return 1;
  }

  if ($minutes < $currentMinutes) {
    return -1;
  } else if ($minutes > $currentMinutes) {
    return 1;
  }

  // Equal
  return 0;
}
?>
