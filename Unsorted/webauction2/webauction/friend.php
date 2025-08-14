<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu


		templates used:
			templates/friend_php3.html
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
	require('includes/messages.inc.php');

	// Connect to sql server & inizialize configuration variables
	require('includes/config.inc.php');


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
	$query = "select description from ".$dbfix."_auctions where id='".AddSlashes($auction_id)."'";
	$result = mysql_query($query);
	if(!$result){
		$TPL_error_text = $ERR_001;
		exit;
	}
	$item_description = mysql_result($result,0,"description");
	$TPL_item_description = "".$item_description;

	if (empty($action))
	{
		include "header.php";
		include "templates/friend_php3.html";
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
		$TPL_error_text = "Al menos una de las direcciones e-mail no es correcta";
	}

	if ( strlen($TPL_error_text)>0 )
	{
		include "header.php";
		include "templates/friend_php3.html";
		include "footer.php";
		exit;
	}

	//-- Send e-mail message

	require('includes/friendmail.inc.php');

	$subject = "Check this auction at $SITE_NAME";
	$from = "From:$sender_name <$sender_email>";
	mail($friend_email,$subject,$message,$from);

	include "header.php";
	include "templates/friend_confirmation_php3.html";
	include "footer.php";
	exit;	
	?>
