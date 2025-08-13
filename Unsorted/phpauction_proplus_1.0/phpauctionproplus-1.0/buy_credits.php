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
	if(!isset($PHPAUCTION_LOGGED_IN))
	{
		Header("Location: user_login.php");
		exit;
	}
	

	if($HTTP_POST_VARS[action] == "buy")
	{
		#// Check amount
		if(!CheckMoney($HTTP_POST_VARS[amount]))
		{
			$ERR = $ERR_058;
		}
		else
		{
			#// Prepare data for Paypal
			$AMOUNT = input_money($HTTP_POST_VARS[amount]);
		}
	}
	
	
	
	#// Retrieve user's balance only if the user is logged in
	if(!empty($HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]) && $SETTINGS[signupfee] == 1)
	{
		$query = "SELECT balance FROM PHPAUCTIONPROPLUS_users WHERE id='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]'";
	    $res = mysql_query($query);
	    if(!$res)
	    {
			MySQLError($query);
			exit;
		}
		else
		{
			$BALANCE = mysql_result($res,0,"balance");
		}
	}

	$USER_ID = $HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN];

?>

<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" >

<?

	if(isset($AMOUNT))
	{
		require("header.php");
		include "templates/template_buy_credits_paypal_php.html";
		include "./footer.php"; 
	}
	else
	{
		require("header.php");
		include "templates/template_buy_credits_php.html";
		include "./footer.php"; 
	}
?>
</BODY>
</HTML>
