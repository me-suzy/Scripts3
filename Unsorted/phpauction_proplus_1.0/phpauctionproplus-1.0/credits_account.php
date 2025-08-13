<?#//v.1.0.0
	//include "lib/check-key.php";

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');
   
	#// If user is not logged in redirect to login page
	if(!isset($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"]))
	{
		Header("Location: user_login.php");
		exit;
	}
	
	
	#// Retrieve account info
	$query = "SELECT * FROM PHPAUCTIONPROPLUS_accounts WHERE user='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' ORDER BY id DESC";
	$res = mysql_query($query);
	if(!$res)
	{
		MySQLError($query);
		exit;
	}
	else
	{
		while($row = mysql_fetch_array($res))
		{
			$OPERATION_DATE[$row[id]] = $row[operation_date];
			$OPERATION_DESCR[$row[id]] = $row[description];
			$OPERATION_TYPE[$row[id]] = $row[operation_type];
			$OPERATION_AMOUNT[$row[id]] = $row[opeartion_amount];
			$OPERATION_AUCTION[$row[id]] = $row[auction];
			$BALANCE[$row[id]] = $row[account_balance];
		}
	}
   

	
?>

<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" >

<?

require("header.php");

#// Inlude the template according to the payments type in the $SETTINGS array (pre,prepay)
if($SETTINGS[feetype] == "prepay")
{
	include "templates/template_credits_account_php.html";
}
else
{
	include "templates/template_credits_account_pay_php.html";
}

include "./footer.php"; 

?>
</BODY>
</HTML>
