<?php
/**************************************************************
 * File: 		Setup File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-09 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:	- create or upgrade database tables
 *		- populate tables
 *		- This file has undergone a relatively large rewrite
 *		  so that in the futurer, we do NOT need to touch this
 *		  file as new versions of phpCOIN are released.
 *		  See "setup_config.php" for more details.
**************************************************************/

# Turn off pointless "warning" messages
	ini_set('error_reporting','E_ALL');


/**************************************************************
 * Process "global" variables
**************************************************************/
	# Following should provide php4.06 and register_globals off functionality
	# Process Variables
		IF ( !isset($_SERVER)	)	{ $_SERVER	= $HTTP_SERVER_VARS;	}
		IF ( !isset($_POST)		)	{ $_POST		= $HTTP_POST_VARS;		}
		IF ( !isset($_GET)		)	{ $_GET		= $HTTP_GET_VARS;		}
		IF ( !isset($_COOKIE)	)	{ $_COOKIE	= $HTTP_COOKIE_VARS;	}
		IF ( !isset($_FILES)	)	{ $_FILES		= $HTTP_POST_FILES;		}
		IF ( !isset($_ENV)		)	{ $_ENV		= $HTTP_ENV_VARS;		}
		IF ( !isset($_SESSION)	)	{ $_SESSION	= $HTTP_SESSION_VARS;	}

	# Load GET/POST in global array (POST overwrites GET)
		global $_GPV;

		while(list($key, $var) = each($_GET)) {
			while($var != utf8_decode($var))	{$var = utf8_decode($var);}
			while($var != urldecode($var))	{$var = urldecode($var);}
			$var = htmlentities($var);
			IF (function_exists('html_entity_decode')) {
				$var = html_entity_decode($var);
			} ELSE {
				$var = unhtmlentities($var);
			}
			while($var != strip_tags($var))	{$var = strip_tags($var);}
			$pieces = explode("\"",$var);		$var = $pieces[0];
			$pieces = explode("'",$var);		$var = $pieces[0];
			$pieces = explode(" ",$var);		$var = $pieces[0];
			IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
			$_GPV[$key] = $var;
		}

		while(list($key, $var) = each($_POST))	{
			while($var != utf8_decode($var))	{$var = utf8_decode($var);}
			while($var != urldecode($var))	{$var = urldecode($var);}
			$var = htmlentities($var);
			IF (function_exists('html_entity_decode')) {
				$var = html_entity_decode($var);
			} ELSE {
				$var = unhtmlentities($var);
			}
			while($var != strip_tags($var))	{$var = strip_tags($var);}
			IF (eregi('_id', $key) || $key == 'id') {IF (!is_numeric($var)) {$var=0;}}
			$_GPV[$key] = $var;
		}


# Figure out our location
	$separat = "/coin_";

# Build the web path
	$Location = $_SERVER['PHP_SELF'];
	$Location = str_replace("\\","/",$Location);
	$PathWeb = explode("/", $Location);
	$FileName = array_pop($PathWeb);
	$_PACKAGE['PATH'] = implode("/", $PathWeb);
	$data_array = explode("$separat",$_PACKAGE['PATH']);
	$_PACKAGE['PATH'] = $data_array[0];
	$_PACKAGE['PATH'] .= '/';

# Build the URL
	$_PACKAGE['URL'] = (($_SERVER["HTTPS"]=="on")?"https":"http").'://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$_PACKAGE['PATH'];

# build the file path
	$tempdocroot = (substr(PHP_OS, 0, 3) == 'WIN') ? strtolower(getcwd()) : getcwd();
	$_PACKAGE['DIR'] = str_replace("\\","/",$tempdocroot);
	$data_array = explode("$separat",$_PACKAGE['DIR']);
	$_PACKAGE['DIR'] = $data_array[0];
	$_PACKAGE['DIR'] .= '/';

