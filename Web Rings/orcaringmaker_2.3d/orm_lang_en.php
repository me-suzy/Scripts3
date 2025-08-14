<?php /* ***** Orca Ringmaker - English Language File ********* */

/* ***************************************************************
* Orca Ringmaker v2.x
*  A comprehensive web ring creation and managment script
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*
* If you translate this file into your native language, please
* send me a copy so I can include it in the ringmaker package.
* Your name will appear in the header of the file you translate
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("en_EN", "enc"));
$pageEncoding = 1;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$dateFormat = "%b %d, %Y  %X";  // see http://www.php.net/strftime



/* ***** Database Values ************************************** */
$lang['db1'] = "Found";
$lang['db2'] = "Not Found";
$lang['db3'] = "Error";
$lang['db4'] = "My Ring";
$lang['db5'] = "your@email.com";
$lang['db6'] = "Ring Announcement";
$lang['db7'] = "Inactive";
$lang['db8'] = "Unchecked";
$lang['db9'] = "Active";
$lang['dba'] = "Hibernating";
$lang['dbb'] = "All";
$lang['dbc'] = "Suspended";
$lang['dbd'] = "Selected";
$lang['dbe'] = "Join";
$lang['dbf'] = "Ring Hub";
$lang['dbg'] = "Random";
$lang['dbh'] = "Previous";
$lang['dbi'] = "Next";
$lang['dbj'] = "This site is a member of: %s";
$lang['dbk'] = "Direct";


/* ***** Terms ************************************************ */

$lang['term2'] = "Administrator";
$lang['term3'] = "Login";
$lang['term4'] = "Logout";
$lang['term5'] = "Add";
$lang['term6'] = "Member";
$lang['term7'] = "Change";
$lang['term8'] = "Check";
$lang['term9'] = "Leave";
$lang['terma'] = "Edit";
$lang['termb'] = "Toggle Status";
$lang['termc'] = "Submit";

$lang['terme'] = "Remove";
$lang['termf'] = "Email";

$lang['termh'] = "Username";
$lang['termi'] = "Password";
$lang['termj'] = "Reset";
$lang['termk'] = "Cancel";
$lang['terml'] = "Confirm";
$lang['termm'] = "Yes";
$lang['termn'] = "No";
$lang['termo'] = "Disabled";
$lang['termp'] = "Enabled";


/* ***** Authentication *************************************** */
$lang['auth1'] = "Submission rejected; Image authentication failed";
$lang['auth2'] = "Enter the numbers displayed in the image to authenticate your submission";
$lang['auth3'] = "Authentication";


/* ***** HTML Page Text *************************************** */
$lang['page1'] = "Controls";
$lang['page2'] = "Go to the Ring Hub";
$lang['page3'] = "Add Site";
$lang['page4'] = "Add a site to this ring";
$lang['page5'] = "Random Site";
$lang['page6'] = "Go to a random site in this ring";
$lang['page7'] = "Statistics";
$lang['page8'] = "Ring Statistics";
$lang['page9'] = "Sites";
$lang['pagea'] = "Hits (2 weeks)";
$lang['pageb'] = "Hits (8 weeks)";
$lang['pagec'] = "Logged in as:<br /> <strong>%s</strong>";
$lang['paged'] = "Edit Details";
$lang['pagee'] = "Edit your site details";
$lang['pagef'] = "Administration";
$lang['pageg'] = "Ring Administration";
$lang['pageh'] = "Send Email";
$lang['pagei'] = "Send Email to members of this ring";
$lang['pagej'] = "Ring Setup";
$lang['pagek'] = "Ring Setup control panel";
$lang['pagel'] = "Your login name, alphanumerics only";
$lang['pagem'] = "Enter twice to verify, alphanumerics only";
$lang['pagen'] = "Email Address";
$lang['pageo'] = "Display Name";
$lang['pagep'] = "The \"Owner\" of the site";
$lang['pageq'] = "Site Name";
$lang['pager'] = "Site URI";
$lang['pages'] = "Site Description";
$lang['paget'] = "Less than 500 characters";
$lang['pageu'] = "Confirm Site Removal";
$lang['pagev'] = "Are you sure you wish to leave the ring?";

