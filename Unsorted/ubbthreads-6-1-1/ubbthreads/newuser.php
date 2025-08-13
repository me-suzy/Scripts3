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
   require ("languages/${$config['cookieprefix']."w3t_language"}/newuser.php");
   $html = new html;

// -------------------------------------------------------------------------
// Security check, make sure install, createtable, altertable files are gone
   $query = "
      SELECT COUNT(U_Username)
      FROM   {$config['tbprefix']}Users
   ";
   $sth = $dbh -> do_query($query);
   list($usercheck) = $dbh -> fetch_array($sth);
   if ($usercheck < 2) {

      $alterarray = array(
	"altertable-5.2-5.3.php",
	"altertable-5.3-5.4.php",
	"altertable-5.4.1-5.4.2.php",
	"altertable-5.4.2-5.4.3.php",
	"altertable-5.4.3-5.4.4.php",
	"altertable-5.4.4-5.4.5.php",
	"altertable-5.4.6-5.5.php",
	"altertable-5.5-6.0.php"
      );

      if (file_exists("install.php")) {
         $html -> not_right("You need to delete the install.php script before creating your admin user.",$Cat);
      } 
      elseif (file_exists("createtable.php")) {
         $html -> not_right("You need to delete the createtable.php script before creating your admin user.",$Cat);
      } 
      $asize = sizeof($alterarray);
      for ($i=0;$i<$asize;$i++) {
         if (file_exists($alterarray[$i])) {
            //$html -> not_right("You need to delete all altertable scripts before creating your admin user.",$Cat);
         } 
      }
   }

// ------------------------------------------------------
// If we are checking ages, the we need to do this first
   if ( ($config['checkage']) && ($p != "y") ) {
      $html -> send_header($ubbt_lang['AGE_VER'],$Cat,0,0,0,0);

      $currentyear = date("Y");
      if (${$config['cookieprefix']."ubbt_dob"}) {
        list($month,$day,$year) = split("/",${$config['cookieprefix']."ubbt_dob"});
        $months = array('','January','February','March','April','May','June','July','August','September','October','November','December');
        $formprint = "
           {$ubbt_lang['DOB_EXIST']}
           $months[$month] $day, $year
           <input type=\"hidden\" name=\"month\" value=\"$month\" />
           <input type=\"hidden\" name=\"day\" value=\"$day\" />
           <input type=\"hidden\" name=\"year\" value=\"$year\" />
        ";
      }
      else {
         $formprint = "
          {$ubbt_lang['DOB']}<br />
          <select name=\"month\" class=\"formboxes\">
          <option value=\"1\">{$ubbt_lang['JAN']}</option>
          <option value=\"2\">{$ubbt_lang['FEB']}</option>
          <option value=\"3\">{$ubbt_lang['MAR']}</option>
          <option value=\"4\">{$ubbt_lang['APR']}</option>
          <option value=\"5\">{$ubbt_lang['MAY']}</option>
          <option value=\"6\">{$ubbt_lang['JUN']}</option>
          <option value=\"7\">{$ubbt_lang['JUL']}</option>
          <option value=\"8\">{$ubbt_lang['AUG']}</option>
          <option value=\"9\">{$ubbt_lang['SEP']}</option>
          <option value=\"10\">{$ubbt_lang['OCT']}</option>
          <option value=\"11\">{$ubbt_lang['NOV']}</option>
          <option value=\"12\">{$ubbt_lang['DEC']}</option>
          </select>
          <select name=\"day\" class=\"formboxes\">
         ";
         for ($i=1;$i<=31;$i++) {
            $formprint .= "<option>$i</option>";
         }
         $formprint .= "</select><select name=\"year\" class=\"formboxes\">";
         for ($i=$currentyear;$i>=($currentyear-100);$i--) {
            $formprint .= "<option>$i</option>";
         }
         $formprint .= "
            </select>
         ";
      }

      if ($config['under13']) {
         $parentform = $ubbt_lang['PERM_FORM_DETAIL'];
      }
      else {
         $parentform = $ubbt_lang['NO_PERM_FORM'];
      }
   
      include("$thispath/templates/$tempstyle/newuser_checkage.tmpl");
      $html -> send_footer();
      exit;
   }

// ---------------------
// Send the page to them 
   $html -> send_header($ubbt_lang['NEW_USER'],$Cat,0,0,0,0);

// ---------------------------------------
// Let's see if we are giving board rules
   if ($config['boardrules']) {
      $boardrules = <<<EOF
<table width="{$theme['tablewidth']}" align="center" cellpadding="1" cellspacing="1" class="tablesurround">
<tr>
<td>
<table cellpadding="{$theme['cellpadding']}" cellspacing="{$theme['cellspacing']}" width="100%" class="tableborders">
<tr>
<td class="tdheader">
{$ubbt_lang['BOARD_RULES']}
</td>
</tr>
</table>
</td>
</tr>
</table>

<table width="{$theme['tablewidth']}" align="center" cellpadding="1" cellspacing="1" class="tablesurround">
<tr>
<td>
<table cellpadding="{$theme['cellpadding']}" cellspacing="{$theme['cellspacing']}" width="100%" class="tableborders">
<tr class="darktable">
<td>
EOF;
      $rules = @file("{$config['path']}/includes/boardrules.php");
      if (!is_array($rules)) {
         $rules = @file("{config['phpurl']}/includes/boardrules.php");
      }
      if ($rules) {
         while(list($linenum,$line) = each($rules)) {
            $boardrules .= $line;
         }
      }
$boardrules .= <<<EOF
<br />
<br />
<input type="checkbox" class="formboxes" name="agree" value="yes" /> {$ubbt_lang['I_AGREE']}

</td>
</tr>
</table>
</td>
</tr>
</table>
<br /><br />
EOF;
    
   }
   else {
      $boardrules = "<input type=\"hidden\" name=\"agree\" value=\"yes\">";
   }

// ----------------------------------------------------------------------
// If we are allowing the user to create their own password, then we give
// them a form
   if ($config['userpass']) {
      $choosepassword = " 
         {$ubbt_lang['OPT_PASS']}<br />
         <input type=\"password\" name=\"Loginpass\" class=\"formboxes\" />
         <br /><br />
         {$ubbt_lang['VER_PASS']}<br />
         <input type=\"password\" name = \"Verify\" class=\"formboxes\" />
         <br /><br />
      ";
   }

   include("$thispath/templates/$tempstyle/newuser_signup.tmpl");
   $html -> send_footer();

?>
