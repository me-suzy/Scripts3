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
   require ("main.inc.php");
   require ("languages/${$config['cookieprefix']."w3t_language"}/ubbthreads.php");

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate();  

// -----------------------------------------------------------
// Let's make sure this user isn't already in our address book
   $Owner_q = addslashes($user['U_Username']);
   $Member_q = addslashes($User);
   $query = " 
     SELECT Add_Owner
     FROM   {$config['tbprefix']}AddressBook
     WHERE  Add_Owner  = '$Owner_q'
     AND    Add_Member = '$Member_q'
   ";
   $sth = $dbh -> do_query($query);
   list($check) = $dbh -> fetch_array($sth);
   $dbh -> finish_sth($sth);
   if (!$check) {	

   // ------------------------------------------------------
   // Insert the details into the database
      $query = " 
        INSERT INTO {$config['tbprefix']}AddressBook
        (Add_Owner, Add_Member)
        VALUES ('$Owner_q', '$Member_q')
      "; 
      $dbh -> do_query($query);
   }

// Return to the profile
   header("Location: {$config['phpurl']}/showprofile.php?Cat=$Cat&User=$User&Board=$Board&Number=$Number&what=$what&page=$page&view=$view&sb=$sb&o=$o&PHPSESSID=$PHPSESSID");


