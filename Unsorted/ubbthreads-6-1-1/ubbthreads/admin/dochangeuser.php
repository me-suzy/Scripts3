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
   $user = $userob -> authenticate("U_Number");
   $html = new html;

// ---------------------------------
// Make sure they are should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator o
r moderator to access this.",$Cat);
  }                            

// ------------------------------------------------
// If color is Normal, then it get's set to nothing
   if ( $Color == "Normal") {
      $Color = "";
   }


// -----------------------------------
// Make sure this isn't the first user
   $User_q = addslashes($User);
   $query = " 
     SELECT U_Number
     FROM  {$config['tbprefix']}Users
     WHERE U_Username = '$User_q'
   ";
   $sth = $dbh -> do_query($query);
   list($Number) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);

   if (($user['U_Number'] != 2) && ($Number == '2')) {
      $html -> not_right("You cannot view or edit the first Administrator",$Cat);
   }


// --------------------------
// Find out what we are doing
   if ($option == "delete") {
      $html -> send_header ("Please confirm that you want to delete",$Cat,0,$user);
      $html -> admin_table_header("Please confirm that you want to delete $User");
      echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD class=\"cleartable\"><span class=\"onbody\">";  
      echo "If you are sure you want to delete $User, click the link below.<br><br>";
      $Userenc = rawurlencode($User);
      echo "<a href=\"{$config['phpurl']}/admin/dodeleteuser.php?Cat=$Cat&User=$Userenc\">Yes, I want to delete $User.</a>";
      echo "</span></TD></TR></TABLE>";
      $html -> send_admin_footer(); 
      exit();
   }

// Get the current profile for this username
   $Username_q = addslashes($User);
   $query = " 
     SELECT U_Status
     FROM  {$config['tbprefix']}Users
     WHERE U_Username = '$Username_q'
   "; 
   $sth = $dbh -> do_query($query);


// --------------------------
// Assign the retrieved data
   list($Status) = $dbh -> fetch_array($sth); 
   $dbh -> finish_sth($sth);

// ------------------------------------------------------------------------
// If this user is a moderator we have to make sure they are only looking at
// someone with User status
   if ( ($user['U_Status'] == 'Moderator') && ($Status != 'User') ) {
      $html -> not_right("As a moderator you cannot view the profiles of other moderators or Admins.",$Cat);
   }  

// --------------------------------------------------------
// If the password isn't the proper length we can't proceed
   if ($ChosenPassword != $OldPass) {
      if((strlen($ChosenPassword)<4) || (strlen($ChosenPassword)>30)){
         $html -> not_right("Password needs to be between 4 and 20 characters.",$Cat);
      }
   }

// -----------------------------------------------------
// If homepage is greater than 150 then we can't proceed
   if ( strlen($Homepage) > 150 ) {
      $html -> not_right("The homepage entry is too long.",$Cat);
   }

// --------------------------------------------------------
// If Occupation is greater than 150 then we can't proceed
   if ( strlen($Occupation) > 150 ) {
      $html -> not_right("The occupation entry is too long.",$Cat);
   }


// ----------------------------------------------------
// If hobbies is greater than 200 then we can't proceed
   if ( strlen($Hobbies) > 200 ) {
      $html -> not_right("The hobbies entry is too long.",$Cat);
   }


// -----------------------------------------------------
// If location is greater than 200 then we can't proceed
   if ( strlen($Location) > 200 ) {
      $html -> not_right("The location entry is too long.",$Cat);
   }


// -----------------------------------------------------------
// If the 2 given password do not match then we can't proceed
   if($ChosenPassword != $Verify){
      $html -> not_right("Passwords do not match.",$Cat);
   }

// --------------------------------------------------------------
// If Posts per is less than 1 or greater than 999, can't proceed
   if( ($PostsPer < 1) || ($PostsPer > 99) ) {
      $html -> not_right("Posts per page value cannot be used.",$Cat);
   }

// ------------------------------
// Convert the Bio and Sig fields
   $Bio = str_replace("<","&lt;",$Bio);
   $Bio = str_replace("\n","<br />",$Bio);
   $Bio = $html -> do_markup($Bio);
   $Signature = str_replace("<","&lt;",$Signature);
   $Signature = str_replace(">","&gt;",$Signature);
   $Signature = str_replace("&lt;br&gt;","<br />",$Signature);
   $Signature = $html -> do_markup($Signature);


// -----------------------------------------------
// If this is a new password we need to encrypt it
   if ($ChosenPassword != $OldPass) {
      $ChosenPassword = md5($ChosenPassword);
   }   

