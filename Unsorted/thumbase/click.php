<?php
$parts = explode('|',$QUERY_STRING);
$r = rand(0,1000);
if ($r>($parts[0]*10)) {
	header ("Location: ".rawurldecode($parts[1]));
} else {
	header ("Location: ".rawurldecode($parts[2]));
}
?>
