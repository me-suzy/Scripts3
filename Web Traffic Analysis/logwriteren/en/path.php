<?php

if (empty($PATH_TRANSLATED)) {
$pathinfo = $SCRIPT_FILENAME;
}else{
$pathinfo = $PATH_TRANSLATED;
}

$lastseparator = strrpos($pathinfo,"/");

if (!$lastseparator) {
$lastseparator = strrpos($pathinfo,"\\");
}

$pathname = substr($pathinfo,0,$lastseparator+1);

print "Maybe this file is in <br> $pathname";
?>