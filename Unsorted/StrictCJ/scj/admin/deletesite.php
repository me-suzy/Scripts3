<?php
include("variables.inc.php");
include($dbconnect);
$sql = "DELETE FROM $tablename WHERE NOUSER='".$_SERVER['QUERY_STRING']."';";
mysql_query($sql);
$sql = "DELETE FROM $tday WHERE USER='".$_SERVER['QUERY_STRING']."';";
mysql_query($sql);
$sql = "DELETE FROM $thour WHERE USER='".$_SERVER['QUERY_STRING']."';";
mysql_query($sql);
//header("Location: showdb.php");
?>
<script language="JavaScript">window.close();</script>
