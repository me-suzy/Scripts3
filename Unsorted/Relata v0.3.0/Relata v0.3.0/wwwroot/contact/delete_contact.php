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

// this page does not use templates for speed purposes, all JS has been stripped of all comments and compressed

include("../config.inc.php");

//  We use the following features:
//  sess   for session variables
//  auth   for login checks, also required for user variables

// force browser to not cache page
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past

// functions used within this page are stored here
include("lib.php");

// perform all startup functions
page_open(array(
	"sess" => "relata_session", 
	"auth" => "relata_challenge_auth"
	));

$user_id = $auth->auth["uid"];

// open a db connnection
$db   = new relata_db();

delete_account($account_id);
delete_contact($contact_id);

// start a new template
$template = start_template();

// set template file
$template->set_file("pop","contact_delete_pop.htm");

// output the template
$template->parse("output","pop");
$template->p("output");

page_close();
?>


