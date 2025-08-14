<?php

/**************************************************************
 * File: 		Admin Control Panel- Menu File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_admin.php
 *
**************************************************************/


###########################################################################
#	CHANGE YOUR ADMIN MENU OPTIONS HERE                                   #
###########################################################################


# Each menu item consists of three parts:
#	1: the text for the hyperlink
#	2: the actual hyperlink, and
#	3: the "group" that it belongs with
# Menu items will be displayed in the order entered in each array "group"
# To move a menu item to a different "group", change the first parameter of the array
# to match the desired group. For instance, to move "eMail Templates" from the MAIL group
# to the CONFIG group, change $AdminMenu[MAIL]..... to $AdminMenu[CONFIG].....


	# 'Configuration' section
		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Admins'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=admins';

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Parameters'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=parms';

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Server_Info'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=server_info';

		IF ($_CCFG['WHOIS_ENABLED']) {
			$AdminMenu[CONFIG][TEXT][]	= $_LANG['_ADMIN']['CP_WHOIS_Lookups'];
			$AdminMenu[CONFIG][URL][]	= $_SERVER["PHP_SELF"].'?cp=whois';
		}

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Downloads'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=downloads';

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Menu_Blocks'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=menu';

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Components'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=components';

		$AdminMenu[CONFIG][TEXT][]		= $_LANG['_ADMIN']['CP_Icons'];
		$AdminMenu[CONFIG][URL][]		= $_SERVER["PHP_SELF"].'?cp=icons';



	# 'Products' section
		$AdminMenu[PRODUCTS][TEXT][]		= $_LANG['_ADMIN']['CP_Products'];
		$AdminMenu[PRODUCTS][URL][]		= $_SERVER["PHP_SELF"].'?cp=prods';

		$AdminMenu[PRODUCTS][TEXT][]		= $_LANG['_ADMIN']['CP_Vendors'];
		$AdminMenu[PRODUCTS][URL][]		= $_SERVER["PHP_SELF"].'?cp=vendors';

		$AdminMenu[PRODUCTS][TEXT][]		= $_LANG['_ADMIN']['CP_Vendors_Products'];
		$AdminMenu[PRODUCTS][URL][]		= $_SERVER["PHP_SELF"].'?cp=vprods';


	# 'Site Content' section
		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_FAQ_Edit'];
		$AdminMenu[CONTENT][URL][]		= 'mod.php?mod=faq&mode=edit&obj=faq';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_FAQ_QA_Edit'];
		$AdminMenu[CONTENT][URL][]		= 'mod.php?mod=faq&mode=edit&obj=faqqa';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_Topics'];
		$AdminMenu[CONTENT][URL][]		= $_SERVER["PHP_SELF"].'?cp=topics';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_Categories'];
		$AdminMenu[CONTENT][URL][]		= $_SERVER["PHP_SELF"].'?cp=categories';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_Pages_Edit'];
		$AdminMenu[CONTENT][URL][]		= 'mod.php?mod=pages&mode=edit';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_SiteInfo_Edit'];
		$AdminMenu[CONTENT][URL][]		= $_SERVER["PHP_SELF"].'?cp=siteinfo';

		$AdminMenu[CONTENT][TEXT][]		= $_LANG['_ADMIN']['CP_Articles_Edit'];
		$AdminMenu[CONTENT][URL][]		= 'mod.php?mod=articles&mode=edit';

	# Sorry, but this section is so that I do not have to maintain several versions of the single code-base
		IF ($_SERVER["SERVER_NAME"] == "www.phpcoin.com") {
			$AdminMenu[CONTENT][TEXT][]	= $_LANG['_CUSTOM']['CP_Supporters_Edit'];
			$AdminMenu[CONTENT][URL][]	= $_SERVER["PHP_SELF"].'?cp=supporters';

			$AdminMenu[CONTENT][TEXT][]	= $_LANG['_CUSTOM']['CP_Downloads_Edit'];
			$AdminMenu[CONTENT][URL][]	= $_SERVER["PHP_SELF"].'?cp=downloads';
		}


	# 'Mail' section
		$AdminMenu[MAIL][TEXT][]			= $_LANG['_ADMIN']['CP_eMail_Contacts'];
		$AdminMenu[MAIL][URL][]			= $_SERVER["PHP_SELF"].'?cp=mail_contacts';

		$AdminMenu[MAIL][TEXT][]			= $_LANG['_ADMIN']['CP_eMail_Templates'];
		$AdminMenu[MAIL][URL][]			= $_SERVER["PHP_SELF"].'?cp=mail_templates';

		IF ($_ACFG['INVC_AUTO_REMINDERS_ENABLE']) {
			$AdminMenu[MAIL][TEXT][]		= $_LANG['_ADMIN']['CP_Reminders'];
			$AdminMenu[MAIL][URL][]		= $_SERVER["PHP_SELF"].'?cp=reminders';
		}

		$AdminMenu[MAIL][TEXT][]			= $_LANG['_ADMIN']['CP_Mail'];
		$AdminMenu[MAIL][URL][]			= 'mod.php?mod=mail&mode=search&sw=archive';

		IF ($_ACFG[HELPDESK_AUTO_IMPORT_ENABLE]) {
			$AdminMenu[MAIL][TEXT][]		= $_LANG['_ADMIN']['CP_Support_Import'];
			$AdminMenu[MAIL][URL][]		= 'coin_cron/helpdesk.php';
		}


	# 'Operations' section
		IF ($_CCFG['_PKG_ENABLE_IP_BAN']) {
			$AdminMenu[OPS][TEXT][]		= $_LANG['_ADMIN']['CP_Banned_IP'];
			$AdminMenu[OPS][URL][]		= $_SERVER["PHP_SELF"].'?cp=banip';
		}
	# Sorry, but this section is so that I do not have to maintain several versions of the single code-base
		IF ($_SERVER["SERVER_NAME"] == "www.phpcoin.com" || $_SERVER["SERVER_NAME"] == "hosting.cantexgroup.ca") {
			$AdminMenu[OPS][TEXT][]		= $_LANG['_CUSTOM']['LICENSE'];
			$AdminMenu[OPS][URL][]		= $_SERVER["PHP_SELF"].'?cp=licenses';
		}



