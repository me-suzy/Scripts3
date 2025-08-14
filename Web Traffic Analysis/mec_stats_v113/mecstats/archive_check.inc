<?php
//Written By Matt Toigo

$current_size = filesize('log.txt');
$current_size = round($current_size/1000, 2);
echo('Your log file is '.$current_size.' kilobytes<br><br>');

if(($SETTINGS['auto_archive'] && filesize($SETTINGS['logfile']) > $SETTINGS['archive_limit']))
{
	//ARCHIVE LOG FILE
	require('archive.php');
	
	//Compare archive date to current date to make sure archive went ok
	if(file_exists('arch.txt'))
	{
		$archivefile = fopen('arch.txt', 'r');
		while(!feof($archivefile))
		{
			$line = fgets($archivefile, 1024);
			$ld = explode('|', $line);
		
			if($ld[0]=='ARCH_DATE')
				$archdate = $ld[1];
		}
	}
	
	//Fixes bug with $archdate having an extra line ending
	$archdate = preg_replace('/\n/', '', $archdate);
	
	if($archdate==date('YmdHi'))
	{
		echo('Your Stats Have Been Successfully Archived.');
		//Clear out log file
		$logfile = fopen($SETTINGS['logfile'], 'w');
		fwrite($logfile, '');
		fclose($logfile);
	}
	else
	{
		echo('There Was An Error Archiving Your Stats<br><br>');
		echo('Check to make sure that your arch.txt has the proper permissions to be written to.');
	}
}



?>