# Include config file
	require_once($_PACKAGE['DIR'].'config.php');

	# Check for <phpcoindir><lang>lang_theme.php file
		$_fr_lang 	= is_readable( $_CCFG['_PKG_PATH_LANG'].'lang_theme.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_lang/lang_english/lang_theme.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_LANG'].'lang_theme.php</b>';
		IF (!$_fr_lang) {$_output .= error_block($block_title, $block_content); exit;}
	# Include lang_config file
		require_once($_CCFG['_PKG_PATH_LANG']."lang_theme.php");


	# Check for <phpcoindir><theme>config.php file
		$_fr_tconfig 	= is_readable( $_CCFG['_PKG_PATH_THEME'].'config.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_themes/cantex/config.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_THEME'].'config.php</b>';
		$block_content	.= '<br><br>'.'Please double check to make sure the theme configured in <phpcoindir>config.php exists';
		IF (!$_fr_tconfig) {error_block($block_title, $block_content); exit;}

	# Include theme config file
		require_once ($_CCFG['_PKG_PATH_THEME'].'config.php');


	# Check for <phpcoindir><includes>common.php file
		$_fr_common 	= is_readable( $_CCFG['_PKG_PATH_INCL'].'common.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_includes/common.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_INCL'].'common.php</b>';
		IF (!$_fr_common) {error_block($block_title, $block_content); exit;}

	# Include constants file
		require_once ($_CCFG['_PKG_PATH_INCL'].'common.php');


	# Check for <phpcoindir><includes>redirect.php file
		$_fr_redirect 	= is_readable( $_CCFG['_PKG_PATH_INCL'].'redirect.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_includes/redirect.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_INCL'].'redirect.php</b>';
		IF (!$_fr_common) {error_block($block_title, $block_content); exit;}

	# Include redirect file
		require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');


	# Check for <phpcoindir><lang>lang_config.php file
		$_fr_lang 	= is_readable( $_CCFG['_PKG_PATH_LANG'].'lang_config.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_lang/lang_english/lang_config.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_LANG'].'lang_config.php</b>';
		IF (!$_fr_lang) {error_block($block_title, $block_content); exit;}

	# Include lang_config file
		require_once ($_CCFG['_PKG_PATH_LANG']."lang_config.php");


	# Check for <phpcoindir><includes>db.php file
		$_fr_db 	= is_readable( $_CCFG['_PKG_PATH_INCL'].'db.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_includes/db.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_INCL'].'db.php</b>';
		IF (!$_fr_db) {error_block($block_title, $block_content); exit;}

	# Include db.php file and connect
		require_once ($_CCFG['_PKG_PATH_INCL'].'db.php');


	# Check for <phpcoindir><includes>constants.php file
		$_fr_constants 	= is_readable( $_CCFG['_PKG_PATH_INCL'].'constants.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_includes/constants.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_CCFG['_PKG_PATH_INCL'].'constants.php</b>';
		IF (!$_fr_constants) {error_block($block_title, $block_content); exit;}

	# Include constants file (requires config for prefix)
		require_once ($_CCFG['_PKG_PATH_INCL'].'constants.php');


	# Check for <phpcoindir><setup>setup_config.php file
		$_fr_setup 	= is_readable( $_PACKAGE['DIR'].'coin_setup/setup_config.php' );
		$block_title	= 'Error: Required File Not Found';
		$block_content	= 'The required <b>phpcoindir/coin_setup/setup_config.php</b> file could not be located at:';
		$block_content	.= '<br><br><b>'.$_PACKAGE['DIR'].'coin_setup/setup_config.php</b>';
		IF (!$_fr_setup) {error_block($block_title, $block_content); exit;}

	# Include our setup config vars
		require_once ($_PACKAGE['DIR'].'coin_setup/setup_config.php');
		$ThisVersionIs = str_replace("b","",$ThisVersion);

	# Create db Instance
		$db_coin = new db_funcs();


/**************************************************************
 * Open Page
**************************************************************/

	$_out = do_install_page_open('1');
	echo $_out;


/**************************************************************
 * Database Call
**************************************************************/
	# Attempt database connect:
		$db_coin->db_set_suppress_errors(0);
		$db_coin->db_connect();

		IF ( $db_coin->connection ) {
			$_db_check = 1;
			$_cstr  = '<b>Database Connection completed:</b><br>'.$_nl;
			$_cstr .= '&nbsp;&nbsp;- Hostname, Username, and Password are OK.<br>'.$_nl;
		} ELSE {
			$_db_check = 0;
			$_cstr  = '<b>Database Connection failed:</b><br>'.$_nl;
			$_cstr .= '&nbsp;&nbsp;- Check Hostname, Username, and Password and try again. Items located in config.php file.<br>'.$_nl;
		}

	# Attempt database select:
		$db_coin->db_select_db();

		IF ( $db_coin->connection )  {
			$_db_check = 1;
			$_cstr .= '<br>'.$_nl;
			$_cstr .= '<b>Database Selection Completed:</b><br>'.$_nl;
			$_cstr .= '&nbsp;&nbsp;- Database Name is OK.<br>'.$_nl;
		} ELSE {
			$_db_check = 0;
			$_cstr .= '<br>'.$_nl;
			$_cstr .= '<b>Database Selection failed:</b><br>'.$_nl;
			$_cstr .= '&nbsp;&nbsp;- Check Database Name and try again. Items located in config.php file.<br>'.$_nl;
		}

	# Check to see if already installed, and if so, what version
		IF ( $db_coin->connection ) {

		# What tables do we have installed? This will help determine our upgrade/install path.
			$Table_Installed['clients'] = 0;
			$Table_Installed['versions'] = 0;
			$Table_Installed['clients_status'] = 0;
			$Table_Installed['server_accounts'] = 0;
			$Table_Installed['install_status'] = 0;
			$_DBCFG['clients_status'] = $_DBCFG['table_prefix'].'clients_status';
			$_DBCFG['server_accounts'] = $_DBCFG['table_prefix'].'server_acounts';

			$result = mysql_query("SHOW TABLES");
			$num_results = mysql_num_rows($result);
			for ($i = 0; $i < $num_results; $i++) {
				$row = mysql_fetch_array($result);
  				IF ($row[0] == $_DBCFG['clients']) {$Table_Installed['clients']++;}
				IF ($row[0] == $_DBCFG['versions']) {$Table_Installed['versions']++;}
				IF ($row[0] == $_DBCFG['clients_status']) {$Table_Installed['clients_status']++;}
				IF ($row[0] == $_DBCFG['server_accounts']) {$Table_Installed['server_acounts']++;}
				IF ($row[0] == "install_status") {$Table_Installed['install_status']++;}
			}
		# Are we resuming an aborted install?
			IF ($Table_Installed['install_status']) {$Resuming=1;} ELSE {$Resuming=0;}

		# OK, so what version is installed?
			IF (!$Table_Installed['clients']) {
				$InstalledVersion = "";
			} ELSEIF ($Table_Installed['clients_status']) {
				$InstalledVersion = "1.1.0";
			} ELSEIF ($Table_Installed['server_accounts']) {
				$InstalledVersion = "1.1.1";
			} ELSEIF ($Table_Installed['versions']) {
				$query = "SELECT v_ver FROM ".$_DBCFG['versions'];
				$result	= db_query_execute($query);
				IF ($result) {
					$numrows	= db_query_numrows($result);
					IF ($numrows) {
						while ($row = db_fetch_array($result)) {
							$row['v_ver'] = strtolower($row['v_ver']);
							$InstalledVersion = str_replace("v","",$row['v_ver']);
						}
					}
				}
			} ELSE {
				$InstalledVersion = "";
			}

		# Build installation action to display
			$_cstr .= '<br><b>Installation Action:</b><br>&nbsp;&nbsp;- '.$_nl;
			IF ($Resuming) {$_cstr .= 'Resuming ';}

			IF ($InstalledVersion == $ThisVersionIs) {
				$FullInstall++;
				$Warn=1;
				$_cstr .= 'This version of phpCOIN is allready installed.<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>ALL EXISTING DATA WILL BE ERASED AND FRESH DATA INSTALLED IF YOU PROCEED</b>'.$_nl;
			} ELSEIF ($Table_Installed['clients_status']) {
				$_cstr .= 'Upgrade v'.$InstalledVersion.' To v'.$ThisVersion.'<br>'.$_nl;
				$_cstr .= '<br>Sorry, but v1.1.0 or lower cannot be <i>directly</i> upgraded to v'.$ThisVersion.$_nl;
				$_cstr .= '<br>To complete the upgrade process, please exit this installation program, then:<ol>'.$_nl;
				$_cstr .= '<li>Run upgrade_to_v11x.php then</li>'.$_nl;
				$_cstr .= '<li>Run upgrade_to_v120.php then</li>'.$_nl;
				$_cstr .= '<li>Rerun setup.php</li></ol><br>'.$_nl;
				$FatalError++;
			} ELSEIF ($Table_Installed['server_accounts']) {
				$_cstr .= 'Upgrade v'.$InstalledVersion.' To v'.$ThisVersion.'<br>'.$_nl;
				$_cstr .= '<br>Sorry, but v1.1.1 cannot be <i>directly</i> upgraded to v'.$ThisVersion.$_nl;
				$_cstr .= '<br>To complete the upgrade process, please exit this installation program, then:<ol>'.$_nl;
				$_cstr .= '<li>run upgrade_to_v120.php then</li>'.$_nl;
				$_cstr .= '<li> rerun setup.php</li></ol><br>'.$_nl;
				$FatalError++;
			} ELSEIF ($InstalledVersion) {
				$_cstr .= 'Upgrade v'.$InstalledVersion.' To v'.$ThisVersion.'<br>'.$_nl;
			} ELSE {
				$_cstr .= 'New Installation of v'.$ThisVersion.'<br>'.$_nl;
			}
			$InstalledVersion = str_replace(".","",$InstalledVersion);
		}

	# Output  data
		$_tstr	= 'Initial Database Check';
		$_out	= '<br>'.$_nl;
		$_out	.= do_install_block_it ($_tstr, $_cstr, 0, '', '1');
		$_tstr	= ''; $_cstr	= ''; $_mstr	= '';
		echo $_out;


	# Do cheesy login to check against db password (non-encrypt)
	# unless your password is easy to guess, this SHOULD prevent people from
	# re-installing the database and wiping out your data IF you do NOT
	# delete the setup directory and contents
		IF ( !$FatalError && !$_GPV['stage'] && $_db_check==1 ) {

		# Create Login Form
			$_cstr .= '<table width="100%"><tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<form action="'.$_SERVER["PHP_SELF"].'" method="post" name="login">'.$_nl;

		# Show db backup options if NOT upgrading
			IF ($FullInstall && !$Resuming && !$InstalledVersion) {
				IF ( $_GPV['tbl_bak']==1 ) { $_set .= ' CHECKED'; }
				$_cstr .= '<INPUT TYPE=CHECKBOX NAME="tbl_bak" value="1"'.$_set.' border="0">'.$_nl;
				$_cstr .= '&nbsp;Check to rename existing tables with _bak.'.$_nl;
				$_cstr .= '<br>(existing will be dropped if unchecked)'.$_nl;
				$_cstr .= '<br><br>'.$_nl;
			} ELSE {
				$_cstr .= '<INPUT TYPE=hidden NAME="tbl_bak" value="0">'.$_nl;
			}

			$_cstr .= '<b>Enter Your Database Password to ';
			IF ($Resuming) {$_cstr .= 'Resume';} ELSE {$_cstr .= 'Complete';}
			$_cstr .= ' Installation:</b><br>'.$_nl;
			$_cstr .= '&nbsp;&nbsp;(installation will begin on clicking install- no additional prompts)<br>'.$_nl;
			IF ($Warn==1) {
				$_cstr .= '<br><b>This version of phpCOIN is allready installed.<br>&nbsp;&nbsp;&nbsp;&nbsp;ALL EXISTING DATA WILL BE ERASED AND FRESH DATA INSTALLED IF YOU PROCEED</b>'.$_nl;
			}
			$_cstr .= '<b>Password:&nbsp;&nbsp;<INPUT class="PMED_NL" type="password" name="password" size="20" maxlength="20">'.$_nl;
			$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
			$_cstr .= '<INPUT class="PMED_NC" TYPE=SUBMIT value="Install">'.$_nl;
			$_cstr .= '</form>'.$_nl;
			$_cstr .= '</td></tr>'.$_nl;

			$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
			$_cstr .= '<b>Installing this package indicates acceptance of accompanying '.$_nl;
			$_cstr .= '<a href="'.$_CCFG['_PKG_URL_BASE'].'coin_docs/license.txt" target="_blank">license</a></b>'.$_nl;
			$_cstr .= '</td></tr></table>'.$_nl;
			$_cstr .= '<br>'.$_nl;

		# Output  data
			$_tstr	= 'Log In';
			$_out	= '<br>'.$_nl;
			$_out	.= do_install_block_it ($_tstr, $_cstr, 0, '', '1');
			$_tstr	= ''; $_cstr	= ''; $_mstr	= '';
			echo $_out;
		} ELSE {
			IF ( $_GPV['stage']==1 && $_db_check==1 ) {
				IF ( $_GPV['password'] == $_DBCFG['dbpass'] ) {

				# Build result string
					$_proceed = 1;
					$_cstr .= '<b>Login Results:</b><br>'.$_nl;
					$_cstr .= '&nbsp;&nbsp;- Login: Passed- proceeding with installation.............<br>'.$_nl;

				# Output  data
					$_tstr	= 'Log In';
					$_out	= '<br>'.$_nl;
					$_out	.= do_install_block_it ($_tstr, $_cstr, 0, '', '1');
					$_tstr	= ''; $_cstr	= ''; $_mstr	= '';
					echo $_out;
				} ELSE {

				# Build result string
					$_cstr .= '<b>Login Results:</b><br>'.$_nl;
					$_cstr .= '&nbsp;&nbsp;- Login: Failed- aborting.............<br>'.$_nl;
					$_cstr .= '<br>'.$_nl;

				# Create Login Form
					$_cstr .= '<table width="100%"><tr><td class="TP5MED_NC">'.$_nl;
					$_cstr .= '<b>Enter Your Database Password to Complete Installation:</b><br>'.$_nl;
					$_cstr .= '&nbsp;&nbsp;(installation will begin on clicking install- no additional prompts)<br>'.$_nl;
					$_cstr .= '<form action="'.$_SERVER["PHP_SELF"].'" method="post" name="login">'.$_nl;
					IF ($FullInstall && !$Resuming && !$InstalledVersion) {
						IF ( $_GPV['tbl_bak']==1 ) { $_set .= ' CHECKED'; }
						$_cstr .= '<INPUT TYPE=CHECKBOX NAME="tbl_bak" value="1"'.$_set.' border="0">'.$_nl;
						$_cstr .= '&nbsp;Check to rename existing tables with _bak.'.$_nl;
						$_cstr .= '<br>(existing will be dropped if unchecked)'.$_nl;
						$_cstr .= '<br><br>'.$_nl;
					} ELSE {
						$_cstr .= '<INPUT TYPE=hidden NAME="tbl_bak" value="0">'.$_nl;
					}
					$_cstr .= '<b>Password:&nbsp;&nbsp;<INPUT type="password" name="password" size="20" maxlength="20">'.$_nl;
					$_cstr .= '<INPUT TYPE=hidden name="stage" value="1">'.$_nl;
					$_cstr .= '<INPUT TYPE=SUBMIT value="Install">'.$_nl;
					$_cstr .= '</form>'.$_nl;
					$_cstr .= '</td></tr>'.$_nl;

					$_cstr .= '<tr><td class="TP5MED_NC">'.$_nl;
					$_cstr .= '<b>Installing this package indicates acceptance of accompanying '.$_nl;
					$_cstr .= '<a href="'.$_CCFG['_PKG_URL_BASE'].'coin_docs/license.txt" target="_blank">license</a></b>'.$_nl;
					$_cstr .= '</td></tr></table>'.$_nl;
					$_cstr .= '<br>'.$_nl;

				# Output  data
					$_tstr	= 'Log In';
					$_out	= '<br>'.$_nl;
					$_out	.= do_install_block_it ($_tstr, $_cstr, 0, '', '1');
					$_tstr	= ''; $_cstr	= ''; $_mstr	= '';
					echo $_out;
				}
			}
		}

	# Check proceed flag and go
		IF (!isset($_proceed)) {$_proceed = '';}
		IF ($_proceed) {
			$db_coin->db_set_suppress_errors(0);

			IF (!$Resuming) {

			# Get list of existing tables, and set "exist" flags
				$_cstr .= '<b>Checking for existing tables: Begin</b><br>'.$_nl;
				$result = mysql_list_tables($_DBCFG['dbname']);
				while(list($_TBL) = mysql_fetch_row($result)) {

			    # Let the user see what we'e doing
					$_cstr.= '&nbsp;&nbsp;"'.$_TBL.'"<br>'.$_nl;
					$_TBLEXIST[$_TBL] = 1;
				}
				IF ( $result) { $result = mysql_free_result($result); }
				$_cstr .= '<b>Checking for existing tables: Completed</b><br><br>'.$_nl;

			# Backup and/or delete existing tables
				$_cstr .= '<b>Backup and/or delete existing tables: Begin</b><br>'.$_nl;

			# How many tables are we working with for the upgrade/install?
				$ToDo = count($_TBL_NAMES);

			# Loop TBL_NAMES array and backup or delete each table
				FOR ($i = 0; $i < $ToDo; $i++) {

				# Process Table (without prefix): $_TBL_NAMES[$i]
					$_TBL_NAME 	= $_TBL_NAMES[$i];

				# Check exist flag and process
					IF (!isset($_TBLEXIST)) {$_TBLEXIST = array();}
					IF ( $_TBLEXIST[$_TBL_NAME] == 1 ) {

					# Check _bak option
						IF ( $_GPV['tbl_bak'] == 1) {

						# Drop existing _bak table
							$result 	= ''; $eff_rows = '';
							$query 	= "DROP TABLE IF EXISTS ".$_TBL_NAME."_bak";
							$result	= db_query_execute($query);
							IF ($result) {$_cstr .= '&nbsp;&nbsp;- Dropped existing '.$_TBL_NAME.'_bak table.<br>'.$_nl;}

						# Create the _bak table
							$result	= ''; $eff_rows = '';
							$query 	= "ALTER TABLE ".$_TBL_NAME." RENAME ".$_TBL_NAME."_bak";
							$result	= db_query_execute($query);
							IF ($result) {$_cstr .= '&nbsp;&nbsp;- Renamed the existing table to '.$_TBL_NAME.'_bak.<br>'.$_nl;}
						}

					# Drop existing table if re-installing
						IF ($FullInstall) {
							$result	= ''; $eff_rows = '';
							$query 	= "DROP TABLE IF EXISTS ".$_TBL_NAME;
							$result	= db_query_execute($query);
							IF ($result) {$_cstr .= '&nbsp;&nbsp;- Dropped existing table '.$_TBL_NAME.'<br>'.$_nl;}
						}
					}
				}
				$_cstr .= '<b>Backup and/or delete existing tables: Completed.</b><br><br>'.$_nl;

			# create our placeholder table
    	     	$result	= ''; $eff_rows = '';
			$query 	= "DROP TABLE IF EXISTS ".$_DBCFG['table_prefix']."install_status";
			$result	= db_query_execute($query);
			$query 	= "CREATE TABLE ".$_DBCFG['table_prefix']."install_status (id int(11) NOT NULL auto_increment, datafile varchar(75) NOT NULL default '', theline text NOT NULL, PRIMARY KEY (id)) TYPE=MyISAM COMMENT='Temporary table For tracking installation'";
			$result	= db_query_execute($query);
			$query 	= "INSERT INTO ".$_DBCFG['table_prefix']."install_status (id, datafile, theline) VALUES ('','','blank line');";
			$result	= db_query_execute($query);
			} # End Not resuming

		# Lets read the MySQL command file(s) and process it/them
			$NoGood = 0;				# No errors yet
			$LineNo = 0;
			$Loops = sizeof($SQL_Files);	# Number of command files available, if upgrading
			$_cstr .= '<b>Create or upgrade tables & populate: Begin</b><br>'.$_nl;

		# If new install, or overwriting the database,
			IF ($FullInstall || !$InstalledVersion) {
				$datafile = $_PACKAGE['DIR'] . 'coin_setup/sql/setup.sql';
				$fd = fopen("$datafile", "r");
				IF (!$fd) {
					$_cstr .= 'Cannot find SQL commands file<br> - '.$datafile.'<br>'.$_nl;
					$NoGood = 1;
				} ELSE {

			    # Let the user see what we are doing
    				$_cstr .= "&nbsp;&nbsp;&nbsp;Processing MySQL commands: setup.sql<br>";

				# Loop through the sql file until done
					while (!feof ($fd)) {
						$LineNo++;
						$buffer = fgets($fd, 8192);
						IF ($buffer) {$error = Do_The_SQL(rtrim($buffer),$datafile,$LineNo);}

					# Terminate on sql error
						IF ($error) {
							$FatalError = $error;
							break;
						}
					}
					fclose ($fd);
				}
			} ELSE {

			# Upgrading, so loop through each sql command file in turn
				FOR ($i=1; $i <= $Loops; $i++) {

				# get our command file version/name and break it apart
				    $field = explode("|",$SQL_Files[$i]);

				# create our "datafile" name
					$datafile = $_PACKAGE['DIR'] . 'coin_setup/sql/'.$field[1];

				# If it is for a higher version of phpCOIN than what is already installed, process it
					IF ($field[0] > $InstalledVersion) {

					# Let the user see what we are doing
						$_cstr .= "&nbsp;&nbsp;&nbsp;Processing MySQL commands: $field[1]<br>";

					# Open the datafile for reading
						$fd = fopen("$datafile", "r");

					# If an error opening then bug out
						IF (!$fd) {
							$_cstr .= 'Cannot find SQL commands file<br> - '.$datafile.'<br>'.$_nl;
							$NoGood = 1;
						} ELSE {

						# Else read a line at a time and send to routine to write to database
							$LineNo = 0;
							while (!feof ($fd)) {
								$LineNo++;
								$buffer = fgets($fd, 8192);
								IF ($buffer) {
									$error = Do_The_SQL(rtrim($buffer),$datafile,$LineNo);
								}
							# Terminate on sql error
								IF ($error) {
									$FatalError = $error;
									break;
								}
							}
							fclose ($fd);
						}
					}
				}
			}


		# We're done, so build results strings
			IF ($NoGood || $FatalError) {
    			$_cstr .= '<b>Create or upgrade tables and populate: Aborted</b><br><br>'.$_nl;
			} ELSE {

			# else everything was OK
    			$_cstr .= '<b>Create or upgrade tables and populate: Completed</b><br><br>'.$_nl;
			# drop our status table
				$query 	= "DROP TABLE IF EXISTS ".$_DBCFG['table_prefix']."install_status";
				$result	= db_query_execute($query);
			}

		# If full install or overwrite
			IF ($FullInstall || !$InstalledVersion) {
				$_tstr	= 'Database Schema Create and Populate';
				If (!$NoGood && !$FatalError) {
        		# If no errors, and new install or overwrite, show link to admin -> parameters -> user
					$_mstr = '<a href="'.$_CCFG['_PKG_URL_BASE'].'admin.php?cp=parms&fpg=user&w=admin&o=login&username=webmaster&password='.$_GPV['password'].'">'.$_TCFG['_IMG_ADMIN_M'].'</a>';
					$_cstr .= 'Installation Completed. Click the button below.';
				}
			} ELSE {

			# else it is an upgrade
				$_tstr	= 'Database Schema Upgrade and Populate';
				If (!$NoGood && !$FatalError) {
				# Else if no errors give link to home and admin
					$_mstr	= '<a href="'.$_CCFG['_PKG_URL_BASE'].'">'.$_TCFG['_IMG_HOME_M'].'</a>';
					$_mstr .= '<a href="'.$_CCFG['_PKG_URL_BASE'].'admin.php">'.$_TCFG['_IMG_ADMIN_M'].'</a>';
					$_cstr .= 'Installation Completed. Click one of the buttons below.';
				}
			}

		# If any errors
			IF ($NoGood || $FatalError) {
				IF ($NoGood) {
	   				$_cstr .= 'Please check the datafile location and try again.<br>'.$_nl;
				}
				IF ($FatalError) {
					$_cstr .= $FatalError;
				}
				$_cstr .= 'Fatal errors encountered. Installation Aborted.';
			}
			$_out	= '<br>'.$_nl;

		# Now show the results of all our hard work
			$_out	.= do_install_block_it ($_tstr, $_cstr, 1, $_mstr, '1');
			$_tstr	= ''; $_cstr	= ''; $_mstr	= '';
			echo $_out;

		} # proceed flag


		ELSE {
			IF ($FatalError) {
    			$_cstr = '<b>Upgrade to v'.$ThisVersion.': Aborted</b><br><br>'.$_nl;
    			$_out	= do_install_block_it ($_tstr, $_cstr, 1, "", '1');
    			echo $_out;
    		}
    	}


/**************************************************************
 * Close Page
**************************************************************/

	$_out	= do_install_page_close('1');
	echo $_out;



# For php < 4.3 compatability
# replaces html_entity_decode
function unhtmlentities($string) {
	 $trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}


###############################################################
#	Functions library
function error_block($block_title, $block_content) {
	global $_CCFG, $_GPV, $_nl, $_sp;

	# Build Table Start and title
		$_out  = '<html>'.$_nl;
		$_out .= '<head>'.$_nl;
		$_out .= '<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">'.$_nl;
		$_out .= '<meta name="generator" content="phpcoin">'.$_nl;
		$_out .= '<title>phpCOIN Installation/Upgrade Error</title>'.$_nl;
		$_out .= '<style media="screen" type="text/css">'.$_nl;
		$_out .= '<!--'.$_nl;
		$_out .= 'body	{ background-color: #FFFFFF; margin: 5px }'.$_nl;
		$_out .= 'p { color: #001; font-family: Verdana, Arial, Helvetica, Geneva }'.$_nl;
		$_out .= '.BLK_DEF_TITLE	{ font-family: Verdana, Arial, Helvetica, Geneva; background-color: #EBEBEB }'.$_nl;
		$_out .= '.BLK_DEF_ENTRY	{ font-family: Verdana, Arial, Helvetica, Geneva; background-color: #F5F5F5 }'.$_nl;
		$_out .= '.BLK_IT_TITLE	{ color: #001; font-style: normal; font-weight: bold; text-align: left; font-size: 12px; padding: 5px; height: 25px }'.$_nl;
		$_out .= '.BLK_IT_ENTRY	{ color: #001; font-style: normal; font-weight: normal; text-align: justify; font-size: 11px; padding: 5px }'.$_nl;
		$_out .= '.BLK_IT_FMENU	{ color: #001; font-style: normal; font-weight: normal; text-align: center; font-size: 11px; padding: 5px }'.$_nl;
		$_out .= '--></style>'.$_nl;
		$_out .= '</head>'.$_nl;
		$_out .= '<body link="blue" vlink="red">'.$_nl;
		$_out .= '<div align="center" width="100%">'.$_nl;
		$_out .= '<br>';
		$_out .= '<div align="center" width="100%">';
		$_out .= '<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#000000">';
		$_out .= '<tr bgcolor="#000000"><td bgcolor="#000000">';
		$_out .= '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
		$_out .= '<tr class="BLK_DEF_TITLE" height="30" valign="middle"><td class="BLK_IT_TITLE">';
		$_out .= $block_title;
		$_out .= '</td></tr>';
		$_out .= '<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY">';
		$_out .= $block_content;
		$_out .= '</td></tr>';
		$_out .= '<tr class="BLK_DEF_TITLE" valign="middle"><td class="BLK_IT_FMENU">';
		$_out .= '<a href="setup.php">Try Again</a>';
		$_out .= '</td></tr>';
		$_out .= '</table>';
		$_out .= '</td></tr>';
		$_out .= '</table>';
		$_out .= '</div>';
		$_out .= '</div>'.$_nl;
		$_out .= '</body>'.$_nl;
		$_out .= '</html>'.$_nl;

	# Echo final output
		echo $_out;
}

/**************************************************************
 * Function:	do_password_crypt ($apwrd_input)
 * Arguments:	$apwrd_input	- password string to encrypt
 * Returns:		encrypted password string
 * Description:	Function for encrypt passed string
 * Notes:
 *	-
**************************************************************/
function do_password_crypt ( $apwrd_input ) {
	# Generate and return encrypted password
		return crypt($apwrd_input);
}

/**************************************************************
 * Function:	do_mod_block_it ($atitle_text, $acontent_text, $ado_menu_flag=0, $abot_row_menu_text='', $aret_flag=0)
 * Function:	do_install_block_it ($atitle_text, $acontent_text, $ado_menu_flag=0, $abot_row_menu_text='', $aret_flag=0)
 * Arguments:	$atitle_text		- Block Title test
 *			$acontent_text		- Block Content
 *			$ado_menu_flag		- Bottom Row Menu Flag
 *			$abot_row_menu_text	- Bottom row text
 *			$aret_flag			- How To Handle Output- 1=return, 0=echo
 * Returns:	output return switchable
 * Description:	Function to build content html block for passed data
 * Notes:
 *	- Uses _WIDTH_CONTENT_AREA var for setting width
**************************************************************/
# Do html for mod content block (needed for db_api errors)
function do_mod_block_it ($atitle_text, $acontent_text, $ado_menu_flag=0, $abot_row_menu_text='', $aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp;

	$_out  = '<table width="100%" cellpadding="0" cellspacing="0" border="0">'.$_nl;
	$_out .= '<tr bgcolor="black"><td bgcolor="black">'.$_nl;
	$_out .= '<table border="0" cellpadding="5" cellspacing="1" width="100%">'.$_nl;
	$_out .= '<tr class="BLK_DEF_TITLE" valign="middle"><td class="BLK_IT_TITLE" colspan="2">'.$_nl;
	$_out .= $atitle_text.$_nl;
	$_out .= '</td></tr>'.$_nl;
	$_out .= '<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY" colspan="2">'.$_nl;
	$_out .= $acontent_text.$_nl;
	$_out .= '</td></tr>'.$_nl;
	IF ( $ado_menu_flag ) {
		$_out .= '<tr class="BLK_DEF_FMENU"><td class="BLK_IT_FMENU" align="center" valign="top" colspan="2">'.$_nl;
		$_out .= $abot_row_menu_text.$_nl;
		$_out .= '</td></tr>'.$_nl;
	}
	$_out .= '</table>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}

# Do html for standard content block
function do_install_block_it ($atitle_text, $acontent_text, $ado_menu_flag=0, $abot_row_menu_text='', $aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	$_out  = '<table width="100%" cellpadding="0" cellspacing="0" border="0">'.$_nl;
	$_out .= '<tr bgcolor="black"><td bgcolor="black">'.$_nl;
	$_out .= '<table border="0" cellpadding="5" cellspacing="1" width="100%">'.$_nl;
	$_out .= '<tr class="BLK_DEF_TITLE" valign="middle"><td class="BLK_IT_TITLE" colspan="2">'.$_nl;
	$_out .= $atitle_text.$_nl;
	$_out .= '</td></tr>'.$_nl;
	$_out .= '<tr class="BLK_DEF_ENTRY"><td class="BLK_IT_ENTRY" colspan="2">'.$_nl;
	$_out .= $acontent_text.$_nl;
	$_out .= '</td></tr>'.$_nl;
	IF ( $ado_menu_flag ) {
		$_out .= '<tr class="BLK_DEF_FMENU"><td class="BLK_IT_FMENU" align="center" valign="top" colspan="2">'.$_nl;
		$_out .= $abot_row_menu_text.$_nl;
		$_out .= '</td></tr>'.$_nl;
	}
	$_out .= '</table>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_title_block_it ($atitle_text, $aret_flag=0)
 * Arguments:	$atitle_text	- Block Text
 *				$aret_flag - How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build module subject block for passed data
 * Notes:
 *	- Uses _WIDTH_CONTENT_AREA var for setting width
**************************************************************/
# Do html for title content block
function do_install_title_block_it ($atitle_text, $aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	$_out  = '<table width="100%" cellpadding="0" cellspacing="0" border="0">'.$_nl;
	$_out .= '<tr bgcolor="black"><td bgcolor="black">'.$_nl;
	$_out .= '<table border="0" cellpadding="5" cellspacing="1" width="100%">'.$_nl;
	$_out .= '<tr class="BLK_DEF_TITLE" valign="middle"><td class="BLK_IT_TITLE" colspan="2">'.$_nl;
	$_out .= $atitle_text.$_nl;
	$_out .= '</td></tr>'.$_nl;
	$_out .= '</table>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_header($acomp_ptitle='phpCOIN', $aret_flag=0)
 * Arguments:	$acomp_ptitle	- Page title
 *				$aret_flag		- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build html for page "header"
 * Notes:
 *	- Opens initial system table and ready for first row (top_row)
**************************************************************/
function do_install_page_header($aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	$_out  = '<html>'.$_nl;
	$_out .= '<head>'.$_nl;
	$_out .= '<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">'.$_nl;
	$_out .= '<meta name="generator" content="phpcoin">'.$_nl;
	$_out .= '<title>phpCOIN Installation/Upgrade</title>'.$_nl;
	$_out .= '<link href="'.$_CCFG['_PKG_URL_THEME'].'styles.css" rel="styleSheet" type="text/css">'.$_nl;
	$_out .= '</head>'.$_nl;
	$_out .= '<body bgcolor="#00AFAF" link="#0000FF" vlink="#FF0000">'.$_nl;
	$_out .= '<div align="center" width="100%">'.$_nl;
	$_out .= '<!-- Outer Table- 1 Col- span 2-3 -->'.$_nl;
	$_out .= '<table border="0" bordercolor="black" cellpadding="0" cellspacing="0" width="600px">'.$_nl;
	$_out .= '<tr><td valign="top">'.$_nl;
	$_out .= '<!-- Inner Table- 2/3 Col add rules=none here -->'.$_nl;
	$_out .= '<table border="0" bordercolor="black" cellpadding="0" cellspacing="5" width="100%" rules="none">'.$_nl;
	$_out .= '<!-- End page_header -->'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_top_row($aret_flag=0)
 * Arguments:	$aret_flag		- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build html for page "top row"
 * Notes:
 *	-
**************************************************************/
function do_install_page_top_row ($aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	$_out  = '<tr height="40"><td colspan="2">'.$_nl;
	$_out .= do_install_page_top_block('1');
	$_out .= '</td></tr>'.$_nl;
	$_out .= '<!-- Start Content Column -->'.$_nl;
	$_out .= '<tr>'.$_nl;
	$_out .= '<td valign="top" align="center" width="100%">'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_top_block($aret_flag=0)
 * Arguments:	$aret_flag	- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build html for page "top block"
 * Notes:
 *	-
**************************************************************/
function do_install_page_top_block($aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	# Build Top Of Page Title Block
		$_out  = '<!-- Start topblock -->'.$_nl;
		$_out .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="black">'.$_nl;
		$_out .= '<table border="0" cellpadding="0" cellspacing="1" width="100%">'.$_nl;
		$_out .= '<tr class="BLK_HDR_TITLE" height="40px"><td class="TP3LRG_BL">'.$_nl;
		$_out .= 'phpCOIN Installation/Upgrade Program'.$_nl;
		$_out .= '</td></tr>'.$_nl;
		$_out .= '</table>'.$_nl;
		$_out .= '</td></tr></table>'.$_nl;
		$_out .= '<!-- End topblock -->'.$_nl;

	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}

/**************************************************************
 * Function:	do_install_page_footer_block($aret_flag=0)
 * Arguments:	$aret_flag		- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build html for page "footer"
 * Notes:
 *	-
**************************************************************/
function do_install_page_footer_block($aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl, $ThisVersion;

	$_out  = '</td>'.$_nl;
	$_out .= '</tr>'.$_nl;
	$_out .= '<!-- End Content Area : End Row 2 -->'.$_nl;

	$_out .= '<!-- Start Footer Row -->'.$_nl;
	$_out .= '<tr height="20"><td valign="middle" colspan="2">'.$_nl;
	$_out .= '<div align="center" valign="middle">'.$_nl;

	$_out .= '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>'.$_nl;
	$_out .= '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><td class="BLK_FTR_CLEAR_C" valign="middle">'.$_nl;

	$_out .= 'Powered By <a href="http://www.phpcoin.com" target="_blank">phpCOIN</a> v'.$ThisVersion.$_nl;
	$_out .= '</td></tr></table>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;

	$_out .= '</div>'.$_nl;
	$_out .= '</td></tr>'.$_nl;
	$_out .= '<!-- End Footer Row -->'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_closeout($aret_flag=0)
 * Arguments:	$aret_flag	- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build html for final page closeout
 * Notes:
 *	-
**************************************************************/
function do_install_page_closeout($aret_flag=0) {
	global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp, $_nl;

	$_out  = '<!-- Close Out Inner/Outer Table and Page Tags -->'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;
	$_out .= '</td></tr></table>'.$_nl;
	$_out .= '</div>'.$_nl;
	$_out .= '</body>'.$_nl;
	$_out .= '</html>'.$_nl;

	IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_open($aret_flag=0)
 * Arguments:	$aret_flag	- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build page html from starting tag
 *				to opening column for start of content.
 * Notes:
 *	-
**************************************************************/
function do_install_page_open($aret_flag=0) {
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp;

	# Call page header function-
		$_out = do_install_page_header('1');

	# Call page top row function-
		$_out .= do_install_page_top_row('1');

	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Function:	do_install_page_close($aret_flag=0)
 * Arguments:	$aret_flag	- How To Handle Output- 1=return, 0=echo
 * Returns:		output return switchable
 * Description:	Function to build page html from closeout of
 *				column for content to final page tag.
 * Notes:
 *	-
**************************************************************/
function do_install_page_close($acompdata, $aret_flag=0) {
		global $_CCFG, $_TCFG, $_DBCFG, $_UVAR, $_LANG, $_SERVER, $_sp;

	# Call footer block function- does copyright and tag close out
		$_out = do_install_page_footer_block('1');

	# Call page closeout function- does page tag close outs
		$_out .= do_install_page_closeout('1');

	# Return results
		IF ( $aret_flag ) { return $_out; } ELSE { echo $_out; }
}


/**************************************************************
 * Create some additional required functions
**************************************************************/
# Return current unix timestamp with offset:
function dt_get_uts() {
	global $_CCFG;
	If (!isset($_CCFG['_PKG_DATE_SERVER_OFFSET'])) {$_CCFG['_PKG_DATE_SERVER_OFFSET'] = 0;}
	return time()+($_CCFG['_PKG_DATE_SERVER_OFFSET']*3600);
}

# Make unix timestamp from passed date array:
function dt_make_uts($_dt) {
	global $_CCFG;
	return  mktime( $_dt['hour'],$_dt['minute'],$_dt['second'],$_dt['month'],$_dt['day'],$_dt['year']);
}

# Make unix timestamp from passed date string (mySQL stored yyyy-mm-dd hh:mm:ss):
function dt_make_uts_from_string($_dt) {
	global $_CCFG;
	$dt['year']	= substr($_dt,0,4);
	$dt['month']	= substr($_dt,5,2);
	$dt['day']	= substr($_dt,8,2);
	$dt['hour']	= substr($_dt,11,2);
	$dt['minute']	= substr($_dt,14,2);
	$dt['second']	= substr($_dt,17,2);
	return  mktime( $dt['hour'],$dt['minute'],$dt['second'],$dt['month'],$dt['day'],$dt['year']);
}

# Return current formatted datetime string based on unix timestamp and format passed (uses date() function):
function dt_get_datetime ( $_format='Y-m-d H:i:s' ) {
	# Format examples:
	#	long	- $_format='l- F d, Y @ h:i:s a T'
	#	short	- $_format='Y-m-d H:i:s'

	global $_CCFG;
	$_uts = time()+($_CCFG['_PKG_DATE_SERVER_OFFSET']*3600);
	return date($_format, $_uts);
}

# Make formatted datetime string based on unix timestamp and format passed (uses date() function):
function dt_make_datetime ( $_uts=0, $_format='Y-m-d H:i:s' ) {
	# Format examples:
	#	long	- $_format='l- F d, Y @ h:i:s a T'
	#	short	- $_format='Y-m-d H:i:s'
	return date($_format, $_uts);
}

# Make datetime array from passed unix timestamp :
function dt_make_datetime_array ( $_uts ) {
	$_dt = dt_make_datetime ( $_uts, 'Y-m-d H:i:s' );
	$dt['year']	= substr($_dt,0,4);
	$dt['month']	= substr($_dt,5,2);
	$dt['day']	= substr($_dt,8,2);
	$dt['hour']	= substr($_dt,11,2);
	$dt['minute']	= substr($_dt,14,2);
	$dt['second']	= substr($_dt,17,2);
	return  $dt;
}

# A single SQL statement is passed in.
# string replacement happens,
# the query *may* be executed,
# the installation progress database is updated,
# and an error string or "0" is returned.
function Do_The_SQL($sql,$thefile,$theline) {
	# Grab necessary global vars
		global $_SERVER, $_DBCFG;

	# Create some variables for auto-insert data
		$_time_stamp = dt_get_uts();
		$sql = str_replace("%DOMAINNAME%", $_SERVER["SERVER_NAME"], $sql);
		$sql = str_replace("%PASSWORD%", do_password_crypt($_DBCFG['dbpass']), $sql);
		$sql = str_replace("%PREFIX%", $_DBCFG['table_prefix'], $sql);
		$sql = str_replace("%TIMESTAMP%", $_time_stamp, $sql);

		$pieces = explode(".",$_SERVER["SERVER_NAME"]);
		$precedent = $pieces[0].'.';
		$EmailDomain = str_replace($precedent,"",$_SERVER["SERVER_NAME"]);
		$sql = str_replace("%MAILDOMAIN%", $EmailDomain, $sql);
		$sql2 = addslashes($sql);

	# See if we have already completed this command.
		$query		= "SELECT * FROM ".$_DBCFG['table_prefix']."install_status WHERE datafile='$thefile' AND theline='$sql2'";
		$result		= db_query_execute($query);
		$numrows	= db_query_numrows($result);

	# If this line exists it is already completed, so bug-out
		IF ($numrows) {return 0;}

	# Execute the query
		$sh = mysql_query("$sql");

	# Build result string
		$sh = strtolower($sh);
		$ErrorCode = mysql_errno();
		IF ($ErrorCode == 1060 && strpos($sh,"add column")) {
			# Duplicating column add
			$errorstring = 0;
		} ELSEIF ($ErrorCode == 1050 && strpos($sh,"reate table")) {
			# Duplicating create table
			$errorstring = 0;
		} ELSEIF ($ErrorCode == 1062 && strpos($sh,"nsert into")) {
			# Duplicating Insert record
			$errorstring = 0;
		} ELSEIF ($sh) {
			# No errors
			$errorstring = 0;
		} ELSE {
			# A fatal error that we are not trapping
			$errorstring  = 'Error '.mysql_errno().' running SQL command: '.mysql_error().'<br>';;
			$errorstring .= '&nbsp;&nbsp;&nbsp;In File: '.$thefile.'<br>';
			$errorstring .= '&nbsp;&nbsp;&nbsp;At Line: '.$theline.'<br>';
			$errorstring .= 'Please check the datafile mentioned above <i>or</i> ';
			$errorstring .= 'contact phpCOIN support staff for assistance, ';
			$errorstring .= 'making sure to tell them the file and line number ';
   			$errorstring .= 'that caused the problem.<br><br>';
		}

	# Update our installation status
		IF ($errorstring == 0) {
    		$query = "INSERT INTO ".$_DBCFG['table_prefix']."install_status (id, datafile, theline) VALUES('','$thefile', '$sql2')";
            $result	= db_query_execute($query);
		}

	# Return result string
		return $errorstring;
}

?>
