<?
	include( "../config.php" );
	include( "../usersession.inc" );
	UpdateUserSession();
?>

<html>
<head>
<title>New Ad</title>
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
        <p><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">Ad 
          Type Selection </font></b></font></p>
        <p><font face="Arial, Helvetica, sans-serif" size="2">Select the type 
          of ad that you wish to place. </font></p>
        <ul>
          <li type="square"> <font face="Arial, Helvetica, sans-serif" size="4"><a href="NewAuctionAd.php">Auction 
            style</a> </font></li>
          <li><font face="Arial, Helvetica, sans-serif" size="4"><a href="NewClassifiedAd.php">Classified 
            style</a></font></li>
        </ul>
        <ul>
          <li type="square"><font face="Arial, Helvetica, sans-serif" size="2"><a href="MyAccount.php">Cancel 
            New Ad Placement and Go Back to My Account</a></font></li>
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
