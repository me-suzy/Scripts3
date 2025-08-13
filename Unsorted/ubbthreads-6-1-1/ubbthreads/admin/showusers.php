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
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat); 
   }


// ------------------------
// Send them a page
   $html -> send_header ("Registered users.",$Cat,0,$user);
   $html -> admin_table_header("Registered users.");
   $html -> open_admin_table();

// -------------------------------------
// check to see if $search_val is needed
   if (($Menu1 == 'realusername' || $Menu1 == 'username' || $Menu1 == 'domain' || $Menu1 == 'email') && $search_val == '') {
      $html -> not_right ("You must enter a Username, Real Username, Domain, or E-mail address to search correctly.",$Cat);
   }

// -----------------------------------------------------------------------
// If this is a moderator then they can only look up Users with the Status
// of User
	$check = "";
   if ($user['U_Status'] == 'Moderator') {
      $status_q = "User";
      $check = "U_Status = '$status_q'";
   }

   $format = '';
   $part   = '';
   $part_q = '';
   $order = '';

// ----------------
// Which type of search are we doing?  added by Matt Reinfeldt 11/29/99
   if ($Menu1 == "users") {
      if (ereg("[A-Z]",$Menu2)){
         $part = "$Menu2"."%";
         $part_q = addslashes($part);
         $format = "AND U_Username Like '$part_q'";
         if ($check) {
            $format .=" AND ";
         }
      }
      elseif ($Menu2 == "0-9"){
         $zero  = "0%";
         $one   = "1%";
         $two   = "2%";
         $three = "3%";
         $four  = "4%";
         $five  = "5%";
         $six   = "6%";
         $seven = "7%";
         $eight = "8%";
         $nine  = "9%";
         $format = "
             AND U_Username Like '$zero'
             OR U_Username Like '$one'
             OR U_Username Like '$two'
             OR U_Username Like '$three'
             OR U_Username Like '$four'
             OR U_Username Like '$five'
             OR U_Username Like '$six'
             OR U_Username Like '$seven'
             OR U_Username Like '$eight'
             OR U_Username Like '$nine'
         "; 
         if ($check) {
            $format .=" AND ";
         }
      }
      if ($Menu2 == "All Users") {
         $format = '';
         if ($check) { $check ="AND $check"; }
      }
   }
   elseif ( $Menu1 == "email" ) {
      $part = $search_val;
      $part_q = addslashes($part);
      $format = "AND U_Email = '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "domain" ) {
      $part = "%".$search_val;
      $part_q = addslashes($part);
      $format = "AND U_Email Like '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "username" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "AND U_Username LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "realusername" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "AND U_Name LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "ipaddy" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "AND U_RegIP LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'datereg' ) {
      $time = $html -> get_date();
      $time = $time - $Menu2;
      $part_q = $time;
      $format = "AND U_Registered > '$part_q'";
      if ($check) {
          $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'laston' ) {
      $time = $html -> get_date();
      $time = $time - $Menu2;
      $part_q = $time;
      $format = "AND U_Laston > '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'totposts' ) {
      list($low_q,$upp_q) = split("-",$Menu2);
      if ($Menu2 == "2500+") { 
         $low_q = 2500;
         $upp_q = 9999999999999;
      }
      $format = "AND U_TotalPosts >= '$low_q' AND U_TotalPosts <= '$upp_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'group' ) {
      $part = "%-"."$Number"."-%";
      $part_q = addslashes($part);
      $format = "AND U_Groups LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   else {
      $check = "AND U_Status = '$status_q'";
   }

// ------------------------------------
// now we have to define the sort order
  if ( $Sort_key == 'Total Posts - Ascending' ) {
     $order = "ORDER BY U_TotalPosts ASC";
  }
  elseif ( $Sort_key == 'Total Posts - Descending' ) {
     $order = "ORDER BY U_TotalPosts DESC";
  }
  elseif ( $Sort_key == 'Date Registered - Ascending' ) {
     $order = "ORDER BY U_Registered ASC";
  }
  elseif ( $Sort_key == 'Date Registered - Descending' ) {
     $order = "ORDER BY U_Registered DESC";
  }
  elseif ( $Sort_key == 'Last On - Ascending' ) {
     $order = "ORDER BY U_Laston ASC";
  }
  elseif ( $Sort_key == 'Last On - Descending' ) {
     $order = "ORDER BY U_Laston DESC";
  }
  else {
     $order = "ORDER BY U_Username";
  }

  $numbercheck = "WHERE U_Number <> 1";
  if ($user['U_Number'] != 2) {
     $numbercheck = "WHERE U_Number <> 1 AND U_Number <> 2";
  }

// ------------
// Do the query
   $query = " 
     SELECT U_Username,U_Number,U_Registered,U_Totalposts,U_Laston,U_Email,U_RegEmail,U_Status,U_Title
     FROM {$config['tbprefix']}Users
     $numbercheck
     AND   U_Approved = 'yes' 
     $format
     $check
     $order
   ";
   $sth = $dbh -> do_query($query);

// let's see how many Users were returned... MINUS PLACEHOLDER USER
   $usrCnt = $dbh -> total_rows($sth);
   

// ---------------------
// Give them the results
   echo "<tr class=\"cleartable\"><td colspan=7><span class=\"onbody\">Total Users returned by query: $usrCnt</span></td></tr>\n";

   if ($assignmod) {
      echo "<br>Click on the user that you want to be the moderator of your selected forums.";
   }

   echo "<TR class=\"tdheader\"><TD><b>UserName</TD><TD><b>Registered On</TD><TD><b>User Status</TD><TD><b>Total Posts</TD><TD><b>User Title</TD><TD><b>Last On</TD><TD><b>Real E-mail,<br>Original E-mail</TD></TR>\n";

   $color = "lighttable";
   while (list ($User,$Number,$RegDate,$TotPosts,$LastOn,$CurrEmail,$RegEmail,$Status,$Title) = $dbh -> fetch_array($sth)){

      $lastonTime = $html -> convert_time($LastOn);
      $regdateTime = $html -> convert_time($RegDate);

      if ( ($Number == '2') && ($user['U_Number'] != 2) ) {
         continue; 
      }

      $EUser = rawurlencode($User);
      echo "<TR><TD class=\"$color\">";
      echo "<a href=\"{$config['phpurl']}/admin/selectoption.php?User=$EUser&assignmod=$assignmod\">$User</a>";
      echo "</TD><TD class=\"$color\">$regdateTime</TD><TD class=\"$color\">$Status</TD><TD class=\"$color\">$TotPosts</TD><TD class=\"$color\">$Title</TD><TD class=\"$color\">$lastonTime</TD><TD class=\"$color\">";
      echo "<a href=\"mailto:$CurrEmail\">$CurrEmail</a><br><a href=\"mailto:$RegEmail\">$RegEmail</a></TD></TR>\n";
      if ($color == "lighttable") {
         $color = "darktable";
      }
      else {
         $color = "lighttable";
      }
   }
   $dbh -> finish_sth($sth);

   echo "</TABLE>";
   echo "</TD></TR></TABLE>";
   $html -> send_admin_footer();
