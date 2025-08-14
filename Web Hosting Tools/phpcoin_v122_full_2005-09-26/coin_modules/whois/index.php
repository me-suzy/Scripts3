<?php
/**************************************************************
 * File: 		WHOIS Module Index.php
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_whois.php
 *	This file borrows the original concept and code from
 *	Whois - a Whois lookup script written in PHP. PHP Nuke Module.
 *	Copyright (C) 2001 Marek Rozanski
 *	marek@mrscripts.co.uk
 *
 * It has been modified so extensively that almost 75% of the code
 * is not the original code, therefore the new file header
**************************************************************/

# Check file loaded through modules call
	IF (!isset($_SERVER)) {$_SERVER = $HTTP_SERVER_VARS;}
	IF (eregi("index.php", $_SERVER["PHP_SELF"])) {
		require_once ('../../coin_includes/session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01&url=mod.php?mod=siteinfo');
		exit();
	}

# Include language file (must be after parameter load to use them)
	require_once ($_CCFG['_PKG_PATH_LANG'].'lang_whois.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_whois_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_whois_override.php');
	}


# Define some look and misc parameters
	$poweredby  = "WHOIS lookup code based on <a href=http://www.mrscripts.co.uk target=_blank><b>MRWhois</b></a>"; // DO NOT CHANGE
	$backgcol   = '#9AC0CD';						// general background color
	$fontacolor = 'green';							// color of an available domain
	$fontucolor = 'red';							// color when not available
	$infolinks  = 'black';							// color of additional links
	$sepcolor   = '#cccccc';						// separator color
	$stdcolor   = 'black';							// header and footer font color
	$errcolor   = 'red';							// color of error messages
# End of variables, you do not need to change anything below this line.



# Register / Order Link Configuration
	$_link 	= $_CCFG['WHOIS_LINK'];
	switch($_link) {
		case 0:
			# Do NOT add an [Order] or [Register] link
			$regurl		= '';
			$regtext	= '';
			break;
		case 1:
			/*
			Go to "orders" page, passing in domain name and setting "New Domain" to "Yes"
			If you want the customer to be able to click on an [Order] button
			for available domains. May need to edit $regurl.
			*/
			$regurl		= 'mod.php?mod=orders&ord_referred_by=' . $_GPV[ord_referred_by];
			$regtext	= $_LANG['_WHOIS']['Link_Order'];
			break;
		case 2:
			/*
			Go to your affiliiate link to register the domain.
			This option will open a new browser window to the registrar, and the
			original browser window will remain on this page. May need to edit $regurl.
			*/
			$regurl		= $_CCFG['WHOIS_AFFILIATE_LINK'];
			$regtext	= $_LANG['_WHOIS']['Link_Register'];
			break;
		default:
			# Do NOT add an [Order] or [Register] link
			$regurl		= '';
			$regtext	= '';
			break;
		}

# Do Database lookup of registrars
	$result= ""; $numrows = 0;

# Set Query for select.
	$query	= "SELECT * FROM ".$_DBCFG['whois']." WHERE whois_include=1 ORDER BY whois_display ASC";

# Do select and return check
	$result		= db_query_execute($query);
	$numrows	= db_query_numrows($result);

# Process query results
	$xx=0;
	while(list($whois_id, $whois_server, $whois_nomatch, $whois_value, $whois_display, $whois_include, $whois_prod_id, $whois_notes) = db_fetch_row($result)) {
		$Lookup_Domain['server'][$xx]	= $whois_server;	// server to lookup for domain name
		$Lookup_Domain['nomatch'][$xx]	= $whois_nomatch;	// string returned by server if the domain is not found
		$Lookup_Domain['value'][$xx]	= $whois_value;		// string value for this domain extension (do not change)
		$Lookup_Domain['display'][$xx]	= $whois_display;	// string value for this domain to display on form
		$Lookup_Domain['include'][$xx]	= $whois_include;	// include this domain in lookup
		$Lookup_Domain['prod_id'][$xx]	= $whois_prod_id;	// over-ride product ordered with this id
		$xx++;                                              // Increment counter
	}
	$Lookups = $xx;