$lang['pagex'] = "Your Current Profile";
$lang['pagey'] = "Change Password";
$lang['pagez'] = "Old password";
$lang['pag_1'] = "Site Status";
$lang['pag_2'] = "Not Yet Approved";
$lang['pag_3'] = "Your Current Email Address";
$lang['pag_4'] = "Current Navigation Bar Status";
$lang['pag_5'] = "Status";
$lang['pag_6'] = "Last checked <em>%s</em>";
$lang['pag_7'] = "Your Ring Code";
$lang['pag_8'] = "Leave the Ring";
$lang['pag_9'] = "You will be asked to confirm this action";
$lang['pag_a'] = "Ring Name";
$lang['pag_b'] = "Ring Email";
$lang['pag_c'] = "SMTP Server";
$lang['pag_d'] = "Default: localhost";

$lang['pag_f'] = "Ring Sites Per Page";
$lang['pag_g'] = "Top # Sites to Display in Statistics";
$lang['pag_h'] = "Ring Announcement";
$lang['pag_i'] = "Administrative Status";
$lang['pag_j'] = "Toggle Ring Member Administrative Status";
$lang['pag_k'] = "Select a Ring Member";
$lang['pag_l'] = "Navigation Bar";
$lang['pag_m'] = "CAUTION";
$lang['pag_n'] = "Current Navigation Bar";
$lang['pag_o'] = "Edit JavaScript Enabled Navigation Bar";
$lang['pag_p'] = "Edit JavaScript Disabled Navigation Bar";
$lang['pag_q'] = "Profile for <a href=\"%1\$s?Email&id=%2\$d\">%3\$s</a>";
$lang['pag_r'] = "Reason for status change (optional)";
$lang['pag_s'] = "Remove %s";
$lang['pag_t'] = "Remove %s from the ring?";
$lang['pag_u'] = "Confirm Site Removal";
$lang['pag_v'] = "Are you sure you wish to remove %s from the ring?";
$lang['pag_w'] = "This action cannot be undone";
$lang['pag_x'] = "Reason for removal (optional)";
$lang['pag_y'] = "Sites awaiting review...";
$lang['pag_z'] = "Edit Accounts";

$lang['pa__2'] = "Compose Email";
$lang['pa__3'] = "Select Recipient List";
$lang['pa__4'] = "Select Ring Members";
$lang['pa__5'] = "Message Subject";
$lang['pa__6'] = "%s Announcement";
$lang['pa__7'] = "Message Text";
$lang['pa__8'] = "Three Days";
$lang['pa__9'] = "Two Weeks";
$lang['pa__a'] = "Eight Weeks";
$lang['pa__b'] = "Hits";
$lang['pa__c'] = "Ring total";
$lang['pa__d'] = "% of total";
$lang['pa__e'] = "Clicks";
$lang['pa__f'] = "Click Graph";
$lang['pa__g'] = "Hit Graph";
$lang['pa__h'] = "Eight Weeks Ago";
$lang['pa__i'] = "Today";
$lang['pa__j'] = "Entire Ring";
$lang['pa__k'] = "Daily Average (%s)";
$lang['pa__l'] = "Hits, Top %d Sites";

$lang['pa__n'] = "Clicks, Top %d Sites";
$lang['pa__o'] = "Forgot your password?";
$lang['pa__p'] = "Type your username here";
$lang['pa__q'] = "A new password will be emailed to you";
$lang['pa__r'] = "Previous Page";
$lang['pa__s'] = "Next Page";
$lang['pa__t'] = "Page %d";
$lang['pa__u'] = "Sunday";
$lang['pa__v'] = "Monday";
$lang['pa__w'] = "Tuesday";
$lang['pa__x'] = "Wednesday";
$lang['pa__y'] = "Thursday";
$lang['pa__z'] = "Friday";
$lang['p___1'] = "Saturday";
$lang['p___2'] = "Statistics will become available once there are active sites in this ring";
$lang['p___3'] = "Email functions will become available once there are sites in this ring";
$lang['p___4'] = "Administration functions will become available once there are sites in this ring";
$lang['p___5'] = "This Member's Ring Code";
$lang['p___6'] = "Visit this URI";
$lang['p___7'] = "Site ID";
$lang['p___8'] = "Rearrange Ring Order";
$lang['p___9'] = "Randomize Order";
$lang['p___a'] = "Reorder by Site ID";
$lang['p___b'] = "Errors";
$lang['p___c'] = "Noon";
$lang['p___d'] = "Hourly Average";

