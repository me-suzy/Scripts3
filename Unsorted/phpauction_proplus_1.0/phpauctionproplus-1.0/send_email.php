<?#//v.1.0.0

/*

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	*/



	// Include messages file	

	require('./includes/messages.inc.php');



	// Connect to sql server & inizialize configuration variables

	require('./includes/config.inc.php');





    $auction_id = $sessionVars["CURRENT_ITEM"];


	//--Get item description

	$query = "select user,title from PHPAUCTIONPROPLUS_auctions where id='".AddSlashes($auction_id)."'";
	$result = mysql_query($query);
	if(!$result)
	{
		MySQLError($query);
		exit;
	}

	$seller_id = stripslashes(mysql_result($result,0,"user"));
	$item_title = stripslashes(mysql_result($result,0,"title"));

	//--Get seller data

	$query = "select nick,email from PHPAUCTIONPROPLUS_users where id='".AddSlashes($seller_id)."'";
	$result = mysql_query($query);
	if(!$result)
	{
		MySQLError($query);
		exit;
	}

	$seller_nick = stripslashes(mysql_result($result,0,"nick"));
	$seller_email = stripslashes(mysql_result($result,0,"email"));


	$TPL_auction_id = "".$auction_id;

	$TPL_seller_nick_value = $seller_nick;

	$TPL_seller_email_value = $seller_email;

	$TPL_sender_name_value = $sender_name;

	$TPL_sender_email_value = $sender_email;

	$TPL_item_title = $item_title;

	$TPL_sender_question = $sender_question;


	if (empty($action))

	{
		include "header.php";
		include "templates/template_send_email_php.html";
		include "footer.php";
		exit;
	}



	//--Check errors

	if	(

			$action && 

			(!$sender_name || !$sender_email || !$seller_nick || !$seller_email)

		)

	{

		$TPL_error_text = $ERR_032;

	}



	if	(

			$action &&

			(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$sender_email) ||

			!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$seller_email))

		)

	{

		$TPL_error_text = $ERR_008;

	}


	if ( strlen($TPL_error_text)>0 )

	{

		include "header.php";

		include "templates/template_send_email_php.html";

		include "footer.php";

		exit;

	}
	else
	{
	$MSG = "$MSG_337: <i>$seller_nick</i><br><br>";
	}



	//-- Send e-mail message

$message = "Hello $seller_nick,

This message is sent from $SETTINGS[sitename].

$sender_name had this question(s) about you auction $item_title :

$sender_question
";

mail($seller_email,"$MSG_650",$message,"From:$sender_name <$sender_email>\nReplyTo:$sender_email"); 

	include "header.php";
	include "templates/template_send_email_php.html";
	include "footer.php";

	exit;	

	?>

