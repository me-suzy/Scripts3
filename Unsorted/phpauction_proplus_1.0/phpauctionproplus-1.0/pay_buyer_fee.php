<?#//v.1.0.0
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

	#// Get record from the WINNERs table
	$query = "select * from PHPAUCTIONPROPLUS_winners 
		      WHERE
		      winner='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN]' 
		      AND
		      auction='$HTTP_GET_VARS[auction]'";
	$res = @mysql_query($query);
	if(!$res)
	{
		MySQLError($query);
		exit;
	}
	elseif(mysql_num_rows($res) == 0)
	{
		include "header.php";
		include "templates/template_pay_buyerfee_error_php.html";
		include "footer.php";
	}
	else
	{
           	#// Calculate buyer's FEE
            if($SETTINGS[buyerfinaltype] == 1)
            {
            	$PRICE = doubleval(mysql_result($res,0,"bid"));
                $AMOUNT = ($PRICE / 100) * doubleval($SETTINGS[buyerfinalvalue]);
            }
            else
            {
	         	#// _____FIX FEE______else
                $AMOUNT  = doubleval($SETTINGS[buyerfinalvalue]);
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
include "templates/template_pay_buyerfee_php.html";
include "./footer.php"; 

?>
</BODY>
</HTML>