$lang['p___f'] = "Browsers";

$lang['p___h'] = "Cache ring statistics to decrease load time";
$lang['p___i'] = "Statistics Caching";
$lang['p___j'] = "No Caching";
$lang['p___k'] = "Hourly";
$lang['p___l'] = "Daily";
$lang['p___m'] = "Caching Enabled";
$lang['p___n'] = "Time since last cache";
$lang['p___o'] = "Hours";
$lang['p___p'] = "Minutes";
$lang['p___q'] = "Seconds";
$lang['p___r'] = "Reorder by Site Title";
$lang['p___s'] = "Top Sites Generating Errors";
$lang['p___t'] = "Help";
$lang['p___u'] = "Ring Help";
$lang['p___v'] = "Choose a Timezone for this ring";
$lang['p___w'] = "Timezone";
$lang['p___x'] = "The UTC Offset of the specified Timezone";
$lang['p___y'] = "UTC Offset";
$lang['p___z'] = "Show the Orca Ringmaker Help Text";
$lang['page_'] = "Show Help Text";
$lang['page0'] = "Path to the Help Text file";
$lang['pag__'] = "Help Text File";
$lang['pag_0'] = "Enable/Disable Image Authentication for the Add Site form";
$lang['pa___'] = "Image Authentication";
$lang['pa__0'] = "Font %d";
$lang['p____'] = "Path to the Statistics Icon";
$lang['p___0'] = "Statistics Icon";
$lang['Page_'] = "File %s could not be found!";
$lang['Page0'] = "Icon %s could not be found!";
$lang['Page1'] = "Statistics have not yet been cached";
$lang['Page2'] = "Show URIs";
$lang['Page3'] = "Show Titles";
$lang['Page4'] = "Joined";
$lang['Page5'] = "Before %s";

$lang['html1'] = <<<ORCA
  Your site has been successfully added to the ring queue.
  Note that your site will not become a link in the ring until it is approved by a ring moderator.

  Approval requires the presence of the ring navigation script somewhere on the page with the URI you gave in your application.
  Just copy and paste the code below onto that page and you'll be ready to go!

  If you wish to change any of your details later, just log in to your account with your username and password in the control panel.
ORCA;

$lang['html2'] = <<<ORCA
  An email containing a confirmation link has been sent to the address above.
  To complete your submission just click on the link or paste it into your browser's address bar.
ORCA;

$lang['html3'] = "Read the readme.txt file which came with this script <strong>thoroughly</strong> before attempting to edit your navigation bars!";

$lang['html5'] = "Welcome to the %s";

$lang['html6'] = <<<ORCA
  This script is a complete webring creation and management tool.
  Ideally, it's meant to be inserted into an existing webpage layout.
  Examine the readme.txt file for more information on how to go about doing this.

  If you're seeing this message, the script created and built the necessary databases it needs to function.
  Add your website using the "Add Site" link in the Control Panel to the right and lets make a ring!

  - GreyWyvern
ORCA;