# This function displays an available domain ($what), adding [Order] or [Register] links if configured.
function dispav($what,$prodid) {
	global $fontacolor, $infolinks, $regurl, $regtext, $_nl;
	$_out .= '<tr>'.$_nl;
	$_out .= '<td class="TP3MED_BC" nowrap>'.$_nl;
	IF ($regurl <> '') {
		# Check if regurl is linked to phpCOIN orders page (if no, open new window)
		$theURL = strpos($regurl, 'mod.php');
		# Set link
		IF ( $theURL === false ) {
			# Open a new browser window to the registrar.
			$_out .= '<a href="'.$regurl.'" target="_blank" onMouseOver="window.status=\'Register '.$what.'\';return true" onMouseOut="window.status=\'\';return true">';
		} ELSE {
			# Send current browser window to order form, passing in domain name.
			$_out .= '<a href="'.$regurl.'&ord_domain='.$what.'&ord_prod_id='.$prodid.'"'.'onMouseOver="window.status=\'Order '.$what.'\';return true"onMouseOut="window.status=\'\';return true">';
		}
		$_out .= '<font color='.$infolinks.'>'.$regtext.'</font></a>'.$_nl;
	} ELSE {
		$_out .= '&nbsp;';
	}
	$_out .= '</td>'.$_nl;
	$_out .= '<td class="TP3MED_BC" nowrap><font color="'.$fontacolor.'"><b>'.$what.'</b></font></td>'.$_nl;
	$_out .= '<td class="TP3MED_BC" colspan="3">&nbsp;</td>'.$_nl;
	$_out .= '</tr>'.$_nl;
	return $_out;
}


# Function to display an unavailable domain ($what) via server ($where), with [details] and [goto] links
function dispun($what,$where) {
	global $fontucolor, $infolinks, $_nl, $_LANG, $_CCFG;
	$_out .= '<tr><td class="TP3MED_BC" colspan="2">&nbsp;</td>'.$_nl;
	$_out .= '<td class="TP3MED_BC" nowrap>'.$_nl;
	$_out .= '<font color="'.$fontucolor.'"><b>'.$what.'</b></font></td>'.$_nl;
	$_out .= '<td class="TP3MED_BC" nowrap>'.$_nl;
	IF (!$_CCFG['WHOIS_DETAILS_NEW']) {
		# Set to open details in current window
		$_out .= '<a href="mod.php?mod=whois&action=details&ord_domain='.$what.'&server='.$where.'" onMouseOver="window.status=\'Details about '.$what.'\';return true" onMouseOut="window.status=\'\';return true"';
		$_out .= ' <font color="'.$infolinks.'">'.$_LANG['_WHOIS']['Link_Details'].'</font></a>'.$_nl;
	} ELSE {
		# Set to open details in new window
		$_out .= '<a href="#" onMouseOver="window.status=\'Details about '.$what.'\';return true" onMouseOut="window.status=\'\';return true"';
		$_out .= ' onClick="javascript:window.open(\'coin_modules/whois/details.php?ord_domain='.$what.'&server='.$where.'\',\'details\',\'scrollbars=1,copyhistory=0,directories=0,status=0,resizable=yes,width=700,height=500\')">';
		$_out .= ' <font color="'.$infolinks.'">'.$_LANG['_WHOIS']['Link_Details'].'</font></a>'.$_nl;
	}
	$_out .= '</td>'.$_nl;
	$_out .= '<td class="TP3MED_BC" nowrap><a href="http://www.'.$what.'" target="_blank"><font color="'.$infolinks.'">'.$_LANG['_WHOIS']['Link_Goto'].'</font></a></td>'.$_nl;
	$_out .= '</tr>'.$_nl;
	return $_out;
}


# Function to display an error
function disperror($text) {
	global $errcolor, $_nl;
	$_out .= '<table width=80%><tr><td class="TP3MED_BC">'.$_nl;
	$_out .= '<font color="'.$errcolor.'"><b>'.$text.'</b></font>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;
	return $_out;
}


