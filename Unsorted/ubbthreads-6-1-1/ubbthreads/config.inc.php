<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

   $VERSION = "6.1.1";

  // ------------------
  // Database Variables

  // What type of database are you running
  // current options
  // mysql, (postgres, sybase are available but untested)
    $config['dbtype'] =		"mysql";

  // Server hosting the database
    $config['dbserver']=	"localhost";

  // Username that has permissions to the database
    $config['dbuser']	=	"";

  // Password for the database
    $config['dbpass']	= 	"";

  // Name of the database
    $config['dbname']	=	"";

  // If you are using mysql do you want to use persistent connections?
  // Note that on some hosted servers you may not be allowed to do this
  // so contact your host before setting this to "on"
  // Options: 1 - Use persistent: 0 - Don't use persistent
    $config['persistent'] =	0;

  // Path to mysql.  Only necessary for using the database restore function 
  // in the admin section.
    $config['mysqlpath'] = "/usr/bin";

  // Path to mysqldump.  Only necessary for using the database backup function
  // in the admin section.
    $config['mysqldumppath'] = "/usr/bin";

  // Directory to store database backups in.  Only necessary for using the 
  // database backup/restore function in the admin section.  This directory 
  // will need write permissions and should be outside of your html tree.
    $config['dumpdir']	= "";


  // ----------------------
  // Path and Url variables

  // Url to the main UBB.threads php install
    $config['phpurl']	=	"";

  // Absolute Url containing the images
    $config['images']	=	"/ubbthreads/images";

  // Full URL to the images folder.  Necessary for users that want their email
  // in HTML format.
	 $config['imageurl'] =	"";

  // Path to your images directory, needed for calculating image sizes
    $config['imagepath'] =	"";

  // Url to your stylesheets directory
    $config['styledir'] =       "/ubbthreads/stylesheets"; 

  // Path to your UBB.threads php install
    $config['path']	=	"";

  // Path to your stylesheets directory
    $config['stylepath'] =	"";

  // Path to save session information
  // This is only used if $config['tracking'] = "sessions"
  // Needs to be a world writeable directory outside of your html tree
    $config['sessionpath'] =	"";

  // Do you want to disable the referer check?  Only disable this if you have 
  // many users that are unable to post due to firewall/proxy servers 
  // manipulating their referer variable.
   $config['disablerefer'] =	"0";
 
  // Domain that UBB.threads is running under.
    $config['referer'] =	"";

  // --------------------------
  // Site specific / Navigation

  // Do you want to do full new post tracking? Show the number of new posts on
  // a board/thread and show which posts are new in a thread? This option can
  // contribute to high load on forums with hundreds of users online at once due
  // to the number of queries that are necessary.
    $config['newcounter'] =    2;

  // Manually approve all new user registrations (An email will be sent to 
  // all admins when a new user registers).
    $config['userreg'] =	0;

  // What groups do you want new users to belong to?  THIS SHOULD ONLY
  // BE CHANGED IN THE ONLINE EDITOR, UNLESS YOU REALLY KNOW WHAT YOU ARE
  // DOING
    $config['newusergroup'] = "-3-";

  // Title of site
    $config['title']	=	"UBBThreads devel";

  // Your Home page URL
    $config['homeurl']	=	"http://www.infopop.com";

  // Title of Homepage link
    $config['urltitle']=	"Infopop.com";

  // Email address
    $config['emailaddy']=	"rbaker@wcsoft.net";

  // EMail Title
    $config['emailtitle']=	"Contact Us";

  // Show privacy statement link in footer
    $config['privacy'] = "1";

  // Show board rules on new user screen?
    $config['boardrules'] = "1";

  // -----------------
  // Special Functions

  // Do you want to use sessions or cookies to track your users
  // values = "cookies" or "sessions";
    $config['tracking'] =	"cookies";

  // If you are using cookies you can specify a path to make them available
  // to other areas of your website.  If you only want them to be available
  // to things within your UBB.threads installation directory leave this blank
  // If you want them available to your whole website, specify a path of "/";
     $config['cookiepath'] = "/";

  // Custom cookie prefix.  Can be left blank but useful if you have 2 installs   // and don't want cookies from one overwriting the other.  WARNING: Changing 
  // this will make all current cookies invalid, meaning everyone will be 
  // logged out.
     $config['cookieprefix'] = "";

 
  // This one variable is used to open or close your forums.  Set this to
  // 1 if you want to work on your forums.  If this is set to 1 then your
  // forums will only be accessible by the admin users.  Make sure you are
  // logged in as an admin before you close the forums.
    $config['isclosed'] =	0;

  // Show the user list in the navigation menu?
  // 0 = No : 1 = Yes
    $config['userlist'] =	1;

  // Use zlib compression if available (pages will be smaller but it may cause excess server overhead on busy sites)
  // 0 = No : 1 = Yes
    $config['compression'] = 0;

  // Print debugging code in footer (Page generation times, number of queries, compression)
  // 0 = No : 1 = Yes
    $config['debugging'] = 1;

  // Allow users to turn markup / html (if enabled) on or off when they post
  // 0 = No : 1 = Yes
    $config['markupoption'] = 0;

  // Do you want users to be able to mail posts to others
  // 0 = No ; 1 = Yes
    $config['mailpost'] =  1;
 
  // What type of main page do you want to use.
  // 0 = all categories and forums	- mainpage = ubbthreads.php
  // 1 = categories only		- mainpage = categories.php
    $config['catsonly'] =	0;

  // Allow all users to place polls in posts? If this is off, then only
  // admin and moderators may use the polls feature
    $config['allowpolls'] =	1;

  // Do you want everyone to vote, or just registered users?
  // 1 = everyone : 0 = registered
    $config['whovote'] =	1;

  // How long after a post has been made, can it be edited by the poster?
  // This variable is in hours, so you can also use .5 for values of less
  // than an hour for example
    $config['edittime']=	"6";

  // Check the user's age before creating an account to be compliant with the
  // COPPA law found at http://www.ftc.gov/opa/1999/9910/childfinal.htm
  // Values = 1 or 0 
    $config['checkage']=	1;

  // If you are checking the user's age and they are under 13 can they register   // with the parent consent form?
    $config['under13'] = 0;

  // Allow special characters in usernames
  // Values = 1 or 0 
    $config['specialc']=	0;

  // Allow moderators to edit/delete regular users
  // Values = 1 or 0 
    $config['modedit']	=	1;

  // Allow users that are not logged in to choose an unregistered name
  // for their post instead of the default Anonymous
    $config['anonnames'] =	1;

  // Default language.  Options can be found in the languages directory
    $config['language']= 	"english";

  // Censored replacement word.  If you have anything in your filters/badwords
  // file then any words matched when adding a new post will be replaced with
  // this word.  Set this to "" if you do not want to censor words.
    $config['censored']=	"[censored]";

  // Max length of subjects
    $config['subjectlength']=	"50";

  // Max length of signatures
    $config['Sig_length']=	"100";

  // When persistent cookies will expire (Number of seconds from current time)
    $config['cookieexp']=	"1036800";

  // If displayed times need to be adjusted, in hours (2,1,0,-1,-2)
    $config['adjusttime']=	"0";		 

  // Show the ip address of the posters
  // 0 - Show to nobody
  // 1 - Show to everyone
  // 2 - Show to moderators and admins
  // 3 - Show to admins only
    $config['showip']	=	1;

  // Allow subscriptions to boards.  If you allow this then you have to
  // have subscriptions.php in the cron directory setup to run nightly
    $config['subscriptions']=	1;

  // Allow private messages.  User's will still receive the welcome message
  // and private messages will still be used for admin purposes, but general
  // users will not be able to send private messages.
    $config['private']	=	1;

  // Allow the image markup tag.
    $config['allowimages']=	1;

  // If allowing pictures you can also let users upload these pictures to your 
  // server.  By specifying a path this will allow users to upload .gif, .jpg 
  // or .png files to that directory.  You must make the directory 
  // world_writeable as well.  If no path specified, then users will need to 
  // provide a url to their picture.
    $config['avatars'] = "/blah/blah";

  // If a path was specified above, we need to know the full url to that 
  // directory (Partial url will not work)
    $config['avatarurl'] = "http://blahblah";

  // If allowing users to upload their pictures, enter the maximum size
  // in bytes.
    $config['avatarsize'] = "";

  // Allow File Attachemnts.  If you provide a path to a directory below
  // then File Attachments will be allowed.  If you leave it blank, then they
  // will not.  Make sure this directory is writeable by everyone
    $config['files']	=	"/home/virtual/site41/fst/var/www/html/files";

  // We also need the url to the files directory
    $config['fileurl']	=	"http://threadsbeta.infopop.cc/files";

  // Do you want to only allow certain file types, or exclude certain file
  // types? Only uncomment one of the following.  Seperate your filetypes with
  // a ,
    $config['allowfiles'] = ".txt";

  // Maximum filesize allowed
    $config['filesize']	=	"100000";

  // Allow users to specify their password at the time of creating the userid
    $config['userpass']	=	1;

  // Allow multiple usernames form the same email address
  // On- allow multiple usernames from same email.  off - disallow
    $config['multiuser']	=	0;

  // Default to having a preview post screen?
    $config['preview']	=	1;

  // Dateslip.  If this is on, when replies are made to a thread, this thread
  // will be moved up to the top of the post list when sorting by descending
  // date.  If this is off, then threads will stay in their original spot.
    $config['dateslip']	=	1;

  // These are the extra fields for users to fill out in their profiles.
  // If you leave these blank they will not be users.  If you give them a
  // title then they will show up on the user's profile page.
    $config['extra1']	=	"ICQ";
    $config['extra2']	=	"";
    $config['extra3']	=	"";
    $config['extra4']	=	"";
    $config['extra5']	=	"";

  // Use the ICQ status indicator.  Depending on the size of your user database
  // you might be required to turn this off.  Please read the ICQ license
  // agreemtn at http://www.icq.com/legal/indicator.html
    $config['ICQ_Status']	= 1;

	
?>