###########################################################################
#	DO NO CHANGE ANYTHING BELOW THIS LINE                                 #
###########################################################################

# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (!eregi("admin.php", $_SERVER["PHP_SELF"]) ) {
		require_once ('../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=admin.php');
		exit;
	}

# Get admin permissions
	$_SEC	= get_security_flags();
	$_PERMS	= do_decode_perms_admin($_SEC[_sadmin_perms]);



# Content start flag
	$_out .= '<!-- Start content -->'.$_nl;

# Build Title String
	$_tstr = $_LANG['_ADMIN']['CP_Administrator_Menu'];

# Display as new "list" format
	IF ($_CCFG[DisplayType] == 1) {
		$_cstr .= '<table width="100% border="0" cellpadding="5" cellspacing="0"><tr>';

	# Start left-hand column
		$_cstr .= '<td valign="top"><dl>';

	# Build "Config" section
		$_cstr .= '<dt><b>'.$_LANG['_ADMIN']['l_CONFIGURATION'].':</b></dt>'.$_nl;
			for ($i=0; $i<= sizeof($AdminMenu[CONFIG][TEXT]); $i++) {
				$_cstr .= '<dd><a href="'.$AdminMenu[CONFIG][URL][$i].'">'.$AdminMenu[CONFIG][TEXT][$i].'</a></dd>'.$_nl;
			}
		$_cstr .= '<dd>&nbsp;</dd>';

	# Build "Products" Section
		$_cstr .= '<dt><b>'.$_LANG['_ADMIN']['l_PRODUCTS'].':</b></dt>'.$_nl;
			for ($i=0; $i<= sizeof($AdminMenu[PRODUCTS][TEXT]); $i++) {
				$_cstr .= '<dd><a href="'.$AdminMenu[PRODUCTS][URL][$i].'">'.$AdminMenu[PRODUCTS][TEXT][$i].'</a></dd>'.$_nl;
			}
		$_cstr .= '<dd>&nbsp;</dd>';

	# End left-hand column and begin right-hand column
		$_cstr .= '</dl></td><td valign="top"><dl>';

	# Build "Content" section
		$_cstr .= '<dt><b>'.$_LANG['_ADMIN']['l_CONTENT'].':</b></dt>'.$_nl;
			for ($i=0; $i<= sizeof($AdminMenu[CONTENT][TEXT]); $i++) {
				$_cstr .= '<dd><a href="'.$AdminMenu[CONTENT][URL][$i].'">'.$AdminMenu[CONTENT][TEXT][$i].'</a></dd>'.$_nl;
			}
		$_cstr .= '<dd>&nbsp;</dd>';

	# Build "Email" Section
		$_cstr .= '<dt><b>'.$_LANG['_ADMIN']['l_EMAIL'].'</b></dt>'.$_nl;
			for ($i=0; $i<= sizeof($AdminMenu[MAIL][TEXT]); $i++) {
				$_cstr .= '<dd><a href="'.$AdminMenu[MAIL][URL][$i].'">'.$AdminMenu[MAIL][TEXT][$i].'</a></dd>'.$_nl;
			}
		$_cstr .= '<dd>&nbsp;</dd>';

	# Build "Operations" Section
		$_cstr .= '<dt><b>'.$_LANG['_ADMIN']['l_OPERATION'].':</b></dt>'.$_nl;
			for ($i=0; $i<= sizeof($AdminMenu[OPS][TEXT]); $i++) {
				$_cstr .= '<dd><a href="'.$AdminMenu[OPS][URL][$i].'">'.$AdminMenu[OPS][TEXT][$i].'</a></dd>'.$_nl;
			}

	# End right hand column
		$_cstr .= '</dl></td></tr></table>'.$_nl;


# Do original 'Buttons' layout
	} ELSE {
		$_td_start_str	= '<td class="TP1MED_BL" valign="top" width="25%">'.$_nl;
		$_td_hdr_str	= '<td class="BLK_HDR_MENU_C" valign="top" width="25%">'.$_nl;

		$_cstr .= '<center><br>'.$_nl;
		$_cstr .= '<table border="0" cellpadding="0" cellspacing="0" width="90%"><tr>'.$_nl;

	# Build "Config" section
		$_cstr .= '<td valign="top">';
		$_cstr .= '<table border="0" cellpadding="5" cellspacing="0" width="100%">'.$_nl;
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_nl;
		$_cstr .= $_td_hdr_str.'<b>'.$_LANG['_ADMIN']['l_CONFIGURATION'].'</b></td></tr>'.$_nl;
			for ($i=0; $i< sizeof($AdminMenu[CONFIG][TEXT]); $i++) {
				$_cstr .= '<tr>'.$_td_start_str.'<div class="button"><a href="'.$AdminMenu[CONFIG][URL][$i].'">'.$AdminMenu[CONFIG][TEXT][$i].'</a></div></td></tr>'.$_nl;
			}
		$_cstr .= '</table></td>';

	# Build "Content" section
		$_cstr .= '<td valign="top">';
		$_cstr .= '<table border="0" cellpadding="5" cellspacing="0" width="100%">'.$_nl;
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_nl;
		$_cstr .= $_td_hdr_str.'<b>'.$_LANG['_ADMIN']['l_CONTENT'].'</b></td></tr>'.$_nl;
			for ($i=0; $i< sizeof($AdminMenu[CONTENT][TEXT]); $i++) {
				$_cstr .= '<tr>'.$_td_start_str.'<div class="button"><a href="'.$AdminMenu[CONTENT][URL][$i].'">'.$AdminMenu[CONTENT][TEXT][$i].'</a></div></td></tr>'.$_nl;
			}
		$_cstr .= '</table></td>';


	# Build "Products" section
		$_cstr .= $_td_start_str;
		$_cstr .= '<table border="0" cellpadding="5" cellspacing="0" width="100%">'.$_nl;
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_nl;
		$_cstr .= $_td_hdr_str.'<b>'.$_LANG['_ADMIN']['l_PRODUCTS'].'</b></td></tr>'.$_nl;
			for ($i=0; $i< sizeof($AdminMenu[PRODUCTS][TEXT]); $i++) {
				$_cstr .= '<tr>'.$_td_start_str.'<div class="button"><a href="'.$AdminMenu[PRODUCTS][URL][$i].'">'.$AdminMenu[PRODUCTS][TEXT][$i].'</a></div></td></tr>'.$_nl;
			}

	# Build 'Operations' Section
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_td_hdr_str.$_sp.'</td></tr>'.$_nl;
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_nl;
		$_cstr .= $_td_hdr_str.'<b>'.$_LANG['_ADMIN']['l_OPERATION'].'</b></td></tr>'.$_nl;
			for ($i=0; $i< sizeof($AdminMenu[OPS][TEXT]); $i++) {
				$_cstr .= '<tr>'.$_td_start_str.'<div class="button"><a href="'.$AdminMenu[OPS][URL][$i].'">'.$AdminMenu[OPS][TEXT][$i].'</a></div></td></tr>'.$_nl;
		}
		$_cstr .= '</table></td>';

	# Build "Email" section
		$_cstr .= '<td valign="top">';
		$_cstr .= '<table border="0" cellpadding="5" cellspacing="0" width="100%">'.$_nl;
		$_cstr .= '<tr class="BLK_DEF_TITLE">'.$_nl;
		$_cstr .= $_td_hdr_str.'<b>'.$_LANG['_ADMIN']['l_EMAIL'].'</b></td></tr>'.$_nl;
			for ($i=0; $i< sizeof($AdminMenu[MAIL][TEXT]); $i++) {
				$_cstr .= '<tr>'.$_td_start_str.'<div class="button"><a href="'.$AdminMenu[MAIL][URL][$i].'">'.$AdminMenu[MAIL][TEXT][$i].'</a></div></td></tr>'.$_nl;
			}
		$_cstr .= '</table></td>';


	# Close off table
		$_cstr .= '</tr></table>';
		$_cstr .= '</center>';
	}



