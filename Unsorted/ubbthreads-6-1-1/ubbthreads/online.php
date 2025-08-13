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
   require ("languages/${$config['cookieprefix']."w3t_language"}/online.php");

// Define any necessary variables
   $icqindicator = "";

// -----------------
// Get the user info
   $userob = new user;
   $user = $userob -> authenticate("U_TimeOffset,U_Groups");

// ---------------------------------
// Split their groups into an array
   if (!$user['U_Groups']) {
     $user['U_Groups'] = "-4-";
   }
   $Grouparray = split("-",$user['U_Groups']);
   $gsize = sizeof($Grouparray);

// -------------------------
// Delete the inactive users
   $html = new html;
   $Outdated = $html -> get_date() - 600;
   $query = "
      DELETE FROM {$config['tbprefix']}Online
      WHERE       O_Last < $Outdated
   ";
   $dbh -> do_query($query);

// ------------------
// Send a html header
   $html -> send_header($ubbt_lang['ONLINE_HEAD'],$Cat,"<meta http-equiv=\"Refresh\" content=\"60;url={$config['phpurl']}/online.php?Cat=$Cat\" />",$user);
   $query = "
      SELECT {$config['tbprefix']}Online.O_Username, {$config['tbprefix']}Online.O_Last, {$config['tbprefix']}Online.O_What,{$config['tbprefix']}Online.O_Extra,{$config['tbprefix']}Online.O_Read,{$config['tbprefix']}Users.U_Status,{$config['tbprefix']}Users.U_Visible,{$config['tbprefix']}Users.U_Extra1,{$config['tbprefix']}Users.U_Title,{$config['tbprefix']}Users.U_Color,{$config['tbprefix']}Users.U_OnlineFormat
      FROM   {$config['tbprefix']}Online,{$config['tbprefix']}Users
      WHERE {$config['tbprefix']}Online.O_Username = {$config['tbprefix']}Users.U_Username
      ORDER BY O_Last DESC
   ";
   $reged = $dbh -> do_query($query);
   $regrows  = $dbh -> total_rows($reged);


   $color = "lighttable";
 
// Array key tracker
   $x = 0;
   
   while (list($Username,$Last,$RealWhat,$Extra,$Read,$Status,$Visible,$ICQ,$Title,$Color,$OnlineFormat) = $dbh -> fetch_array($reged) ) {

      $regrow[$x]['color'] = $color;

   // -----------------------------------------------
   // Let's see if we need to block some information
      $Private = 0;
      if ($user['U_Status'] != "Administrator") {
         $Private = 1;
         if ($Read) {
            for ($i=0; $i<=$gsize; $i++) {
               if (!preg_match("/[0-9]/",$Grouparray[$i])) { continue; };
               if (!strstr($Read,"-$Grouparray[$i]")) {
                  $Private = 1;
               }
               else {
                  $Private = 0;
                  break;
               }
            }
         }
      } 
   // ------------------------------------------------------
   // Replace their location with something that makes sense
      if (stristr($RealWhat,"faq_")) {
         $What = "faq_english";
      }
      $What = $ubbt_lang[$RealWhat];
      if (!$What) { $What = $ubbt_lang['all_admin']; }


      if ( ($user['U_Status'] != "Administrator") && ($Visible == "no") ) {
         continue;
      }
      $extra = "";
      if ($Visible == "no") {
         $extra = "(I)";
      }
      $regrow[$x]['extra'] = $extra;
      $Last = $html -> convert_time($Last,$user['U_TimeOffset']);
      $EUsername = rawurlencode($Username);
      $PUsername = $Username;
      if ($Color) {
         $PUsername = "<font color=\"$Color\">$Username</font>";
      }
      $regrow[$x]['EUsername'] = $EUsername;
      $regrow[$x]['PUsername'] = $PUsername;

      if ($Status == "Administrator") { $Status = $ubbt_lang['USER_ADMIN']; }
      if ($Status == "Moderator") { $Status = $ubbt_lang['USER_MOD']; }
      if ($Status == "User") { $Status == $ubbt_lang['USER_USER']; }

    // -----------------------------------------------------
    // Here we give more information on what they are doing
       if ( ($OnlineFormat == "no") && ($user['U_Status'] != "Administrator") ) { $Private = 1; }

      if ( ($Extra) && (!$Private) ){
        if (( $RealWhat == "postlist") || ($RealWhat == "newpost") ){
           if (!$Boards[$Extra]) {
              $query = "SELECT Bo_Title FROM {$config['tbprefix']}Boards WHERE Bo_Keyword='$Extra'";
              $sti = $dbh -> do_query($query);
              list($Boards[$Extra]) = $dbh -> fetch_array($sti);
           }
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/postlist.php?Board=$Extra\">$Boards[$Extra]</a></font>";
        }
        elseif ($RealWhat == "showflat") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showflat.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           
        }
        elseif ($RealWhat == "showthreaded") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           
        }
        elseif ($RealWhat == "newreply") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
        }
        elseif ($RealWhat == "addpost") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           if ($subject) {
              $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           }
           else {
              if (!$Boards[$board]) {
                 $query = "SELECT Bo_Title FROM {$config['tbprefix']}Boards WHERE Bo_Keyword='$board'";
                 $sti = $dbh -> do_query($query);
                 list($Boards[$board]) = $dbh -> fetch_array($sti);
              }
              $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/postlist.php?Board=$board\">$Boards[$board]</a></font>";
           }
        }     
      }

      if ( ($Extra) && ($Private) ){
         $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({$ubbt_lang['Private']})";
      }

      if ( (preg_match('/^\d+$/',$ICQ)) && ($config['ICQ_Status']) ) {
         $icqindicator = "<img src=\"http://online.mirabilis.com/scripts/online.dll?icq=$ICQ&amp;img=5\" alt=\"*\" />";
      }
	   else {
		   $icqindicator = "";
		}

      if ($Extra == "0") { $Extra = ""; }
      $regrow[$x]['icqindicator'] = $icqindicator;
      $regrow[$x]['Status']       = $Status;
      $regrow[$x]['Title']        = $Title;
      $regrow[$x]['Last']         = $Last;
      $regrow[$x]['What']         = $What;
      $regrow[$x]['Extra']        = $Extra;

      $color = $html -> switch_colors($color);
      $x++;

   }
   $regsize = sizeof($regrow);
   $dbh -> finish_sth($reged);

