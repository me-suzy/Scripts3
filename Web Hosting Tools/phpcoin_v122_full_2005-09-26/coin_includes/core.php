<?php

/**************************************************************
 * File: 		Core Function- File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *	- basic core file / functions
 *	- Translation File: lang_base.php
**************************************************************/

# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF (eregi("core.php", $_SERVER["PHP_SELF"])) {
		require_once ('session_set.php');
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
		html_header_location('error.php?err=01');
		exit;
	}

/**************************************************************
 * Load includes (pre-db load)
**************************************************************/

	# Include config file (must be first include load after paths)
		require_once ($_PACKAGE['DIR'].'config.php');

	# Include common file
		require_once ($_CCFG['_PKG_PATH_INCL'].'common.php');

	# Include redirect file
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');

	# Include version info
		require_once ($_PACKAGE['DIR'] . "version.php");
		$ThisVersionIs = str_replace("b","",$ThisVersion);

	# Include SMTP library
		require_once ($_CCFG['_PKG_PATH_INCL'].'smtp.php');

/**************************************************************
 * Offline redirect (require config.php)
**************************************************************/
	# Check Offline flag and redirect if required.
		IF ($_CCFG['_PKG_MODE_OFFLINE'] == 1) {
			html_header_location("index_offline.html");
			exit();
		}


/**************************************************************
 * Database Call and load remaining (require config.php)
**************************************************************/
	# Include db.php file and connect
		require_once ($_CCFG['_PKG_PATH_INCL'].'db.php');

	# Create db Instance and Connect (check for not install
//		IF ( !$_INSTALL ) {
			$db_coin = new db_funcs();
			$db_coin->db_connect();
			IF (!$db_coin->connection) { exit(); }
//		}


# Include constants file (requires db_config for prefix)
	require_once ($_CCFG['_PKG_PATH_INCL'].'constants.php');

# Connect to db
	$db_coin->db_select_db();

	# Dim some vars
		$InstalledVersion = '';
		$Versions_Table_Installed = 0;

	# See what tables are installed
		$result = mysql_query("SHOW TABLES");
		$num_results = mysql_num_rows($result);
		IF ($num_results) {
			FOR ($i = 0; $i < $num_results; $i++) {
				$row = mysql_fetch_array($result);
				IF ($row[0] == $_DBCFG['versions']) {$Versions_Table_Installed++;}
			}
		}

	# If version table is present, what version is installed?
		IF ($Versions_Table_Installed) {
			$query = "SELECT v_ver FROM ".$_DBCFG['versions'];
			$result	= db_query_execute($query);
			IF ($result) {
				$numrows = db_query_numrows($result);
				IF ($numrows) {
					while ($row = db_fetch_array($result)) {
						$row['v_ver'] = strtolower($row['v_ver']);
						$InstalledVersion = str_replace("v","",$row['v_ver']);
					}
				}
			}
		}

	# Not current version, so bugout to install page
		IF ($InstalledVersion != $ThisVersionIs) {
			html_header_location("coin_setup/setup.php");
			exit;
		}



/**************************************************************
 * Load parameters table
**************************************************************/
	global $_PARM;
	IF ( $db_coin->connection ) {
		$_PARM = do_parameter_load();
			FOR ($i = 0; $i < count($_PARM); $i++)
				{
					IF ( $_PARM[$i]['parm_group'] == 'common' )
						{ $_CCFG[$_PARM[$i]['parm_name']]	= $_PARM[$i]['parm_value']; }

					IF ( $_PARM[$i]['parm_group'] == 'theme' )
						{ $_TCFG[$_PARM[$i]['parm_name']]	= $_PARM[$i]['parm_value']; }

					IF ( $_PARM[$i]['parm_group'] == 'user' )
						{ $_UVAR[$_PARM[$i]['parm_name']]	= $_PARM[$i]['parm_value']; }

					IF ( $_PARM[$i]['parm_group'] == 'automation' )
						{ $_ACFG[$_PARM[$i]['parm_name']]	= $_PARM[$i]['parm_value']; }

				}

			IF ( $_CCFG['_DB_PKG_THEME_ENABLE'] == 1 )
				{
					# Re-Calc theme related vars
						$_CCFG['_PKG_URL_THEME']			= $_CCFG['_PKG_URL_BASE'].'coin_themes/'.$_CCFG['_DB_PKG_THEME'].'/';
						$_CCFG['_PKG_URL_THEME_IMGS']		= $_CCFG['_PKG_URL_BASE'].'coin_themes/'.$_CCFG['_DB_PKG_THEME'].'/images/';
						$_CCFG['_PKG_PATH_THEME']		= $_CCFG['_PKG_PATH_BASE'].'coin_themes/'.$_CCFG['_DB_PKG_THEME'].'/';
				}
			IF ( $_CCFG['_DB_PKG_LANG_ENABLE'] == 1 )
				{
					# Re-Calc language related vars
						$_CCFG['_PKG_PATH_LANG']			= $_CCFG['_PKG_PATH_BASE'].'coin_lang/'.$_CCFG['_DB_PKG_LANG'].'/';
				}
		}


/**************************************************************
 * Do Set Site Session
**************************************************************/
	$_sret = do_session_set();

/**************************************************************
 * Do Banned IP Check / Redirect
**************************************************************/
	# Load list and check for match, redirect on match.
		IF ( $_CCFG['_PKG_ENABLE_IP_BAN'] == 1 && $db_coin->connection )
			{
				# Set Query for select, execute, and check
					$q = ""; $r= ""; $n = 0;
					$q	= "SELECT banned_ip FROM ".$_DBCFG['banned']." ORDER BY banned_ip ASC";
					$r	= db_query_execute($q);
					$n	= db_query_numrows($r);

				# Loop return for match
					IF (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					} ELSE {
						$ip = $_SERVER["REMOTE_ADDR"];
					}

					while(list($banned_ip) = db_fetch_row($r))
						{ IF ( $_ip == $banned_ip ) { html_header_location('error.php?err=50'); exit; } }
			}


/**************************************************************
* Check count of menu items on eash side, adjust if none.
**************************************************************/
	global $_SYS;
	IF ( $_TCFG['_DISABLE_MENU_COLS'] != 1 ) { $_mbi_count = do_menu_items_count(); }
	IF ( $_mbi_count['count_left'] > 0 )  { $_SYS['_do_col_left'] = 1; } ELSE { $_SYS['_do_col_left'] = 0; }
	IF ( $_mbi_count['count_right'] > 0 )  { $_SYS['_do_col_right'] = 1; } ELSE { $_SYS['_do_col_right'] = 0; }


/**************************************************************
 * Load Theme Related and Base / Config language files
**************************************************************/
	# Load theme related files
		require_once ($_CCFG['_PKG_PATH_LANG'].'lang_theme.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_theme_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_theme_override.php');
		}
		require_once ($_CCFG['_PKG_PATH_THEME'].'config.php');
		require_once ($_CCFG['_PKG_PATH_THEME'].'core.php');

	# Include language file (must be after parameter load to use them)
		require_once ($_CCFG['_PKG_PATH_LANG'].'lang_base.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_base_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_base_override.php');
		}
		require_once ($_CCFG['_PKG_PATH_LANG'].'lang_config.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_config_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_config_override.php');
		}


/**************************************************************
 * Load Custom Function / Language files and API File
**************************************************************/
	# Load custom related files
		require_once ($_CCFG['_PKG_PATH_INCL'].'custom.php');
		require_once ($_CCFG['_PKG_PATH_LANG'].'lang_custom.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_custom_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_custom_override.php');
		}

	# Load API related files
		require_once ($_CCFG['_PKG_PATH_INCL'].'api.php');
		require_once ($_CCFG['_PKG_PATH_LANG'].'lang_api.php');
		IF (file_exists($_CCFG['_PKG_PATH_LANG'].'lang_api_override.php')) {
			require_once($_CCFG['_PKG_PATH_LANG'].'lang_api_override.php');
		}


/**************************************************************
 * Load License Compliance Files
**************************************************************/
	# 	require_once ('license.php');

/**************************************************************
 *                    Start Core Functions
**************************************************************/


/**************************************************************
 * Function:	do_parse_input_data ( $aentry )
 * Arguments:	$aentry - Input entry to be parsed.
 * Returns:		argument string with stuff parsed out.
 * Description:	For stripping possible hacking input strings.
 * Notes:
 *	- See config file: $_CCFG['PARSE_USER_ENTRY'][] array for
 *	  items to parse out array.
**************************************************************/
function do_parse_input_data ( $aentry ) {
	# Dim some Vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Also added- parse out if does not start with letter / character to avoid spaces entries
		IF ( !eregi('^[a-zA-Z0-9_\-]', $aentry) ) { $aentry = ''; }

	# Loop array and load list
		$_str_result = $aentry;

		FOR ($i = 0; $i < count($_CCFG['PARSE_USER_ENTRY']); $i++) {
			$_str_search	= $_CCFG['PARSE_USER_ENTRY'][$i];
			$_str_replace	= '<b>****</b>';
			$_str_result	= eregi_replace ( $_str_search, $_str_replace, $_str_result);
		}

	# Return results
		return $_str_result;
}


/**************************************************************
 * Site Session Handlers (set,select,update)
**************************************************************/
# Do session purge, update, or insert as required
function do_session_set()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Get / Set some vars
			$_SEC	= get_security_flags ();
			$_si	= session_id();
			$_tm	= dt_get_uts();
			$_ip	= $_SERVER["REMOTE_ADDR"];
			$_pv	= $_CCFG['S_AGE_IN_SECONDS'];

		# Do Purge (time in seconds)
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "DELETE FROM ".$_DBCFG['sessions']." WHERE ($_tm - s_time_last) > $_pv";
			$result 	= db_query_execute($query);
			$eff_rows	= db_query_affected_rows ();

		# Try select existing for either update or insert
			$query = ""; $result = ""; $numrows = 0;
			$query	= "SELECT s_id FROM ".$_DBCFG['sessions'];
			$query	.= " WHERE s_id = '".$_si."'";
			$result	= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Check if exist, update or insert as required
			IF ( $numrows == 1 )
				{
					# Do update existing
						$query = ""; $result = ""; $numrows = 0;
						$query 		= "UPDATE ".$_DBCFG['sessions']." SET ";
						$query 		.= "s_time_last = '$_tm',s_ip = '$_ip'";
						$query 		.= ",s_is_admin = '".$_SEC['_sadmin_flg']."'";
						$query 		.= ",s_is_user = '".$_SEC['_suser_flg']."'";
						$query 		.= " WHERE s_id = '".$_si."'";
						$result		= db_query_execute($query);
						$numrows	= db_query_affected_rows ();
				}
			ELSE
				{
					# Do Insert
						$query = ""; $result = ""; $numrows = 0;
						$query 		= "INSERT INTO ".$_DBCFG['sessions']." ( ";
						$query 		.= "s_id, s_time_init, s_time_last, s_ip";
						$query 		.= ",s_is_admin, s_is_user";
						$query 		.= " ) VALUES ( ";
						$query 		.= "'$_si','$_tm','$_tm','$_ip'";
						$query 		.= ",'$_SEC[_sadmin_flg]','$_SEC[_suser_flg]'";
						$query 		.= ")";
						$result 	= db_query_execute($query);
						$insert_id	= db_query_insertid ();
				}

		return 1;
	}

