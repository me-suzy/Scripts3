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

//  We use the following features:
//  sess   for session variables
//  auth   for login checks, also required for user variables

// perform all startup functions
page_open(array(
	"sess" => "relata_session", 
	"auth" => "relata_challenge_Auth"
	));

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// start a new template
$template = start_template();

// set template file
$template->set_file("frameset","group_email.htm");

//new db connection
$db  = new relata_db();
$db2 = new relata_db();
$db3 = new relata_db();

$user_id = $auth->auth["uid"];

// select all available groups for this user
$db->query("select group_id, group_name, group_desc from $group where user_id='$user_id' order by group_id");

$i = 0;

while($db->next_record())
	{
	 // make the table alternating colors
	 if($i % 2 == 0)
	 	{
   		 $table .= "<tr bgcolor=\"darkgray\">";
  		}
 	 else
   		{
   		 $table .= "<tr bgcolor=\"silver\">";
  		}
	 $table .= '<td><span class="grouptext"><b>&nbsp;' . $db->f('group_name') . '&nbsp;</b></span></td>';
	 $table .= '<td><span class="grouptext">&nbsp;' . $db->f('group_desc') . '&nbsp;</span></td>';

	 // find out how many contacts are associated with this group
	$group_id = $db->f('group_id');
	$email_addresses     = "";
	$first_email_address = "";
	$cnt = 0;
 	$db2->query("select contact_id from $contact_group where group_id='$group_id'");
	while($db2->next_record())
	{
		$contact_id=$db2->f('contact_id');
    	for($j=1;$j<=5;$j++)
	 	{
      		$db3->query("select contact_val$j from $contact where contact_lbl$j='4' and contact_id = '$contact_id'");
      		$db3->next_record();
	  		if($db3->num_rows())
	   		{
				$temp="contact_val$j";
				if($cnt == 0)
				{
					if($db3->f($temp) != "")
					{
						$first_email_address=$db3->f($temp);
						$cnt++;
					}
					break;
				}
				else
				{
					if($db3->f($temp) != "")
					{
						$email_addresses.=$db3->f($temp);
						$email_addresses.=";";
					}
					break;
				}
	   		}
		}
	}

	 $table .= '<td align="center" class="grouptext">'.$db2->num_rows().'</td>';
	 $table .= '<td bgcolor="#D4D0C8">';
	 if($first_email_address != "")
	 	{
	 	$table .= ' <img src="../templates/images/email_norm.gif"
						alt="Click here to send an email to this contact group"
						onmouseover="this.src=\'../templates/images/email_roll.gif\'"
						onmouseout="this.src=\'../templates/images/email_norm.gif\'"
						onmousedown="this.src=\'../templates/images/email_down.gif\'"
						onClick="Mail=\'mailto:'.$first_email_address.'?bcc='.$email_addresses.'\'; window.location.href=Mail;" align="absmiddle">';
		}
	 $table .= '</td>';
	 $table .= '</tr>';
 	 $i++;
	}

$count = $db->num_rows();

$groups = "$table";

// set template variables
$template->set_var("GROUPS",$groups);
$template->set_var("ROWS",$count);
$template->set_var("SESSION_ID",$session_id);

// output the template
$template->parse("output","frameset");
$template->p("output");

page_close();

?>