<?
   header("Cache-Control: no-cache, must-revalidate, proxy-revalidate");
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); // always modified
   header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
   header("Pragma: no-cache");

   include( "../config.php" );  
   include( "../usersession.inc" );

   UpdateUserSession();
?>

<html>
<head>
<title>My Account</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
	ValidateLoginRedirect();
?>
</head>

<body bgcolor="#FFFFFF">
<?
	include( "../header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td> 
      <blockquote> 
        <p>&nbsp;</p>
        <table width="75%" border="0" cellspacing="0" cellpadding="3">
          <tr bgcolor="#FFCC00"> 
            <td><font face="Arial, Helvetica, sans-serif" size="3"><b>Account 
              Options</b></font></td>
          </tr>
        </table>
        <ul>
          <li><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><font size="2"><a href="NewAd.php">Post 
            New Ad</a></font></b><font size="2"> - post a new auction or classified 
            ad </font></font></li>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><a href="ViewMyAds.php">View 
            My Ads</a></b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2"> 
            - view auctions and/or classified ads that you've posted</font></font></li>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><a href="ViewMyBids.php">View 
            My Bids</a></b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2"> 
            - view auctions that you've placed bids for</font></font></li>
        </ul>
        <ul>
          <li><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="PrivateMsgs.php">View 
            Messages</a></font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
            </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2">- 
            view messages that other users have sent you</font></font><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
            </font></li>
        </ul>
        <ul>
          <li><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="UpdateProfile.php">Update 
            Account Profile</a> </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2">- 
            change your account information</font></font></li>
        </ul>
        <ul>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><a href="Logout.php">Logout</a> 
            </b></font><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><font size="2">- 
            logout of the system</font></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b> 
            </b></font></li>
        </ul>
      </blockquote>
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