# Do session select data
function do_session_select()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();

		# Do select existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "SELECT * FROM ".$_DBCFG['sessions'];
			$query 		.= " WHERE s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$data['numrows'] = $numrows;
			while ($row = db_fetch_array($result))
			{
				# Rebuild Data Array with returned record
					$data['numrows']				= $numrows;
					$data['s_id']					= $row['s_id'];
					$data['s_time_init']			= $row['s_time_init'];
					$data['s_time_last']			= $row['s_time_last'];
					$data['s_ip']					= $row['s_ip'];
					$data['s_is_admin']				= $row['s_is_admin'];
					$data['s_is_user']				= $row['s_is_user'];
					$data['s_time_last_contact']	= $row['s_time_last_contact'];
					$data['s_time_last_order']		= $row['s_time_last_order'];
			}

		return $data;
	}


# Do session update data-
function do_session_update($adata)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set some vars
			$_si = session_id();
			$_tm = dt_get_uts();

		# Do update existing
			$query = ""; $result = ""; $numrows = 0;
			$query 		= "UPDATE ".$_DBCFG['sessions']." SET s_time_last = '$_tm'";
			IF ( $adata['set_last_contact'] == 1 )
				{ $query .= ",s_time_last_contact = '$_tm'"; }
			IF ( $adata['set_last_order'] == 1 )
				{ $query .= ",s_time_last_order = '$_tm'"; }
			$query 		.= " WHERE s_id = '".$_si."'";
			$result		= db_query_execute($query);
			$numrows	= db_query_affected_rows ();

		return 1;
	}



/**************************************************************
 * Function:	do_parameter_load()
 * Arguments:
 * Returns:		$_PARM		- Parameters Array
 * Description:	Function load parameters from database
 * Notes:
 *	-
**************************************************************/
function do_parameter_load()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Get parameters information (to reduce return, do not load descrip and notes)
			$query	= "SELECT parm_id, parm_group, parm_group_sub, parm_type, parm_name, parm_value";
			$query	.= " FROM ".$_DBCFG['parameters'];
			$query	.= " WHERE ".$_DBCFG['parameters'].".parm_group != 'undefined'";
			$query	.= " ORDER BY parm_group ASC, parm_name ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Check Return and process results
			IF ( $numrows )
				{
					# Process query results
						$cnt=0;
						while ($row = db_fetch_array($result))
						{
							# Set data array
								$_PARM[$cnt]['parm_group']		= $row['parm_group'];
								$_PARM[$cnt]['parm_group_sub']	= $row['parm_group_sub'];
								$_PARM[$cnt]['parm_type']		= $row['parm_type'];
								$_PARM[$cnt]['parm_name']		= $row['parm_name'];
								$_PARM[$cnt]['parm_value']		= $row['parm_value'];

							# Increment count
								$cnt++;
						}
				}

		# Set return
			return $_PARM;
	}

/**************************************************************
 * Function:	get_security_flags ()
 * Arguments:	none
 * Returns:		$_SEC (security array)
 * Description:	Function for getting admin / user flags
 * Notes:
 *	-
**************************************************************/
function get_security_flags () {
	# Dim some vars
		global $HTTP_SESSION_VARS, $_SESSION;

	# Check session's registered and return accordingly
		IF ( !isset($_SESSION['_sadmin_flg']) )			{ $_SEC['_sadmin_flg'] = 0; }				ELSE { $_SEC['_sadmin_flg'] = $_SESSION['_sadmin_flg']; }
		IF ( !isset($_SESSION['_sadmin_id']) )			{ $_SEC['_sadmin_id'] = 0; }				ELSE { $_SEC['_sadmin_id'] = $_SESSION['_sadmin_id']; }
		IF ( !isset($_SESSION['_sadmin_name']) )		{ $_SEC['_sadmin_name'] = 'none'; }			ELSE { $_SEC['_sadmin_name'] = $_SESSION['_sadmin_name']; }
		IF ( !isset($_SESSION["_sadmin_name_first"]) )	{ $_SEC['_sadmin_name_first'] = 'none'; }	ELSE { $_SEC['_sadmin_name_first'] = $_SESSION['_sadmin_name_first']; }
		IF ( !isset($_SESSION["_sadmin_name_last"]) )	{ $_SEC['_sadmin_name_last'] = 'none'; }	ELSE { $_SEC['_sadmin_name_last'] = $_SESSION['_sadmin_name_last']; }
		IF ( !isset($_SESSION['_sadmin_perms']) )		{ $_SEC['_sadmin_perms'] = 0; }				ELSE { $_SEC['_sadmin_perms'] = $_SESSION['_sadmin_perms']; }
		IF ( !isset($_SESSION['_suser_flg']) )			{ $_SEC['_suser_flg'] = 0; }				ELSE { $_SEC['_suser_flg'] = $_SESSION['_suser_flg']; }
		IF ( !isset($_SESSION['_suser_id']) )			{ $_SEC['_suser_id'] = 0; }					ELSE { $_SEC['_suser_id'] = $_SESSION['_suser_id']; }
		IF ( !isset($_SESSION['_suser_name']) )			{ $_SEC['_suser_name'] = 'none'; }			ELSE { $_SEC['_suser_name'] = $_SESSION['_suser_name']; }
		IF ( !isset($_SESSION["_suser_name_first"]) )	{ $_SEC['_suser_name_first'] = 'none'; }	ELSE { $_SEC['_suser_name_first'] = $_SESSION['_suser_name_first']; }
		IF ( !isset($_SESSION["_suser_name_last"]) )	{ $_SEC['_suser_name_last'] = 'none'; }		ELSE { $_SEC['_suser_name_last'] = $_SESSION['_suser_name_last']; }
		IF ( !isset($_SESSION['_suser_groups']) )		{ $_SEC['_suser_groups'] = 0; }				ELSE { $_SEC['_suser_groups'] = $_SESSION['_suser_groups']; }

	# Return security array
		Return $_SEC;
}


/**************************************************************
 * Function:	do_decode_perms_admin ($aperms)
 * Arguments:	$aperms		- Admin perms value (0-65535)
 * Returns:		$_PERMS (bit-wise array values- 16-bit)
 * Description:	Function for getting admin bit-wise permissions
 * Notes:
 *	-
**************************************************************/
function do_decode_perms_admin($aperms)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Decode into array
			$_bin = str_pad(decbin($aperms), 16, "0", STR_PAD_LEFT);
			$_PERMS['AP00']	= $_bin;
			$_PERMS['AP16']	= $_bin{0};
			$_PERMS['AP15']	= $_bin{1};
			$_PERMS['AP14']	= $_bin{2};
			$_PERMS['AP13']	= $_bin{3};
			$_PERMS['AP12']	= $_bin{4};
			$_PERMS['AP11']	= $_bin{5};
			$_PERMS['AP10']	= $_bin{6};
			$_PERMS['AP09']	= $_bin{7};
			$_PERMS['AP08']	= $_bin{8};
			$_PERMS['AP07']	= $_bin{9};
			$_PERMS['AP06']	= $_bin{10};
			$_PERMS['AP05']	= $_bin{11};
			$_PERMS['AP04']	= $_bin{12};
			$_PERMS['AP03']	= $_bin{13};
			$_PERMS['AP02']	= $_bin{14};
			$_PERMS['AP01']	= $_bin{15};

		# Return decoded array
			return $_PERMS;
	}


/**************************************************************
 * Function:	do_decode_groups_user ($agroups)
 * Arguments:	$agroups	- User (client) perms value (0-255)
 * Returns:		$_GROUPS (bit-wise array values- 8-bit)
 * Description:	Function for getting groups bit-wise groups
 * Notes:
 *	-
**************************************************************/
function do_decode_groups_user($agroups)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;

		# Decode into array
			$_bin = str_pad(decbin($agroups), 8, "0", STR_PAD_LEFT);
			$_GROUPS['UG00']	= $_bin;
			$_GROUPS['UG08']	= $_bin{0};
			$_GROUPS['UG07']	= $_bin{1};
			$_GROUPS['UG06']	= $_bin{2};
			$_GROUPS['UG05']	= $_bin{3};
			$_GROUPS['UG04']	= $_bin{4};
			$_GROUPS['UG03']	= $_bin{5};
			$_GROUPS['UG02']	= $_bin{6};
			$_GROUPS['UG01']	= $_bin{7};

		# Return decoded array
			return $_GROUPS;
	}


/**************************************************************
 * Function:	Check_User_Group($desiredgroup,$actualgroups)
 * Arguments:	$desiredgroup - Bit Flag of group we are interested in
			$actualgroups - bindec string of user's actual groups
 * Returns:	true or false, user is in desired group
 * Description:	Function for determining if specified bit set
			in decimal to 8-bit binary string
 * Notes:
**************************************************************/
function Check_User_Group($DesiredGroup,$ActualGroups) {
	$ValidGroup=0;
	$binstring = strrev(str_pad(decbin ($ActualGroups),8,"0",STR_PAD_LEFT));
	$bitarray = explode(":",chunk_split($binstring, 1, ":"));
	for($x = 0; $x < 8; $x++) {if (($x == $DesiredGroup-1) && ($bitarray[$x])) {$ValidGroup++;}}
	return $ValidGroup;
}


/**************************************************************
 * Function:	do_decode_DB16 ($_DV)
 * Arguments:	$_DV	- Decimal value to decode (0-65535)
 * Returns:	$_BV (bit-wise array values- 16-bit)
 * Description:	Function for decoding decimal to 16-bit binary
 * Notes:
 *	-
**************************************************************/
# Do decode Decimal to Binary (16-bit 0-65535)
function do_decode_DB16($_DV)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Decode decimal value into array
			$_bin = str_pad(decbin($_DV), 16, "0", STR_PAD_LEFT);
			$_BV['B00']	= $_bin;
			$_BV['B16']	= $_bin{0};
			$_BV['B15']	= $_bin{1};
			$_BV['B14']	= $_bin{2};
			$_BV['B13']	= $_bin{3};
			$_BV['B12']	= $_bin{4};
			$_BV['B11']	= $_bin{5};
			$_BV['B10']	= $_bin{6};
			$_BV['B09']	= $_bin{7};
			$_BV['B08']	= $_bin{8};
			$_BV['B07']	= $_bin{9};
			$_BV['B06']	= $_bin{10};
			$_BV['B05']	= $_bin{11};
			$_BV['B04']	= $_bin{12};
			$_BV['B03']	= $_bin{13};
			$_BV['B02']	= $_bin{14};
			$_BV['B01']	= $_bin{15};

		# Return decoded binary values array
			return $_BV;
	}


