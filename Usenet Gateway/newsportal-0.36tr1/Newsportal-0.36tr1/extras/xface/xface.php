<?php

// this script wasn't tested.

if($_REQUEST['preview']==true){

$xface = $_REQUEST['xface'];

header("Content-Type: image/png");
passthru("echo '$xface'|uncompface -X |convert xbm:- png:-");
}
else {
header("HTTP/1.0 402 Forbidden");
}
?>
