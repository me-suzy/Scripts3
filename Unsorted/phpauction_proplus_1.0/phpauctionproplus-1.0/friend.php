<?#//v.1.0.0

/*

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


		templates/templates used:

			templates/template_friend_php.html

				$TPL_auction_id

				$TPL_error_text

				$TPL_friend_name_value

				$TPL_friend_email_value

				$TPL_sender_name_value

				$TPL_sender_email_value

				$TPL_item_description

				$TPL_sender_comment_value

	*/



	// Include messages file	

	require('./includes/messages.inc.php');



	// Connect to sql server & inizialize configuration variables

	require('./includes/config.inc.php');





    $auction_id = $sessionVars["CURRENT_ITEM"];



	$TPL_error_text = "";

	$TPL_auction_id = "".$auction_id;

	$TPL_friend_name_value = "".$friend_name;

	$TPL_friend_email_value = "".$friend_email;

	$TPL_sender_name_value = "".$sender_name;

	$TPL_sender_email_value = "".$sender_email;

	$TPL_item_description = "";

	$TPL_sender_comment_value = "".$sender_comment;



	//--Get item description

	$query = "select description,title,category from PHPAUCTIONPROPLUS_auctions where id='".AddSlashes($auction_id)."'";

	$result = mysql_query($query);

	if(!$result)
	{
		MySQLError($query);
		exit;
	}

	$item_description = stripslashes(mysql_result($result,0,"description"));
	$item_title = stripslashes(mysql_result($result,0,"title"));
  	$item_category = mysql_result($result,0,"category");

	$TPL_item_description = "".$item_description;



	if (empty($action))

	{

		include "header.php";

		include "templates/template_friend_php.html";

		include "footer.php";

		exit;

	}



	//--Check errors

	if	(

			$action && 

			(!$sender_name || !$sender_email || !$friend_name || !$friend_email)

		)

	{

		$TPL_error_text = $ERR_032;

	}



	if	(

			$action &&

			(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$sender_email) ||

			!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$friend_email))

		)

	{

		$TPL_error_text = $ERR_008;

	}


	if ( strlen($TPL_error_text)>0 )

	{

		include "header.php";

		include "templates/template_friend_php.html";

		include "footer.php";

		exit;

	}



	//-- Send e-mail message
   	include('./includes/friend_confirmation.inc.php');

    //-- Display confirmation web page
	include "header.php";
	include "templates/template_friend_confirmation_php.html";
	include "footer.php";

	exit;	

	?>

