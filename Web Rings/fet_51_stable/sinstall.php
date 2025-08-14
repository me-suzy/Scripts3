<?php

// FET Installator under SHELL !!!!!!!

// Path to all html files of youe domain like index.html ect.
$html_path='/home/www/username/yourdomain.com/html';

// Site's domain without www.
$fet_domain='yourdomain.com';

// FET's toplist templates path
$fet_top_templates='/home/www/username/yourdomain.com/html/fet/templates';

// PHP path
$php_path='/usr/local/bin/php';


///////////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////
/////////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////
///////////////////////// DO NOT CHANGE ANYTHING BELOW THIS LINE ////////////////////////////////////


$fet_path=$html_path.'/fet';
echo "Creating FET working dir...\n";
if(!file_exists($fet_path))
    if(!@mkdir($fet_path)) exit("Can't create $fet_path\n");
echo "Done\n\n";

echo "Creating common dirs...\n";
if(!file_exists($fet_path.'/backups'))
    if(!@mkdir($fet_path.'/backups')) exit("Can't create $fet_path/backups\n");
@chmod($fet_path.'/backups',0777);
if(!file_exists($fet_path.'/cron'))
    if(!@mkdir($fet_path.'/cron')) exit("Can't create $fet_path/cron\n");
@chmod($fet_path.'/cron',0777);
if(!file_exists($fet_path.'/data'))
    if(!@mkdir($fet_path.'/data')) exit("Can't create $fet_path/data\n");
@chmod($fet_path.'/data',0777);
if(!file_exists($fet_path.'/includes'))
    if(!@mkdir($fet_path.'/includes')) exit("Can't create $fet_path/includes\n");
@chmod($fet_path.'/includes',0777);
if(!file_exists($fet_path.'/logs'))
    if(!@mkdir($fet_path.'/logs')) exit("Can't create $fet_path/logs\n");
@chmod($fet_path.'/logs',0777);
if(!file_exists($fet_path.'/logs/in_archive'))
    if(!@mkdir($fet_path.'/logs/in_archive')) exit("Can't create $fet_path/logs/in_archive\n");
@chmod($fet_path.'/logs/in_archive',0777);
if(!file_exists($fet_path.'/logs/out_archive'))
    if(!@mkdir($fet_path.'/logs/out_archive')) exit("Can't create $fet_path/logs/out_archive\n");
@chmod($fet_path.'/logs/out_archive',0777);
if(!file_exists($fet_path.'/logs/checker'))
    if(!@mkdir($fet_path.'/logs/checker')) exit("Can't create $fet_path/logs/checker\n");
@chmod($fet_path.'/logs/checker',0777);
if(!file_exists($fet_top_templates))
    if(!@mkdir($fet_top_templates,0777)) exit("Can't create $fet_top_templates\n");
@chmod($fet_top_templates,0777);

echo "Done\n\n";

echo "Copying data files...\n";

if(!@copy('files/data/htaccess.txt',$fet_path.'/data/.htaccess')) exit("Can't copy data file to $fet_path/data/.htaccess\n");
if(!@copy('files/data/htaccess.txt',$fet_path.'/cron/.htaccess')) exit("Can't copy data file to $fet_path/cron/.htaccess\n");
if(!@copy('files/data/htaccess.txt',$fet_path.'/logs/.htaccess')) exit("Can't copy data file to $fet_path/logs/.htaccess\n");
if(!@copy('files/data/htaccess.txt',$fet_path.'/backups/.htaccess')) exit("Can't copy data file to $fet_path/backups/.htaccess\n");

if(!@copy('files/data/admin_settings.txt',$fet_path.'/data/admin_settings.dat')) exit("Can't copy data file to $fet_path/data/admin_settings.dat\n");
if(!@chmod($fet_path.'/data/admin_settings.dat',0777)) exit("Can't set permissions on file $fet_path/data/admin_settings.dat\n");

if(!@copy('files/data/auto_signup.txt',$fet_path.'/data/auto_signup.dat')) exit("Can't copy data file to $fet_path/data/auto_signup.dat\n");
if(!@chmod($fet_path.'/data/auto_signup.dat',0777)) exit("Can't set permissions on file $fet_path/data/auto_signup.dat\n");

if(!@copy('files/data/banned_ips.txt',$fet_path.'/data/banned_ips.dat')) exit("Can't copy data file to $fet_path/data/banned_ips.dat\n");
if(!@chmod($fet_path.'/data/banned_ips.dat',0777)) exit("Can't set permissions on file $fet_path/data/banned_ips.dat\n");

if(!@copy('files/data/blacklist.txt',$fet_path.'/data/blacklist.dat')) exit("Can't copy data file to $fet_path/data/blacklist.dat\n");
if(!@chmod($fet_path.'/data/blacklist.dat',0777)) exit("Can't set permissions on file $fet_path/data/blacklist.dat\n");

