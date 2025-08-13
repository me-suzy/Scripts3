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

// ----------------------------------------------------------------------
// If we are not logged in, then present a log on form, otherwise present
// a menu of options.

   if( ($user['U_Status'] != 'Administrator') && ($user['U_Status'] != 'Moderator') ) {
      $html -> send_header("You must login first.",$Cat,"<META HTTP-EQUIV=\"Refresh\" content=\"5;url={$config['phpurl']}/login.php?Cat=$Cat\">",0,0,0);

      $html -> admin_table_header("You must login first.");
      echo <<<EOF
        <TABLE BORDER=0 WIDTH="95%" ALIGN="center">
        <TR class="cleartable"><TD><span class="onbody">
         To access this section you must first be logged in.  You will be taken to the login screen shortly.  
         </span>
        </TD></TR>
        </TABLE>
EOF;
      $html -> send_admin_footer();
      exit;
   }
   
// --------------------------------------
// Make sure we load the proper main page
   if (!$file) {
      $file = "main.php";
      $extra = "?Cat=$Cat";

      if ($option == "option") {
         $file = "selectoption.php";
         $extra = "?Cat=$Cat&User=$User";
      }
      elseif ($option == "oneuser") {
         $file = "showoneuser.php";
         $extra = "?Cat=$Cat&User=$User";
      }
   }

   echo <<<EOF
<html>
<head>
<title>UBB.threads Administration Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="70,*" frameborder="NO" border="0" framespacing="0"> 
  <frame name="topFrame" scrolling="NO" noresize src="top.php?Cat=$Cat">
<frameset cols="233,*" frameborder="NO" border="0" framespacing="0" rows="*">

  <frame name="leftFrame" scrolling="YES" noresize src="menu.php">
  <frame name="mainFrame" src="$file$extra" noresize>
</frameset>
</frameset>
<noframes> 
EOF;
?>
