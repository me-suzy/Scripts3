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
   if ( ($user['U_Status'] != 'Moderator') && ($user['U_Status'] != 'Administrator')){
      $html -> not_right ("You must be logged in, and be a valid administrator or moderator to access this.",$Cat);
   }

// ------------------------
// Send them a page
   $user['java'] = "groupcopy";

   $html -> send_header ("Manipulate a group",$Cat,0,$user);
   $html -> admin_table_header("Change users in this group.");
   $html -> open_admin_table();

// -------------------------------------
// check to see if $search_val is needed
   if (($Menu1 == 'realusername' || $Menu1 == 'username' || $Menu1 == 'domain' || $Menu1 == 'email') && $search_val == '') {
      $html -> not_right ("You must enter a Username, Real Username, Domain, or E-mail address to search correctly.",$Cat);
   }

// -----------------------------------------------------------------------
// If this is a moderator then they can only look up Users with the Status
// of User
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
         $format = "WHERE U_Username Like '$part_q'";
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
             WHERE U_Username Like '$zero'
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
         if ($check) { $check ="WHERE $check"; }
      }
   }
   elseif ( $Menu1 == "email" ) {
      $part = $search_val;
      $part_q = addslashes($part);
      $format = "WHERE U_Email = '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "domain" ) {
      $part = "%".$search_val;
      $part_q = addslashes($part);
      $format = "WHERE U_Email Like '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "username" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "WHERE U_Username LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "realusername" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "WHERE U_Name LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == "ipaddy" ) {
      $part = "%"."$search_val"."%";
      $part_q = addslashes($part);
      $format = "WHERE U_RegIP LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'datereg' ) {
      $time = $html -> get_date();
      $time = $time - $Menu2;
      $part_q = $time;
      $format = "WHERE U_Registered > '$part_q'";
      if ($check) {
          $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'laston' ) {
      $time = $html -> get_date();
      $time = $time - $Menu2;
      $part_q = $time;
      $format = "WHERE U_Laston > '$part_q'";
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
      $format = "WHERE U_TotalPosts >= '$low_q' AND U_TotalPosts <= '$upp_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   elseif ( $Menu1 == 'group' ) {
      $part = "%-"."$Number"."-%";
      $part_q = addslashes($part);
      $format = "WHERE U_Groups LIKE '$part_q'";
      if ($check) {
         $format .=" AND ";
      }
   }
   else {
      $check = "WHERE U_Status = '$status_q'";
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

// ------------
// Do the query
   $query = "
     SELECT U_Username,U_Groups
     FROM {$config['tbprefix']}Users
     $format
     $check
     $order
   ";
   $sth = $dbh -> do_query($query);

// let's see how many Users were returned... MJR
   $usrCnt = $dbh -> total_rows($sth);


// ---------------------
// Give them the results

   echo "<tr class=\"lighttable\"><td>";
   echo "<form method=post action=\"{$config['phpurl']}/admin/massupdate.php\" onSubmit = \"check(this)\">";
   echo "<input type=hidden name=\"Cat\" value=\"$Cat\">";
   echo "<input type=hidden name=\"Group\" value=\"$Group\">";
   while ( list($User,$TGroup) = $dbh -> fetch_array($sth)){

   // ----------------------------------  
   // Check if they belong to this group
      $belong['0'] = "";
      $nobelong['0'] = "";
      if (ereg("-$Group-",$TGroup)) {
         array_push ($belong, $User);
      }
      else {
         array_push ($nobelong, $User);
      } 
    
   }

// -----------------------------------------------
// Show a box for users that are not in this group
   $html -> open_admin_table();
   echo "
    <tr class=darktable>
      <td width=40% align=center>
        Users not in this group
      </td>
      <td width=20% align=center>
      &nbsp;
      </td>
      <td width=40% align=center>
        Users in this group
      </td>
    </tr>
    <tr class=lighttable>
      <td align=center>
        <select multiple size=\"20\" name=\"nobelong\">
   ";

// -------------------------------  
// echo those that do not belong
   $arraysize = sizeof($nobelong);
   for ( $i=1; $i<=$arraysize;$i++) {
      if ($nobelong[$i]) {
         echo "<option value=\"$nobelong[$i]\">$nobelong[$i]";
      }
   }

   echo "
         <option value=\"-spacer-\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         </select>
      </td>
      <td align=center>
        <input type=\"button\" value=\"   >>   \" onclick=\"move(this.form.nobelong,this.form.belong)\" name=\"B1\"><br>
        <input type=\"button\" value=\"   <<   \" onclick=\"move(this.form.belong,this.form.nobelong)\" name=\"B2\">
      </td>
      <td align=center>
        <select multiple size=\"20\" name=\"belong\">
   ";

// -------------------------------  
// echo those that do not belong
   $arraysize = sizeof($belong);
   for ( $i=0; $i<=$arraysize;$i++) {
      if ($belong[$i]) {
         echo "<option value=\"$belong[$i]\">$belong[$i]";
      }
   }

   echo "
         <option value=\"-spacer-\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       </select>
     </td>
   </tr>
   <tr>
     <td class=cleartable colspan=3 align=center>
       <input type=submit value=\"Update this group\" onClick=\"check(this.form.belong,this.form.nobelong)\">
  ";
  $html -> close_table();     

  $html -> close_table();
  $html -> send_admin_footer();

?>