if(!@copy('files/data/checker.txt',$fet_path.'/data/checker.dat')) exit("Can't copy data file to $fet_path/data/checker.dat\n");
if(!@chmod($fet_path.'/data/checker.dat',0777)) exit("Can't set permissions on file $fet_path/data/checker.dat\n");

if(!@copy('files/data/day_clicks.txt',$fet_path.'/data/day_clicks.dat')) exit("Can't copy data file to $fet_path/data/day_clicks.dat\n");
if(!@chmod($fet_path.'/data/day_clicks.dat',0777)) exit("Can't set permissions on file $fet_path/data/day_clicks.dat\n");

if(!@copy('files/data/day_in.txt',$fet_path.'/data/day_in.dat')) exit("Can't copy data file to $fet_path/data/day_in.dat\n");
if(!@chmod($fet_path.'/data/day_in.dat',0777)) exit("Can't set permissions on file $fet_path/data/day_in.dat\n");

if(!@copy('files/data/day_out.txt',$fet_path.'/data/day_out.dat')) exit("Can't copy data file to $fet_path/data/day_out.dat\n");
if(!@chmod($fet_path.'/data/day_out.dat',0777)) exit("Can't set permissions on file $fet_path/data/day_out.dat\n");

if(!@copy('files/data/day_timing.txt',$fet_path.'/data/day_timing.dat')) exit("Can't copy data file to $fet_path/data/day_timing.dat\n");
if(!@chmod($fet_path.'/data/day_timing.dat',0777)) exit("Can't set permissions on file $fet_path/data/day_timing.dat\n");

