<?
/*
  
  This library is free software; you can redistribute it and/or
  modify it under the terms of the GNU Lesser General Public
  License as published by the Free Software Foundation; either
  version 2.1 of the License, or (at your option) any later version.
  
  This library is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  Lesser General Public License for more details.
  
  You should have received a copy of the GNU Lesser General Public
  License along with this library; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

  CMS Bridge is Copyright (C) 2002 Bob Treumann, released under
  the gnu Limited General Public License at
  http://www.gnu.org/licenses/lgpl.html     
   
  See
      http://portal.dbserve.net/codeshare/cms_bridge.htm
  for instructions and information about latest releases of
  cms_bridge.

	Sync User Objective:  Get the username from the cms authorization table
	and copy it into an array, "cms_logged_in"
  This is done as an include file at the bridge level
  so that all the separate application  bridges can
  call the same file.
  
*/
if (isset($cms_bridge) and !(isset($cms_bridge_cc))) {
$cms_bridge_cc=$cms_bridge;
}
//initialize the CMS variables
include "$DOCUMENT_ROOT/bridge/initialize_bridge.php";

// Use the CMS variables to set the YP variables
$yemail = $cms_logged_in[email];
$userEmail = $cms_logged_in[email];     
$userFirstName = $cms_logged_in[name];

?>