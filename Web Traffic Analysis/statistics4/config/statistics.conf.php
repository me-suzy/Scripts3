<?php
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// + statistics.conf.php - config for showlog
// + Initially written by Daniel Sokoll 2000 - http://www.sirsocke.de
// +
// + Release v4.0.0:	05.01.2005 - Daniel Sokoll
// + Last Change:	30.09.2005 - Daniel Sokoll
// +
// + This program is free software; you can redistribute it and/or modify it
// + under the terms of the GNU General Public License as published by the
// + Free Software Foundation; either version 2 of the License, or (at your
// + option) any later version.
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// --- weblog -------------------------------------------------------------------------------------------
$weblog_showlog_dir = "statistics4/";	// folder with SHOWlog-files
$weblog_anti_reload = 120;			// time (in minutes) before logging same IP again
$weblog_time_offset = 0;			// time (in minutes) that server divers from local time 
$weblog_ignore_ip = "127.,192.168.";	// do not log this ip-ranges
$weblog_check_for_files = true;		// check if logfiles exist else create them [ true/ false ]

// --- language -----------------------------------------------------------------------------------------
$lang_code = "en";		// default language
$lang_format = "us"; 		// date format [ eu/us ] 	eu: dd.mm.yyyy 
					//				us: yyyy-mm-dd

// --- display  -------------------------------------------------------------------------------------------
$page_menu = false;		// show additional menu on every statistic [ true/false ]
$detail_length = 30;		// number of entries per page (logfile view)
$tpl_to_use = "default";	// name of template you want to use
$show_detail_filter = true;	// enable filter for logfile
$benchmark = false;		// enable benchmark outputs
$hide_referer = "";		// hide this referer to viewers (devided by '|')


// --- login / user --------------------------------------------------------------------------------------
$login_require = false;			// require login for showlog [ true/false ]
$login_screen = false;			// show loginscreen [ true/false ]
$login_accept = "statistics.php";	// path / file to load if login accepted
$login_denied = "../";			// errorpage or errorpath if login denied

$login[1]["user"] = "all";			// username for showlog login
$login[1]["pass"] = "all";		// password for showlog login
$login[1]["right"] = "all";		// userrights [ all/view ]

$login[2]["user"] = "view";		// username for showlog login
$login[2]["pass"] = "view";		// password for showlog login
$login[2]["right"] = "view";		// userrights [ all/view ]

// --- leaving-button ----------------------------------------------------------------------------------
$leave["code"] = "";			// leavebutton on mainmenu, empty => no button
$leave["text"] = "back";		// leavebutton text (has to be defined in languagefile
						// on section default (e.g. back or close)
$leave["logout"] = true;		// logout if leaving? true / false

// --- files ---------------------------------------------------------------------------------------------
$log_dir = "logs/";			// logging
$vst_file = $log_dir."visit.dat";	// name of the anti-recount-file
$cnt_file = $log_dir."count.dat";	// name of the counter-file
$log_file = $log_dir."log.dat";		// name of the log-file
$bak_file = $log_dir."log.bak";		// name of the backup-file
$dl_file = $log_dir."backup.dat";	// name of the backup-time-file

// --- everything else ---------------------------------------------------------------------------------
$sl_vst_since = "2005-09-30";	// Visitors since this date (for counter)

// --- DO NOT TOUCH - only if you know what you do ---------------------------------------------
$ver_weblog  = "4.2.1";				// DO NOT TOUCH - Version of the logger
$showlog_ver = "4.6.7";				// DO NOT TOUCH - version of the showlog
$tpl_dir = "templates/";					// DO NOT TOUCH - folder with templates
$tpl_file = $tpl_dir."template.inc.php";		// DO NOT TOUCH - read template functions
$lang_dir = "languages/";				// DO NOT TOUCH - folder with languagefiles
$pic["dir"] = "pictures/";				// DO NOT TOUCH - pictures
$pic["fake"] = $pic["dir"]."fake.png";		// DO NOT TOUCH - fake-image ( clear 1x1 )
$cfg_dir = "config/";					// DO NOT TOUCH - configuration
$bot_file = $cfg_dir."bots.dat";			// DO NOT TOUCH - file with known bots
$modules_dir = "modules/";				// DO NOT TOUCH - path to modules
$ip2c_dir = "countries/";				// DO NOT TOUCH - path to ip2country data
$ip2c_db = $ip2c_dir."ip2country.db";		// DO NOT TOUCH - database of ip2country
$ip2c_fkt = $modules_dir."ip2country.php";	// DO NOT TOUCH - file with ip2countryfunction
$unknown_text = "UKN";				// DO NOT TOUCH - text for unknown content
?>