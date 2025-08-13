<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>

<html>
<head>
<title>New Classified Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?
	ValidateLoginRedirect();
?>
</head>

<?
	if ( ($Title != "") && ($Price != "") && ($Quantity != "") &&
		 ($Description != "") && (strcmp($Category, "None") != 0) )
	{
		print("<BODY onload=\"document.SubmitNewAd.submit();\" bgcolor=\"#FFFFFF\">\n");
	}
	else
	{
		print( "<body bgcolor=\"#FFFFFF\">\n" );
	}
?>

<form action="SubmitNewClassifiedAd.php" method="post" name="SubmitNewAd">
	<input type="hidden" name="Title" value="<? echo htmlentities($Title); ?>">
	<input type="hidden" name="Price" value="<? echo htmlentities($Price); ?>">
	<input type="hidden" name="Quantity" value="<? echo htmlentities($Quantity); ?>">
	<input type="hidden" name="Description" value="<? echo htmlentities($Description); ?>">
	<input type="hidden" name="Category" value="<? echo htmlentities($Category); ?>">
</form>

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
          <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">Post 
            New Classified Ad</font></b></font></td>
        </tr>
        <tr> 
          <td width="30">&nbsp;</td>
          <td width="*"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><br>
            To post a new classified ad please fill in the following information:</font></td>
        </tr>
        <tr>
          <td width="30">&nbsp;</td>
          <td width="*"> <br>
<?
	if ( isset($Title) || isset($Price) || isset($Quantity) ||
		isset($Description) || isset($Category) )
	{
		if ( ($Title=="") || ($Price=="") || ($Quantity == "") &&
			 ($Description=="") || (strcmp($Category, "None") == 0) )
		{
			print("<p><font color=\"#FF0033\">Please enter in all required fields before submitting.</font></p>\n");
		}
	}
?>
		  </td>
        </tr>
      </table>
<br>

      <form action="SubmitNewClassifiedAd.php" method="post" name="NewAd">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><b><font face="Arial, Helvetica, sans-serif">Title </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the title of your item below. Limit to 255 characters.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
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
            <td><b><font face="Arial, Helvetica, sans-serif">Price </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the price of your item below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td align="right">$ </td>
            <td> 
              <input type="text" name="Price" size="15" maxlength="30" 
					<? $str = "value=\"";
					   $str .= htmlentities($Price);
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
              the description of your item below.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr valign="top"> 
            <td>&nbsp;</td>
            <td> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td> 
                    <textarea name="Description" cols="50" rows="30">
<? echo htmlentities($Description); ?>
</textarea>
                  </td>
                  <td valign="top" width="*"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="10">
                      <tr valign="top"> 
                        <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><i>Tips: 
                          </i><br>
                          - Use HTML tags to make your ads look better! <br>
                          - To include pictures in your ads, upload the picture 
                          to your website and link to it from your ad's HTML code 
                          with the &lt;img&gt; tag.</font><font face="Arial, Helvetica, sans-serif" size="1"> 
                          <br>
                          </font><font face="Verdana, Arial, Helvetica, sans-serif" size="1">- 
                          After submitting your new ad, you can later go back 
                          to modify your ad. Just go to your <a href="MyAccount.php">My 
                          Account</a> section, click on <a href="ViewMyAds.php">View 
                          My Ads</a>, and then click on the ad to modify. </font><font face="Arial, Helvetica, sans-serif" size="1"> 
                          </font><font face="Arial, Helvetica, sans-serif" size="1"> 
                          </font></td>
                      </tr>
                    </table>
                    <p><font face="Arial, Helvetica, sans-serif" size="1"> </font></p>
                  </td>
                </tr>
              </table>
              <font face="Arial, Helvetica, sans-serif" size="1"> </font> </td>
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
            <td><a href="javascript:document.NewAd.submit()"><font face="Arial, Helvetica, sans-serif"><b><font size="2"><font size="3">Post 
              Your Ad!</font></font></b></font></a></td>
          </tr>
        </table>
</form>

        <p>&nbsp;</p>
    </td>
  </tr>
</table>
<?
	include( "../footer.inc" );
?>
</body>
</html>
