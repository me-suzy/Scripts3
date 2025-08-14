<?php
/**************************************************************
 * File: 		HelpDesk Module Auto-Import Emails
 * Author:	Stephen M. Kitching (http://phpCOIN.com)
 *			Based on imap functions from:
 *              http://xeoman.com/code/php/xeoport
 * Date:		2004-04-03 (V1.2.1)
 * Changed:	2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- Translation File: lang_helpdesks.php
 *
**************************************************************/


# Silent running, or show progress, and message count(s) on-screen when done
	$Silent=0;


# Include the "where are we" code
	$cronfile = 'helpdesk.php';
	require_once ('cron_config.php');


# Include core file
	require_once ($_PACKAGE[DIR]."coin_includes/core.php");

# Include language file (must be after parameter load to use them)
	require_once ( $_CCFG['_PKG_PATH_LANG'].'lang_helpdesk.php');
	IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_helpdesk_override.php')) {
		require_once($_CCFG['_PKG_PATH_LANG'].'lang_helpdesk_override.php');
	}

# Include required functions file
	require_once ( $_CCFG['_PKG_PATH_MDLS'].'helpdesk/helpdesk_funcs.php');

# FROM HERE TO 'END XEOPORT' IS FROM http://xeoman.com/code/php/xeoport
	$line_break = "\r\n";  // How we want line breaks to appear in database.  Use "\n" for *nix
	$type = array("text", "multipart", "message", "application", "audio", "image", "video", "other");
	$encoding = array("7bit", "8bit", "binary", "base64", "quoted-printable", "other");
	$xp_type = 'email';
	$parts_type = array();
	$parts_encoding = array();
	$parts_attachments = array();
	$parts_filename = array();
	$parts_filesize = array();
	$parts_size = array();
	$counter_found = 0;
	$counter_inserted = 0;
	$counter_sqlerrors = 0;
	$time_counter = 0;
	$counter_empty = 0;
	$counter_size = 0;
	$conf_magicquotes = get_magic_quotes_gpc();

	function decode_text($input, $encoding) {
		switch ($encoding) {
			case '7bit':
				return $input;
				break;
			case 'quoted-printable':
				$input = preg_replace("/=\r?\n/", '', $input);
				return quoted_printable_decode($input);
				break;
			case 'base64':
				return base64_decode($input);
				break;
			default:
				return $input;
		}
	}

	function decode_header ($string) {
		if(ereg("=\?.{0,}\?[Bb]\?",$string)){
			$arrHead=split("=\?.{0,}\?[Bb]\?",$string);
			while(list($key,$value)=each($arrHead)){
				if(ereg("\?=",$value)){
					$arrTemp=split("\?=",$value);
					$arrTemp[0]=base64_decode($arrTemp[0]);
					$arrHead[$key]=join("",$arrTemp);
				}
			}
			$string=join("",$arrHead);
		} elseif (ereg("=\?.{0,}\?[Qq]\?", $string)){
			$string = quoted_printable_decode($string);
			$string = ereg_replace("=\?.{0,}\?[Qq]\?","", $string);
			$string = ereg_replace("\?=","", $string);
		}
		if (substr_count($string, '@') < 1) {$string = str_replace("_", " ", $string);}
		return $string;
	}

	function get_name ($body) {
		if (substr_count($body, '<') < 1 || substr_count($body, '>') < 1) {
			return $body;
			break;
		}
		$endposition = strpos($body, '<');
		return trim(substr($body, 0, $endposition));
	}

	function get_substring($body, $start, $end) {
		$startposition = strpos($body, $start, 0) + strlen($start);
		if (substr_count($body, $start) < 1 || substr_count($body, $end) < 1) {
			return $body;
			break;
		}
		$endposition = strpos($body, $end, 0);
		return substr($body, $startposition, $endposition - $startposition);
	}
# END XEOPORT



