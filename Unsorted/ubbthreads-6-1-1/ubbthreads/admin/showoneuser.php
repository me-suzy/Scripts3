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

// Require the library
   require ("../main.inc.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Number,U_TextCols,U_TextRows");
   $html = new html;

// ---------------------------------
// Make sure they are should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
  } 

// -----------------------------------------
// Get the current profile for this username
   $Username_q = addslashes($User);
   $query = " 
    SELECT U_LoginName,U_Password,U_Email,U_Fakeemail,U_Name,U_Signature,U_Homepage,U_Occupation,U_Hobbies,U_Location,U_Bio,U_TextCols,U_TextRows,U_Extra1,U_Extra2,U_Extra3,U_Extra4,U_Extra5,U_Picture,U_Visible,U_AcceptPriv,U_Sort,U_Display,U_View,U_PostsPer,U_EReplies,U_Notify,U_Status,U_Number,U_Title,U_Color,U_Groups,U_OnlineFormat,U_StyleSheet,U_TimeOffset,U_StartPage,U_ActiveThread,U_ShowSigs,U_AdminEmails,U_EmailFormat
    FROM  {$config['tbprefix']}Users
    WHERE U_Username = '$Username_q'
   "; 
   $sth = $dbh -> do_query($query);


// --------------------------
// Assign the retrieved data
   list($LoginName,$Password,$Email,$Fakeemail,$Name,$Signature,$Homepage,$Occupation,$Hobbies,$Location,$Bio,$TextCols,$TextRows,$Extra1,$Extra2,$Extra3,$Extra4,$Extra5,$Picture,$Visible,$AcceptPriv,$Sort,$Display,$View,$PostsPer,$EReplies,$Notify,$Status,$Number,$Title,$Color,$Groups,$OnlineFormat,$StyleSheet,$TimeOffset,$startpage,$activethreads,$sigview,$adminemail,$emailformat) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

// ------------------------------------------------------------------------
// If this is the very first admin, then they cannot view or edit this user
   if ( ($user['U_Number'] != 2) && ($Number == '2') ){
      $html -> not_right("You cannot view or edit the first Administrator",$Cat);
   }  

// -------------------------------------------------
// If there is no color, then it get's set to Normal
   if (!$Color) {
      $Color = "Normal";
   }

// ------------------------------------------------------------------------
// If this user is a moderator we have to make sure they are only looking at
// someone with User status
   if ( ($user['U_Status'] == 'Moderator') && ($Status != 'User') ) {
      $html -> not_right("As a moderator you cannot view the profiles of other moderators or Admins.",$Cat);
   }  


// -----------------------------------------------
// Find out if they already have a sort preference
   $DS = '';
   $AS = '';
   $DP = '';
   $AP = '';
   $DD = '';
   $AD = '';
   if($Sort == 1) { $DS = "selected"; } 
   if($Sort == 2) { $AS = "selected"; }
   if($Sort == 3) { $DP = "selected"; } 
   if($Sort == 4) { $AP = "selected"; }
   if($Sort == 5) { $DD = "selected"; }
   if($Sort == 6) { $AD = "selected"; }

// --------------------------------------------------
// Find out if they already have a display preference
   if($Display == "flat")     { $flat = "selected"; };
   if($Display == "threaded") { $threaded = "selected"; }


// ---------------------------------------
// Find out if they have a view preference
   if($View == "collapsed") { $collapsed = "selected"; }
   if($View == "expanded")  { $expanded  = "selected"; }

// ---------------------------------
// Set the default for Email replies
   if($EReplies == "Off") { 
      $noereplies = "checked"; 
   }
   else {
      $ereplies = "checked";
   }


// ------------------------------------------------
// Set the default for private message notification
   if ($Notify == "On") {
      $donotify = "checked";
   }
   else {
      $nonotify = "checked";
   }

// -----------------------------------------
// Set the default for TextCols and TextRows
   if (!$TextCols) { $TextCols = $theme['TextCols']; }
   if (!$TextRows) { $TextRows = $theme['TextRows']; }

// ----------------------------
// Set the default for visibile
   if ($Visible == "yes") {
      $visibleyes = "checked";
   }
   else {
      $visibleno = "checked";
   }

