<?
    include( "config.php" );
	include("usersession.inc");			
	UpdateUserSession();
    include( "dblink.inc" );	

	$ValidMember = false;

	if ( isset($MemberID ) )
	{
		$MemberID2 = addslashes($MemberID);
		$query = "SELECT UserAccountID, DateRegistered FROM UserAccounts WHERE MemberID='$MemberID2'";
		$result = mysql_query( $query, $link );		

		if ( $row = mysql_fetch_row( $result ) )
		{
			$ValidMember = true;
			$ForUserAcctID = $row[0];
			$MemberDateRegistered = date( "M j, Y", $row[1] );
		}
	}

	$UserAcctID = GetSessionUserID();			

	if ( !$ValidMember )
	{
		print( "<html><head><title>Invalid Member</title></head><body>\n" );
		print( "<br><br><center><h3>The member account is invalid.</h3></center></body></html>\n" );
		exit;
	}

	if ( $ForUserAcctID == $UserAcctID )
	{
		print( "<html><head><title>Feedback Error</title></head><body>\n" );
		print( "<br><br><center><h3>Cannot submit feedback for yourself.</h3></center></body></html>\n" );
		exit;
	}

	$ErrorMsg = "";
	if ( isset($Rating) || isset($Role) || isset($Comment) )
	{
		if ( ($Rating=="") || ($Role=="") )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Please enter in all required fields before submitting.</font></p>\n";
		}
		else if ( !(($Rating >= 1) && ($Rating <= 5)) )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Please select a rating before submitting.</font></p>\n";
		}
		else if ( ($Role != "Buyer") && ($Role != "Seller") )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Please select whether the user is a buyer or seller.</font></p>\n";
		}
		else if ( strlen($Comment) > 100 )
		{
			$ErrorMsg = "<p><font color=\"#FF0033\">Comment cannot be greater than 100 characters.</font></p>\n";
		}

		if (($ErrorMsg == "") && ($UserAcctID != -1) && ($ValidMember))
		{
			$TimeNow = time();
			$IP = getenv("REMOTE_ADDR"); 

			$Comment = addslashes($Comment);

			if ( $Role == "Buyer" )
				$ForUserRole = 0;
			else
				$ForUserRole = 1;

			$query = "INSERT INTO Feedback (ForUserAcctID, ByUserAcctID, Comment, Rating, ForUserRole, FeedbackDate, CreatedIPAddress) ";
			$query .= "VALUES ($ForUserAcctID, $UserAcctID, '$Comment', $Rating, $ForUserRole, $TimeNow, '$IP')";
			$result = mysql_query( $query, $link );		
		
			if ( !$result )
			{
				print("<h3><font color=\"#FF0033\">Error executing insert ad query.</font></h3></body></html>\n");    
				exit;
			}
		}
	}
?>
<html>
<head>
<title>Leave Feedback</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta name="description" content="Get updated with the latest Internet news!  Place classified ads, auctions, advertising, auctions, and have fun searching for computers, electronics, hardware, software, CDs, antiques, and other items.">
<meta name="keywords" content="free, classified, auction, free for all, advertising, listings, ad, cool, fun, website, news, Internet, computer, electronics, hardware, software, CD, shopping, news, forum">
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- 
function viewMember(ToMemberID)
{
	var newWin = window.open("viewmember.php?ToMemberID=" + ToMemberID, "viewmember", "width=500,height=450, scrollbars=1, resizable=yes");	
	newWin.focus();
}
//-->
</SCRIPT>
<body bgcolor="#FFFFFF">
<?
	include( "header.inc" );
?><br>
<?
	if ( $UserAcctID == -1 )
	{
		print( "<p align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>&gt;" );
		print( " You must be <a href=\"signin.php\">signed in</a> to leave feedback. " );
		print( "&lt;</b></font></p>\n" );
		print( "</body></html>\n" );
		exit;
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td> 
      <form action="LeaveFeedback.php?MemberID=<? echo $MemberID; ?>" method="post" name="Feedback">
        <table width="100%" border="0" cellspacing="2" cellpadding="4">
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><font face="Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#003399">Leave 
              Feedback </font></b></font></td>
          </tr>
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Once 
              a comment has been placed, it cannot be removed or edited by <? echo $SiteName ?>. 
              Please fill out the form below carefully and double check to make 
              sure that everything is the way you want it to be before submitting.</font></td>
          </tr>
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><?echo $ErrorMsg;?></td>
          </tr>
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"> 
              <hr noshade>
            </td>
          </tr>
          <tr> 
            <td width="30">&nbsp;</td>
            <td width="*"><b><font face="Arial, Helvetica, sans-serif">Feedback 
              For: <a href="javascript:viewMember('<? echo $MemberID; ?>')"><? echo $MemberID; ?></a></font></b></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Rating </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Select 
              a rating for the member below where 5 = highest and 1 = lowest.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <select name="Rating" size="1">
                <option selected value="">Select Rating</option>
                <option value=5>5</option>
                <option value=4>4</option>
                <option value=3>3</option>
                <option value=2>2</option>
                <option value=1>1</option>
              </select>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Buyer/Seller </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Select 
              whether the member you're leaving feedback for was the buyer or 
              seller in the transaction.)</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <select name="Role" size="1">
                <option selected value="">Select Member's Role</option>
                <option value="Buyer">Buyer</option>
                <option value="Seller">Seller</option>
              </select>
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><b><font face="Arial, Helvetica, sans-serif">Comment </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Leave 
              a comment below. 100 characters maximum. )</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <input type="text" name="Comment" size="100" maxlength="100">
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <input type="submit" name="Submit" value="Submit Feedback">
              <b><font face="Arial, Helvetica, sans-serif"> </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(Press 
              the submit button only once. )</font><font face="Arial, Helvetica, sans-serif" size="1"> 
              </font> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> 
              <hr noshade>
            </td>
          </tr>
        </table>
      </form>
      
    </td>
  </tr>
</table>
<br>
<?
	include( "footer.inc" );
?> 
