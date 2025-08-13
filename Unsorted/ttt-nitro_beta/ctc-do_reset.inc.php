<?php
//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!
//#####################
function resetlog() {
$today = date("Y-m-d", mktime(0,0,0,date("m"), date("d")-1, date("Y")));
$tomorrow = mktime (0,0,0, date("m"), date("d")+1, date("Y"));
mysql_query("UPDATE ctc_reset SET reset=$tomorrow") or print_error(mysql_error());
mysql_query("DELETE FROM ctc_referers") or print_error(mysql_error());
@mysql_query("OPTIMIZE TABLE ctc_referers");
}
?>