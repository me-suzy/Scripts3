<?php
#$Id: global.inc.php,v 1.4 2003/08/12 17:10:55 ryan Exp $
function getStartTime(){

    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
    return $starttime;
}

function getEndTime($starttime, $p, $q){
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);
    print "$totaltime";
    timerLog($totaltime, $p, $q);
}

function timerLog($total, $p, $q){
    $current_date_time = date("M-d-y H:i:s");
    $writefile = "admin/logs/timer.log";
    if (file_exists($writefile)){ $fp = fopen( $writefile, 'a'); }
    else { touch($writefile); $fp = fopen( $writefile, 'w' ); }
    fwrite( $fp, "$current_date_time :: $total :: $p?$q \r\n");
    fclose($fp);
}



?>