// ----------------------------------------------
// Set the default for accepting private messages
   if ($AcceptPriv == "yes") {
      $acceptyes = "checked";
   }
   else {
      $acceptno = "checked";
   }

// ------------------------
// Convert returns to <br>s
   $Signature = str_replace("<br />","\n",$Signature);
   $Bio = str_replace("<br />","\n",$Bio);

   $Signature = $html -> undo_markup($Signature);
   $Bio = $html -> undo_markup($Bio);

// ------------------------------------------------
// Check if we are showing aux info on who's online
	if ($OnlineFormat == "no") {
		$OnlineFormatno = "checked=\"checked\"";
	}
	else {
		$OnlineFormatyes = "checked=\"checked\"";
	}

// ------------------
// Default stylesheet
	if (!$StyleSheet) {
		$StyleSheet = $theme['stylesheet'];
	}

// -------------------
// Default time offset
	if (!$TimeOffset) { $TimeOffset = "0"; }

// ---------------------------
// Where do they want to start
	if ($startpage == "mi") {
		$mi = "selected=\"selected\"";
	}
	else {
		$cp = "selected=\"selected\"";
	}

// ------------------------------------------------
// Figure out their default aged threads to display
   if (!$activethreads) {
      $activethreads = $config['activethreads'];
   }
  if ($activethreads == "1") {
    $d1 = "selected=\"selected\"";
  } elseif ($activethreads == "2") {
    $d2 = "selected=\"selected\"";
  } elseif ($activethreads == "7") {
    $w1 = "selected=\"selected\"";
  } elseif ($activethreads == "14") {
    $w2 = "selected=\"selected\"";
  } elseif ($activethreads == "21") {
    $w3 = "selected=\"selected\"";
  } elseif ($activethreads == "31") {
    $m1 = "selected=\"selected\"";
  } elseif ($activethreads == "93") {
    $m3 = "selected=\"selected\"";
  } elseif ($activethreads == "186") {
    $m6 = "selected=\"selected\"";
  } elseif ($activethreads == "365") {
    $y1 = "selected=\"selected\"";
  } elseif ($activethreads == "999") {
    $default = "selected=\"selected\"";
  } else {
    $allofthem = "selected=\"selected\"";
  }

// ----------------------------
// Set the default for sigview
   if ($sigview == "no") {
      $nosigview = "checked=\"checked\"";
   }
   else {
      $yessigview = "checked=\"checked\"";
   }

// --------------------------------
// Set the default for admin emails
   if ($adminemail == "Off") {
      $optout = "checked=\"checked\"";
   }
   else {
      $optin = "checked=\"checked\"";
   }

// ------------------------------------
// Receive emails in plain text or HTML
   if (!emailformat == "HTML") {
      $htmlformat = "checked=\"checked\"";
   }
   else {
      $plaintext = "checked=\"checked\"";
   }

// ---------------------------
// Change the quotes to &quot;
   $Password = str_replace("\"","&quot;",$Password);
   $Password = str_replace("<","&lt;",$Password);
   $Email = str_replace("\"","&quot;",$Email);
   $Email = str_replace("<","&lt;",$Email);
   $Fakeemail = str_replace("\"","&quot;",$Fakeemail);
   $Fakeemail = str_replace("<","&lt;",$Fakeemail);
   $Signature = str_replace("\"","&quot;",$Signature);
   $Signature = str_replace("<","&lt;",$Signature);
   $Homepage = str_replace("\"","&quot;",$Homepage);
   $Homepage = str_replace("<","&lt;",$Homepage);
   $Occupation = str_replace("\"","&quot;",$Occupation);
   $Occupation = str_replace("<","&lt;",$Occupation);
   $Hobbies = str_replace("\"","&quot;",$Hobbies);
   $Hobbies = str_replace("<","&lt;",$Hobbies);
   $Location = str_replace("\"","&quot;",$Location);
   $Location = str_replace("<","&lt;",$Location);
   $Bio = str_replace("\"","&quot;",$Bio);
   $Bio = str_replace("<","&lt;",$Bio);


// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("Profile for $User",$Cat,0,$user);
   $html -> admin_table_header("Edit profile of $User");
   $html -> open_admin_table();

   echo " 
     <p>
     <TR><TD CLASS=\"lighttable\">
     You can edit any of the information below. 
     </p><p>
     <form method = POST action = \"{$config['phpurl']}/admin/dochangeuser.php\">
     <INPUT TYPE=HIDDEN NAME=Cat VALUE=$Cat>
     <input type = hidden name = User value =\"$User\">
     <input type = hidden name = OldPass value = \"$Password\">
  
	  Login Name<br>
	  $LoginName
		<br><br> 
     Password (between 4 and 20 characters)<br>
     <input type = password name=ChosenPassword value = \"$Password\" class=\"formboxes\">
     <br><br>
     Verify Password<br>
     <input type = password name = Verify value = \"$Password\" class=\"formboxes\">
     <br><br>
     Email Address (Not available to other users)<br>
     <input type = text name = Email value = \"$Email\" class=\"formboxes\">
     <br><br>
     Email address displayed on posts<br>
     <input type = text name = Fakeemail value = \"$Fakeemail\" class=\"formboxes\">
     <br><br>
     Full Name<br>
     <input type = text name = Name value = \"$Name\" class=\"formboxes\">
     <br><br>
     User Title<br>
     <input type = text name = Title value = \"$Title\" class=\"formboxes\">
     <br><br>
     Name Color<br>
     <input type = text name = Color class=\"formboxes\" value = \"$Color\">
     <br><br>
     Signature (up to {$config['Sig_length']} characters).<br>
     <textarea name=Signature cols=50 rows=3 wrap=soft class=\"formboxes\">$Signature</textarea>
     <br><br>
     Homepage (up to 150 characters)<br>
     http://<input type=text name=Homepage value = \"$Homepage\" class=\"formboxes\">
     <br><br>
     Occupation<br>
     <input type=text name=Occupation value = \"$Occupation\" class=\"formboxes\">
     <br><br>
     Hobbies (up to 200 characters)<br>
     <input type=text name=Hobbies value = \"$Hobbies\" class=\"formboxes\">
     <br><br>
     Geographic Location (State, Country, etc.)<br>
     <input type=text name=Location value = \"$Location\" class=\"formboxes\">
     <br><br>
     Bio (up to 250 characters)<br>
     <textarea name=Bio cols={$theme['TextCols']} rows={$theme['TextRows']} wrap=soft class=\"formboxes\">$Bio</textarea>
     <br><br>
     Which stylesheet does this user want?<br>
";

// ------------------------
// List out the stylesheets
	if ($StyleSheet == "usedefault") {
		$defselected = "selected=\"selected\"";
	}
	echo "<select name=\"StyleSheet\" class=\"formboxes\">";
	echo "<option value=\"usedefault\" $defselected> Always use default";
   $styles = split(",",$theme['availablestyles']);
   $size = sizeof($styles);
   for ($i=0;$i<$size;$i++) {
       list($style,$desc) = split(":",$styles[$i]);
       $style = trim($style);
       $desc  = trim($desc);
       $extra = "";
       if ($StyleSheet == $style) {
          $extra = "selected=\"selected\"";
       }
       echo  "<option value=\"$style\" $extra>$desc</option>";
   }


