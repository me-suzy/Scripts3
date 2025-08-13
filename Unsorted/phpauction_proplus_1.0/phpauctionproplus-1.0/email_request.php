<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	require "./includes/config.inc.php";
	require('./includes/messages.inc.php');
	
	//-- Get auction_id from sessions variables
	
	$auction_id = $sessionVars["CURRENT_ITEM"];
	
	if ($REQUEST_METHOD=="GET")
	{
		/*
			display form
		*/
		$TPL_id_value = $auction_id;

		include "header.php";
		include "templates/template_email_request_form.html";
		include "footer.php";
		exit;
	}
	else
	{
		/*
			check username/password
				if correct: display user's email
				if incorrect: display form once again
		*/
		
		if(!$TPL_sender_name || !$TPL_sender_mail || !$TPL_subject || !$TPL_text)
		{
			$TPL_error_text = $ERR_116;
			include "header.php";
			include "templates/template_email_request_form.html";
			include "footer.php";
			exit;
		}
		else
		{
			//-- Send e-mail message
			$query = "select email from PHPAUCTIONPROPLUS_users where id='$user_id'";
			$result = mysql_query($query);
			if(!$result)
			{
				MySQLError($query);
				exit;
			}
			else
			{
				$email = mysql_result($result,0,"email");
			}
			
			$from = "From: $TPL_sender_name <$TPL_sender_mail>\n";
			$subject = "$MSG_335 $SETTINGS[sitename] $MSG_336 $auction_id";
			mail($email,$subject,$TPL_subject."\n\n".$TPL_text,$from);
			include "header.php";
			include "templates/template_email_request_result.html";
			include "footer.php";
			exit;
			
		}
			

	}
?>
