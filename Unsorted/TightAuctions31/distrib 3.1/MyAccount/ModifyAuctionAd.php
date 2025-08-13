<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );

	// Check to make sure that the ad belongs to the user

	$UserAcctID = GetSessionUserID();		

	$query = "SELECT Title, Description, CatID, StartPrice, ReservePrice, Quantity FROM AuctionAds WHERE UserAcctID=$UserAcctID AND AdID=$AdID";
	$result = mysql_query( $query, $link );		

	if ( $row = mysql_fetch_row( $result ) )
	{
		// Make sure that there has been no bids for the ad

		$query = "SELECT BidID FROM Bids WHERE AdID=$AdID";
		$NumBidsResult = mysql_query( $query, $link );		

		if ( mysql_num_rows($NumBidsResult) != 0 )
		{
$BeginHeader = <<<BEGINHEADER
<html>
<head>
<title>Invalid Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
BEGINHEADER;

	print( "$BeginHeader\n" );

	ValidateLoginRedirect();

$EndHeader = <<<ENDHEADER
</head>
<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p><font color="#FF0033">The ad has bids and cannot be modified.</font></p>
</body>
</html>
ENDHEADER;

		print( "$EndHeader\n" );
		exit;				
		}

		// Load the ad values if we're not initially bringing up the page to modify

		if ( !isset($action) )
		{
			$Title = $row[0];
			$Description = $row[1];
			$Category = $row[2];		
			$StartPrice = $row[3];
			$ReservePrice = $row[4];
			$Quantity = $row[5];
		}

$BeginHeader = <<<BEGINHEADER
<html>
<head>
<title>Modify Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
BEGINHEADER;

		print( "$BeginHeader\n" );
	
		ValidateLoginRedirect();

$EndHeader = <<<ENDHEADER
</head>
<body bgcolor="#FFFFFF">
ENDHEADER;

		print( "$EndHeader\n" );

	}
	else
	{
		// Invalid ad -- the Ad ID doesn't exist for the user or at all

$BeginHeader = <<<BEGINHEADER
<html>
<head>
<title>Invalid Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
BEGINHEADER;

	print( "$BeginHeader\n" );

	ValidateLoginRedirect();

$EndHeader = <<<ENDHEADER
</head>
<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p><font color="#FF0033">The ad to modify is invalid. Go back to <a href="MyAccount.php">My 
  Account</a> and select the ad to modify from there.</font></p>
</body>
</html>
ENDHEADER;

		print( "$EndHeader\n" );
		exit;				
	}

?>

<SCRIPT LANGUAGE="JavaScript">
<!-- 
function previewAd()
{
	var winNewAd = window.open("../ViewAuctionAd.php?AdID=" + <? echo $AdID; ?> + "&mode=preview", "previewAd", "width=750,height=450, scrollbars=yes, resizable=yes");	
	winNewAd.focus();
}

function updateAd()
{
	document.SubmitModifyAd.action="ModifyAuctionAd.php?action=update";
	document.SubmitModifyAd.submit();
}

function disableAd()
{
    if ( window.confirm( "Disable this ad?" ) )
    {
		document.SubmitModifyAd.action="SubmitModifyAuctionAd.php?action=disable";
		document.SubmitModifyAd.submit();
	}
}

function renewAd()
{
    if ( window.confirm( "Renew this ad?" ) )
    {
		document.SubmitModifyAd.action="SubmitModifyAuctionAd.php?action=renew";
		document.SubmitModifyAd.submit();
	}
}

function deleteAd()
{
    if ( window.confirm( "Delete this ad?" ) )
    {
		document.SubmitModifyAd.action="SubmitModifyAuctionAd.php?action=delete";
		document.SubmitModifyAd.submit();
	}
}

//-->
</SCRIPT>

<?
	include( "../header.inc" );
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td> 
        <p>&nbsp;</p>        
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="30">&nbsp;</td>
          <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">Modify 
            Auction Ad</font></b></font></td>
        </tr>
        <tr> 
          <td width="30">&nbsp;</td>
          <td width="*"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><br>
            Update, renew, disable, or delete your auction ad. </font></td>
        </tr>
        <tr> 
          <td width="30">&nbsp;</td>
          <td width="*"> <br>