echo "
     </select>
     <br><br>
     Time Offset for displayed times. (...,-2,-1,0,1,2,...)<br>
     <input type=\"text\" size=\"3\" maxlength=\"3\" name=\"timeoffset\" class=\"formboxes\" value=\"$TimeOffset\">
     <br><br>

     Start page set to Main Index or \"My Home\"?<br>
     <select name=\"startpage\" class=\"formboxes\">
       <option value=\"cp\" $cp> My Home
       <option value=\"mi\" $mi> Main Index
     </select>
     <br><br>

     Default aged threads to display<br>
     <select name=\"activethreads\" class=\"formboxes\">
     <option value=\"999\" $default>Use default
     <option value=\"1\" $d1>1 day
     <option value=\"2\" $d2>2 days
     <option value=\"7\" $w1>1 week
     <option value=\"14\" $w2>2 weeks
     <option value=\"21\" $w3>3 weeks
     <option value=\"31\" $m1>1 month
     <option value=\"93\" $m3>3 months
     <option value=\"186\" $m6>6 months
     <option value=\"365\" $y1>1 year
     <option value=\"0\" $allofthem>Show all
     </select>

     <br><br>

     Default sort order<br>
     <select name = \"sort_order\" class=\"formboxes\">
       <option value = 1 $DS>Descending Subject</option>
       <option value = 2 $AS>Ascending Subject</option>
       <option value = 3 $DP>Descending Poster</option>
       <option value = 4 $AP>Ascending Poster</option>
       <option value = 5 $DD>Descending Date</option>
       <option value = 6 $AD>Ascending Date</option>
     </select><br><br>
     Default display mode<br>
     <select name = \"display\" class=\"formboxes\">
       <option value = \"threaded\" $threaded>Threaded Mode</option>
       <option value = \"flat\" $flat>Flat Mode</option>
     </select><br><br>
     Default view<br>
     <select name = \"view\" class=\"formboxes\">
       <option value = \"collapsed\" $collapsed>Collapsed Threads</option>
       <option value = \"expanded\"  $expanded>Expanded Threads</option>
     </select><br><br>
     Total parent posts to show per page: (default is {$theme['postsperpage']})<br>
     <input type=text name=PostsPer maxlength = 3 size = 3 value = \"$PostsPer\" class=\"formboxes\"><br><br>

	  Show user's signatures with their posts:<br>
	  <input type=\"radio\" name=\"ShowSigs\" value=\"yes\" $yessigview class=\"formboxes\"> Yes
	  <input type=\"radio\" name=\"ShowSigs\" value=\"no\" $nosigview class=\"formboxes\"> No 
	  <br><br> 
   ";
 
   if ($config['extra1']) {
      echo "ICQ Number<br>";
      echo "<input type=text name=ICQ value=\"$Extra1\" class=\"formboxes\">";
      echo "<p>";
   }
   if ($config['extra2']) {
      echo "{$config['extra2']}<br>";
      echo "<input type=text name=Extra2 value=\"$Extra2\" class=\"formboxes\">";
      echo "<p>";
   }
   if ($config['extra3']) {
      echo "{$config['extra3']}<br>";
      echo "<input type=text name=Extra3 value=\"$Extra3\" class=\"formboxes\">";
      echo "<p>";
   }
   if ($config['extra4']) {
      echo "{$config['extra4']}<br>";
      echo "<input type=text name=Extra4 value=\"$Extra4\" class=\"formboxes\">";
      echo "<p>";
   }
   if ($config['extra5']) {
      echo "{$config['extra5']}<br>";
      echo "<input type=text name=Extra5 value=\"$Extra5\" class=\"formboxes\">";
      echo "<p>";
   }
   if ($theme['PictureView'] == "on") {
      if (!$Picture) { $Picture ="http://"; }
      echo "Picture (Entire URL)<br>";
      echo "<input type=text name=Picture value=\"$Picture\" size=50 class=\"formboxes\">";
      echo "<p>";
   }
   else {
      echo "<input type=hidden name=Picture value=\"$Picture\">";
   }

   echo " 
      Visible in the \"Who's Online\" Screen<br>
      <input type=radio name = Visible value=\"yes\" $visibleyes class=\"formboxes\"> Yes
      <input type=radio name = Visible value=\"no\" $visibleno class=\"formboxes\"> No 
      <p>
		Show what forum/post this user is looking at in who's online<br>
		<input type=\"radio\" name=\"OnlineFormat\" value=\"yes\" $OnlineFormatyes class=\"formboxes\"> Yes
		<input type=\"radio\" name=\"OnlineFormat\" value=\"no\" $OnlineFormatno class=\"formboxes\"> No 
		<p>
		
   ";
   if ($config['private'] == "1") {
      echo " 
         Accept Private Messages<br>
         <input type=radio name =AcceptPriv value=\"yes\" $acceptyes class=\"formboxes\"> Yes 

        <input type=radio name = AcceptPriv value=\"no\" $acceptno class=\"formboxes\"> No 
        <p>
      "; 
   }


   echo " 

      Email notifications for private messages?<br>
        <input type=radio name=Notify value=\"Off\" $nonotify class=\"formboxes\"> No 
        <input type=radio name=Notify value=\"On\" $donotify class=\"formboxes\"> Yes
    
      <br><br>
      Email all replies to posts made?<br>
        <input type=radio name=EReplies value=\"Off\" $noereplies class=\"formboxes\"> No
        <input type=radio name=EReplies value=\"On\" $ereplies class=\"formboxes\"> Yes
      <br><br>

		Receive admin emails when the mass mailer is used:<br>
		<input type=\"radio\" name=\"adminemails\" value=\"Off\" $optout class=\"formboxes\"> No
		<input type=\"radio\" name=\"adminemails\" value=\"On\" $optin class=\"formboxes\"> Yes
		<br><br>

		Receive emails in HTML format or plain text<br>
		<input type=\"radio\" name=\"emailformat\" value=\"HTML\" $htmlformat class=\"formboxes\"> HTML
		<input type=\"radio\" name=\"emailformat\" value=\"plaintext\" $plaintext class=\"formboxes\"> Plain Text
 
		<br><br>
   ";

