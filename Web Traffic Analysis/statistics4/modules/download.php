<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + download.php - part of showlog - the statistics
// + Initially written by Daniel Sokoll 2004 - http://www.sirsocke.de
// +
// + Release v1.0.0:	2004 - Daniel Sokoll
// + Last Changes:	21.08.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// --- do nessassary configurations ---
include_once("../config/statistics.conf.php");
$path = "../".$log_dir;
$file = basename($log_file);

// --- write download-log ---
$dl_file = "../".$dl_file;
$dl_fp = fopen($dl_file, "w".(file_exists($dl_file)?"":"+")) or die("Can't open $dl_file\n");
flock($dl_fp, 2);
fwrite($dl_fp, date("Y-m-d"));
fclose($dl_fp);

// --- send logfile to client ---
chdir($path);
header("Content-type: application/oct-stream"); 
header("Content-disposition: attachment; filename=\"".$file."\""); 
readfile($file);
chdir("../"); ?>