<?php
function buildSelectList($tablename,$selectname) {
/* 
   Build a select list of all fields in a database table.
   The $tablename is the name of the table to use.
   The $selectname is the name of the select list to make.
   The select list name is referenced in other places, such as Find Needle in Haystack . . . 
*/
$query = "SELECT * FROM $tablename LIMIT 1";
if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
	if ($select = mysql_select_db(DBNAME, $link_identifier)) {
     	$queryResultHandle = mysql_query($query, $link_identifier) or die (mysql_error());
		$rowCount = mysql_num_rows ($queryResultHandle); 
		if ( $rowCount > 0 ) { // if there are rows then process them
         echo"<select name=\"$selectname\">\n";
			for ($i = 0; $i < mysql_num_fields($queryResultHandle); $i++) {
    		   echo("<option value=\"" . mysql_field_name($queryResultHandle,$i) . "\">" . mysql_field_name($queryResultHandle,$i) . "</option>\n");
			}
			echo"</select>\n";
		}
	}else{ // select
		echo mysql_error();
	} // select
}else{ //pconnect
	echo mysql_error();
} //pconnect	
} // this completes the function to build the dynamic select list
/* Highlight
   Thanks! to dave@[nospam]netready.biz
	Contributed code to: http://www.php.net/manual/en/function.str-replace.php
   The highlight function selects a needle in a haystack and marks it up with html.
*/
function highlight( $needle, $haystack ) {
    $parts = explode( strtolower($needle), strtolower($haystack) );
    $pos = 0;
    foreach( $parts as $key=>$part ){
        $parts[ $key ] = substr($haystack, $pos, strlen($part));
        $pos += strlen($part);
        $parts[ $key ] .= "<span class=\"needleFound\">" . substr($haystack, $pos,strlen($needle)) . "</span>";
        $pos += strlen($needle);
        }
    return( join( '', $parts ) );
}
function drawHeader(){ // used to reserve future right of Header invocation prior to printed output
print("<!DOCTYPE html public \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<HTML>\n<HEAD>\n");
print("<script language=\"Javascript\" type=\"text/javascript\" src=\"phpcheckout.js\">\n</script><script language=\"Javascript\" type=\"text/javascript\">loadCSSFile();</script></HEAD>\n<BODY><!-- START of Page -->\n");
} // end of function drawHeader()?>