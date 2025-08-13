<?

#################################################################################################
#
#  project           	: PHPBazar
#  filename          	: config.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose	     	: Configuraion File
#  version           	: 1.60
#  last modified     	: 10/26/2002
#
#################################################################################################



#  mySql Configuration

#################################################################################################

$server                 = "localhost";                          // mySQL Server, most cases "localhost" or like "www.mysqlserver.com:3306"

$db_user 		= "mysql_user";				// mySQL Username

$db_pass 		= "mysql_pass";				// mySQL Password

$database 		= "phpBazar";				// database-name of phpBazar-Database



#  Chat Interface Configuration (optional)

#################################################################################################

$chat_enable		= false;				// Chat enabled

$chat_interface		= "";					// Chat interface definition (e.g int_phpBazarChat.php)

$chat_server 		= "$server";				// mySQL Server, most cases "localhost"

$chat_db_user        	= "$db_user";                       	// mySQL Username

$chat_db_pass		= "$db_pass";                           // mySQL Password

$chat_database		= "$database"; 				// database-name of Chat-Script



#  Forum Interface Configuration (optional)

#################################################################################################

$forum_enable		= false;				// Forum enabled

$forum_interface	= "";					// Forum interface definition (e.g. int_phpBB-V2.x.php)

$forum_server 		= "$server";				// mySQL Server, most cases "localhost"

$forum_db_user          = "$db_user";                       	// mySQL Username

$forum_db_pass          = "$db_pass";                           // mySQL Password

$forum_database 	= "$database";				// database-name of Forum-Script



#  Path & URL Configuration

#################################################################################################

$url_to_start		= "http://www.domain.com/bazar";	// complete URL to start

$convertpath		= "AUTO";				// if set to "AUTO" -> Picture-Upload WITHOUT convert-program

#$convertpath		= "/home/httpd/phpexec/convert";	// complete PATH from rootdir (/) to convert-program

$bazar_dir		= "/home/httpd/domain.com/bazar";	// complete PATH from rootdir (/) to bazar-script

$admin_dir 		= "admin";				// Admin PATH from $bazar_dir without begin and trailing slashes

$backup_dir		= "admin/backup";			// Backup PATH from $bazar_dir without begin and trailing slashes

$image_dir		= "images";				// Image PATH from $bazar_dir without begin and trailing slashes

$languagebase_dir	= "lang";				// Language PATH from $bazar_dir without begin and trailing slashes

$fix_tmp_dir		= "";					// Set ONLY if your UPLOAD_TMPDIR is not working (Upload-Problems)



#  Name & Version Configuration

#################################################################################################

$bazar_name 		= "phpBazar";				// the name of the bazar site

$bazar_copyright	= "YourCompany Inc.";			// the name of your company

$bazar_version		= "1.60";				// PLEASE DO NOT EDIT THIS

$book_version		= "1.60";				// PLEASE DO NOT EDIT THIS

$members_version	= "1.60";				// PLEASE DO NOT EDIT THIS



#  Mail Configuration

#################################################################################################

$admin_email 		= "webmaster@domain.com";		// admin email address

$reg_notify		= ""; 					// Resistration Notify, if empty ("") -> NO notify will be send

$conf_notify		= "webmaster@domain.com"; 		// Confirmation Notify, if empty ("") -> NO notify will be send

$mail_notify		= "webmaster@domain.com"; 		// Mail Reply Notify, if empty ("") -> NO notify will be send

$ad_notify		= "webmaster@domain.com"; 		// New Ad Notify, if empty ("") -> NO notify will be send

$aded_notify		= "webmaster@domain.com"; 		// Edit Ad Notify, if empty ("") -> NO notify will be send

$gb_notify		= "webmaster@domain.com"; 		// New Guestbook Notify

$no_confirmation	= false;				// NO registration confirmation mail - !!! DO NOT ENABLE THIS !!!

$confirm_mail		= true;					// Send Mail to User after confirmation

$anonymous_mail		= false;				// Answer to Ads, if NOT logged in !!! Attention to Spammers !!!

$webmail_enable		= true;					// WebMail enabled

$webmail_notify		= true;					// Notify Member via Mail when new WebMail arrived

$webmail_path		= "uploads/webmailattachments";		// Upload PATH from $bazar_dir without begin and trailing slashes

$webmail_storedays	= 90;					// How long should Webmails stored (prune with ./admin/cleanup.php), "" -> disabled



#  Picture-Handling Configuration

#################################################################################################

$pic_enable		= true;					// Ad-Pictures enabled

$pic_path		= "uploads/images";			// Upload PATH from $bazar_dir without begin and trailing slashes

$pic_icon		= "icons/photo2.gif";			// Show this icon in Ads-Overview, if $pic_prelowres is ""

#

