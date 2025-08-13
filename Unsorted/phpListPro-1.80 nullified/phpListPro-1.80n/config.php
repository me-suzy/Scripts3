<?
#################################################################################################
#
#  project		: phpListPro
#  filename 		: config.php
#  version		: 1.80
#  last modified by	: Erich Fuchs
#  supplied by          : CyKuH [WTN]
#  nullified by      	: CyKuH [WTN]          
#  purpose		: Configuration File
#  last modified	: 10/26/2002
#
#################################################################################################

#  Main Settings
#################################################################################################

$server         	= "localhost";                  	// Your mySQL Server, most cases "localhost"
$db_user		= "mysql_user";                  	// Your mySQL Username
$db_pass        	= "mysql_pass";                	 	// Your mySQL Password
$database       	= "phpListPro";                 	// Database Name

$list_name 		= "phpListPro TOPlist";			// the name of the top site list
$list_ver		= "1.80";				// phpListPro Version DO NOT change this !!!
$list_url 		= "http://www.domain.com/products/listpro"; 	// put FULL URL to top list folder, no trailing slash

$admin_email 		= "webmaster@domain.com";		// admin email address
$notify_email 		= "webmaster@domain.com";		// notify email address, blank ("") -> disabled

$voteimage_1 		= "images/vote_image1.gif";		// voteimage relativ to $listurl, blank ("") -> disabled
$voteimage_2 		= "images/vote_image2.gif";		// voteimage relativ to $listurl, blank ("") -> disabled
$voteimage_3 		= "images/vote_image3.gif";		// voteimage relativ to $listurl, blank ("") -> disabled

$max_listsize 		= 100;					// this is a top what list? 100, 50 and 25 is recommended
$max_banners 		= 10;					// amount of banners to show at the top of the list.
$break_afterbanners	= true;					// advertising break after banners
$break_time 		= 25;					// advertising break after every N entries
$min_votes_require	= 0;					// minimum votes required to display on list

$days_to_reset 		= 30;					// home many days before the list is reset to 0 votes and 0 hits 								// (0) -> disabled
$setinactive_on_reset	= false;				// on reset, set sites inactive if 0 votes since last reset
$setactive_on_reset	= false;				// on reset, set sites active if any votes since last reset

$vote_timeout 		= 12;					// how many hours until someone can vote for the same site again.
$votelog_timeout	= 480;					// how many hours until delete entries from votelog

$anti_cheating 		= true;					// enable anti-cheating (true) or not (false)
$gateway		= true;					// Front-door Gateway - if disabled, also $show_rating disabled !!!
$show_newicon		= 7;					// Enter days how long should show NEW-Icon for new Entries, false is disabled
$show_rating		= true;					// show rating (true) or not (false)
$show_ratingbar		= true;					// show ratingbar (true) or not (false)
$ratingbar_high		= 90;					// on this rating-percentage we change the ratingbar-color
$ratingbar_width 	= 40;					// ratingbar width in pixel
$ratingbar_height	= 10;					// ratingbar height in pixel

$max_banner_width 	= 468;					// max bannersize for entries
$max_banner_height 	= 60;					// max bannersize for entries
$max_banner_filesize	= 50000;				// max bannerfilesize for entries, 0 disable the check

$show_favwinfirsttime   = true;                                 // show "add to favorits"-window on IE for firsttime-users
$show_proctime		= true;					// show processing time
$show_useronline	= true;					// show users currently online (within 5 min.)
$show_counter		= true;					// show pageview counter
$show_randomlink	= true;					// show random link button
$show_search		= true;					// show search
$show_categories	= true;					// show categories dropdown menu, see also lang_*.php !!!
$show_languages		= true;					// show laguages dropdown menu, see also lang_*.php !!!
								//   users can switch to another language (cookiestored 1 year)

$auto_entersize		= true;					// in addsite enter autom. the maxbanner size
$listsorting		= "votes";				// to sort the list on "votes" or  "hits" or "rating"
$listsorting2		= "rating";				// then sort the list on "votes" or  "hits" or "rating"

