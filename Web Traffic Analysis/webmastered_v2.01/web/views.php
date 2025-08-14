<?php
$filelocation = "ip.txt";
$newfile = fopen($filelocation,"r");
$content = fread($newfile, filesize($filelocation));
fclose($newfile);

print('<BODY STYLE="margin:0;font:10px verdana">'.substr_count($content, $ip).'</BODY>');


?>