# Build "Backup Database" Section
	IF ($_SEC['_sadmin_flg'] && ($_PERMS[AP13] == 1 || $_PERMS[AP16] == 1)) {
		$_cstr .= '<form action="coin_admin/cp_backup.php" method="post">';
		$_cstr .= '<dl><dt><b>'.$_LANG['_ADMIN']['CP_Backup'].':</b>';
		$_cstr .= ' <a href="admin.php?cp=parms&op=edit&fpg=&fpgs=backup">'.$_TCFG['_S_IMG_PM_S'].'</a>';
		$_cstr .= '</dt>';
		$_cstr .= '<dd><INPUT type="radio" name="btype" value="download" checked> '.$_LANG['_BASE']['l_backup_download'].'<br>';
		$_cstr .= '<INPUT type="radio" name="btype" value="save"> '.$_LANG['_BASE']['l_backup_save'].'<br>';
		$_cstr .= '<INPUT type="radio" name="btype" value="email"> '.$_LANG['_BASE']['l_backup_email'].'<br>';
		$_cstr .= '<INPUT TYPE=hidden name="op" value="save">&nbsp;&nbsp;&nbsp;';
		$_cstr .= '<input type="image" src="'.$_CCFG['_PKG_URL_THEME_IMGS'].'nav/n_med_backup.gif" alt="Backup" border="0" align="middle">';
		$_cstr .= '</dd></dl></form>';
	}



