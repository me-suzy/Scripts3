<?php
/**************************************************************
 * File: 		Control Panel: Backup MySQL Database
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	none
 * Notes:
 *	- Translation File: lang_admin.php
 *  - MUST be called BEFORE any output is sent
 *
**************************************************************/

# Include session file (loads core)
	require_once ("../coin_includes/session_set.php");

# Include db_config file & redirect for after we are done
	require_once($_PACKAGE['DIR'] . '/coin_includes/redirect.php');


# Do the backup IF it is an admin.
	$_SEC = get_security_flags();
	$_PERMS	= do_decode_perms_admin($_SEC['_sadmin_perms']);
	IF ($_SEC['_sadmin_flg'] && ($_PERMS['AP13'] == 1 || $_PERMS['AP16'] == 1)) {

	# Setup for download to desktop
		IF ($_POST['btype'] == "download") {
			IF ((is_integer (strpos($user_agent, "msie"))) && (is_integer (strpos($user_agent, "win")))) {
				header( "Content-disposition: filename=site-data_backup.sql");
			} ELSE {
				header( "Content-Disposition: attachment; filename=site-data_backup.sql");
			}
			Header("Content-type: application/octetstream");

	# Setup for backup to local directory
		} ELSEIF ($_POST['btype'] == "save") {
		    $today = date("F_j_Y");
    		$thearchive = $_CCFG['MYSQL_BACKUP_SAVE_DIR'] . '/phpcoin_' . $_SEC['_sadmin_name'] . '_' . $today . '.sql';
			$handle = fopen ("$thearchive", "w");

	# Setup for Emailing
		} ELSE {
			$headers  = 'From: '.$_CCFG['MYSQL_BACKUP_EMAIL_FROM_NAME'].' <'.$_CCFG['MYSQL_BACKUP_EMAIL_FROM_ADDRESS'].">\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: multipart/mixed; boundary=\"PHPCOIN-12345\"\n";
			$headers .= "Content-Transfer-Encoding: 7bit\n";
			$headers .= "X-Mailer: phpCOIN Backup\n";
			$headers .= "X-Priority: $MailPriority\n";
			$headers .= 'Reply-To: <'.$_CCFG['MYSQL_BACKUP_EMAIL_FROM_ADDRESS'].">\n";
			$headers .= "\n";

			$message = "This is a MIME encoded message\n";
			$message .= "--PHPCOIN-12345\n";
			$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
			$message .= "Content-Transfer-Encoding: 7bit\n";

			$message .= "\n";
			$message .= $_CCFG['MYSQL_BACKUP_EMAIL_BODY']."\n";
			$message .= "\n";

			$message .= "--PHPCOIN-12345\n";
			$message .= "Content-Type: text/plain\n";
			$message .= "Content-Transfer-Encoding: base64\n";
			$message .= "Content-Disposition: attachment; filename=\"phpcoin_dump.sql\"\n";
			$message .= "\n";
		}

	# Create the MySQL dump.
		$TheFile = '';
		mysql_connect($_DBCFG['dbhost'], $_DBCFG['dbuname'], $_DBCFG['dbpass']);
		mysql_select_db($_DBCFG['dbname']);
		$tables = mysql_query('show tables');
		WHILE ($table = mysql_fetch_array($tables)) {
			$table = $table[0];
			$schema = "drop table if exists $table;\n";
			$schema = "create table $table (\n";
			$table_list = '(';
			$fields = mysql_query("show fields from $table");
			WHILE ($field = mysql_fetch_array($fields)) {
				$schema .= '  ' . $field['Field'] . ' ' . $field['Type'];
				IF (isset($field['Default'])) {$schema .= ' default \'' . $field['Default'] . '\'';}
				IF ($field['Null'] != 'YES') {$schema .= ' not null';}
				IF (isset($field['Extra'])) {$schema .= ' ' . $field['Extra'];}
				$schema .= ",\n";
				$table_list .= $field['Field'] . ', ';
			}
			$schema = ereg_replace(",\n$", "", $schema);
			$table_list = ereg_replace(", $", "", $table_list) . ')';
			$index = array();
			$keys = mysql_query("show keys from $table");
			WHILE ($key = mysql_fetch_array($keys)) {
				$kname = $key['Key_name'];
				IF (($kname != "PRIMARY") && ($key['Non_unique'] == 0)) {$kname = "UNIQUE|$kname";}
				IF (!isset($index[$kname])) {$index[$kname] = array();}
				$index[$kname][] = $key['Column_name'];
			}
			WHILE (list($x, $columns) = @each($index)) {
				$schema .= ",\n";
				IF ($x == "PRIMARY") {
					$schema .= "  PRIMARY KEY (" . implode($columns, ", ") . ")";
				} ELSEIF (substr($x, 0, 6) == "UNIQUE") {
					$schema .= "  UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
				} ELSE {
					$schema .= "  KEY $x (" . implode($columns, ", ") . ")";
				}
			}
			$schema .= "\n);";
			IF ($_POST['btype'] == "download") {
				echo "$schema\n";
			} ELSE {
				$TheFile .= $schema;
			}
			$rows = mysql_query("select * from $table");
			WHILE ($row = mysql_fetch_array($rows)) {
				$schema_insert = "INSERT INTO $table $table_list VALUES (";
				WHILE (list($field) = each($row)) {
					list($field) = each($row);
					IF (!isset($row[$field])) {
						$schema_insert .= " NULL,";
					} ELSEIF ($row[$field] != "") {
						$schema_insert .= " '".addslashes($row[$field])."',";
					} ELSE {
						$schema_insert .= " '',";
					}
				}
				$schema_insert = ereg_replace(",$", "", $schema_insert);
				$schema_insert .= ")";
				IF ($_POST['btype'] == "download") {
	                echo trim($schema_insert) . ";\n";
				} ELSE {
					$TheFile .= trim($schema_insert) . ";\n";
				}
			}
			IF ($_POST['btype'] == "download") {
	            echo "\n";
			} ELSE {
				$TheFile .= "\n";
			}
		}

	# Process the MySQL dump
		IF ($_POST['btype'] == "save") {
			fwrite($handle,$TheFile);
			fclose($handle);
		}

	# Email The File
		IF ($_POST['btype'] == "email") {
			# Add the dumpfile
				$message .= chunk_split(base64_encode("$TheFile\n"));
				$message .= "\n";

			# Close message
       			$message .= "--PHPCOIN-12345--\n\n";

			# Send it
				mail("\"$_CCFG[MYSQL_BACKUP_EMAIL_TO_NAME]\" <$_CCFG[MYSQL_BACKUP_EMAIL_TO_ADDRESS]>", $_CCFG['MYSQL_BACKUP_EMAIL_SUBJECT'], $message, $headers);

			# redirect to "backed up" page
				html_header_location('mod.php?mod=pages&mode=view&id=3');
		}

		//	session_write_close();

} ELSE {
	// disallowed, so redirect to error page
	html_header_location('mod.php?mod=pages&mode=view&id=2');
}
?>
