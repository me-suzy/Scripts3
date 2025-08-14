<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: db_backup.php,v 1.7 2002/05/15 05:34:49 verbic Exp $
#

require "../smarty.php";
require "../config.php";

#
# Dump database code
#
if($REQUEST_METHOD=="POST" && $mode=="backup") {

	require("./safe_mode.php");

#
# This function returns dump of the table
#
    function getTableContent($table)
    {
        $rows_cnt=0;
        $current_row=0;

        $local_query = "SELECT * FROM $table";
        $result = db_query($local_query);
        if ($result != FALSE) {
            $fields_cnt = mysql_num_fields($result);
            $rows_cnt   = mysql_num_rows($result);

            // Checks whether the field is an integer or not
            for ($j = 0; $j < $fields_cnt; $j++) {
                $field_set[$j] = mysql_field_name($result, $j);
                $type          = mysql_field_type($result, $j);
                if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                    $type == 'bigint'  ||$type == 'timestamp') {
                    $field_num[$j] = TRUE;
                } else {
                    $field_num[$j] = FALSE;
                }
            } // end for

            // Sets the scheme
            $schema_insert = "INSERT INTO $table VALUES (";
        
            $search       = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
            $replace      = array('\0', '\n', '\r', '\Z');
            $current_row  = 0;

            while ($row = mysql_fetch_row($result)) {
                $current_row++;
                for ($j = 0; $j < $fields_cnt; $j++) {
                    if (!isset($row[$j])) {
                        $values[]     = 'NULL';
                    } else if ($row[$j] == '0' || $row[$j] != '') {
                        // a number
                        if ($field_num[$j]) {
                            $values[] = $row[$j];
                        }
                        // a string
                        else {
                            $values[] = "'" . str_replace($search, $replace, addslashes($row[$j])) . "'";
                        }
                    } else {
                        $values[]     = "''";
                    } // end if
                } // end for

                // Extended inserts case
                $insert_line  = $schema_insert . implode(', ', $values) . ')';
                unset($values);

                // Call the handler
                $return.=$insert_line.";\n";

                // loic1: send a fake header to bypass browser timeout if data
                //        are bufferized
            } // end while
        } // end if ($result != FALSE)
        db_free_result($result);
    
        return $return;
    }


	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment; filename=db_backup.sql");

	$tables = db_query('show tables');
	while ($table = db_fetch_array($tables)) {
	$table = $table[0];
      $schema = "CREATE TABLE $table (\n";
      $table_list = '(';
      $fields = db_query("show fields from $table");
      while ($field = db_fetch_array($fields)) {
        $schema .= '  ' . $field['Field'] . ' ' . $field['Type'];
        if ($field['Null'] != 'YES') {
          $schema .= ' NOT NULL';
        }
        if ($field['Default']) {
          $schema .= ' default \'' . $field['Default'] . '\'';
        }
        if (isset($field['Extra'])) {
          $schema .= ' ' . $field['Extra'];
        }
        $schema .= ",\n";
        $table_list .= $field['Field'] . ', ';
      }
      $schema = ereg_replace(",\n$", "", $schema);
      $table_list = ereg_replace(", $", "", $table_list) . ')';
      // Add the keys
      $index = array();
      $keys = db_query("show keys from $table");
      while ($key = db_fetch_array($keys)) {
        $kname = $key['Key_name'];
        if(($kname != "PRIMARY") && ($key['Non_unique'] == 0)) {
          $kname = "UNIQUE|$kname";
        }
        if(!isset($index[$kname])) {
          $index[$kname] = array();
        }
        $index[$kname][] = $key['Column_name'];
      }
      while (list($x, $columns) = @each($index)) {
        $schema .= ",\n";
        if($x == "PRIMARY") {
          $schema .= "  PRIMARY KEY (" . implode($columns, ", ") . ")";
        } elseif (substr($x, 0, 6) == "UNIQUE") {
          $schema .= "  UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
        } else {
          $schema .= "  KEY $x (" . implode($columns, ", ") . ")";
        }
      }
      $schema .= "\n);";
      echo "$schema\n\n";
      // Dump the data
      echo getTableContent($table);
      echo "\n";
	}
	exit;
}

require "./auth.php";
require "../include/security.php";

#
# Restore database code
#
if($REQUEST_METHOD=="POST" && $mode=="restore" && $userfile!="none"&& $userfile!="") {

	require("./safe_mode.php");

 	set_time_limit(240);

	move_uploaded_file($userfile, "$file_temp_dir/$userfile_name");
	$userfile="$file_temp_dir/$userfile_name";

	$fp = fopen($userfile, "rb");
	$command = "";
	echo "Please wait...<br>\n";
	while (!feof($fp)) {
		$c = fgets($fp, 1500000);
		$c = chop($c);
		$c = ereg_replace("^[ \t]*#.*", "", $c);
		$command.=$c;
		if (ereg(";$",$command)) {
			$command=ereg_replace(";$","",$command);
			if (ereg("CREATE TABLE ", $command)) {
				$table_name = ereg_replace(" .*$", "", eregi_replace("^.*CREATE TABLE ", "", $command));
				echo "Restoring table: [$table_name] ...<br>\n";
				flush();
				db_query("drop table if exists $table_name");
			}
			db_query($command);
			$myerr = mysql_error ();
			if (!empty($myerr))
			{
				echo $myerr;
				break;
			}
			$command="";
			flush();
		}
	}
	fclose($fp);
	@unlink($userfile);
	echo "<p><b>Database has been restored successfully!</b><p><a href=\"db_backup.php\">Go back</a>";
	exit;
}


#
# Smarty display code goes here
#
$smarty->assign("main","db_backup");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
