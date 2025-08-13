<?
   include( "../config.php" );
   include( "../usersession.inc" );
   UpdateUserSession();
   include( "../dblink.inc" );
?>

<html>
<head>
<title>New Auction Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?
	ValidateLoginRedirect();
?>
<style type="text/css">
<!--
.submitButton
{
    FONT-WEIGHT: 400;
    FONT-SIZE: 80%;
    BORDER-LEFT-COLOR: #FBEDBB;
    BORDER-BOTTOM-COLOR: #FBEDBB;
    COLOR: white;
    BORDER-TOP-COLOR: #FBEDBB;
    BACKGROUND-COLOR: #E08900;
    BORDER-RIGHT-COLOR: #FBEDBB
}
-->
</style>
</head>
<script language='JavaScript'>
<!--
function isNumber(theElement, theElementName)
{
  s = theElement.value;
  if ( (s == "") || (isNaN(Math.abs(s)) && (s.charAt(0) != '#')))
  {
	alert( theElementName +  " must be a number." );
    theElement.focus(); 
    return false;
  }
  return true;
}

function Validate() 
{
if ( document.NewAd.Title.value=="" ) {
	alert( "Please enter a title" );
	document.NewAd.Title.focus();
	return false;
}
if ( document.NewAd.Title.value.length > 255 ) {
	alert( "The title is too long.  Maximum length is 255 characters." );
	document.NewAd.Title.focus();
	return false;	
}
if ( document.NewAd.StartPrice.value=="" ) {
	alert( "Please enter a starting price" );
	document.NewAd.StartPrice.focus();
	return false;
}
if ( !isNumber(document.NewAd.StartPrice, "Starting Price" ) ) {
	return false;
}
if ( document.NewAd.ReservePrice.value=="" ) {
	alert( "Please enter a reserve price" );
	document.NewAd.ReservePrice.focus();
	return false;
}
if ( !isNumber(document.NewAd.ReservePrice, "Reserve Price" ) ) {
	return false;
}
if ( document.NewAd.Quantity.value=="" ) {
	alert( "Please enter a quantity" );
	document.NewAd.Quantity.focus();
	return false;
}
if ( !isNumber(document.NewAd.Quantity, "Quantity" ) ) {
	return false;
}
if ( document.NewAd.Description.value=="" ) {
	alert( "Please enter a description" );
	document.NewAd.Description.focus();
	return false;
}
if ( document.NewAd.Category.value=="None" ) {
	alert( "Please select a category" );
	document.NewAd.Category.focus();
	return false;
}
return true;
}

	var PreviewAd = null;
	function Preview() 
	{
		if ( !Validate() )
			return false;

		PreviewAd = open("","preview","toolbar=yes,resizable=yes,scrollbars=yes,directories=no,menubar=no,width=780,height=550");
		document.NewAd.action = "PreviewAuctionAd.php";
		document.NewAd.target = "preview";
		return true; 
	}

	function SubmitAuctionAd()
	{
		if ( !Validate() )
			return false;

	    if ( !window.confirm( "Submit this ad? Make sure that you've previewed it first!" ) )
			return false;

		document.NewAd.action = "NewAuctionAd.php";
		document.NewAd.target = "";
		document.NewAd.submit();
		return true;
	}
//-->
</script>
<?
	$SubmitValidate = true;
	if ( isset($Title) || isset($Quantity) ||
		 isset($StartPrice) || isset($ReservePrice) || 
		 isset($Description) || isset($Category) )
	{
		if ( ($Title=="") || ($Quantity=="") || ($StartPrice=="") || ($ReservePrice=="") ||
			 ($Description=="") || (strcmp($Category, "None") == 0) )
		{
			$SubmitValidate = false;			
			print( "<body bgcolor=\"#FFFFFF\">\n" );
		}
		else
		{
			print("<BODY onload=\"document.SubmitNewAd.submit();\" bgcolor=\"#FFFFFF\">\n");			
		}
	}
?>

<form action="SubmitNewAuctionAd.php" method="post" name="SubmitNewAd">
	<input type="hidden" name="Title" value="<? echo htmlentities($Title); ?>">
	<input type="hidden" name="StartPrice" value="<? echo htmlentities($StartPrice); ?>">
	<input type="hidden" name="ReservePrice" value="<? echo htmlentities($ReservePrice); ?>">
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
            New Auction Ad</font></b></font></td>
        </tr>
        <tr> 
          <td width="30">&nbsp;</td>
          <td width="*"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><br>
            To post a new auction ad please fill in the following information:</font></td>
        </tr>
        <tr>
          <td width="30">&nbsp;</td>
          <td width="*"> <br>
<?
	if ( !$SubmitValidate )
		print("<p><font color=\"#FF0033\">Please enter in all required fields before submitting.</font></p>\n");
?>
		  </td>
        </tr>
      </table>
<br>

<form action="NewAuctionAd.php" method="post" name="NewAd">          
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
            <td><b><font face="Arial, Helvetica, sans-serif">Start Price </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the starting price of your item below. This is the price that the 
              auction will begin at.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td align="right">$</td>
            <td> 
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
              the reserve price of your item below. The reserve price is the minimum 
              amount you will accept for the auction, but the reserve price is 
              not visible to bidders.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td align="right">$</td>
            <td>
<input type="text" name="ReservePrice" size="15" maxlength="30" 
					<? $str = "value=\"";
					   if ( !isset( $ReservePrice ) )
					     $str .= "0.00";
					   else
						 $str .= htmlentities($ReservePrice);
					   $str .= "\"";
					   print("$str"); ?> >
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Quantity </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Enter 
              the number of items you're selling below. Currently, dutch-style 
              bidding is not supported. Bidders will be bidding for the entire 
              quantity listed.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font><font face="Arial, Helvetica, sans-serif" size="1"> </font></td>
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
                          </font><font face="Arial, Helvetica, sans-serif" size="1"> 
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
            <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font size="2">Please 
              preview your ad before submitting to make sure it's how you want 
              it to look. Once submitted, the auction ad cannot be deleted or 
              modified. </font></b></font><font face="Arial, Helvetica, sans-serif" size="2"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <table width="100%" border="0" cellspacing="0" cellpadding="1">
                <tr> 
                  <td width="150"> 
                    <input type="submit" class="submitButton" name="PreviewSubmit" value="Preview Ad" onClick='return Preview();'>
                  </td>
                  <td> 
                    <input type="submit" class="submitButton" name="Submit" value="Submit Ad" onclick='return SubmitAuctionAd();'>
                  </td>
                </tr>
              </table>
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
