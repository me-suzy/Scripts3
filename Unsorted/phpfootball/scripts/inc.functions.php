<?
function do_backup ($dbtable,$fname){
	//get database data
	$query = "SELECT * FROM $dbtable";
	$result = mysql_query($query) or die ("Failed to get $dbtable data<br>Debug info: $query");
	//process data
	$v=1;
	while ($line = mysql_fetch_assoc($result)){
	   if ($line){
		foreach($line as $col_value) {
		$values[$v] .= "'".$col_value."',";
		}
		$v++;
	   }
	}
	//write backup file
	$fhw = fopen("$fname", "a");
	if ($values){
	  foreach ($values as $values_n=> $values_v){
		$values_v = substr($values_v, 0, -1);
		$values_v = "INSERT INTO $dbtable VALUES ($values_v);";
		fwrite ($fhw,"$values_v\r\n");
	  }
	}
	unset ($values);
}

function do_import ($fname){
	$handle = fopen($fname, "r");
	$contents = fread ($handle, filesize ($fname));
	$contents .= "\n\n"; 
	fclose ($handle); 
	$queries = explode(";", $contents); 
	$querycount = count($queries)-1; 
	for($i=0; $i < $querycount; $i++){ 
	$result = mysql_query($queries[$i]); 
		if(mysql_errno() != 0){  
		$err .= mysql_errno(). ':' .mysql_error().'<BR>'; 
		$error = 1;
		}
	}
	if ($error == 1){ echo $err;}
}

function rem_crlf($str){
    return strtr($str, "\015\012", ' ');
}

function do_chmod($fname){
   $perms = substr(base_convert(fileperms($fname), 10, 8), 3); 
   if ($perms != "666"){
	$chmod = chmod ("$fname", 0666);
		if (!$chmod){
			 echo "<b>Your server does not allow automatic file chmoding</b><br>";
			 echo "<b>You have to manually chmod 666 $fname/make it writable</b><br>";
			 die;
		}

   }
}

function get_homeurl(){
	$script_name = $_SERVER['SCRIPT_NAME'];
	preg_match ("/^(.*?)impexp.php$/",$script_name,$dir);
	$dir = $dir[1];
	$dom = $_SERVER['HTTP_HOST'];
	$home_url = "http://$dom$dir";
	return $home_url;
}

function get_filesize($fname){
	$size = filesize($fname);
	if ($size >= 1073741824){ $size = round($size / 1073741824 * 100) / 100 . ' Gb'; }
	elseif ($size >= 1048576){ $size = round($size / 1048576 * 100) / 100 . ' Mb'; }
	elseif ($size >= 1024){	$size = round($size / 1024 * 100) / 100 . ' Kb'; }
	elseif ($size > 0){ $size = $size . ' b'; }
	else{ $size = 'NA'; }
	return $size;
} 

function fix_path ($fname){
	$fname = preg_replace("/\\\\\\\/s","\\",$fname);
	return $fname;
}

?>
