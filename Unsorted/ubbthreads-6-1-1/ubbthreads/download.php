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

// -----------------------------------------
// require the language file for this script
   require "{$config['path']}/languages/${$config['cookieprefix']."w3t_language"}/download.php";

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_Groups");

// ------------------------------------------------
// Let's grab the board and filename for this post 
	$query = "
		SELECT B_Board,B_File
		FROM   {$config['tbprefix']}Posts
		WHERE  B_Number='$Number'
	";
	$sth = $dbh -> do_query($query);
	list($board,$file) = $dbh -> fetch_array($sth);

// --------------------------------------------------------------
// Now we have the board, let's see who has access to this forum.
	$query = "
		SELECT Bo_Read_Perm
		FROM {$config['tbprefix']}Boards
		WHERE  Bo_Keyword='$board'
	";
	$sth = $dbh -> do_query($query);
	list($readperm) = $dbh -> fetch_array($sth);

// ----------------------------------------------------------------------
// We need to check and see if they have privileges for this forum.
	if (!$user['U_Groups']) { $user['U_Groups'] = "-4-"; }
	$Grouparray = split("-",$user['U_Groups']);
	$gsize = sizeof($Grouparray);
	$readable = "";
	for ($j=0; $j < $gsize; $j++) {
		if (!$Grouparray[$j]) { continue; }
		if (strstr($readperm,"-$Grouparray[$j]-") ) {
			$readable = "yes";
			break;
		}
	}

// ---------------------------------------------------------------------
// If readable wasn't set to yes in that loop then the user doesn't have
// access to that download
	if ($readable != "yes") {
		$html = new html;
		$html -> not_right($ubbt_lang['NO_DOWNLOAD'],$Cat);
	}

// ---------------------------------------------
// Now let's update the total # of downloads by 1
	$query = "
		UPDATE {$config['tbprefix']}Posts
		SET B_FileCounter = B_FileCounter + 1
		WHERE B_Number='$Number'
	";
	$dbh -> do_query($query);

   $file = rawurlencode($file);
	header("Location:  {$config['fileurl']}/$file");
