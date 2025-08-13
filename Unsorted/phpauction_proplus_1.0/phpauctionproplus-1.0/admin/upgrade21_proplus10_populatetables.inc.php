<?#//v.1.0.1
session_start();   //missing session vars. MH.
set_magic_quotes_runtime(1);

if($HTTP_SESSION_VARS[DB] == "new")
{
		include $HTTP_SESSION_VARS[CURRENT_PATH]."/includes/passwd.inc.php";
		$CURRENT_DB_HOST = $DbHost; 
		$CURRENT_DB_NAME = $DbDatabase;
		$CURRENT_DB_USER = $DbUser;
		$CURRENT_DB_PASSWORD = $DbPassword;

		$NEW_DB_HOST = $DB_HOST; 
		$NEW_DB_NAME = $DB_NAME;
		$NEW_DB_USER = $DB_USER;
		$NEW_DB_PASSWORD = $DB_PASSWORD;
}
else
{
		$CURRENT_DB_HOST = $DB_HOST; 
		$CURRENT_DB_NAME = $DB_NAME;
		$CURRENT_DB_USER = $DB_USER;
		$CURRENT_DB_PASSWORD = $DB_PASSWORD;

		$NEW_DB_HOST = $DB_HOST; 
		$NEW_DB_NAME = $DB_NAME;
		$NEW_DB_USER = $DB_USER;
		$NEW_DB_PASSWORD = $DB_PASSWORD;
}
	
