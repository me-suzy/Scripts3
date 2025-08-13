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
   
*/

	include "./includes/messages.inc.php";
	include "./includes/config.inc.php";



	//--
	$auction_id = $sessionVars["CURRENT_ITEM"];

	include "header.php";

if ($REQUEST_METHOD=="GET" && !$action) 
{
	$query = "SELECT suspended FROM PHPAUCTION_users WHERE id='$id'";
	$result = mysql_query($query);
	if(!$result)
	{
		$TPL_errmsg = $ERR_001;
		$TPL_err = 1;
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
		include "templates/template_confirm_php.html";
	}
}


if ($REQUEST_METHOD=="POST" && $action == $MSG_249) 
{
	//-- User wants to confirm his/her registration
	
	$query = "UPDATE PHPAUCTION_users SET suspended=0 where id='$id'";
	$res = mysql_query($query);
	if(!$res)
	{
		$TPL_errmsg = $ERR_001;
		$TPL_err = 1;
	}
	
	include "templates/template_confirmed_php.html";
}

if ($REQUEST_METHOD=="POST" && $action == $MSG_250) 
{
	//-- User doesn't want to confirm hid/her registration
	$query = "DELETE FROM PHPAUCTION_users where id='$id'";
	$res = mysql_query($query);
	if(!$res)
	{
		$TPL_errmsg = $ERR_001;
		$TPL_err = 1;
	}
	

	//-- Get actual users and auctions counters
					$query = "select users from PHPAUCTION_counters";
					$result_counters = mysql_query($query);
					if(!$result_counters){
						$TPL_errmsg = $ERR_001;
					}else{
						$users_counter = mysql_result($result_counters,0,"users") - 1;
						
						//-- Update counters table
						
						$query = "update PHPAUCTION_counters set users = $users_counter";
					
						$result_update_counters = mysql_query($query);
						if(!$result_update_counters){
							$TPL_errmsg = $ERR_001;
						}
					}
							

	include "templates/template_confirmed_refused_php.html";
}


include "footer.php";

$TPL_err=0;
$TPL_errmsg="";
?>
