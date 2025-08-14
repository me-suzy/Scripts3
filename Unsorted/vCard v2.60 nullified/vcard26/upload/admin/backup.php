<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
require('./lib.inc.php');
if (function_exists("set_time_limit")==1 and get_cfg_var("safe_mode")==0)
{
	@set_time_limit(0);
}

check_lvl_access($superuser);

function sqlAddslashes($str = '', $is_like = FALSE)
{
	if ($is_like)
	{
        	$str = str_replace('\\', '\\\\\\\\', $str);
        }else{
		$str = str_replace('\\', '\\\\', $str);
        }
        $str = str_replace('\'', '\\\'', $str);
        return $str;
}

// data dump functions
function sqldumptable($table) {
	global $DB_site;

	$nline = "\n";
	$dump = 'DROP TABLE IF EXISTS ' . $table . ';' . $nline;
	$dump .= 'CREATE TABLE ' . $table . '(' . $nline;
	$firstfield = 1;
	$fields_array = $DB_site->query("SHOW FIELDS FROM $table");
	
	while ($field = $DB_site->fetch_array($fields_array))
	{
		if (!$firstfield)
		{
			$dump .= ",\n";
		}else{
			$firstfield = 0;
		}
		$dump .= "   $field[Field] $field[Type]";
		if (isset($field['Default']) && $field['Default'] != '')
		{
                	$dump .= ' default \'' . sqlAddslashes($field['Default']) . '\'';
		}
		if ($field['Null'] != 'YES')
		{
			$dump .= ' NOT NULL';
		}
		if (!empty($field[Extra]))
		{
			$dump .= " $field[Extra]";
		}
	}
	$DB_site->free_result($fields_array);

	$keysindex_array = $DB_site->query("SHOW KEYS FROM $table");
	while ($key = $DB_site->fetch_array($keysindex_array))
	{
		$kname=$key['Key_name'];
		if ($kname != "PRIMARY" and $key['Non_unique'] == 0)
		{
			$kname="UNIQUE|$kname";
		}
		if(!is_array($index[$kname]))
		{
			$index[$kname] = array();
		}
		$index[$kname][] = $key['Column_name'];
	}
	$DB_site->free_result($keysindex_array);
	
	while(list($kname, $columns) = @each($index))
	{
		$dump .= ",\n";
		$colnames=implode($columns,",");
		if($kname == 'PRIMARY')
		{
			$dump .= "   PRIMARY KEY ($colnames)";
		}else{
			if (substr($kname,0,6) == 'UNIQUE')
			{
				$kname=substr($kname,7);
			}
			$dump .= "   KEY $kname ($colnames)";

		}
	}
	
	$dump .= "\n);\n\n";

	$rows = $DB_site->query("SELECT * FROM $table");
	$numfields=$DB_site->num_fields($rows);
	
	while ($row = $DB_site->fetch_array($rows))
	{
		$dump .= "INSERT INTO $table VALUES(";
		$fieldcounter=-1;
		$firstfield=1;
		while (++$fieldcounter<$numfields)
		{
			if(!$firstfield)
			{
				$dump.=',';
			}else{
				$firstfield=0;
			}
			if (!isset($row[$fieldcounter]))
			{
				$dump .= 'NULL';
			}else{
				$dump .= "'".mysql_escape_string($row[$fieldcounter])."'";
			}
		}
		$dump .= ");\n";
	}
	$DB_site->free_result($rows);
	return $dump;
}


