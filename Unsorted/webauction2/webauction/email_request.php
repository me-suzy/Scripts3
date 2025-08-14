<?php
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu



		templates used:
			1) templates/email_request_form.html
				vars:	$TPL_error_text
						$TPL_nick_value (nick)
						$TPL_user_value (user)
							(password)
			2) templates/email_request_result.html
				vars:	$TPL_nick_value
						$TPL_email_value
	*/

	require "includes/config.inc.php";
	require('includes/messages.inc.php');
	
	//-- Get auction_id from sessions variables
	
	$auction_id = $sessionVars["CURRENT_ITEM"];
	
	if ($REQUEST_METHOD=="GET")
	{
		/*
			display form
		*/
		$TPL_id_value = $auction_id;

		include "header.php";
		include "templates/email_request_form.html";
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
		
		/* prepare variables for template */
		$TPL_error_text = "";
		$TPL_nick_value = htmlspecialchars($nick);
		$TPL_id_value = htmlspecialchars($user_id);

		/* check for username and password from form */
		$err = 0; /* error code */

			/* first check if such user exists */
		$query = "SELECT * FROM ".$dbfix."_users WHERE nick='".AddSlashes($nick)."'";
		$result = mysql_query ($query);
		if ($result)
			$n = mysql_num_rows($result);
		else	
			$n = 0;

		if ($n==0)
			$err = 1;

			/* check if password is correct */
		if ($err==0)
		{
			$pass = mysql_result ($result,0,"password");
			if ($password!=$pass)
				$err = 2;
		}

			/* perform different actions depending of value of $err */
		if ($err!=0)
		{
			/* display form with error */
			if ($err==1)
				$TPL_error_text = "Usuario desconocido";
			elseif ($err==2)
				$TPL_error_text = "Falta la contraseña";

			include "header.php";
			include "templates/email_request_form.html";
			include "footer.php";
			exit;
		}
		else
		{
			/* display requested email */
			include "header.php";

			$query = "SELECT * FROM ".$dbfix."_users WHERE id='".AddSlashes($user_id)."'";
			
			$result = mysql_query ($query);
			if ( $result )
				$n = mysql_num_rows($result);
			else
				$n = 0;

			if ($n!=0)
			{
				$TPL_nick_value = mysql_result ($result,0,"nick");
				$TPL_email_value = mysql_result ($result,0,"email");
				
			}

			

			include "templates/email_request_result.html";
			include "footer.php";
			exit;
		}
	}
?>