// ------------------------------------------------------------------
// if we are allowing subscribes then we need to put that on the page
   if ($config['subscriptions']) {

   // ------------------------------------------------------
   // We need to know what boards this user can subscribe to
      $Grouparray = split("-",$Groups);
      $gsize = sizeof($Grouparray);
      $groupquery = "(";
      $g = 0;
      for ($i=0; $i<=$gsize;$i++) {
         if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
         $g++;
         if ($g > 1) {
            $groupquery .= " OR ";
         }
         $groupquery .= "Bo_Read_Perm LIKE '%-$Grouparray[$i]-%'";
      }
      $groupquery .= ")"; 

   // -----------------------------------------
   // Grab the keyword and titles of all boards
      $query = " 
        SELECT Bo_Title,Bo_Keyword,Bo_Cat
        FROM  {$config['tbprefix']}Boards
        WHERE $groupquery
        ORDER BY Bo_Title
      ";
      $sth = $dbh -> do_query($query);
      echo "Subscription status for the forums this user has access to.<br>";
      echo "(S - Subscribe ; U - Unsubscribe)<p>";

      echo "<table border=\"0\" cellspacing=\"0\"><tr class=\"tdheader\">";
      echo "<td>Board</td><td align=\"center\">S</td><td align=\"center\">U</td><td class=\"tdheader\">&nbsp;&nbsp;</td>";
      echo "<td>Board</td><td align=\"center\">S</td><td align=\"center\">U</td></tr>";
      $cycle = 0;
      $color = "darktable";
      $rows = 0;
      while (list($Title,$Keyword) = $dbh -> fetch_array($sth)) {
         $rows++;
         if ($cycle == 0) {
            echo "<tr class=\"$color\">";
         } 
      // ------------------------------------
      // check if they are already subscribed
         $Keyword_q  = addslashes($Keyword);

         $query = " 
           SELECT S_Username
           FROM  {$config['tbprefix']}Subscribe
           WHERE S_Username = '$Username_q'
           AND   S_Board = '$Keyword_q'
         "; 
         $sti = $dbh -> do_query($query);
         list ($check) = $dbh -> fetch_array($sti);
         $dbh -> finish_sth($sti);
         if ($check) {
            $Sub = "checked";
            $Nosub = "";
         }
         else {
            $Nosub = "checked";
            $Sub = "";
         } 

         echo "<td>$Title</td>";
         $Word = "$Keyword"."--SUB--YES";
         echo "<td><input type=radio name=\"$rows\" value = \"$Word\" $Sub class=\"formboxes\"></td>";
         $Word = "$Keyword"."--SUB--NO";
         echo "<td><input type=radio name=\"$rows\" value = \"$Word\" $Nosub class=\"formboxes\"></td>";
         $cycle++;
         if ($cycle == 1) {
            echo "<td class=\"tdheader\"></td>";
         }
         if ($cycle == 2) {
            echo "</tr>";
            $cycle = 0;
            if ($color == "darktable") {
               $color = "lighttable";
            }
            else {
               $color = "darktable";
            }
         } 
      }
      $dbh -> finish_sth($sth);
      if ($cycle == 1) {
         echo "<td></td><td></td><td></td>";
      }
      echo "</table>";
      echo "<br>";
      echo "<input type=hidden name = Totalsubs value = $rows>"; 
 
   }

   echo"<input type=submit name=option value=\"Submit\" class=\"buttons\">";
   echo"</FORM>";

   $html -> close_table();
   $html -> send_admin_footer();