/**************************************************************
 * Function:	do_encode_BD16 ($_BV)
 * Arguments:	$_BV	- Binary array to encode (16-bit)
 * Returns:		$_dec 	- Encoded decimal value  (0-65535)
 * Description:	Function for encoding 16-bit binary to decimal
 * Notes:
 *	-
**************************************************************/
# Do encode Binary to Decimal (16-bit 0-65535)
function do_encode_BD16($_BV)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Encode into 16-bit binary string
			IF ( $_BV['B16'] != 1 ) { $_BV['B16'] = 0; }
			IF ( $_BV['B15'] != 1 ) { $_BV['B15'] = 0; }
			IF ( $_BV['B14'] != 1 ) { $_BV['B14'] = 0; }
			IF ( $_BV['B13'] != 1 ) { $_BV['B13'] = 0; }
			IF ( $_BV['B12'] != 1 ) { $_BV['B12'] = 0; }
			IF ( $_BV['B11'] != 1 ) { $_BV['B11'] = 0; }
			IF ( $_BV['B10'] != 1 ) { $_BV['B10'] = 0; }
			IF ( $_BV['B09'] != 1 ) { $_BV['B09'] = 0; }
			IF ( $_BV['B08'] != 1 ) { $_BV['B08'] = 0; }
			IF ( $_BV['B07'] != 1 ) { $_BV['B07'] = 0; }
			IF ( $_BV['B06'] != 1 ) { $_BV['B06'] = 0; }
			IF ( $_BV['B05'] != 1 ) { $_BV['B05'] = 0; }
			IF ( $_BV['B04'] != 1 ) { $_BV['B04'] = 0; }
			IF ( $_BV['B03'] != 1 ) { $_BV['B03'] = 0; }
			IF ( $_BV['B02'] != 1 ) { $_BV['B02'] = 0; }
			IF ( $_BV['B01'] != 1 ) { $_BV['B01'] = 0; }
			$_bin	= $_BV['B16'].$_BV['B15'].$_BV['B14'].$_BV['B13'].$_BV['B12'].$_BV['B11'].$_BV['B10'].$_BV['B09'];
			$_bin	.= $_BV['B08'].$_BV['B07'].$_BV['B06'].$_BV['B05'].$_BV['B04'].$_BV['B03'].$_BV['B02'].$_BV['B01'];
			$_dec	= bindec($_bin);

		# Return decoded array
			return $_dec;
	}


/**************************************************************
 * Function:	do_get_version()
 * Arguments:	none
 * Returns:		array- Latest version record from versions
 * Description:	Function version from database
 * Notes:
 *	-
**************************************************************/
function do_get_version()
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Set Query for select
			$query = ""; $result = ""; $numrows = 0;
			$query		= "SELECT * FROM ".$_DBCFG['versions']." ORDER BY v_id ASC";
		#	$query		= "SELECT * FROM ".$_DBCFG['versions']." WHERE v_id = SELECT MAX(v_id) ORDER BY v_id DESC";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			IF ( $numrows ) {
				while ($row = db_fetch_array($result))
				{
					# Set values
						$_vdata['comp_id']		= $row['v_id'];
						$_vdata['comp_type']	= $row['v_ts'];
						$_vdata['comp_name']	= $row['v_ver'];
						$_vdata['comp_mod']		= $row['v_type'];
						$_vdata['version']		= dt_make_datetime ( $row[v_ts], $_CCFG['_PKG_DATE_FORMAT_SHORT_DT'] );
						$_vdata['version']		.= $_sp.'-'.$_sp.$row['v_ver'].$_sp.'-'.$_sp.$row['v_type'];
				}
			}
		return $_vdata;
	}


/**************************************************************
 * Function:	do_highlight_text ( $aentry, $ahltext )
 * Arguments:	$aentry		- String to search / replace in.
 * 				$ahltext	- Value of string to match.
 * Returns:		$aentry string with $ahltext highlighted.
 * Description:	For highlighting text in other string.
 * Notes:
 *
**************************************************************/
function do_highlight_text ( $aentry, $ahltext )
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		IF ($ahltext)
			{
				$evalReplace	= "preg_replace('#\b(".$ahltext.")\b#i', '<span class=\"PSR\"><b>\\\\1</b></span>', '\\0')";
				$_str			= '>'.$aentry.'<';
				$_str			= preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', $evalReplace, $_str);
				$_str			= substr($_str, 1, -1);
				$_str_result	= str_replace('\"', '"', $_str);
			}
		return $_str_result;
	}


/**************************************************************
 * Function:	do_****slashes ( $astring )
 * Arguments:	$astring	- String to process slashes on
 * Returns:		$rstring string with slashes processed
 * Description:	For processing slashes based on "magic quotes"
 * Notes:
 *	- **** = Add will add if magic quotes off
 *	- **** = Strip will strip if magic quotes on
**************************************************************/
function do_addslashes($astring) {
	IF (get_magic_quotes_gpc()) {
		$rstring = $astring;
	} ELSE {
		$rstring = addslashes($astring);
	}
	// $rstring = addslashes($astring);
	return $rstring;
}

function do_stripslashes($astring) {
	IF (get_magic_quotes_gpc()) {
		$rstring = stripslashes($astring);
	} ELSE {
		$rstring = $astring;
	}
	//$rstring = stripslashes($astring);
	return $rstring;
}


/**************************************************************
 * Function:	do_password_create ()
 * Arguments:	none
 * Returns:		new random password string
 * Description:	Function for random password create
 * Notes:
 *	-
**************************************************************/
function do_password_create ()
	{
		# Generate new password string and return
	        mt_srand((double)microtime()*1000000);
	        while ($i < 8) { $_nps .= chr(mt_rand(97,122)); $i++; }
			return $_nps;
	}


/**************************************************************
 * Function:	do_password_crypt ($apwrd_input)
 * Arguments:	$apwrd_input	- password string to encrypt
 * Returns:		encrypted password string
 * Description:	Function for encrypt passed string
 * Notes:
 *	-
**************************************************************/
function do_password_crypt ( $apwrd_input )
	{
		# Generate encrypted password
			return crypt($apwrd_input);
	}


/**************************************************************
 * Function:	do_password_check ($apwrd_input, $apwrd_encrypt)
 * Arguments:	$apwrd_input	- non-encrypted password input
 *				$apwrd_encrypt	- encrypted stored password
 * Returns:		1 if match, 0 if no match
 * Description:	Function for password comparisons
 * Notes:
 *	-
**************************************************************/
function do_password_check ( $apwrd_input, $apwrd_encrypt ) {
	# Get salt parameter from encrypted password
		$_salt = substr($apwrd_encrypt, 0, CRYPT_SALT_LENGTH);

	# Generate encrypted password of input
		$apwrd_input_encrypt = crypt($apwrd_input, $_salt);

	# Compare entered vs encrypted
		IF ( $apwrd_input_encrypt == $apwrd_encrypt) {
		# Passwords match, login successful
			return 1;

		} ELSE {
		# Passwords no-match, login failed
			return 0;
		}
}


/**************************************************************
 * Function:	do_domain_validate ( $adomain_name )
 * Arguments:	$adomain_name	- domain name to check
 * Returns:		1 if valid, 0 if not valid
 * Description:	Function for domain name comparisons
 * Notes:
 *	- Domain name only: mydomain.tld (no www. etc.)
**************************************************************/
function do_domain_validate($adomain_name) {
	# Dim some vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$result= ""; $numrows = 0; $_RET = 0; $xx=0;

	# If "accept anything, return "good"
		IF ($_CCFG['DOM_FORCE_CHECK_TRUE'] == 1) {return 1;}

	# If "none", return "good"
		IF (strtolower($adomain_name) == "none") {return 1;}

	# If name not passed in, return "no-good"
		IF (!$adomain_name) {return 0;}

	# Do Database lookup of registrars
	# Set Query for select.
		$query = "SELECT whois_id, whois_value FROM ".$_DBCFG['whois']." WHERE whois_include=1 ORDER BY whois_value ASC";

	# Do select and return check
		$result  = db_query_execute($query);
		$numrows = db_query_numrows($result);

	# If no registrars, return "no good"
		IF (!$numrows) {return 0;}

	# Build domain extensions array
		while(list($whois_id, $whois_value) = db_fetch_row($result)) {
			$_CCFG['_DOMAIN_EXT'][$xx] = $whois_value;	// string value for this domain to display on form
			$xx++;										// Increment counter
		}

	# Loop through array and check domain name and extension
		FOR ($i = 0; $i < $xx; $i++) {
			IF (eregi('^[a-zA-Z0-9_\.\-]+\.'.$_CCFG['_DOMAIN_EXT'][$i].'$', $adomain_name)) {$_RET = 1;}
		}

	# Set return
		return $_RET;
}


