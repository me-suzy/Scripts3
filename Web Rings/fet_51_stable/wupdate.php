<?php

// FET UPDATE under WWW !!!!!!!

// Path to your current FET installation
$fet_path='/home/www/username/yourdomain.com/html/fet';

// Path to HTML
$html_path='/home/www/username/yourdomain.com/html';


///////////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////
/////////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////
///////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////


echo "Copying main scripts...\n<br>";

if(!@copy('files/script/html_drawer.inc.txt',$fet_path.'/includes/html_drawer.inc.php')) exit("<font color=red>Can't copy Script file to $fet_path/includes/html_drawer.inc.php\n");
if(!@copy('files/script/webmasters.txt',$fet_path.'/webmasters.php')) exit("<font color=red>Can't copy Script file to $fet_path/webmasters.php\n");
if(!@copy('files/script/index.txt',$fet_path.'/index.php')) exit("<font color=red>Can't copy Script file to $fet_path/index.php\n");
if(!@copy('files/script/main.inc.txt',$fet_path.'/includes/main.inc.php')) exit("<font color=red>Can't copy Script file to $fet_path/includes/main.inc.php\n");
if(!@copy('files/script/settings_uploader.inc.txt',$fet_path.'/includes/settings_uploader.inc.php')) exit("<font color=red>Can't copy Script file to $fet_path/includes/settings_uploader.inc.php\n");
if(!@copy('files/script/user_checker.inc.txt',$fet_path.'/includes/user_checker.inc.php')) exit("<font color=red>Can't copy Script file to $fet_path/includes/user_checker.inc.php\n");
if(!@copy('files/script/wap.inc.txt',$fet_path.'/includes/wap.inc.php')) exit("<font color=red>Can't copy Script file to $fet_path/includes/wap.inc.php\n");
if(!@copy('files/script/mastercron.dat',$fet_path.'/cron/mastercron.php')) exit("<font color=red>Can't copy Script file to $fet_path/cron/mastercron.php\n");
if(!@copy('files/script/topcron.dat',$fet_path.'/cron/topcron.php')) exit("<font color=red>Can't copy Script file to $fet_path/cron/topcron.php\n");
if(!@copy('files/script/index.dat',$html_path.'/index.php')) exit("<font color=red>Can't copy Script file to $html_path/index.php\n");
if(!@copy('files/script/out.dat',$html_path.'/out.php')) exit("<font color=red>Can't copy Script file to $html_path/out.php\n");

echo "Done\n\n";

?>
