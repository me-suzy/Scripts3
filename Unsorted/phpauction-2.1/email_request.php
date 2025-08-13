<?php


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
   


*/




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

			$query = "select email from PHPAUCTION_users where id='$user_id'";

			$result = mysql_query($query);

			if(!$result)

			{

				print $ERR_001;

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
