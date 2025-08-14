<?php

/**************************************************************
 * File: 	Auxilliary Page Example file.
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:
 *	- auxpage is a simple wrapper around a content page.
 *	- are minimum requirements for wrapping around file.
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (!eregi("auxpage.php", $_SERVER["PHP_SELF"]))
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01&url=index.php');
			exit;
		}

##############################
# Content Start
# Notes:
#	- required includes
#	- db connected
#	- html is fine
#   - php requires tags set
##############################
?>

<?php
# Get security vars
	$_SEC = get_security_flags ();

# Do User Logged in check
IF ( !$_SEC['_suser_flg'] ) { do_login($data, 'user', '0').$_nl; }
ELSE
{
?>
<!-- Put your html content in here -->
<?php
}
?>

<!-- Start content -->
	<table width="<?php echo "$_TCFG[_WIDTH_CONTENT_AREA]"; ?>" cellpadding="0" cellspacing="0" border="0">
	<tr bgcolor="#000000"><td bgcolor="#000000">
		<table border="0" cellpadding="5" cellspacing="1" width="100%">
			<tr class="BLK_DEF_TITLE" height="30" valign="middle"><td class="BLK_IT_TITLE" align="center">
 	 			<p ALIGN="CENTER">Auxilliary Page Example File / Information</p>
			</td></tr>
			<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY" align="left" valign="top">
				<p align="justify">
					<b>Auxilliary Page:</b> The idea for an auxilliary page function came about from custom files that needed to be wrapped into a package. Face-it, some times you just want to put up a custom file and this will help it fit in. Nothing fancy at this point, but functional.
				<p align="justify">
					<ul>
						<li align="justify">Used for wrapping some html / php code into the content area.
						<li align="justify">Database connection already established.
						<li align="justify">No includes necessary (can get table prefix from globals).
						<li align="justify">Basically all you need is what would go between the body tags- everything else is done.
					</ul>
				<p align="justify">
					<b>Examples:</b> On the menu's are some auxpages I put in. Client Info, My Domains Listing, and the phpMyWebThing info link on main index.
				<p align="justify">
			</td></tr>
			<tr class="BLK_DEF_FMENU" height="20" valign="middle"><td class="BLK_IT_FMENU" align="center">
 	 			<p ALIGN="CENTER"><a href="/">phpCOIN Home</a></p>
			</td></tr>
		</table>
	</td></tr></table>
<!-- Finish content -->
