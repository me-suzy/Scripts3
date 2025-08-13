<?



/*



   Copyright (c), 1999, 2000 - phpauction.org                  

   

   This program is free software; you can redistribute it and/or modify 

   it under the terms of the GNU General Public License as published by 

   the Free Software Foundation (version 2 or later).                                  

                                                                        

   This program is distributed in the hope that it will be useful,      

   but WITHOUT ANY WARRANTY; without even the implied warranty of       

   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        

   GNU General Public License for more details.                         

                                                                        

   You should have received a copy of the GNU General Public License    

   along with this program; if not, write to the Free Software          

   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

   





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

	$query = "select description,title,category from PHPAUCTION_auctions where id='".AddSlashes($auction_id)."'";

	$result = mysql_query($query);

	if(!$result){

		$TPL_error_text = $ERR_001;

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

