<?
//Written by Matt Toigo

//CHECK FOR | Symbol in variables before logging

require('settings.inc');

$date = date('Y-m-d G:i:s');

if(!$_SESSION['hit'])
{
	$unique = 1;
	$_SESSION['hit'] = TRUE;
}
else
{
	$unique = 0;
}

$line = $date.'|'.$_SERVER[HTTP_USER_AGENT].'|'.$_SERVER[REMOTE_ADDR].'|'.$_SERVER[HTTP_REFERER].'|'.$_SERVER[REQUEST_URI].'|'.$unique."\n";

if($SETTINGS['admin_ip']!=$_SERVER['REMOTE_ADDR'])
{
	$file = fopen($SETTINGS[dir_name].'/'.$SETTINGS[logfile], 'a+');
	fwrite($file, $line);
	fclose($file);
}
?>