$pic_noicon		= "icons/signno.gif";			// Show this icon in Ads-Overview, if no picture is availiable, "" -> disabled

$pic_database		= false;				// DO NOT USE THIS, SLOW !!! Store Picture's Binary's in Database

$pic_maxsize 		= "300000";  				// Maximum Uploadsize per Picture (in Bytes)

$pic_res 		= "400x300"; 				// max. Standard Picture

$pic_lowres 		= "85x120";  				// max. Thumbnail Picture

$pic_prelowres          = "40x70";                              // max. Preview Thumbnail Picture, "" -> disabled, see $pic_icon

$pic_width		= "440";     				// Popup Window

$pic_height		= "440";     				// Popup Window

$pic_quality 		= "80";         			// JPEG-Quality in % (only with convert)



#  Attachment-Handling Configuration

#################################################################################################

$att_enable		= true;					// Ad-Attachments enabled

$att_path		= "uploads/attachments";		// Upload PATH from $bazar_dir without begin and trailing slashes

$att_icon		= "icons/att3.gif";			// Show this icon in Ads-Overview, if attachment is present

$att_maxsize 		= "300000";  				// Maximum Uploadsize per Attachment (in Bytes)



#  Language Configuration

#################################################################################################

$language_default	= "en";					// Default Language (for new users)

$show_languages		= true;					// show Language-switch-option (true) or not (false)

$language[0]		= "en";					// Language-Choice 1

$language[1]		= "dt";					// Language-Choice 2

#$language[2]		= "fr";					// Language-Choice 3

#$language[3]		= "it";					// Language-Choice 4



#  System Configuration

#################################################################################################

$compress_output	= false;				// SPEED :-) works only on PHP 4.0.4 (above) with compiled zlib

$logging_enable		= true;					// Enable Logging of Bazar-Events

$logging_days		= 30;					// How many days should stored (prune in cleanup.php)

$dateformat		= "M. j Y, g:i a"; 			// "j.M.Y H:i:s" Dateformat, see http://www.php.net/manual/en/function.date.php

$genders		= array("m","f","a","b","c");		// Gender's (see also ./lang/XX/variables.php)

$limit 			= array(5 ,1000);                       // Text-Input limits.  First number is minimum amount of characters, second number is maximum

$floodprotect		= "24";					// Floodprotection, set ad-flood-timeout in hours, ("") -> Floodprotect-Function disabled

$floodprotect_ad	= "3";					// Floodprotection, set how many ads can be added within $floodprotect time-frame

$floodprotect2		= "24";					// Floodprotection, set email-flood-timeout in hours, ("") -> Floodprotect-Function disabled

$floodprotect_mail	= "10";					// Floodprotection, set how many mails can be send within $floodprotect time-frame

$location_text  	= false;				// Show location as text(true) or option-field(false)

$registermembersex	= true;					// New Members MUST enter there sex (true) or not (false)

$memberoffset		= 1000;					// Showing MemberNumber with a Offset (addition), UserID is untouched

$login_cookie_time	= 12;					// How long is LOGIN Cookie valid (in hours)

$vote_cookie_time 	= 24;					// How long is VOTED Time valid (in hours)

$passphrase_cookie_time = 24;                                   // How long is Category Passphrase valid (in hours)

$auto_login		= true;					// after successfull confirmation -> auto-login

$force_addad		= false;				// after successfull confirmation -> a add MUST placed

$addurations		= "week";				// Enable Adduration in Week's ("week") or in Day's ("day") or no timeout ("")

$adduration[0]		= 48;					// First Option for Adduration-Value (max. 9999)

$adduration[1]		= 44;					//     x Option for Adduration-Value (max. 9999)

$adduration[2]		= 40;					//     x Option for Adduration-Value (max. 9999)

$adduration[3]		= 36;					//     x Option for Adduration-Value (max. 9999)

$adduration[4]		= 32;					//     x Option for Adduration-Value (max. 9999)

$adduration[5]		= 28;					//     x Option for Adduration-Value (max. 9999)

$adduration[6]		= 24;					//     x Option for Adduration-Value (max. 9999)

$adduration[7]		= 20;					//     x Option for Adduration-Value (max. 9999)

$adduration[8]		= 16;					//     x Option for Adduration-Value (max. 9999)

$adduration[9]		= 12;					//     x Option for Adduration-Value (max. 9999)

$adduration[10]		= 8;					//     x Option for Adduration-Value (max. 9999)

$adduration[11]		= 4;					//     x Option for Adduration-Value (max. 9999)

$timeoutnotify		= 3;					// Day's to notify the Ad-owner before timeout, 0 to disable notify

$timeoutconfirm		= 30;					// Day's to increase after timeout-notify-mail is confirmed

$timeoutmax		= 90;					// Day's maximum, to expand the timeout