# Call import email functions (if enabled)
	IF ( $_ACFG['HELPDESK_AUTO_IMPORT_ENABLE'] ) {

	# Dim some vars
		$Processed	= 0;
		$total		= 0;

	# Connect To Mail Server
		IF (!$Silent) {echo 'Connecting to mail-server<br>';}
		$inbox = @imap_open('{' . $_ACFG['HELPDESK_AUTO_IMPORT_SERVER'] . $_ACFG['HELPDESK_AUTO_IMPORT_TYPE'] . '}INBOX', $_ACFG['HELPDESK_AUTO_IMPORT_USERID'], $_ACFG['HELPDESK_AUTO_IMPORT_PASSWORD']);

		IF ($inbox) {

		# Grab The Message Headers
			$headers = imap_headers($inbox);
			$total = imap_num_msg($inbox);
			IF (!$Silent && $total) {echo $total.' messages to process<br>';}

		# Process The Messages
			FOR ($x=1; $x <= $total; $x++) {
				If (!$Silent) {echo '<br>&nbsp;&nbsp;&nbsp;Processing message '.$x.'<br>';}
				$headers	= imap_header($inbox, $x);
				$structure	= imap_fetchstructure($inbox, $x);

			# FROM HERE TO 'END XEOPORT' IS FROM http://xeoman.com/code/php/xeoport
			# Get constituent parts
				$xp_id = $headers->message_id;
				$xp_time_unix = strtotime("+ $_CCFG[_PKG_DATE_SERVER_OFFSET] hours");
				$xp_time_iso = date("H:i:s", $xp_time_unix);
				$xp_date_iso = date("Y-m-d", $xp_time_unix);
				$xp_date_full = $headers->Date;
				$xp_subject_text = decode_header($headers->subject);
				$xp_from_full = decode_header($headers->fromaddress);
				$xp_from_address = get_substring($xp_from_full, '<', '>');
				$xp_header_raw = imap_fetchheader($inbox, $x);
				$parts = $structure->parts;
				$parts_count = count($parts);

			# Retrieving the message body doesn't seem to work sometimes,
			# so cantex stuck this next line in to fool it
				IF (!$parts_count) {$xp_body_text = imap_body($inbox,$x);}

				for ($temp_z=0; $temp_z <= $parts_count; $temp_z++) {
					$temp_p = NULL;
					$temp_b = NULL;
					$parts_type_main = NULL;
					$parts_subtype_main = NULL;
					if ($parts[$temp_z]->type == "") {$parts[$temp_z]->type = 0;}
					$temp_y = $temp_z + 1;
					$parts_number = '_' . $temp_y;
					$parts_type_main = strtolower($type[$parts[$temp_z]->type]);
					$parts_type["$parts_number"] = $parts_type_main . '/' . strtolower($parts[$temp_z]->subtype);
					$parts_encoding["$parts_number"] = $encoding[$parts[$temp_z]->encoding];
					$parts_size["$parts_number"] = $parts[$temp_z]->bytes;
					if (strtolower($parts[$temp_z]->disposition) == "attachment") {
						$temp_b = $parts[$temp_z]->dparameters;
						if(is_array($temp_b) || is_object($temp_b)) {
							reset($temp_b);
							while (list(, $temp_p) = each ($temp_b)) {
								if ($temp_p->attribute == "FILENAME") {
									$xp_attachments .= decode_header($temp_p->value) . ' [' . ceil($parts[$temp_z]->bytes / 1024) . ' KB]' . $line_break;
									$parts_filename["$parts_number"] = decode_header($temp_p->value);
									$parts_filesize["$parts_number"] = $parts[$temp_z]->bytes;
								}
							}
						}
					}
					if ($parts_type_main == 'multipart') {
						$parts_sub = $parts[$temp_z]->parts;
						$parts_sub_count = count($parts_sub);
						for ($temp_s = 0; $temp_s < $parts_sub_count; $temp_s++) {
							$temp_t = $temp_s + 1;
							$parts_sub_number = $parts_number . '.' . $temp_t;
							$parts_subtype_main = strtolower($type[$parts_sub[$temp_s]->type]);
							$parts_type["$parts_sub_number"] = $parts_subtype_main . '/' . strtolower($parts_sub[$temp_s]->subtype);
							$parts_encoding["$parts_sub_number"] = strtolower($encoding[$parts_sub[$temp_s]->encoding]);
							$parts_size["$parts_sub_number"] = $parts_sub[$temp_s]->bytes;
							if ($parts_subtype_main == 'multipart') {
								$parts_subsub = $parts_sub[$temp_s]->parts;
								$parts_subsub_count = count($parts_subsub);
								for ($temp_m = 0; $temp_m < $parts_subsub_count; $temp_m++) {
									$temp_n = $temp_m + 1;
									$parts_subsub_number = $parts_sub_number . '.' . $temp_n;
									$parts_type["$parts_subsub_number"] = strtolower($type[$parts_subsub[$temp_m]->type]) . '/' . strtolower($parts_subsub[$temp_m]->subtype);
									$parts_encoding["$parts_subsub_number"] = strtolower($encoding[$parts_subsub[$temp_m]->encoding]);
									$parts_size["$parts_subsub_number"] = $parts_subsub[$temp_m]->bytes;
								}
							}
						}
					}
				}


				IF (is_array($parts_type)) {
					while (list ($key, $val) = each ($parts_type)) {
						if (strlen($key) < 3) {
							$parts_structure .= '<strong>' . str_replace("_", "", $key) . '</strong>';
						} else {
							$parts_structure .= '&nbsp;&nbsp;&nbsp;<strong>' . str_replace("_", "", $key) . '</strong>';
						}
						$parts_structure .= ' _ ' . $val . ' <em>' . $parts_encoding[$key] . ' _ </em> [' . $parts_size[$key] . ']<br />';
						if ($val == 'text/plain' || $val == 'message/rfc822') {
							$xp_body_text = decode_text(imap_fetchbody($inbox, $x, str_replace("_", "", $key)), $parts_encoding[$key]);
						}
						if ($val == 'text/html') {$temp_html_key = $key;}
					}
				} ELSE {
					IF ($structure->encoding > 0) {
						$xp_body_text = decode_text(imap_body($inbox, $x), $encoding[$structure->encoding]);
						$parts_structure .= '<strong>0</strong> _ text/plain <em>' . $encoding[$structure->encoding] . '</em> _ [' . $structure->bytes . ']<br />';
					} ELSE {
						$xp_body_text = imap_body($inbox, $x);
						$parts_structure .= '<strong>0</strong> _ text/plain <em>7bit</em> _ [' . $structure->bytes . ']<br />';
					}
				}

				IF (($xp_body_text == '') && ($temp_html_key)) {
					$xp_body_text = strip_tags(decode_text(imap_fetchbody($inbox, $x, str_replace("_", "", $temp_html_key)), $parts_encoding[$temp_html_key]));
				}


				$xp_body_text = preg_replace("/(\015\012)|(\015)|(\012)/", "$line_break", $xp_body_text);
				$xp_attachments = str_replace("$line_break$line_break","$line_break", $xp_attachments);

			# calculating the message size
				if (is_array($parts_size)) {
					$xp_size = ceil(array_sum($parts_size) / 1024);
				} else {
					$xp_size = ceil($structure->bytes / 1024);
				}

				if ($conf_magicquotes == 0) {
					foreach($GLOBALS as $temp_k=>$temp_v) {
						if (substr_count($temp_k, "xp_") > 0) {
							$GLOBALS[$temp_k] = addslashes($temp_v);
						}
					}
				} # END XEOPORT


			IF (!$Silent) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From '.$xp_from_address.'<br>';}

			# Check "from" to see if from a valid client

			# Build query string
				$query = ""; $result = ""; $numrows = 0;
				$aquery = ""; $aresult = ""; $anumrows = 0;

			# Check if any additional client contacts. Used to build actual query later
				$aquery = "SELECT * FROM ".$_DBCFG['clients_contacts'];
				$aresult = db_query_execute($aquery);
				$anumrows = db_query_numrows($aresult);

				$query  = "SELECT DISTINCT cl_id from " . $_DBCFG['clients'];
				IF ($anumrows) {$query .= ", " . $_DBCFG['clients_contacts'];}
				$query .= " WHERE ";
				IF ($anumrows) {$query .= "(".$_DBCFG['clients'].".";}
				$query .= "cl_email='" . $xp_from_address . "'";
				IF ($anumrows) {
					$query .= ") OR (".$_DBCFG['clients_contacts'].".contacts_email='";
					$query .= $xp_from_address . "' AND ".$_DBCFG['clients_contacts'];
					$query .= ".contacts_cl_id=".$_DBCFG['clients'].".cl_id)";
				}

			# Process query
				$result = db_query_execute($query);
	  			$numrows = db_query_numrows($result);


			# If a valid client, create or update a support ticket
				IF ($numrows) {
					while(list($cl_id) = db_fetch_row($result)) {
						$Processed++;

					# Break the subject line apart using space as seperator
						$pieces = explode(" ",$xp_subject_text);

					# Look for "Ticket";
						$TicketID = 0;
						FOR ($tp=0; $tp <= count($pieces); $tp++) {
							IF ($pieces[$tp] == "Ticket") {     // Next text is our Ticket ID
			        	        $TicketID = $pieces[$tp+1];
								break;
							}
						}

						IF ($TicketID) {
							IF (!$Silent) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appending to ticket<br>';}

						# Add helpdesk response to existing ticket
							$tt_query = ""; $tt_result = ""; $tt_numrows	= 0;
							$xp_body_text = do_addslashes($xp_body_text);
							$xp_subject_text = do_addslashes($xp_subject_text);

						# Build SQL and execute
							$tt_query	= "INSERT INTO ".$_DBCFG['helpdesk_msgs'];
							$tt_query	.= " (hdi_tt_id, hdi_tt_time_stamp";
							$tt_query	.= ", hdi_tt_cl_id, hdi_tt_ad_id, hdi_tt_message";
							$tt_query	.= ")";
							$tt_query	.= " VALUES ( ";
							$tt_query	.= "'$TicketID','$xp_time_unix'";
							$tt_query	.= ",'$cl_id','0','$xp_body_text'";
							$tt_query	.= ")";
							$tt_result	= db_query_execute ($tt_query) OR DIE("Unable to complete request");
						} ELSE {
							IF (!$Silent) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Creating ticket<br>';}


						# Create a new ticket
						#Get max / create new tt_id
							$_max_hd_tt_id	= do_get_max_hd_tt_id();

							$xp_body_text = do_addslashes($xp_body_text);
							$xp_subject_text = do_addslashes($xp_subject_text);
						# Build SQL and execute
							$tt_query = "INSERT INTO ".$_DBCFG['helpdesk'];
							$tt_query .= " (hd_tt_id, hd_tt_cl_id, hd_tt_cl_email";
							$tt_query .= ", hd_tt_time_stamp, hd_tt_priority, hd_tt_category";
							$tt_query .= ", hd_tt_subject, hd_tt_message, hd_tt_cd_id";
							$tt_query .= ", hd_tt_url, hd_tt_status, hd_tt_closed";
							$tt_query .= ", hd_tt_rating";
							$tt_query .= ")";
							$tt_query .= " VALUES ( $_max_hd_tt_id+1";
							$tt_query .= ",'$cl_id','$xp_from_address'";
							$tt_query .= ",'$xp_time_unix','$_ACFG[HELPDESK_AUTO_IMPORT_DEFAULT_PRIORITY]','$_ACFG[HELPDESK_AUTO_IMPORT_DEFAULT_CATEGORY]'";
							$tt_query .= ",'$xp_subject_text','$xp_body_text','0'";
							$tt_query .= ",'','$_ACFG[HELPDESK_AUTO_IMPORT_DEFAULT_STATUS]','$_LANG[_HDESK][Status_Open]'";
							$tt_query .= ",'0'";
							$tt_query .= ")";
							$tt_result	= db_query_execute ($tt_query) OR DIE("Unable to complete request");
						}

					# Email the ticket to admin
						IF (!$Silent) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Emailing to admin<br>';}
						$data['hd_tt_id'] = $_max_hd_tt_id+1;
						$data['stage'] = 0;
						$_out .= do_mail_helpdesk_tt( $data, '1' ).$_nl;


					# If a valid client and $_CCFG['DELETE_IMPORTED_MESSAGES'] then delete message on server
						IF ($_ACFG['HELPDESK_AUTO_IMPORT_DELETE_IMPORTED_MESSAGES']) {
							IF (!$Silent) {echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Marking for deletion<br>';}
							$DeleteIt = imap_delete($inbox,$x);}
					}
				}
			}

		# Perform delete of any messages flagged for deletion
			IF (!$Silent) {echo '<br>Deleting Messages<br>';}
			$DeletedEmail = imap_expunge($inbox);

		# Close The Connection
			IF (!$Silent) {echo 'Closing connection to mail-server';}
			imap_close($inbox);

		} ELSE {
			echo 'Unable to connect to mail-server';
		}

	# Done, with Number of messages processed
		IF (!$Silent) {
			echo $_out.'<br><br>';
			echo 'Message(s) processed: '.$total.'<br>';
			echo 'Message(s) created or appended: '.$Processed;
		}
	}
?>