// ----------------------------------------
// Now show the one's that aren't logged in
   $query = "
      SELECT O_Username,O_Last,O_What,O_Extra
      FROM   {$config['tbprefix']}Online
      WHERE  O_Username LIKE '-ANON-%'
      ORDER BY O_Last DESC 
   ";
   $unreged = $dbh -> do_query($query);
   $anonrows    = $dbh -> total_rows($unreged);
   if ($user['U_Status'] == "Administrator") {
      $column = $ubbt_lang['FROM_IP'];
   }
   else {
      $column = $ubbt_lang['USERNAME_TEXT'];
   }
   $color = "lighttable";

   $What = "";
   $Extra = "";

// Anonrow array key number
   $x = 0;
   while (list($Username,$Last,$RealWhat,$Extra) = $dbh -> fetch_array($unreged) ) {

      $anonrow[$x]['color'] = $color;

   // ------------------------------------------------------
   // Replace their location with something that makes sense
      if (stristr($RealWhat,"faq_")) {
         $What = "faq_english";
      }
      $What = $ubbt_lang[$RealWhat];

      if ($user['U_Status'] == "Administrator") {
         $piece['0'] = "";
         preg_match("/-ANON-(.*)/",$Username,$piece);
         $Username = $piece['1'];
      }
      else {
         $Username = $ubbt_lang['ANON_TEXT'];
      }
      
      $Last = $html -> convert_time($Last,$user['U_TimeOffset']);

    // -----------------------------------------------------
    // Here we give more information on what they are doing
      if ($Extra){
        if (( $RealWhat == "postlist") || ($RealWhat == "newpost") ){
           if (!$Boards[$Extra]) {
              $query = "SELECT Bo_Title FROM {$config['tbprefix']}Boards WHERE Bo_Keyword='$Extra'";
              $sti = $dbh -> do_query($query);
              list($Boards[$Extra]) = $dbh -> fetch_array($sti);
           }
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/postlist.php?Board=$Extra\">$Boards[$Extra]</a></font>";
        }
        elseif ($RealWhat == "showflat") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showflat.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           
        }
        elseif ($RealWhat == "showthreaded") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           
        }
        elseif ($RealWhat == "newreply") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
        }
        elseif ($RealWhat == "addpost") {
           list($board,$number,$subject) = split("_SEP_",$Extra);
           if ($subject) {
              $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/showthreaded.php?Board=$board&amp;Number=$number\">$subject</a></font>";
           }
           else {
              if (!$Boards[$board]) {
                 $query = "SELECT Bo_Title FROM {$config['tbprefix']}Boards WHERE Bo_Keyword='$board'";
                 $sti = $dbh -> do_query($query);
                 list($Boards[$board]) = $dbh -> fetch_array($sti);
              }
              $Extra = "<font class=\"small\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$config['phpurl']}/postlist.php?Board=$board\">$Boards[$board]</a></font>";
           }
        }     
      }
		if (!$Extra) { $Extra = ""; }
      $anonrow[$x]['Username'] = $Username;
      $anonrow[$x]['Last']     = $Last;
      $anonrow[$x]['What']     = $What;
      $anonrow[$x]['Extra']    = $Extra;
      $x++;
      $color = $html -> switch_colors($color);

   }
   $anonsize = sizeof($anonrow);
   $dbh -> finish_sth($unreged);
	if (!$debug) {
   	include("$thispath/templates/$tempstyle/online.tmpl");
	}

// Send the footer
  $html -> send_footer();
?>

