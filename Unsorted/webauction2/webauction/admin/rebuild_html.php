<?

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

function rebuild_html_file($table)
{
        switch($table) {
            case "countries" :
			$output_filename = "../includes/countries.inc.php";
                        $field_name = "country";
                        $array_name = "countries";
                        break;
            default :
                        break;
        }

	$sqlqry = "SELECT " . $field_name . " FROM " . $table . " ORDER BY " . $field_name . ";";
	$result = mysql_query ($sqlqry);

	$output = "<?\n";
	$output.= "$" . $array_name . " = array( \n";

	if ($result)
		$num_rows = mysql_num_rows($result);
	else
		$num_rows = 0;

	$i = 0;
	while($i < $num_rows){
		$value = mysql_result($result,$i, $field_name);
                $output .= "\"" . $value . "\"";
		$i++;
                if ($i < $num_rows)
			$output .= ",\n";
                else
                        $output .= "\n";
	}

        $output .= ");\n?>\n";

	$handle = fopen ( $output_filename , "w" );
	fputs ( $handle, $output );
	fclose ($handle);
}
?>

