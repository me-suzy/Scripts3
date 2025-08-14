<?php

/****************************************************
 *   file      : weblog.php							*
 *   version   : 0.1								*
 *   date      : October 27, 2005					*
 *   copyright : Abbas Alafoo						*
 *   website   : http://www.website-hostings.net	*
 ****************************************************/

// get current date and time
$date = date("Y-m-d H:i:s", time());

// get the IP of your visitor
$ip = getenv("REMOTE_ADDR");

// the path of the requested page
$path = $_SERVER['REQUEST_URI'];

// the path of the log file
$log_path = "./log.txt";

// open the log file and append the new entry
$log_handler = fopen($log_path, "a");
$new_entry = $date."	".$ip."	".$path."\n";
fwrite($log_handler, $new_entry);
fclose($log_handler);

?>