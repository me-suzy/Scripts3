<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/

function htmlSmilies($message, $path) {

	$smilie[0] = ';-P';
	$smilieImage[0] = '22.gif';
	$smilie[1] = ':-$';
	$smilieImage[1] = '23.gif';
	$smilie[2] = '8-)';
	$smilieImage[2] = '24.gif';
	$smilie[3] = ':-@';
	$smilieImage[3] = '25.gif';
	$smilie[4] = ':-()';
	$smilieImage[4] = '26.gif';
	$smilie[5] = ':-O';
	$smilieImage[5] = '27.gif';
	$smilie[6] = ":(";
	$smilieImage[6] = '28.gif';
	$smilie[7] = ";-)";
	$smilieImage[7] = '29.gif';
	$smilie[8] = ':-S';
	$smilieImage[8] = '30.gif';
	$smilie[9] = ':-|';
	$smilieImage[9] = '31.gif';
	$smilie[10] = ':-P';
	$smilieImage[10] = '32.gif';
	$smilie[11] = ':-D';
	$smilieImage[11] = '33.gif';
	$smilie[12] = ':-(';
	$smilieImage[12] = '35.gif';
	$smilie[13] = ':-)';
	$smilieImage[13] = '36.gif';
	$smilie[14] = ':-';
	$smilieImage[14] = '34.gif';

	for($i=0; $i < count($smilie); $i++) {
		$message = str_replace($smilie[$i], "<image src=\"$path" . $smilieImage[$i] . "\">", $message);
	}
	return $message;
}
?>