// -----------------------
// Format the query words
   $Password_q   = addslashes($ChosenPassword);
   $Email_q      = addslashes($Email);
   $Fakeemail_q  = addslashes($Fakeemail);
   $Name_q       = addslashes($Name);
   $Title_q      = addslashes($Title);
   $Signature_q  = addslashes($Signature);
   $Homepage_q   = addslashes($Homepage);
   $Occupation_q = addslashes($Occupation);
   $Hobbies_q    = addslashes($Hobbies);
   $Location_q   = addslashes($Location);
   $Bio_q        = addslashes($Bio);
   $Username_q   = addslashes($User);
   $Display_q    = addslashes($display);
   $View_q       = addslashes($view);
   $EReplies_q   = addslashes($EReplies);
   $Notify_q     = addslashes($Notify);
   $Color_q      = addslashes($Color);
   $Extra1_q     = addslashes($ICQ);
   $Extra2_q     = addslashes($Extra2);
   $Extra3_q     = addslashes($Extra3);
   $Extra4_q     = addslashes($Extra4);
   $Extra5_q     = addslashes($Extra5);
   $Picture_q    = addslashes($Picture);
   $Visible_q    = addslashes($Visible);
   $AcceptPriv_q = addslashes($AcceptPriv);

// --------------------------
// Update the User's profile
   $query = "
    UPDATE {$config['tbprefix']}Users
    SET U_Password   = '$Password_q',
    U_Email          = '$Email_q',
    U_Fakeemail      = '$Fakeemail_q',
    U_Name           = '$Name_q',
    U_Signature      = '$Signature_q',
    U_Homepage       = '$Homepage_q',
    U_Occupation     = '$Occupation_q',
    U_Hobbies        = '$Hobbies_q',
    U_Location       = '$Location_q',
    U_Bio            = '$Bio_q',
    U_Sort           = '$sort_order',
    U_Display        = '$Display_q',
    U_View           = '$View_q',
    U_PostsPer       = '$PostsPer',
    U_EReplies       = '$EReplies_q',
    U_Notify         = '$Notify_q',
    U_Title          = '$Title_q',
    U_Color          = '$Color_q',
    U_Extra1         = '$Extra1_q',
    U_Extra2         = '$Extra2_q',
    U_Extra3         = '$Extra3_q',
    U_Extra4         = '$Extra4_q',
    U_Extra5         = '$Extra5_q',
    U_Picture        = '$Picture_q',
    U_Visible        = '$Visible_q',
    U_AcceptPriv     = '$AcceptPriv_q',
	 U_OnlineFormat   = '$OnlineFormat', 
	 U_TimeOffset     = '$timeoffset',
    U_StartPage      = '$startpage',
	 U_ShowSigs       = '$ShowSigs',
	 U_ActiveThread   = '$activethreads',
    U_StyleSheet     = '$StyleSheet',
	 U_AdminEmails    = '$adminemails',
    U_EmailFormat    = '$emailformat'
    WHERE U_Username = '$Username_q'
   "; 
   $dbh -> do_query($query);

// -----------------------------------------------------------------
// Now lets see if they are subscribing or unsubscribing to anything
   for ($i=1; $i<=$Totalsubs; $i++){

      $Board = $HTTP_POST_VARS[$i];
      list($Board,$Sub) = split("--SUB--",$Board);

   // --------------------------------------------------------------
   // If the board is unmarked then we need to unsubscribe this user
      if ($Sub == "NO") {
         $Board_q    = addslashes($Board);
         $Username_q = addslashes($User);
         $query = " 
            DELETE FROM {$config['tbprefix']}Subscribe
            WHERE S_Username = '$Username_q' AND S_Board = '$Board_q'
         ";
         $dbh -> do_query($query);
      }

   // ---------------------------------------------------------
   // If they board is marked then we need to subscribe the user
   // but we need to make sure they aren't already subscribed
      else {

         $Board_q    = addslashes($Board);
         $Username_q = addslashes($User);
         $query = "
            SELECT S_Username 
            FROM {$config['tbprefix']}Subscribe
            WHERE S_Username = '$Username_q' AND S_Board = '$Board_q'
         "; 
         $sth = $dbh -> do_query($query);
         list($check) = $dbh -> fetch_array($sth); 
         $dbh -> finish_sth($sth);

      // ---------------------------------------------------------
      // They were not already subscribed so we now subscribe them
         if (!$check) {

         // ----------------------------------------
         // Find out what the current post number is
            $query = " 
               SELECT B_Number
               FROM   {$config['tbprefix']}Posts
               WHERE  B_Board = '$Board_q'
               ORDER BY B_Number DESC
            "; 
            $sth = $dbh -> do_query($query);
            list($Last) = $dbh -> fetch_array($sth); 
            $dbh -> finish_sth($sth);

         // ---------------------
         // Now we subscribe them
            $query = " 
               INSERT INTO {$config['tbprefix']}Subscribe
               (S_Username,S_Board,S_Last)
               VALUES ('$Username_q','$Board_q','$Last')
            "; 
            $dbh -> do_query($query);
         }
      }
   }

// ------------------------
// Send them a page 
   $html -> send_header ("Profile changed",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/admin/main.php?Cat=$Cat\">",$user);
   $html -> admin_table_header("Profile changed");
   echo "<TABLE BORDER=0 WIDTH=\"95%\" ALIGN=\"center\"><TR><TD>";  
   echo "You have successfully changed the profile.  You will now be returned to the main admin page.";
   echo "</TD></TR></TABLE>";
   $html -> send_admin_footer();
