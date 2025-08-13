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
   $user = $userob -> authenticate();
   $html = new html;

// -----------------------------
// Make sure they should be here
   if ($user['U_Status'] != 'Administrator'){
      $html -> not_right ("You must be logged in, and be a valid administrator to access this.",$Cat);
   }

// -------------------------
// Grab all unapproved users
   $query = "
      SELECT U_Username,U_Email,U_RegIP,U_Number
      FROM   {$config['tbprefix']}Users
      WHERE  U_Approved = 'no'
   ";
   $sth = $dbh -> do_query($query);

   $html -> send_header ("Approve/Delete New Registrations",$Cat,0,$user);
   $html -> admin_table_header("Approve/Delete New Registrations.  If deleting a user and no reason specified then an email will not be sent to that user.  If you received an email about a new user needing approval but don't find them here then they were already approved/deleted by another admin.");
   $html -> open_admin_table();

   echo "
    <FORM METHOD = POST action =\"{$config['phpurl']}/admin/doapproveusers.php\">
    <tr class=darktable>
    <td>
       Username
    </td>
    <td>
       Email
    </td>
    <td>
       IP
    </td>
    <td>
       Approve
    </td>
    <td>
       Delete
	 </td>
	 <td>
		 No Action
    </td>
    </tr>
   ";
   $i=0;
   while(list($name,$email,$ip,$number) = $dbh -> fetch_array($sth)) {
      echo "
         <tr class=lighttable>
           <td>
             $name
           </td>
           <td>
             <a href=\"mailto:$email\">$email</a>
           </td>
           <td>
             $ip
           </td>
           <td>
             <input type=radio class=formboxes name=\"newuser$i\" value=\"approve-$number\">
           </td>
           <td>
             <input type=radio class=formboxes name=\"newuser$i\" value=\"delete-$number\">
             Reason: <input type=text class=formboxes name=\"reason$i\">
           </td>
			  <td>
				 <input type=radio class=formboxes name=\"noaction\" value=\"noaction\" checked>
				</td>
           </tr>
      ";
      $i++;
   }
   echo "
      <tr class=darktable>
        <td colspan=6>
          <input type=hidden name=\"totalusers\" value=$i> 
          <input type=submit value=\"Approve/Delete registrations\" class=buttons>
   ";
   $html -> close_table();
   echo"</form>";
   $html -> send_admin_footer();

?>
