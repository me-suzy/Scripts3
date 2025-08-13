<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	// Include messages file	
   require('./includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('./includes/config.inc.php');
   


require("header.php");

        $query = "select * from PHPAUCTIONPROPLUS_auctions where id='$id';";
	$result = mysql_query($query);
	if (!$result)
	{
		MySQLError($query);
		exit;
	}

	$user           = stripslashes(mysql_result ( $result, 0, "user" ));
	$title          = stripslashes(mysql_result ( $result, 0, "title" ));
	$buy_now_price  = mysql_result ( $result, 0, "buy_now" );
	$category 	= mysql_result ( $result, 0, "category" );

    #// If there are bids for this auction -> error
    $q = "select * from PHPAUCTIONPROPLUS_bids WHERE auction='$id'";
    $res = @mysql_query($q);

    if (mysql_num_rows($res) > 0)
    {
        $ERR = $ERR_712;
    }

	$TPL_seller = $user;
	$TPL_title_value = "Buy It Now";
	$TPL_title = $title;
	$TPL_buy_now_price = print_money($buy_now_price);

	/* get user's nick */
	$query      = "SELECT id,nick FROM PHPAUCTIONPROPLUS_users WHERE id='$user'";
	
	$result_usr = mysql_query ( $query );
	if ( !$result_usr )
	{
		print $MSG_001;
		exit;
	}
/*
	if(mysql_num_rows($result_usr) > 0)
	{
		$user_nick			= mysql_result ( $result_usr, 0, "nick");
		$user_id				= mysql_result ( $result_usr, 0, "id");
		$TPL_user_nick_value = $user_nick;
	}
*/
		$user_nick			= mysql_result ( $result_usr, 0, "nick");
		$user_id				= mysql_result ( $result_usr, 0, "id");
		$TPL_user_nick_value = $user_nick;

	 	   /* Get current number of feedbacks */
	   $query          = "SELECT rated_user_id FROM PHPAUCTIONPROPLUS_feedbacks WHERE rated_user_id='$user_id'";
	   $result         = mysql_query   ( $query );
	   $num_feedbacks  = mysql_num_rows ( $result );

	   /* Get current total rate value for user */
	   $query         = "SELECT rate_sum FROM PHPAUCTIONPROPLUS_users WHERE nick='$user_nick'";
	   $result        = mysql_query  ( $query );
	   if($result && mysql_num_rows($result) > 0)
	   	$total_rate    = mysql_result ( $result, 0, "rate_sum" );           

	   $TPL_vendetor_value = " <A HREF=\"profile.php?user_id=".$user_id."\"><b>".$TPL_user_nick_value."</b></A>";

	   if ( $num_feedbacks > 0 )
		{
			$rate_ratio = round( $total_rate / $num_feedbacks );
	   }
		else
		{
			$rate_ratio = 0;
	   }

		$TPL_rate_radio = "<IMG SRC=\"./images/estrella_".$rate_ratio.".gif\" ALIGN=TOP>";
			$TPL_num_feedbacks="<B>($num_feedbacks)</B>";


	if ($action == "buy")
		{
		// check if nickname and password entered
		if ( strlen($nick)==0 || strlen($password)==0 )
					$ERR = "610";

		// check if nick is valid
		$query = "SELECT * FROM PHPAUCTIONPROPLUS_users WHERE nick='".addslashes($nick)."'";

			$result = mysql_query($query);
		
			$n = 0;
			if ($result)
					$n = mysql_num_rows($result);
			else
					$ERR = "001";

					if ($n==0)
							$ERR = "609";
			if($n > 0)
			{
				$bidder_id = mysql_result($result,0,"id");

		// check if password is correct
				$pwd = mysql_result($result,0,"password");
				if ($pwd != md5($MD5_PREFIX.$password))
				{
						$ERR = "611";
				}
				else
				{
					if(mysql_result($result,0,"suspended") > 0)
					{
						$ERR = "618";
					}
				}
			}
		// check if buyer is not the seller
		if ($nick == $user_nick)
			{
				$ERR = "711";
			}

		// perform final actions
		if ( isset($ERR) )
		{
				$TPL_errmsg = ${"ERR_".$ERR};
		}

			if ($ERR == "") 
			{ 
			$query = "UPDATE PHPAUCTIONPROPLUS_auctions set current_bid='$buy_now_price' where id='$id'";
			$result = mysql_query($query);
				if (!$result)
				{
				MySQLError($query);
				exit;
				}
			$now = date ("YmdHms");
			$query = "UPDATE PHPAUCTIONPROPLUS_auctions set ends='$now' where id='$id'";
			$result = mysql_query($query);
				if (!$result)
				{
				MySQLError($query);
				exit;
				}
			$query = "INSERT INTO PHPAUCTIONPROPLUS_bids VALUES ('$id', '$bidder_id', '$buy_now_price', NULL, 0)";
			$result = mysql_query($query);
				if (!$result)
				{
				MySQLError($query);
				exit;
				}


			include ("cron.php");
			$buy_done = 1;
			}
			
		}


        include "templates/template_buy_now_php.html";
?>


<? require("./footer.php"); ?>
</BODY>
</HTML>