/**************************************************************
 * Function:	do_domain_ext_valid_list($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html domain ext. select list
 * Notes:
 *	-
**************************************************************/
function do_domain_ext_valid_list( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;

		# Set Query for select.
			$query	= "SELECT whois_id, whois_value FROM ".$_DBCFG['whois']." WHERE whois_include=1 ORDER BY whois_display ASC";

		# Do select and return check
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build domain extensions array
			$xx=0;
			while(list($whois_id, $whois_display) = db_fetch_row($result)) {
				$_CCFG['_DOMAIN_EXT'][$xx]	= $whois_display;	// string value for this domain to display on form
				$xx++;                                          // Increment counter
			}

		# Load config array and sort,
			$_tmp_array = $_CCFG['_DOMAIN_EXT'];
			sort($_tmp_array);

		# Loop array and load list
			FOR ($i = 0; $i < count($_tmp_array); $i++)
			{
				$_out .= '<option value="'.$_tmp_array[$i].'"';
				IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
* Function: do_mail_basic ($amail)
* Arguments: $amail - mail data array
* Returns:  1=error, 0=ok
* Description: Function for sending email
*			   function can handle attachments, html and/or plain text,
*			   and authenticated or normal SMTP sessions
* Notes:
* -
**************************************************************/
function do_mail_basic($amail) {
	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp, $_SMTP;
		$Boundary = "----=_NextPart" . md5(uniqid("EMAIL"));
		$OB = $Boundary.'.001';
		$IB = $Boundary.'.002';

	# Do data error
		IF ( !$amail['recip'] )  { $_err_flag = 1; }
		IF ( !$amail['from'] )   { $_err_flag = 1; }
		IF ( !$amail['subject'] )  { $_err_flag = 1; }
		IF ( !$amail['message'] )  { $_err_flag = 1; }
		IF ( !$amail['replyto'] )  { $amail['replyto'] = $amail['from']; }

	# Prepare the message
		IF ($_CCFG[EMAIL_AS_HTML]) {
		$Html = $amail['message'];
		$Html = Replace_CRs($Html);
//		$Html = preg_replace( "/(?<!<a href=\')((http|https|ftp)+(d)?:\/\/[^<>\s]+)/i", '<a href=\'\\0\' target=\'_blank\'>\\0</a>', $Html );
		$Html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'."\n".str_replace("\n",'<br />',$Html);

		$Text = strip_tags($amail['message']);
		$Text = $Text ? $Text : "Sorry, but you need an html mailer to read this mail.";
	} ELSE {
		$Msg = $amail['message'];
		$Msg = Insert_CRs($Msg);
	}

	# Set some header items
		$_mail_hdr  = 'Date:' . date("r", dt_get_uts()) . "\n";
		$_mail_hdr .= 'From: '.$amail['from'] . "\n";
		$_mail_hdr .= 'Reply-To: '.$amail['replyto'] . "\n";

		IF ( $amail['cc'] )  {$_mail_hdr .= 'CC: '.$amail['cc'] . "\n"; }
		IF ( $amail['bcc'] ) {$_mail_hdr .= 'BCC: '.$amail['bcc'] . "\n"; }

		$pieces = explode("@",$_CCFG[_PKG_EMAIL_MAIL]);
		$_mail_hdr .= 'Message-ID: <' .md5($amail['from']). '@'. $pieces[1] .'>'. "\n";

		IF ($_CCFG[EMAIL_AS_HTML]) {
			$_mail_hdr .= 'X-Mailer: phpCOIN Mailer'."\n";
			$_mail_hdr .= 'MIME-Version: 1.0' . "\n";
			$_mail_hdr .= 'Content-Type: multipart/alternative;';
			$_mail_hdr .= 'boundary="' . $OB . "\"\n";

		# Messages start with text/html alternatives in OB
			$Msg  = "This is a multi-part message in MIME format.\n";
			$Msg .= "\n--".$OB."\n";
			$Msg .= "Content-Type: multipart/alternative;\n\tboundary=\"".$IB."\"\n";

		# plaintext section
			$Msg .= "\n--".$IB."\n";
			$Msg .= "Content-Type: text/plain;\n\tcharset=\"iso-8859-1\"\n";
			$Msg .= "Content-Transfer-Encoding: base64\n";
			$Msg .= "Content-Description: Message in plain-text form\n\n";
			$Msg .= chunk_split(base64_encode($Text))."\n\n";

		# html section
			$Msg .= "\n--".$IB."\n";
			$Msg .= "Content-Type: text/html;\n\tcharset=\"iso-8859-1\"\n";
			$Msg .= "Content-Transfer-Encoding: base64\n";
			$Msg .= "Content-Description: Message in html form\n\n";
			$Msg .= chunk_split(base64_encode($Html))."\n\n";
			$Msg .= "\n--".$IB."--\n";

		# attachments
			IF ($amail[AttmFiles]) {
				foreach($amail[AttmFiles] as $AttmFile) {
					$patharray = explode ("/", $AttmFile);
					$FileName = $patharray[count($patharray)-1];
					$Msg .= "\n--".$OB."\n";
					$Msg .= "Content-Type: application/octetstream;\n\tname=\"".$FileName."\"\n";
					$Msg .= "Content-Transfer-Encoding: base64\n";
					$Msg .= "Content-Disposition: attachment;\n\tfilename=\"".$FileName."\"\n\n";
					$fd = fopen ($AttmFile, "r");
					$FileContent = fread($fd,filesize($AttmFile));
					fclose ($fd);
					$FileContent = chunk_split(base64_encode($FileContent));
					$Msg .= $FileContent;
					$Msg .= "\n\n";
				}
			}
			$Msg .= "\n--".$OB."--\n";
			$Msg .= "\n-- End --\n";
		}

	# Call mail function
		IF ( !$_err_flag && $_CCFG['_PKG_EMAIL_OUT_DISABLE'] != 1 ) {

			IF ($_SMTP['AUTHENTICATED']) { // Send authenticated mail using sockets
				$smtp = new SMTP;
				$smtp->do_debug = 0; # sets the amount of debug information we get

			# connect to the smtp server
				IF (!$smtp->connect($_SMTP['HOST'])) {echo "Error: " . $smtp->error["error"] . "\n";}

			# say hello so we all know who we are
				$smtp->Hello($_SMTP['LOCALHOST']);
				$smtp->Authenticate($_SMTP['ACC'], $_SMTP['PASS']);

			# start the mail transaction with with the return path address
				$smtp->Mail("<". $amail['from'] .">");

			# state each recipient. NOTE: this does _not_ add to: or cc: headers to the email
				$smtp->Recipient("<". $amail['recip'] .">");

			# send the message including headers (headers must be followed by an empty newline
				$smtp->Data("Subject: ". $amail['subject'] ."\n".
                "From: ". $amail['from'] ."\n".
                "To: ". $amail['recip'] ."\n".
                $_mail_hdr . "\n" .
                "\n".
                $Msg ."\n");

			# the mail either been sent or failed either way we are done
				$smtp->quit();
				$_err_flag = 0;

			} ELSE { // Send mail using php "mail" function
				$_err_flag = mail($amail['recip'], $amail['subject'], $Msg, $_mail_hdr);
				IF ($_err_flag) {$_err_flag=0;} ELSE {$_err_flag=1;}
			}

		# Archive the message
			IF ( $_CCFG['_PKG_ENABLE_EMAIL_ARCHIVE'] && !$_err_flag) { $_ret = do_mail_archive ($amail); }
		}

	# Return
		return $_err_flag;
}



/**************************************************************
 * Function:	do_mail_pager ($amail)
 * Arguments:	$amail	- mail data array
 * Returns:		1=error, 0=ok
 * Description:	Function for pager email
 * Notes:
 *	-
**************************************************************/
function do_mail_pager ( $amail )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Do data error
			IF ( !$amail['recip'] ) 	{ $_err_flag = 1; }
			IF ( !$amail['message'] ) 	{ $_err_flag = 1; }

		# Force subject to empty
			$amail['subject'] = '';

		# Set some header items (left in just in case)
		#	$_mail_hdr = 'From: '.$amail['from'];
		#	IF ( $amail['cc'] )		{ $_mail_hdr .= "\r\n".'Cc: '.$amail['cc']; }
		#	IF ( $amail['bcc'] )	{ $_mail_hdr .= "\r\n".'Bcc: '.$amail['bcc']; }

		# Call mail function
			IF ( !$_err_flag && $_CCFG['_PKG_EMAIL_OUT_DISABLE'] != 1 )
				{
					mail($amail['recip'], $amail['subject'], $amail['message'], $_mail_hdr);
					IF ( $_CCFG['_PKG_ENABLE_EMAIL_ARCHIVE'] ) { $_ret = do_mail_archive ($amail); }
				}

		# Return
			return $_err_flag;
	}


/**************************************************************
 * Function:	do_mail_archive ($amail)
 * Arguments:	$amail	- mail data array
 * Returns:		1=error, 0=ok
 * Description:	Function for basic email archive to table
 * Notes:
 *	-
**************************************************************/
function do_mail_archive ( $amail )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$_uts = dt_get_uts();

			$amail[from]	= addslashes($amail[from]);
			$amail[recip]	= addslashes($amail[recip]);
			$amail[cc]		= addslashes($amail[cc]);
			$amail[bcc]		= addslashes($amail[bcc]);
			$amail[subject] = addslashes($amail[subject]);
			$amail[message] = addslashes($amail[message]);

		# Set query for insert and execute
			$query = "INSERT INTO ".$_DBCFG['mail_archive']." ( ";
			$query .= "ma_time_stamp, ma_fld_from, ma_fld_recip, ma_fld_cc";
			$query .= ", ma_fld_bcc, ma_fld_subject, ma_fld_message";
			$query .= ")";
			$query .= " VALUES ( ";
			$query .= "'$_uts','$amail[from]','$amail[recip]','$amail[cc]'";
			$query .= ",'$amail[bcc]','$amail[subject]','$amail[message]'";
			$query .= ")";

			$result 	= db_query_execute($query) OR DIE("Unable to complete request");
			$insert_id	= db_query_insertid ();

		# Return
			return $insert_id;
	}


/**************************************************************
 * Function:	get_mail_template ($atemplate_name)
 * Arguments:	$atemplate_name		- template name to return
 * Returns:		processed template string from database
 * Description:	Function for getting email template text
 * Notes:
 *	- Does eval() to process template variables.
**************************************************************/
function get_mail_template ($atemplate_name, $amtp)
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

		# Set query for select and execute
			$query	= "";	$result	= "";	$numrows = 0;
			$query		= "SELECT mt_text FROM ".$_DBCFG['mail_templates']." WHERE mt_name = '$atemplate_name'";
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Get value and set return
			while(list($mt_text) = db_fetch_row($result)) { $_mt_text = $mt_text; }

		# Process template (evaluate) and return
			$_MTP = $amtp;
			$_mt = addslashes($_mt_text);
			eval("\$_mt = \"$_mt\";");
			$_mt = stripslashes($_mt);

			return $_mt;
	}


/**************************************************************
 * Function:	do_user_name_exist_check($ausername, $aw)
 * Arguments:	$ausername	- User Name to check
 *				$aw			- Who: admin or user
 * Returns:		numrows- 0 is no exist
 * Description:	Function verify user name exist in database
 * Notes:
 *	-
**************************************************************/
function do_user_name_exist_check($ausername, $aw=user)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set Query for select
			IF ( $aw == 'admin' )
				{ $query	= "SELECT admin_id FROM ".$_DBCFG['admins']." WHERE admin_user_name = '$ausername' ORDER BY admin_id ASC"; }
			IF ( $aw == 'user' )
				{ $query	= "SELECT cl_id FROM ".$_DBCFG['clients']." WHERE cl_user_name = '$ausername' ORDER BY cl_id ASC"; }

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

			return $numrows;
	}


/**************************************************************
 * Function:	get_user_name_email($ausername, $aw)
 * Arguments:	$ausername	- User Name to get email
 *				$aw			- Who: admin or user
 * Returns:		numrows- 0 is no exist
 * Description:	Function returns user name email
 * Notes:
 *	-
**************************************************************/
function get_user_name_email($ausername, $aw)
	{
		# Dim some Vars
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = ""; $result = ""; $numrows = 0;

		# Set Query for select
			IF ( $aw == 'admin' )
				{ $query	= "SELECT admin_email FROM ".$_DBCFG['admins']." WHERE admin_user_name = '$ausername' ORDER BY admin_id ASC"; }
			IF ( $aw == 'user' )
				{ $query	= "SELECT cl_email FROM ".$_DBCFG['clients']." WHERE cl_user_name = '$ausername' ORDER BY cl_id ASC"; }

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Get value and set return
			while(list($email) = db_fetch_row($result)) { $_email = $email; }
			return $_email;
	}