# Function to display main lookup form
function main() {
	global $sepcolor, $stdcolor, $poweredby, $_nl, $_sp, $_LANG, $_CCFG, $_GPV;
	global $Lookups, $Lookup_Domain;

	IF ($_GPV[type]=='') {$_GPV[type]='com';}
	$_out .= '<table width="80%"><tr><td>'.$_nl;
	$_out .= '<form method="post" action="mod.php?mod=whois">'.$_nl;
	$_out .= '<table width="100%" align="center" cellspacing="0" cellpadding="8" border="0">'.$_nl;

	$_out .= '<tr>'.$_nl;
	$_out .= '<td class="TP3MED_NC" colspan="2" align="center" width="100%">'.$_nl;
	IF ($_CCFG['WHOIS_INSTRUCTIONS_SHORT']) {
		$_out .= $_LANG['_WHOIS']['Text_Instructions_Short'].$_nl;
	} ELSE {
		$_out .= $_LANG['_WHOIS']['Text_Instructions_Long'].$_nl;
	}
	$_out .= '</td>'.$_nl;
	$_out .= '</tr>'.$_nl;
	$_out .= '<tr>'.$_nl.'<td class="TP3MED_BC" colspan="2" width="100%">'.$_sp.'</td>'.$_nl.'</tr>'.$_nl;

	$_out .= '<tr>'.$_nl.'<td class="TP3MED_BR" >'.$_LANG['_WHOIS']['Title_Domain'].$_sp.'</td>'.$_nl;
	$_out .= '<td class="TP3MED_BL">'.$_sp.$_LANG['_WHOIS']['Title_Extension'].'</td></tr>'.$_nl;
	$_out .= '<tr>'.$_nl;
	$_out .= '<td class="TP3MED_NR" valign="middle">'.$_nl;
	$_out .= '<input class="PSML_NL" type="hidden" name="action" value="checkdom">'.$_nl;
	$_out .= '<input class="PSML_NL" type="hidden" name="ord_referred_by" value="'.$_GPV[ord_referred_by].'">'.$_nl;
	$_out .= '<input class="PSML_NL" type="hidden" name="ord_prod_id" value="'.$_GPV[ord_prod_id].'">'.$_nl;
	$_out .= '<input class="PSML_NL" type="text" name="ord_domain" size="25" value="'.$_GPV[ord_domain].'" maxlength="58">&nbsp;'.$_nl;
	$_out .= '</td><td class="TP3MED_NL" valign="middle">'.$_nl;
	IF ($_CCFG['WHOIS_EXT_LIST']) {
		# Build extensions as select list
		$_out .= $_sp.$_sp.'<select class="PMED_NL" name="type" size="1" value="'.$_GPV[type].'">'.$_nl;
		# Loop through for domains list
		FOR ($i=0; $i <= $Lookups; $i++) {
			IF ( $Lookup_Domain['include'][$i] == true ) {
				$_cnt++;
				$_out .= '<option value="'.$Lookup_Domain['value'][$i].'"';
				IF ($_GPV[type] == $Lookup_Domain['value'][$i]) { $_out .= ' selected'; }
				$_out .= '>'.$_sp.$_sp.$Lookup_Domain['display'][$i] .'</option>'. $_nl;
			}
		}
		IF ( $_cnt > 0 ) {
			$_cnt = 0;
			$_out .= '<option value="all"';
			IF ($_GPV[type] == 'all') {$_out .= ' selected';}
			$_out .= '>'.$_sp.$_sp.$_LANG['_WHOIS']['Option_All_Domains'].'</option>'. $_nl;
		}
		$_out .= '</select>'.$_nl;
	} ELSE {
		# Build extensions as radio buttons list
		$_out .= '<p class="PMED_NL_ID">'.$_nl;
		# Loop through for domains list
		FOR ($i=0; $i <= $Lookups; $i++) {
			IF ($Lookup_Domain['include'][$i] == true) {
				$_cnt++;
				$_out .= '<INPUT TYPE="radio" ';
				IF ($_GPV[type] == $Lookup_Domain['value'][$i]) {$_out .= 'CHECKED ';}
				$_out .= 'NAME="type" VALUE="'.$Lookup_Domain['value'][$i].'">';
				$_out .= '<font color="'.$stdcolor.'"> '.$Lookup_Domain['display'][$i].'</font><br>'.$_nl;
			}
		}
		IF ($_cnt > 0) {
			$_cnt = 0;
			$_out .= '<INPUT TYPE="radio" ';
			IF ($_GPV[type] == "all") {$_out .= 'CHECKED ';}
			$_out .= 'NAME="type" VALUE="all">';
			$_out .= '<font color="'.$stdcolor.'"> '.$_LANG['_WHOIS']['Option_All_Domains'].'</font><br>';
		}
		$_out .= '</p>'.$_nl;
	} // showlist
	$_out .= '</td>'.$_nl;
	$_out .= '</tr>'.$_nl;
	$_out .= '<tr>'.$_nl.'<td class="TP3MED_BC" colspan="2" align="center" width="100%">'.$_sp.'</td>'.$_nl.'</tr>'.$_nl;
	$_out .= '<tr><td class="TP3MED_BC" colspan="2"><input class="PSML_NC" type="submit" name="button" value="'.$_LANG['_WHOIS']['B_Check'].'"></td></tr>'.$_nl;
	$_out .= '<tr>'.$_nl.'<td class="TP3MED_BC" colspan="2" align="center" width="100%">'.$_sp.'</td>'.$_nl.'</tr>'.$_nl;
	$_out .= '</table>'.$_nl;
	$_out .= '</form>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;
	return $_out;
}