if(!@copy('files/data/exit_urls.txt',$fet_path.'/data/exit_urls.dat')) exit("Can't copy data file to $fet_path/data/exit_urls.dat\n");
if(!@chmod($fet_path.'/data/exit_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/exit_urls.dat\n");

if(!@copy('files/data/exout_urls.txt',$fet_path.'/data/exout_urls.dat')) exit("Can't copy data file to $fet_path/data/exout_urls.dat\n");
if(!@chmod($fet_path.'/data/exout_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/exout_urls.dat\n");

if(!@copy('files/data/exout_urls.txt',$fet_path.'/data/exout_urls.dat')) exit("Can't copy data file to $fet_path/data/exout_urls.dat\n");
if(!@chmod($fet_path.'/data/exout_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/exout_urls.dat\n");

if(!@copy('files/data/exout_urls.txt',$fet_path.'/data/exout_urls.dat')) exit("Can't copy data file to $fet_path/data/exout_urls.dat\n");
if(!@chmod($fet_path.'/data/exout_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/exout_urls.dat\n");

if(!@copy('files/data/groups.txt',$fet_path.'/data/groups.dat')) exit("Can't copy data file to $fet_path/data/groups.dat\n");
if(!@chmod($fet_path.'/data/groups.dat',0777)) exit("Can't set permissions on file $fet_path/data/groups.dat\n");

if(!@copy('files/data/hour_clicks.txt',$fet_path.'/data/hour_clicks.dat')) exit("Can't copy data file to $fet_path/data/hour_clicks.dat\n");
if(!@chmod($fet_path.'/data/hour_clicks.dat',0777)) exit("Can't set permissions on file $fet_path/data/hour_clicks.dat\n");

if(!@copy('files/data/hour_in.txt',$fet_path.'/data/hour_in.dat')) exit("Can't copy data file to $fet_path/data/hour_in.dat\n");
if(!@chmod($fet_path.'/data/hour_in.dat',0777)) exit("Can't set permissions on file $fet_path/data/hour_in.dat\n");

if(!@copy('files/data/hour_out.txt',$fet_path.'/data/hour_out.dat')) exit("Can't copy data file to $fet_path/data/hour_out.dat\n");
if(!@chmod($fet_path.'/data/hour_out.dat',0777)) exit("Can't set permissions on file $fet_path/data/hour_out.dat\n");

if(!@copy('files/data/hour_timing.txt',$fet_path.'/data/hour_timing.dat')) exit("Can't copy data file to $fet_path/data/hour_timing.dat\n");
if(!@chmod($fet_path.'/data/hour_timing.dat',0777)) exit("Can't set permissions on file $fet_path/data/hour_timing.dat\n");

if(!@copy('files/data/in_settings.txt',$fet_path.'/data/in_settings.dat')) exit("Can't copy data file to $fet_path/data/in_settings.dat\n");
if(!@chmod($fet_path.'/data/in_settings.dat',0777)) exit("Can't set permissions on file $fet_path/data/in_settings.dat\n");

if(!@copy('files/data/lang_click_urls.txt',$fet_path.'/data/lang_click_urls.dat')) exit("Can't copy data file to $fet_path/data/lang_click_urls.dat\n");
if(!@chmod($fet_path.'/data/lang_click_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/lang_click_urls.dat\n");

if(!@copy('files/data/lang_factors.txt',$fet_path.'/data/lang_factors.dat')) exit("Can't copy data file to $fet_path/data/lang_factors.dat\n");
if(!@chmod($fet_path.'/data/lang_factors.dat',0777)) exit("Can't set permissions on file $fet_path/data/lang_factors.dat\n");

if(!@copy('files/data/lang_hit_urls.txt',$fet_path.'/data/lang_hit_urls.dat')) exit("Can't copy data file to $fet_path/data/lang_hit_urls.dat\n");
if(!@chmod($fet_path.'/data/lang_hit_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/lang_hit_urls.dat\n");

if(!@copy('files/data/links_activity.txt',$fet_path.'/data/links_activity.dat')) exit("Can't copy data file to $fet_path/data/links_activity.dat\n");
if(!@chmod($fet_path.'/data/links_activity.dat',0777)) exit("Can't set permissions on file $fet_path/data/links_activity.dat\n");

if(!@copy('files/data/long_forces.txt',$fet_path.'/data/long_forces.dat')) exit("Can't copy data file to $fet_path/data/long_forces.dat\n");
if(!@chmod($fet_path.'/data/long_forces.dat',0777)) exit("Can't set permissions on file $fet_path/data/long_forces.dat\n");

if(!@copy('files/data/main_pages.txt',$fet_path.'/data/main_pages.dat')) exit("Can't copy data file to $fet_path/data/main_pages.dat\n");
if(!@chmod($fet_path.'/data/main_pages.dat',0777)) exit("Can't set permissions on file $fet_path/data/main_pages.dat\n");

if(!@copy('files/data/new_traders.txt',$fet_path.'/data/new_traders.dat')) exit("Can't copy data file to $fet_path/data/new_traders.dat\n");
if(!@chmod($fet_path.'/data/new_traders.dat',0777)) exit("Can't set permissions on file $fet_path/data/new_traders.dat\n");

if(!@copy('files/data/nocookie_urls.txt',$fet_path.'/data/nocookie_urls.dat')) exit("Can't copy data file to $fet_path/data/nocookie_urls.dat\n");
if(!@chmod($fet_path.'/data/nocookie_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/nocookie_urls.dat\n");

if(!@copy('files/data/noref_urls.txt',$fet_path.'/data/noref_urls.dat')) exit("Can't copy data file to $fet_path/data/noref_urls.dat\n");
if(!@chmod($fet_path.'/data/noref_urls.dat',0777)) exit("Can't set permissions on file $fet_path/data/noref_urls.dat\n");

if(!@copy('files/data/out_settings.txt',$fet_path.'/data/out_settings.dat')) exit("Can't copy data file to $fet_path/data/out_settings.dat\n");
if(!@chmod($fet_path.'/data/out_settings.dat',0777)) exit("Can't set permissions on file $fet_path/data/out_settings.dat\n");

if(!@copy('files/data/referrers.txt',$fet_path.'/data/referrers.dat')) exit("Can't copy data file to $fet_path/data/referrers.dat\n");
if(!@chmod($fet_path.'/data/referrers.dat',0777)) exit("Can't set permissions on file $fet_path/data/referrers.dat\n");

if(!@copy('files/data/rules.txt',$fet_path.'/data/rules.dat')) exit("Can't copy data file to $fet_path/data/rules.dat\n");
if(!@chmod($fet_path.'/data/rules.dat',0777)) exit("Can't set permissions on file $fet_path/data/rules.dat\n");

if(!@copy('files/data/short_forces.txt',$fet_path.'/data/short_forces.dat')) exit("Can't copy data file to $fet_path/data/short_forces.dat\n");
if(!@chmod($fet_path.'/data/short_forces.dat',0777)) exit("Can't set permissions on file $fet_path/data/short_forces.dat\n");

if(!@copy('files/data/short_stats.txt',$fet_path.'/data/short_stats.dat')) exit("Can't copy data file to $fet_path/data/short_stats.dat\n");
if(!@chmod($fet_path.'/data/short_stats.dat',0777)) exit("Can't set permissions on file $fet_path/data/short_stats.dat\n");

if(!@copy('files/data/stats_history.txt',$fet_path.'/data/stats_history.dat')) exit("Can't copy data file to $fet_path/data/stats_history.dat\n");
if(!@chmod($fet_path.'/data/stats_history.dat',0777)) exit("Can't set permissions on file $fet_path/data/stats_history.dat\n");

if(!@copy('files/data/top_pages.txt',$fet_path.'/data/top_pages.dat')) exit("Can't copy data file to $fet_path/data/top_pages.dat\n");
if(!@chmod($fet_path.'/data/top_pages.dat',0777)) exit("Can't set permissions on file $fet_path/data/top_pages.dat\n");

if(!@copy('files/data/top_settings.txt',$fet_path.'/data/top_settings.dat')) exit("Can't copy data file to $fet_path/data/top_settings.dat\n");
if(!@chmod($fet_path.'/data/top_settings.dat',0777)) exit("Can't set permissions on file $fet_path/data/top_settings.dat\n");

if(!@copy('files/data/traders.txt',$fet_path.'/data/traders.dat')) exit("Can't copy data file to $fet_path/data/traders.dat\n");
if(!@chmod($fet_path.'/data/traders.dat',0777)) exit("Can't set permissions on file $fet_path/data/traders.dat\n");

echo "Done\n\n";

echo "Copying main scripts...\n";

if(!@copy('files/script/exit.txt',$html_path.'/exit.php')) exit("Can't copy Script file to $html_path/exit.php\n");
if(!@copy('files/script/html_drawer.inc.txt',$fet_path.'/includes/html_drawer.inc.php')) exit("Can't copy Script file to $fet_path/includes/html_drawer.inc.php\n");
if(!@copy('files/script/index.dat',$html_path.'/index.php')) exit("Can't copy Script file to $html_path/index.php\n");
if(!@copy('files/script/index.txt',$fet_path.'/index.php')) exit("Can't copy Script file to $fet_path/index.php\n");
if(!@copy('files/script/main.inc.txt',$fet_path.'/includes/main.inc.php')) exit("Can't copy Script file to $fet_path/includes/main.inc.php\n");
if(!@copy('files/script/mastercron.dat',$fet_path.'/cron/mastercron.php')) exit("Can't copy Script file to $fet_path/cron/mastercron.php\n");
if(!@copy('files/script/out.dat',$html_path.'/out.php')) exit("Can't copy Script file to $html_path/out.php\n");
if(!@copy('files/script/services.inc.txt',$fet_path.'/includes/services.inc.php')) exit("Can't copy Script file to $fet_path/includes/services.inc.php\n");
if(!@copy('files/script/settings_uploader.inc.txt',$fet_path.'/includes/settings_uploader.inc.php')) exit("Can't copy Script file to $fet_path/includes/settings_uploader.inc.php\n");
if(!@copy('files/script/topcron.dat',$fet_path.'/cron/topcron.php')) exit("Can't copy Script file to $fet_path/cron/topcron.php\n");
if(!@copy('files/script/tradechecker.dat',$fet_path.'/cron/tradechecker.php')) exit("Can't copy Script file to $fet_path/cron/tradechecker.php\n");
if(!@copy('files/script/user_checker.inc.txt',$fet_path.'/includes/user_checker.inc.php')) exit("Can't copy Script file to $fet_path/includes/user_checker.inc.php\n");
if(!@copy('files/script/wap.inc.txt',$fet_path.'/includes/wap.inc.php')) exit("Can't copy Script file to $fet_path/includes/wap.inc.php\n");
if(!@copy('files/script/webmasters.txt',$fet_path.'/webmasters.php')) exit("Can't copy Script file to $fet_path/webmasters.php\n");
if(!@copy('files/script/interface_color_conf.inc.txt',$fet_path.'/includes/interface_color_conf.inc.php')) exit("Can't copy Script file to $fet_path/includes/interface_color_conf.inc.php\n");
if(!$sf=@file('files/script/settings.txt')) exit("Can't read Script file files/script/settings.txt\n");
$ss=@join('',$sf);
$ss=str_replace('####FET_WORKING_DIR####',$fet_path,$ss);
$ss=str_replace('####FET_HTML_PATH####',$html_path,$ss);
$ss=str_replace('####FET_DOMAIN####',$fet_domain,$ss);
$ss=str_replace('####FET_TOP_TEMPLATES####',$fet_top_templates,$ss);
$ss=str_replace('####PHP_PATH####',$php_path,$ss);
if(!$fp=@fopen($fet_path.'/settings.php','w')) exit("Can't write Script file to $fet_path/settings.php\n");
@fwrite($fp,$ss);
@fclose($fp);

echo "Done\n\n";

echo "You have to add following lines to the crontab to make the script operational:\n\n";
echo "*/1 * * * * cd $fet_path/cron; $php_path mastercron.php 1>/dev/null 2>/dev/null\n";
echo "*/15 * * * * cd $fet_path/cron; $php_path topcron.php 1>/dev/null 2>/dev/null\n";
echo "30 * * * * cd $fet_path/cron; $php_path tradechecker.php 1>/dev/null 2>/dev/null\n\n";
?>