if ($action == 'sqltable')
{

	//header("Content-disposition: filename=vcardpro.sql");
	//header("Content-type: unknown/unknown");
	$table = $DB_site->query("SHOW tables");
	unset($temp_buffer);
	while ($row = $DB_site->fetch_array($table))
	{
		if (!empty($row[0]))
		{
			$temp_buffer .= sqldumptable($row[0])."\n\n\n";
		}
	}
	/* show content */
	// zip file
	$file_name = 'vcardbackup-'.date("Y-m-d");
	if ($HTTP_POST_VARS['compress_type'] == 'zip')
	{
		if (VC_PHP_VERSION >= 40000 && @function_exists('gzcompress'))
		{
			$zipfile = new zipfile();
			$zipfile -> add_file($temp_buffer, $file_name .'.sql');
			header("Content-disposition: filename=$file_name.zip");
			header("Content-type: unknown/unknown");
			echo $zipfile -> file();
		}
	// bzipped file
	}elseif ($HTTP_POST_VARS['compress_type'] == 'bzip'){
		if(VC_PHP_VERSION >= 40004 && @function_exists('bzcompress'))
		{
			header("Content-disposition: filename=$file_name.bz2");
			header("Content-type: unknown/unknown");
			echo bzcompress($temp_buffer);
		} 
	// gzipped file
	}elseif( $HTTP_POST_VARS['compress_type'] == 'gzip'){
		if(VC_PHP_VERSION >= 40004 && @function_exists('gzencode'))
		{
			header("Content-disposition: filename=$file_name.gz");
			header("Content-type: unknown/unknown");
			// without the optional parameter level because it bug
			echo gzencode($temp_buffer);
		}
	// screen
	}else{
		header("Content-disposition: filename=$file_name.sql");
		header("Content-type: unknown/unknown");
		echo $temp_buffer;
	}
	exit;
}

dothml_pageheader();
if (empty($action))
{
dohtml_form_header("backup","sqltable");
dohtml_table_header("backup","$msg_admin_dbbkp_dbtoinclude");

	$result=$DB_site->query("SHOW tables");
	while ($currow=$DB_site->fetch_array($result))
	{
		dohtml_form_yesno($currow[0],"table[$currow[0]]",1);
	}
	// gzip and bzip2 encode features
	echo "<tr class=\"".get_row_bg()."\" valign=\"top\"><td colspan='2'> Save as file\n";
	if (VC_PHP_VERSION >= 40004)
	{
		$is_zip  = (@function_exists('gzcompress'))?1:0;
	        $is_gzip = (@function_exists('gzencode'))?1:0;
	        $is_bzip = (@function_exists('bzcompress'))?1:0;
	        if ($is_zip || $is_gzip || $is_bzip)
		{
			echo '(';
			if ($is_zip==1)
			{
				echo " <input type='radio' name='compress_type' value='zip'><b>zip</b>ped ";
			}
			if ($is_gzip==1)
			{
				echo " <input type='radio' name='compress_type' value='gzip'><b>gz</b>ipped ";
			}
			if ($is_bzip==1)
			{
				echo " <input type='radio' name='compress_type' value='bzip'>bizpped ";
			}
			echo ')';
	        }
	}
	echo '</td></tr>';
dohtml_form_infobox($msg_admin_dbbkp_dumpviaweb);
dohtml_form_footer($msg_admin_dbbkp_dobackup);


dohtml_form_header("backup","sqlfile");
dohtml_table_header("backup","$msg_admin_dbbkp_serverfile");
dohtml_form_input("Path and filename on server","filename","./vcardbackup-".date("Y-m-d",time()).".sql",0,60);
dohtml_form_infobox("<b>PHP must have access to write in THIS directory (vcard/admin/)</b> (chmod 0777)</p> This tool will create a backup file into your webserver from database name: <b>$dbName</b>");
dohtml_form_footer("Save File");

}

if ($HTTP_POST_VARS['action']=="sqlfile")
{
	$filehandle = fopen($HTTP_POST_VARS['filename'],"w");
	$result = $DB_site->query("SHOW tables");
	while ($row = $DB_site->fetch_array($result))
	{
		fwrite($filehandle,sqldumptable($row[0])."\n\n\n");
		echo "<p>Dumping $row[0]</p>";
	}
	fclose($filehandle);
	echo "<p><b>$msg_admin_dbbkp_sucessfully</b></p>";
}

dothml_pagefooter();
exit;
?>
