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

// ------------------------
// Set the current version
   $thisversion = "6.1.1";

// Require the library
   require ("../main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }


// ------------------------
// Send them a page 
  $html -> send_header ("Edit configuration settings",$Cat,0,$user);
  $html -> admin_table_header("Edit configuration settings (VERSION: $thisversion)");
  $html -> open_admin_table();

// ------------------------------------------------------------------------
// We need to define the base set of keys that we need for the operation of
// the program
   $knownkeys = "-persistent-dbtype-isclosed-phpurl-images-path-styledir-stylepath-dbserver-dbname-dbdriver-dbuser-dbpass-referer-title-homeurl-urltitle-emailaddy-emailtitle-userlist-catsonly-allowpolls-whovote-edittime-checkage-specialc-modedit-anonnames-language-censored-subjectlength-Sig_length-cookieexp-adjusttime-showip-subscriptions-private-allowimages-files-fileurl-allowfiles-filesize-userpass-multiuser-preview-dateslip-extra1-extra2-extra3-extra4-extra5-ICQ_Status-newusergroup-imagepath-sessionpath-tracking-mailpost-markupoption-newcounter-cookiepath-privacy-boardrules-under13-userreg-compression-debugging-avatars-avatarsize-avatarurl-disablerefer-mysqlpath-mysqldumppath-dumpdir-cookieprefix-imageurl-tbprefix-";

// ---------------------------------------------------------------------
// Now we need to check for any new keys that have been introduced since
// the current version being ran
   $keycheck = split("-",$knownkeys);
   $keysize = sizeof($keycheck);
   for ( $i=0; $i<=$keysize;$i++) {
    if (!isset($config[$keycheck[$i]])) {
      $new[$keycheck[$i]] = "class = standouttext";
    }
  }

// ---------------------------------------------------------------
// Let's set some default values from the existing config.inc.php file
   if ($config['isclosed']) { 
      $notclosed = "SELECTED"; 
   }
   else {
      $isclosed = "SELECTED";
   }

   if ($config['userlist'] == 0) {
      $userlistno = "SELECTED";
   }
   elseif ($config['userlist'] == 1) {
      $userlistall = "SELECTED";
   }
   else {
      $userlistreg = "SELECTED";
   }

   if ($config['catsonly']) {
      $catsonly = "SELECTED";
   }
   else {
      $nocatsonly = "SELECTED";
   }

   if ($config['allowpolls']) {
      $pollsall = "SELECTED";
   }
   else {
      $nopollsall = "SELECTED";
   }

   if ($config['whovote']) {
      $allvote = "SELECTED";
   }
   else {
      $noallvote = "SELECTED";
   }

   if ($config['checkage']) {
      $checkage = "SELECTED";
   }
   else {
      $nocheckage = "SELECTED";
   }

   if ($config['under13']) {
      $under13 = "SELECTED";
   }
   else {
      $nounder13 = "SELECTED";
   }


   if ($config['specialc']) {
      $specialc = "SELECTED";
   }
   else {
      $nospecialc = "SELECTED";
   }

   if ($config['modedit']) {
      $modedit = "SELECTED";
   }
   else {
      $nomodedit = "SELECTED";
   }

   if ($config['anonnames']) {
      $anonnames = "SELECTED";
   }
   else {
      $noanonnames = "SELECTED";
   }

   if ($config['language'] == "big5") {
      $big5 = "SELECTED";
   }
   elseif ($config['language'] == "chinese") {
      $chinese = "SELECTED";
   }
   elseif ($config['language'] == "danish") {
      $danish = "SELECTED";
   }
   elseif ($config['language'] == "dutch") {
      $dutch = "SELECTED";
   }
   elseif ($config['language'] == "english") {
      $english = "SELECTED";
   }
   elseif ($config['language'] == "french") {
      $french = "SELECTED";
   }
   elseif ($config['language'] == "german") {
      $german = "SELECTED";
   }
   elseif ($config['language'] == "hungarian") {
      $hungarian = "SELECTED";
   }
   elseif ($config['language'] == "italian") {
      $italian = "SELECTED";
   }
   elseif ($config['language'] == "polish") {
      $polish = "SELECTED";
   }
   elseif ($config['language'] == "portuguese") {
     $portuguese = "SELECTED";
   }
   elseif ($config['language'] == "romanian") {
      $romanian = "SELECTED";
   }
   elseif ($config['language'] == "russian") {
      $russian = "SELECTED";
   }
   elseif ($config['language'] == "spanish") {
      $spanish = "SELECTED";
   }
   elseif ($config['language'] == "swedish") {
      $swedish = "SELECTED";
   }
   else {
      $english = "SELECTED";
   }

   if ($config['showip'] == 0) {
      $ipnone = "SELECTED";
   }
   elseif ( $config['showip'] == 1 ) {
      $ipall = "SELECTED";
   }
   elseif ( $config['showip'] == 2 ) {
      $ipmodadmin = "SELECTED";
   }
   else {
      $ipadmin = "SELECTED";
   }

   if ($config['subscriptions']) {
      $subscriptions = "SELECTED";
   }
   else {
      $nosubscriptions = "SELECTED";
   }

   if ($config['private']) {
      $private = "SELECTED";
   }
   else {
      $noprivate = "SELECTED";
   }

   if ($config['allowimages']) {
      $allowimages = "SELECTED";
   }
   else {
      $noallowimages = "SELECTED";
   }

  if ($config['userpass']) {
    $userpass = "SELECTED";
  }
  else {
    $nouserpass = "SELECTED";
  }

  if ($config['multiuser']) {
    $multiuser = "SELECTED";
  }
  else {
    $nomultiuser = "SELECTED";
  }

  if ($config['preview']) {
    $Preview = "SELECTED";
  }
  else {
    $NoPreview = "SELECTED";
  }

  if ($config['dateslip']) {
    $dateslip = "SELECTED";
  }
  else {
    $nodateslip = "SELECTED";
  }

  if ($config['ICQ_Status']) {
    $ICQ_Status = "SELECTED";
  }
  else {
    $NoICQ_Status = "SELECTED";
  }

  if ($config['persistent']) {
    $persistent = "SELECTED";
  }
  else {
    $nopersistent = "SELECTED";
  }

  if ($config['tracking'] == "sessions") {
    $sessions = "SELECTED";
  }
  else {
    $cookies = "SELECTED";
  }

  if ($config['mailpost'] == "1") {
    $mailon = "SELECTED";
  } else {
    $mailoff = "SELECTED";
  }

  if ($config['markupoption'] == "1") {
    $markupoptionon = "SELECTED";
  }
  else {
    $markupoptionoff = "SELECTED";
  }

  if ($config['newcounter'] == "2") {
    $newcounterall = "SELECTED";
  }
  if ($config['newcounter'] == "1") {
    $newcountermid = "SELECTED";
  }
  else {
    $newcounteroff = "SELECTED";
  }
	
	if ($config['maincount'] == "1") {
		$maincounton = "SELECTED";
	}
	else {
		$maincountoff = "SELECTED";
	}

  if ($config['privacy'] == "1") {
    $yesprivacy = "SELECTED";
  }
  else {
    $noprivacy = "SELECTED";
  }

  if ($config['boardrules'] == "1") {
    $yesboardrules = "SELECTED";
  }
  else {
    $noboardrules = "SELECTED";
  }

  if ($config['showmods'] == "1") {
    $showmodson = "SELECTED";
  }
  else {
    $showmodsoff = "SELECTED";
  }

  if ($config['userreg']) {
     $userreg = "SELECTED";
  }
  else {
     $nouserreg = "SELECTED";
  }

  if ($config['compression']) {
     $usecompression = "SELECTED";
  }
  else {
     $nocompression = "SELECTED";
  }

  if ($config['debugging']) {
     $yesdebug = "SELECTED";
  }
  else {
     $nodebug = "SELECTED";
  }

  if ($config['disablerefer']) {
     $nocheckrefer = "SELECTED";
  }
  else {
     $yescheckrefer = "SELECTED";
  }

  	if (!$config['mysqlpath']) {
  		$config['mysqlpath'] = "/usr/bin";
	}
	if (!$config['mysqldumppath']) {
		$config['mysqldumppath'] = "/usr/bin";
	}
 
  echo "
    <TR><TD class=\"lighttable\">
    You can edit any of the information below.<p>
  ";

   if (!$configdir) {
      $configdir = $thispath;
   }
	if (!$config['tbprefix']) { $config['tbprefix'] = "w3t_"; }

  if (!is_writeable("$configdir/config.inc.php")) {
    echo "<b>$configdir/config.inc.php is not writeable, so you will not be able to use this tool until the permissions are changed.</b><p>";
  }

  echo <<<EOF
    <FORM METHOD = POST action ="{$config['phpurl']}/admin/doeditconfig.php" name=configedit>
    <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
    <INPUT TYPE=HIDDEN NAME=thisversion VALUE="$thisversion">
    <input type=hidden name=Number value=$Number>
    <input type=hidden name=tbprefix value="{$config['tbprefix']}">
    <hr>
    <b>Database settings</b>
    <p {$new['dbserver']}>
     Server hosting the database <br>
     <input type=text name=dbserver size=50 class=formboxes value="{$config['dbserver']}">
   
    <p {$new['dbname']}>
     Name of the database <br>
     <input type=text name=dbname size=50 class=formboxes value="{$config['dbname']}">
   
    <p {$new['dbuser']}>
     Username that has permission to the database<br>
     <input type=text name=dbuser size=50 class=formboxes value="{$config['dbuser']}">
   
    <p {$new['dbpass']}>
     Password for the database <br>
     <input type=password name=dbpass size=50 class=formboxes value="hiddenpass">

    <p {$new['persistent']}>
    Do you want to use persistent connections?  Note that on some hosted servers you may not be allowed to do this so contact your host before turning this on.<br>
    <select name="persistent" class="formboxes">
      <option value="1" $persistent>Yes, use persistent connections
      <option value="0" $nopersistent>No, do not use persistent connections
    </select>

	<p {$new['mysqlpath']}>
	Path to mysql.  Only necessary for using the database restore function in the admin section.<br>
	<input type="text" name="mysqlpath" value="{$config['mysqlpath']}" class="formboxes" size="50">

	<p {$new['mysqldumppath']}>
	Path to mysqldump.  Only necessary for using the database backup function in the admin section.<br>
	<input type="text" name="mysqldumppath" value="{$config['mysqldumppath']}" class="formboxes" size="50">

	<p {$new['dumpdir']}>
	Directory to store database backups in.  Only necessary for using the database backup/restore function in the admin section.  This directory will need write permissions.<br>
	<input type="text" name="dumpdir" value="{$config['dumpdir']}" class="formboxes" size="50">

    <p>
    <hr>

    <b>Path, URL and tracking settings</b>
    <p {$new['phpurl']}>
    Url to the main UBB.threads install<br>
    <input type=text name=phpurl class=formboxes size=50 value="{$config['phpurl']}">

    <p {$new['path']}>
    Path to the main UBB.threads install<br>
    <input type=text name=path class=formboxes size=50 value="{$config['path']}">

    <p {$new['images']}>
    Absolute URL containing the images for the program:<br>
    <input type=text size=50 name="images" value="{$config['images']}" class=formboxes>

	 <p {$new['imageurl']}>
	 Full URL to your images folder.  (Necessary for users that want their emails in HTML format):<br>
	 <input type=text size=50 name="imageurl" value="{$config['imageurl']}" class="formboxes">

    <p {$new['imagepath']}>
    Path to your images directory, need for calculating image sizes:<br>
    <input type=text size=50 name="imagepath" value="{$config['imagepath']}" class=formboxes>

    <p {$new['styledir']}>
    Absolute URL containing the stylesheets for the program:<br>
    <input type=text size=50 name="styledir" value="{$config['styledir']}" class=formboxes>
    
    <p {$new['stylepath']}>
    Path to your stylesheets directory:<br>
    <input type=text size=50 name="stylepath" value="{$config['stylepath']}" class=formboxes>

    <p {$new['tracking']}>
    Do you want to use sessions or cookies to track your users?<br>
    <select name=tracking class=formboxes>
      <option value="cookies" $cookies> Use cookies
      <option value="sessions" $sessions> Use sessions
    </select>

    <p {$new['sessionpath']}>
    Path to save session information.  This is only used if your "tracking" variable is set to sessions.  This needs to be a world writeable directory outside of your html tree:<br>
    <input type=text size=50 name="sessionpath" value="{$config['sessionpath']}" class=formboxes> 

    <span class=small>(<a href="javascript:void(0);" onClick="javascript:window.open('{$config['phpurl']}/admin/testdirectory.php?type=sess&dirname=' + configedit.sessionpath.value,'_blank','toolbar=no, menubar=no,location=no,directories=no,status=no,width=400,height=300,top=200,left=200');">Click to test directory</a>)</span>

    <p {$new['cookiepath']}>
    If you are using cookies you can specify a path as well. Leaving this blank will make the cookies only available under the UBB.threads install directory.  Setting this to / will make them available to your entire website.<br>
    <input type=text name=cookiepath value="{$config['cookiepath']}" class=formboxes>

    <p {$new['cookieprefix']}>
  Custom cookie prefix.  Can be left blank but useful if you have 2 installs and don't want cookies from one overwriting the other.  WARNING: Changing this will make all current cookies invalid, meaning everyone will be logged out.<br>
    <input type=text name="cookieprefix" value="{$config['cookieprefix']}" class="formboxes">

    <p {$new['disablerefer']}>
  Do you want to disable the referer check?  Only disable this if you have many users that are unable to post due to firewall/proxy servers manipulating their referer variable.<br>
    <select name=disablerefer class=formboxes>
      <option value=0 $yescheckrefer>No, always check referer
      <option value=1 $nocheckrefer>Yes, disable this check.
    </select>


    <p {$new['referer']}>
    If the referer check is not disable, then please supply the name of the server the program is running on.  If necessary seperate referers with a |<br>
    <input type=text size=50 name="referer" value="{$config['referer']}" class="formboxes">


    <p>
    <hr>
    <b>Site specific - Navigation settings</b>
    <p {$new['isclosed']}>
    Are your forums open or closed? (Closed forums will only be accessible by admin uses) <br>
    <select name=isclosed class=formboxes>
      <option value=0 $isclosed>Open
      <option value=1 $notclosed>Closed
    </select>


    <p {$new['title']}>
    Title of your site:<br>
    <input type=text size=50 name="title" value="{$config['title']}" class=formboxes>

    <p {$new['homeurl']}>
    Your Homepage URL:<br>
    <input type=text size=50 name="homeurl" value="{$config['homeurl']}" class=formboxes>

    <p {$new['urltitle']}>
    Text to use for the homepage link:<br>
    <input type=text size=50 name="urltitle" value="{$config['urltitle']}" class=formboxes>

    <p {$new['emailaddy']}>
    Contact email address:<br>
    <input type=text size=50 name="emailaddy" value="${config['emailaddy']}" class=formboxes>

    <p {$new['emailtitle']}>
    Text to use for the email address:<br>
    <input type=text size=50 name="emailtitle" value="${config['emailtitle']}" class=formboxes>

    <p {$new['privacy']}>
    Privacy statement link in footer?<br>
    <select name=privacy class=formboxes>
      <option value=1 $privacy>On
      <option value=0 $noprivacy>Off
    </select>

    <p {$new['boardrules']}>
    Show board rules on new user screen?<br>
    <select name=boardrules class=formboxes>
      <option value=1 $boardrules>On
      <option value=0 $noboardrules>Off
    </select>
   
    <p>
     
    <hr>
    <b>Special functions</b>

    <p {$new['newcounter']}>
  Do you want to do full new post tracking (standard behavior)?  If, this is set to full, then this generates the most queries (2 per forum on main page).  If this is set to thread only, then it doesn't generate the 2 queries on the main page but users will still see there are new posts and it will still show how many are new when viewing individual threads.  If this is off then new posts are not tracked at all.<br>

    <select name=newcounter class=formboxes>
      <option value=0 $newcounteroff>No, don't track new posts
      <option value=1 $newcountermid>Only track new posts on individual threads (thread only).
		<option value=2 $newcounterall>Do full new post tracking (full).
    </select>

    <p {$new['userreg']}>
    Approve all new user registrations? (An email will be sent to all Admins when a user registers)<br>
    <select name=userreg class=formboxes>
      <option value="1" $userreg>Yes
      <option value="0" $nouserreg>No
    </select>

    <p {$new['newusergroup']}>
    What groups do you want new users to belong to?<br>
EOF;

  // --------------------------------------------------------------------------
  // List out all of the current groups besides the admin/moderator/guest group
     $query = "
      SELECT G_Name, G_Id
      FROM   {$config['tbprefix']}Groups
    ";
     $sth = $dbh -> do_query($query);

  // --------------------------------------------------------------------
  // This gets somewhat confusing.  Basically we are echoing out a table
  // with 3 colums.  When we are done listing the groups then we need
  // to fill out the rest of the table with nbsp;
     $html -> open_admin_table();
     $row =0;
     if (!$config['newusergroup']) {
        $config['newusergroup'] = "-3-";
     }
     while ( list($Name,$Id) = $dbh -> fetch_array($sth)) {
        $checked = "";
        if ( ($Id < 5) && ($Id != 3)) { continue; }
        if ($row == 0) { echo "<TR>"; }
        $row++;
        echo "<TD width=33% class=\"lighttable\">";
        $checky = "-$Id-";
        if (ereg("$checky",$config['newusergroup'])) {
           $checked = "CHECKED";
        }
        echo "<INPUT TYPE=CHECKBOX NAME=\"GROUP$Id\" $checked VALUE=\"$Id\" class=\" formboxes\"> ";
        echo "$Name";
        echo "</TD>";
        if ($row == 3) {
           echo "</TR>";
           $row = 0;
        }
    }
    if ($row > 0) {
       for ( $i=0; $i<(3-$row); $i++) {
          echo "<TD width=33% class=\"lighttable\">&nbsp;</TD>";
       }
       echo "</TR>";
    }
    echo <<<EOF
    </TD></TR></TABLE>
    </TABLE>

    <P {$new['compression']}>
    Use zlib compression if available (pages will be smaller but it may cause excess server overhead on busy sites:<br />
    <select name=compression class=formboxes>
      <option value=0 $nocompression>No
      <option value=1 $usecompression>Yes
    </select>

    <P {$new['debugging']}>
    Print debugging code in footer (page generation times, number of queries, compression):<br />
    <select name=debugging class=formboxes>
       <option value=0 $nodebug>No
       <option value=1 $yesdebug>Yes
    </select>

    <P {$new['markupoption']}>
    Allow users to turn on/off markup or html (if enabled) when they post:<br>
    <select name=markupoption class=formboxes>
      <option value=0 $markupoptionoff>No
      <option value=1 $markupoptionon>Yes
    </select>

    <P {$new['mailpost']}>
    Do you want users to be able to mail posts to others:<br>
    <select name=mailpost class=formboxes>
      <option value=0 $mailoff>No
      <option value=1 $mailon>Yes
    </select>
 
    <p {$new['userlist']}>
    Show the userlist in the navigation menu:<br>
    <select name=userlist class=formboxes>
      <option value=0 $userlistno>Show to noone
      <option value=1 $userlistall>Show to everyone 
      <option value=2 $userlistreg>Show only to registered users 
    </select>

    <p {$new['catsonly']}>
    What type of main page do you want to use:<br>
    <select name="catsonly" class=formboxes>
      <option value=0 $nocatsonly>List all categories and forums
      <option value=1 $catsonly>List categories only
    </select>

    <p {$new['allowpolls']}>
    Who should be allowed to place polls in their posts:<br>
    <select name="allowpolls" class=formboxes>
      <option value=0 $nopollsall>Admins and moderators only
      <option value=1 $pollsall>Everyone 
    </select>

    <p {$new['whovote']}>
    Who should be allowed to vote on polls:<br>
    <select name="whovote" class=formboxes>
      <option value=0 $noallvote>Registered users only
      <option value=1 $allvote>Everyone
    </select>

    <p {$new['edittime']}>
    How long after a post has been made can it be edited by the poster? (This is in hours.  For a half hour use .5):<br>
    <input type=text width=50 name="edittime" size=5 value="${config['edittime']}" class=formboxes>

    <p {$new['checkage']}>
    Check the user's age before creating an account? (for <a target="_blank" href="http://www.ftc.gov/bcp/conline/edcams/kidzprivacy/biz.htm">Children's Online Privacy Protection Act</a>):<br>
    <select name="checkage" class=formboxes>
      <option value=0 $nocheckage>No
      <option value=1 $checkage>Yes
    </select>

    <p {$new['under13']}>
    If you are checking the user's age and they are under 13 can they register with the parent consent form?
    <select name="under13" class="formboxes">
      <option value=0 $nounder13>No
      <option value=1 $under13>Yes
    </select>

    <p {$new['specialc']}>
    Allow special characters in usernames:<br>
    <select name="specialc" class=formboxes>
      <option value=0 $nospecialc>No
      <option value=1 $specialc>Yes
    </select>

    <p {$new['modedit']}>
    Allow moderators to edit/delete regular users:<br>
    <select name="modedit" class=formboxes>
      <option value=0 $nomodedit>No, only admins
      <option value=1 $modedit>Yes
    </select>

    <p {$new['anonnames']}>
    Allow users that are not logged in to choose an unregistered username for their post:<br>
    <select name="anonnames" class="formboxes">
      <option value=0 $noanonnames>No, show them as "Anonymous"
      <option value=1 $anonnames>Yes
    </select>

    <p {$new['language']}>
    Default language:<br>
    <select name="language" class=formboxes>
      <option $big5>big5
      <option $chinese>chinese
      <option $danish>danish
      <option $dutch>dutch
      <option $english>english
      <option $french>french
      <option $german>german
      <option $hungarian>hungarian
      <option $italian>italian
      <option $polish>polish
      <option $portuguese>portuguese
      <option $romanian>romanian
      <option $russian>russian
      <option $spanish>spanish
      <option $swedish>swedish
    </select>

    <p {$new['censored']}>
    Censored replacement word.  If you have anything in your filters/badwords file, then any words in a new post that match will be replaced by this word.  Set this to blank if you do not want to censor words.<br>
    <input type="text" width=50 name="censored" value="${config['censored']}" class=formboxes>
    
    <p {$new['subjectlength']}>
    Maximum length for subjects:<br>
    <input type="text" name="subjectlength" value="${config['subjectlength']}" class=formboxes size=5>

    <p {$new['Sig_length']}>
    Maximum length for signatures:<br>
    <input type="text" name="Sig_length" value="${config['Sig_length']}" class=formboxes size=5>
  
    <p {$new['cookieexp']}>
    When should persistent cookies expire (number of seconds from time the cookie is set):<br>
    <input type=text name="cookieexp" value="${config['cookieexp']}" class=formboxes>

    <p {$new['adjusttime']}>
    Adjust the base time in hours (...,2,1,0,-1,-2,...):<br>
    <input type=text name="adjusttime" value="${config['adjusttime']}" class=formboxes size=5>

    <p {$new['showip']}>
    Who should the IP address of the poster be displayed to:<br>
    <select name="showip" class=formboxes>
      <option value=0 $ipnone>Show to noone
      <option value=1 $ipall>Show to everyone
      <option value=2 $ipmodadmin>Show to moderators and admins only
      <option value=3 $ipadmin>Show to admins only
    </select>

    <p {$new['subscriptions']}>
    Allow subscriptions to forum. (If this is allowed you will need to setup something like a cron task to run the cron/subscriptions.php script)<br>
    <select name="subscriptions" class=formboxes>
      <option value=0 $nosubscriptions>Do not allow subscriptions
      <option value=1 $subscriptions>Allow subscriptions
    </select>   

    <p {$new['private']}>
    Allow users to send private messages? (Users will still receive the welcome message and private messages used for admin purposes):<br>
    <select name="private" class="formboxes">
      <option value=0 $noprivate>No, Users cannot send private messages
      <option value=1 $private>Yes, Users can send private messages
    </select>

    <p {$new['allowimages']}>
    Allow the [image] markup tag to be used in forums:<br>
    <select name="allowimages" class="formboxes">
      <option value=0 $noallowimages>No, Do not allow the [image] markup tag
      <option value=1 $allowimages>Yes, Allow the [image] markup tag
    </select>

    <p {$new['avatars']}>
    If allowing pictures you can also let users upload these pictures to your server.  By specifying a path this will allow users to upload .gif, .jpg or .png files to that directory.  You must make the directory world_writeable as well.  If no path specified, then users will need to provide a url to their picture.
    <br />
    <input type="text" name="avatars" size="50" class=formboxes value="{$config['avatars']}">

    <span class="small">(<a href="javascript:void(0);" onClick="javascript:window.open('{$config['phpurl']}/admin/testdirectory.php?dirname=' + configedit.avatars.value,'_blank','toolbar=no, menubar=no,location=no,directories=no,status=no,width=400,height=300,top=200,left=200');">Click to test directory</a>)</span>

    <p {$new['avatarurl']}>
    If a path was specified above, we need to know the full url to that directory. (Paritial URL will not work because the image size can't be grabbed) 
    <br />
    <input type="text" name="avatarurl" size="50" class=formboxes value="{$config['avatarurl']}">

    <p {$new['avatarsize']}>
    If allowing users to upload pictures, enter the maximum size in bytes.
    <br />
    <input type="text" name='avatarsize' class=formboxes value="{$config['avatarsize']}">

    <p {$new['files']}>
    Allow file attachments.  If you want to allow file attachments, provide a full path to the directory to hold the files.  This directory must be writeable by everyone.  If you do not want to allow file attachments, leave this blank:<br>
    <input type="text" name="files" value="${config['files']}" class="formboxes" size="50">

    <span class=small>(<a href="javascript:void(0);" onClick="javascript:window.open('{$config['phpurl']}/admin/testdirectory.php?dirname=' + configedit.files.value,'_blank','toolbar=no, menubar=no,location=no,directories=no,status=no,width=400,height=300,top=200,left=200');">Click to test directory</a>)</span>

    <p {$new['fileurl']}>
    Also supply the url to the file attachment directory if you are allowing file attachments:<br>
    <input type="text" name="fileurl" value="${config['fileurl']}" class="formboxes" size="50">

    <p {$new['filehandler']}>
    If allowing file uploads you must specify which file types you want to allow (separate file types with a comma):<br>
    <input type="text" name="filetypes" value="{$config['allowfiles']}" class="formboxes" size="50">

    <p {$new['filesize']}> 
    Maximum file upload size (in bytes):<br>
    <input type="text" name="filesize" value="${config['filesize']}" class=formboxes>

    <p {$new['userpass']}>
    Allow users to specify a password at the time of registration:<br>
    <select name="userpass" class="formboxes">
      <option value=0 $nouserpass>No, initial passwords are generated and emailed
      <option value=1 $userpass>Yes, users can choose their own initial password
    </select>

    <p {$new['multiuser']}>
    Allow multiple usernames from the same email address:<br>
    <select name="multiuser" class="formboxes">
      <option value=0 $nomultiuser>Only one username per email address
      <option value=1 $multiuser>Unlimited usernames per email address
    </select>

    <p {$new['Preview']}>
    Default to previewing posts and private messages:<br>
    <select name="preview" class="formboxes">
      <option value=0 $NoPreview>Do not preview posts
      <option value=1 $Preview>Preview posts
    </select>
    
    <p {$new['dateslip']}>
    Dateslip - If on, when new replies are made to a thread, the thread will be move to the top of the postlist.  If off, threads will stay in their original position:<br>
    <select name="dateslip" class="formboxes">
      <option value=0 $nodateslip>Turn the dateslip feature off
      <option value=1 $dateslip>Turn the dateslip feature on
    </select>

    <p>
    Extra fields for users to fill out in their profiles.  If left blank, these will not be used:<br>
    <input type="text" name="extra2" size="50" class="formboxes" value="${config['extra2']}"><br>
    <input type="text" name="extra3" size="50" class="formboxes" value="${config['extra3']}"><br>
    <input type="text" name="extra4" size="50" class="formboxes" value="${config['extra4']}"><br>
    <input type="text" name="extra5" size="50" class="formboxes" value="${config['extra5']}"><br>
 
    <p {$new['ICQ_Status']}>
    Use the ICQ status indicator.  This may need to be turned off depending on the size of your database.  Check the <a href="http://www.icq.com/legal/indicator.html">ICQ license agreement</a>:<br>
    <select name="ICQ_Status" class="formboxes">
      <option value=0 $NoICQ_Status>No, Do not use the ICQ status indicator
      <option value=1 $ICQ_Status>Yes, use the ICQ status indicator
    </select>
 
    <p>
    The following variables are unknown to the base UBB.threads package, but might be used by installed hacks or modifications to the program:<br>
    <textarea name="configextras" cols=60 rows=5 class=formboxes>
EOF;

// ----------------------------------------------------------------------
// Now we need to see if there are any extra key/value pairs in this hash 
// that aren't distributed with the program
   $extras;
   while (list($key,$value) = each($config)) {
      if (!ereg("-$key-",$knownkeys)) {
         $extras .= "\$config['$key'] =	\"$config[$key]\";\n";
      }
   }

   $extras = chop ($extras);

   echo "$extras</textarea>
      <br><br>
       <input type=submit value=\"Update config.inc.php\" class=buttons>
      </form>
   ";

   $html -> close_table();
   $html -> send_admin_footer();


?>
