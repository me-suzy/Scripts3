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

// -----------------------------
// Make sure they should be here
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ----------------------------------------------------------------------
// If they are assigning a moderator to a board, we redirect them to that
// script
   if ($assignmod) {
      $Euser = rawurlencode($User);
      header("Location: {$config['phpurl']}/admin/doassignmod.php?Cat=$Cat&User=$Euser&assignmod=$assignmod");
      exit;
   }

// -----------------------------------------
// Get the current profile for this username
   $Username_q = addslashes($User);
   $query = "
    SELECT U_Status,U_Number
    FROM  {$config['tbprefix']}Users
    WHERE U_Username = '$Username_q'
   ";
   $sth = $dbh -> do_query($query);

// --------------------------
// Assign the retrieved data
   list($Status,$Number) = $dbh -> fetch_array($sth);

// ------------------------------------------------------------------------
// If this is the very first admin, then they cannot view or edit this user
   if ( ($user['U_Number'] != 2) && ($Number == '2') ){
      $html -> not_right("You cannot view or edit the first Administrator",$Cat);
   }  

// ------------------------------------------------------------------------
// If this user is a moderator we have to make sure they are only looking at
// someone with User status
   if ( ($user['U_Status'] == 'Moderator') && ($Status != 'User') ) {
      $html -> not_right("As a moderator you cannot view the profiles of other moderators or Admins.",$Cat);
   }  


// ---------------------------------------------------------
// Ok, we found the profile, now lets put it all onto a page
   $html -> send_header("Available options",$Cat,0,$user);
   $html -> admin_table_header("Available options for $User");
   $html -> open_admin_table();

   $Euser = rawurlencode($User);
   echo "
      <TR><TD CLASS=\"lighttable\">
      The following options are available for editing.
      </p><p>
   ";

   $html -> open_admin_table();
 
   echo " 
     <tr><td width=30% class=lighttable valign=top> 
     <a href=\"{$config['phpurl']}/admin/showoneuser.php?Cat=$Cat&User=$Euser\">Edit profile</a>
     </td><td width=70% class=darktable>
     Edit this user's general profile
     </td></tr>
   ";

   if ( ($user['U_Status'] == "Administrator") && ($Number != '1') ){
      echo "
         <tr><td class=lighttable valign=top>
           <a href=\"{$config['phpurl']}/admin/dochangeuser.php?Cat=$Cat&User=$Euser&option=delete\">Delete user</a>
         </td><td class=darktable>
           Delete this user from the database
         </td>
         </tr>
      ";
   }

// -------------------------------------------
// Let's see if this user moderates any boards
   $User_q = addslashes($User);
   $query = "
    SELECT Mod_Board
    FROM   {$config['tbprefix']}Moderators
    WHERE  Mod_Username = '$User_q'
   ";
   $sth = $dbh -> do_query($query);
   list($modcheck) = $dbh -> fetch_array($sth);

// -----------------------------------------------------------------------
// If this is an admin and it's not the first admin, let them revoke privs
   if ( ($user['U_Status'] == "Administrator") && ($Status == "Administrator" && $Number != "1") ){
      echo "
        <tr><td class=lighttable valign=top>
          <a href=\"{$config['phpurl']}/admin/dorevokeadmin.php?Cat=$Cat&OldAdmin=$Euser\">Revoke admin privs</a>
        </td><td class=darktable>
          Revoke administration privileges from this user.
      ";
      if ($modcheck) {
         echo "  Since this user is the moderator of one or more forums they will be returned to moderator status.";
      }
      else {
         echo "  This user will be returned to user status.";
      }
      echo " (Immediate action)</td></tr>";

   }
   elseif ( ($Status != "Administrator") && ($user['U_Status'] == "Administrator") ){
      echo "
        <tr><td class=lighttable valign=top>
          <a href=\"{$config['phpurl']}/admin/dograntadmin.php?Cat=$Cat&NewAdmin=$Euser\">Grant admin privs</a>
        </td><td class=darktable>
          Give administration privileges to this user. (Immediate action)
        </td></tr>
      ";
      if ($Status != "Moderator") {
         echo "
           <tr><td class=lighttable valign=top>
            <a href=\"{$config['phpurl']}/admin/dograntmod.php?Cat=$Cat&NewMod=$Euser\">Grant mod privs</a>
           </td><td class=darktable>
            Give moderator privileges to this user. (Immediate action)
           </td></tr>
         ";
      }

   }
        

// --------------------------------------------------------------------------
// If they moderate a forum give a link to remove them from one of the forums
   if ( ($modcheck) && ($user['U_Status'] == "Administrator") ){
      echo "
        <td class=lighttable valign=top>
          <a href=\"{$config['phpurl']}/admin/removemod2.php?Cat=$Cat&Moderator=$Euser\">Remove from moderator list</a>
        </td><td class=darktable>
          This user moderates one or more forums.  Remove them from one or all.
        </td></tr>
      ";
  
// -----------------------------------------------------------------------
// Otherwise they do not moderate a board so they can be demoted to a user
// if they are currently a moderator
   } elseif ( (!$modcheck) && ($Status == "Moderator") ) {
      echo "
        <td class=lighttable valign=top>
          <a href=\"{$config['phpurl']}/admin/dorevokemod.php?Cat=$Cat&Modname=$Euser\">Revoke moderator privs</a>
        </td><td class=darktable>
          Since this user does not actually moderate an individual forum you have the option of revoking moderator privileges from this user.
        </td></tr>
      ";
   }

// --------------------------------------------------
// Give them the option of editing this user's groups
   echo "
    <tr><td class=lighttable valign=top>
    <a href=\"{$config['phpurl']}/admin/showugroup.php?Cat=$Cat&User=$Euser\">Edit groups</a>
    </td><td class=darktable>
    Edit the groups that this user belongs to.
    </td></tr>
   ";

// --------------------------------------------
// Let's find out if this user is banned or not
   $query = "
    SELECT B_Username
    FROM   {$config['tbprefix']}Banned
    WHERE  B_Username = '$User_q'
   ";
   $sth = $dbh -> do_query ($query);
   list($bcheck) = $dbh -> fetch_array($sth);
   if (!$bcheck) {
    echo "
      <tr><td class=lighttable valign=top>
        <a href=\"{$config['phpurl']}/admin/banuser.php?Cat=$Cat&User=$Euser\">Ban this user</a>
      </td><td class=\"darktable\">
        Ban this user from making any new posts or sending private messages.
      </td></tr>
    ";
   } 
   else {
    echo "
      <tr><td class=lighttable valign=top>
        <a href=\"{$config['phpurl']}/admin/dounbanuser.php?Cat=$Cat&Unban=$Euser&option=Unban%20this%20username\">Unban this user</a>
      </td><td class=darktable>
        This user is currently banned.  Use this to unban them so they can post and send private messages.
      </td></tr>
    ";
   }
   echo "
      <tr><td class=lighttable valign=top valign=top>
        <a href=\"{$config['phpurl']}/admin/loginas.php?Cat=$Cat&Who=$Euser\" target=\"_top\">Become this user</a>
      </td><td class=darktable>
        Logs you in as this user so you can see what they are seeing or having problems with.  In order for this to work your cookiepath option in the config file must be an absolute path such as \"/\".

      </td></tr>
   ";
    

   $html -> close_table();

   $html -> close_table();
// ----------------
// send the footer
   $html -> send_admin_footer();

?>