if(!@mysql_connect($CURRENT_DB_HOST,$CURRENT_DB_USER,$CURRENT_DB_PASSWORD)) //fixed error with $CURRENT_DB_PASSWORD (typo).MH.
{
		print "*ERROR*: cannot connect to <FONT FACE=Courier>$CURRENT_DB_HOST</FONT><BR>";
		exit;
}
else
{
		set_time_limit(1000);
		
		#//Admin users
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_adminusers";
		$R = mysql_query($query);
		if($R)
		{
			  print "Importing data into PHPAUCTIONPROPLUS_adminusers<BR>";
     flush();
			  $N = mysql_select_db($NEW_DB_NAME);
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_adminusers VALUES(
						            '$c[id]',
						            '$c[username]',
						            '$c[username]',
						            '$c[created]',
						            '$c[lastlogin]',
						            '$c[status]')";
				    if(!mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into adminusers table<BR>";
				    }
			  }
		}
		
		#//Auctions
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_auctions";
		$R = @mysql_query($query);
		if($R)
		{
			 $N = mysql_select_db($NEW_DB_NAME);
			 print "Importing data into PHPAUCTIONPROPLUS_auctions<BR>";
    flush();
			
			 while($c = mysql_fetch_array($R))
			 {
				   $query = "insert into PHPAUCTIONPROPLUS_auctions VALUES(
						           '$c[id]',
						           '$c[user]',
						           '$c[title]',
						           '$c[starts]',
						           '$c[description]',
						           '$c[pict_url]',
						           '$c[category]',
						           '$c[minimum_bid]',
						           '$c[reserve_price]',
						           '$c[auction_type]',
						           '$c[duration]',
						           '$c[increment]',
						           '$c[location]',
						           '$c[location_zip]',
						           '$c[shipping]',
						           '$c[payment]',
						           '$c[international]',
						           '$c[ends]',
						           '$c[current_bid]',
						           '$c[closed]',
						           '$c[photo_uploaded]',
						           '$c[quantity]',
						           '$c[suspended]')";
			  	 if(!@mysql_query($query))
				   {
					     print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into auctions table<BR>\n";
				   }
			 }
		}
		
		#//bids
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_bids";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_bids<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_bids VALUES(
						            '$c[auction]',
						            '$c[bidder]',
						            '$c[bid]',
						            '$c[bidwhen]',
						            '$c[quantity]')";
				    if(!mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into bids table<BR>\n";
				    }
			}
		}
		
		#//categories
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_categories";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_categories<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
			    	$query = "insert into PHPAUCTIONPROPLUS_categories VALUES(
						            '$c[cat_id]',
						            '$c[parent_id]',
						            '$c[cat_name]',
						            '$c[deleted]',
						            '$c[sub_counter]',
						            '$c[counter]',
						            '$c[cat_color]',
						            '$c[cat_image]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into categories table<BR>\n";
				    }
			  }
		}
		
		#//categories_plain
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_categories_plain";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_categories_plain<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_categories_plain VALUES(
						            '$c[id]',
						            '$c[cat_id]',
						            '$c[cat_name]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into categories_plain table<BR>\n";
				    }
		   }
  }
		
		#//counters
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_counters";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_counters<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
			    	$query = "insert into PHPAUCTIONPROPLUS_counters VALUES(
						            '$c[users]',
						            '$c[auctions]',
						            '0',
						            '0',
						            '0',
						            '0',
						            '0',
						            '0',
						            '0',
						            '0')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into counters table<BR>\n";
				    }
			  }
		}
		
		#//feedbacks
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_durations";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_counters<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_durations VALUES(
						            '$c[days]',
						            '$c[description]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into durations table<BR>\n";
				    }
			  }
		}
		
		#//feedbacks
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_feedbacks";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_counters<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_feedbacks VALUES(
						            '$c[rated_user_id]',
						            '$c[rated_user_nick]',
						            '$c[feedback]',
						            '$c[rate]',
						            '$c[feedbackdate]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into feedbacks table<BR>\n";
				    }
			  }
		}
		
		#//help
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_help";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_help<BR>";flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_help VALUES(
						            '$c[topic]',
						            '$c[text]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into help table<BR>\n";
				    }
			  }
		}
		
		#//increments
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_increments";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_increments<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_increments VALUES(
						            '$c[id]',
						            '$c[low]',
						            '$c[high]',
						            '$c[increment]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into increments table<BR>\n";
				    }
			  }
		}
		
		#//news
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_news";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_news<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_news VALUES(
						            '$c[id]',
						            '$c[title]',
						            '$c[content]',
						            '$c[new_daate]',
						            '$c[suspended]')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into news table<BR>\n";
				    }
			  }
		}
		
		#//payments
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_payments";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_payments<BR>";flush();
			  while($c = mysql_fetch_array($R))
			  {
				     $query = "insert into PHPAUCTIONPROPLUS_payments VALUES(
						             '$c[id]',
						             '$c[description]')";
				     if(!@mysql_query($query))
				     {
					       print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into payments table<BR>\n";
			      }
			  }
		}
		
		#//requests
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_request";
		$R = @mysql_query($query);
		if($R)
		{
			$N = mysql_select_db($NEW_DB_NAME);
			print "Importing data into PHPAUCTIONPROPLUS_request<BR>";flush();
			while($c = mysql_fetch_array($R))
			{
				$query = "insert into PHPAUCTIONPROPLUS_request VALUES(
						  '$c[req_auction]',
						  '$c[req_user]',
						  '$c[req_test]',
						  '$c[req_date]'
							)";
				if(!@mysql_query($query))
				{
					print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into request table<BR>\n";
				}
			}
		}
		
		#//settings
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_settings";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_settings<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_settings VALUES(
						            '$c[sitename]',
						            '$c[siteurl]',
						            '$c[cookiesprefix]',
						            '$c[loginbox]',
						            '$c[newsbox]',
						            '$c[newstoshow]',
						            '$c[moneyformat]',
						            '$c[moneydecimals]',
						            '$c[moneysymbol]',
						            '$c[currency]',
						            '$c[showacceptancetext]',
						            '$c[acceptancetext]',
						            '$c[adminmail]',
						            '$c[err_font]',
						            '$c[std_font]',
						            '$c[sml_font]',
						            '$c[tlt_font]',
						            '$c[nav_font]',
						            '$c[footer_font]',
						            '$c[bordercolor]',
						            '$c[headercolor]',
						            '$c[tableheadercolor]',
						            '$c[linkscolor]',
						            '$c[vlinkcolor]',
						            '$c[banners]',
						            '$c[newsletter]',
						            '$c[logo]',
						            '0',
						            '2',
						            '60',
						            'USA',
						            'pay',
						            '2',
						            '0',
						            '0',
						            '2',
						            '1',
						            '0',
						            '2',
						            '1',
						            '0',
						            '',
						            'An error occured during your transaction',
						            '',
						            '2',
						            '0')";
				    if(!@mysql_query($query))
				    {
					      print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into settings table<BR>\n";
			     }
			  }
		}
		
		#//users
		$C = mysql_select_db($CURRENT_DB_NAME);
		$query = "select * from PHPAUCTION_users";
		$R = @mysql_query($query);
		if($R)
		{
			  $N = mysql_select_db($NEW_DB_NAME);
			  print "Importing data into PHPAUCTIONPROPLUS_users<BR>";
     flush();
			  while($c = mysql_fetch_array($R))
			  {
				    $query = "insert into PHPAUCTIONPROPLUS_users VALUES(
						            '$c[id]',
						            '$c[nick]',
						            '$c[password]',
						            '$c[name]',
						            '$c[address]',
						            '$c[city]',
						            '$c[prov]',
						            '$c[country]',
						            '$c[zip]',
						            '$c[phone]',
						            '$c[email]',
						            '$c[reg_date]',
						            '$c[rate_sum]',
						            '$c[rate_num]',
						            '$c[birthdate]',
						            '$c[suspended]',
						            '$c[nletter]',
						            '0',
						            '',
						            '')";
				    if(!@mysql_query($query))
				    {
				      	print "*ERROR* $query<BR>".mysql_error()."<BR>while inserting into users table<BR>\n";
				    }
			  }
		}
}
?>