/**************************************************************
 * Function:	do_login ($adata, $aw, $aret_flag=0)
 * Arguments:	$adata		- Data Array
 *				$aw			- Who admin or user
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function user load login
 * Notes:
 *	-
**************************************************************/
function do_login ( $adata, $aw, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_GPV, $_nl, $_sp;
			$query = ""; $result= ""; $numrows = 0;
			$_err_flag = 0;

		# Some HTML Strings (reduce text)
			$_td_str_span	= '<td class="TP3SML_BC" colspan="2">';
			$_td_str_left	= '<td class="TP3SML_NR" width="30%">';
			$_td_str_right	= '<td class="TP3SML_NL" width="70%">';

		# Set parms based on who is logging in (admin/user)
			switch($aw)
			{
				case "admin":
					$_blk_title 	= $_LANG['_BASE']['Administrative_Login_Required'];
					$_blk_action	= $_CCFG['_PKG_URL_INCL'].'session_admin.php';
					break;
				case "user":
					$_blk_title 	= $_LANG['_BASE']['Client_Login_Required'];
					$_blk_action	= $_CCFG['_PKG_URL_INCL'].'session_user.php';
					break;
				default:
					$aw				= 'user';
					$_blk_title 	= $_LANG['_BASE']['Client_Login_Required'];
					$_blk_action	= $_CCFG['_PKG_URL_INCL'].'session_user.php';
					break;
			}

		# Check / build error string.
			IF ( $adata['e']=='u' ) { $_err = $_LANG['_BASE']['Failed_Msg_User_Name']; $_err_flag = 1; }
			IF ( $adata['e']=='p' ) { $_err = $_LANG['_BASE']['Failed_Msg_Password']; $_err_flag = 1; }

		# Build Title String, Content String, and Footer Menu String

			$_tstr = $_blk_title;

			$_cstr  = '<table width="100%" border="0" cellspacing="0" cellpadding="5">'.$_nl;
			$_cstr .= '<tr><td align="center">'.$_nl;
			$_cstr .= '<form action="'.$_blk_action.'" method="post" name="login">'.$_nl;
			$_cstr .= '<table width="100%" cellspacing="0" cellpadding="1">'.$_nl;

			IF ( $_err_flag == 1 )
				{
					$_cstr .= '<tr>'.$_nl;
					$_cstr .= $_td_str_left.$_nl.'<b>****</b>'.$_sp.$_nl.'</td>'.$_nl;
					$_cstr .= $_td_str_right.$_nl.'<b>'.$_err.'</b>'.$_nl.'</td>'.$_nl;
					$_cstr .= '</tr>'.$_nl;
				}

			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_LANG['_BASE']['l_User_Name'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" type="text" name="username" size="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= '<b>'.$_LANG['_BASE']['l_Password'].$_sp.'</b>'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '<INPUT class="PSML_NL" type="password" name="password" size="'.$_CCFG['CLIENT_MAX_LEN_UNAME'].'" maxlength="'.$_CCFG['CLIENT_MAX_LEN_PWORD'].'">'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
			$_cstr .= $_sp.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= '('.$_LANG['_BASE']['Forgot_your_password'].$_sp.$_LANG['_BASE']['Click'].$_sp.'<a href="mod.php?mod=mail&mode=reset&w='.$aw.'">'.$_LANG['_BASE']['here'].'</a>'.$_sp.$_LANG['_BASE']['for reset'].')'.$_nl;
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '<tr>'.$_nl;
			$_cstr .= $_td_str_left.$_nl;
/*
			$_cstr .= '<INPUT TYPE=hidden name="mod" value="'.$adata['mod'].'">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="mode" value="'.$adata['mode'].'">'.$_nl;
*/
			while(list($key, $var) = each($_GPV)) {
				$_cstr .= '<INPUT TYPE=hidden name="'.$key.'" value="'.$var.'">'.$_nl;
			}

			$_cstr .= '</td>'.$_nl;
			$_cstr .= $_td_str_right.$_nl;
			$_cstr .= do_input_button_class_sw ('b_login', 'SUBMIT', $_LANG['_BASE']['B_Log_In'], 'button_form_h', 'button_form', '1').$_nl;
			$_cstr .= do_input_button_class_sw ('b_reset', 'RESET', $_LANG['_BASE']['B_Reset'], 'button_form_h', 'button_form', '1');
			$_cstr .= '</td>'.$_nl;
			$_cstr .= '</tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;
			$_cstr .= '</form>'.$_nl;
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '<tr><td align="right">'.$_nl;
				IF ( $aw == 'admin' )
					{ $_cstr .= do_nav_link ('login.php?w=user&o=login', $_TCFG['_IMG_CLIENTS_M'],$_TCFG['_IMG_CLIENTS_M_MO'],'',$_LANG['_BASE']['B_Log_In']); }
				ELSE
					{ IF ( $_TCFG['_ENABLE_ADMIN_LOGIN_LINK'] ) { $_cstr .= do_nav_link ('login.php?w=admin&o=login', $_TCFG['_IMG_ADMIN_M'],$_TCFG['_IMG_ADMIN_M_MO'],'',$_LANG['_BASE']['B_Log_In']); }  }
			$_cstr .= '</td></tr>'.$_nl;
			$_cstr .= '</table>'.$_nl;

			$_mstr_flag	= 0;
			$_mstr 		= ''.$_nl;

		# Call block it function
			$_out  = do_mod_block_it ($_tstr, $_cstr, $_mstr_flag, $_mstr, '1');
			$_out .= '<br>'.$_nl;

		# Return / Echo Final Output
			IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	get_datetime (&$mydt, &$mydt_display )
 * Arguments:	&$mydt			- String for logging
 *				&$mydt_display	- String for display
 *				$mytime			- unix timestamp to use
 * Returns:		arguments set by reference ( & )
 * Description:	Return Date/Time Strings
 * Notes:
 *	- Uses standard: getdate() function
 *	- Arguments are set by reference
 *	- If no $mytime argument, will use current server timestamp
**************************************************************/
# Build DateTime String with getdate() function:
	# Array objects:
	#	seconds
	# 	minutes
	#	hours
	#	mday	(day of month, integer- ex. 1-31)
	#	wday	(day of week, integer- ex. 1-7)
	#	mon		(month, integer- ex. 1-12)
	#	year
	#	yday	(day of the year, integer- ex. 1-365)
	#	weekday	(day of week, string- ex. Sunday-Saturday)
	#	month	(month, string- ex. January-December)

function get_datetime ( &$mydt, &$mydt_display, $mytime=0 )
	{
		global $_CCFG;

		IF ( $mytime <= 0 ) { $_uts = time()+($_CCFG[_PKG_DATE_SERVER_OFFSET]*3600); }
		ELSE				{ $_uts = $mytime+($_CCFG[_PKG_DATE_SERVER_OFFSET]*3600); }

		$dt				= getdate($_uts);
		$mydt			= $dt['year']."-".$dt['mon']."-".$dt['mday']." ".$dt['hours'].":".$dt['minutes'].":".$dt['seconds'];
		$mydt_display	= $dt['weekday']."- ".$dt['month']."&nbsp;".$dt['mday'].",&nbsp;".$dt['year']."&nbsp;&nbsp;".$dt['hours'].":".$dt['minutes'].":".$dt['seconds']." EST";
	}

# Build DateTime String with date() function:
# See http://www.php.net/manual/en/function.date.php for example
function get_udatetime ( &$mydt, &$mydt_display, $_format='l- F d, Y @ h:i:s a T', $mytime=0 )
	{
		global $_CCFG;

		IF ( $mytime <= 0 ) { $_uts = time()+($_CCFG[_PKG_DATE_SERVER_OFFSET]*3600); }
		ELSE				{ $_uts = $mytime+($_CCFG[_PKG_DATE_SERVER_OFFSET]*3600); }

		$dt					= getdate($_uts);
		$mydt				= date("Y-m-d H:i:s", $_uts);
		$mydt_display		= date($_format, $_uts);
	}

# Return current unix timestamp with offset:
function dt_get_uts ( )
	{
		global $_CCFG;
		return time()+($_CCFG['_PKG_DATE_SERVER_OFFSET']*3600);
	}

# Make unix timestamp from passed date array:
function dt_make_uts ( $_dt )
	{
		global $_CCFG;
		return  mktime( $_dt[hour],$_dt[minute],$_dt[second],$_dt[month],$_dt[day],$_dt[year]);
	}

# Make unix timestamp from passed date string (mySQL stored yyyy-mm-dd hh:mm:ss):
function dt_make_uts_from_string ( $_dt )
	{
		global $_CCFG;
		$dt[year]	= substr($_dt,0,4);
		$dt[month]	= substr($_dt,5,2);
		$dt[day]	= substr($_dt,8,2);
		$dt[hour]	= substr($_dt,11,2);
		$dt[minute]	= substr($_dt,14,2);
		$dt[second]	= substr($_dt,17,2);
		return  mktime( $dt[hour],$dt[minute],$dt[second],$dt[month],$dt[day],$dt[year]);
	}

# Return current formatted datetime string based on unix timestamp and format passed and setlocale (uses strftime() function):
function dt_display_datetime ( $_uts='', $_format='%Y-%m-%d %H:%M:%S' )
	{
		# Format examples:
		#	long	- $_format='%A- %B %d, %Y @ %H:%M:%S %Z'
		#	short	- $_format='%Y-%m-%d %H:%M:%S'
		#	misc	- $_format='%A- %B %d, %Y'

		global $_CCFG;
		IF ( $_uts == '' || $_uts == 0 ) { $_uts = dt_get_uts(); }
		setlocale (LC_TIME, $_CCFG['_DB_PKG_LOCALE']);
		return strftime ($_format,$_uts);
	}

# Return current formatted datetime string based on unix timestamp and format passed (uses date() function):
function dt_get_datetime ( $_format='Y-m-d H:i:s' )
	{
		# Format examples:
		#	long	- $_format='l- F d, Y @ h:i:s a T'
		#	short	- $_format='Y-m-d H:i:s'

		global $_CCFG;
		$_uts = time()+($_CCFG['_PKG_DATE_SERVER_OFFSET']*3600);
		return date($_format, $_uts);
	}

# Make formatted datetime string based on unix timestamp and format passed (uses date() function):
function dt_make_datetime ( $_uts='', $_format='Y-m-d H:i:s' )
	{
		# Format examples:
		#	long	- $_format='l- F d, Y @ h:i:s a T'
		#	short	- $_format='Y-m-d H:i:s'

		global $_CCFG;
		IF ( $_uts !='' ) 	{ return date($_format, $_uts); }
		ELSE				{ return ''; }
	}

# Make datetime array from passed unix timestamp :
function dt_make_datetime_array ( $_uts )
	{
		global $_CCFG;
		$_dt = dt_make_datetime ( $_uts, 'Y-m-d H:i:s' );
		# $dt[_dt]	= $_dt;
		$dt[year]	= substr($_dt,0,4);
		$dt[month]	= substr($_dt,5,2);
		$dt[day]	= substr($_dt,8,2);
		$dt[hour]	= substr($_dt,11,2);
		$dt[minute]	= substr($_dt,14,2);
		$dt[second]	= substr($_dt,17,2);
		return  $dt;
	}

/**************************************************************
 * Function:	do_load_comp_data($acomp_name, $acomp_oper='')
 * Arguments:	$acomp_name	- Component Name
 * 				$acomp_oper	- Component Operation
 * Returns:		none
 * Description:	Function load component data
 * Notes:
 *	-
**************************************************************/
function do_load_comp_data( $acomp_name, $acomp_oper="" ) {
	# Dim some vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp, $_SYS;
		$query = "";	$result = "";	$numrows = 0;

	# Get security flags
		$_SEC = get_security_flags ( );

	# Initialize return array values to default
		$compdata['comp_ptitle']	= $_CCFG['_PKG_NAME_SHORT'];
		$compdata['comp_col_num']	= 3;
		$compdata['comp_isadmin']	= 0;
		$compdata['comp_isuser']	= 0;
		$compdata['comp_status']	= 1;

	###############
	# Put in load aux record here
	#
	# Table structure for table `phpcoin_components`
	#
	# DROP TABLE IF EXISTS phpcoin_components;
	# CREATE TABLE phpcoin_components (
	#   comp_id mediumint(9) NOT NULL auto_increment,
	#   comp_type varchar(20) NOT NULL default '',
	#   comp_name varchar(20) NOT NULL default '',
	#   comp_mod varchar(20) NOT NULL default '',
	#   comp_desc varchar(50) NOT NULL default '',
	#   comp_ptitle varchar(50) NOT NULL default '',
	#   comp_col_num tinyint(1) NOT NULL default '3',
	#   comp_isadmin tinyint(1) NOT NULL default '0',
	#   comp_isuser tinyint(1) NOT NULL default '0',
	#   comp_status tinyint(1) NOT NULL default '1',
	#   PRIMARY KEY  (comp_id),
	#   KEY comp_id (comp_id)
	# ) TYPE=MyISAM COMMENT='Site Components';

	# Do select of blocks records
		$query  = "SELECT * FROM ".$_DBCFG['components']." WHERE comp_name='".$acomp_name."'";
        IF ( $acomp_oper != "" ) {$query .= " AND comp_mod='".$acomp_oper."'";}
        $query  .= " ORDER BY comp_id ASC";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		IF ( $numrows ) {
			while ($row = db_fetch_array($result)) {

			# Set values for globals
				$compdata['comp_id']		= $row['comp_id'];
				$compdata['comp_type']		= $row['comp_type'];
				$compdata['comp_name']		= $row['comp_name'];
				$compdata['comp_mod']		= $row['comp_mod'];
				$compdata['comp_desc']		= $row['comp_desc'];
				$compdata['comp_ptitle']	= $row['comp_ptitle'];
				$compdata['comp_col_num']	= $row['comp_col_num'];
				$compdata['comp_isadmin']	= $row['comp_isadmin'];
				$compdata['comp_isuser']	= $row['comp_isuser'];
				$compdata['comp_status']	= $row['comp_status'];
			}  # End while block_id loop
		}

	# Check menu items enabled count and reset column data as required.
		IF ( $_TCFG['_DISABLE_MENU_COLS'] == 0 ) {

		# Get menu count items from $_SYS array
			$_do_left = $_SYS['_do_col_left'];
			$_do_right = $_SYS['_do_col_right'];

			IF ( $_do_left == 0 && $_do_right == 0 ) { $compdata['comp_col_num'] = 1; }
			IF ( $_do_left == 1 && $_do_right == 0 ) { $compdata['comp_col_num'] = 2; }
			IF ( $_do_left == 0 && $_do_right == 1 ) { $compdata['comp_col_num'] = 2; }
		# Remaining combination is left and right on- so pass component column_num
		}

	# Check status (on/off) only and redirect accordingly
		IF ( $compdata['comp_status'] != 1 ) {
			html_header_location("error.php?err=05&url=index.php");
			exit;
		}

	# Check is admin only and redirect accordingly
		IF ( $compdata['comp_isadmin'] == 1 && !$_SEC['_sadmin_flg'] ) {
			html_header_location("login.php?w=admin&o=login");
			exit;
		}

	# Check is user only and redirect accordingly
		IF ( $compdata['comp_isuser'] == 1 && (!$_SEC['_suser_flg'] && !$_SEC['_sadmin_flg']) ) {
			html_header_location("login.php?w=user&o=login");
			exit;
		}

		return $compdata;
}


/**************************************************************
 * Function:	do_site_info_display($asi_id, $asi_group, $asi_name, $aret_flag=0)
 * Arguments:	$asi_id		- SI Item ID
 *				$asi_group	- SI Item Group
 *				$asi_name	- SI Item Name
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html for site info content
 * Notes:
 *	-
**************************************************************/
function do_site_info_display( $asi_id, $asi_group, $asi_name, $aret_flag=0, $ass='' )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = "";	$result= "";	$numrows = 0;

		$_SEC = get_security_flags ();
		$_PERMS	= do_decode_perms_admin($_SEC['_sadmin_perms']);

		$_out = '';
		# For ID only call- need to get group and name parms to implement multiple
		#	seq no entries. So- shorter url- but double queries. Code will modify
		#	values for arguments on lookup- then use those returns for group/name.
		#	ID is unique so most of one record returned.
		#
			IF ($asi_id <> 0)
			{
				$query = "SELECT ".$_DBCFG['site_info'].".si_id, ".$_DBCFG['site_info'].".si_group, ".$_DBCFG['site_info'].".si_name";
				$query .= " FROM ".$_DBCFG['site_info'];
				$query .= " WHERE ".$_DBCFG['site_info'].".si_id=".$asi_id;
				$query .= " AND ".$_DBCFG['site_info'].".si_status=1";

				# Do select
					$result		= db_query_execute($query);
					$numrows	= db_query_numrows($result);

				# Process query results
					while(list($si_id, $si_group, $si_name) = db_fetch_row($result))
					{
						# Vars work
							$asi_group	= $si_group;
							$asi_name	= $si_name;
					}
			}

		# Build query string
			# Initial select string
				$query = "SELECT ".$_DBCFG['site_info'].".si_id, ".$_DBCFG['site_info'].".si_group";
				$query .= ", ".$_DBCFG['site_info'].".si_name, ".$_DBCFG['site_info'].".si_seq_no";
				$query .= ", ".$_DBCFG['site_info'].".si_desc, ".$_DBCFG['site_info'].".si_block_it";
				$query .= ", ".$_DBCFG['site_info'].".si_title, ".$_DBCFG['site_info'].".si_code";
				$query .= ", ".$_DBCFG['site_info'].".si_status, ".$_DBCFG['site_info'].".si_footer_menu";
				$query .= " FROM ".$_DBCFG['site_info'];

			# Select via group/name to get multiple seq returns appropriately
				$query .= " WHERE ".$_DBCFG['site_info'].".si_group='".$asi_group."'";
				$query .= " AND ".$_DBCFG['site_info'].".si_name='".$asi_name."'";
				$query .= " AND ".$_DBCFG['site_info'].".si_status=1";

			# Sort order for multi-seq items
				$query .= " ORDER BY si_group asc, si_seq_no asc";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			$si_title = '';
			while(list($si_id, $si_group, $si_name, $si_seq_no, $si_desc, $si_block_it, $si_title, $si_code, $si_status, $si_footer_menu) = db_fetch_row($result))
			{
	            # Add "Edit" button if admin
					IF ($_SEC['_sadmin_flg'] && $_CCFG[ENABLE_QUICK_EDIT] && ($_PERMS[AP16] == 1 || $_PERMS[AP14] == 1)) {
	                    $si_title .= ' <a href="admin.php?cp=siteinfo&op=edit&si_id='.$si_id.'">'.$_TCFG['_S_IMG_EDIT_S'].'</a>';
					}

				# Check for search string to emphasize:
					IF ( $ass != '' )
						{
							$_str_search	= $ass;
							$subject 		= do_highlight_text ( $subject, $_str_search );
							$si_code 		= do_highlight_text ( $si_code, $_str_search );
						}

				# Check for blockit call, or just dump text
					IF ($si_block_it)
					{
						# Vars work
							$string = addslashes($si_code);
							eval("\$string = \"$string\";");
							$string = stripslashes($string);
							# echo $string;

						# Build function argument text
							$menu_flag = $si_footer_menu;
							$menu_text = do_nav_link (getenv("HTTP_REFERER"), $_TCFG['_IMG_RETURN_M'],$_TCFG['_IMG_RETURN_M_MO'],'','');
						#	$menu_text = do_nav_link ($_CCFG['_PKG_URL_BASE'], $_TCFG['_IMG_HOME_M'],$_TCFG['_IMG_HOME_M_MO'],'','');

						# Call block function
							$_out .= do_block_it ($si_title, $string, $menu_flag, $menu_text, '1');
					}
					ELSE
					{
						# Vars work
							$string = addslashes($si_code);
							eval("\$string = \"$string\";");
							$string = stripslashes($string);
							$_out .= $string;
					}
			}

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_menu_blocks($ablock_col='L', $aret_flag=0)
 * Arguments:	$ablock_col	- Column to load (L=left,R=right)
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function load / call all menu blocks
 * Notes:
 *	- Called from various page loads
 *	- Outputs all menu blocks html.
**************************************************************/
function do_menu_blocks ( $ablock_col='L', $aret_flag=0 ) {
	# Get security flags
		$_SEC = get_security_flags ( );
		$_PERMS	= do_decode_perms_admin($_SEC['_sadmin_perms']);

	# Dim some Vars:
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
		$query = "";	$result = "";	$numrows = 0;

	# Validate params and set where strings accordingly

	# Check ablock_col status
		$_ablock_col_upper = strtoupper($ablock_col);
		switch ($_ablock_col_upper) {
			case "A":
				$_where_01 = "";
				break;
			case "L":
				$_where_01 = " AND ".$_DBCFG['menu_blocks'].".block_col = '$_ablock_col_upper'";
				break;
			case "R":
				$_where_01 = " AND ".$_DBCFG['menu_blocks'].".block_col = '$_ablock_col_upper'";
				break;
			default:
				$ablock_col = 'A';
				$_where_01 = "";
				break;
		}

	# Set string for filter blocks on block status "on"
		$_where_02 = " AND ".$_DBCFG['menu_blocks'].".block_status = 1";
		$_where_02 .= " AND ".$_DBCFG['menu_blocks_items'].".item_status = 1";

	# Check admin status- filter blocks requiring admin.
		IF ( $_SEC['_sadmin_flg'] == 1 ) {
			$_where_03 = "";
		} ELSE {
			IF ( $_SEC['_suser_flg'] == 1 ) {
				$_SEC['_sadmin_flg'] = 0;
				$_where_03 = " AND ".$_DBCFG['menu_blocks'].".block_admin = 0";
				$_where_03 .= " AND ".$_DBCFG['menu_blocks_items'].".item_admin = 0";
			} ELSE {
				$_SEC['_sadmin_flg'] = 0;
				$_where_03 = " AND ".$_DBCFG['menu_blocks'].".block_admin = 0";
				$_where_03 .= " AND ".$_DBCFG['menu_blocks'].".block_user = 0";
				$_where_03 .= " AND ".$_DBCFG['menu_blocks_items'].".item_admin = 0";
				$_where_03 .= " AND ".$_DBCFG['menu_blocks_items'].".item_user = 0";
			}
		}

	# Set Query for select.
		$query	= "SELECT ".$_DBCFG['menu_blocks'].".block_id, ".$_DBCFG['menu_blocks'].".block_pos";
		$query	.= ", ".$_DBCFG['menu_blocks'].".block_title, ".$_DBCFG['menu_blocks'].".block_status";
		$query	.= ", ".$_DBCFG['menu_blocks'].".block_admin, ".$_DBCFG['menu_blocks'].".block_user";
		$query	.= ", ".$_DBCFG['menu_blocks_items'].".block_id, ".$_DBCFG['menu_blocks_items'].".item_id";
		$query	.= ", ".$_DBCFG['menu_blocks_items'].".item_text, ".$_DBCFG['menu_blocks_items'].".item_url";
		$query	.= ", ".$_DBCFG['menu_blocks_items'].".item_target, ".$_DBCFG['menu_blocks_items'].".item_type";
		$query	.= ", ".$_DBCFG['menu_blocks_items'].".item_status";
		$query	.= ", ".$_DBCFG['menu_blocks_items'].".item_admin, ".$_DBCFG['menu_blocks_items'].".item_user";

		$query	.= " FROM ".$_DBCFG['menu_blocks'].", ".$_DBCFG['menu_blocks_items'];

		$query	.= " WHERE ".$_DBCFG['menu_blocks'].".block_id = ".$_DBCFG['menu_blocks_items'].".block_id";
		$query	.= $_where_01;
		$query	.= $_where_02;
		$query	.= $_where_03;

		$query	.= " ORDER BY ".$_DBCFG['menu_blocks'].".block_pos ASC, ".$_DBCFG['menu_blocks_items'].".item_id ASC";

	# Do select
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# Process query results
		$_block_id_last 	= "";
		$_block_cnt 		= 0;
		$_block_item_cnt	= 0;

		while(list($block_id, $block_pos, $block_title, $block_status, $block_admin, $block_user, $item_block_id, $item_id, $item_text, $item_url, $item_target, $item_type, $item_status, $item_admin, $item_user) = db_fetch_row($result)) {

		# Check for links to disabled modules
			$SkipIt = 0;
			IF (!$_CCFG['DOMAINS_ENABLE'] && strpos($item_url, strtolower("domain"))) {$SkipIt++;}
			IF (!$_CCFG['ORDERS_ENABLE'] && strpos($item_url, strtolower("order"))) {$SkipIt++;}
			IF (!$_CCFG['INVOICES_ENABLE'] && strpos($item_url, strtolower("invoice"))) {$SkipIt++;}
			IF (!$_CCFG['HELPDESK_ENABLE'] && strpos($item_url, strtolower("helpdesk"))) {$SkipIt++;}
			IF (!$_CCFG['WHOIS_ENABLED'] && strpos($item_url, strtolower("whois"))) {$SkipIt++;}

			IF (!$SkipIt) {

			# Flag first of menu block group and do link- else- just block item links.
				IF ( $_block_id_last != $block_id ) {
					$_block_cnt = $_block_cnt + 1;
					$_block_title[$_block_cnt] = $block_title;
					$_block_content[$_block_cnt] = '';
					$_block_item_cnt = 0;

				# Add "edit parameters" button
					IF ($_CCFG['ENABLE_QUICK_EDIT'] && ($_PERMS['AP16'] == 1 || $_PERMS['AP15'] == 1)) {
						$_block_title[$_block_cnt] .= ' <a href="admin.php?cp=menu&op=edit&&obj=block&block_id='.$block_id.'">'.$_TCFG['_S_IMG_PM_S'].'</a>';
					}
				}

			# Count block items for new line control
				$_block_item_cnt = $_block_item_cnt + 1;
				IF ( $_block_item_cnt > 1 ) { $_block_content[$_block_cnt] .= $_nl; }

			# Check URL Target flag- Build and Echo Link
				$_tflag_jscript = 0;
				IF ( $item_target == 0 || $item_target == 1 || $item_target == 2 ) {
					$_target_str = ' target="'.$_CCFG['MBI_LINK_TARGET'][$item_target].'"';
				} ELSE {
					$_target_str = '';
				}

				IF ( $item_text{0} == '$' && $item_type == 1 ) {
					eval("\$item_text = \"$item_text\";");
					IF ( $_tflag_jscript == 1 ) {
						$_block_content[$_block_cnt] .= '<tr><td width="100%" valign="top">'.$_nl;
						$_block_content[$_block_cnt] .= '<span align="center"><a href="'.$item_url.'"'.$_target_str.'>'.$item_text.'</a></span>';
						$_block_content[$_block_cnt] .= '</td></tr>';
					} ELSE {
						$_block_content[$_block_cnt] .= '<span align="center"><a href="'.$item_url.'"'.$_target_str.'>'.$item_text.'</a></span>';
					}
				} ELSEIF ( $item_text{0} == '_'  && $item_type == 2 ) {
					$_isfunc = 'do'.$item_text;
					IF (function_exists($_isfunc)) { $_fout = $_isfunc(); } ELSE { $_fout = 'Error- no function'; }
					IF ( $_tflag_jscript == 1 ) {
						$_block_content[$_block_cnt] .= '<tr><td width="100%" valign="top">'.$_nl;
						$_block_content[$_block_cnt] .= '<span align="left">'.$_fout.'</span>';
						$_block_content[$_block_cnt] .= '</td></tr>';
					} ELSE {
						$_isfunct = 'do'.$item_text;
						$_block_content[$_block_cnt] .= '<span align="left">'.$_fout.'</span>';
					}
				} ELSE {
					IF ( $_tflag_jscript == 1 ) {
						$_id	= $block_id.$item_id;
						$_mover	= ' onMouseOver="setClassName(\''.$_id.'\',\'button_mblock_h\');"';
						$_mout	= ' onMouseOut="setClassName(\''.$_id.'\',\'button_mblock\');"';
						$_block_content[$_block_cnt] .= '<tr><td id="'.$_id.'" class="button_mblock" width="100%" valign="top"'.$_mover.$_mout.'>'.$_nl;
						$_block_content[$_block_cnt] .= '<strong>&#183;&nbsp;</strong><a href="'.$item_url.'"'.$_target_str.'>'.$item_text.'</a>';
						$_block_content[$_block_cnt] .= '</td></tr>';
					} ELSE {
						$_block_content[$_block_cnt] .= '<strong>&#183;&nbsp;</strong><a href="'.$item_url.'"'.$_target_str.'>'.$item_text.'</a><br>';
					}
				}

			# Set last to current
				$_block_id_last = $block_id;
			}
		}   # End of "Not SkipIt"

	# Create a "login" box IF enabled and IF columns are enabled
		$ablock_col = strtoupper($ablock_col);
		IF ($_CCFG['USE_LOGIN_MENUBOX'] == "1") {
			$desired = "L";
		} ELSEIF ($_CCFG['USE_LOGIN_MENUBOX'] == "2") {
			$desired = "R";
		} ELSE {
			$desired = "";
		}
		IF ($_CCFG['USE_LOGIN_MENUBOX'] && !$_TCFG['_DISABLE_MENU_COLS'] && $ablock_col==$desired) {
			$_out = do_menu_block($_LANG['_BASE']['Login_Form'],do_Display_Login_Menu_Form(),'1').'<br>';
		}

	# Loop Array and Print Out Row HTML (based on 4-cols)
		for ($i = 1; $i <= $_block_cnt; $i++) {

		# Check for line break after first block
			IF ($i > 1) { $_out .= '<br>'.$_nl; }

		# Check jscript enabled for enclosed in table
			IF ( $_tflag_jscript == 1 ) {
				$_block_content[$i] = '<table width="100%" cellpadding="0" cellspacing="0">'.$_block_content[$i].'</table>';
			}

		# Call core do_block function
			$_out .= do_menu_block($_block_title[$i], $_block_content[$i], '1');
		}

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_menu_items_count( )
 * Arguments:	none
 * Returns:		$array[count_left]	- left column count
 * 				$array[count_right]	- right column count
 * Description:	Return menu items that will load.
 * Notes:
 *	- Called by do_left_col and do_right_col
**************************************************************/
function do_menu_items_count ( )
	{
		# Get security flags
			$_SEC = get_security_flags ( );

		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query = "";	$result = "";	$numrows = 0;

		# Check admin status- filter blocks requiring admin.
			IF ( $_SEC['_sadmin_flg'] == 1 )
				{
					$_where_01 = "";
				}
			ELSE
				{
					$_SEC['_sadmin_flg'] = 0;
					$_where_01 = " AND ".$_DBCFG['menu_blocks'].".block_admin = 0";
				}

		# Check user status- filter blocks requiring user.
			IF ( $_SEC['_suser_flg'] == 1 )
				{
					$_where_02 = "";
				}
			ELSE
				{
					$_SEC['_suser_flg'] = 0;
					IF ( $_SEC['_sadmin_flg'] != 1 )
						{ $_where_02 = " AND ".$_DBCFG['menu_blocks'].".block_user = 0"; }
				}

		# Set string for filter blocks on block status "on"
			$_where_03 = " AND ".$_DBCFG['menu_blocks'].".block_status = 1";

		# Set Query for select.
			$query	= "SELECT ".$_DBCFG['menu_blocks'].".block_col, count(".$_DBCFG['menu_blocks'].".block_id) as mbi_count";
			$query	.= " FROM ".$_DBCFG['menu_blocks'].", ".$_DBCFG['menu_blocks_items'];

			$query	.= " WHERE ".$_DBCFG['menu_blocks'].".block_id = ".$_DBCFG['menu_blocks_items'].".block_id";
			$query	.= " AND (".$_DBCFG['menu_blocks'].".block_col = 'L' OR ".$_DBCFG['menu_blocks'].".block_col = 'R')";
			$query	.= $_where_01;
			$query	.= $_where_02;
			$query	.= $_where_03;

			$query .= " GROUP BY ".$_DBCFG['menu_blocks'].".block_col";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

			# Process query results
				$tt_count_ttl_o	= 0;
				while(list($block_col, $mbi_count) = db_fetch_row($result))
				{
					IF ($block_col == 'L' )	{ $_ret['count_left'] = $mbi_count; }
					IF ($block_col == 'R' )	{ $_ret['count_right'] = $mbi_count; }
				}

		# Check for empty and set to zero
			IF ($_ret['count_left'] == '' )		{ $_ret['count_left'] = 0; }
			IF ($_ret['count_right'] == '' )	{ $_ret['count_right'] = 0; }

		# Set return
			return $_ret;
	}


/**************************************************************
 * Function:	do_select_list_topic($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html topics select list
 * Notes:
 *	-
**************************************************************/
function do_select_list_topic( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT topic_id, topic_pos, topic_name, topic_desc, topic_icon FROM ".$_DBCFG['topics']." ORDER BY topic_name ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_BASE']['Select_Topic'].'</option>'.$_nl;

		# Loop return and load list
			while(list($topic_id, $topic_pos, $topic_name, $topic_desc, $topic_icon) = db_fetch_row($result))
			{
				$_out .= '<option value="'.$topic_id.'"';
				IF ( $topic_id == $avalue ) { $_out .= ' selected'; }
				$_out .= '> - '.$topic_name.'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	get_name_topic($atopic_id, $aret_flag=0)
 * Arguments:	$atopic_id	- Topic ID to return name for
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to get topic name from passed topic_id
 * Notes:
 *	-
**************************************************************/
function get_name_topic( $atopic_id, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT topic_name FROM ".$_DBCFG['topics']." WHERE topic_id = $atopic_id";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			while(list($topic_name) = db_fetch_row($result))
			{
				$_out = $topic_name;
			}

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_select_list_cat($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html categories select list
 * Notes:
 *	-
**************************************************************/
function do_select_list_cat( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT cat_id, cat_pos, cat_name, cat_desc, cat_icon FROM ".$_DBCFG['categories']." ORDER BY cat_name ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_BASE']['Select_Category'].'</option>'.$_nl;

		# Loop return and load list
			while(list($cat_id, $cat_pos, $cat_name, $cat_desc, $cat_icon) = db_fetch_row($result))
			{
				$_out .= '<option value="'.$cat_id.'"';
				IF ( $cat_id == $avalue ) { $_out .= ' selected'; }
				$_out .= '> - '.$cat_name.'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	get_name_cat($acat_id, $aret_flag=0)
 * Arguments:	$acat_id	- Category ID to return name for
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to get topic name from passed cat_id
 * Notes:
 *	-
**************************************************************/
function get_name_cat( $acat_id, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT cat_name FROM ".$_DBCFG['categories']." WHERE cat_id = $acat_id";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Process query results
			while(list($cat_name) = db_fetch_row($result))
			{
					$_out = $cat_name;
			}

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}

/**************************************************************
 * Function:	do_select_list_parm_group($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html parm group select list
 * Notes:
 *	-
**************************************************************/
function do_select_list_parm_group( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_BASE']['Select_Group'].'</option>'.$_nl;

		# Load config array and sort
			$_tmp_array = $_CCFG[_PARM_GROUP];
			sort($_tmp_array);

		# Loop array and load list
			FOR ($i = 0; $i < count($_tmp_array); $i++)
			{
				$_out .= '<option value="'.$_tmp_array[$i].'"';
				IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_select_exist_parm_group($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html parm group existing
 *				select list
 * Notes:
 *	-
**************************************************************/
function do_select_exist_parm_group( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT DISTINCT parm_group FROM ".$_DBCFG['parameters']." ORDER BY parm_group ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="">'.$_LANG['_BASE']['All'].'</option>'.$_nl;

		# Loop return and load list
			while(list($parm_group) = db_fetch_row($result))
			{
				$_out .= '<option value="'.$parm_group.'"';
				IF ( $parm_group == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$parm_group.'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_select_list_parm_group_sub($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html parm group select list
 * Notes:
 *	-
**************************************************************/
function do_select_list_parm_group_sub( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="0">'.$_LANG['_BASE']['Select_SubGroup'].'</option>'.$_nl;

		# Load config array and sort
			$_tmp_array = $_CCFG['_PARM_GROUP_SUB'];
			sort($_tmp_array);
			$todo = count($_CCFG['_PARM_GROUP_SUB']);

		# Loop array and load list
			FOR ($i=0; $i < $todo; $i++) {
				$_out .= '<option value="'.$_tmp_array[$i].'"';
				IF ( $_tmp_array[$i] == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$_tmp_array[$i].'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}

/**************************************************************
 * Function:	do_select_exist_parm_group_sub($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html parm subgroup existing
 *				select list
 * Notes:
 *	-
**************************************************************/
function do_select_exist_parm_group_sub( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Set Query for select.
			$query		= "SELECT DISTINCT parm_group_sub FROM ".$_DBCFG['parameters']." ORDER BY parm_group_sub ASC";

		# Do select
			$result		= db_query_execute($query);
			$numrows	= db_query_numrows($result);

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="">'.$_LANG['_BASE']['All'].'</option>'.$_nl;

		# Loop return and load list
			while(list($parm_group_sub) = db_fetch_row($result))
			{
				$_out .= '<option value="'.$parm_group_sub.'"';
				IF ( $parm_group_sub == $avalue ) { $_out .= ' selected'; }
				$_out .= '>'.$parm_group_sub.'</option>'.$_nl;
			}

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_select_list_parm_type($aname, $avalue, $aret_flag=0)
 * Arguments:	$aname		- select field name
 * 				$avalue		- select field value
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to build html parm group select list
 * Notes:
 *	-
**************************************************************/
function do_select_list_parm_type( $aname, $avalue, $aret_flag=0 )
	{
		# Dim some Vars:
			global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;
			$query	= "";	$result	= "";	$numrows = 0;

		# Build Form row
			$_out = '<select class="select_form" name="'.$aname.'" size="1" value="'.$avalue.'">'.$_nl;
			$_out .= '<option value="B"'.$_nl;
				IF ( 'B' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_Boolean'].'</option>'.$_nl;

			$_out .= '<option value="D"'.$_nl;
				IF ( 'D' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_Date'].'</option>'.$_nl;

			$_out .= '<option value="I"'.$_nl;
				IF ( 'I' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_Integer'].'</option>'.$_nl;

			$_out .= '<option value="R"'.$_nl;
				IF ( 'R' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_Real'].'</option>'.$_nl;

			$_out .= '<option value="S"'.$_nl;
				IF ( 'S' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_String'].'</option>'.$_nl;

			$_out .= '<option value="T"'.$_nl;
				IF ( 'T' == $avalue ) { $_out .= ' selected'; }
			$_out .= '>'.$_LANG['_BASE']['PTL_Timestamp'].'</option>'.$_nl;

			$_out .= '</select>'.$_nl;

		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
	}


/**************************************************************
 * Function:	do_convert_currency($avalue, $aret_flag=0)
 * Arguments:	$avalue		- currency amount to be converted
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to convert from one currency to another
 * Notes:
 *	-
**************************************************************/
function do_convert_currency( $avalue, $aret_flag=0 ) {
	global $_CCFG;
	IF ($_CCFG[PAYLINK_EXCHANGE_RATE]) {
		$_out = $avalue * $_CCFG[PAYLINK_EXCHANGE_RATE];
	} ELSE {
		$_out = $avalue;
	}
	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_parse_paylink($adata, $_mod_paylink, $insert_ord_id, $insert_invc_id, $aret_flag=0)
 * Arguments:	$adata			- order/invoice information array
 *				$_mod_paylink	- existing paylink
 *				$insert_ord_id	- order_id
 *				$insert_id_invc	- invoice_id
 *				$aret_flag	- Output Return (1) or echo (0)
 * Returns:		output via return flag.
 * Description:	Function to parse a paylink and replace parameters
 * Notes:
 *	-
**************************************************************/
function do_parse_paylink( $adata, $_mod_paylink, $ord_id, $invc_id, $aret_flag=0) {
	global $_CCFG, $_ACFG;
		IF ($_CCFG[PAYLINK_EXCHANGE_RATE]) {
			$adata[ord_unit_cost] = do_convert_currency($adata[ord_unit_cost], 1);
			$adata[prod_unit_cost] = do_convert_currency($adata[prod_unit_cost], 1);
			$adata[tax1_amt] = do_convert_currency($adata[tax1_amt], 1);
			$adata[tax2_amt] = do_convert_currency($adata[tax2_amt], 1);
			$adata[total_amt] = do_convert_currency($adata[total_amt], 1);
		}

	# Parse and replace
		$_start_date = date("Ymd");
		$_mod_paylink = str_replace( "<ord_start_date>", $_start_date, $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_domain>", $adata[ord_domain], $_mod_paylink );
		$_mod_paylink = str_replace( "<prod_id>", $adata[prod_id], $_mod_paylink );
		$_mod_paylink = str_replace( "<prod_name>", $adata[prod_name], $_mod_paylink );
		$_mod_paylink = str_replace( "<prod_desc>", $adata[prod_desc], $_mod_paylink );
		$_mod_paylink = str_replace( "<prod_unit_cost>", $adata[prod_unit_cost], $_mod_paylink );
		$_mod_paylink = str_replace( "<order_id>", $ord_id, $_mod_paylink );
//		$_mod_paylink = str_replace( "<invoice_id>", $invc_id, $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_unit_cost>", $adata[ord_unit_cost], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_company>", $adata[ord_company], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_name_first>", $adata[ord_name_first], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_name_last>", $adata[ord_name_last], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_name_full>", $adata[ord_name_first].' '.$adata[ord_name_last], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_addr_01>", $adata[ord_addr_01], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_addr_02>", $adata[ord_addr_02], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_city>", $adata[ord_city], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_state_prov>", $adata[ord_state_prov], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_country>", $adata[ord_country], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_zip_code>", $adata[ord_zip_code], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_phone>", $adata[ord_phone], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_email>", $adata[ord_email], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_optfld_01>", $adata[ord_optfld_01], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_optfld_02>", $adata[ord_optfld_02], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_optfld_03>", $adata[ord_optfld_03], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_optfld_04>", $adata[ord_optfld_04], $_mod_paylink );
		$_mod_paylink = str_replace( "<ord_optfld_05>", $adata[ord_optfld_05], $_mod_paylink );
		$_mod_paylink = str_replace( "<tax1_amt>", $adata[tax1_amt], $_mod_paylink );
		$_mod_paylink = str_replace( "<tax2_amt>", $adata[tax2_amt], $_mod_paylink );
		$_mod_paylink = str_replace( "<total_amt>", $adata[total_amt], $_mod_paylink );

	# Return results
	IF ( $aret_flag ) { return $_mod_paylink; } ELSE { echo $_mod_paylink; }
}


// Get The Server Load
function get_ServerLoad() {
	IF (PHP_OS != 'WINNT' && PHP_OS != 'WIN32') {
		IF (file_exists('/proc/loadavg') ) {
			IF ($fh = @fopen( '/proc/loadavg', 'r' )) {
				$data = @fread( $fh, 6 );
				@fclose( $fh );
				$load_avg = explode( " ", $data );
				$server_load = trim($load_avg[0]);
			}
		} ELSE {
			$data = @exec('uptime');
			preg_match('/(.*):{1}(.*)/', $data, $matches);
			$load_arr = explode(',', $matches[2]);
			$server_load = trim($load_arr[0]);
		}
	}
	IF (!$server_load) { $server_load = 'N/A'; }
	return $server_load;
}


/**************************************************************
 * Function:	do_page_debug_info($acomp_col_num=2, $aret_flag=0)
 * Arguments:	$acomp_col_num	- Current number of columns
 * 		$aret_flag	- How To Handle Output- 1=return, 0=echo
 * Returns:	output return switchable
 * Description:	Function to build html for debug info data row
 * Notes:
 *	-
**************************************************************/
function do_page_debug_info($acomp_col_num=2, $aret_flag=0) {
	# Dim some vars
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_nl, $_sp;

	# Get security vars
		$_SEC = get_security_flags ();

	# Calc Elapsed Time
		global $_OTS;
		$_CTS = explode(" ",microtime());
		$_CTS = number_format( ($_CTS[1] + $_CTS[0]), 4, '.', '');
		$_ETS = number_format( ($_CTS-$_OTS), 4, '.', '');

	# Output Debug Row
		IF ( $_CCFG['_IS_PRINT'] != 1 ) {
			$_out .= '<!-- Start Debug Row -->'.$_nl;
			$_out .= '<tr height="12px"><td class="TP0SML_NC" colspan="'.$acomp_col_num.'">'.$_nl;
			$_out .= '<br><br>DEBUG INFORMATION:<BR><BR>';
			$_out .= '[ Queries:'.$_sp.db_query_count().' ]';
			$_out .= $_sp.$_sp;
			$_out .= '[ Execution Time:'.$_sp.$_ETS.' ]'.$_nl;
			$_out .= $_sp.$_sp;
			$_out .= '[ Server Load:'.$_sp.get_ServerLoad().' ]'.$_nl;
			$_out .= '</td></tr>'.$_nl;
			IF ( $_CCFG['_debug_queries'] == 1 && $_SEC['_sadmin_flg'] == 1) {
				$_out .= '<tr><td class="TP0SML_NL" colspan="'.$acomp_col_num.'">'.$_nl;
				$_out .= '<br>'.db_query_strings().$_nl;
				$_out .= '</td></tr>'.$_nl;
			}
			$_out .= '<!-- End Debug Row -->'.$_nl;
		}
	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


function Replace_CRs($item) {
	$display = eregi_replace("\r\n", "<br>", $item);
	$display = eregi_replace("\n\r", "<br>", $display);
	$display = eregi_replace("\r", "<br>", $display);
	$display = eregi_replace("\n", "<br>", $display);
	return $display;
}

function Insert_CRs($item) {
	$display = eregi_replace("<br>","\n",$item);
	$display = eregi_replace("<br />","\n",$item);
	return $display;
}


// Pass in an email address and the "check valid domain / do not check" flag.
// Routine returns 1 if invalid format, 2 if unreachable domain, 0 otherwise
function do_validate_email($email,$CheckDomain) {
	$bademail = 0;
	IF (!eregi("^" . "[[:alnum:]]+([_\\.-][[:alnum:]]+)*" . "@" . "([[:alnum:]]+([\.-][[:alnum:]]+)*)+" . "\\.[[:alpha:]]{2,}" . "$", $email, $regs)) {
	$bademail = 1;
	} ELSE {
		list($User,$Host) = split("@",$email);
		IF ($CheckDomain) {
			IF (($Host) && (gethostbyname($Host) == $Host)) {$bademail = 2;}
		}
	}
	return $bademail;
}
?>
