<?php

/**************************************************************
 * File: 		Auxilliary Page Why Hack Me?
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
	IF (!eregi("auxpage.php", $_SERVER["PHP_SELF"])) {
		require_once ('../coin_includes/session_set.php');
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

<!-- Start content -->
	<table width="<?php echo "$_TCFG[_WIDTH_CONTENT_AREA]"; ?>" cellpadding="0" cellspacing="0" border="0">
		<tr bgcolor="#000000">
			<td bgcolor="#000000"><table border="0" cellpadding="5" cellspacing="1" width="100%">
				<tr class="BLK_DEF_TITLE" height="30" valign="middle">
					<td class="BLK_IT_TITLE" align="center">Why Are You Doing This?</td>
				</tr><tr class="BLK_DEF_ENTRY">
					<td class="BLK_IT_ENTRY" align="left" valign="top">You are attempting to hack my installation of phpCOIN. That is not nice. Why are you trying to destroy my livelihood? Aren't you ashamed of yourself?</td>
				</tr>
			</table></td>
		</tr>
	</table>
<!-- Finish content -->