/* ***** Error/Success Messages ******************************* */
$lang['err1'] = "User/Password not recognized";
$lang['err2'] = "You must log out before you can add another site";
$lang['err3'] = "Confirmation code not found or no longer valid";
$lang['err4'] = "Invalid characters in Username";
$lang['err5'] = "Username too long (32 character maximum)";
$lang['err6'] = "Username too short (4 character minimum)";
$lang['err7'] = "Username already exists in this ring";
$lang['err8'] = "You must choose a Username";
$lang['err9'] = "Invalid characters in Password";
$lang['erra'] = "Password too long (32 character maximum)";
$lang['errb'] = "Password too short (4 character minimum)";
$lang['errc'] = "Password fields do not match";
$lang['errd'] = "You must enter your password twice to verify";
$lang['erre'] = "You must choose a Password";
$lang['errf'] = "The Email address you entered is not valid";
$lang['errg'] = "You must enter a valid email address";
$lang['errh'] = "Owner's Name too long (32 Character limit)";
$lang['errj'] = "You must enter an Owner's Name";
$lang['errk'] = "Site Name too long (32 Character limit)";
$lang['errl'] = "Site Name already exists in this ring";
$lang['errm'] = "You must choose a Site Name";
$lang['errn'] = "You must specify a URI";
$lang['erro'] = "Description too long (500 Character limit)";
$lang['errp'] = "You must be logged in to perform this action";
$lang['errq'] = "You did not choose a new password";
$lang['errr'] = "Previous password does not match";
$lang['errs'] = "Only a ring moderator can change your site status to %s";
$lang['errt'] = "You do not have permission to access this page";
$lang['erru'] = "Ring Name too long (48 Character limit)";
$lang['errv'] = "Sorry, the site you wanted could not be reached";
$lang['errw'] = "Sites Per Page maximum = 50";
$lang['errx'] = "Sites Per Page minimum = 5";
$lang['erry'] = "Sites Per Top # List maximum = 50";
$lang['errz'] = "Sites Per Top # List minimum = 5";
$lang['er_1'] = "Announcement too long (500 Character limit)";
$lang['er_2'] = "Selected ID does not exist or has already been deleted";
$lang['er_3'] = "You did not select a ring member";
$lang['er_4'] = "This account no longer exists";
$lang['er_5'] = "You did not include a subject";
$lang['er_6'] = "You did not enter a message";
$lang['er_7'] = "No sites matched your selections";

$lang['er_9'] = "Not enough sites in this ring to Reorder";
$lang['er_a'] = "Statistics Icon could not be found";
$lang['er_b'] = "You did not select any recipients";
$lang['er_c'] = "Error sending mail to %s";


$lang['suc1'] = "If you submitted a valid username, you should be receiving a new password shortly";
$lang['suc2'] = "Site added successfully!";
$lang['suc3'] = "Verify your email address (%s)";
$lang['suc4'] = "Email address changed successfully";
$lang['suc5'] = "Verify your new email address (%s)";
$lang['suc6'] = "Your ring account has been deleted.  Sorry to see you go, %s";
$lang['suc7'] = "Password changed successfully";
$lang['suc8'] = "Owner's Name changed successfully";
$lang['suc9'] = "Site Name changed successfully";
$lang['suca'] = "Site URI changed successfully";
$lang['sucb'] = "Site Description changed successfully";
$lang['succ'] = "Your site status was changed to %s";
$lang['sucd'] = "Ring Name changed successfully";
$lang['suce'] = "PHP SMTP changed successfully";
$lang['sucf'] = "Sites Per Page changed successfully";
$lang['sucg'] = "Announcement changed successfully";
$lang['such'] = "%1\$s, owner of %2\$s has been given Administrative status";
$lang['suci'] = "%1\$s, owner of %2\$s has had their Administrative status removed";
$lang['sucj'] = "Check result for %1\$s: %2\$s";

$lang['sucl'] = "%s has been removed";
$lang['sucm'] = "Site Status was changed to %s";
$lang['sucn'] = "Mail was sent successfully";
$lang['suco'] = "Ring order randomized successfully";
$lang['sucp'] = "Ring ordered by Site ID successfully";
$lang['sucq'] = "Statistics caching preference changed successfully";
$lang['sucr'] = "Statistics caching in progress...";
$lang['sucs'] = "Ring ordered by Site Title successfully";
$lang['suct'] = "Ring Timezone set successfully";
$lang['sucu'] = "Ring UTC Offset set successfully";
$lang['sucv'] = "Help Text enabled";
$lang['sucw'] = "Help Text disabled";
$lang['sucx'] = "Help Text File changed successfully";
$lang['sucy'] = "Image Authentication setting changed successfully";
$lang['sucz'] = "Statistics Icon changed successfully";