<?
	if ($action == "update")
	{
		if ( ($Title=="") || ($StartPrice=="") || ($ReservePrice=="") || ($Quantity=="") || 
			 ($Description=="") || (strcmp($Category, "None") == 0) )
		{
			print("<p><font color=\"#FF0033\">Please enter in all required fields.</font></p>\n");
		}
		else
		{
			$Title2 = addslashes($Title);
			$Description2 = addslashes($Description);
			$StartPrice2 = addslashes($StartPrice);
			$ReservePrice2 = addslashes($ReservePrice);
			$Quantity2 = addslashes($Quantity);

			$IP = getenv("REMOTE_ADDR"); 

			// update the ad

			$query = "UPDATE AuctionAds SET CatID=$Category, Title='$Title2', Description='$Description2', StartPrice='$StartPrice2', ReservePrice='$ReservePrice2', Quantity='$Quantity2', ModifiedIPAddress='$IP'";
			$query .= "WHERE AdID=$AdID";
			$result = mysql_query( $query, $link );
		
			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing update ad query.</font></h3></body></html>\n");    
				echo "<br>\n";
				echo mysql_error();		
				exit;
			}
		}
	}
?>
</td>
        </tr>
      </table>
<br>
  <form action="SubmitModifyAuctionAd.php" method="post" name="SubmitModifyAd">
	<input type="hidden" name="AdID" value=<? echo htmlentities($AdID); ?> >          
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><b><font face="Arial, Helvetica, sans-serif">Title </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the title of your product below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <input type="text" name="Title" size="50" maxlength="255" 
					<? $str = "value=\"";
					   $str .= htmlentities($Title);
					   $str .= "\"";
					   print("$str"); ?> >
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Start Price </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the starting price of your product below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> $ 
              <input type="text" name="StartPrice" size="15" maxlength="30" 
					<? $str = "value=\"";
					   $str .= htmlentities($StartPrice);
					   $str .= "\"";
					   print("$str"); ?> >
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Reserve Price </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the starting price of your product below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>$ 
              <input type="text" name="ReservePrice" size="15" maxlength="30" 
					<? $str = "value=\"";
					   $str .= htmlentities($ReservePrice);
					   $str .= "\"";
					   print("$str"); ?> >
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Quantity </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the number of items you're selling below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>
              <input type="text" name="Quantity" size="15" maxlength="30" 
					<? $str = "value=\"";
					   $str .= htmlentities($Quantity);
					   $str .= "\"";
					   print("$str"); ?> >
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Description </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the description of your product below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td> 
                    <textarea name="Description" cols="50" rows="30">
<? 
//$Description = htmlspecialchars($Description);
//$Description = ereg_replace('&amp;', '&', $Description);
//$Description = nl2br($Description);
echo $Description; 
?>
</textarea>
                  </td>
                  <td valign="top" width="*"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="10">
                      <tr> 
                        <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><i>Tips: 
                          </i><br>
                          - Use HTML tags to make your ads look better! <br>
                          - To include pictures in your ads, upload the picture 
                          to your website and link to it from your ad's HTML code 
                          with the &lt;img&gt; tag.</font><font face="Arial, Helvetica, sans-serif" size="1"> 
                          </font></td>
                      </tr>
                    </table>
                    <p></p>
                    <p>&nbsp;</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Category </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Select 
              a category to place your ad in below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <select name="Category">
                <option value="None">Select a Category</option>
                <?

function GetCategories( &$HTML, $Parent, $ParentStr, $link )
{	
	global $Category;

	$query = "SELECT CategoryID, Name FROM Categories WHERE ParentCatID=$Parent ORDER BY Name";
	$result = mysql_query( $query, $link );		
	
	while ( $row = mysql_fetch_row( $result ) )
	{
		$val = $ParentStr;
		if ( $Parent != -1 )
			$val .= ">";
		$val .= $row[1];

		if ( $Category == $row[0] )
		{
			$HTML .= "<option SELECTED value=\"$row[0]\">$val</option>\n";
		}
		else
		{
			$HTML .= "<option value=\"$row[0]\">$val</option>\n";
		}

		GetCategories( $HTML, $row[0], $val, $link );
	}
}

	$HTML = "";
	GetCategories( $HTML, -1, "", $link );

	print( "$HTML" );
?> 
              </select>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><a href="javascript:updateAd();"><font face="Arial, Helvetica, sans-serif"><b><font size="2"><font size="3">Update 
              Ad</font></font></b></font></a>&nbsp;&nbsp;&nbsp;&nbsp;<font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
              (<a href="javascript:previewAd();">Click here to preview ad in another 
              window.</a>)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><a href="javascript:renewAd();"><font face="Arial, Helvetica, sans-serif"><b><font size="2"> 
              <font size="3">Renew Ad</font></font></b></font></a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><a href="javascript:disableAd();"><font face="Arial, Helvetica, sans-serif"><b><font size="2"><font size="3">Disable 
              Ad</font></font></b></font></a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><a href="javascript:deleteAd();"><font face="Arial, Helvetica, sans-serif"><b><font size="2"><font size="3">Delete 
              Ad</font></font></b></font></a></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><a href="MyAccount.php"><font face="Arial, Helvetica, sans-serif"><b><font size="2"> 
              <font size="3">Go Back to My Accounts</font></font></b></font></a> 
            </td>
          </tr>
        </table>
</form>
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