#############################
#####   End Functions   #####
#############################

# Output main form except for details
	IF ($_GPV[action] != 'details') {$_out .= main();}

# Continue if displaying results
	IF ($_GPV[action] == 'details' && !$_CCFG['WHOIS_DETAILS_NEW']) {
		$_out .= '<div align="center">'.$_nl;
		$_out .= '<table width="80%"><tr><td>'.$_nl;
		$_out .= '<p class="PMED_NL">'.$_nl;
		$fp = fsockopen($_GPV[server],43);
		fputs($fp, "$_GPV[ord_domain]\r\n");
		while(!feof($fp)) {$_out .= fgets($fp,128).'<br>'.$_nl;}
		fclose($fp);
		$_out .= '</td></tr></table>'.$_nl;
		$_out .= '</div>'.$_nl;
	}

# Continue if checking domain
	IF ($_GPV[action] == 'checkdom') {
		// Check the name for bad characters
		IF (strlen($_GPV[ord_domain]) < 3) {
			$err	= 1;
			$msg	= $_LANG['_WHOIS']['Error_Too_Short'];
			$_out	.= disperror($msg);
		}
		IF (strlen($_GPV[ord_domain]) > 63) {
			$err	= 1;
			$msg	= $_LANG['_WHOIS']['Error_Too_Long'];
			$_out	.= disperror($msg);
		}
		IF (ereg("^-|-$",$_GPV[ord_domain])) {
			$err	= 1;
			$msg	= $_LANG['_WHOIS']['Error_Hyphens'];
			$_out	.= disperror($msg);
		}

		IF (!$err) {
			IF (!ereg("([a-z]|[A-Z]|[0-9]|-){".strlen($_GPV[ord_domain])."}",$_GPV[ord_domain])) {
				$err	= 1;
				$msg	= $_LANG['_WHOIS']['Error_AlphaNum'];
				$_out	.= disperror($msg);
			}
		}
		IF ($err) {$_out = '<br>'.$_out.$_nl;}

		IF (!$err) {
			#	$_out .= '<br>'.$_nl;
			$_out .= '<table width="80%"><tr><td>'.$_nl;
			$_out .= '<table width="100%" align="center" cellspacing="0" cellpadding="1">'.$_nl;
			$_out .= '<tr>'.$_nl;
			$_out .= '<td class="TP3MED_BC" nowrap bgcolor="'.$sepcolor.'">'.$_nl;
			$_out .= '<font color="'.$stdcolor.'"><b>'.$_sp.'</b></font>'.$_nl;
			$_out .= '</td>'.$_nl;
			$_out .= '<td class="TP3MED_BC" nowrap bgcolor="'.$sepcolor.'">'.$_nl;
			$_out .= '<font color="'.$stdcolor.'"><b>'.$_LANG['_WHOIS']['Title_Available'].'</b></font>'.$_nl;
			$_out .= '</td>'.$_nl;
			$_out .= '<td class="TP3MED_BC" nowrap bgcolor="'.$sepcolor.'">'.$_nl;
			$_out .= '<font color="'.$stdcolor.'"><b>'.$_LANG['_WHOIS']['Title_Taken'].'</b></font>'.$_nl;
			$_out .= '</td>'.$_nl;
			$_out .= '<td class="TP3MED_BC" nowrap bgcolor="'.$sepcolor.'">'.$_nl;
			$_out .= '<font color="'.$stdcolor.'"><b>'.$_sp.'</b></font>'.$_nl;
			$_out .= '</td>'.$_nl;
			$_out .= '<td class="TP3MED_BC" nowrap bgcolor="'.$sepcolor.'">'.$_nl;
			$_out .= '<font color="'.$stdcolor.'"><b>'.$_sp.'</b></font>'.$_nl;
			$_out .= '</td>'.$_nl;
			$_out .= '</tr>'.$_nl;
			FOR ( $x=0; $x < $Lookups; $x++ ) {
				IF ((($_GPV[type] == "all") || ($_GPV[type] == $Lookup_Domain['value'][$x])) && $Lookup_Domain['include'][$x]) {
					# Custom to get multiple lookups from one item
					IF ($Lookup_Domain['value'][$x] == 'com' && $Lookup_Domain['display'][$x] == '.com .net') {
						$dom_array = array($_GPV[ord_domain].'.com', $_GPV[ord_domain].'.net');
					} ELSEIF ($Lookup_Domain['value'][$x] == 'uk' && $Lookup_Domain['display'][$x] == '.co.uk .org.uk .me.uk') {
						$dom_array = array($_GPV[ord_domain].'.co.uk', $_GPV[ord_domain].'.org.uk', $_GPV[ord_domain].'.me.uk');
					} ELSEIF ($Lookup_Domain['value'][$x] == 'pl' && $Lookup_Domain['display'][$x] == '.pl .com.pl') {
						$dom_array = array($_GPV[ord_domain].'.pl', $_GPV[ord_domain].'.com.pl');
					} ELSE {
						$dom_array = array($_GPV[ord_domain].'.'.$Lookup_Domain['value'][$x]);
					}
    				$dom_count = count($dom_array);
					$i=0;
					FOR ($i=0; $i < $dom_count; $i++) {
						$domname = $dom_array[$i];
						$ns = fsockopen($Lookup_Domain['server'][$x], 43, $errno, $errstr, 10);
						fputs($ns,"$domname\r\n");
						$result = '';
						while(!feof($ns)) {$result .= fgets($ns,128);}
						fclose($ns);
						IF (eregi($Lookup_Domain['nomatch'][$x],$result)) {
							// Over-ride product_id ordered, if set in array above,
							// otherwise pass back ord_prod_id originally ordered.
							IF ($Lookup_Domain['prod_id'][$i]) {
								$prodid = $Lookup_Domain['prod_id'][$i];
							} ELSE {
								$prodid = $_REQUEST[ord_prod_id];
							}
							$_out .= dispav($domname,$prodid);
						} ELSE {
							$_out .= dispun($domname,$Lookup_Domain['server'][$x]);
						}
					}
					$_out .= '<tr><td colspan="5" bgcolor="'.$sepcolor.'">&nbsp;</td></tr>'.$_nl;
				}
			}
			$_out .= '</table>'.$_nl;
			$_out .= '</td></tr></table>'.$_nl;
		} // error check
	# Hide ls layer if implemented.
		IF ($_WAIT) {
			$_js .= '<script language="javascript">';
			$_js .= 'hidelayer("waitlayer");';
			$_js .= '</script>'.$_nl;
			echo $_js;
		}
	} // action checkdomain


# Call block it function
	$_tstr	.= $_LANG['_WHOIS']['Text_Title'];
	$_cstr	.= '<div align="center">'.$_out.'</div><br>'.$_nl;
	$_cstr	.= '<div class="PSML_NC">'.$poweredby.'</div><br>'.$_nl;
	$_mstr	.= '<a href="mod.php?mod=whois">'.$_TCFG['_IMG_RETURN_M'].'</a>';
	$_ret	.= do_mod_block_it ($_tstr, $_cstr, '1', $_mstr, '1');
	$_ret	.= '<br>'.$_nl;
	echo $_ret;
?>