# Block Footer Menu
	$_mstr .= do_nav_link ('login.php?w=admin&o=logout', $_TCFG['_IMG_LOGOUT_M'],$_TCFG['_IMG_LOGOUT_M_MO'],'',$_LANG['_BASE']['B_Log_Out']);
	$_mstr .= do_nav_link ($_SERVER["PHP_SELF"], $_TCFG['_IMG_REFRESH_M'],$_TCFG['_IMG_REFRESH_M_MO'],'','');

# Call block it function
	$_out .= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
	$_out .= '<br>'.$_nl;

# Echo final output
	echo $_out;


$_cstr = '';
$_mstr = '';
$_out = '';

# Check for updates to phpCOIN
	IF ($_ACFG[AUTOCHECK_UPDATES]) {
		class xItem {
			var $xTitle;
			var $xLink;
			var $xDate;
			var $xVersion;
			var $xDescription;
		}

	# Initialize variables
		$sTitle = "";
		$sLink = "";
		$sDate = "";
		$sVersion = "";
		$sDescription = "";
		$arItems = array();
		$itemCount = 0;
		$_content = '';
		$_out = "";
		$Release = 0;
		$Fixes = 0;
		$uFile = "http://www.phpcoin.com/current.rss";

	# internal functions

		function update_site_is_available() {
			$socket = @fsockopen( 'www.phpcoin.com', 80, $errno, $errstr, 3 );
			IF (!$socket) {
				return(false);
			} ELSE {
				fclose($socket);
				return(true);
			}
		}

		function startElement($parser, $name, $attrs) {
			global $curTag;
			$curTag .= "^$name";
		}
		function endElement($parser, $name) {
			global $curTag;
			$caret_pos = strrpos($curTag,'^');
			$curTag = substr($curTag,0,$caret_pos);
		}
		function characterData($parser, $data) {
			global $curTag; // get the Channel information first
			global $sTitle, $sLink, $sDescription;
			$titleKey = "^RSS^CHANNEL^TITLE";
			$linkKey = "^RSS^CHANNEL^LINK";
			$verKey = "^RSS^CHANNEL^VERSION";
			$dateKey = "^RSS^CHANNEL^DATE";
			$descKey = "^RSS^CHANNEL^DESCRIPTION";
			IF ($curTag == $titleKey) {
				$sTitle = $data;
			} ELSEIF ($curTag == $linkKey) {
				$sLink = $data;
			} ELSEIF ($curTag == $dateKey) {
				$sDate = $data;
			} ELSEIF ($curTag == $descKey) {
				$sDescription = $data;
			}
			global $arItems, $itemCount;
			$itemTitleKey = "^RSS^CHANNEL^ITEM^TITLE";
			$itemLinkKey = "^RSS^CHANNEL^ITEM^LINK";
			$itemDateKey = "^RSS^CHANNEL^ITEM^DATE";
			$itemVerKey = "^RSS^CHANNEL^ITEM^VERSION";
			$itemDescKey = "^RSS^CHANNEL^ITEM^DESCRIPTION";
			IF ($curTag == $itemTitleKey) {
				$arItems[$itemCount] = new xItem();
				$arItems[$itemCount]->xTitle = $data;
			} ELSEIF ($curTag == $itemLinkKey) {
				$arItems[$itemCount]->xLink = $data;
			} ELSEIF ($curTag == $itemDateKey) {
				$arItems[$itemCount]->xDate = $data;
			} ELSEIF ($curTag == $itemVerKey) {
				$arItems[$itemCount]->xVersion = $data;
			} ELSEIF ($curTag == $itemDescKey) {
				$arItems[$itemCount]->xDescription = $data;
				$itemCount++;
			}
		}

	# Main loop
		require_once($_PACKAGE[DIR] . 'version.php');
		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");

	# See if update site is available.
	# If it is, grab the .rss file
		IF (update_site_is_available()) {
			IF (!($fp = fopen($uFile,"r"))) {die ("could not open RSS for input");}
			while ($data = fread($fp, 8192)) {
				IF (!xml_parse($xml_parser, $data, feof($fp))) {
					die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
				}
			}
			xml_parser_free($xml_parser);

			FOR ($i=0; $i<count($arItems); $i++) {
				$txItem = $arItems[$i];

				IF ($txItem->xTitle == "Release") {
					IF ($ThisVersion < $txItem->xVersion) {
						$_content .= 'phpCOIN ';
						$_content .= '<a href = "'.$txItem->xLink.'">v'.$txItem->xVersion.'</a> was released '.$txItem->xDate.'<br>';
						$_content .= 'Features: '. $txItem->xDescription.'<br><br>';
						$Release++;
					}
				}

				IF ($txItem->xTitle == "Fix-File") {
					IF (($ThisFix < $txItem->xDate) && ($ThisVersion == $txItem->xVersion)) {
						$_content .= $txItem->xTitle.' ';
						$_content .= '<a href = "'.$txItem->xLink.'">'.$txItem->xDate.'</a> for version '.$txItem->xVersion.'<br>';
						$txItem->xDescription = str_replace("|",'</li><li>',$txItem->xDescription);
						$_content .= '<ul><li>'. $txItem->xDescription.'</li></ul>';
						$Fixes++;
					}
				}
			}
		} ELSE {
			$_content = $_LANG['_ADMIN']['UPDATE_UNAVAILABLE'];
		}
		IF (!$_content) {$_content = $_LANG['_ADMIN']['UPDATE_NONE'];}


	# Display output
		IF ($Fixes > 1) {
			$_content .= $_LANG['_ADMIN']['UPDATE_MANY'].' v'.$ThisVersion;
		}
		IF ($Release && $Fixes) {
			$_content .= '<br>'.$_LANG['_ADMIN']['UPDATE_NEW'].' v'.$ThisVersion;
		}

	# Build Title String, Content String, and Footer Menu String
		$_tstr  = $_LANG['_ADMIN']['UPDATE_TITLE'];
		$_cstr  = '<div align="center" valign="top" height="100%">'.$_nl;
		$_cstr .= '<table width="90%" cellspacing="5">'.$_nl;
		$_cstr .= '<tr><td align="left" valign="top">'.$_nl;
		$_cstr .= $_LANG['_ADMIN']['UPDATE_VERSION'].' v'.$ThisVersion.' '.$_LANG['_ADMIN']['UPDATE_FIX'].' '.$ThisFix.'<br><br>';
		$_cstr .= $_content;
		$_cstr .= '</td></tr>'.$_nl;
		$_cstr .= '</table>'.$_nl;
		$_cstr .= '</div>'.$_nl;
		$_mstr_flag	= 0;
		$_mstr  = '';

	# Call block it function
		$_out  = do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
		$_out .= '<br>'.$_nl;

	# Echo final output
		echo $_out;
	}

?>
