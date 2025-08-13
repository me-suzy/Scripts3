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
	
	
?>

<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" >

<?
	require("header.php");
	include "templates/template_buy_credits_cancelled_php.html";
	include "./footer.php"; 
?>
</BODY>
</HTML>
