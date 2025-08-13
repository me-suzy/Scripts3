<?php 
/*
***************************************************************************
Parameters :

required :
dbtable : any table ( shows this )
dbfield : any field .. etc (finds into this)

optional :
footer : 1 or 0 (disables header load when 0)
header : 1 or 0 (disables footer load when 0)
urled : what fileds to url'ize :)
dbfields : sort by this field
dbfieldv : string to search for
dbdate1 : year-month-day formatted date (starts listing from this date if given and if table has a date row )
dbdate2 : year-month-day formatted date (ends listing to this date if given and if table has a date row )

Notes :
(parameter order does not matter)
(it makes all lines starting with www. a link to a site and all email adresses a mail link)
***************************************************************************
*/
?>

<?php require("inc.layout.php"); ?>

<?php require("inc.auth.php"); ?>

<?php
if ($header == "1"){ include("inc.header.php"); echo $headersrc; }
if (!$header){  include("inc.header.php"); echo $headersrc;  }
if ($header == "0"){ echo "<link rel=stylesheet href=style.css>\n"; }
?>

<?php
//
//see if table has the dbfield field
//
$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
for ($x = 0; $x < $columns; $x++) {
	$dbfield_s[] = mysql_field_name($fields, $x);
}

//if table does not have asked field notice the user
//if (in_array ("$dbfield", $dbfield_s)) { } else {
//	echo "$dbtable does not have a $dbfield row";
//	die;
//}

//if table does not have asked field make dbfield the first field of the table
if (in_array ("$dbfield", $dbfield_s)) { } else {
	$result = mysql_query("SELECT * FROM $dbtable");
	$dbfield = mysql_field_name($result, 0);
}

//if no field to sort by is given default to the search field
if (!$dbfields){ $dbfields = $dbfield; }

//if no search string is given default to everything
if (!$dbfieldv){ $dbfieldv = "%"; }

//see if we must show by date
if (!$dbdate1 && !$dbdate2 ){
	$query0 = "SELECT * FROM $dbtable WHERE MONTH(date) >= MOD(MONTH(NOW()), 12) - 1 AND $dbfield LIKE '$dbfieldv' ORDER BY $dbfields";
	}else{
	$query0 = "SELECT * FROM $dbtable WHERE date >= '$dbdate1' AND date <= '$dbdate2' AND $dbfield LIKE '$dbfieldv' ORDER BY $dbfields";
}
$query1 = "SELECT * FROM $dbtable WHERE $dbfield LIKE '$dbfieldv' ORDER BY $dbfields";

//change querry if used table does not have a "Date" field
$fie_res = mysql_list_fields("$dbname", "$dbtable");
$columns = mysql_num_fields($fie_res);
for ($i = 0; $i < $columns; $i++) {
	$fie_lis[] = mysql_field_name($fie_res, $i); 
}
if (in_array("Date", $fie_lis)){ $query = $query0; } else { $query = $query1; }

//run query
$result = mysql_query($query) or die ("died while opening table<br>Debug info: $query");
?>

<table align=center width=97% cellpadding="0" cellspacing="1" >

<?php
//
//write table header
//
//
//Define tabletext
//
//if we dont get a date
if (!$dbdate1 && !$dbdate2 ){
	$timeline = "Last 2 months";
}else{
	$timeline = " $dbdate1 to $dbdate2";
}
$tabletext = "Viewing $dbtable Sorted by $dbfield From $timeline Matching $dbfieldv";
echo "<tr><td colspan=$columns class=tdark><center><b>$tabletext</b></center></td></tr>\n";

//write data
print "<tr>";
$fields = mysql_list_fields("$dbname", "$dbtable", $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
	$name = mysql_field_name($fields, $i);
	//take out id's
	if ($name != "Id" | $userlev == "developer"){
		print "<td class=tddd><a href=\"{$_SERVER['PHP_SELF']}?dbtable=$dbtable&dbfield=$dbfield&dbfieldv=$dbfieldv&dbfields=$name&urled=Division,Venue,League,Agegroup&dbdate1=$dbdate1&dbdate2=$dbdate2\">$name</a></td>"; //is also end tr

	}
	//define start tr
	if ($i == "0"){
		$naem = "$name";
	}
}
print "</tr>";


//write the formated table
while ($line = mysql_fetch_assoc($result)) {
  foreach($line as $col_name=> $col_value) {
	//make a link if row data is url or email adress
	if (preg_match("/www\..+?\.\w+?/", $col_value)){
	$col_value = "<a href=http://$col_value target=_blank>Go Visit!</a>";
	}
	if (preg_match("/\w+?@.+?\.\w+?/", $col_value)){
	$col_value = "<a href=mailto:$col_value>$col_value</a>";
	}
	//erase the "No ..." and ID enteries
	$urls = explode(",", $urled);
	foreach ($urls as $url){
	$s_string = "No $url";
		if (preg_match("/$s_string/", $col_value)){
			$col_value = "";
		}
	}
	//filter out the Id enteries
	if ($col_name != "Id" | $userlev == "developer"){
	     if (preg_match ("/$naem/", $col_name)){
			print "<tr><td class=td>$col_value&nbsp;</td>\n";
	     }
	     if (preg_match ("/$name/", $col_name)){
			print "<td class=td>$col_value&nbsp;</td></tr>\n";
	     }
	     if ($col_name != $naem && $col_name != $name){
			if (preg_match ("/$col_name/", $urled)){
			print "<td class=td><a href=\"show.php?dbtable={$col_name}s&dbfield=Name&dbfieldv=$col_value&footer=1&header=1&urled=$urled\">$col_value&nbsp;</a></td>\n";
	     		} else { print "<td class=td>$col_value&nbsp;</td>\n";}
	     }
	}
  }

}

//write the comand details if admin
if (in_array("$userlev", $admins)){
$command = "scripts/show.php?dbtable=$dbtable&dbfield=$dbfield&dbfieldv=$dbfieldv&dbfields=$dbfields&urled=Division,Venue,League,Agegroup&dbdate1=$dbdate1&dbdate2=$dbdate2";
echo "<tr><td colspan=$columns class=input><center><b>$command</b></center></td></tr>\n";
} 
?>

</table>

<?php
if ($footer == "1"){ echo $footersrc; include("inc.footer.php"); }
if (!$footer){  echo $footersrc; include("inc.footer.php");  }
if ($footer == "0"){ echo "<link rel=stylesheet href=style.css>\n"; }
?>