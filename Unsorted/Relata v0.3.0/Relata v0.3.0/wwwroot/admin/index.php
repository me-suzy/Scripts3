<?

//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************

include("../config.inc.php");

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// start a new template
$template = start_template();

// set template file
$template->set_file("main","admin_main.htm");

// open a db connection
$db = new relata_db;

// find out how many sales stages there are
$db->query("SELECT ss_id FROM $sales_stage");
while($db->next_record())
{
	$ss[] = $db->f('ss_id');
}
if(count($ss))
{
	$num_salesstages = max($ss);
}

// if the user clicked on 'delete' for a user
if($action == "delete" && isset($user_id))
{
	$db->query("DELETE FROM $relata_user WHERE user_id='$user_id'");
	$db->query("DELETE FROM $account WHERE user_id='$user_id'");
	$db->query("DELETE FROM $activity WHERE user_id='$user_id'");
	$db->query("DELETE FROM $contact WHERE user_id='$user_id'");
	$db->query("DELETE FROM $extra_field WHERE user_id='$user_id'");
	$db->query("DELETE FROM $group WHERE user_id='$user_id'");
	$db->query("DELETE FROM $opportunity WHERE user_id='$user_id'");
}
// delete a sales stage
else if($action == "delete" && isset($ss_id))
{
	$db->query("DELETE FROM $sales_stage WHERE ss_id='$ss_id';");
	$db->query("UPDATE $opportunity SET ss_id='0' WHERE ss_id='$ss_id'");
}

// update sales stages
for($i = 1; $i < $num_salesstages; $i++)
{
	if($action == "  OK  ")
	{
		$ssid_var = "ss_id" . $i;
		
		// if there is already a record... update else insert
		$db->query("SELECT ss_id FROM $sales_stage WHERE ss_id='$i'");
		if($db->num_rows())
		{
			$db->query("UPDATE $sales_stage SET ss_title='${$ssid_var}' WHERE ss_id='$i';");
		}
		else
		{
			$db->query("INSERT INTO $sales_stage (ss_id,ss_title) VALUES('$i','${$ssid_var}');");
		}
	}
}

// update user list
$db->query("SELECT login,password,user_id FROM $relata_user ORDER BY login ASC");
while($db->next_record())
{
	if($action == "  OK  ")
	{
		$login_var = "login" . $db->f('user_id');
		$password_var = "password" . $db->f('user_id');
		$user_id = $db->f('user_id');
		
		$db->query("UPDATE $relata_user SET login='${$login_var}', password='${$password_var}' WHERE user_id='$user_id'");
	}
}

// if the user entered in a new sales stage to be added
if($ss_new)
{
	// get the next possible id
	$num_salesstages++;
	
	$db->query("INSERT into $sales_stage (ss_title,ss_id) VALUES ('$ss_new','$num_salesstages')");
}

// if the user entered in username / password ( user ) to be added to the system
if($new_login && $new_password)
{
	// get the current date / time
	$today = today();
	$now = now();
	
	// keep trying new random numbers until we find one that works
	$add_new_user = false;
	while($add_new_user == false)
	{
		// get a random number between one and a billion and take the md5 hash of it
		srand((double)microtime() * 1000000);
		$new_user_id = md5(rand(0,1000000000));
		
		// make sure the user_id hasn't already been taken
		$db->query("SELECT user_id FROM $relata_user WHERE user_id='$new_user_id'");
		if(!$db->num_rows())
		{
			$db->query("INSERT INTO $relata_user (user_id,login,password,login_date,login_time) VALUES ('$new_user_id','$new_login','$new_password','$today','$now');");
			$add_new_user = true;
		}
	}
}

for($i = 1; $i <= $num_salesstages; $i++)
{
	// get a list of all the sales stages
	$db->query("SELECT ss_id,ss_title FROM $sales_stage WHERE ss_id='$i' ORDER BY ss_id ASC");
	$db->next_record();
	
	$sales_stages .= "
		<tr>
		<td class=\"form\"><a href=\"$PHP_SELF?session_id=$session_id&ss_id=$i&action=delete\">Delete</a></td>
		<td class=\"form\"><input type=\"text\" size=\"20\" class=\"form\" name=\"ss_id$i\" value=\"" . $db->f('ss_title') . "\" class=\"form\" /></td>
	 	</tr>
		";
}

// build a list of all of the users
$db->query("SELECT * FROM $relata_user");
while($db->next_record())
{
	$users .=
	"
	 <tr>
		<td class=\"form\"><a href=\"$PHP_SELF?session_id=$session_id&user_id=".$db->f('user_id')."&action=delete\">Delete</a></td>
		<td class=\"form\">
			<input type=\"text\" size=\"10\" class=\"form\" name=\"login" . $db->f('user_id') . "\" value=\"".$db->f('login')."\" class=\"form\" />
		</td>
		<td class=\"form\">
			<input type=\"password\" size=\"10\" class=\"form\" name=\"password".$db->f('user_id')."\" value=\"".$db->f('password')."\" class=\"form\" />
		</td>
	 </tr>
	";
}

// set template variables
$template->set_var(array(
	"SALES_STAGES"	=>	$sales_stages,
	"USERS"			=>	$users,
	"SESSION_ID"	=>	$session_id,
	"PHP_SELF"		=>	$PHP_SELF
));

// print the template
$template->parse("output","main");
$template->p("output");

?>