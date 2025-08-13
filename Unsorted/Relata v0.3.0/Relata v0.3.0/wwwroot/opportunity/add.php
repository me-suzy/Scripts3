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

$db = new relata_db;

$template->set_file("main","opportunity_main.htm");

$user_id = $auth->auth["uid"];

if($action == "   OK   ")
{
	$entry_date = date("Y-m-d");

	// if there is no sales stages defined set it to default 0
	if(!$ss_id)
	{
		$ss_id = 0;
	}
	
	// insert the actual opportunity
	$db->query("INSERT INTO $opportunity (user_id,opp_title,entry_date,close_date,opp_desc,ss_id,potential_revenue,probability) VALUES ('$user_id','$opp_title','$entry_date','$close_date','$opp_desc','$ss_id','$potential_revenue','$probability')");
	
	// insert the link record that associates the contact with this opportunity

	$opp_id = $db->currid("opportunity_seq");
	
	$cnt = count($opp_contacts);
	for($i = 0;$i < $cnt; $i++)
	{
		$db->query("INSERT INTO $contact_opportunity (contact_id,opp_id) VALUES ('$opp_contacts[$i]','$opp_id')");
	}
	
	echo '<script type="text/javascript">';
	// reload the left frame
	echo "eval('parent.parent.left' + parent.parent.winid + '.location.reload();');";
		
	// select the just added button in left frame if there is one to select
	if(!$no_records)
	{
		echo "parent.parent.curbtn = $opp_id;";
	}
	else
	{
		echo "parent.location.reload();";
	}
	echo "parent.location.href=\"opp_main.php?session_id=$session_id&opp_id=$opp_id\";";
	echo "</script>";

}

$template->set_var("FORM_URL", $_PHPLIB['webdir'] . "opportunity/add.php?session_id=$session_id");

// build a list of all the sales stages
$db->query("SELECT ss_title,ss_id FROM $sales_stage");
while($db->next_record())
{
	$salesstages .= "<option value=".$db->f('ss_id').">".$db->f('ss_title')."</option>";
}

$add_contact = 1;

// set the default values for 'add' mode
$template->set_var(array(
	"TARGET"		=>	'target="loader"',
	"LOADER"		=>	'<iframe src="' . $_PHPLIB['webdir'] . 'opportunity/opp_loader.php?session_id=' . $session_id . '&opp_id='.$opp_id.'&add_contact='.$add_contact.'&add='.$add.'" name="loader" width="0" height="0"></iframe>',
	"ACTION2"		=>	"reset",
	"ACTION2_LABEL"	=>	"Cancel",
	"FORM_URL"		=>	$_PHPLIB['webdir'] . "opportunity/add.php?session_id=$session_id",		
	"TEMPLATE_MODE"	=>	"Add",
	"SALES_STAGES"	=>	$salesstages
	));

$template->parse("output","main");
$template->p("output");

page_close();

?>