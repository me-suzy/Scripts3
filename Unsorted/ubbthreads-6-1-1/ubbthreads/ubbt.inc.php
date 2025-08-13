<? /* Main functions and classes */
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

// ------------------------
// Turn off high level info
   error_reporting(7);

// -------------------------------------
// Strip out all of those pesky slashes
   if ( (is_array($GLOBALS)==1) && (get_magic_quotes_gpc()==1) ){ 
      while( list($key,$value) = each($GLOBALS)) {
         if ($key == "thispath") { continue; }
         if (is_string($value) == 1) {
            $GLOBALS[$key] = stripslashes($value);
         }
      }
   }

// -----------------------
// Get magic quote setting
   $magic = get_magic_quotes_gpc();

// --------------------------------
// Register the necessary variables
   if (is_array($HTTP_GET_VARS)) {
     while(list($key,$value) = each($HTTP_GET_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }
   if (is_array($HTTP_POST_VARS)) {
     while(list($key,$value) = each($HTTP_POST_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }
   if (is_array($HTTP_COOKIE_VARS)) {
     while(list($key,$value) = each($HTTP_COOKIE_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }
   if (is_array($HTTP_SESSION_VARS)) {
     while(list($key,$value) = each($HTTP_SESSION_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }

// ---------------------
// Include the libraries
// We need to protect both $thispath and $configdir at this time to make
// sure an external url isn't called

   if (!$configdir) {
		$configdir = $thispath;
	}
   if ( 
      $HTTP_GET_VARS['thispath']  
   || $HTTP_POST_VARS['thispath']
   || $HTTP_COOKIE_VARS['thispath']
   || $HTTP_POST_FILES['thispath']
   || $HTTP_GET_VARS['configdir']
   || $HTTP_POST_VARS['configdir']
   || $HTTP_COOKIE_VARS['configdir']
   || $HTTP_POST_FILES['configdir'] )
   {   
      exit;
   }
   include("$configdir/config.inc.php");
   include("$thispath/mysql.inc.php");
   include("$thispath/theme.inc.php");


// In order to be XHTML compliant we need to see if this is a Netscape 4
// series browser.  Because they do not support the vertical-align property
   $browser = find_environmental ("HTTP_USER_AGENT");
   $imagestyle = "style=\"vertical-align: text-top\"";
   if( (stristr($browser, 'MOZILLA/4.')) && (!stristr($browser, 'compatible')) ) {
      $imagestyle = "";
   }

// -----------------------------------------------------------------
// Just in case the table name prefix isn't set we always default to
// w3t_
	if (!$config['tbprefix']) {
		$config['tbprefix'] = "w3t_";
	}

// ------------------------------------------------
// If available and turned on, use zlib compression
   if ($config['compression']) {
      $phpa = phpversion();
      $phpv = $phpa[0] . "." . $phpa[2] . $phpa[4];
      if (($phpv > 4.0004) && extension_loaded("zlib") && !ini_get("zlib.output_compression") && !ini_get("output_handler")) {
         ob_start("ob_gzhandler");
         $zlib = "Zlib compression enabled.";
      }
      else {
         $zlib = "Zlib compression unavailable.";
      }
   }
   else {
      $zlib = "Zlib compression disabled.";
   }
   if (ini_get("zlib.output_compression")) {
      $zlib = "Zlib compression enabled in php.ini";
   }


// ----------------------
// Start the session here
   if ($config['tracking'] == "sessions") {
      session_save_path($config['sessionpath']);
      session_start();
   }
   else {
      $HTTP_SESSION_VARS="";
   }




   $tempstyle = "default";

// ---------------------------
// Turn off the magic quoting
   set_magic_quotes_runtime(0);

  if (!${$config['cookieprefix']."w3t_language"}) {
    ${$config['cookieprefix']."w3t_language"} = $config['language'];
  }
  include("$thispath/languages/${$config['cookieprefix']."w3t_language"}/generic.php");
  $dbh = new sql;
  $dbh -> connect();


// ########################################################################
// MAILER CLASS
// Define class for sending email 
// ######################################################################## 
  class mailer {
  
  // ###################################################################### 
  // headers 
  // ###################################################################### 
    function headers($htmlmail="",$from="") {

       global $config;
       $newline = "\n";
       if (stristr(PHP_OS,"win")) {
          $newline = "\r\n";
       }
		 if (!$from) {
       	$headers .= "From: {$config['emailaddy']}$newline";
		 }
		 else {
		   $headers .= "FRom: $from$newline";
		 }
       $headers .= "Sender: {$config['emailaddy']}$newline";
       $headers .= "Reply-to: {$config['emailaddy']}$newline";
       $headers .= "X-Mailer: UBB.threads$newline";
       $headers .= "Return-Path: <{$config['emailaddy']}>$newline";
		 if ($htmlmail == "HTML") {
			 $headers .= "Content-Type: text/html; charset=iso-8859-1$newline";
		 }
       return $headers;

    }

  }

// ########################################################################
// HTML CLASS
// Define class for sending html
// ######################################################################## 
  class html {
  
  // ###################################################################### 
  // SEND_HEADER FUNCTION
  // Grab the title and send the header
  // ###################################################################### 
    function send_header($inputTitle="",$Cat="",$refresh="",$user="",$aux="",$readperm="",$bypass="") {

      global $theme, $config, $ubbt_lang, $dbh, $thispath, $w3t_id, $w3t_mypass, ${$config['cookieprefix']."w3t_language"}, $tempstyle, ${$config['cookieprefix']."w3t_visit"}, $PHPSESSID, $SID, $Board,$fheader, $fstyle,$debug;  

      if (isset($user['loggedout'])) {
         $loggedout = $user['loggedout'];
      }

    // -------------------------------------------------------------------
    // If we don't have a status then they we need to try and authenticate
      if (!$user['U_Status']) {
        $userob = new user;
        $user = $userob -> authenticate();
         
      }

    // -----------------------------
    // Grab any personal preferences
      $FrontPage = $user['U_FrontPage'];
      $Privates  = $user['U_Privates'];
      $Status    = $user['U_Status'];
      $ubbt_language  = ${$config['cookieprefix']."w3t_language"};
      if (isset($user['newlanguage'])) {
         require ("{$config['path']}/languages/{$user['newlanguage']}/generic.php");
      }
      if (!$ubbt_language) { $ubbt_language = $config['language']; }

      $insert = @file("{$config['path']}/includes/header-insert.php");
      if (!is_array($insert)) {
         $insert = @file("{$config['phpurl']}/includes/header-insert.php");
      }
      $headerinsert = "";
      if ($insert) {
         $headerinsert = implode('',$insert);
      }
      if (!empty($refresh)) {
         $refresh = str_replace("\" />","&amp;PHPSESSID=$PHPSESSID\" />",$refresh);
         $refresh = $refresh;
      }
      else {
         $refresh = "";
      }

    // -------------------------------------------------------
    // If they don't have a stylesheet pref we use the default
      if (!$fstyle) {
        $fstyle = $user['U_StyleSheet'];
         if ( (!$fstyle) || ($fstyle == "usedefault") ) {
           $fstyle = $theme['stylesheet'];
         }
      }

    // ----------------------------------------------------
    // For certain languages we need to specify an encoding
      if ($ubbt_language == "polish") {
        $contenttype = "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-2\">";
      }
      elseif ($ubbt_language == "russian") {
        $contenttype = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">";
      }
      elseif ($ubbt_language == "chinese") {
        $contenttype = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2313\">";
      }
      elseif ($ubbt_language == "big5") {
        $contenttype = "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=big5\">";
      }
      else {
         $contenttype = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />";
      }

   // --------------------
   // set the style sheet
      $stylesheet = "{$config['styledir']}/$fstyle.css";

    // ---------------------------------------------------
    // If we need to include some javascript we do it here
      $javascript = "";
      if (isset($user['dynamic'])) {
         $javascript = "{$user['dynamic']}";
      }

      if (isset($user['java'])) {
         $java = @file("{$config['path']}/includes/{$user['java']}.js");
         if (!is_array($java)) {
            $java = @file("{$config['phpurl']}/includes/{$user['java']}.js");
         }
         $javascript = @implode('',$java);
      }

      if ($inputTitle == $config['title']) {
         if (!${$config['cookieprefix']."w3t_language"}) {
            ${$config['cookieprefix']."w3t_language"} = $config['language'];
         }
         require ("{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/online.php");
         $What = find_environmental ("PHP_SELF");
         $script['0'] = "";
         preg_match ("/(.*)\/(.*).php/",$What,$script);
         $What     = $script['2'];
         if (stristr($What,"faq_")) {
            $What = "faq_english";
         }
         $inputTitle = $ubbt_lang[$What];
         if (!$inputTitle) { $inputTitle = $ubbt_lang['all_admin']; }
      }

       if (!$debug) {
	       include("$thispath/templates/$tempstyle/ubbt_header.tmpl");
       }

    // ------------------------------------------------
    // Let's see if we need to print out the nav menu
       $What = find_environmental("PHP_SELF");
       $script['0'] = "";
       preg_match ("/(.*)\/(.*).php/",$What,$script);
       $What     = $script['2'];
       if ( ($script['2'] == 'top') || (!preg_match("/\/admin$/",$script['1']) )) {

       // ----------------------------
       // require the header.php file
         if ( ($script['2'] != "top") ) {
            if ($fheader) {
               $header = "header_$Board.php";
            }else {
               $header = "header.php";
            }
				if (!$debug) {
            	@include "$thispath/includes/$header";
				}
         }
   
      
       // ----------------------------------------------------
       // If they have private messages we give the PM flasher
       // as long as they didn't just log out
         $privateslink = "";
         $adminlink = "";
         if (($Privates) && (!$bypass)) {
           $privateslink =  "<a href=\"{$config['phpurl']}/viewmessages.php?Cat=$Cat&amp;box=received\" target=\"_top\"><img src= \"{$config['images']}/newpm.gif\" border=\"0\" alt=\"{$ubbt_lang['WEL_PRIV2']} $Privates {$ubbt_lang['WEL_PRIV3']}\" /></a>";
         }
   
       // ----------------------------  
       // Set up the faq file to go to
         $ubbt_language = "faq_" .$ubbt_language;
   
       // ----------------------
       // What is our main page?
         $mainpage = "ubbthreads";
         if ($config['catsonly']) {
           $mainpage = "categories";
         }
         if (!$FrontPage) {
           $FrontPage = $mainpage;
         }
   
       // ---------------------------------------------------
       // If they aren't logged in they get the standard menu
         $phpurl = $config['phpurl'];
         if ( (!$user['U_Username']) || ($bypass) ) {

         // -------------------------------------------------
         // Are we showing the user list to reged users only?
            if ($config['userlist'] == '1'){
               $template['members_link'] = " | <a href=\"$phpurl/showmembers.php?Cat=$Cat&amp;page=1\" target=\"_top\">{$ubbt_lang['USER_LIST']}</a>";
            }
   
         // -------------------------------------------
         // Now require the registerednav.php template
				if (!$debug) {
            	include("$thispath/templates/$tempstyle/ubbt_unregisterednav.tmpl");
				}

         // Since we still want to see non logged in users on the online screen
         // we need to track this by IP
           $IP = find_environmental ("REMOTE_ADDR");
           $What = find_environmental ("PHP_SELF");
           if (strstr($What, 'admin')) {
              $What = "all_admin";
           }
           else {
              $script['0'] = "";
              preg_match ("/(.*)\/(.*).php/",$What,$script);
              $What     = $script['2'];
           }
           $Last     = $this -> get_date();
           $Username = "-ANON-$IP";
           $What     = addslashes($What);
           $Username = addslashes($Username);
           $aux      = addslashes($aux);
           $query = "
             REPLACE INTO {$config['tbprefix']}Online
             (O_Username,O_Last,O_What,O_Extra,O_Read,O_Type)
             VALUES ('$Username','$Last','$What','$aux','$readperm','a')	
           ";
           $dbh -> do_query($query);
         }  
   
       // Otherwise they are logged in so they get the special menu
         else {
   
         // ------------------------------
         // Update the who's online screen
           $Username = addslashes($user['U_Username']);
           $What = find_environmental ("PHP_SELF");
           if(strstr($What, 'admin')) {
              $What = "all_admin";
           }
           else {
              $script['0'] = "";
              preg_match ("/(.*)\/(.*).php/",$What,$script);
              $What     = $script['2'];
           }
           $Last     = $this -> get_date();
           $What     = addslashes($What);
           $aux      = addslashes($aux);
   
           $query = "
             REPLACE INTO {$config['tbprefix']}Online
             (O_Username,O_Last,O_What,O_Extra,O_Read,O_Type)
             VALUES ('$Username','$Last','$What','$aux','$readperm','r')	
           ";
           $dbh -> do_query($query);
         // ------------------------------------------------------------
         // We need to check for a temporary cookie and if we don't find
         // one then this is their first visit for that browser session
         // so we get rid of the TempRead values in their profile
           if ( !${$config['cookieprefix']."w3t_visit"} ) {
             $date = time();
             $query = "
               UPDATE {$config['tbprefix']}Users
               SET    U_Laston   = $date,
                      U_TempRead = ''
               WHERE  U_Username = '$Username'
               AND    U_Laston   < ($date - 14300)
             ";
             $dbh -> do_query($query);
           }
   
           $phpurl = $config['phpurl'];
   
         // ------------------------------------------------------------------
         // If they are an admin or moderator they get a link to the admin sec
           if ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) {
              $target = "target=\"_top\"";
              $adminlink = "<a href = \"$phpurl/admin/login.php?Cat=$Cat\" $target>{$ubbt_lang['ADMIN_MENU']}</a> | ";
           }
   
   
         // -------------------------------------------------
         // Are we showing the user list to reged users only?
            if ( ($config['userlist'] == '2') || ($config['userlist'] == '1') ){
               $template['members_link'] = " | <a href=\"$phpurl/showmembers.php?Cat=$Cat&amp;page=1\" $target>{$ubbt_lang['USER_LIST']}</a>";
            }

         // -------------------------------------------
         // Now require the registerednav.php template
				if (!$debug) {
            	include("$thispath/templates/$tempstyle/ubbt_registerednav.tmpl");
				}
   
         }
   
       // -----------------------------------------------------
       // If the forum is closed the we only let admins proceed
       // But they can try to login
         if ($What != "login") {
           if ($config['isclosed'] && $user['U_Status'] != "Administrator") {
              $insert = @file("{$config['path']}/includes/closedforums.php");
              if (!is_array($insert)) {
                 $insert = @file("{$config['phpurl']}/includes/closedforums.php");
              }
              if ($insert) {
                 $template['closedforums'] = implode('',$insert);
              }
           // -------------------------------------------
           // Now require the registerednav.php template
					if (!$debug) {
              		include("$thispath/templates/$tempstyle/ubbt_closedboard.tmpl");
					}
              $this -> send_footer();
              exit();
           }
           if ($config['isclosed']) {
             echo "<p align=\"center\">The forums are closed</center></p>";
           }
         }
       }
     }


  // ####################################################################### 
  // Not right - something went wrong - UHOH!
  // ####################################################################### 
    function not_right($error="",$Cat="",$header="") {

       global $ubbt_lang,$theme,$tempstyle,$thispath,$debug;

       if (!$header) {
          $this -> send_header($ubbt_lang['NO_PROCEED'],$Cat);
       }
       $template['error'] = $error;
		 if (!$debug) {
       	include "$thispath/templates/$tempstyle/ubbt_notright.tmpl";
		 }
       $this -> send_footer();
       exit;
    }


  // ####################################################################### 
  // Check_refer 
  // ####################################################################### 
    function check_refer($Cat="") {

      global $config,$ubbt_lang;
      $valid = 0;       
      $referers = split("\|",$config['referer']);
      for ($i=0; $i <= (sizeof($referers)-1); $i++) {
         if (stristr(find_environmental('HTTP_REFERER'),$referers[$i])) {
           $valid++;
         }
      }
      if (!$valid) {
         $this -> not_right($ubbt_lang['NOT_VALID'],$Cat);
      }
    }


  // #######################################################################
  // form_encode: There are a few characters that will break form boxes
  // so we need to encode them
  // #######################################################################
    function form_encode($input = "") {
      
       $input = str_replace("<","%3C",$input);
       $input = str_replace(">","%3E",$input);
       $input = str_replace("\"","%22",$input);
       return $input;

    }


  // #######################################################################
  // form_decode: There are a few characters that will break form boxes
  // so we need to encode them
  // #######################################################################
    function form_decode($input = "") {
      
       $input = str_replace("%3C","<",$input);
       $input = str_replace("%3E",">",$input);
       $input = str_replace("%22","\"",$input);
       return $input;

    }

  // ####################################################################### 
  // Markup a string 
  // ####################################################################### 
    function do_markup($Body="") {
    
       global $ubbt_lang,$config,$dbh;

    // First grab all refs to the code tag
       preg_match_all("/(\[code\])\n?\r?(.*?)(\[\/code\])/is",$Body,$matches);
       for ($i=0; $i < count($matches['0']); $i++) {
    
          $Body = str_replace($matches[0][$i],"[eDoC]{$i}[/eDoC]",$Body);
       // ------------------------------------------------
       // We do allow color markup tags within Code blocks
          $matches[0][$i] = preg_replace("/\[{$ubbt_lang['COLOR']}:(.+?)\]/e","nojs(\"$1\")",$matches[0][$i]);
          $matches[0][$i] = preg_replace("/\[\/{$ubbt_lang['COLOR']}\]/","</font color>",$matches[0][$i]);
       }

    // ------------------------
    // Encode bolds and italics
       $Body =str_replace("[b]","<b>",$Body);
       $Body =str_replace("[i]","<i>",$Body);
       $Body =str_replace("[/i]","</i>",$Body);
       $Body =str_replace("[/b]","</b>",$Body);

    // ----------------
    // Do list elements
       $Body =preg_replace("/(\[list\])\n?\r?(.+?)(\[\/list\])/is","<ul type=\"square\">\\2</ul>",$Body);
       $Body =preg_replace("/(\[list=)(A|1)(\])\n?\r?(.+?)(\[\/list\])/is","<ol type=\"\\2\">\\4</ol>",$Body);
       $Body =preg_replace("/\n?\r?(\[\*\])/is","<li>",$Body);

    // -----------------------
    // Convert the color codes
       $Body = preg_replace("/\[{$ubbt_lang['COLOR']}:(.+?)\]/e","nojs(\"$1\")",$Body);
       $Body = preg_replace("/\[\/{$ubbt_lang['COLOR']}\]/","</font color>",$Body);

    // Convert the smileys
	 // First grab all smileys out of the database
		 $query = "
			SELECT G_Code,G_Smiley,G_Image
			FROM   {$config['tbprefix']}Graemlins
			ORDER BY G_Smiley
		 ";
		 $sth = $dbh -> do_query($query);
		 while (list($code,$smiley,$image) = $dbh -> fetch_array($sth)) {
		 	if ($smiley) {
				@eval("\$string = $code;");
				$smiley = str_replace(")","\)",$smiley);
				$smiley = str_replace("(","\(",$smiley);
       		$Body = preg_replace("/(( |\n|^|\r)$smiley|(\[|:)$string(\]|:))/","\\2<img src=\"{$config['images']}/graemlins/$image\" alt=\"\" />",$Body);
			}
			else {
				@eval("\$string = $code;");
       		$Body = preg_replace("/(:|\[)$string(:|\])/i","<img src=\"{$config['images']}/graemlins/$image\" alt=\"\" />",$Body); 
			}
		}

    // -------------
    // Quote markup
       $Body = str_replace("[{$ubbt_lang['TEXT_QUOTE']}]","</font><blockquote><font class=\"small\">{$ubbt_lang['IN_REPLY']}:</font><hr /><br />",$Body);
       $Body = str_replace("[/{$ubbt_lang['TEXT_QUOTE']}]","<br /><br /><hr /></blockquote><font class=\"post\">",$Body);



    // ---------------------------
    // Convert www -> html
        $Body = " " . $Body;
        $Body = preg_replace("#([\n\r ])([a-z]+?)://([^, \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $Body);
        $Body = preg_replace("#([\n\r ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $Body);
        $Body = preg_replace("#([\n\r ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $Body);
        $Body = substr($Body, 1);

    // ---------------------------
    // Convert url markup -> html
       $Body = preg_replace("/\[url\]ftp:\/\/([^\[]*?)\[\/url\]/i","<a href=\"ftp://\\1\" target=\"_blank\">ftp://\\1</a>",$Body);
       $Body = preg_replace("/\[url\]http:\/\/([^\[]*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">http://\\1</a>",$Body);
       $Body = preg_replace("/\[url\]https:\/\/([^\[]*?)\[\/url\]/i","<a href=\"https://\\1\" target=\"_blank\">https://\\1</a>",$Body);
       $Body = preg_replace("/\[url\]([^\[]*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\1</a>",$Body);
       $Body = preg_replace("/\[url=http:\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$Body);
       $Body = preg_replace("/\[url=https:\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"https://\\1\" target=\"_blank\">\\2</a>",$Body);
       $Body = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$Body);
     

    // ----------------------------
    // Convert email markup -> html
       $Body = preg_replace("/\[{$ubbt_lang['TEXT_EMAIL']}\]([^\[]*)\[\/{$ubbt_lang['TEXT_EMAIL']}\]/i","<a href=\"mailto:\\1\">\\1</a>",$Body);

    // ---------------------
    // Convert image markup 
       if ($config['allowimages']) {
          if (!preg_match("/(\[IMG\]|\[{$ubbt_lang['TEXT_IMAGE']}\])(.*?)\?(.*?)(\[\/IMG]|\[\/{$ubbt_lang['TEXT_IMAGE']})/",$Body)) {
             $Body = preg_replace("/(\[IMG\]|\[{$ubbt_lang['TEXT_IMAGE']}\])http([^\[]*)(\.gif|\.jpg|\.png)(\[\/IMG\]|\[\/{$ubbt_lang['TEXT_IMAGE']}\])/i","<img src=\"http\\2\\3\">",$Body);
          }
       }

    // Now we need to replace everything in the code markup tags
       preg_match_all("/(\[eDoC\])\n?\r?(.*?)(\[\/eDoC\])/is",$Body,$newmatches);
       reset($matches);
       for ($i=0; $i < count($newmatches['0']); $i++) {
          $Body = str_replace($newmatches[0][$i],$matches[0][$i],$Body);
       }
       $Body = str_replace("[code]","<pre><font class=\"small\">code:</font><hr>",$Body);
       $Body = str_replace("[/code]","</pre><hr>",$Body);
      

       return $Body;

    }


  // ####################################################################### 
  // Remove Markup from a string 
  // ####################################################################### 
    function undo_markup($Body="") {

       global $ubbt_lang,$config,$dbh;
    // ------------------------
    // Encode bolds and italics
       $Body =str_replace("<b>","[b]",$Body);
       $Body =str_replace("<i>","[i]",$Body);
       $Body =str_replace("</i>","[/i]",$Body);
       $Body =str_replace("</b>","[/b]",$Body);

    // -----------------------
    // Conver the list markup
       $Body = preg_replace("/<ul type=\"(.*?)\">/","[LIST]",$Body);
       $Body = str_replace("<li>","[*]",$Body);
       $Body = str_replace("</ul>","[/LIST]",$Body);

    // -----------------------
    // Convert the color codes
       $Body = preg_replace("/<font color=(.+?)>/i","[{$ubbt_lang['COLOR']}:\\1]",$Body);
       $Body = preg_replace("/<\/font color>/i","[/{$ubbt_lang['COLOR']}]",$Body);

    // Convert the smileys
	 // First grab all smileys out of the database
		 $query = "
			SELECT G_Code,G_Smiley,G_Image
			FROM   {$config['tbprefix']}Graemlins
			ORDER BY G_Smiley
		 ";
		 $sth = $dbh -> do_query($query);
		 while (list($code,$smiley,$image) = $dbh -> fetch_array($sth)) {
			eval("\$string = $code;");
			if ($smiley) {
				$string = $smiley;
			} else {
				$string = ":$string:";
			}
       	$Body = str_replace("<img src=\"{$config['images']}/graemlins/$image\" alt=\"\" />","$string",$Body); 
		 }

    // -------------
    // Quote markup
       $Body = preg_replace("/<\/font><blockquote><font class=\"small\">(.*?):<\/font><hr \/><br \/>/","[{$ubbt_lang['TEXT_QUOTE']}]",$Body);
       $Body = str_replace("<br /><br /><hr /></blockquote><font class=\"post\">","[/{$ubbt_lang['TEXT_QUOTE']}]",$Body);


    // HTML markup
    // ---------------------------
       $Body = str_replace("<pre><font class=\"small\">code:</font><hr>","[code]",$Body);
       $Body = str_replace("</pre><hr>","[/code]",$Body);

    // ---------------------------
    // Convert url markup -> html
       $Body = preg_replace("/<a href=(\"|&quot;)(http|https|ftp):\/\/(.*?)(\"|&quot;) target=(\"|&quot;)_blank(\"|&quot;)>(.*?)<\/a>/i","[url=\\2://\\3]\\7[/url]",$Body);
       $Body = preg_replace("/<a target=(\"|&quot;)_blank(\"|&quot;) href=(.*?)>(.*?)<\/a>/i","[url=\\3]\\4[/url]",$Body);
     
    // ----------------------------
    // Convert email markup -> html
       $Body = preg_replace("/<a href=(\"|&quot;)mailto:([^\[]*)(\"|&quot;)>(.*?)<\/a>/i","[{$ubbt_lang['TEXT_EMAIL']}]\\2[/{$ubbt_lang['TEXT_EMAIL']}]",$Body);

    // ---------------------
    // Convert image markup 
       if ($config['allowimages']) {
          $Body = preg_replace("/<img src=\"([^\>]*)\">/i","[{$ubbt_lang['TEXT_IMAGE']}]\\1[/{$ubbt_lang['TEXT_IMAGE']}]",$Body);
       }
       return $Body;

    }
   
  // ####################################################################### 
  // Send a table header
  // ####################################################################### 
    function table_header($header="",$full="") {

      global $theme;
      $this -> open_table($full);
      echo "<tr><td class=\"tdheader\">$header";
      $this -> close_table();

    }

  // ####################################################################### 
  // Send a table header for the admin section
  // ####################################################################### 
    function admin_table_header($header="",$full="") {

      global $theme;
      $this -> open_admin_table($full);
      echo "<tr><td class=\"tdheader\">$header";
      $this -> close_table();

    }

  // ####################################################################### 
  // Open a table
  // ####################################################################### 
    function open_table($full="") {
      global $theme;

      $width = $theme['tablewidth'];
      if ($full) {
         $width = "100%";
      }
      echo " 
<table width=\"$width\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"tablesurround\">
<tr>
<td>
<table cellpadding=\"{$theme['cellpadding']}\" cellspacing=\"{$theme['cellspacing']}\" width=\"100%\" class=\"tableborders\">
      ";
    }

  // ####################################################################### 
  // Open a table for the admin area
  // ####################################################################### 
    function open_admin_table($full="") {

      global $theme;
      $width = "95%";
      echo " 
<table width=\"$width\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"tablesurround\">
<tr>
<td>
<table cellpadding=\"{$theme['cellpadding']}\" cellspacing=\"{$theme['cellspacing']}\" width=\"100%\" class=\"tableborders\">
      ";
    }


  // ####################################################################### 
  // Close the table
  // ####################################################################### 
    function close_table() {
      echo "
</td></tr></table>
</td></tr></table>
      ";
    }

  // ####################################################################### 
  // Send the footer
  // ####################################################################### 
    function send_footer() {

      global $config, $theme, $thispath, $VERSION, $ubbt_lang, $tempstyle, $timea,$querycount,$zlib,$fheader,$Board,$mysqltime,$debug,$debugprint;

      if ($config['privacy']) {
         $template['privacy_statement'] = " | <a href=\"{$config['phpurl']}/viewprivacy.php\">{$ubbt_lang['PRIVACY']}</a>";
      }


      if ($config['debugging']) {
         $timeb = getmicrotime();
         $time = $timeb - $timea;
         $time = round($time,3);
         $debug = "<p align=\"center\" class=\"small\">Generated in $time seconds in which $mysqltime seconds were spent on a total of $querycount queries. $zlib</p>";
      }

      if ($fheader) {
          $file = "footer_$Board.php";
      }
      else {
          $file = "footer.php";
      }
      $insert = @file("{$config['path']}/includes/$file");
      if (!is_array($insert)) {
         $insert = @file("{$config['phpurl']}/includes/$file");
      }
      if ($insert) {
         $footerfile = implode('',$insert);
      }

    	@include("$thispath/templates/$tempstyle/ubbt_footer.tmpl");
    }      


  // ####################################################################### 
  // Send the footer for admin section
  // ####################################################################### 
    function send_admin_footer() {

      global $config, $theme, $thispath, $VERSION;

      $html = new html;
      echo "<br /><br />";
      $html -> open_admin_table();
      echo "
        <tr class=\"darktable\">
        <td>
          <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
          <tr>
            <td align=\"left\">

              <a href=\"{$config['homeurl']}\">{$config['urltitle']}</a> |
              <a href=\"mailto:{$config['emailaddy']}\">{$config['emailtitle']}</a>
            </td><td align=\"right\">
              <a href=\"http://www.infopop.com\" target=\"_blank\"><font class=\"small\">Powered By UBB.threads&trade; $VERSION</font></a>
          </td></tr></table>
      ";
      $html -> close_table();
      echo "
        </body>
        </html>
      ";
        
    }      


  // ####################################################################### 
  // get_date
  // ####################################################################### 
    function get_date() {
   
      global $config;
      $currtime = time();
      $currtime = $currtime+($config['adjusttime']*3600);
      return $currtime;
    }

  // ####################################################################### 
  // Convert the time
  // ####################################################################### 
    function convert_time($time="",$offset="") {

      global $theme;
      if ($offset) {
         $time = $time+($offset *3600);
      }
      if ($theme['timeformat'] == "short1") {
        $time = @date("m/d/y h:i A", $time);
      }
      elseif ($theme['timeformat'] == "short2") {
        $time = @date("d/m/y h:i A", $time);
      }
      elseif ($theme['timeformat'] == "short3") {
        $time = @date("y/m/d h:i A", $time);
      }
      elseif ($theme['timeformat'] == "short4") {
        $time = @date("d/m/Y H:i", $time);
      }
		elseif ($theme['timeformat'] == "short5") {
			$time = @date("H:i d/m/Y", $time);
		}
      elseif ($theme['timeformat'] == "long") {
        $time = @date("D M d Y h:i A", $time);
      }


      return $time;

    }

  
  // ####################################################################### 
  // Switch_colors - This will switch colors between the 2 table colors
  // ####################################################################### 
    function switch_colors($color="") {

      if ($color == "lighttable") {
        $color = "darktable";
      }
      else {
        $color = "lighttable";
      }
      return ($color);
    }



  // ####################################################################### 
  // jump box- prints out a jump box to jump to other forums
  // ####################################################################### 
     function jump_box($Cat="",$groupquery="",$Board="") {

       global $dbh, $config, $ubbt_lang, $tempstyle, $thispath,$debug;
      
       $template['Cat'] = $Cat;
       $thiscat = "";

       if ($Cat) {
			if (stristr("$Cat",",")) {
            $pattern = ",";
            $replace = " OR Bo_Cat = ";
            $thiscat = str_replace($pattern,$replace,$Cat);
            $thiscat = "AND (Bo_Cat = " . $thiscat .")";
         }
         else {
            $thiscat = "AND (Bo_Cat = '$Cat')";
         }
       }
       $groupquery = str_replace("AND","WHERE",$groupquery);

       $query = "
         SELECT Bo_Number,Bo_Title,Bo_Keyword,Bo_Cat,Bo_CatName,Bo_Sorter
         FROM   {$config['tbprefix']}Boards
         $groupquery
         $thiscat
         ORDER BY Bo_Cat,Bo_Sorter
       ";
       $sth = $dbh -> do_query($query);

       $initialcat = "";
       $template['choices'] = "";

       while ($rows = $dbh -> fetch_array($sth) ) {
         if ($initialcat != $rows['4']) {
           $template['choices'] .= "<option value =\"-CATJUMP-{$rows['3']}\">*{$rows['4']}* -----</option>";
           $initialcat = $rows['4'];
           $firstpass = 1;
         }
         $selected = "";
         if ($rows['2'] == $Board) {
           $selected = "selected=\"selected\"";
         }
         $template['choices'] .= "<option value=\"{$rows['2']}\" $selected>&nbsp;&nbsp;&nbsp;{$rows['1']}</option>";
       }
       $dbh -> finish_sth($sth);

     // ----------------------------
     // require the jumpbox template
       if (!$debug) {
          include("$thispath/templates/$tempstyle/ubbt_jumper.tmpl");
       }
       return $template;

     }

  // ####################################################################### 
  // Do login - Logs the user on 
  // ####################################################################### 
     function do_login($Cat = "",$Username="",$Password="",$rememberme="") {

       global $theme,$config,$dbh, $ubbt_lang, ${$config['cookieprefix']."w3t_language"}, ${$config['cookieprefix']."w3t_myid"}, ${$config['cookieprefix']."w3t_mysess"}, $thispath,${$config['cookieprefix']."w3t_key"},$tempstyle,$debug;

    // -----------------------------------------
    // Connect to db and authenticate this user 
       $Username_q = addslashes($Username);
       $query = "
        SELECT U_Username, U_Laston, U_Password,U_Number,U_Language,U_TempPass,U_Approved
        FROM   {$config['tbprefix']}Users
        WHERE  U_LoginName = '$Username_q' 
       ";
       $sth = $dbh -> do_query($query);
       $user = $dbh -> fetch_array($sth);
       list($CheckUser,$laston,$pass,$Uid,$ubbt_language,$temppass,$approved) = $user;
       $dbh -> finish_sth($sth);

       if (!$CheckUser) {
         $this -> not_right($ubbt_lang['NO_AUTH'],$Cat);
       }
       if ($approved == "no") {
         $this -> not_right($ubbt_lang['UNAPPROVED'],$Cat);
       }

    // Set the default language
       if (!$ubbt_language) {
         $ubbt_language = ${$config['cookieprefix']."w3t_language"};
       }
       ${$config['cookieprefix']."w3t_language"} = $ubbt_language;    

    // -------------------------------------------------------------
    // We allow them to login if they are using the correct password
    // of if they are using the temporary password
		 $bad = "yes";
       if ((crypt($Password,$pass) != $pass) && (md5($Password) != $pass)) {
        $bad = "yes";
       }
		 else {
		 	$bad = "no";
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET U_TempPass=''
            WHERE  U_Loginname = '$Username_q'
         ";
         $dbh -> do_query($query);
		 }
       if ($temppass) {
        if (md5($Password) == $temppass) {
          $bad = "no";
          $temppass = addslashes($temppass);
          $query = "
  	    		UPDATE {$config['tbprefix']}Users
            SET U_Password='$temppass',
                U_TempPass=''
            WHERE  U_Loginname = '$Username_q' 
          ";
          $dbh -> do_query($query);
        }
       }
       if ($bad == "yes") {
        $this -> send_header($ubbt_lang['BAD_PASS'],$Cat,"<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"5;url={$config['phpurl']}/logout.php?Cat=$Cat\">",0,0,0);

    // ------------------------------------
    // Now include the header.php template
        if (!$debug) {
           include("$thispath/templates/$tempstyle/ubbt_badlogin.tmpl");
        }

        $this -> send_footer();
        exit();
      }

    // --------------------------------------
    // Set a cookie or register a session var
       srand((double)microtime()*1000000);
       $newsessionid = md5(rand(0,32767));
       if ($rememberme) {
          $autolog = md5("$CheckUser$pass");
       }
       if ($config['tracking'] == "sessions") {
          session_register("{$config['cookieprefix']}w3t_myid");
          session_register("{$config['cookieprefix']}w3t_mysess");
          session_register("{$config['cookieprefix']}w3t_language");
          ${$config['cookieprefix']."w3t_myid"} = $Uid;
          ${$config['cookieprefix']."w3t_mysess"} = $newsessionid;
          ${$config['cookieprefix']."w3t_language"} = $ubbt_language;
       }
       else {
          if ($config['cookieexp'] > 31536000) {
              $config['cookieexp'] = 31536000;
           }
          setcookie("{$config['cookieprefix']}w3t_myid","$Uid",time()+$config['cookieexp'],"{$config['cookiepath']}");
          if ( ($rememberme) || (${$config['cookieprefix']."w3t_key"}) ) {
             setcookie("{$config['cookieprefix']}w3t_key","$autolog",time()+$config['cookieexp'],"{$config['cookiepath']}");
          }
          setcookie("{$config['cookieprefix']}w3t_mysess","$newsessionid","0","{$config['cookiepath']}");
          setcookie("{$config['cookieprefix']}w3t_language","$ubbt_language",time()+$config['cookieexp'],"{$config['cookiepath']}");
          setcookie("{$config['cookieprefix']}ubbt_pass","",time()-3600,"{$config['cookiepath']}");
          setcookie("{$config['cookieprefix']}ubbt_dob","",time()-3600,"{$config['cookiepath']}");
       }

   // -----------------------
   // Update last log on time

      $date = $this -> get_date();
      $newsessionid = addslashes($newsessionid);
      $Username_q = addslashes($CheckUser);
      $query = "
        UPDATE {$config['tbprefix']}Users
        SET    U_Laston   = $date,
               U_SessionId = '$newsessionid'
        WHERE  U_Username = '$Username_q'
      ";
      $dbh -> do_query($query);

   // ------------------------------------------
   // Get rid of their IP from the online table
      $ip = "-ANON-" . find_environmental('REMOTE_ADDR');
      $ip_q = addslashes($ip);
      $query = "
        DELETE FROM {$config['tbprefix']}Online
        WHERE  O_Username = '$ip_q'
      ";
      $dbh -> do_query($query);

   // Now send them to the start_page function   
      $this -> start_page($Cat,$Uid,$newsessionid,"",1);
             
     }

  // ####################################################################### 
  // Start_page - Sends the user to their start page 
  // ####################################################################### 
    function start_page($Cat="",$myid="",$mysess="",$chosenlanguage="",$firstlogin="") {
      global $theme,$config,$dbh, $ubbt_lang, ${$config['cookieprefix']."w3t_language"}, ${$config['cookieprefix']."w3t_myid"}, ${$config['cookieprefix']."w3t_mysess"}, $thispath, $tempstyle,$debug;

    // -----------------------------------------------------
    // Connect to db and get total posts and last logon, etc
       if ($mysess) { ${$config['cookieprefix']."w3t_mysess"} = $mysess; }
       if ($myid) { ${$config['cookieprefix']."w3t_myid"} = $myid; }

       $userob = new user;
       $user = $userob -> authenticate("U_Totalposts,U_Laston,U_Sort,U_Display,U_View,U_PostsPer,U_EReplies,U_TextCols,U_TextRows,U_Language,U_TempPass,U_TimeOffset,U_Groups,U_StartPage,U_Picture,U_Name,U_Title,U_Favorites,U_Number,U_PicWidth,U_PicHeight");
      list($totalposts,$laston,$sort,$display,$view,$postsper,$ereplies,$textcols,$textrows,$ubbt_language,$temppass,$offset,$groups,$startpage,$picture, $fullname,$title,$favorites,$Uid,$picwidth,$picheight,$CheckUser,$pass,$sessionid,$stylesheet,$status,$privates,$frontpage) = $user;

      $dbh -> finish_sth($sth);

    // If we don't a UID back then the authentication failed
		 if (!$Uid) {
         $this -> not_right($ubbt_lang['NO_AUTH'],$Cat);
		 }
	
    // If we have changed languages we do that here
       if ($chosenlanguage) {
          $ubbt_language = $chosenlanguage;
          $user['newlanguage'] = $ubbt_language;
          ${$config['cookieprefix']."w3t_language"} = $ubbt_language;
       }

   // -----------------------------------
   // Figure out their front page setting
      $catsonly = $frontpage;
      if ($config['catsonly']) {
         $main = "categories";
      }
      if (!$catsonly) { 
         $catsonly = $main;
      }
      if ($catsonly == "categories") {
         $main = "categories.php";
         $linker = "categories";
      }
      else {
         $main = "ubbthreads.php";
         $linker = "ubbthreads";
      }
      if ( ($startpage == "mi") && ($firstlogin) ) {
		   echo <<<EOF
<html>
<head>
<meta http-equiv="Refresh" content="1;url={$config['phpurl']}/$main?Cat=$Cat">
</head>
<body>
</body>
</html>
EOF;
			exit;
      }

   // -----------------------------------------
   // require the language file for this script
      if (!${$config['cookieprefix']."w3t_language"}) {
         ${$config['cookieprefix']."w3t_language"} = $config['language'];
      }
      require "languages/${$config['cookieprefix']."w3t_language"}/controlpanel.php";

      $html = new html;
      $html -> send_header("{$ubbt_lang['WEL_CONTROL']} $CheckUser",$Cat,0,$user);
  
   // ----------------------------------------------
   // Let's grab how many private messages they have
      $username_q = addslashes($CheckUser);
      $query = "
       SELECT COUNT(*)
       FROM   {$config['tbprefix']}Messages
       WHERE  M_Username = '$username_q'
       AND    M_Status <> 'X'
       ORDER BY M_Sent DESC
      ";
      $sth = $dbh -> do_query($query);
      list($total) = $dbh -> fetch_array($sth);
   
   // ------------------------------------------------------------------
   // Let's grab how many private messages they have in their sent folder
      $query = "
       SELECT COUNT(*) 
       FROM   {$config['tbprefix']}Messages
       WHERE  M_Username = '$username_q'
       AND    M_Status = 'X'
       ORDER BY M_Sent DESC
      ";
      $sth = $dbh -> do_query($query);
      list($totalsent) = $dbh -> fetch_array($sth);
   
  
   // -----------------------------------------------
   // Expire any outdated entries in the Online table
      $Outdated = $html -> get_date();
      $Outdated = $Outdated - 600;
      $query = "
       DELETE FROM {$config['tbprefix']}Online
       WHERE O_Last < $Outdated
      ";
      $dbh -> do_query($query);
   
   // ----------------------------------------
   // Grab the entries from their address book
      $query = "
       SELECT {$config['tbprefix']}AddressBook.Add_Member,{$config['tbprefix']}Online.O_What
       FROM   {$config['tbprefix']}AddressBook,{$config['tbprefix']}Online
       WHERE  {$config['tbprefix']}AddressBook.Add_Member = O_Username
       AND    {$config['tbprefix']}AddressBook.Add_Owner = '$username_q'
      ";
      $sth = $dbh -> do_query($query);
		require "{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/online.php";
      $any=0;
      $x=0;
      while ( list($member,$what) = $dbh -> fetch_array($sth)) {
   
      // ---------------------------
      // Check if they are invisible
         $extra = "";
         $memberq = addslashes($member);
         $query = "
          SELECT U_Visible
          FROM   {$config['tbprefix']}Users
          WHERE  U_Username = '$memberq'
         ";
         $sti = $dbh -> do_query($query);
         list($visible) = $dbh -> fetch_array($sti);
         if ($member == $user['U_Username']) { continue; }
         if ($visible == "no") {
            if ($user['U_Status'] != "Administrator") {
               continue;
            }
            else {
               $extra = "(I)";
            }
         }
         $memberq = rawurlencode($member);
         if (stristr($what,"faq_")) {
            $what = "faq_english";
         }
         $what = $ubbt_lang[$what];
         if (!$what) { $what = $ubbt_lang['all_admin']; }

         $online[$x]['extra'] = $extra;
         $online[$x]['memberq'] = $memberq;
         $online[$x]['member'] = $member;
         $online[$x]['what']   = $what;

         $x++;
         $any = 1; 
      }
   
      $onlinesize = sizeof($online);
      if (!$online) { $onlinesize = 1; }

 
   // --------------------------------------------------------------------------
   // Now echo the box for thread reminders
   // --------------------------------------------------------------------------
   
   // Grab any favorites with type r
      $query = "
       SELECT {$config['tbprefix']}Favorites.F_Thread,{$config['tbprefix']}Posts.B_Subject,{$config['tbprefix']}Favorites.F_Number,{$config['tbprefix']}Posts.B_Board,{$config['tbprefix']}Favorites.F_LastPost,{$config['tbprefix']}Posts.B_Username,{$config['tbprefix']}Posts.B_Main
       FROM   {$config['tbprefix']}Posts,{$config['tbprefix']}Favorites
       WHERE  {$config['tbprefix']}Favorites.F_Owner = '$username_q'
       AND    {$config['tbprefix']}Favorites.F_Type  = 'r'
       AND    {$config['tbprefix']}Favorites.F_Thread = {$config['tbprefix']}Posts.B_Number
       ORDER BY {$config['tbprefix']}Favorites.F_Number
       LIMIT 11
      ";
      $sth = $dbh -> do_query($query);
      $x=0;
      while ( list($reminder[$x]['Thread'],$reminder[$x]['Subject'],$reminder[$x]['Number'],$reminder[$x]['Board'],$LastPost,$reminder[$x]['poster'],$reminder[$x]['main']) = $dbh -> fetch_array($sth)) {

         $reminder[$x]['checkname'] = "E$x";
         $x++;
      }
      $remindersize = sizeof($reminder) - 1;
   
   // --------------------------------------------------------------------------
   // echo out the favorite threads box
   // --------------------------------------------------------------------------

   
   // -------------------------------------------------------
   // Setup a placeholder array for last visits to each board
   // This is necessary to see if there really are any unread posts
   // in any of the threads displayed
   
      $Viewable = "AND {$config['tbprefix']}Posts.B_Approved='yes'";
      if ( ($user['U_Status'] == "Administrator") || ($user['U_Status'] == "Moderator") ) {
         $Viewable = "";
      }
   
   // ------------------------------
   // Grab any favorites with type f
      $query = "
       SELECT {$config['tbprefix']}Posts.B_Last_Post,{$config['tbprefix']}Favorites.F_Thread,{$config['tbprefix']}Posts.B_Subject,{$config['tbprefix']}Favorites.F_Number,{$config['tbprefix']}Posts.B_Board,{$config['tbprefix']}Favorites.F_LastPost,{$config['tbprefix']}Posts.B_Username
       FROM   {$config['tbprefix']}Posts,{$config['tbprefix']}Favorites
       WHERE  {$config['tbprefix']}Favorites.F_Owner = '$username_q'
       AND    {$config['tbprefix']}Favorites.F_Type  = 'f'
       AND    {$config['tbprefix']}Favorites.F_Thread = {$config['tbprefix']}Posts.B_Number
       ORDER BY {$config['tbprefix']}Posts.B_Last_Post
       LIMIT 11
      ";
      $sth = $dbh -> do_query($query);

      $x=0;   
      while ( list($RealLast,$favorite[$x]['Thread'],$favorite[$x]['Subject'],$favorite[$x]['Number'],$favorite[$x]['Board'],$LastPost,$favorite[$x]['poster']) = $dbh -> fetch_array($sth)) {
     
         $partnumber = "";
         $piece['0'] = "";
         preg_match("/-$Board=(.*?)-/",${$config['cookieprefix']."w3t_visit"},$piece);
         $unread = $piece['1'];
         if (!$unread) { $unread = $lastread[$Board]; }

         if ( ($RealLast > $LastPost) && ($RealLast > $unread) ) {
         // ------------------------------------------------------------
         // If we don't have a last visit from their cookie then we need
         // to grab their last visit to this board
            if (!$unread) {
               $Board_q = addslashes($Board);
               $query = "
                 SELECT L_Last
                 FROM   {$config['tbprefix']}Last
                 WHERE  L_Username = '$username_q'
                 AND    L_Board    = '$Board_q'
               ";
               $sti = $dbh -> do_query($query);
               list($last) = $dbh -> fetch_array($sti);
               $lastread[$Board] = $last; 
               $unread = $last;
            }
            if ($RealLast > $unread) {  
            // ----------------------------------------------------
            // It looks like there are some new messages in here so
            // Now we need to grab all of the replies in this thread
               $Board_q = addslashes($Board);
               $query = "
                 SELECT B_Number,B_Parent,B_Posted
                 FROM   {$config['tbprefix']}Posts
                 WHERE  B_Main  = '$Thread'
                 AND    B_Board = '$Board_q'
                 AND    B_Number <> '$Number'
                 $Viewable
                 ORDER BY B_Posted
               ";
               $sti = $dbh -> do_query($query);
               $replydata = $dbh -> fetch_array($sti);
               $replies   = $dbh -> total_rows($sti); 
               $new = '';
               $newreplies = 0;
               $cycle = 0;
               for ( $i=0; $i<$replies;$i++) {
                  $cycle++;
                  if ($cycle == $flatposts) {
                     $pagejump++;
                     $cycle = 0;
                  }
                  list($No,$Pa,$Po) = $dbh -> fetch_array($sti);
                  $checkthis = ",$No,";
                  if ( ($Po > $unread) && (!preg_match("/$checkthis/",$read)) )  {
                     $favorite[$x]['newmarker'] = "<img align=\"absmiddle\" src=\"{$config['images']}/new.gif\" alt=\"{$ubbt_lang['NEW_TEXT']}\" />";
                     if (!$postmarker) {
                        $favorite[$x]['postmarker'] = "#Post$No";
                        $favorite[$x]['partnumber'] = $pagejump;
                     }
                  }
               }
            }
         }
         $favorite[$x]['checkname'] = "E$x";
         $x++;
      }
      $favoritesize = sizeof($favorite) - 1;
   

   // -------------------------------------
   // Split out all of their favorit forums
      $favs = split("-",$favorites);
      $initialcat = "";
      $fsize = sizeof($favs);
      $g=0;
      for ($i=0; $i<$fsize;$i++) {
         if (!preg_match("/[0-9]/",$favs[$i])) { continue; };
         $g++;
         if ($g > 1) {
            $favquery .= " OR ";
         }
         $favquery .= "Bo_Number = $favs[$i]";
      }

   // --------------------------------------------------------------
   // If they are only a user we set Viewable to approved posts only
      $Viewable = "AND B_Approved = 'yes'";
      if ($user['U_Status'] != "User") {
         $Viewable = "";
      }    
      $firstpass = 0;

   // ------------------------
   // Grab all favorite forums
      if ($favquery) {
         $query = "
           SELECT Bo_Title,Bo_Keyword,Bo_Threads,Bo_Total,Bo_CatName, Bo_Cat, Bo_Last, Bo_Moderated,Bo_Number
           FROM   {$config['tbprefix']}Boards
           WHERE  $favquery 
           ORDER  BY Bo_Cat,Bo_Sorter
         ";
         $sth = $dbh -> do_query($query);
         $cats = 0;
         $forums = 0;
         while (list($Title,$Board,$threads,$posts,$catname,$catnum,$lastpost,$moderated,$bnumber) = $dbh -> fetch_array($sth)) {
            if (!$firstpass) {
               $initialcat = $catnum;
               $firstpass = 1;
               $oldcatname = $catname;
            }
            if ($catnum != $initialcat) {
               $initialcat = $catnum;
               $catrow[$cats]['catname'] = $oldcatname;
               $catrow[$cats]['forumsize'] = sizeof($forumrow[$cats]);
               $cats++;
               $forums=0;
               $oldcatname = $catname;
            }

         // ------------------------------------------------------------------
         // Let's see if there are new posts since they last visited the forum
            $Board_q = addslashes($Board);
            $checker = $lastread[$Board];
            if (!$checker) {
               $query = "
                SELECT L_Last
                FROM   {$config['tbprefix']}Last
                WHERE  L_Username = '$username_q'
                AND    L_Board    = '$Board_q'
               ";
               $sti = $dbh -> do_query($query);
               list($checker) = $dbh -> fetch_array($sti); 
            }
            if (!$checker) { $checker = "0"; }
     
         // --------------------------------------
         // Let's see how many new posts there are
            $query = "
              SELECT COUNT(*), SUM(B_Topic)
              FROM   {$config['tbprefix']}Posts
              WHERE  (B_Posted > $checker AND B_Posted <> 4294967295)
              $Viewable
              AND    B_Board = '$Board_q'
            ";
            $sti1 = $dbh -> do_query($query);
            $newposts1 = $dbh -> fetch_array($sti1);
            $newthreads1 = $newposts1[1];
            $newposts1 = $newposts1[0];

            $query = "
              SELECT COUNT(*), SUM(B_Topic)
              FROM   {$config['tbprefix']}Posts
              WHERE  (B_Posted = 4294967295 AND B_Sticky > $checker)
              $Viewable
              AND    B_Board = '$Board_q'
            ";
            $sti2 = $dbh -> do_query($query);
            $newposts2 = $dbh -> fetch_array($sti2);
            $newthreads2 = $newposts2[1];
            $newposts2 = $newposts2[0];
            $newposts = $newposts1 + $newposts2;
            $newthreads = $newthreads1 + $newthreads2;

            if ($newposts) {
               $shownew = " <font class=\"new\">($newposts {$ubbt_lang['NEW_TEXT']})</font>";
               if ($newthreads) {
                  $shownewt = " <font class=\"new\">($newthreads {$ubbt_lang['NEW_TEXT']})</font>";
               } 
            }

         // --------------------------------------
         // Let's see how many NA posts there are
            if ( ($moderated == "yes") && ($Viewable == "") ) {
               $query = "
                 SELECT COUNT(*)
                 FROM   {$config['tbprefix']}Posts
                 WHERE  B_Approved = 'no'
                 AND    B_Board    = '$Board_q'
               ";
               $sti = $dbh -> do_query($query);
               list($notapproved) = $dbh -> fetch_array($sti);
            }
      
            if ($checker < $lastpost) {
              $forumrow[$cats][$forums]['shownewt'] = $shownewt; 
            }
            if ($checker < $lastpost) {
              $forumrow[$cats][$forums]['shownew'] = $shownew; 
            }
            if ($notapproved) {
              $forumrow[$cats][$forums]['notapproved'] = " <font class=\"new\">($notapproved {$ubbt_lang['NOT_APPROVED']})</font>";
            }
            if ($lastpost) {
              $forumrow[$cats][$forums]['lastpost'] = $html -> convert_time($lastpost,$timeoffset);
            }
            $forumrow[$cats][$forums]['bnumber'] = $bnumber;
            $forumrow[$cats][$forums]['Title'] = $Title;
            $forumrow[$cats][$forums]['threads'] = $threads;
            $forumrow[$cats][$forums]['posts']   = $posts;
            $forumrow[$cats][$forums]['Board'] = $Board;
            $forums++;
         }
      }

	// Now finish this out for the last category
		$catrow[$cats]['catname'] = $oldcatname;
		$catrow[$cats]['forumsize'] = sizeof($forumrow[$cats]);
      $catsize = sizeof($catrow);
      if (!$catsize) { $catsize = 1; }

   // ------------------------------------------------------------------------
   // User profile box section
   // --------------------------------------------------------------------------
   
   // ------------------------------------------------
   // Set picture to blank if they do not have one set
      if ( ($picture == "http://") || (!$picture) ) {
         $picture = "{$config['images']}/nopicture.gif";
      }
      $picsize = "";
      if ($picwidth && $picheight) {
         $picsize = "width=\"$picwidth\" height=\"$picheight\"";
      }
      else {
         $picsize = "width=\"{$theme['PictureWidth']}\" height=\"{$theme['PictureHeight']}\"";
      }

      if (!$debug) { 
         include("$thispath/templates/$tempstyle/myhome.tmpl");
      }  
   
      $html -> send_footer();
             

   }

  // #######################################################################
  // Create the icon selection list
  // #######################################################################
     function icon_select($icon="") {
 
       global $thispath,$tempstyle, $ubbt_lang, $config,$debug;
       require ("imagesizes.php");

       if (!$icon) { $icon = "book.gif"; }
       list($selected,$extra) = split("\.",$icon);
       ${$selected} = "selected=\"selected\" checked=\"checked\"";

		 // --------------------------------------------------------
		 // We now load the entire directory of images into an array
	    $dir = opendir("{$config['imagepath']}/icons");
		 $i=0;
   	 while( ($file = readdir($dir)) != false) {
       	if ( ($file == ".") || ($file == "..") || ($file == "lock.gif") ) {
         	 continue;
       	}
			if (!preg_match("/.(gif|jpg|png)/",$file)) {
				continue;
			}
			$filename = preg_replace("/\.(.*?)$/","",$file);
         $iconlist .= "<input type=\"radio\" name=\"Icon\" value=\"$file\" ${$filename} /><img src=\"{$config['images']}/icons/$file\" alt=\"$filename\" title=\"$filename\" />&nbsp;";
			$i++;
			if ($i==9) {
				$iconlist .= "<br />";
				$i=0;
			}

		 }

		 if (!$debug) {
          include("$thispath/templates/$tempstyle/ubbt_icon_select.tmpl");
       }

       return $template;

     }

  // #####################################################################
  // Create the instant graemlin/ubbcode 
  // #####################################################################

     function instant_ubbcode() {

        global $config,${$config['cookieprefix']."w3t_language"},$thispath,$tempstyle,$ubbt_lang,$debug,$dbh;

        require ("imagesizes.php");

			$langfile = ${$config['cookieprefix']."w3t_language"};
  			if (!$langfile) {
    			$langfile = $config['language'];
  			}
        	require ("languages/$langfile/instant_markup.php");

		// --------------------------------------------------
		// We need to grab all of the graemlins out of the db
			$query = "
				SELECT G_Code,G_Smiley,G_Image
				FROM {$config['tbprefix']}Graemlins
			";
			$sth = $dbh -> do_query($query);
			$i=0;
			while (list($code,$smiley,$image) = $dbh -> fetch_array($sth)) {
				if ($smiley) { 
					$code = $smiley; 
				} else {
					eval("\$code = $code;");
					$code = ":$code:";
				}
				$graemlinlist .= <<<EOF
 <a href="javascript:void(0)" onclick="insertAtCaret(document.replier.Body, ' $code'); document.replier.Body.focus();"><img src="{$config['images']}/graemlins/$image" border="0" alt="$code" /></a>  &nbsp;
EOF;
				$i++;
				if ($i==6) {
					$i=0;
					$graemlinlist .= "<br />";
				}
			}

        if (!$debug) {
           include("$thispath/templates/$tempstyle/ubbt_instant_ubbcode.tmpl");
        }
        return $template;

     }


  // ####################################################################### 
  //  Send_messages
  // ####################################################################### 
     function send_message($Sender="",$To="",$Subject="",$Mess="",$Group="") {

       global $ubbt_lang,$dbh, $config;
       $Sender_q  = addslashes($Sender);
       $To_q      = addslashes($To);
       $Subject_q = addslashes($Subject);
       $Mess_q    = addslashes($Mess);
       $date      = $this -> get_date();
       $Status_q  = $ubbt_lang['TEXT_NEW'];

    // FInd out if we are sending to a group of users
       $admin_q = "Administrator";
       $mod_q   = "Moderator";
       $user_q  = "User";

       if ($Group == "ADMIN_GROUP") {
         $selector = "WHERE U_Status = '$admin_q'";
       }
       elseif ($Group == "MODERATOR_GROUP") {
         $selector = "WHERE U_Status = '$mod_q'";
       }
       elseif ($Group == "A_M_GROUP") {
         $selector = "WHERE U_Status = '$admin_q' OR U_Status = '$mod_q'";
       }
       elseif ($Group == "ALL_USERS") {
         $selector = "";
       }
       else {
         $selector = "WHERE U_Username = '$To_q'";
       }

    // ------------------------------------
    // Grab everyone we are sending this to
       $query = "
         SELECT DISTINCT U_Username, U_Status
         FROM {$config['tbprefix']}Users
         $selector
       ";
       $sth = $dbh -> do_query($query);
       while (list($To,$Status) = $dbh -> fetch_array($sth)) {
         
       // Increment the recipients total number of unread pms
          $To_q = addslashes($To);
          $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_Privates = U_Privates + 1
            WHERE  U_Username = '$To_q'
          ";
          $dbh -> do_query($query);

       // INSERT DEPENDS ON AUTO_INCREMENT OR SEQUENCE
          if ($config['dbtype'] == "mysql") {
             $query = "
                INSERT INTO {$config['tbprefix']}Messages(M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
                VALUES ('$To_q','$Status_q','$Subject_q','$Sender_q','$Mess_q','$date')
             ";
             $dbh -> do_query($query);
          }
          else {
             $seq = "nextval('M_seq')";
             $query = "
                INSERT INTO {$config['tbprefix']}Messages(M_Username,M_Status,M_Subject,M_Sender,M_Message,M_Sent)
                VALUES ('$seq','$To_q','$Status_q','$Subject_q','$Sender_q','$Mess_q','$date')
             ";
             $dbh -> do_query($query);
          }
      }
      $dbh -> finish_sth($sth);
    }
    
  }

// ########################################################################
// USER CLASS 
// Define a class for the user object
// ########################################################################
  class user {

  // ###################################################################### 
  // AUTHENTICATE FUNCTION
  // Authenticate the user
  // ######################################################################
    function authenticate($Query="") {

      global $dbh, $HTTP_COOKIE_VARS, $config, ${$config['cookieprefix']."w3t_key"}, $config,${$config['cookieprefix']."w3t_mysess"}, ${$config['cookieprefix']."w3t_myid"};

      if ($Query == "*") {
        $Query = "*";
      }
  // -----------------------------------------------------------------
  // We are automatically adding StyleSheet, Status, Privates and
  // FrontPage and Number to each SQL call because this information is needed by
  // every script that makes a call to authenticate
      else {
        if ($Query) { $Query .=","; }
        $Query .= "U_Username,U_Password,U_SessionId, U_StyleSheet, U_Status, U_Privates, U_FrontPage, U_Number";
      }
      $Uid = addslashes(${$config['cookieprefix']."w3t_myid"});

      $query = "SELECT $Query FROM {$config['tbprefix']}Users WHERE U_Number = '$Uid'";
      $sth = $dbh -> do_query($query);
      $thisuser = $dbh -> fetch_array($sth);
      $dbh -> finish_sth($query);
      if ( ($thisuser['U_SessionId']) && ($thisuser['U_SessionId'] == ${$config['cookieprefix']."w3t_mysess"}) ) {
         return $thisuser;
      } elseif (${$config['cookieprefix']."w3t_key"} == md5("{$thisuser['U_Username']}{$thisuser['U_Password']}")) {
         srand((double)microtime()*1000000);
         $newsessionid = md5(rand(0,32767));
         $newsessionid_q = addslashes($newsessionid);
         $query = "
            UPDATE {$config['tbprefix']}Users
            SET    U_SessionId = '$newsessionid_q'
            WHERE  U_Number = $Uid
         ";
         $dbh -> do_query($query);
         if ($config['tracking'] == "sessions") {
             session_register("{$config['cookieprefix']}w3t_mysess");
             ${$config['cookieprefix']."w3t_mysess"} = $newsessionid;
         }
         else {
            setcookie("{$config['cookieprefix']}w3t_mysess","$newsessionid","0","{$config['cookiepath']}");
         }
         return $thisuser;
      }
      $blankarray['0'] = "";
      return $blankarray;
    }

  // ####################################################################### 
  // Check_ban 
  // ####################################################################### 
    function check_ban($Username="",$Cat="") {

       global $ubbt_lang,$dbh,$config;
       $Hostname = find_environmental('REMOTE_ADDR');
       $Username_q = addslashes($Username);
       $Hostname_q = addslashes($Hostname);

       $query = "
          SELECT B_Hostname,B_Username,B_Reason
          FROM   {$config['tbprefix']}Banned
          WHERE  B_Username='$Username_q' OR '$Hostname_q' LIKE B_Hostname
       ";
       $sth = $dbh -> do_query($query);
       list ($Checkuser,$Checkhost,$Reason) = $dbh -> fetch_array($sth);
       $dbh -> finish_sth($sth);
       if ( ($Checkuser) || ($Checkhost) ) {
          $html = new html;
          $html -> not_right("{$ubbt_lang['YOU_BANNED']}: $Reason", $Cat);
       }
    }
  }

// ----------------------------------------------------------
// Make sure we can find environment variables on any system     
function find_environmental ($name) {

	global $HTTP_SERVER_VARS;

	$this = "";

	// Regular way
	if(getenv($name) != '') {
		$this = getenv("$name");
	} // end if

	// Irregular way
	if(($this == '') && ($HTTP_SERVER_VARS["$name"] != '')) {
		$this = $HTTP_SERVER_VARS["$name"];
	} // end if

	// 4.1 way
	if(($this == '') && ($_ENV["$name"] != '')) {
		$this = $_ENV["$name"];
	} // end if

	return $this;
} // end func

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
} 

// #######################################################################
// No Javascript
// DOn't want anyone using javascript in the color markup
// #######################################################################
function nojs($string="") {
		
	$string = str_replace(" ","",$string);
	$string = str_replace("\"","",$string);
   $string = "<font color=\"$string\">";
	return $string;
}

 
?>