$webmasterapproval	= false;				// every new site, must approved by webmaster with admin-panel
$emailapproval		= false;				// user must confirm notification-email to ensure valid e-mail-addr
$secret 		= "This is a secret key !!!"; 		// secret-key for hash-generation on user-e-mail-confirmation

$tilt_insp		= true;					// enable tilt-cheating-inspection (true) or not (false)
$tilt_insp_timeframe	= 5;					// tilt inspection timeframe in minutes
$tilt_insp_maxvotesok	= 10;					// how many votes comes OK in timeframe to block the site
$tilt_insp_maxvotesnok	= 5;					// how many votes comes NOK in timeframe to block the site
$tilt_insp_timeout	= 60;					// how long is the tilt-site blocked, in minutes
$tilt_insp_adminnotify	= true;					// notify the admin if a site is blocked (true) or not (false)
$tilt_insp_sitenotify	= true;					// notify the tilt-site-admin if his site is blocked (true) or not (false)

$cookievotesonly	= true;					// enable ONLY votes with cookie-enabled browsers !!! (ture) or not (false)
								// $gateway MUST true, for this function !!!
$cookiepath		="/products/listpro/";			// enter your listpro-url-path (eg. "/listpro/") here or "/" for hole domain

$exiturl		= "";					// if you want an popup-window on exit, enter url here. "" is disabled
$outexiturl[0]		= "http://www.youdomain.com";					// if you want an additional (advertising) exitwindow on outclick,
								// enter url here. enter "SELFLINK" to open VoteGateway, "" is disabled.
$outexiturl[1]		= "http://www.youdomain.com";					// possible url-array for random outexiturl generation
$outexiturl[2]		= "http://www.youdomain.com";					// add as many you want ...
$outexiturl[3]		= "http://www.youdomain.com";

$bad_words 		= array("shit","fuck","cock");		// enter your bad_words here
$bad_ips		= array("0.0.0.0","192.168.0.1");	// enter your bad_ips (banned) here
$banip_cookietime	= 365;					// how long is a bancookie valid :-) in days

$default_language	= "english";				// set default language

# Optical Settings (Fonts, Colors, ... done in style.css ...)
#################################################################################################

$style			= "style.css";				// name of your style-file comes here

$header 		= "header.inc";				// the header file that you want at the top of list.php or on all the pages
$menu			= "menu.inc";				// the menu file
$footer 		= "footer.inc";				// the footer file that you want at the bottom of list.php or all the pages

$inheader		= "$header";				// use this for special customizing of different headers
$addheader		= "$header";				// use this for special customizing of different headers
$editheader		= "$header";				// use this for special customizing of different headers
$helpheader		= "$header";				// use this for special customizing of different headers
$infooter		= "$footer";				// use this for special customizing of different footers
$addfooter		= "$footer";				// use this for special customizing of different footers
$editfooter		= "$footer";				// use this for special customizing of different footers
$helpfooter		= "$footer";				// use this for special customizing of different footers

$menu_separator		= " || ";				// seperator in menu, between links
$breaks_perpage         = 2;            			// after how many break's (see $break_time) should come a
								// pagebreak (eg. 2 x 25 = 50 entries) 0 is disabled

#################################################################################################
# DO NOT EDIT ANYTHING BEHIND THIS LINE
#################################################################################################

error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));

if ($HTTP_COOKIE_VARS["Language"] || $show_languages){
    if (is_file($returnpath."lang_".$HTTP_COOKIE_VARS["Language"].".php")) {
	$default_language=$HTTP_COOKIE_VARS["Language"];
    }
    $handle=opendir("./");
    while ($file = readdir($handle)) {
	if (ereg("lang_",$file)) {
            $language[count($language)] = substr($file,5,strlen($file)-9);
	}
    }
    closedir($handle);
}
require ($returnpath."lang_".$default_language.".php");
require ($returnpath."library.php");
?>