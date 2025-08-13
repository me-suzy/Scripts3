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
   
	if($HTTP_POST_VARS[action] == "buy")
	{
		#// Check amount
		if(!CheckMoney($HTTP_POST_VARS[amount]))
		{
			$ERR = $ERR_058;
		}
		else
		{
			#// Amount must be equal or greater than the sign up fee
			if($HTTP_POST_VARS[amount] < $SETTINGS[signupvalue])
			{
				$ERR = print_money(input_money($HTTP_POST_VARS[amount]))."&nbsp;".$ERR_120;
			}
			else
			{
				#// Prepare data for Paypal
				$AMOUNT = input_money($HTTP_POST_VARS[amount]);
			}
		}
	}
	
	
	
	#// Retrieve user's balance
	$query = "SELECT balance FROM PHPAUCTIONPROPLUS_users WHERE id='$HTTP_SESSION_VARS[NEWUSER_ID]'";
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
		include "templates/template_newuser_buy_credits_paypal_php.html";
		include "./footer.php"; 
	}
	else
	{
		require("header.php");
		include "templates/template_newuser_buy_credits_php.html";
		include "./footer.php"; 
	}
?>
</BODY>
</HTML>