/* ***** Email Messages *************************************** */
$lang['email']['admin1'] = <<<ORCA
This message was sent to:
%s
----------------------------------------------------------------------

ORCA;

$lang['email']['admin2'] = <<<ORCA
This message was sent to:
%s site owners
----------------------------------------------------------------------

ORCA;


$lang['email1']['subject'] = "Password reset";
$lang['email1']['message'] = <<<ORCA
A password change was requested for the username %1\$s

Your password was reset to: %2\$s

You may change your password using the Edit Details option after you log in.
ORCA;


$lang['email2']['subject'] = "New site awaiting review";
$lang['email2']['message'] = <<<ORCA
User %1\$s submitted the site %2\$s in %3\$s.

The URI associated with this submission is: %4\$s
ORCA;


$lang['email3']['subject'] = "Your confirmation code";
$lang['email3']['message'] = <<<ORCA
Thank you for submitting your site to %1\$s!

This message was sent to the email address corresponding to your application. In order to activate your account, just click the link below, or paste the URL into your browser address bar.

%2\$s?Add&rand=%3\$s

If you don't remember signing up for %1\$s, then some silly person probably used your email address instead of their own.  If that's the case, just ignore and delete this message.
ORCA;


$lang['email4']['subject'] = "Email address change";
$lang['email4']['message'] = <<<ORCA
A request was made at %1\$s to change the email address associated with %2\$s to this address.

To confirm this change, just click the link below, or paste the URL into your browser address bar.

%3\$s?Edit&rand=%4\$s

If you don't remember asking to change your email address, just ignore this message.
ORCA;


$lang['email5']['subject'] = "Your site has been activated";
$lang['email5']['message'] = <<<ORCA
Congratulations!

Your site, %1\$s, in %2\$s, was reviewed by a ring administrator and approved for membership.

Your site is now an active link in this ring!
ORCA;


$lang['email6']['subject'] = "Your site has been removed";
$lang['email6']['message'] = <<<ORCA
%1\$s,

Your site, %2\$s, in %3\$s, was removed from the ring by a ring administrator.

%4\$sThe ring will not save any of your site information; the records of your account have been permanently deleted.

If you have any questions about this action, please reply to this email to reach the ring owner.
ORCA;
$lang['email6']['reason'] = <<<ORCA
The administrator gave the following reason(s):
 - %s\n\n
ORCA;


$lang['email7']['subject'] = "Site status change";
$lang['email7']['message'] = <<<ORCA
Your site, %1\$s, in %2\$s, had its status changed to %3\$s by a ring administrator.

%4\$sIf you are unsure as to why your site status was changed, please reply to this email to reach the ring owner.
ORCA;
$lang['email7']['reason'] = <<<ORCA
The administrator gave the following reason(s):
 - %s\n\n
ORCA;


/* ***** Integration Functions ******************************** */
while (list($key, $value) = each($lang)) {
  if (!is_array($value) && $key != "charset") {
    if ($pageEncoding == 3) {
      $lang[$key] = htmlentities($value, ENT_COMPAT, "ISO-8859-1");
      $lang[$key] = str_replace(array("&lt;", "&gt;"), array("<", ">"), $lang[$key]);
    } else if ($pageEncoding == 1) $lang[$key] = utf8_encode($value);
  }
}

function dateStamp($time) {
  global $pageEncoding, $dateFormat, $lang, $vData;

  switch ($pageEncoding) {
    case 1: $timeStr = utf8_encode(gmstrftime($dateFormat, $time + $vData['tzoffset'] * 3600)); break;
    case 2: $timeStr = gmstrftime($dateFormat, $time + $vData['tzoffset'] * 3600); break;
    default: $timeStr = @htmlentities(gmstrftime($dateFormat, $time + $vData['tzoffset'] * 3600), ENT_COMPAT, $lang['charset']); break;
  }
  return $timeStr." ({$vData['timezone']})";
}

?>