$search_sort		= "adeditdate desc";			// mySQL ORDER-String, Syntax: "ads_table_fieldname desc/asc" eg. "adeditdate desc" or "answered asc" ...

$ad_sort		= "addate desc";			// mySQL ORDER-String, Syntax: "ads_table_fieldname desc/asc" eg. "addate desc" or "viewed asc" ...

$top_sortorder		= "viewed desc";			// TopAds mySQL ORDER-String, Syntax: "ads_table_fieldname desc/asc" eg. "addate desc" or "viewed asc" ...

$top_maximum		= "5";					// TopAds Counter (how many ads should display on TopList)

$catnotify		= true;					// Enable Category-Notify-System (true) or not (false)

$adapproval		= false;				// Webmaster (Moderator) approval is required for new ADs

$adeditapproval		= false;				// Webmaster (Moderator) approval is required for edited ADs

$adautoflush		= false;				// AUTO Delete timed-out ads (true) or not (false) - Manually flush timed-out ads in Admin-Panel

$addfavorits		= true;					// Show "Add Favorits Window" to first time surfers ONCE

$really_del_memb	= false;				// really delete user (true) or only reset passwd (false) BETTER for History.

$forumfreeread		= true; 				// Anonymous can read forum

$bazarfreeread		= true; 				// Anonymous can read, search Ad's, but NO new, edit, answer Ad

$picturesfree		= true; 				// Anonymous can view pictures (or picturelib)

$linksfree		= true; 				// Anonymous can view links

$guestbookfree  	= true;					// Anonymous can read and add Entry's to Guestbook

$votefree		= true;					// Anonymous can vote



#  Display Configuration

#################################################################################################

$STYLE			= "style.css";				// specify your CascadeStyleSheet-File (*.css) EDIT THIS FILE FOR COLORS & FONTS

$HEADER 		= "header.inc";				// the header file that you want at the top of all the pages

$FOOTER 		= "footer.inc";				// the footer file that you want at the bottom of all the pages

$show_useronline	= true; 				// show online users within LoginWindow - turn off for heavy load sites

$show_useronline_detail	= true;					// show online users details

$show_advert1		= true;					// show the advertising's on the left side or not

$show_advert2		= true;					// show the advertising's on the left bottom side or not

$show_picadday		= true;					// show the picture ad of the day on the left bottom side or not (instead of advert2)  false -> disabled, true -> enabled (timeout 24 hours), n -> enabled (timeout n hours)

$show_advert3		= true;					// show the advertising's on the right side or not

$show_news		= true;					// show the news on the right side or not

$show_votes		= true;					// show the votes on the right side or not

$show_proctime		= true;					// show processing time (bottom of every page) // !!! works NOT on Windows-Systems !!!

$show_url		= true;					// show members gotourl-icon within ads if given in members-section or not

$show_adrating		= true;					// show rating on every ad

$adrating_icon		= "icons/rating_red.gif";		// AdRating Icon, if $show_adrating is enabled

$show_members_overview	= true;					// show Memberoverview

$show_members_details	= true;					// show Memberdetails

$show_members_ads	= true;					// show Memberads

$show_membersortorder	= true;					// show Sort Order on Member overview

$show_adsortorder	= true;					// show Sort Order on Ad overview

$show_newicon		= 7;					// how many days is the NEW-icon shown, 0 disable

$perpage		= 7;					// how many db-row's perpage (gb, ad's)

$pperpage		= 7;					// how many pagebreak-links

$table_width		= 470;					// Middle Table Width

$table_height		= 421;					// Middle Table Height

$wel_table_width	= 738;					// Welcome Middle Table Width

$table_width_menu 	= 738;					// Menu Table Width

$table_width_side 	= 130;					// Side Table Width

$votebar_width		= 60;					// votebar with

$votebar_height		= 10;					// votebar height



#  Security Configuration

#################################################################################################

$supportpwd		= "2support";				// enable php-info-page if support-pwd is given & known, PARANOID-Setting: "" -> disabled

$secret 		= "ThiS IS tHe SeCrEt For THe haSH";  	// for hash generation, type a litte small text as you want



#################################################################################################

#

# End

#

#################################################################################################















#################################################################################################

# DO NOT EDIT ANYTHING BEHIND THIS LINE #########################################################

#################################################################################################



if (getenv(HTTP_CLIENT_IP)){$ip=getenv(HTTP_CLIENT_IP);} else {$ip=getenv(REMOTE_ADDR);}

$client=addslashes(getenv(HTTP_USER_AGENT));

$timestamp=time();

$bazar_path=substr($PHP_SELF,0,-1*strlen(strrchr($PHP_SELF,"/")));

$cookiepath=$bazar_path."/";

error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));

if ($compress_output) {ob_start ("ob_gzhandler");}



#################################################################################################

#################################################################################################

#################################################################################################



?>