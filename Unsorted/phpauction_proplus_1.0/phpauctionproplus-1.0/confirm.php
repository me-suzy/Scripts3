<?#//v.1.0.0

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";



	//--
	$auction_id = $sessionVars["CURRENT_ITEM"];

	include "header.php";

if ($REQUEST_METHOD=="GET" && !$action) 
{
	$query = "SELECT suspended FROM PHPAUCTIONPROPLUS_users WHERE id='$id'";
	$result = mysql_query($query);
	if(!$result)
	{
		MySQLError($query);
		exit;
	}
	elseif(mysql_num_rows($result) ==0)
	{
		$TPL_errmsg = $ERR_025;
		$TPL_err = 1;
	}
	elseif(mysql_result($result,0,suspended) == 0)
	{
		$TPL_errmsg = $ERR_039;
		$TPL_err = 1;
	}
	
	if($TPL_err)
	{
		include "templates/template_confirm_error_php.html";
	}
	else
	{
		if($SETTINGS[signupfee] == 1)
		{
			session_name($SESSION_NAME);
			$NEWUSER_ID = $id;
			session_register("NEWUSER_ID");
			
			include "templates/template_confirm_fee_php.html";
		}
		else
		{
			include "templates/template_confirm_php.html";
		}
	}
}


if ($REQUEST_METHOD=="POST" && $action == $MSG_249) 
{
	//-- User wants to confirm his/her registration
	
	$query = "UPDATE PHPAUCTIONPROPLUS_users SET suspended=0 where id='$id'";
	$res = mysql_query($query);
	if(!$res)
	{
		MySQLError($query);
		exit;
	}

/* Update column users inactiveusers in table PHPAUCTIONPROPLUS_counters */
	$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET users=(users+1),inactiveusers=(inactiveusers-1)");



	include "templates/template_confirmed_php.html";
}

if ($REQUEST_METHOD=="POST" && $action == $MSG_250) 
{
	//-- User doesn't want to confirm hid/her registration
	$query = "DELETE FROM PHPAUCTIONPROPLUS_users where id='$id'";
	$res = mysql_query($query);
	if(!$res)
	{
		MySQLError($query);
		exit;
	}
	/* Update column inactiveusers in table PHPAUCTIONPROPLUS_counters */
	$counteruser = mysql_query("UPDATE PHPAUCTIONPROPLUS_counters SET inactiveusers=(inactiveusers-1)");
	include "templates/template_confirmed_refused_php.html";
}


include "footer.php";

$TPL_err=0;
$TPL_errmsg="";
?>
