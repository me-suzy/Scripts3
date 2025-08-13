<?php
/*
                                                   PHP Freelancers version 1.1
                                                   -----------------------
                                                   A script by ProEx101 Web Services
                                                   (http://www.ProEx101.com/)

    "PHP Freelancers" is not a free script. If you got this from someplace
    other than SmartCGIs.com or ProEx101 Web Services, please contact us,
    we do offer rewards for that type of information. Visit our site for up
    to date versions. Most PHP scripts are over $300, sometimes more than
    $700, this script is much less. We can keep this script cheap, as well
    as free scripts on our site, if people don't steal it.
          Also, no return links are required, but we appreciate it if you
          do find a spot for us.
          Thanks!

          Special Notice to Resellers
          ===========================
          Reselling this script without prior permission
          from ProEx101 Web Services is illegal and
          violators will be prosecuted to the fullest
          extent of the law.  To apply to be a legal
          reseller, please visit:
          http://www.ProEx101.com/freelancers/resell.php

       (c) copyright 2001 ProEx101 Web Services, SmartCGIs.com, and R3N3 Internet Services */

require "vars.php";
require "cron.php";

if (!$pass || $pass !== $adminpass) {
echo '<html>
  <head>
    <title>
      Access Unauthorized
    </title>
  </head>
  <body>
    <font color="red"><b>Access Denied: You are not authorized to view this page.</b></font>
  </body>
</html>';
} else {
if (!$submit) {
if ($mode == "demo") {
$y = "SELECTED";
} else {
$n = "SELECTED";
}
if ($attachmode == "enabled") {
$yy = "SELECTED";
} else {
$nn = "SELECTED";
}
if ($deposit2checkout == "enabled") {
$yyy = "SELECTED";
} else {
$nnn = "SELECTED";
}
if ($depositpaypal == "enabled") {
$yyyy = "SELECTED";
} else {
$nnnn = "SELECTED";
}
if ($depositnotify == "enabled") {
$yyyyy = "SELECTED";
} else {
$nnnnn = "SELECTED";
}
if ($depositccaut == "enabled") {
$yyyyyy = "SELECTED";
} else {
$nnnnnn = "SELECTED";
}
if ($depositppaut == "enabled") {
$yyyyyyy = "SELECTED";
} else {
$nnnnnnn = "SELECTED";
}
if ($depositmail == "enabled") {
$yyyyyyyy = "SELECTED";
} else {
$nnnnnnnn = "SELECTED";
}
if ($depositother == "enabled") {
$yyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnn = "SELECTED";
}
if ($withdrawpaypal == "enabled") {
$yyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnn = "SELECTED";
}
if ($withdrawwire == "enabled") {
$yyyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnnn = "SELECTED";
}
if ($withdrawother == "enabled") {
$yyyyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnnnn = "SELECTED";
}
if ($withdrawnotify == "enabled") {
$yyyyyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnnnnn = "SELECTED";
}
if ($multiplecats == "enabled") {
$yyyyyyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnnnnnn = "SELECTED";
}
if ($userportfolio == "enabled") {
$yyyyyyyyyyyyyyy = "SELECTED";
} else {
$nnnnnnnnnnnnnnn = "SELECTED";
}
$result = SQLact("query", "SELECT * FROM freelancers_cats");
while ($row=@SQLact("fetch_array", $result)) {
$categories .= '~' . $row[categories];
}
$categories = substr($categories, 1);
echo '<!-- Layout by DaDJRock. -->
<html lang="en-us">
  <head>
    <title>
      Customize Setup Variables
    </title>
    <style type="text/css">
      <!--
        .line {background-color: #005075}
        a { text-decoration: none}
        a:hover { color: 00C3E9; text-decoration: underline}
      -->
    </style>
    <script language="JavaScript" type="text/javascript">
      function stopError() {
        return false;
      }

      window.onError=stopError();
    </script>
  </head>
  <body bgcolor="#2A73B8">
    <center>
      <font size="+1" color="#0000FF">Customize Setup Variables</font><br><br>
      <form action="admin.php" method="post">
        <input type="hidden" name="pass" value="' . $pass . '">
        <input type="submit" value="Back to Admin...">
      </form>
      <form action="admin_setup.php" method="post">
        <input type="hidden" name="pass" value="' . $pass . '">
        <table width="691" border="1" cellspacing="0" cellpadding="3" align="center">
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: siteurl<br>
              Description: The complete directory URL to PHP Freelancers (DO NOT include the trailing slash).<br>
              Example: ' . $siteurl . '<br>
              <input type="text" name="newsiteurl" size="60" value="' . $siteurl . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: toplayout<br>
              Description: The contents of the header which will be placed on the top of all pages contained within PHP Freelancers (HTML tags are allowed).<br>
              Example: My Header&lt;hr><br>
              <textarea name="newtoplayout" rows="8" cols="52" wrap="off">' . $toplayout . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: bottomlayout<br>
              Description: The contents of the footer which will be placed on the bottom of all pages contained within PHP Freelancers (HTML tags are allowed).<br>
              Example: &lt;hr>My Footer<br>
              <textarea name="newbottomlayout" rows="8" cols="52" wrap="off">' . $bottomlayout . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: emailheader<br>
              Description: The contents of the header which will be placed on the top of all emails sent by PHP Freelancers (HTML tags aren\'t allowed).  In all emails sent by PHP Freelancers, below this email header will be "----------", followed by the message contents.<br>
              Example: E-Mail Header<br>
              <textarea name="newemailheader" rows="8" cols="52" wrap="off">' . $emailheader . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: emailfooter<br>
              Description: The contents of the footer which will be placed on the bottom of all emails sent by PHP Freelancers (HTML tags aren\'t allowed).  In all emails sent by PHP Freelancers, above this email footer will be "----------", and then the message contents.<br>
              Example: E-Mail Footer<br>
              <textarea name="newemailfooter" rows="8" cols="52" wrap="off">' . $emailfooter . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: emailaddress<br>
              Description: The main email address that you want to use for all matters involving PHP Freelancers and emails.<br>
              Example: nulledby@drew010.com<br>
              <input type="text" name="newemailaddress" size="45" value="' . $emailaddress . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: companyname<br>
              Description: The name of your company/organization that will be used as the default website name of everything.<br>
              Example: Freelance Web<br>
              <input type="text" name="newcompanyname" size="35" value="' . $companyname . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: fsbamount<br>
              Description: The account signup bonus that "freelancers" will receive when they create an account at your website (a decimal is allowed).<br>
              Example: 1.00<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newfsbamount" size="9" value="' . $fsbamount . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: bsbamount<br>
              Description: The account signup bonus that "buyers" will receive when they create an account at your website (a decimal is allowed).<br>
              Example: 2.50<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newbsbamount" size="9" value="' . $bsbamount . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: frefamount<br>
              Description: The referal amount a user will receive when they refer a "freelancer" and the "freelancer" successfully creates an account at your website (a decimal is allowed).<br>
              Example: 0.25<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newfrefamount" size="9" value="' . $frefamount . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: brefamount<br>
              Description: The referal amount a user will receive when they refer a "buyer" and the "buyer" successfully creates at your website (a decimal is allowed).<br>
              Example: 1<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newbrefamount" size="9" value="' . $brefamount . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: address<br>
              Description: If you choose to allow deposits by check, this is the street address where the check will be sent to.<br>
              Example: #1-999 Terror Ave.<br>
              <input type="text" name="newaddress" size="48" value="' . $address . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: city<br>
              Description: If you choose to allow deposits by check, this is the city where the check will be sent to.<br>
              Example: Terrorville<br>
              <input type="text" name="newcity" size="30" value="' . $city . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: state<br>
              Description: If you choose to allow deposits by check, this is the state/province where the check will be sent to (abbreviations allowed).<br>
              Example: Tekno Terrors<br>
              <input type="text" name="newstate" size="23" value="' . $state . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: zipcode<br>
              Description: If you choose to allow deposits by check, this is the zip/postal code where the check will be sent to.<br>
              Example: Z9Z 9Z9<br>
              <input type="text" name="newzipcode" size="20" value="' . $zipcode . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: currency<br>
              Description: The currency symbol of all money amounts shown on pages of PHP Freelancers.<br>
              Example: $<br>
              <input type="text" name="newcurrency" size="3" value="' . $currency . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: currencytype<br>
              Description: The type of the currency of all money amounts shown on pages of PHP Freelancers (leave this blank if there aren\'t multiple types of your selected currency).<br>
              Example: US<br>
              <input type="text" name="newcurrencytype" size="8" value="' . $currencytype . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: buyer<br>
              Description: The official name that all "buyers" will be represented by.<br>
              Example: Buyer<br>
              <input type="text" name="newbuyer" size="17" value="' . $buyer . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: buyers<br>
              Description: The plural version of the official name that all "buyers" will be represented by.<br>
              Example: Buyer<font color="red">s</font><br>
              <input type="text" name="newbuyers" size="19" value="' . $buyers . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: freelancer<br>
              Description: The official name that all "freelancers" will be represented by.<br>
              Example: Freelancer<br>
              <input type="text" name="newfreelancer" size="22" value="' . $freelancer . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: freelancers<br>
              Description: The plural version of the official name that all "freelancers" will be represented by.<br>
              Example: Freelancer<font color="red">s</font><br>
              <input type="text" name="newfreelancers" size="24" value="' . $freelancers . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: mode<br>
              Description: Demo mode configures PHP Freelancers to run in "test" mode, meaning that no deposits will actually be charged, attachments will not be allowed, etc.<br>
              Recommended: Disabled<br>
              <select name="newmode">
                <option ' . $y . ' value="demo"> Enabled
                <option ' . $n . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: adminpass<br>
              Description: The only password which will be allowed to access the admin pages (admin.php and admin_setup.php).<br>
              Example: mypass999<br>
              <input type="text" name="newadminpass" size="11" value="' . $adminpass . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: attachmode<br>
              Description: Attach mode configures PHP Freelancer to allow "buyers" to upload files that are relevant to their project when creating a new project.  This often involves a need for a lot of diskspace on your website.<br>
              Recommended: Disabled<br>
              <select name="newattachmode">
                <option ' . $yy . ' value="enabled"> Enabled
                <option ' . $nn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: attachmaxi<br>
              Description: If you enabled project attachments above, you can limit the size of the attachment in bytes.<br>
              Example: 50000<br>
              <input type="text" name="newattachmaxi" size="8" value="' . $attachmaxi . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: attachpath<br>
              Description: If you enabled project attachments above, you must specify the full path to where the attachments will be uploaded to (DO NOT include a trailing slash).  You MUST CHMOD this directory\'s permissions to 777!<br>
              Example: /home/mywebsite/public_html/freelancers/uploads<br>
              <input type="text" name="newattachpath" size="60" value="' . $attachpath . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: attachurl<br>
              Description: If you enabled project attachments above, you must specify the full directory URL to where attachments will be uploaded to (DO NOT include a trailing slash).  You MUST CHMOD this directory\'s permissions to 777!<br>
              Example: ' . $siteurl . '/uploads<br>
              <input type="text" name="newattachurl" size="60" value="' . $attachurl . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: catname<br>
              Description: This is the title of the categories inwhich "freelancers" choose to become experts of and "buyers" select as categories involving their project.<br>
              Example: Job Type<br>
              <input type="text" name="newcatname" size="19" value="' . $catname . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: categories<br>
              Description: These are the categories inwhich "freelancers" choose to become experts of and "buyers" select as relevencies to their project (separate the categories by a "~", except without the quotes).<br>
              Example: Tests~Demos~Uploads~Attachments<br>
              <textarea name="newcategories" rows="8" cols="52" wrap="off">' . $categories . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: pcat1<br>
              Description: If you want an extra category selection for "buyers" to select from when creating a project, provide the name of this extra category.<br>
              Example: Location<br>
              <input type="text" name="newpcat1" size="26" value="' . $pcat1 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: pcat1val<br>
              Description: If you chose to have an extra category above, provide the selections that can be made for that extra category (separate the categories by "***", except without the quotes).<br>
              Example: Washington, DC***Ottawa, ON***Mexico City, Mexico<br>
              <textarea name="newpcat1val" rows="8" cols="52" wrap="off">' . $pcat1val . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: pcat2<br>
              Description: If you want a second extra category selection for "buyers" to select from when creating a project, provide the name of that second extra category.<br>
              Example: Website Status<br>
              <input type="text" name="newpcat2" size="26" value="' . $pcat2 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: pcat2val<br>
              Description: If you chose to have a second extra category above, provide the selections that can be made for that second extra category (separate the categories by "***", except without the quotes).<br>
              Example: Active***Under Construction***Inactive<br>
              <textarea name="newpcat2val" rows="8" cols="52" wrap="off">' . $pcat2val . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: pcat3<br>
              Description: If you want a third extra category selection for "buyers" to select from when creating a project, provide the name of that third extra category.<br>
              Example: Project Size<br>
              <input type="text" name="newpcat3" size="26" value="' . $pcat3 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: pcat3val<br>
              Description: If you chose to have a third extra category above, provide the selections that can be made for that third extra category (separate the categories by "***", except without the quotes).<br>
              Example: Huge***Large***Average***Small***Tiny<br>
              <textarea name="newpcat3val" rows="8" cols="52" wrap="off">' . $pcat3val . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: balexpdays<br>
              Description: Specify the days when users will receive automatic warnings of a balance lower than ' . $currencytype . '' . $currency . '0.00 in their account (separate the categories by a "-", except without the quotes).  The last warning day should be 1 below the low balance maximum day.<br>
              Example: 25-26-27-28-29<br>
              <input type="text" name="newbalexpdays" size="37" value="' . $balexpdays . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: balmaxdays<br>
              Description: Specify the day when users will receive an automatic account suspension message (they will be suspended) of a balance lower than ' . $currencytype . '' . $currency . '0.00 in their account and will remain suspended until they add funds enough to make their account balance a positive number.<br>
              Example: 30<br>
              <input type="text" name="newbalmaxdays" size="4" value="' . $balmaxdays . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: tableborder<br>
              Description: The border that will be placed on all tables in PHP Freelancers.<br>
              Example: 0<br>
              <input type="text" name="newtableborder" size="3" value="' . $tableborder . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: tablecellsp<br>
              Description: The cell spacing amount that will be placed inbetween all cells on all tables in PHP Freelancers.<br>
              Example: 0<br>
              <input type="text" name="newtablecellsp" size="3" value="' . $tablecellsp . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: tablecellpa<br>
              Description: The cell padding amount that will be placed on all cells on all table in PHP Freelancers.<br>
              Example: 2<br>
              <input type="text" name="newtablecellpa" size="3" value="' . $tablecellpa . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: tablecolort<br>
              Description: The color of the text that will be placed on all headers of tables in PHP Freelancers (color codes are allowed).<br>
              Example: #84BCF4<br>
              <input type="text" name="newtablecolort" size="21" value="' . $tablecolort . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: tablecolorh<br>
              Description: The color of the header that will be placed on the top of all tables in PHP Freelancers (color codes are allowed).<br>
              Example: #000080<br>
              <input type="text" name="newtablecolorh" size="21" value="' . $tablecolorh . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: tablecolor1<br>
              Description: The background color of the first row of data shown on most tables in PHP Freelancers (color codes are allowed).<br>
              Example: #D6E4FE<br>
              <input type="text" name="newtablecolor1" size="21" value="' . $tablecolor1 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: tablecolor2<br>
              Description: The background color of the second row of data shown on most tables in PHP Freelancers (color codes are allowed).<br>
              Example: #84BCF4<br>
              <input type="text" name="newtablecolor2" size="21" value="' . $tablecolor2 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: ppemailaddr<br>
              Description: The PayPal email address that users pay when depositting money into their account (if you enabled PayPal deposits), and when the user clicks on the PayPal link, they will be taken to the referral signup referred by this email address (if you don\'t have a PayPal account, use the one shown in the example).<br>
              Example: user@domain.com<br>
              <input type="text" name="newppemailaddr" size="45" value="' . $ppemailaddr . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: deposit2checkout<br>
              Description: 2Checkout deposit mode configures PHP Freelancers to allow users to deposit money into their account by a secure 2Checkout credit card payment (you must first have a 2Checkout account).<br>
              Recommended: Enabled<br>
              <select name="newdeposit2checkout">
                <option ' . $yyy . ' value="enabled"> Enabled
                <option ' . $nnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositpaypal<br>
              Description: PayPal deposit mode configures PHP Freelancers to allow users to deposit money into their account by a secure PayPal credit or debit card payment (you must first have a PayPal account which you provided in the variable "ppemailaddr").<br>
              Recommended: Enabled<br>
              <select name="newdepositpaypal">
                <option ' . $yyyy . ' value="enabled"> Enabled
                <option ' . $nnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositnotify<br>
              Description: Deposit notify mode configures PHP Freelancers to send you an email whenever a user makes a PayPal (if enabled) or 2Checkout (if enabled) money deposit into their account.<br>
              Recommended: Enabled<br>
              <select name="newdepositnotify">
                <option ' . $yyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositccurl<br>
              Description: If you did not want to use 2Checkout, but do want to use some other credit card processor for deposits, provide the complete URL/web address here.  The deposit will not automatically <br>
              [amount] will be replaced by the total deposit amount including fees.<br>
              [username] will be replaced by the username of the user making the deposit.<br>
              [type] will be replaced by the user\'s account type.<br>
              Example: http://www.paymentworld.com/cgi-bin/purchase.cgi?uid=12345&total=[amount]&customer=[username]&acctype=[type]<br>
              <input type="text" name="newdepositccurl" size="63" value="' . $depositccurl . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositccper<br>
              Description: If you enabled 2Checkout or some other credit card processor for deposits, provide the percentage fee which will be added to the deposit amount before paying.<br>
              Example: 5.5<br>
              <input type="text" name="newdepositccper" size="6" value="' . $depositccper . '">%
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositccfee<br>
              Description: If you enabled 2Checkout or some other credit card processor for deposits, provide the pure dollar fee which will be added to the deposit amount before paying.<br>
              Example: 0.45<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newdepositccfee" size="6" value="' . $depositccfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositccpay<br>
              Description: If you enabled 2Checkout deposits, provide the URL to where the payment will be processed (DO NOT include the sid).  The URL should match the one supplied in the example but this is just incase it ever changes.<br>
              Example: https://www.2checkout.com/cgi-bin/buyers/cartpurchase1.2c?sid=<br>
              <input type="text" name="newdepositccpay" size="63" value="' . $depositccpay . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositccsid<br>
              Description: If you enabled 2Checkout deposits, you must provide your sid for the deposit amount to be transferred into your 2Checkout account when the user makes the payment.<br>
              Example: 14387<br>
              <input type="text" name="newdepositccsid" size="6" value="' . $depositccsid . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositccaut<br>
              Description: If you enabled 2Checkout deposits, the 2Checkout automatic deposit configures PHP Freelancers to get the amount (without fees) the user depositted and to automatically be added to the users\' account.<br>
              Recommended: Disabled<br>
              <select name="newdepositccaut">
                <option ' . $yyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositppper<br>
              Description: If you enabled PayPal deposits, provide the percentage fee which will be added to the deposit amount before paying.<br>
              Example: 2.9<br>
              <input type="text" name="newdepositppper" size="6" value="' . $depositppper . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositppfee<br>
              Description: If you enabled PayPal deposits, provide the pure dollar fee which will be added to the deposit amount before paying.<br>
              Example: 0.30<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newdepositppfee" size="6" value="' . $depositppfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositppaut<br>
              Description: If you enabled PayPal deposits, the PayPal automatic deposit configures PHP Freelancers to get the amount (without fees) the user depositted and to automatically be added to the users\' account.<br>
              Recommended: Disabled<br>
              <select name="newdepositppaut">
                <option ' . $yyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositmail<br>
              Description: Mail deposit mode configures PHP Freelancers to allow users to deposit money into their account by check (all information needed are the address variables and companyname).<br>
              Recommended: Enabled<br>
              <select name="newdepositmail">
                <option ' . $yyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: depositmlfee<br>
              Description: If you enabled check deposits, provide the pure dollar fee which will be added to the deposit amount before paying.<br>
              Example: 1<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newdepositmlfee" size="6" value="' . $depositmlfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: depositother<br>
              Description: Other deposit mode configures PHP Freelancers to allow users to deposit money into their account by some other deposit method you specify (if you enable this, be sure to upload a file named "payment.html" to the directory inwhich PHP Freelancers is, containing the details of the other deposit method, and how to use it).<br>
              Recommended: Disabled<br>
              <select name="newdepositother">
                <option ' . $yyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: feedbackcmax<br>
              Description: The maximum number of characters a user can type as feedback when providing feedback on another user.<br>
              Example: 500<br>
              <input type="text" name="newfeedbackcmax" size="6" value="' . $feedbackcmax . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: projectper<br>
              Description: The percentage of a "freelancers" bid that is charged as a project commission fee to their account, this is split in half if they are a "guarenteed programmer", and the commission fee that is charged is this or projectfee, whichever is greater.<br>
              Example: 12<br>
              <input type="text" name="newprojectper" size="3" value="' . $projectper . '">%
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: projectfee<br>
              Description: The pure dollar ammount that is charged as a project commission fee to the winning bidder\'s account, this is split in half if they are a "guarenteed programmer", and the commission fee that is charged is this or projectper, whichever is greater.<br>
              Example: 8<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newprojectfee" size="5" value="' . $projectfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: projectper2<br>
              Description: The percentage of a "freelancers" bid that is charged as a project commission fee to the project creator\'s account, this is split in half if they are a "guarenteed webmaster", and the commission fee that is charged is this or projectfee2, whichever is greater.<br>
              Example: 7<br>
              <input type="text" name="newprojectper2" size="3" value="' . $projectper2 . '">%
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: projectfee2<br>
              Description: The pure dollar ammount that is charged as a project commission fee to the project creator\'s account, this is split in half if they are a "guarenteed webmaster", and the commission fee that is charged is this or projectper2, whichever is greater.<br>
              Example: 2<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newprojectfee2" size="5" value="' . $projectfee2 . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawcfee<br>
              Description: The pure dollar amount that is charged (subtracted from the total withdrawal) when a user withdraws money from their account by check.<br>
              Example: 1<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newwithdrawcfee" size="3" value="' . $withdrawcfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: withdrawpaypal<br>
              Description: PayPal withdraw mode configures PHP Freelancers to allow users to withdraw money from their account by a secure PayPal credit or debit card payment (you must first have a PayPal account).<br>
              Recommended: Enabled<br>
              <select name="newwithdrawpaypal">
                <option ' . $yyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawpfee<br>
              Description: If you enabled the PayPal withdraw mode, provide the pure dollar amount that is charged (subtracted from the total withdrawal) when a user withdraws money from their account by PayPal.<br>
              Example: 1<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newwithdrawpfee" size="4" value="' . $withdrawpfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: withdrawwire<br>
              Description: Bank wire withdraw mode configures PHP Freelancers to allow users to withdraw money from their account by a secure bank wire payment (you must first have a bank account).<br>
              Recommended: Enabled<br>
              <select name="newwithdrawwire">
                <option ' . $yyyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawwfee<br>
              Description: If you enabled the bank wire withdraw mode, provide the pure dollar amount that is charged (subtracted from the total withdrawal) when a user withdraws money from their account by bank wire.<br>
              Example: 20<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newwithdrawwfee" size="6" value="' . $withdrawwfee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: withdrawother<br>
              Description: Other withdraw mode configures PHP Freelancers to allow users to withdraw money from their account by an other payment (you must first in most cases have an account with them).<br>
              Recommended: Enabled<br>
              <select name="newwithdrawother">
                <option ' . $yyyyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawofee<br>
              Description: If you enabled the other withdraw mode, provide the pure dollar amount that is charged (subtracted from the total withdrawal) when a user withdraws money from their account by other means.<br>
              Example: 1<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newwithdrawofee" size="6" value="' . $withdrawofee . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: withdrawonam<br>
              Description: If you enabled the other withdraw mode, provide the name of this other withdraw mode that users will see and be able to choose from.<br>
              Example: Western Union Transfer<br>
              <input type="text" name="newwithdrawonam" size="38" value="' . $withdrawonam . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawoins<br>
              Description: If you enabled the other withdraw mode, provide the instructions that users will have to follow of what to insert into an input box when they choose the other withdraw mode for a withdrawal.<br>
              Example: Provide your name and address please.<br>
              <textarea name="newwithdrawoins" rows="8" cols="52" wrap="off">' . $withdrawoins . '</textarea>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: withdrawmini<br>
              Description: Choose the minimum withdraw amount a user can specify to make a withdrawal.<br>
              Example: 25<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newwithdrawmini" size="5" value="' . $withdrawmini . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: withdrawnotify<br>
              Description: Withdraw notify mode configures PHP Freelancers to notify you whenever a user makes a withdrawal request.<br>
              Recommended: Enabled<br>
              <select name="newwithdrawnotify">
                <option ' . $yyyyyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: multiplecats<br>
              Description: Multiple category mode configures PHP Freelancers to allow "freelancers" to choose multiple categories to be experts of, and project creator\'s can choose multiple categories for their projects.<br>
              Recommended: Enabled<br>
              <select name="newmultiplecats">
                <option ' . $yyyyyyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: featuredcost<br>
              Description: This is the cost that will be subtracted from a user\'s account balance if they want their project to be listed as a featured one.
              Example: 10<br>
              ' . $currencytype . '' . $currency . '<input type="text" name="newfeaturedcost" size="5" value="' . $withdrawmini . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: mprojectdays<br>
              Description: The maximum number of days that a project creator can enter for how long they can wait for the project completion and when the project will expire.<br>
              Example: 90<br>
              <input type="text" name="newmprojectdays" size="5" value="' . $mprojectdays . '">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: projectudays<br>
              Description: The days when a project will be classed as "urgent" and will display an urgent image beside the project name.<br>
              Example: 0-1-2-3<br>
              This example shows that if the project only has 0-3 days until expiry, it will display the urgent image beside the name.<br>
              <textarea name="newprojectudays" rows="8" cols="52" wrap="off">' . $projectudays . '</textarea>">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: forumnextnum<br>
              Description: The number of subjects on the main message board to be showed per page.<br>
              Example: 25<br>
              <input type="text" name="newforumnextnum" value="' . $forumnextnum . '" size="3">
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: maxextend<br>
              Description: The maximum amount of days a project can be extended at a time.<br>
              Example: 7<br>
              <input type="text" name="newmaxextend" value="' . $maxextend . '" size="3">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: userportfolio<br>
              Description: User Portfolio mode configures PHP Freelancers to allow freelancers to upload images/screenshots of their previous works to their profile.<br>
              Recommended: Disabled<br>
              <select name="newuserportfolio">
                <option ' . $yyyyyyyyyyyyyyy . ' value="enabled"> Enabled
                <option ' . $nnnnnnnnnnnnnnn . ' value=""> Disabled
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" align="center" valign="middle">
              Name: refreturn1<br>
              Description: The URL to where a user will be redirected once they follow a Buyer referal link and a cookie has been set.<br>
              Example: http://' . $HTTP_HOST . '/buyers/<br>
              <input type="text" name="newrefreturn1" value="' . $refreturn1 . '" size="45">
            </td>
          </tr>
          <tr>
            <td bgcolor="#00ACCC" align="center" valign="middle">
              Name: refreturn2<br>
              Description: The URL to where a user will be redirected once they follow a Freelancer referal link and a cookie has been set.<br>
              Example: http://' . $HTTP_HOST . '/freelancers/<br>
              <input type="text" name="newrefreturn2" value="' . $refreturn2 . '" size="45">
            </td>
          </tr>
          <tr>
            <td bgcolor="#005075" align="center" valign="middle">
              <input type="submit" name="submit" value="Update Setup Variables">
            </td>
          </tr>
        </table>
      </form>
      <form action="admin.php" method="post">
        <input type="hidden" name="pass" value="' . $pass . '">
        <input type="submit" value="Back to Admin...">
      </form>
    </center>
  </body>
</html>';
} else {
SQLact("query", "DELETE FROM freelancers_cats");
SQLact("query", "UPDATE freelancers_setup SET siteurl='$newsiteurl', toplayout='$newtoplayout', bottomlayout='$newbottomlayout', emailheader='$newemailheader', emailfooter='$newemailfooter', emailaddress='$newemailaddress', companyname='$newcompanyname', fsbamount='$newfsbamount', bsbamount='$newbsbamount', frefamount='$newfrefamount', brefamount='$newbrefamount', address='$newaddress', city='$newcity', state='$newstate', zipcode='$newzipcode', currency='$newcurrency', currencytype='$newcurrencytype', buyer='$newbuyer', buyers='$newbuyers', freelancer='$newfreelancer', freelancers='$newfreelancers', mode='$newmode', adminpass='$newadminpass', attachmode='$newattachmode', attachmaxi='$newattachmaxi', attachpath='$newattachpath', attachurl='$newattachurl', catname='$newcatname', pcat1='$newpcat1', pcat1val='$newpcat1val', pcat2='$newpcat2', pcat2val='$newpcat2val', pcat3='$newpcat3', pcat3val='$newpcat3val', balexpdays='$newbalexpdays', balmaxdays='$newbalmaxdays', tableborder='$newtableborder', tablecellsp='$newtablecellsp', tablecellpa='$newtablecellpa', tablecolort='$newtablecolort', tablecolorh='$newtablecolorh', tablecolor1='$newtablecolor1', tablecolor2='$newtablecolor2', ppemailaddr='$newppemailaddr', deposit2checkout='$newdeposit2checkout', depositpaypal='$newdepositpaypal', depositnotify='$newdepositnotify', depositccurl='$newdepositccurl', depositccper='$newdepositccper', depositccfee='$newdepositccfee', depositccpay='$newdepositccpay', depositccsid='$newdepositccsid', depositccaut='$newdepositccaut', depositppper='$newdepositppper', depositppfee='$newdepositppfee', depositppaut='$newdepositppaut', depositmail='$newdepositmail', depositmlfee='$newdepositmlfee', depositother='$newdepositother', feedbackcmax='$newfeedbackcmax', projectper='$newprojectper', projectfee='$newprojectfee', projectper2='$newprojectper2', projectfee2='$newprojectfee2', withdrawcfee='$newwithdrawcfee', withdrawpaypal='$newwithdrawpaypal', withdrawpfee='$newwithdrawpfee', withdrawwire='$newwithdrawwire', withdrawwfee='$newwithdrawwfee', withdrawother='$newwithdrawother', withdrawofee='$newwithdrawofee', withdrawonam='$newwithdrawonam', withdrawoins='$newwithdrawoins', withdrawmini='$newwithdrawmini', withdrawnotify='$newwithdrawnotify', multiplecats='$newmultiplecats', featuredcost='$newfeaturedcost', mprojectdays='$newmprojectdays', projectudays='$newprojectudays', forumnextnum='$newforumnextnum', maxextend='$newmaxextend', userportfolio='$newuserportfolio', refreturn1='$newrefreturn1', refreturn2='$newrefreturn2'");
$categories = explode("~", $newcategories);
for ($i=0;$i<count($categories);$i++) {
SQLact("query", "INSERT INTO freelancers_cats (categories) VALUES ('" . $categories[$i] . "')");
}
echo '<center>
  Setup Variables successfully updated.<br>
  <form action="admin_setup.php" method="post">
    <input type="hidden" name="pass" value="' . $newadminpass . '">
    <input type="submit" value="Back to Setup Variables Customization...">
  </form><br><br>
  <form action="admin.php" method="post">
    <input type="hidden" name="pass" value="' . $pass . '">
    <input type="submit" value="Back to Admin...">
  </form>
</center>';
}
}
?>